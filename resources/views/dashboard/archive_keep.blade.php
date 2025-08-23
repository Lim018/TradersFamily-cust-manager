@extends('layouts.app')

@section('title', 'Arsip Closing - Traders Family')

@section('page-title', 'Arsip Closing')

@section('page-description', 'Kelola customer yang telah diarsipkan')

@section('content')
<!-- Search Filter -->
<div class="bg-white rounded-lg border border-gray-200 shadow-sm mb-6">
    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-50">
        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
            <i class="fas fa-search mr-2 text-gray-600"></i>
            Cari Customer Arsip
        </h3>
    </div>
    <div class="p-6">
        <form method="GET" action="{{ route('dashboard.archived_keep') }}" class="flex gap-4">
            <div class="flex-1">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama customer..."
                           class="form-input w-full pl-10 pr-4 py-2.5 rounded-lg">
                </div>
            </div>
            <button type="submit" class="btn-secondary px-6 py-2.5 rounded-lg flex items-center">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
            <a href="{{ route('dashboard.archived_keep') }}" class="btn-secondary px-6 py-2.5 rounded-lg flex items-center">
                <i class="fas fa-undo mr-2"></i>Reset
            </a>
        </form>
    </div>
</div>

<!-- Archived Customer Table -->
<div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-50">
        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
            <i class="fas fa-archive mr-2 text-gray-600"></i>
            Customer yang Diarsipkan ({{ $keep->total() }})
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kontak</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($keep as $customer)
                <tr class="table-row">
                    <td class="px-6 py-4">
                        <div>
                            <div class="text-sm font-semibold text-gray-900">{{ $customer->nama ?? 'No Name' }}</div>
                            <div class="text-sm text-gray-500">{{ $customer->regis }}</div>
                            <div class="text-sm text-gray-500">{{ $customer->interest }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center text-sm text-gray-900 mb-1">
                            <i class="fas fa-envelope mr-2 text-gray-400"></i>
                            {{ $customer->email ?? 'N/A' }}
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-phone mr-2 text-gray-400"></i>
                            {{ $customer->phone ?? 'N/A' }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full {{ $customer->status_color }}">
                            {{ $customer->status_display }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            @if($customer->phone)
                                <a href="{{ $customer->whatsapp_link }}" target="_blank"
                                   class="bg-teal-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-teal-600 transition-all duration-200 flex items-center">
                                    <i class="fab fa-whatsapp mr-1"></i>WA
                                </a>
                            @endif
                            <form method="POST" action="{{ route('customer.restore', $customer->id) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" onclick="return confirm('Yakin ingin mengembalikan customer ini ke dashboard?')"
                                        class="btn-success px-3 py-1.5 rounded-lg text-xs font-medium flex items-center">
                                    <i class="fas fa-undo mr-1"></i>Kembalikan
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-archive text-4xl mb-4 text-gray-300"></i>
                        <p class="text-lg font-medium">Tidak ada customer yang diarsipkan</p>
                        <p class="text-sm text-gray-400 mt-1">Customer yang diarsipkan akan ditampilkan di sini</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if ($keep->hasPages())
        <div class="border-t border-gray-100">
            <div class="pagination-container">
                @if ($keep->onFirstPage())
                    <button class="pagination-btn disabled">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                @else
                    <a href="{{ $keep->previousPageUrl() . (request()->getQueryString() ? '&' . request()->getQueryString() : '') }}" class="pagination-btn">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @endif

                @foreach ($keep->getUrlRange(1, $keep->lastPage()) as $page => $url)
                    @if ($page == $keep->currentPage())
                        <button class="pagination-btn active">{{ $page }}</button>
                    @else
                        <a href="{{ $url . (request()->getQueryString() ? '&' . request()->getQueryString() : '') }}" class="pagination-btn">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($keep->hasMorePages())
                    <a href="{{ $keep->nextPageUrl() . (request()->getQueryString() ? '&' . request()->getQueryString() : '') }}" class="pagination-btn">
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
@endsection