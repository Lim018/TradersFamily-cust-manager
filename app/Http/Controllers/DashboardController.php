<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\ActivityLog;
use App\Models\Maintain;
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

    private function calculateFollowupTodayPending($customers)
    {
        $today = Carbon::today()->format('Y-m-d');
        $count = 0;
        
        foreach ($customers as $customer) {
            if ($customer->hasPendingFollowupToday()) {
                $count++;
            }
        }
        
        return $count;
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
        $today = Carbon::today()->format('Y-m-d');
        $count = 0;
        
        foreach ($customers as $customer) {
            $hasOverdueFollowup = false;
            
            // Check fu_ke_1 (overdue if past today)
            if ($customer->fu_ke_1 && $customer->fu_ke_1 < $today) {
                $hasOverdueFollowup = true;
            }
            
            // Check next_fu_2 to next_fu_5 (only if not completed and past today)
            if (!$hasOverdueFollowup) {
                for ($i = 2; $i <= 5; $i++) {
                    $fuField = "next_fu_{$i}";
                    $checkedField = "fu_{$i}_checked";
                    
                    if ($customer->$fuField && $customer->$fuField < $today && !$customer->$checkedField) {
                        $hasOverdueFollowup = true;
                        break;
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
        $today = Carbon::today()->format('Y-m-d');
        
        // Query untuk mencari customers yang memiliki follow-up hari ini
        $query = Customer::active()
            ->where(function ($q) use ($today) {
                $q->where('fu_ke_1', $today)
                ->orWhere('next_fu_2', $today)
                ->orWhere('next_fu_3', $today)
                ->orWhere('next_fu_4', $today)
                ->orWhere('next_fu_5', $today);
            });
        
        // Jika user adalah agent, filter berdasarkan user_id
        if ($user->role === 'agent') {
            $query->where('user_id', $user->id);
        }
        
        // Order: pending follow-ups first, completed follow-ups last
        $customers = $query->orderByRaw("
            CASE 
                WHEN fu_ke_1 = ? THEN 0
                WHEN next_fu_2 = ? AND fu_2_checked = 0 THEN 0
                WHEN next_fu_3 = ? AND fu_3_checked = 0 THEN 0
                WHEN next_fu_4 = ? AND fu_4_checked = 0 THEN 0
                WHEN next_fu_5 = ? AND fu_5_checked = 0 THEN 0
                ELSE 1
            END, 
            CASE 
                WHEN fu_ke_1 = ? THEN 1
                WHEN next_fu_2 = ? THEN 2
                WHEN next_fu_3 = ? THEN 3
                WHEN next_fu_4 = ? THEN 4
                WHEN next_fu_5 = ? THEN 5
                ELSE 6
            END,
            created_at DESC
        ", array_fill(0, 10, $today))->get();
        
        $stats = [
            'archived_count' => Customer::where('user_id', $user->id)->archived()->count(),
            'total_followups' => $customers->count(),
            'pending_followups' => $customers->filter(function($customer) {
                return $customer->hasPendingFollowupToday();
            })->count(),
            'completed_followups' => $customers->filter(function($customer) {
                return $customer->hasCompletedFollowupToday();
            })->count()
        ];
        
        Log::info('Followup Today Query Results', [
            'total_customers' => $customers->count(),
            'pending_count' => $stats['pending_followups'],
            'completed_count' => $stats['completed_followups']
        ]);
        
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

    public function markFollowupCompleted(Customer $customer, $followupNumber)
    {
        $user = Auth::user();
        
        // Pastikan agent hanya bisa update customer miliknya
        if ($user->role == 'agent' && $customer->user_id != $user->id) {
            abort(403);
        }
        
        // Validate followup number
        if ($followupNumber < 2 || $followupNumber > 5) {
            return back()->with('error', 'Invalid follow-up number');
        }
        
        // Check if this follow-up is scheduled for today
        $today = Carbon::today()->format('Y-m-d');
        $fuField = "next_fu_{$followupNumber}";
        
        if ($customer->$fuField !== $today) {
            return back()->with('error', 'This follow-up is not scheduled for today');
        }
        
        $oldData = $customer->toArray();
        $checkedField = "fu_{$followupNumber}_checked";
        
        // Mark as completed
        $customer->update([
            $checkedField => true
        ]);
        
        // Log aktivitas
        ActivityLog::create([
            'user_id' => $user->id,
            'customer_id' => $customer->id,
            'action' => 'followup_completed',
            'description' => "Follow-up #{$followupNumber} marked as completed for " . Carbon::today()->format('d M Y'),
            'old_data' => $oldData,
            'new_data' => $customer->fresh()->toArray()
        ]);
        
        return back()->with('success', "Follow-up #{$followupNumber} marked as completed");
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

    public function updateFollowup(Request $request, Customer $customer)
    {
        $user = Auth::user();
        
        // Pastikan agent hanya bisa update customer miliknya
        if ($user->role === 'agent' && $customer->user_id !== $user->id) {
            abort(403);
        }
        
        $request->validate([
            'followup_number' => 'required|integer|between:1,5',
            'notes' => 'nullable|string|max:1000',
            'next_followup_date' => 'nullable|date|after:today',
            'mark_completed' => 'boolean'
        ]);
        
        $followupNumber = $request->followup_number;
        $oldData = $customer->toArray();
        $updateData = [];
        
        // Update notes if provided
        if ($request->filled('notes') && $followupNumber >= 2) {
            $noteField = "fu_{$followupNumber}_note";
            $updateData[$noteField] = $request->notes;
        }
        
        // Mark as completed if requested and followup number >= 2
        if ($request->mark_completed && $followupNumber >= 2) {
            $checkedField = "fu_{$followupNumber}_checked";
            $updateData[$checkedField] = true;
        }
        
        // Set next follow-up date if provided
        if ($request->filled('next_followup_date')) {
            $nextFollowupNumber = $followupNumber + 1;
            if ($nextFollowupNumber <= 5) {
                $nextFuField = "next_fu_{$nextFollowupNumber}";
                $updateData[$nextFuField] = $request->next_followup_date;
                
                // Increment follow-up count
                $updateData['fu_jumlah'] = $customer->fu_jumlah + 1;
            }
        }
        
        if (!empty($updateData)) {
            $customer->update($updateData);
            
            // Log aktivitas
            ActivityLog::create([
                'user_id' => $user->id,
                'customer_id' => $customer->id,
                'action' => 'followup_updated',
                'description' => "Follow-up #{$followupNumber} updated",
                'old_data' => $oldData,
                'new_data' => $customer->fresh()->toArray()
            ]);
            
            return back()->with('success', 'Follow-up updated successfully');
        }
        
        return back()->with('error', 'No changes made');
    }

    public function archiveKeep(Request $request)
    {
        $search = $request->query('search');
        $keepQuery = Customer::where('is_archived', true)
            ->when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
            });
        $keep = $keepQuery->paginate(10);

        $stats = ['followup_today' => Customer::whereNotNull('next_fu_2')->count()];

        return view('dashboard.archive_keep', compact('keep', 'stats'));
    }

    public function archiveMaintain(Request $request)
    {
        $search = $request->query('search');
        $maintainQuery = Maintain::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%")
                        ->orWhere('agent_code', 'like', "%{$search}%");
        });
        
        $maintain = $maintainQuery->paginate(10);

         $stats = [
        'followup_today' => Customer::where('is_archived', true)
            ->whereNotNull('next_fu_2')
            ->count()
    ];

    return view('dashboard.archive_maintain', compact('maintain', 'stats'));
    }

    public function archiveCustomer(Customer $customer)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($user->role == 'agent' && $customer->user_id != $user->id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        // Kalau sudah diarsip, stop
        if ($customer->is_archived) {
            return back()->with('error', 'Customer is already archived');
        }

        // Arsipkan (ubah jadi 1)
        $customer->update(['is_archived' => 1]);

        // Log aktivitas
        ActivityLog::create([
            'user_id'     => $user->id,
            'customer_id' => $customer->id,
            'action'      => 'archived',
            'description' => 'Customer moved to archive',
            'old_data'    => $customer->getOriginal(),
            'new_data'    => $customer->toArray()
        ]);

        // Setelah berhasil → pindahkan user ke halaman archive_maintain
    return back()->with('success', 'Customer has been archived');
    }


    public function restoreCustomer(Customer $customer)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($user->role == 'agent' && $customer->user_id != $user->id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        if (!$customer->is_archived) {
            return back()->with('error', 'Customer is not archived');
        }

        $oldData = $customer->toArray();
        $customer->update([
            'is_archived' => false,
            'archived_at' => null,
            'archived_by' => null
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'customer_id' => $customer->id,
            'action' => 'restored',
            'description' => 'Customer restored from archive',
            'old_data' => $oldData,
            'new_data' => $customer->fresh()->toArray()
        ]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Customer has been restored']);
        }

        return back()->with('success', 'Customer has been restored');
    }
}