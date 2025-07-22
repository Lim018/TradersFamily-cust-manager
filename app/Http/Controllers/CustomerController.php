<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\ActivityLog;

class CustomerController extends Controller
{
    public function update(Request $request, Customer $customer)
    {
        // Check authorization
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isAdmin() && $customer->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'notes' => 'nullable|string',
            'followup_date' => 'nullable|date',
            'status_fu' => 'nullable|in:normal,warm,hot'
        ]);

        $oldData = $customer->toArray();
        $customer->update($validated);

        // Log activity
        ActivityLog::create([
            'user_id' => $user->id,
            'customer_id' => $customer->id,
            'action' => 'updated_manually',
            'description' => 'Customer data updated manually',
            'old_data' => $oldData,
            'new_data' => $customer->fresh()->toArray()
        ]);

        return redirect()->back()->with('success', 'Customer updated successfully');
    }
}