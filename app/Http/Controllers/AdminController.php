<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display activity logs for admin
     */
    public function activityLogs(Request $request)
    {
        $query = ActivityLog::with(['user', 'customer'])
                           ->orderBy('created_at', 'desc');

        // Filter by user if specified
        if ($request->has('user_id') && $request->user_id !== '') {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action if specified
        if ($request->has('action') && $request->action !== '') {
            $query->where('action', $request->action);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from !== '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to !== '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $activityLogs = $query->paginate(50);

        // Get all users for filter dropdown
        $users = User::where('role', 'agent')->get();

        // Get distinct actions for filter dropdown
        $actions = ActivityLog::select('action')->distinct()->pluck('action');

        return view('admin.activity-logs', compact('activityLogs', 'users', 'actions'));
    }

    /**
     * Display statistics page for admin
     */
    public function statistics(Request $request)
    {
        // Get date range (default to last 30 days)
        $dateFrom = $request->input('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));

        // Overall statistics
        $totalCustomers = Customer::count();
        $totalAgents = User::where('role', 'agent')->count();

        // Status distribution
        $statusStats = Customer::select('status_fu', DB::raw('count(*) as count'))
                             ->groupBy('status_fu')
                             ->pluck('count', 'status_fu')
                             ->toArray();

        // Customers per agent
        $agentStats = User::where('role', 'agent')
                         ->withCount(['customers' => function ($query) use ($dateFrom, $dateTo) {
                             $query->whereBetween('created_at', [$dateFrom, $dateTo]);
                         }])
                         ->get();

        // Daily customer creation trend (last 30 days)
        $dailyStats = Customer::whereBetween('created_at', [$dateFrom, $dateTo])
                            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
                            ->groupBy('date')
                            ->orderBy('date')
                            ->get();

        // Follow-up statistics
        $followupStats = [
            'today' => Customer::whereDate('followup_date', today())->count(),
            'this_week' => Customer::whereBetween('followup_date', [
                now()->startOfWeek(), 
                now()->endOfWeek()
            ])->count(),
            'overdue' => Customer::where('followup_date', '<', today())
                               ->whereNotNull('followup_date')
                               ->count(),
        ];

        // Monthly performance by agent
        $monthlyPerformance = User::where('role', 'agent')
                                ->with(['customers' => function ($query) use ($dateFrom, $dateTo) {
                                    $query->whereBetween('created_at', [$dateFrom, $dateTo])
                                          ->select('user_id', 'status_fu', DB::raw('count(*) as count'))
                                          ->groupBy('user_id', 'status_fu');
                                }])
                                ->get();

        // Activity summary
        $activitySummary = ActivityLog::whereBetween('created_at', [$dateFrom, $dateTo])
                                    ->select('action', DB::raw('count(*) as count'))
                                    ->groupBy('action')
                                    ->pluck('count', 'action')
                                    ->toArray();

        // Top performing agents (based on hot leads)
        $topAgents = User::where('role', 'agent')
                        ->withCount(['customers as hot_leads' => function ($query) {
                            $query->where('status_fu', 'hot');
                        }])
                        ->orderBy('hot_leads', 'desc')
                        ->limit(5)
                        ->get();

        // Conversion funnel
        $conversionFunnel = [
            'total' => Customer::count(),
            'normal' => Customer::where('status_fu', 'normal')->count(),
            'warm' => Customer::where('status_fu', 'warm')->count(),
            'hot' => Customer::where('status_fu', 'hot')->count(),
            'closed' => Customer::whereNotNull('tanggal_closing')->count(),
        ];

        return view('admin.statistics', compact(
            'totalCustomers',
            'totalAgents',
            'statusStats',
            'agentStats',
            'dailyStats',
            'followupStats',
            'monthlyPerformance',
            'activitySummary',
            'topAgents',
            'conversionFunnel',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Get statistics data for AJAX requests (for charts)
     */
    public function getChartData(Request $request)
    {
        $type = $request->input('type');
        $dateFrom = $request->input('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));

        switch ($type) {
            case 'daily-customers':
                $data = Customer::whereBetween('created_at', [$dateFrom, $dateTo])
                              ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
                              ->groupBy('date')
                              ->orderBy('date')
                              ->get()
                              ->map(function ($item) {
                                  return [
                                      'date' => $item->date,
                                      'count' => $item->count
                                  ];
                              });
                break;

            case 'status-distribution':
                $data = Customer::select('status_fu', DB::raw('count(*) as count'))
                              ->groupBy('status_fu')
                              ->get()
                              ->map(function ($item) {
                                  return [
                                      'status' => ucfirst($item->status_fu),
                                      'count' => $item->count
                                  ];
                              });
                break;

            case 'agent-performance':
                $data = User::where('role', 'agent')
                          ->withCount([
                              'customers as total_customers',
                              'customers as hot_leads' => function ($query) {
                                  $query->where('status_fu', 'hot');
                              },
                              'customers as warm_leads' => function ($query) {
                                  $query->where('status_fu', 'warm');
                              }
                          ])
                          ->get()
                          ->map(function ($user) {
                              return [
                                  'name' => $user->name,
                                  'total' => $user->total_customers,
                                  'hot' => $user->hot_leads,
                                  'warm' => $user->warm_leads
                              ];
                          });
                break;

            case 'monthly-trend':
                $data = Customer::whereBetween('created_at', [$dateFrom, $dateTo])
                              ->select(
                                  DB::raw('YEAR(created_at) as year'),
                                  DB::raw('MONTH(created_at) as month'),
                                  DB::raw('count(*) as count')
                              )
                              ->groupBy('year', 'month')
                              ->orderBy('year')
                              ->orderBy('month')
                              ->get()
                              ->map(function ($item) {
                                  return [
                                      'period' => $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT),
                                      'count' => $item->count
                                  ];
                              });
                break;

            default:
                $data = [];
        }

        return response()->json($data);
    }

    /**
     * Export activity logs to CSV
     */
    public function exportActivityLogs(Request $request)
    {
        $query = ActivityLog::with(['user', 'customer'])
                           ->orderBy('created_at', 'desc');

        // Apply same filters as the view
        if ($request->has('user_id') && $request->user_id !== '') {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('action') && $request->action !== '') {
            $query->where('action', $request->action);
        }

        if ($request->has('date_from') && $request->date_from !== '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to !== '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $activityLogs = $query->get();

        $filename = 'activity-logs-' . now()->format('Y-m-d-H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($activityLogs) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Date',
                'Agent',
                'Customer',
                'Action',
                'Description'
            ]);

            foreach ($activityLogs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user->name,
                    $log->customer->nama ?? 'N/A',
                    $log->action,
                    $log->description
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate performance report
     */
    public function performanceReport(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));

        // Generate comprehensive performance data
        $performanceData = User::where('role', 'agent')
                             ->with(['customers' => function ($query) use ($dateFrom, $dateTo) {
                                 $query->whereBetween('created_at', [$dateFrom, $dateTo]);
                             }])
                             ->get()
                             ->map(function ($agent) {
                                 $customers = $agent->customers;
                                 
                                 return [
                                     'agent_name' => $agent->name,
                                     'total_customers' => $customers->count(),
                                     'normal_leads' => $customers->where('status_fu', 'normal')->count(),
                                     'warm_leads' => $customers->where('status_fu', 'warm')->count(),
                                     'hot_leads' => $customers->where('status_fu', 'hot')->count(),
                                     'closed_deals' => $customers->whereNotNull('tanggal_closing')->count(),
                                     'conversion_rate' => $customers->count() > 0 
                                         ? round(($customers->whereNotNull('tanggal_closing')->count() / $customers->count()) * 100, 2)
                                         : 0,
                                     'avg_followup_time' => $this->calculateAvgFollowupTime($customers),
                                 ];
                             });

        return view('admin.performance-report', compact(
            'performanceData',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Calculate average follow-up time for customers
     */
    private function calculateAvgFollowupTime($customers)
    {
        $followupTimes = $customers->filter(function ($customer) {
            return $customer->fu_ke_1 && $customer->created_at;
        })->map(function ($customer) {
            return $customer->created_at->diffInDays($customer->fu_ke_1);
        });

        return $followupTimes->count() > 0 ? round($followupTimes->avg(), 1) : 0;
    }

    /**
     * Get real-time dashboard metrics
     */
    public function getDashboardMetrics()
    {
        $metrics = [
            'total_customers' => Customer::count(),
            'customers_today' => Customer::whereDate('created_at', today())->count(),
            'followups_today' => Customer::whereDate('followup_date', today())->count(),
            'overdue_followups' => Customer::where('followup_date', '<', today())
                                         ->whereNotNull('followup_date')
                                         ->count(),
            'hot_leads' => Customer::where('status_fu', 'hot')->count(),
            'recent_activities' => ActivityLog::with(['user', 'customer'])
                                             ->latest()
                                             ->limit(5)
                                             ->get()
                                             ->map(function ($log) {
                                                 return [
                                                     'user' => $log->user->name,
                                                     'customer' => $log->customer->nama ?? 'N/A',
                                                     'action' => $log->action,
                                                     'time' => $log->created_at->diffForHumans()
                                                 ];
                                             })
        ];

        return response()->json($metrics);
    }
}