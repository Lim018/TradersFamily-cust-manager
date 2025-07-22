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

        $customers = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Stats for agent
        $stats = [
            'total_customers' => $user->customers->count(),
            'normal' => $user->customers->where('status_fu', 'normal')->count(),
            'warm' => $user->customers->where('status_fu', 'warm')->count(),
            'hot' => $user->customers->where('status_fu', 'hot')->count(),
            'followup_today' => $user->customers->where('followup_date', today())->count()
        ];

        return view('dashboard.agent', compact('customers', 'stats'));
    }

    private function adminDashboard(Request $request)
    {
        // Admin can see all customers and agents
        $agents = User::where('role', 'agent')->withCount('customers')->get();
        $totalCustomers = Customer::count();
        
        $stats = [
            'total_customers' => $totalCustomers,
            'total_agents' => $agents->count(),
            'normal' => Customer::where('status_fu', 'normal')->count(),
            'warm' => Customer::where('status_fu', 'warm')->count(),
            'hot' => Customer::where('status_fu', 'hot')->count(),
            'followup_today' => Customer::where('followup_date', today())->count()
        ];

        return view('dashboard.admin', compact('agents', 'stats'));
    }

    public function followupToday()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $query = $user->isAdmin() 
            ? Customer::query() 
            : $user->customers();
            
        $customers = $query->where('followup_date', today())
                          ->with('user')
                          ->get();

        return view('dashboard.followup-today', compact('customers'));
    }
}