<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Customer;
use App\Models\Maintain;
use App\Models\ActivityLog;

class WebhookController extends Controller
{
    public function spreadsheetUpdate(Request $request)
    {
        try {
            // Log data yang diterima untuk debugging
            Log::info('Webhook received data:', $request->all());
            
            // Deteksi jenis data berdasarkan data_type atau keberadaan field tertentu
            $dataType = $this->detectDataType($request);
            
            if ($dataType === 'maintain') {
                return $this->handleMaintainData($request);
            } else {
                return $this->handleKeepData($request);
            }
            
        } catch (\Exception $e) {
            Log::error('Webhook error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function detectDataType(Request $request)
    {
        // Cara 1: Berdasarkan field data_type
        if ($request->has('data_type')) {
            return $request->input('data_type');
        }
        
        // Cara 2: Berdasarkan parameter URL ?type=maintain
        if ($request->get('type')) {
            return $request->get('type');
        }
        
        // Cara 3: Auto-detect berdasarkan keberadaan field khas maintain
        if ($request->has('deposit') || $request->has('wd') || $request->has('nmi') || $request->has('lot')) {
            return 'maintain';
        }
        
        // Default ke 'keep'
        return 'keep';
    }

    private function handleKeepData(Request $request)
{
    $validated = $request->validate([
        'agent_code' => 'required|string',
        'nama' => 'nullable|string',
        'tanggal' => 'nullable|date', // Ubah ke date untuk konsistensi
        'regis' => 'nullable|string',
        'email' => 'nullable|string',
        'phone' => 'nullable|string',
        'first_visit' => 'nullable|string',
        'interest' => 'nullable|string',
        'offer' => 'nullable|string',
        'status_fu' => 'nullable|string',
        'tanggal_closing' => 'nullable|date',
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
        'status_data' => 'nullable|string',
        'next_fu' => 'nullable|string', // Tambahkan untuk menangani field next_fu
    ]);

    Log::info('Processing keep data', [
        'agent_code' => $validated['agent_code'],
        'nama' => $validated['nama']
    ]);

    // Cari user berdasarkan agent_code
    $user = User::where('agent_code', $validated['agent_code'])->first();
    if (!$user) {
        Log::warning('Agent not found', ['agent_code' => $validated['agent_code']]);
        return response()->json(['error' => 'Agent not found'], 404);
    }

    // Tentukan status archive
    $validated['is_archived'] = isset($validated['status_data']) && $validated['status_data'] === 'archived';
    $validated['archived_at'] = $validated['is_archived'] ? now() : null;
    $validated['archived_by'] = $validated['is_archived'] ? $user->id : null;
    $validated['user_id'] = $user->id;

    // Cari atau buat customer
    $customer = Customer::where('nama', $validated['nama'])
                       ->where('user_id', $user->id)
                       ->when($validated['email'], function ($query) use ($validated) {
                           return $query->where('email', $validated['email']);
                       })
                       ->first();

    if ($customer) {
        // Update existing customer
        $oldData = $customer->toArray();
        $customer->update($validated);
        
        ActivityLog::create([
            'user_id' => $user->id,
            'customer_id' => $customer->id,
            'action' => 'updated_from_spreadsheet',
            'description' => 'Customer data updated from sheet',
            'old_data' => json_encode($oldData),
            'new_data' => json_encode($customer->fresh()->toArray())
        ]);
        $action = 'updated';
    } else {
        // Create new customer
        $customer = Customer::create($validated);
        
        ActivityLog::create([
            'user_id' => $user->id,
            'customer_id' => $customer->id,
            'action' => 'created_from_spreadsheet',
            'description' => 'New customer created from sheet',
            'new_data' => json_encode($customer->toArray())
        ]);
        $action = 'created';
    }

    return response()->json([
        'success' => true,
        'data_type' => 'keep',
        'customer_id' => $customer->id,
        'action' => $action
    ]);
}

    private function handleMaintainData(Request $request)
{
    $validated = $request->validate([
        'agent_code' => 'required|string',
        'nama' => 'required|string',
        'tanggal' => 'nullable|date',
        'regis' => 'nullable|string|max:100',
        'alasan_depo' => 'nullable|string|max:255',
        'deposit' => 'nullable|numeric',
        'wd' => 'nullable|numeric',
        'nmi' => 'nullable|numeric',
        'lot' => 'nullable|numeric',
        'profit' => 'nullable|numeric',
        'last_balance' => 'nullable|numeric',
        'status_data' => 'nullable|string|max:50',
        'upsell' => 'nullable|string|max:100',
        'fu_jumlah' => 'nullable|integer',
        'fu_1_date' => 'nullable|date',
        'fu_1_checked' => 'nullable|in:0,1,true,false',
        'fu_1_note' => 'nullable|string',
        'fu_2_date' => 'nullable|date',
        'fu_2_checked' => 'nullable|in:0,1,true,false',
        'fu_2_note' => 'nullable|string',
        'fu_3_date' => 'nullable|date',
        'fu_3_checked' => 'nullable|in:0,1,true,false',
        'fu_3_note' => 'nullable|string',
        'fu_4_date' => 'nullable|date',
        'fu_4_checked' => 'nullable|in:0,1,true,false',
        'fu_4_note' => 'nullable|string',
        'fu_5_date' => 'nullable|date',
        'fu_5_checked' => 'nullable|in:0,1,true,false',
        'fu_5_note' => 'nullable|string',
    ]);

    $cleanData = $this->cleanMaintainData($validated);
    $cleanData['is_archived'] = true; // Semua data maintain dianggap archive
    $cleanData['archived_at'] = now();
    // archived_by bisa diisi jika ada informasi user dari request

    $maintain = Maintain::where('agent_code', $cleanData['agent_code'])
                       ->where('nama', $cleanData['nama'])
                       ->first();

    if ($maintain) {
        $maintain->update($cleanData);
        $action = 'updated';
        Log::info('Maintain record updated:', ['id' => $maintain->id]);
    } else {
        $maintain = Maintain::create($cleanData);
        $action = 'created';
        Log::info('Maintain record created:', ['id' => $maintain->id]);
    }

    return response()->json([
        'success' => true,
        'data_type' => 'maintain',
        'maintain_id' => $maintain->id,
        'action' => $action,
        'data' => $maintain->toArray()
    ]);
}

    private function cleanMaintainData($data)
    {
        // Convert empty strings to null untuk field yang bisa null
        $nullableFields = [
            'tanggal', 'regis', 'alasan_depo', 'status_data', 'upsell',
            'fu_1_date', 'fu_1_note', 'fu_2_date', 'fu_2_note',
            'fu_3_date', 'fu_3_note', 'fu_4_date', 'fu_4_note',
            'fu_5_date', 'fu_5_note'
        ];

        foreach ($nullableFields as $field) {
            if (isset($data[$field]) && $data[$field] === '') {
                $data[$field] = null;
            }
        }

        // Convert numeric fields
        $numericFields = ['deposit', 'wd', 'nmi', 'lot', 'profit', 'last_balance'];
        foreach ($numericFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = $data[$field] === '' ? 0 : (float)$data[$field];
            }
        }

        // Convert boolean fields dari integer ke boolean
        $booleanFields = ['fu_1_checked', 'fu_2_checked', 'fu_3_checked', 'fu_4_checked', 'fu_5_checked'];
        foreach ($booleanFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = (bool)$data[$field];
            }
        }

        // Convert integer fields
        if (isset($data['fu_jumlah'])) {
            $data['fu_jumlah'] = $data['fu_jumlah'] === '' ? 0 : (int)$data['fu_jumlah'];
        }

        return $data;
    }
    public function getMaintainData(Request $request)
    {
        try {
            $validated = $request->validate([
                'agent_code' => 'nullable|string',
                'search' => 'nullable|string',
            ]);

            $query = Maintain::query();

            if ($validated['agent_code']) {
                $query->where('agent_code', $validated['agent_code']);
            }

            if ($validated['search']) {
                $query->where('nama', 'like', '%' . $validated['search'] . '%');
            }

            $maintainData = $query->paginate(10);

            if ($maintainData->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No maintain data found.',
                    'data' => [],
                ], 200);
            }

            return response()->json([
                'success' => true,
                'data_type' => 'maintain',
                'data' => $maintainData,
                'total_records' => $maintainData->total(),
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching maintain data: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getMaintainNotes($id)
    {
        try {
            $maintain = Maintain::findOrFail($id);
            $notes = [
                'alasan_depo' => $maintain->alasan_depo,
                'fu_1_note' => $maintain->fu_1_note,
                'fu_2_note' => $maintain->fu_2_note,
                'fu_3_note' => $maintain->fu_3_note,
                'fu_4_note' => $maintain->fu_4_note,
                'fu_5_note' => $maintain->fu_5_note,
            ];
            $notesHtml = '';
            foreach ($notes as $key => $note) {
                if ($note) {
                    $notesHtml .= "<p><strong>" . str_replace('_', ' ', ucfirst($key)) . ":</strong> $note</p>";
                }
            }
            return response()->json([
                'success' => true,
                'notes' => $notesHtml ?: '<p>No notes available</p>',
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching notes: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
}