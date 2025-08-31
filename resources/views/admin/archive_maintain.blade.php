@extends('layouts.admin')

@section('title', 'Arsip Maintain')
@section('page-title', 'Arsip Maintain')
@section('page-description', 'Kelola data investor maintain')

@section('content')
<!-- Filters -->
<div class="bg-white rounded-lg border border-gray-200 shadow-sm stat-card mb-6">
    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-50">
        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
            <i class="fas fa-filter mr-2 text-gray-600"></i>
            Filter Data Maintain
        </h3>
    </div>
    <div class="p-6">
        <form method="GET" action="{{ route('maintain-data') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Investor</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama investor..."
                           class="form-input w-full pl-10 pr-4 py-2.5 rounded-lg">
                </div>
            </div>
            @if (auth()->user()->role === 'admin')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Agent</label>
                <div class="relative">
                    <i class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <select name="agent_code" class="form-input w-full pl-10 pr-4 py-2.5 rounded-lg">
                        <option value="">Semua Agent</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->agent_code }}" {{ request('agent_code') == $user->agent_code ? 'selected' : '' }}>
                                {{ $user->name ?? 'Unknown' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif
            <div class="md:col-span-2 flex gap-3 pt-2">
                <button type="submit" class="btn-primary px-6 py-2.5 rounded-lg flex items-center">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('maintain-data') }}" class="btn-secondary px-6 py-2.5 rounded-lg flex items-center">
                    <i class="fas fa-undo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Maintain Table -->
<div class="bg-white rounded-lg border border-gray-200 shadow-sm stat-card overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-50">
        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
            <i class="fas fa-table mr-2 text-gray-600"></i>
            Data Maintain ({{ $maintainData->total() }})
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer Info</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Agent</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Keuangan</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Follow-up</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Notes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($maintainData as $item)
                <tr class="table-row">
                    <!-- Tanggal -->
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') : '-' }}</div>
                        <div class="text-xs text-gray-500">{{ $item->regis ?? '-' }}</div>
                    </td>
                    
                    <!-- Customer Info -->
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-blue-600 font-medium text-sm">{{ substr($item->nama ?? 'N/A', 0, 1) }}</span>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-semibold text-gray-900">{{ $item->nama ?? 'No Name' }}</div>
                                <div class="text-xs text-gray-500">Reg: {{ $item->regis ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                   
                    
                   <!-- Agent -->
                    <td class="px-6 py-4">
                        <div class="flex items-center text-sm text-gray-900">
                            <i class="fas fa-user mr-2 text-gray-400"></i>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">
                                    {{ $item->user->name ?? 'Unknown Agent' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    
                    <!-- Keuangan -->
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            <div class="flex items-center mb-1">
                                <i class="fas fa-dollar-sign mr-2 text-gray-400"></i>
                                <span class="text-xs">Deposit: {{ number_format($item->deposit ?? 0, 2) }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-wallet mr-2 text-gray-400"></i>
                                <span class="text-xs">Last Balance: {{ number_format($item->last_balance ?? 0, 2) }}</span>
                            </div>
                        </div>
                    </td>
                    
                    <!-- Follow-up -->
                    <td class="px-6 py-4">
                        <div class="text-xs text-gray-600 mb-1">Total FU: {{ $item->fu_jumlah ?? 0 }}</div>
                        @for($i = 1; $i <= 5; $i++)
                            @php 
                                $fu_date = "fu_{$i}_date";
                                $fu_checked = "fu_{$i}_checked";
                            @endphp
                            @if($item->$fu_date)
                                @php
                                    $date = \Carbon\Carbon::parse($item->$fu_date);
                                    $is_today = $date->isToday();
                                    $is_past = $date->isPast() && !$date->isToday();
                                    $is_checked = $item->$fu_checked;
                                @endphp
                                <div class="text-xs {{ $is_past && !$is_checked ? 'text-red-600' : ($is_today ? 'text-blue-600' : 'text-green-600') }} mb-1">
                                    FU-{{ $i }}: {{ $date->format('d/m/Y') }}
                                    @if($is_checked)
                                        <i class="fas fa-check text-green-500 ml-1"></i>
                                    @elseif($is_past)
                                        <i class="fas fa-exclamation-triangle ml-1"></i>
                                    @elseif($is_today)
                                        <i class="fas fa-calendar-day ml-1"></i>
                                    @endif
                                </div>
                            @endif
                        @endfor
                    </td>
                    
                    <!-- Notes -->
                    <td class="px-6 py-4">
                        @php
                            $hasNotes = $item->alasan_depo || collect(range(1, 5))->some(fn($i) => $item->{"fu_{$i}_note"});
                        @endphp
                        <button onclick="showNotesModal({{ $item->id }})" 
                                class="btn-primary px-4 py-2 rounded-lg text-sm font-medium flex items-center {{ $hasNotes ? '' : 'opacity-50' }}">
                            <i class="fas fa-sticky-note mr-2"></i>
                            {{ $hasNotes ? 'View Notes' : 'No Notes' }}
                        </button>
                    </td>
                </tr>
                @empty
                <div class="px-6 py-12 text-center text-gray-500">
                    <i class="fas fa-archive text-4xl mb-4 text-gray-300"></i>
                    <p class="text-lg font-medium">Tidak ada data maintain</p>
                    <p class="text-sm text-gray-400 mt-1">Data maintain akan ditampilkan di sini</p>
                </div>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($maintainData->hasPages())
        <div class="border-t border-gray-100">
            <div class="pagination-container">
                @if($maintainData->onFirstPage())
                    <button class="pagination-btn disabled">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                @else
                    @php
                        $prevQuery = request()->except('page');
                        $prevQueryString = http_build_query($prevQuery);
                        $prevUrl = $maintainData->previousPageUrl() . ($prevQueryString ? '&' . $prevQueryString : '');
                    @endphp
                    <a href="{{ $prevUrl }}" class="pagination-btn">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @endif

                @foreach($maintainData->getUrlRange(1, $maintainData->lastPage()) as $page => $url)
                    @if($page == $maintainData->currentPage())
                        <button class="pagination-btn active">{{ $page }}</button>
                    @else
                        @php
                            $query = request()->except('page');
                            $queryString = http_build_query($query);
                            $pageUrl = $url . ($queryString ? '&' . $queryString : '');
                        @endphp
                        <a href="{{ $pageUrl }}" class="pagination-btn">{{ $page }}</a>
                    @endif
                @endforeach

                @if($maintainData->hasMorePages())
                    @php
                        $nextQuery = request()->except('page');
                        $nextQueryString = http_build_query($nextQuery);
                        $nextUrl = $maintainData->nextPageUrl() . ($nextQueryString ? '&' . $nextQueryString : '');
                    @endphp
                    <a href="{{ $nextUrl }}" class="pagination-btn">
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
<div id="notesModal" class="fixed inset-0 modal-backdrop hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 modal-content">
        <button onclick="closeNotesModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
            <i class="fas fa-times"></i>
        </button>
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Maintain Notes</h3>
        <div id="notesContent" class="space-y-2 text-sm text-gray-700">
            <!-- Konten notes akan dimasukkan via JS -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function showNotesModal(id) {
        const modal = document.getElementById('notesModal');
        const notesContent = document.getElementById('notesContent');

        axios.get(`/api/maintain-data/${id}/notes`)
            .then(response => {
                notesContent.innerHTML = response.data.notes || '<p>No notes available</p>';
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            })
            .catch(error => {
                notesContent.innerHTML = '<p>Error loading notes</p>';
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
    }

    function closeNotesModal() {
        const modal = document.getElementById('notesModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection