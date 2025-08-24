@extends('layouts.admin')

@section('title', 'Activity Logs')
@section('page-title', 'Activity Logs')
@section('page-description', 'Histori aktivitas seluruh agent')

@section('content')
<!-- Filters -->
<div class="bg-white rounded-lg border border-gray-200 shadow-sm mb-6">
    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-50">
        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
            <i class="fas fa-filter mr-2 text-gray-600"></i>
            Filter Activity Logs
        </h3>
    </div>
    <div class="p-6">
        <form method="GET" action="{{ route('admin.activity-logs') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Agent</label>
                <select name="user_id" class="form-input w-full px-4 py-2.5 rounded-lg">
                    <option value="">Semua Agent</option>
                    @foreach(\App\Models\User::where('role', 'agent')->get() as $agent)
                        <option value="{{ $agent->id }}" {{ request('user_id') == $agent->id ? 'selected' : '' }}>
                            {{ $agent->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Action</label>
                <select name="action" class="form-input w-full px-4 py-2.5 rounded-lg">
                    <option value="">Semua Action</option>
                    <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                    <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                    <option value="status_changed" {{ request('action') == 'status_changed' ? 'selected' : '' }}>Status Changed</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="form-input w-full px-4 py-2.5 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="form-input w-full px-4 py-2.5 rounded-lg">
            </div>
            <div class="md:col-span-2 lg:col-span-4 flex gap-3 pt-2">
                <button type="submit" class="btn-primary text-white px-6 py-2.5 rounded-lg flex items-center">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.activity-logs') }}" class="btn-secondary px-6 py-2.5 rounded-lg flex items-center">
                    <i class="fas fa-undo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Activity Logs -->
<div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-50">
        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
            <i class="fas fa-table mr-2 text-gray-600"></i>
            Activity History ({{ $logs->total() }})
        </h3>
    </div>
    <div class="divide-y divide-gray-100">
        @forelse($logs as $log)
        <div class="px-6 py-4 table-row">
            <div class="flex items-start space-x-4">
                <!-- Avatar -->
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-blue-600 font-medium text-sm">{{ substr($log->user->name, 0, 1) }}</span>
                </div>
                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="font-semibold text-gray-900">{{ $log->user->name }}</span>
                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full
                                {{ $log->action === 'created' ? 'bg-green-100 text-green-800' : 
                                   ($log->action === 'updated' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($log->action) }}
                            </span>
                        </div>
                        <span class="text-sm text-gray-500">{{ $log->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">{{ $log->description }}</p>
                    @if($log->customer)
                        <p class="text-sm text-gray-500 mt-1">
                            Customer: <span class="font-semibold">{{ $log->customer->nama ?? 'Unknown' }}</span>
                        </p>
                    @endif
                    <!-- Changes Details -->
                    @if($log->changes && count($log->changes) > 0)
                        <div class="mt-3 bg-gray-50 rounded-lg p-3">
                            <p class="text-xs font-semibold text-gray-700 mb-2">Changes:</p>
                            <div class="space-y-1">
                                @foreach($log->changes as $change)
                                    <div class="text-xs text-gray-600">
                                        <span class="font-medium">{{ $change['field'] }}:</span>
                                        <span class="text-red-600">{{ $change['old'] ?: 'empty' }}</span>
                                        <i class="fas fa-arrow-right mx-1"></i>
                                        <span class="text-green-600">{{ $change['new'] ?: 'empty' }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="px-6 py-12 text-center text-gray-500">
            <i class="fas fa-history text-4xl mb-4 text-gray-300"></i>
            <p class="text-lg font-medium">No activity found</p>
            <p class="text-gray-500">There are no activity logs matching your criteria.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($logs->hasPages())
        <div class="border-t border-gray-100">
            <div class="pagination-container">
                @if($logs->onFirstPage())
                    <button class="pagination-btn disabled">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                @else
                    <a href="{{ $logs->previousPageUrl() . (request()->getQueryString() ? '&' . request()->getQueryString() : '') }}" class="pagination-btn">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @endif

                @foreach($logs->getUrlRange(1, $logs->lastPage()) as $page => $url)
                    @if($page == $logs->currentPage())
                        <button class="pagination-btn active">{{ $page }}</button>
                    @else
                        <a href="{{ $url . (request()->getQueryString() ? '&' . request()->getQueryString() : '') }}" class="pagination-btn">{{ $page }}</a>
                    @endif
                @endforeach

                @if($logs->hasMorePages())
                    <a href="{{ $logs->nextPageUrl() . (request()->getQueryString() ? '&' . request()->getQueryString() : '') }}" class="pagination-btn">
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