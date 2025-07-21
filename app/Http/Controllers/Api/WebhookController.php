<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Models\ActivityLog;

class WebhookController extends Controller
{
    public function spreadsheetUpdate(Request $request)
    {
        try {
            $data = $request->all();
            
            // Validate required fields
            $validated = $request->validate([
                'agent_code' => 'required|string',
                'nama' => 'required|string',
                'tanggal' => 'required|date',
                'regis' => 'required|string',
                // ... other validations
            ]);

            // Find user by agent_code
            $user = User::where('agent_code', $validated['agent_code'])->first();
            if (!$user) {
                return response()->json(['error' => 'Agent not found'], 404);
            }

            // Check if customer exists
            $customer = Customer::where('nama', $validated['nama'])
                              ->where('user_id', $user->id)
                              ->first();

            if ($customer) {
                // Update existing customer
                $oldData = $customer->toArray();
                $customer->update($validated);
                
                // Log activity
                ActivityLog::create([
                    'user_id' => $user->id,
                    'customer_id' => $customer->id,
                    'action' => 'updated_from_spreadsheet',
                    'description' => 'Customer data updated from spreadsheet',
                    'old_data' => $oldData,
                    'new_data' => $customer->fresh()->toArray()
                ]);
            } else {
                // Create new customer
                $validated['user_id'] = $user->id;
                $customer = Customer::create($validated);
                
                // Log activity
                ActivityLog::create([
                    'user_id' => $user->id,
                    'customer_id' => $customer->id,
                    'action' => 'created_from_spreadsheet',
                    'description' => 'New customer created from spreadsheet',
                    'new_data' => $customer->toArray()
                ]);
            }

            return response()->json(['success' => true, 'customer_id' => $customer->id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}