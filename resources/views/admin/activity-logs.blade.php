<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs - CustomerSync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
        }
        
        .sidebar {
            transition: transform 0.3s ease-in-out;
        }

        .sidebar-hidden {
            transform: translateX(-100%);
        }

        .hamburger {
            display: none;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100%;
                z-index: 1000;
                width: 280px;
            }

            .sidebar-hidden {
                transform: translateX(-100%);
            }

            .hamburger {
                display: block;
            }

            .main-content {
                margin-left: 0 !important;
            }
        }
        
        .sidebar-link {
            transition: all 0.2s ease;
            border-radius: 8px;
            margin-bottom: 4px;
        }
        
        .sidebar-link:hover {
            background: #f8fafc;
            transform: translateX(2px);
        }
        
        .sidebar-link.active {
            background: linear-gradient(135deg, #2D5A27, #40E0D0);
            color: white;
            box-shadow: 0 2px 8px rgba(45, 90, 39, 0.2);
        }
        
        .btn-primary {
            background: #4B5563;
            border: none;
            transition: all 0.2s ease;
            font-weight: 500;
            color: white;
        }
        
        .btn-primary:hover {
            background: #374151;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(55, 65, 81, 0.2);
        }
        
        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease;
            font-weight: 500;
        }
        
        .btn-secondary:hover {
            background: #e5e7eb;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .form-input {
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease;
            background: white;
        }
        
        .form-input:focus {
            border-color: #2D5A27;
            box-shadow: 0 0 0 3px rgba(45, 90, 39, 0.1);
            outline: none;
        }
        
        .stat-card {
            background: white;
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border-color: #d1d5db;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(135deg, #2D5A27, #40E0D0);
        }
        
        .table-row {
            transition: all 0.2s ease;
        }
        
        .table-row:hover {
            background: linear-gradient(135deg, #f8fafc, #f0fffe);
            transform: scale(1.002);
        }
        
        .pagination-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            padding: 1rem;
        }
        
        .pagination-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 8px;
            background: white;
            color: #6b7280;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        
        .pagination-btn:hover {
            background: #f9fafb;
            border-color: #d1d5db;
            color: #374151;
            transform: translateY(-1px);
        }
        
        .pagination-btn.active {
            background: linear-gradient(135deg, #2D5A27, #40E0D0);
            color: white;
            border-color: transparent;
            box-shadow: 0 2px 8px rgba(45, 90, 39, 0.2);
        }
        
        .pagination-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background: #f9fafb;
        }
        
        .pagination-btn.disabled:hover {
            transform: none;
            background: #f9fafb;
            border-color: #e5e7eb;
        }
        
        .success-toast {
            animation: slideInRight 0.4s ease-out;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* === Responsive Utilities === */
        @media (max-width: 1024px) {
            .sidebar-link {
                font-size: 15px;
                padding: 10px 12px;
            }

            .pagination-container {
                flex-wrap: wrap;
                gap: 8px;
            }

            .btn-primary,
            .btn-secondary {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }

        @media (max-width: 768px) {
            body {
                font-size: 14px;
            }

            .form-input {
                width: 100%;
                font-size: 14px;
            }

            .pagination-btn {
                min-width: 32px;
                height: 32px;
                font-size: 13px;
            }

            .sidebar-link {
                display: block;
                padding: 10px;
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .btn-primary,
            .btn-secondary {
                font-size: 13px;
                padding: 10px;
            }

            .table-row {
                display: block;
                padding: 10px;
                border-bottom: 1px solid #e5e7eb;
            }

            .sidebar-link {
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-72 bg-white shadow-lg border-r border-gray-200 sidebar" id="sidebar">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h1 class="text-xl font-bold bg-gradient-to-r from-[#2D5A27] to-cyan-600 bg-clip-text text-transparent">
                        Traders Family
                    </h1>
                    <p class="text-sm text-gray-600 mt-1">Admin Panel</p>
                </div>
                <button class="hamburger md:hidden" onclick="toggleSidebar()">
                    <i class="fas fa-times text-gray-600 text-lg"></i>
                </button>
            </div>
            
            <nav class="mt-6 px-4">
                <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 font-medium">
                    <i class="fas fa-chart-bar mr-3"></i>
                    Dashboard
                </a>
                <a href="{{ route('admin.activity-logs') }}" class="sidebar-link active flex items-center px-4 py-3 text-white font-medium">
                    <i class="fas fa-history mr-3"></i>
                    Activity Logs
                </a>
            </nav>
            
            <!-- Logout -->
            <div class="absolute bottom-6 left-4 right-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-2 text-gray-700 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto bg-gray-50 main-content" id="main-content">
            <div class="p-6">
                <!-- Header -->
                <div class="mb-6 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Activity Logs</h2>
                        <p class="text-gray-600 mt-1">Histori aktivitas seluruh agent</p>
                    </div>
                    <button class="hamburger md:hidden" onclick="toggleSidebar()">
                        <i class="fas fa-bars text-gray-600 text-lg"></i>
                    </button>
                </div>

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
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('sidebar-hidden');
        }
    </script>

    @if(session('success'))
        <div class="fixed top-6 right-6 bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4 rounded-xl shadow-2xl z-50 success-toast">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
        <script>
            setTimeout(() => {
                const toast = document.querySelector('.success-toast');
                if (toast) {
                    toast.style.animation = 'slideInRight 0.4s ease-out reverse';
                    setTimeout(() => toast.remove(), 400);
                }
            }, 3000);
        </script>
    @endif
</body>
</html>