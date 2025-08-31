@extends('layouts.app')

@section('title', 'Arsip Maintain - Traders Family')
@section('page-title', 'Arsip Maintain')
@section('page-description', 'Kelola data investor maintain')

@section('content')
<!-- Search Filter -->
<div class="bg-white rounded-lg border border-gray-200 shadow-sm mb-6">
    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-50">
        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
            <i class="fas fa-search mr-2 text-gray-600"></i>
            Cari Investor Maintain
        </h3>
    </div>
    <div class="p-6">
        <form method="GET" action="{{ route('dashboard.archived_maintain') }}" class="flex gap-4">
            <div class="flex-1">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama investor atau agent code..."
                           class="form-input w-full pl-10 pr-4 py-2.5 rounded-lg">
                </div>
            </div>
            <button type="submit" class="btn-secondary px-6 py-2.5 rounded-lg flex items-center">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
            <a href="{{ route('dashboard.archived_maintain') }}" class="btn-secondary px-6 py-2.5 rounded-lg flex items-center">
                <i class="fas fa-undo mr-2"></i>Reset
            </a>
        </form>
    </div>
</div>

<!-- Maintain Table -->
<div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-50">
        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
            <i class="fas fa-table mr-2 text-gray-600"></i>
            Data Maintain ({{ $maintain->total() }})
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer Info</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kontak</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Keuangan</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Follow-up</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Notes</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($maintain as $item)
                <tr class="table-row hover:bg-gray-50">
                    <!-- Tanggal -->
                    <td class="px-4 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $item->tanggal ? $item->tanggal->format('d/m/Y') : '-' }}</div>
                        <div class="text-xs text-gray-500">{{ $item->regis ?? '-' }}</div>
                    </td>
                    
                    <!-- Customer Info -->
                    <td class="px-4 py-4">
                        <div class="text-sm font-semibold text-gray-900">{{ $item->nama ?? 'No Name' }}</div>
                        <div class="text-xs text-gray-500">Reg: {{ $item->regis ?? '-' }}</div>
                    </td>
                    
                    <!-- Kontak -->
                    <td class="px-4 py-4">
                        <div class="flex items-center text-sm text-gray-900 mb-1">
                            <i class="fas fa-user mr-2 text-gray-400"></i>
                            <span class="truncate max-w-32">{{ $item->agent_code ?? '-' }}</span>
                        </div>
                    </td>
                    
                    <!-- Keuangan -->
                    <td class="px-4 py-4">
                        <div class="text-sm text-gray-900">
                            <div class="flex items-center mb-1">
                                <i class="fas fa-dollar-sign mr-2 text-gray-400"></i>
                                <span class="text-xs">Deposit: {{ number_format($item->deposit, 2) }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-wallet mr-2 text-gray-400"></i>
                                <span class="text-xs">Last Balance: {{ number_format($item->last_balance, 2) }}</span>
                            </div>
                        </div>
                    </td>
                    
                    <!-- Status -->
                    <td class="px-4 py-4">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $item->status_color }}">
                            {{ $item->status_data ?? 'No Status' }}
                        </span>
                    </td>
                    
                    <!-- Follow-up -->
                    <td class="px-4 py-4">
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
                    <td class="px-4 py-4">
                        @php
                            $hasNotes = $item->alasan_depo || collect(range(1, 5))->some(fn($i) => $item->{"fu_{$i}_note"});
                        @endphp
                        <button onclick='showNotesModal(@json($item))' 
                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors duration-200 flex items-center {{ $hasNotes ? '' : 'opacity-50' }}">
                            <i class="fas fa-sticky-note mr-1"></i>
                            {{ $hasNotes ? 'View Notes' : 'No Notes' }}
                        </button>
                    </td>

                    <!-- Aksi -->
                    <td class="px-4 py-4">
                        <form action="{{ route('dashboard.archived_maintain.destroy', ['id' => $item->id]) }}"
                              method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors duration-200 flex items-center">
                                <i class="fas fa-trash-alt mr-1"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-archive text-4xl mb-4 text-gray-300"></i>
                        <p class="text-lg font-medium">Tidak ada data maintain</p>
                        <p class="text-sm text-gray-400 mt-1">Data maintain akan ditampilkan di sini</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($maintain->hasPages())
        <div class="border-t border-gray-100">
            <div class="pagination-container">
                @if ($maintain->onFirstPage())
                    <button class="pagination-btn disabled">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                @else
                    <a href="{{ $maintain->previousPageUrl() . (request()->getQueryString() ? '&' . request()->getQueryString() : '') }}" class="pagination-btn">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @endif

                @foreach ($maintain->getUrlRange(1, $maintain->lastPage()) as $page => $url)
                    @if ($page == $maintain->currentPage())
                        <button class="pagination-btn active">{{ $page }}</button>
                    @else
                        <a href="{{ $url . (request()->getQueryString() ? '&' . request()->getQueryString() : '') }}" class="pagination-btn">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($maintain->hasMorePages())
                    <a href="{{ $maintain->nextPageUrl() . (request()->getQueryString() ? '&' . request()->getQueryString() : '') }}" class="pagination-btn">
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
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Maintain Notes</h3>
        <div id="notesContent" class="space-y-2 text-sm text-gray-700">
            <!-- Konten notes masuk via JS -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showNotesModal(item) {
        const modal = document.getElementById('notesModal');
        const notesContent = document.getElementById('notesContent');

        let html = '';

        if (item.alasan_depo) {
            html += `<p><strong>Alasan Deposit:</strong> ${item.alasan_depo}</p>`;
        }

        for (let i = 1; i <= 5; i++) {
            if (item[`fu_${i}_note`]) {
                html += `<p><strong>FU-${i}:</strong> ${item[`fu_${i}_note`]}</p>`;
            }
        }

        notesContent.innerHTML = html || '<p>Tidak ada catatan</p>';

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeNotesModal() {
        const modal = document.getElementById('notesModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endpush
