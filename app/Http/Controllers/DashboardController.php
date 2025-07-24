<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Customer;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            return $this->adminDashboard($request);
        } else {
            return $this->agentDashboard($request);
        }
    }

    private function agentDashboard(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $query = $user->customers();

        // Apply filters with validation
        $filters = $this->validateAndApplyFilters($request, $query);

        // Get customers with pagination
        $customers = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Get filter options for dropdowns
        $filterOptions = $this->getFilterOptions($user->customers());
        
        // Calculate stats with same filters
        $statsQuery = $user->customers();
        $this->applyFiltersToQuery($request, $statsQuery);
        
        $stats = $this->calculateStats($statsQuery);
        
        // Add agent-specific stats
        $stats['total_this_month'] = $user->customers()
            ->where('sheet_month', now()->format('M'))
            ->count();
        
        $stats['conversion_rate'] = $this->calculateConversionRate($statsQuery);

        return view('dashboard.agent', compact('customers', 'stats', 'filterOptions', 'filters'));
    }

    private function adminDashboard(Request $request)
    {
        // Get agents with customer counts
        $agentsQuery = User::where('role', 'agent')->withCount('customers');
        
        // Apply month filter to agent counts if specified
        if ($request->has('month') && $request->month !== '') {
            $agentsQuery->withCount(['customers as filtered_customers_count' => function($query) use ($request) {
                $query->where('sheet_month', $request->month);
            }]);
        }
        
        $agents = $agentsQuery->get();
        
        // Apply filters to customer query
        $customerQuery = Customer::query();
        $filters = $this->validateAndApplyFilters($request, $customerQuery);
        
        // Get filter options for dropdowns
        $filterOptions = $this->getFilterOptions(Customer::query());
        
        // Calculate admin stats
        $stats = $this->calculateStats($customerQuery);
        $stats['total_agents'] = $agents->count();
        $stats['avg_customers_per_agent'] = $agents->count() > 0 ? round($stats['total_customers'] / $agents->count(), 1) : 0;
        
        // Get agent performance data
        $agentPerformance = $this->getAgentPerformance($request);
        
        // Get monthly trends
        $monthlyTrends = $this->getMonthlyTrends($request);
        
        // Update agents data with filtered counts and customers
        if ($request->has('month') && $request->month !== '') {
            $agents->load(['customers' => function($query) use ($request) {
                $query->where('sheet_month', $request->month);
            }]);
        } else {
            $agents->load('customers');
        }

        return view('dashboard.admin', compact(
            'agents', 
            'stats', 
            'filterOptions', 
            'filters', 
            'agentPerformance', 
            'monthlyTrends'
        ));
    }

    private function validateAndApplyFilters(Request $request, $query)
    {
        $filters = [
            'status' => $request->get('status', ''),
            'month' => $request->get('month', ''),
            'agent' => $request->get('agent', ''),
            'interest' => $request->get('interest', ''),
            'offer' => $request->get('offer', ''),
            'followup_status' => $request->get('followup_status', ''),
            'date_from' => $request->get('date_from', ''),
            'date_to' => $request->get('date_to', ''),
            'search' => $request->get('search', ''),
            'fu_count' => $request->get('fu_count', ''),
            'has_phone' => $request->get('has_phone', ''),
            'has_email' => $request->get('has_email', ''),
            'closing_status' => $request->get('closing_status', ''),
        ];

        $this->applyFiltersToQuery($request, $query);

        return $filters;
    }

    private function applyFiltersToQuery(Request $request, $query)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        // Status filter
        if ($request->has('status') && $request->status !== '') {
            $query->where('status_fu', $request->status);
        }

        // Month filter
        if ($request->has('month') && $request->month !== '') {
            $query->where('sheet_month', $request->month);
        }

        // Agent filter (admin only)
        if ($request->has('agent') && $request->agent !== '' && $user->isAdmin()) {
            $query->where('user_id', $request->agent);
        }

        // Interest filter
        if ($request->has('interest') && $request->interest !== '') {
            $query->where('interest', 'like', '%' . $request->interest . '%');
        }

        // Offer filter
        if ($request->has('offer') && $request->offer !== '') {
            $query->where('offer', 'like', '%' . $request->offer . '%');
        }

        // Follow-up status filter
        if ($request->has('followup_status') && $request->followup_status !== '') {
            switch ($request->followup_status) {
                case 'today':
                    $query->where('followup_date', today());
                    break;
                case 'overdue':
                    $query->where('followup_date', '<', today());
                    break;
                case 'upcoming':
                    $query->where('followup_date', '>', today());
                    break;
                case 'no_followup':
                    $query->whereNull('followup_date');
                    break;
            }
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from !== '') {
            $query->where('created_at', '>=', Carbon::parse($request->date_from)->startOfDay());
        }
        if ($request->has('date_to') && $request->date_to !== '') {
            $query->where('created_at', '<=', Carbon::parse($request->date_to)->endOfDay());
        }

        // Search filter (nama, email, phone)
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        // Follow-up count filter
        if ($request->has('fu_count') && $request->fu_count !== '') {
            if ($request->fu_count === '0') {
                $query->where('fu_jumlah', 0);
            } elseif ($request->fu_count === '5+') {
                $query->where('fu_jumlah', '>=', 5);
            } else {
                $query->where('fu_jumlah', $request->fu_count);
            }
        }

        // Has phone filter
        if ($request->has('has_phone') && $request->has_phone !== '') {
            if ($request->has_phone === 'yes') {
                $query->whereNotNull('phone')->where('phone', '!=', '');
            } else {
                $query->where(function($q) {
                    $q->whereNull('phone')->orWhere('phone', '');
                });
            }
        }

        // Has email filter
        if ($request->has('has_email') && $request->has_email !== '') {
            if ($request->has_email === 'yes') {
                $query->whereNotNull('email')->where('email', '!=', '');
            } else {
                $query->where(function($q) {
                    $q->whereNull('email')->orWhere('email', '');
                });
            }
        }

        // Closing status filter
        if ($request->has('closing_status') && $request->closing_status !== '') {
            if ($request->closing_status === 'closed') {
                $query->whereNotNull('tanggal_closing')->where('tanggal_closing', '!=', '');
            } else {
                $query->where(function($q) {
                    $q->whereNull('tanggal_closing')->orWhere('tanggal_closing', '');
                });
            }
        }
    }

    private function getFilterOptions($baseQuery)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return [
            'availableMonths' => (clone $baseQuery)->whereNotNull('sheet_month')
                ->distinct()
                ->pluck('sheet_month')
                ->sort()
                ->values(),
            
            'availableAgents' => $user->isAdmin() 
                ? User::where('role', 'agent')->get(['id', 'name'])
                : collect(),
            
            'availableInterests' => (clone $baseQuery)->whereNotNull('interest')
                ->where('interest', '!=', '')
                ->distinct()
                ->pluck('interest')
                ->sort()
                ->values(),
            
            'availableOffers' => (clone $baseQuery)->whereNotNull('offer')
                ->where('offer', '!=', '')
                ->distinct()
                ->pluck('offer')
                ->sort()
                ->values(),
        ];
    }

    private function calculateStats($query)
    {
        $baseQuery = clone $query;
        
        return [
            'total_customers' => (clone $baseQuery)->count(),
            'normal' => (clone $baseQuery)->where('status_fu', 'normal')->count(),
            'warm' => (clone $baseQuery)->where('status_fu', 'warm')->count(),
            'hot' => (clone $baseQuery)->where('status_fu', 'hot')->count(),
            'normal_prospect' => (clone $baseQuery)->where('status_fu', 'normal(prospect)')->count(),
            'warm_potential' => (clone $baseQuery)->where('status_fu', 'warm(potential)')->count(),
            'hot_closeable' => (clone $baseQuery)->where('status_fu', 'hot(closeable)')->count(),
            'followup_today' => (clone $baseQuery)->where('followup_date', today())->count(),
            'followup_overdue' => (clone $baseQuery)->where('followup_date', '<', today())->count(),
            'followup_upcoming' => (clone $baseQuery)->where('followup_date', '>', today())->count(),
            'with_phone' => (clone $baseQuery)->whereNotNull('phone')->where('phone', '!=', '')->count(),
            'with_email' => (clone $baseQuery)->whereNotNull('email')->where('email', '!=', '')->count(),
            'closed_deals' => (clone $baseQuery)->whereNotNull('tanggal_closing')->where('tanggal_closing', '!=', '')->count(),
        ];
    }

    private function calculateConversionRate($query)
    {
        $total = (clone $query)->count();
        $closed = (clone $query)->whereNotNull('tanggal_closing')->where('tanggal_closing', '!=', '')->count();
        
        return $total > 0 ? round(($closed / $total) * 100, 1) : 0;
    }

    private function getAgentPerformance(Request $request)
    {
        $query = DB::table('customers')
            ->select('users.name', 'users.id')
            ->selectRaw('COUNT(*) as total_customers')
            ->selectRaw('SUM(CASE WHEN status_fu = "hot" OR status_fu = "hot(closeable)" THEN 1 ELSE 0 END) as hot_leads')
            ->selectRaw('SUM(CASE WHEN tanggal_closing IS NOT NULL AND tanggal_closing != "" THEN 1 ELSE 0 END) as closed_deals')
            ->selectRaw('SUM(CASE WHEN followup_date = CURDATE() THEN 1 ELSE 0 END) as followup_today')
            ->join('users', 'customers.user_id', '=', 'users.id')
            ->where('users.role', 'agent')
            ->groupBy('users.id', 'users.name');

        // Apply month filter if specified
        if ($request->has('month') && $request->month !== '') {
            $query->where('customers.sheet_month', $request->month);
        }

        return $query->get();
    }

    private function getMonthlyTrends(Request $request)
    {
        $query = DB::table('customers')
            ->select('sheet_month')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN status_fu IN ("hot", "hot(closeable)") THEN 1 ELSE 0 END) as hot_leads')
            ->selectRaw('SUM(CASE WHEN tanggal_closing IS NOT NULL AND tanggal_closing != "" THEN 1 ELSE 0 END) as closed_deals')
            ->whereNotNull('sheet_month')
            ->groupBy('sheet_month')
            ->orderBy('sheet_month');

        return $query->get();
    }

    public function followupToday(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $query = $user->isAdmin() 
            ? Customer::query() 
            : $user->customers();
            
        // Apply filters
        $this->applyFiltersToQuery($request, $query);
        
        // Get today's follow-ups
        $customers = $query->where('followup_date', today())
                          ->with('user')
                          ->orderBy('status_fu', 'desc') // Hot first
                          ->orderBy('fu_jumlah', 'desc') // More follow-ups first
                          ->get();
                          
        // Get filter options
        $filterOptions = $this->getFilterOptions($user->isAdmin() ? Customer::query() : $user->customers());
        
        // Get filters for view
        $filters = $this->validateAndApplyFilters($request, $query);

        return view('dashboard.followup-today', compact('customers', 'filterOptions', 'filters'));
    }

    public function export(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $query = $user->isAdmin() 
            ? Customer::with('user') 
            : $user->customers();
            
        // Apply same filters as dashboard
        $this->applyFiltersToQuery($request, $query);
        
        $customers = $query->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="customers_export_' . now()->format('Y-m-d_H-i-s') . '.csv"',
        ];
        
        $callback = function() use ($customers, $user) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            $csvHeaders = [
                'Tanggal', 'Nama', 'Email', 'Phone', 'Status FU', 'Sheet Month',
                'Interest', 'Offer', 'FU Jumlah', 'Follow-up Date', 'Tanggal Closing', 'Notes'
            ];
            
            if ($user->isAdmin()) {
                array_splice($csvHeaders, 2, 0, 'Agent'); // Insert Agent after Nama
            }
            
            fputcsv($file, $csvHeaders);
            
            // CSV Data
            foreach ($customers as $customer) {
                $row = [
                    $customer->tanggal,
                    $customer->nama,
                    $customer->email,
                    $customer->phone,
                    $customer->status_fu,
                    $customer->sheet_month,
                    $customer->interest,
                    $customer->offer,
                    $customer->fu_jumlah,
                    $customer->followup_date ? $customer->followup_date->format('Y-m-d') : '',
                    $customer->tanggal_closing,
                    $customer->notes,
                ];
                
                if ($user->isAdmin()) {
                    array_splice($row, 2, 0, $customer->user->name ?? ''); // Insert agent name
                }
                
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'customer_ids' => 'required|array',
            'customer_ids.*' => 'exists:customers,id',
            'action' => 'required|in:update_status,update_followup,add_notes',
            'status_fu' => 'nullable|in:normal,warm,hot,normal(prospect),warm(potential),hot(closeable)',
            'followup_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $query = $user->isAdmin() 
            ? Customer::whereIn('id', $request->customer_ids)
            : $user->customers()->whereIn('id', $request->customer_ids);

        $updateData = [];
        
        switch ($request->action) {
            case 'update_status':
                if ($request->status_fu) {
                    $updateData['status_fu'] = $request->status_fu;
                }
                break;
                
            case 'update_followup':
                if ($request->followup_date) {
                    $updateData['followup_date'] = $request->followup_date;
                }
                break;
                
            case 'add_notes':
                if ($request->notes) {
                    // This requires individual updates to append notes
                    $customers = $query->get();
                    foreach ($customers as $customer) {
                        $existingNotes = $customer->notes ? $customer->notes . "\n" : '';
                        $customer->update([
                            'notes' => $existingNotes . '[' . now()->format('Y-m-d H:i') . '] ' . $request->notes
                        ]);
                    }
                    
                    return back()->with('success', 'Catatan berhasil ditambahkan ke ' . $customers->count() . ' customer.');
                }
                break;
        }

        if (!empty($updateData)) {
            $updatedCount = $query->update($updateData);
            return back()->with('success', 'Berhasil mengupdate ' . $updatedCount . ' customer.');
        }

        return back()->with('error', 'Tidak ada data yang diupdate.');
    }
}