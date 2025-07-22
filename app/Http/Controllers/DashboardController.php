<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Customer;

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

        // Filter by status if provided
        if ($request->has('status') && $request->status !== '') {
            $query->where('status_fu', $request->status);
        }

        // Filter by month if provided
        if ($request->has('month') && $request->month !== '') {
            $query->where('sheet_month', $request->month);
        }

        $customers = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Get available months for filter dropdown
        $availableMonths = $user->customers()
            ->whereNotNull('sheet_month')
            ->distinct()
            ->pluck('sheet_month')
            ->sort()
            ->values();
        
        // Stats for agent - apply same filters to stats
        $statsQuery = $user->customers();
        if ($request->has('month') && $request->month !== '') {
            $statsQuery->where('sheet_month', $request->month);
        }
        
        $stats = [
            'total_customers' => $statsQuery->count(),
            'normal' => $statsQuery->where('status_fu', 'normal')->count(),
            'warm' => $statsQuery->where('status_fu', 'warm')->count(),
            'hot' => $statsQuery->where('status_fu', 'hot')->count(),
            'followup_today' => $statsQuery->where('followup_date', today())->count()
        ];

        return view('dashboard.agent', compact('customers', 'stats', 'availableMonths'));
    }

    private function adminDashboard(Request $request)
    {
        // Admin can see all customers and agents
        $agents = User::where('role', 'agent')->withCount('customers')->get();
        
        // Apply month filter if provided
        $customerQuery = Customer::query();
        if ($request->has('month') && $request->month !== '') {
            $customerQuery->where('sheet_month', $request->month);
        }
        
        $totalCustomers = $customerQuery->count();
        
        // Get available months for filter dropdown
        $availableMonths = Customer::whereNotNull('sheet_month')
            ->distinct()
            ->pluck('sheet_month')
            ->sort()
            ->values();
        
        $stats = [
            'total_customers' => $totalCustomers,
            'total_agents' => $agents->count(),
            'normal' => (clone $customerQuery)->where('status_fu', 'normal')->count(),
            'warm' => (clone $customerQuery)->where('status_fu', 'warm')->count(),
            'hot' => (clone $customerQuery)->where('status_fu', 'hot')->count(),
            'followup_today' => (clone $customerQuery)->where('followup_date', today())->count()
        ];

        // Update agents data with month filter
        if ($request->has('month') && $request->month !== '') {
            $agents = User::where('role', 'agent')
                ->withCount(['customers' => function($query) use ($request) {
                    $query->where('sheet_month', $request->month);
                }])
                ->get();
                
            // Load filtered customers for each agent
            $agents->load(['customers' => function($query) use ($request) {
                $query->where('sheet_month', $request->month);
            }]);
        } else {
            $agents->load('customers');
        }

        return view('dashboard.admin', compact('agents', 'stats', 'availableMonths'));
    }

    public function followupToday(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $query = $user->isAdmin() 
            ? Customer::query() 
            : $user->customers();
            
        // Filter by month if provided
        if ($request->has('month') && $request->month !== '') {
            $query->where('sheet_month', $request->month);
        }
            
        $customers = $query->where('followup_date', today())
                          ->with('user')
                          ->get();
                          
        // Get available months for filter dropdown
        $availableMonths = $user->isAdmin() 
            ? Customer::whereNotNull('sheet_month')->distinct()->pluck('sheet_month')->sort()->values()
            : $user->customers()->whereNotNull('sheet_month')->distinct()->pluck('sheet_month')->sort()->values();

        return view('dashboard.followup-today', compact('customers', 'availableMonths'));
    }
}