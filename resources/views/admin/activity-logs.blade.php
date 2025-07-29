<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs - CustomerSync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    <div class="flex h-screen">
        <div class="w-64 bg-white shadow-lg">
            <div class="p-6">
                <h1 class="text-xl font-bold text-gray-800">Traders Family</h1>
                <p class="text-sm text-gray-600">Admin Panel</p>
            </div>
            
            <nav class="mt-6">
                <a href="{{ route('dashboard') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-chart-bar mr-3"></i>
                    Dashboard
                </a>
                <a href="{{ route('admin.activity-logs') }}" class="flex items-center px-6 py-3 text-gray-700 bg-blue-50 border-r-2 border-blue-500">
                    <i class="fas fa-history mr-3"></i>
                    Activity Logs
                </a>
                {{-- <a href="{{ route('followup.today') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-calendar-check mr-3"></i>
                    Follow-up Hari Ini
                </a> --}}
            </nav>
            
            <!-- Logout -->
            <div class="absolute bottom-4 left-0 right-0 px-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto">
            <div class="p-6">
                <!-- Header -->
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Activity Logs</h2>
                    <p class="text-gray-600">Histori aktivitas seluruh agent</p>
                </div>

                <!-- Filters -->
                <div class="bg-white p-6 rounded-lg shadow mb-6">
                    <form method="GET" action="{{ route('admin.activity-logs') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Agent</label>
                            <select name="user_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Agent</option>
                                @foreach(\App\Models\User::where('role', 'agent')->get() as $agent)
                                    <option value="{{ $agent->id }}" {{ request('user_id') == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Action</label>
                            <select name="action" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Action</option>
                                <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                                <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                                <option value="status_changed" {{ request('action') == 'status_changed' ? 'selected' : '' }}>Status Changed</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="md:col-span-2 lg:col-span-4">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 mr-2">
                                <i class="fas fa-search mr-2"></i>Filter
                            </button>
                            <a href="{{ route('admin.activity-logs') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Activity Logs -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Activity History ({{ $logs->total() }})</h3>
                    </div>

                    <div class="divide-y divide-gray-200">
                        @forelse($logs as $log)
                        <div class="px-6 py-4 hover:bg-gray-50">
                            <div class="flex items-start space-x-4">
                                <!-- Avatar -->
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-blue-600 font-medium text-sm">{{ substr($log->user->name, 0, 1) }}</span>
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <span class="font-medium text-gray-900">{{ $log->user->name }}</span>
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full
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
                                            Customer: <span class="font-medium">{{ $log->customer->nama ?? 'Unknown' }}</span>
                                        </p>
                                    @endif

                                    <!-- Changes Details -->
                                    @if($log->changes && count($log->changes) > 0)
                                        <div class="mt-3 bg-gray-50 rounded-lg p-3">
                                            <p class="text-xs font-medium text-gray-700 mb-2">Changes:</p>
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
                        <div class="px-6 py-12 text-center">
                            <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-history text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No activity found</h3>
                            <p class="text-gray-500">There are no activity logs matching your criteria.</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($logs->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $logs->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>