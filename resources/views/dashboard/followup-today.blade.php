@extends('layouts.app')

@section('title', 'Follow-up Hari Ini - Traders Family')

@section('page-title', 'Follow-up Hari Ini')

@section('page-description')
    <i class="fas fa-calendar mr-2 text-gray-400"></i>
    {{ \Carbon\Carbon::today()->format('l, d F Y') }}
@endsection

@section('content')
<!-- Summary Card -->
<div class="summary-card p-4 lg:p-6 rounded-lg shadow-sm mb-6">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Total Follow-up Hari Ini</h3>
            <p class="text-3xl font-bold bg-gradient-to-r from-[#2D5A27] to-cyan-600 bg-clip-text text-transparent">
                {{ $customers->count() }}
            </p>
            <p class="text-sm text-gray-500 mt-1">
                Customer yang perlu di follow-up hari ini
            </p>
        </div>
        <div class="p-4 bg-gradient-to-br from-[#2D5A27] to-cyan-500 rounded-xl ml-4">
            <i class="fas fa-calendar-check text-white text-2xl"></i>
        </div>
    </div>
</div>

@if($customers->count() > 0)
    <!-- Customer Cards -->
    <div class="space-y-4">
        @foreach($customers as $customer)
        @php
            // Tentukan follow-up date mana yang hari ini
            $today = \Carbon\Carbon::today()->format('Y-m-d');
            $followupType = '';
            $followupNumber = 0;
            
            if($customer->fu_ke_1 == $today) {
                $followupType = 'First Follow-up';
                $followupNumber = 1;
            } elseif($customer->next_fu_2 == $today) {
                $followupType = '2nd Follow-up';
                $followupNumber = 2;
            } elseif($customer->next_fu_3 == $today) {
                $followupType = '3rd Follow-up';
                $followupNumber = 3;
            } elseif($customer->next_fu_4 == $today) {
                $followupType = '4th Follow-up';
                $followupNumber = 4;
            } elseif($customer->next_fu_5 == $today) {
                $followupType = '5th Follow-up';
                $followupNumber = 5;
            }
            
            // Cek apakah sudah di-check
            $isCompleted = false;
            if($followupNumber >= 2) {
                $isCompleted = $customer->{"fu_{$followupNumber}_checked"};
            }
            
            // Generate WhatsApp link jika ada phone
            $whatsappLink = '';
            if($customer->phone) {
                $cleanPhone = preg_replace('/[^0-9]/', '', $customer->phone);
                if(substr($cleanPhone, 0, 1) == '0') {
                    $cleanPhone = '62' . substr($cleanPhone, 1);
                }
                $message = urlencode("Halo {$customer->nama}, ini dari Traders Family. Ada waktu untuk follow-up hari ini?");
                $whatsappLink = "https://wa.me/{$cleanPhone}?text={$message}";
            }
        @endphp
        
        <div class="customer-card today rounded-lg shadow-sm p-4 lg:p-6">
            <div class="space-y-4">
                <!-- Customer Header -->
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between space-y-3 sm:space-y-0">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $customer->nama ?? 'No Name' }}</h3>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $followupType }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 text-xs bg-green-100 text-green-800 rounded-full">
                                <i class="fas fa-clock mr-1"></i>Today
                            </span>
                            @if($isCompleted)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>Completed
                                </span>
                            @endif
                            @if($customer->status_fu)
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ $customer->status_fu }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="text-left sm:text-right text-sm text-gray-500">
                        <p class="font-medium">{{ \Carbon\Carbon::today()->format('d M Y') }}</p>
                        <p class="text-xs">Follow-up #{{ $followupNumber }}</p>
                    </div>
                </div>

                <!-- Customer Details -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-envelope mr-2 text-gray-400 w-4 flex-shrink-0"></i>
                            <span class="font-medium mr-2">Email:</span>
                            <span class="truncate">{{ $customer->email ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-phone mr-2 text-gray-400 w-4 flex-shrink-0"></i>
                            <span class="font-medium mr-2">Phone:</span>
                            <span>{{ $customer->phone ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-id-card mr-2 text-gray-400 w-4 flex-shrink-0"></i>
                            <span class="font-medium mr-2">Regis:</span>
                            <span>{{ $customer->regis }}</span>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-heart mr-2 text-gray-400 w-4 flex-shrink-0"></i>
                            <span class="font-medium mr-2">Interest:</span>
                            <span class="truncate">{{ $customer->interest ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-calendar-alt mr-2 text-gray-400 w-4 flex-shrink-0"></i>
                            <span class="font-medium mr-2">First Visit:</span>
                            <span>{{ $customer->first_visit ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-redo mr-2 text-gray-400 w-4 flex-shrink-0"></i>
                            <span class="font-medium mr-2">FU Count:</span>
                            <span>{{ $customer->fu_jumlah }}</span>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                @if($followupNumber >= 2 && $customer->{"fu_{$followupNumber}_note"})
                    <div class="bg-gradient-to-r from-gray-50 to-gray-50 border border-gray-100 p-4 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-sticky-note mr-2 text-gray-400 mt-0.5 flex-shrink-0"></i>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-gray-700 mb-1">Follow-up Notes:</p>
                                <p class="text-sm text-gray-600 break-words">{{ $customer->{"fu_{$followupNumber}_note"} }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    @if($whatsappLink)
                        <a href="{{ $whatsappLink }}" target="_blank"
                           class="btn-success text-white px-4 py-2.5 rounded-lg text-sm font-medium flex items-center justify-center">
                            <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                        </a>
                    @endif
                    
                    <button onclick="openQuickUpdate({{ $customer->id }}, {{ $followupNumber }})"
                            class="btn-neutral text-white px-4 py-2.5 rounded-lg text-sm font-medium flex items-center justify-center">
                        <i class="fas fa-edit mr-2"></i>Quick Update
                    </button>
                    
                    @if(!$isCompleted && $followupNumber >= 2)
                        <form method="POST" action="{{ route('customer.mark-fu-completed', [$customer->id, $followupNumber]) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="btn-success text-white px-4 py-2.5 rounded-lg text-sm font-medium flex items-center justify-center">
                                <i class="fas fa-check mr-2"></i>Mark Completed
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
@else
    <!-- Empty State -->
    <div class="empty-state rounded-lg shadow-sm p-12 text-center">
        <div class="w-24 h-24 mx-auto bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-6">
            <i class="fas fa-calendar-check text-gray-400 text-3xl"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak ada follow-up hari ini</h3>
        <p class="text-gray-500 mb-6 max-w-md mx-auto">
            Selamat! Anda tidak memiliki follow-up yang dijadwalkan untuk hari ini. 
            Gunakan waktu ini untuk merencanakan strategi follow-up selanjutnya.
        </p>
        <a href="{{ route('dashboard') }}" class="btn-neutral text-white px-6 py-3 rounded-lg font-medium inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
        </a>
    </div>
@endif

<!-- Quick Update Modal -->
<div id="quickUpdateModal" class="fixed inset-0 modal-backdrop hidden overflow-y-auto h-full w-full z-50 p-4">
    <div class="relative top-20 mx-auto border-0 w-full max-w-md shadow-2xl rounded-xl bg-white modal-content">
        <div class="p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-edit mr-2 text-gray-600"></i>Quick Update
            </h3>
            <form id="quickUpdateForm" method="POST">
                @csrf
                @method('PATCH')
                
                <input type="hidden" id="followupNumber" name="followup_number" value="">
                
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Follow-up Notes</label>
                    <textarea name="notes" id="quickNotes" rows="4"
                              placeholder="Tambahkan catatan follow-up..."
                              class="form-input w-full px-4 py-3 rounded-lg"></textarea>
                </div>
                
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Next Follow-up Date</label>
                    <input type="date" name="next_followup_date" id="quickFollowupDate"
                           min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"
                           class="form-input w-full px-4 py-3 rounded-lg">
                </div>
                
                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="mark_completed" id="quickFuCheckbox" 
                               class="mr-3 w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        <span class="text-sm font-medium text-gray-700">Mark this follow-up as completed</span>
                    </label>
                </div>
                
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeQuickUpdate()"
                            class="btn-neutral text-white px-6 py-2.5 rounded-lg">
                        Cancel
                    </button>
                    <button type="submit" class="btn-success text-white px-6 py-2.5 rounded-lg">
                        Update Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const customers = @json($customers);
    
    function openQuickUpdate(customerId, followupNumber) {
        const customer = customers.find(c => c.id === customerId);
        if (!customer) return;
        
        document.getElementById('quickUpdateForm').action = `/dashboard/customer/${customerId}/followup-update`;
        document.getElementById('followupNumber').value = followupNumber;
        
        // Set existing notes jika ada
        const existingNotes = followupNumber >= 2 ? customer[`fu_${followupNumber}_note`] : '';
        document.getElementById('quickNotes').value = existingNotes || '';
        
        document.getElementById('quickFollowupDate').value = '';
        document.getElementById('quickFuCheckbox').checked = false;
        
        document.getElementById('quickUpdateModal').classList.remove('hidden');
    }
    
    function closeQuickUpdate() {
        document.getElementById('quickUpdateModal').classList.add('hidden');
    }
    
    document.getElementById('quickUpdateModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeQuickUpdate();
        }
    });
</script>
@endpush