<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Dashboard - CustomerSync</title>
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
        
        .btn-danger {
            background: #ef4444;
            color: white;
            border: none;
            transition: all 0.2s ease;
            font-weight: 500;
        }
        
        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
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
        
        .modal-backdrop {
            backdrop-filter: blur(8px);
            background: rgba(0, 0, 0, 0.4);
        }
        
        .modal-content {
            animation: modalSlideIn 0.3s ease-out;
        }
        
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
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
        
        .followup-date-container {
            margin-bottom: 1rem;
            padding: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: #f9fafb;
        }

        /* === Responsive Utilities === */
        @media (max-width: 1024px) {
            .sidebar-link {
                font-size: 15px;
                padding: 10px 12px;
            }

            .stat-card {
                margin-bottom: 1rem;
            }

            .pagination-container {
                flex-wrap: wrap;
                gap: 8px;
            }

            .btn-primary,
            .btn-secondary,
            .btn-danger {
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

            .modal-content {
                width: 90%;
                margin: auto;
            }

            .stat-card {
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
            .btn-secondary,
            .btn-danger {
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
                <a href="{{ route('dashboard') }}" class="sidebar-link active flex items-center px-4 py-3 text-white font-medium">
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
                <a href="{{ route('dashboard.archived') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 font-medium">
                    <i class="fas fa-archive mr-3"></i>
                    Arsip Customer
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
                        <h2 class="text-2xl font-bold text-gray-900">Dashboard Agent</h2>
                        <p class="text-gray-600 mt-1">Kelola customer dan follow-up Anda dengan mudah</p>
                    </div>
                    <button class="hamburger md:hidden" onclick="toggleSidebar()">
                        <i class="fas fa-bars text-gray-600 text-lg"></i>
                    </button>
                </div>

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
                                                {{-- @if($is_overdue1)
                                                    <i class="fas fa-exclamation-triangle ml-1"></i>
                                                @elseif($is_today1)
                                                    <i class="fas fa-calendar-day ml-1"></i>
                                                @endif --}}
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
                                            <button onclick="openEditModal({{ $customer->id }})"
                                                    class="btn-primary text-white px-2 py-1 rounded text-xs font-medium flex items-center justify-center">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </button>
                                            <form method="POST" action="{{ route('customer.archive', $customer->id) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
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
                </div>
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

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('sidebar-hidden');
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