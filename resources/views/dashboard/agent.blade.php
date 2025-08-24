@extends('layouts.app')

@section('title', 'Agent Dashboard - CustomerSync')
@section('page-title', 'Dashboard Agent')
@section('page-description', 'Kelola customer dan follow-up Anda dengan mudah')

@section('content')
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="stat-card p-4 rounded-lg">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg mr-3">
                    <i class="fas fa-users text-blue-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_customers'] }}</p>
                    <p class="text-sm text-gray-600">Total Customer</p>
                </div>
            </div>
        </div>

        <div class="stat-card p-4 rounded-lg">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg mr-3">
                    <i class="fas fa-thermometer-half text-green-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['normal_status'] }}</p>
                    <p class="text-sm text-gray-600">Normal</p>
                </div>
            </div>
        </div>

        <div class="stat-card p-4 rounded-lg">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg mr-3">
                    <i class="fas fa-thermometer-half text-yellow-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['warm_status'] }}</p>
                    <p class="text-sm text-gray-600">Warm</p>
                </div>
            </div>
        </div>

        <div class="stat-card p-4 rounded-lg">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg mr-3">
                    <i class="fas fa-thermometer-half text-red-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['hot_status'] }}</p>
                    <p class="text-sm text-gray-600">Hot</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm mb-6">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-50">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-filter mr-2 text-gray-600"></i>
                Filter Data Customer
            </h3>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Customer</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Nama customer..."
                               class="form-input w-full pl-10 pr-4 py-2.5 rounded-lg">
                    </div>
                </div>
                <div class="md:col-span-2 lg:col-span-4 flex gap-3 pt-2">
                    <button type="submit" class="btn-primary text-white px-6 py-2.5 rounded-lg flex items-center">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn-secondary px-6 py-2.5 rounded-lg flex items-center">
                        <i class="fas fa-undo mr-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Customer Table -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-50">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-table mr-2 text-gray-600"></i>
                Data Customer ({{ $customers->total() }})
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer Info</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kontak</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Interest & Offer</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Follow-up</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Notes</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($customers as $customer)
                    <tr class="table-row hover:bg-gray-50">
                        <!-- Tanggal -->
                        <td class="px-4 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $customer->tanggal ?? '-' }}</div>
                            <div class="text-xs text-gray-500">{{ $customer->regis ?? '-' }}</div>
                        </td>
                        
                        <!-- Customer Info -->
                        <td class="px-4 py-4">
                            <div class="text-sm font-semibold text-gray-900">{{ $customer->nama ?? 'No Name' }}</div>
                            <div class="text-xs text-gray-500">Reg: {{ $customer->regis ?? '-' }}</div>
                        </td>
                        
                        <!-- Kontak -->
                        <td class="px-4 py-4">
                            <div class="flex items-center text-sm text-gray-900 mb-1">
                                <i class="fas fa-envelope mr-2 text-gray-400"></i>
                                <span class="truncate max-w-32">{{ $customer->email ?? '-' }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-phone mr-2 text-gray-400"></i>
                                {{ $customer->phone ?? '-' }}
                            </div>
                        </td>
                        
                        <!-- Interest & Offer -->
                        <td class="px-4 py-4">
                            <div class="text-sm text-gray-900">
                                <div class="flex items-center mb-1">
                                    <i class="fas fa-heart mr-2 text-gray-400"></i>
                                    <span class="text-xs truncate max-w-24">{{ $customer->interest ?? '-' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-tag mr-2 text-gray-400"></i>
                                    <span class="text-xs truncate max-w-24">{{ $customer->offer ?? '-' }}</span>
                                </div>
                            </div>
                        </td>
                        
                        <!-- Status -->
                        <td class="px-4 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $customer->status_color }}">
                                {{ $customer->status_display ?? $customer->status_fu ?? 'No Status' }}
                            </span>
                        </td>
                        
                        <!-- Follow-up -->
                        <td class="px-4 py-4">
                            <div class="text-xs text-gray-600 mb-1">Total FU: {{ $customer->fu_jumlah ?? 0 }}</div>
                            
                            <!-- FU ke-1 -->
                            @if($customer->fu_ke_1)
                                @php
                                    $date1 = \Carbon\Carbon::parse($customer->fu_ke_1);
                                    $is_overdue1 = $date1->isPast() && !$date1->isToday();
                                    $is_today1 = $date1->isToday();
                                @endphp
                                <div class="text-xs text-green-600 mb-1">
                                    FU-1: {{ $date1->format('d/m/Y') }}
                                </div>
                            @endif
                            
                            <!-- Next FU 2-5 -->
                            @for($i = 2; $i <= 5; $i++)
                                @php $next_fu_field = "next_fu_{$i}"; @endphp
                                @if($customer->$next_fu_field)
                                    @php
                                        $date = \Carbon\Carbon::parse($customer->$next_fu_field);
                                        $is_today = $date->isToday();
                                        $is_past = $date->isPast() && !$date->isToday();
                                        $is_checked = $customer->{"fu_{$i}_checked"};
                                    @endphp
                                    <div class="text-xs 
                                        {{ $is_past ? 'text-green-600' : ($is_today ? 'text-blue-600' : 'text-blue-600') }} mb-1">
                                        FU-{{ $i }}: {{ $date->format('d/m/Y') }}
                                        @if($is_today)
                                            <i class="fas fa-calendar-day ml-1"></i>
                                        @endif
                                    </div>
                                @endif
                            @endfor
                            
                            <!-- Additional followup dates from JSON -->
                            @php
                                $followupDates = json_decode($customer->followup_date, true) ?? [];
                            @endphp
                            @foreach($followupDates as $index => $dateObj)
                                @php
                                    try {
                                        $date = \Carbon\Carbon::parse($dateObj['date']);
                                        $is_completed = $dateObj['completed'] ?? false;
                                        $is_overdue = $date->isPast() && !$date->isToday() && !$is_completed;
                                        $is_today = $date->isToday();
                                    } catch (\Exception $e) {
                                        continue;
                                    }
                                @endphp
                                <div class="text-xs {{ $is_overdue ? 'text-red-600' : ($is_today ? 'text-green-600' : 'text-blue-600') }} mb-1">
                                    Extra-{{ $index + 1 }}: {{ $date->format('d/m/Y') }}
                                    @if($is_completed)
                                        <i class="fas fa-check text-green-500 ml-1"></i>
                                    @elseif($is_overdue)
                                        <i class="fas fa-exclamation-triangle ml-1"></i>
                                    @elseif($is_today)
                                        <i class="fas fa-calendar-day ml-1"></i>
                                    @endif
                                </div>
                            @endforeach
                        </td>
                        
                        <!-- Reports & Notes Button -->
                        <td class="px-4 py-4">
                            @php
                                $hasNotes = $customer->report || collect(range(2, 5))->some(fn($i) => $customer->{"fu_{$i}_note"});
                            @endphp
                            <button onclick="showNotesModal({{ $customer->id }})" 
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors duration-200 flex items-center {{ $hasNotes ? '' : 'opacity-50' }}">
                                <i class="fas fa-sticky-note mr-1"></i>
                                {{ $hasNotes ? 'View Notes' : 'No Notes' }}
                            </button>
                        </td>
                        
                        <!-- Actions -->
                        <td class="px-4 py-4">
                            <div class="flex flex-col space-y-2">
                                @if($customer->phone)
                                    <a href="{{ $customer->whatsapp_link }}" target="_blank"
                                    class="bg-teal-500 text-white px-2 py-1 rounded text-xs font-medium hover:bg-teal-600 transition-all duration-200 flex items-center justify-center">
                                        <i class="fab fa-whatsapp mr-1"></i>WA
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('customer.archive', $customer->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Yakin ingin mengarsipkan customer ini?')"
                                            class="btn-danger px-2 py-1 rounded text-xs font-medium flex items-center justify-center w-full">
                                        <i class="fas fa-archive mr-1"></i>Arsip
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-users text-4xl mb-4 text-gray-300"></i>
                            <p class="text-lg font-medium">Tidak ada data customer</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if ($customers->hasPages())
            <div class="border-t border-gray-100">
                <div class="pagination-container">
                    @if ($customers->onFirstPage())
                        <button class="pagination-btn disabled">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                    @else
                        <a href="{{ $customers->previousPageUrl() . (request()->getQueryString() ? '&' . request()->getQueryString() : '') }}" class="pagination-btn">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif

                    @foreach ($customers->getUrlRange(1, $customers->lastPage()) as $page => $url)
                        @if ($page == $customers->currentPage())
                            <button class="pagination-btn active">{{ $page }}</button>
                        @else
                            <a href="{{ $url . (request()->getQueryString() ? '&' . request()->getQueryString() : '') }}" class="pagination-btn">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($customers->hasMorePages())
                        <a href="{{ $customers->nextPageUrl() . (request()->getQueryString() ? '&' . request()->getQueryString() : '') }}" class="pagination-btn">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <button class="pagination-btn disabled">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Notes Modal -->
    <div id="notesModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
            <button onclick="closeNotesModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Notes</h3>
            <div id="notesContent" class="space-y-2 text-sm text-gray-700">
                <!-- Konten notes akan dimasukkan via JS -->
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 modal-backdrop hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-6 border-0 w-full max-w-md shadow-2xl rounded-xl bg-white modal-content">
            <div class="mt-3">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-edit mr-2 text-gray-600"></i>
                    Edit Customer
                </h3>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                        <textarea name="notes" id="editNotes" rows="4"
                                  class="form-input w-full px-4 py-3 rounded-lg"
                                  placeholder="Tambahkan catatan..."></textarea>
                    </div>
                    
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Follow-up Dates</label>
                        <div id="followup-dates-container">
                            <!-- Dynamic follow-up date inputs will be added here -->
                        </div>
                        <button type="button" onclick="addFollowupDateInput()"
                                class="btn-primary text-white px-4 py-2 rounded-lg mt-2 flex items-center">
                            <i class="fas fa-plus mr-2"></i>Tambah Tanggal
                        </button>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeEditModal()"
                                class="btn-secondary px-6 py-2.5 rounded-lg">
                            Cancel
                        </button>
                        <button type="submit" class="btn-primary text-white px-6 py-2.5 rounded-lg">
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
    function showNotesModal(customerId) {
        fetch(`/customers/${customerId}/notes`) // buat route baru untuk fetch data notes
            .then(response => response.json())
            .then(data => {
                let content = "";

                if (data.report) {
                    content += `
                        <div class="mb-2">
                            <div class="text-xs text-gray-600 mb-1">Report:</div>
                            <div class="text-xs text-gray-900 break-words">${data.report}</div>
                        </div>
                    `;
                }

                if (data.fu_notes && data.fu_notes.length > 0) {
                    data.fu_notes.forEach((note, index) => {
                        content += `
                            <div class="mb-1">
                                <div class="text-xs text-blue-600">FU-${index+2} Note:</div>
                                <div class="text-xs text-gray-700 break-words">${note}</div>
                            </div>
                        `;
                    });
                }

                if (!data.report && (!data.fu_notes || data.fu_notes.length === 0)) {
                    content += `<div class="text-xs text-gray-500">No notes</div>`;
                }

                document.getElementById('notesContent').innerHTML = content;
                document.getElementById('notesModal').classList.remove('hidden');
                document.getElementById('notesModal').classList.add('flex');
            })
            .catch(err => {
                document.getElementById('notesContent').innerHTML = `<div class="text-red-500 text-sm">Failed to load notes.</div>`;
                document.getElementById('notesModal').classList.remove('hidden');
                document.getElementById('notesModal').classList.add('flex');
            });
    }

    function closeNotesModal() {
        document.getElementById('notesModal').classList.add('hidden');
        document.getElementById('notesModal').classList.remove('flex');
    }

    const customers = @json($customers->items());
    
    function openEditModal(customerId) {
        const customer = customers.find(c => c.id === customerId);
        if (!customer) return;
        
        document.getElementById('editForm').action = `/dashboard/customer/${customerId}`;
        document.getElementById('editNotes').value = customer.notes || '';
        
        // Clear previous JSON followup inputs
        const container = document.getElementById('followup-dates-container');
        container.innerHTML = '';
        
        // Parse followup_date JSON
        const followupDates = customer.followup_date ? JSON.parse(customer.followup_date) : [];
        
        // Add inputs for JSON follow-up dates
        followupDates.forEach((dateObj, index) => {
            addFollowupDateInput(dateObj.date, dateObj.completed || false, index);
        });
        
        // Add one empty input if no JSON dates exist
        if (followupDates.length === 0) {
            addFollowupDateInput();
        }
        
        document.getElementById('editModal').classList.remove('hidden');
    }
    
    function addFollowupDateInput(date = '', completed = false, index = null) {
        const container = document.getElementById('followup-dates-container');
        const inputIndex = index !== null ? index : container.children.length;
        
        const followupDiv = document.createElement('div');
        followupDiv.className = 'followup-date-container flex items-center space-x-2 mb-2';
        followupDiv.innerHTML = `
            <input type="date" name="followup_date[${inputIndex}][date]" value="${date}" 
                   class="form-input flex-1 px-4 py-2 rounded-lg">
            <button type="button" onclick="this.parentElement.remove()" 
                    class="text-red-500 hover:text-red-700">
                <i class="fas fa-trash"></i>
            </button>
        `;
        container.appendChild(followupDiv);
    }
    
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
    
    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });
</script>
@endpush