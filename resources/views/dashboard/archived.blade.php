<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Customer - CustomerSync</title>
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
        
        .btn-success {
            background: #10b981;
            color: white;
            border: none;
            transition: all 0.2s ease;
            font-weight: 500;
        }
        
        .btn-success:hover {
            background: #059669;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
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

            .btn-success,
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
            .btn-success,
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
                    <p class="text-sm text-gray-600 mt-1">Agent: {{ Auth::user()->name }}</p>
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
                <a href="{{ route('followup.today') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 font-medium">
                    <i class="fas fa-calendar-check mr-3"></i>
                    Follow-up Hari Ini
                    @if($stats['followup_today'] > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ $stats['followup_today'] }}</span>
                    @endif
                </a>
                <a href="{{ route('dashboard.archived') }}" class="sidebar-link active flex items-center px-4 py-3 text-white font-medium">
                    <i class="fas fa-archive mr-3"></i>
                    Investor
                </a>
            </nav>
            
            <!-- Logout -->
            <div class="absolute bottom-6 left-4 right-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center px-4 py-2 text-gray-700 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200">
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
                        <h2 class="text-2xl font-bold text-gray-900">Arsip Customer</h2>
                        <p class="text-gray-600 mt-1">Kelola customer yang telah diarsipkan</p>
                    </div>
                    <button class="hamburger md:hidden" onclick="toggleSidebar()">
                        <i class="fas fa-bars text-gray-600 text-lg"></i>
                    </button>
                </div>

                <!-- Search Filter -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm mb-6">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-search mr-2 text-gray-600"></i>
                            Cari Customer Arsip
                        </h3>
                    </div>
                    <div class="p-6">
                        <form method="GET" action="{{ route('dashboard.archived') }}" class="flex gap-4">
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
                            <a href="{{ route('dashboard.archived') }}" class="btn-secondary px-6 py-2.5 rounded-lg flex items-center">
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
                            Customer yang Diarsipkan ({{ $customers->total() }})
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kontak</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Diarsipkan</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($customers as $customer)
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
                                            {{ $customer->email }}
                                        </div>
                                        <div class="flex items-center text-sm text-gray-500">
                                            <i class="fas fa-phone mr-2 text-gray-400"></i>
                                            {{ $customer->phone }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full {{ $customer->status_color }}">
                                            {{ $customer->status_display }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            {{ $customer->archived_at ? $customer->archived_at->format('d M Y H:i') : '-' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            oleh: {{ $customer->archivedBy->name ?? 'Unknown' }}
                                        </div>
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