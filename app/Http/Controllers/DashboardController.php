<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            return $this->adminDashboard($request);
        }
        
        return $this->agentDashboard($request);
    }
    
    private function agentDashboard(Request $request)
    {
        $user = Auth::user();
        $query = Customer::where('user_id', $user->id)->active(); // Only show active customers
        
        // Filter berdasarkan pencarian nama
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $statusGroups = [
                'normal' => ['normal', 'normal(prospect)'],
                'warm' => ['warm', 'warm(potential)'],
                'hot' => ['hot', 'hot(closeable)']
            ];
            
            if (isset($statusGroups[$request->status])) {
                $query->whereIn('status_fu', $statusGroups[$request->status]);
            }
        }
        
        // Filter berdasarkan bulan sheet
        // if ($request->filled('month')) {
        //     $query->where('sheet_month', $request->month);
        // }
        
        // Filter berdasarkan status follow up
         if ($request->filled('followup_status')) {
        if ($request->followup_status === 'pending') {
            // Check both JSON followup_date and individual FU fields for today's date
            $today = Carbon::today()->toDateString();
            $query->where(function($q) use ($today) {
                // Check JSON followup dates
                $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(followup_date, '$[*].date')) LIKE ?", ["%{$today}%"])
                  // Check individual FU fields
                  ->orWhereDate('fu_ke_1', $today)
                  ->orWhereDate('fu_ke_2', $today)
                  ->orWhereDate('fu_ke_3', $today)
                  ->orWhereDate('fu_ke_4', $today)
                  ->orWhereDate('fu_ke_5', $today);
            });
        } elseif ($request->followup_status === 'overdue') {
            $today = Carbon::today();
            $query->where(function($q) use ($today) {
                // Check JSON followup dates for overdue
                $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(followup_date, '$[*].date')) < ? AND JSON_UNQUOTE(JSON_EXTRACT(followup_date, '$[*].completed')) != 'true'", [$today->toDateString()])
                  // Check individual FU fields for overdue (not completed)
                  ->orWhere(function($subQ) use ($today) {
                      $subQ->where('fu_ke_1', '<', $today)->where('fu_checkbox_1', '!=', true);
                  })
                  ->orWhere(function($subQ) use ($today) {
                      $subQ->where('fu_ke_2', '<', $today)->where('fu_checkbox_2', '!=', true);
                  })
                  ->orWhere(function($subQ) use ($today) {
                      $subQ->where('fu_ke_3', '<', $today)->where('fu_checkbox_3', '!=', true);
                  })
                  ->orWhere(function($subQ) use ($today) {
                      $subQ->where('fu_ke_4', '<', $today)->where('fu_checkbox_4', '!=', true);
                  })
                  ->orWhere(function($subQ) use ($today) {
                      $subQ->where('fu_ke_5', '<', $today)->where('fu_checkbox_5', '!=', true);
                  });
            });
        } elseif ($request->followup_status === 'completed') {
            $query->where(function($q) {
                $q->where('fu_checkbox_1', true)
                  ->orWhere('fu_checkbox_2', true)
                  ->orWhere('fu_checkbox_3', true)
                  ->orWhere('fu_checkbox_4', true)
                  ->orWhere('fu_checkbox_5', true)
                  // Also check for completed JSON followups
                  ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(followup_date, '$[*].completed')) = 'true'");
            });
        }
    }
    
    $customers = $query->orderBy('created_at', 'desc')->paginate(15);
    
    // Get all active customers for statistics calculation
    $allActiveCustomers = Customer::where('user_id', $user->id)->active()->get();
    
    // Statistics untuk dashboard
    $stats = [
        'total_customers' => $allActiveCustomers->count(),
        'normal_status' => $allActiveCustomers->whereIn('status_fu', ['normal', 'normal(prospect)'])->count(),
        'warm_status' => $allActiveCustomers->whereIn('status_fu', ['warm', 'warm(potential)'])->count(),
        'hot_status' => $allActiveCustomers->whereIn('status_fu', ['hot', 'hot(closeable)'])->count(),
        'followup_today' => $this->calculateFollowupToday($allActiveCustomers),
        'overdue_followup' => $this->calculateOverdueFollowup($allActiveCustomers),
        'archived_count' => Customer::where('user_id', $user->id)->archived()->count()
    ];
    
    // Available months untuk filter
    // $availableMonths = Customer::where('user_id', $user->id)->active()
    //     ->whereNotNull('sheet_month')
    //     ->distinct()
    //     ->pluck('sheet_month')
    //     ->sort();
    
    return view('dashboard.agent', compact('customers', 'stats'));
}

public function getNotes($id)
{
    $customer = Customer::findOrFail($id);

    $fu_notes = [];
    foreach (range(2, 5) as $i) {
        $field = "fu_{$i}_note";
        if ($customer->$field) {
            $fu_notes[] = $customer->$field;
        }
    }

    return response()->json([
        'report' => $customer->report,
        'fu_notes' => $fu_notes,
    ]);
}

/**
 * Calculate follow-up today count from both JSON and individual fields
 */
private function calculateFollowupToday($customers)
{
    $today = Carbon::today();
    $count = 0;
    
    foreach ($customers as $customer) {
        $hasFollowupToday = false;
        
        // Check JSON followup_date
        if ($customer->followup_date) {
            $followupDates = json_decode($customer->followup_date, true) ?? [];
            foreach ($followupDates as $dateObj) {
                try {
                    $date = Carbon::parse($dateObj['date']);
                    if ($date->isToday() && !($dateObj['completed'] ?? false)) {
                        $hasFollowupToday = true;
                        break;
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
        }
        
        // Check individual FU fields if no JSON followup found for today
        if (!$hasFollowupToday) {
            foreach(['fu_ke_1', 'fu_ke_2', 'fu_ke_3', 'fu_ke_4', 'fu_ke_5'] as $index => $fu_field) {
                if ($customer->$fu_field) {
                    try {
                        $date = Carbon::parse($customer->$fu_field);
                        $checkboxField = 'fu_checkbox_' . ($index + 1);
                        if ($date->isToday() && !$customer->$checkboxField) {
                            $hasFollowupToday = true;
                            break;
                        }
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }
        }
        
        if ($hasFollowupToday) {
            $count++;
        }
    }
    
    return $count;
}

/**
 * Calculate overdue follow-up count from both JSON and individual fields
 */
private function calculateOverdueFollowup($customers)
{
    $today = Carbon::today();
    $count = 0;
    
    foreach ($customers as $customer) {
        $hasOverdueFollowup = false;
        
        // Check JSON followup_date
        if ($customer->followup_date) {
            $followupDates = json_decode($customer->followup_date, true) ?? [];
            foreach ($followupDates as $dateObj) {
                try {
                    $date = Carbon::parse($dateObj['date']);
                    if ($date->isPast() && !$date->isToday() && !($dateObj['completed'] ?? false)) {
                        $hasOverdueFollowup = true;
                        break;
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
        }
        
        // Check individual FU fields if no JSON overdue found
        if (!$hasOverdueFollowup) {
            foreach(['fu_ke_1', 'fu_ke_2', 'fu_ke_3', 'fu_ke_4', 'fu_ke_5'] as $index => $fu_field) {
                if ($customer->$fu_field) {
                    try {
                        $date = Carbon::parse($customer->$fu_field);
                        $checkboxField = 'fu_checkbox_' . ($index + 1);
                        if ($date->isPast() && !$date->isToday() && !$customer->$checkboxField) {
                            $hasOverdueFollowup = true;
                            break;
                        }
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }
        }
        
        if ($hasOverdueFollowup) {
            $count++;
        }
    }
    
    return $count;
}
    
    private function adminDashboard(Request $request)
    {
        // Statistics untuk admin
        $stats = [
            'total_customers' => Customer::active()->count(),
            'total_agents' => \App\Models\User::where('role', 'agent')->count(),
            'normal_status' => Customer::active()->whereIn('status_fu', ['normal', 'normal(prospect)'])->count(),
            'warm_status' => Customer::active()->whereIn('status_fu', ['warm', 'warm(potential)'])->count(),
            'hot_status' => Customer::active()->whereIn('status_fu', ['hot', 'hot(closeable)'])->count(),
        //    'followup_today' => Customer::whereJsonContains('followup_date', Carbon::today()->toDateString())->count(),
            'closed_deals' => Customer::active()->whereNotNull('tanggal_closing')->count(),
            'archived_count' => Customer::archived()->count()
        ];
        
        // Data per agent
        $agentStats = \App\Models\User::where('role', 'agent')
            ->withCount([
                'customers' => function($query) {
                    $query->active();
                },
                'customers as normal_count' => function($query) {
                    $query->active()->whereIn('status_fu', ['normal', 'normal(prospect)']);
                },
                'customers as warm_count' => function($query) {
                    $query->active()->whereIn('status_fu', ['warm', 'warm(potential)']);
                },
                'customers as hot_count' => function($query) {
                    $query->active()->whereIn('status_fu', ['hot', 'hot(closeable)']);
                },
                'customers as closed_count' => function($query) {
                    $query->active()->whereNotNull('tanggal_closing');
                },
                'customers as archived_count' => function($query) {
                    $query->archived();
                }
            ])->get();
        
        // Chart data
        $chartData = [
            'status_distribution' => [
                'labels' => ['Normal', 'Warm', 'Hot'],
                'data' => [$stats['normal_status'], $stats['warm_status'], $stats['hot_status']]
            ],
            'agent_performance' => [
                'labels' => $agentStats->pluck('name')->toArray(),
                'data' => $agentStats->pluck('customers_count')->toArray()
            ]
        ];
        
        return view('dashboard.admin', compact('stats', 'agentStats', 'chartData'));
    }
    
    public function followupToday()
    {
        $user = Auth::user();
        $today = Carbon::today()->format('Y-m-d'); // 2025-08-22
        
        // Query untuk mencari customers yang memiliki follow-up hari ini
        $query = Customer::active()
            ->where(function ($q) use ($today) {
                // Cek fu_ke_1 (first follow-up)
                $q->where('fu_ke_1', $today)
                // Cek next_fu_2 sampai next_fu_5
                ->orWhere('next_fu_2', $today)
                ->orWhere('next_fu_3', $today)
                ->orWhere('next_fu_4', $today)
                ->orWhere('next_fu_5', $today);
            });
        
        $stats = [
            'archived_count' => Customer::where('user_id', $user->id)->archived()->count()
        ];
        
        // Jika user adalah agent, filter berdasarkan user_id
        if ($user->role === 'agent') {
            $query->where('user_id', $user->id);
        }
        
        $customers = $query->orderBy('created_at', 'desc')->get();
        
        Log::info('Final customers found for followup today: ' . $customers->count());
        
        return view('dashboard.followup-today', compact('customers', 'stats'));
    }

    public function archived(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        
        $query = Customer::archived();

        
        $stats = [
        'archived_count' => Customer::where('user_id', $user->id)->archived()->count(),
        'followup_today' => Customer::where('user_id', $user->id)
        ->where('is_archived', 0)
        ->where(function ($query) use ($today) {
            $query->whereRaw("JSON_CONTAINS(followup_date, '{\"date\": \"$today\"}')");
        })
        ->count()
    ];

        
        if ($user->role === 'agent') {
            $query->where('user_id', $user->id);
        }
        
        // Filter berdasarkan pencarian nama
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }
        
        $customers = $query->with(['archivedBy'])
            ->orderBy('archived_at', 'desc')
            ->paginate(15);
        
        return view('dashboard.archived', compact('customers','stats'));
    }
    
    public function updateCustomer(Request $request, Customer $customer)
    {
        $user = Auth::user();
        
        // Pastikan agent hanya bisa update customer miliknya
        if ($user->role === 'agent' && $customer->user_id !== $user->id) {
            abort(403);
        }
        
        $request->validate([
            'notes' => 'nullable|string',
            'followup_date' => 'nullable|array',
            'followup_date.*.date' => 'nullable|date',
            'fu_checkbox' => 'boolean'
        ]);
        
        $oldData = $customer->toArray();
        
        $updateData = [
            'notes' => $request->notes,
            'followup_date' => json_encode($request->input('followup_date', [])),
            'fu_checkbox' => $request->has('fu_checkbox')
        ];

        Log::info('Update Customer Data:', [
            'customer_id' => $customer->id,
            'update_data' => $updateData
        ]);
        
        // Update customer
        try {
            $customer->update($updateData);
            // Log data setelah update
            Log::info('Customer Updated:', [
                'customer_id' => $customer->id,
                'new_data' => $customer->fresh()->toArray()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to Update Customer:', [
                'customer_id' => $customer->id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Failed to update customer: ' . $e->getMessage());
        }
        
        // Log aktivitas
        ActivityLog::create([
            'user_id' => $user->id,
            'customer_id' => $customer->id,
            'action' => 'updated',
            'description' => 'Customer data updated manually',
            'old_data' => $oldData,
            'new_data' => $customer->fresh()->toArray()
        ]);
        
        return back()->with('success', 'Customer updated successfully');
    }

    public function markCompleted(Customer $customer)
    {
        $user = Auth::user();
        
        // Pastikan agent hanya bisa update customer miliknya
        if ($user->role === 'agent' && $customer->user_id !== $user->id) {
            abort(403);
        }
        
        $oldData = $customer->toArray();
        
        $customer->update([
            'fu_checkbox' => true
        ]);
        
        // Log aktivitas
        ActivityLog::create([
            'user_id' => $user->id,
            'customer_id' => $customer->id,
            'action' => 'status_changed',
            'description' => 'Marked follow-up as completed',
            'old_data' => $oldData,
            'new_data' => $customer->fresh()->toArray()
        ]);
        
        return back()->with('success', 'Follow-up marked as completed');
    }

    public function archiveCustomer(Customer $customer)
    {
        $user = Auth::user();
        
        // Pastikan agent hanya bisa archive customer miliknya
        if ($user->role === 'agent' && $customer->user_id !== $user->id) {
            abort(403);
        }
        
        $oldData = $customer->toArray();
        
        $customer->archive($user->id);
        
        // Log aktivitas
        ActivityLog::create([
            'user_id' => $user->id,
            'customer_id' => $customer->id,
            'action' => 'archived',
            'description' => 'Customer moved to archive',
            'old_data' => $oldData,
            'new_data' => $customer->fresh()->toArray()
        ]);
        
        return back()->with('success', 'Customer has been archived');
    }

    public function restoreCustomer(Customer $customer)
    {
        $user = Auth::user();
        
        // Pastikan agent hanya bisa restore customer miliknya
        if ($user->role === 'agent' && $customer->user_id !== $user->id) {
            abort(403);
        }
        
        $oldData = $customer->toArray();
        
        $customer->restore();
        
        // Log aktivitas
        ActivityLog::create([
            'user_id' => $user->id,
            'customer_id' => $customer->id,
            'action' => 'restored',
            'description' => 'Customer restored from archive',
            'old_data' => $oldData,
            'new_data' => $customer->fresh()->toArray()
        ]);
        
        return back()->with('success', 'Customer has been restored');
    }
}