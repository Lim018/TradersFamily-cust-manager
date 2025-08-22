<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Customer;
use App\Models\ActivityLog;

class WebhookController extends Controller
{
    public function spreadsheetUpdate(Request $request)
    {
        try {
            $validated = $request->validate([
                'agent_code' => 'required|string',
                'nama' => 'nullable|string',
                'tanggal' => 'nullable|string',
                'regis' => 'nullable|string',
                'email' => 'nullable|string',
                'phone' => 'nullable|string',
                'first_visit' => 'nullable|string',
                'interest' => 'nullable|string',
                'offer' => 'nullable|string',
                'status_fu' => 'nullable|string',
                'tanggal_closing' => 'nullable|string',
                'report' => 'nullable|string',
                'alasan_depo_decline' => 'nullable|string',
                'fu_jumlah' => 'nullable|integer',
                'fu_ke_1' => 'nullable|string',
                'next_fu_2' => 'nullable|date',
                'fu_2_checked' => 'nullable|boolean',
                'fu_2_note' => 'nullable|string',
                'next_fu_3' => 'nullable|date',
                'fu_3_checked' => 'nullable|boolean',
                'fu_3_note' => 'nullable|string',
                'next_fu_4' => 'nullable|date',
                'fu_4_checked' => 'nullable|boolean',
                'fu_4_note' => 'nullable|string',
                'next_fu_5' => 'nullable|date',
                'fu_5_checked' => 'nullable|boolean',
                'fu_5_note' => 'nullable|string',
            ]);

            // Find user by agent_code
            $user = User::where('agent_code', $validated['agent_code'])->first();
            if (!$user) {
                return response()->json(['error' => 'Agent not found'], 404);
            }

            // Check if customer exists (berdasarkan nama + user_id + email)
            $customer = Customer::where('nama', $validated['nama'])
                            ->where('user_id', $user->id)
                            ->when($validated['email'], function($query) use ($validated) {
                                return $query->where('email', $validated['email']);
                            })
                            ->first();

            $validated['user_id'] = $user->id;
            // $validated['followup_date'] = null;

            if ($customer) {
                // Update existing customer
                $oldData = $customer->toArray();
                $customer->update($validated);
                
                ActivityLog::create([
                    'user_id' => $user->id,
                    'customer_id' => $customer->id,
                    'action' => 'updated_from_spreadsheet',
                    'description' => "Customer data updated from sheet",
                    'old_data' => $oldData,
                    'new_data' => $customer->fresh()->toArray()
                ]);
            } else {
                // Create new customer
                $customer = Customer::create($validated);
                
                ActivityLog::create([
                    'user_id' => $user->id,
                    'customer_id' => $customer->id,
                    'action' => 'created_from_spreadsheet',
                    'description' => "New customer created from sheet",
                    'new_data' => $customer->toArray()
                ]);
            }

            return response()->json([
                'success' => true, 
                'customer_id' => $customer->id,
                'action' => $customer->wasRecentlyCreated ? 'created' : 'updated'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Webhook error: ' . $e->getMessage(), [
                'request' => $request->all()
            ]);
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}