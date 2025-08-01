
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
    </style>
</head>
<body>
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-72 bg-white shadow-lg border-r border-gray-200">
            <div class="p-6 border-b border-gray-100">
                <h1 class="text-xl font-bold bg-gradient-to-r from-[#2D5A27] to-cyan-600 bg-clip-text text-transparent">
                    Traders Family
                </h1>
                <p class="text-sm text-gray-600 mt-1">Agent: {{ Auth::user()->name }}</p>
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
        <div class="flex-1 overflow-y-auto bg-gray-50">
            <div class="p-6">
                <!-- Header -->
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Dashboard Agent</h2>
                    <p class="text-gray-600 mt-1">Kelola customer dan follow-up Anda dengan mudah</p>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
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
                            <div class="p-2 bg-gray-100 rounded-lg mr-3">
                                <i class="fas fa-thermometer-half text-gray-600 text-lg"></i>
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

                    <div class="stat-card p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 rounded-lg mr-3">
                                <i class="fas fa-calendar-check text-green-600 text-lg"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['followup_today'] }}</p>
                                <p class="text-sm text-gray-600">Follow-up Hari Ini</p>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-red-100 rounded-lg mr-3">
                                <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['overdue_followup'] }}</p>
                                <p class="text-sm text-gray-600">Overdue</p>
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
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select name="status" class="form-input w-full px-4 py-2.5 rounded-lg">
                                    <option value="">Semua Status</option>
                                    <option value="normal" {{ request('status') == 'normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="warm" {{ request('status') == 'warm' ? 'selected' : '' }}>Warm</option>
                                    <option value="hot" {{ request('status') == 'hot' ? 'selected' : '' }}>Hot</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bulan Sheet</label>
                                <select name="month" class="form-input w-full px-4 py-2.5 rounded-lg">
                                    <option value="">Semua Bulan</option>
                                    @foreach($availableMonths as $month)
                                        <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>{{ $month }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status Follow-up</label>
                                <select name="followup_status" class="form-input w-full px-4 py-2.5 rounded-lg">
                                    <option value="">Semua</option>
                                    <option value="pending" {{ request('followup_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="overdue" {{ request('followup_status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                    <option value="completed" {{ request('followup_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
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
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kontak</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Follow-up</th>
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
                                        @php
                                            $hasFu = false;
                                            if ($customer->followup_date) {
                                                $hasFu = true;
                                            }
                                            foreach(['fu_ke_1', 'fu_ke_2', 'fu_ke_3', 'fu_ke_4', 'fu_ke_5'] as $fu_field) {
                                                if ($customer->$fu_field) {
                                                    $hasFu = true;
                                                }
                                            }
                                        @endphp
                                        @if(!$hasFu)
                                            <div class="text-sm text-gray-500">No Follow-up Data</div>
                                        @endif
                                        @if($customer->followup_date)
                                            @php
                                                try {
                                                    $date = \Carbon\Carbon::parse($customer->followup_date);
                                                    $is_overdue = $date->isPast() && !$date->isToday() && !$customer->fu_checkbox;
                                                    $is_today = $date->isToday();
                                                    $is_pending = !$is_overdue && !$is_today && !$customer->fu_checkbox;
                                                } catch (\Exception $e) {
                                                    $date = null;
                                                    $is_overdue = false;
                                                    $is_today = false;
                                                    $is_pending = false;
                                                }
                                            @endphp
                                            @if($date)
                                                <div class="text-sm {{ $is_overdue ? 'text-red-600 font-semibold' : ($is_today ? 'text-green-600 font-semibold' : ($is_pending ? 'text-blue-600 font-semibold' : 'text-gray-900')) }}">
                                                    Follow-up: {{ $date->format('d M Y') }}
                                                    @if($is_overdue)
                                                        <i class="fas fa-exclamation-triangle text-red-500 ml-1"></i>
                                                    @elseif($is_today)
                                                        <i class="fas fa-calendar-day text-green-500 ml-1"></i>
                                                    @elseif($is_pending)
                                                        <i class="fas fa-clock text-blue-500 ml-1"></i>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="text-sm text-red-600">Invalid date for Follow-up</div>
                                            @endif
                                            @if($customer->fu_checkbox)
                                                <span class="inline-flex items-center px-3 py-1 text-xs bg-green-100 text-green-800 rounded-full mt-1">
                                                    <i class="fas fa-check mr-1"></i>Completed
                                                </span>
                                            @endif
                                        @endif
                                        @foreach(['fu_ke_1', 'fu_ke_2', 'fu_ke_3', 'fu_ke_4', 'fu_ke_5'] as $index => $fu_field)
                                            @if($customer->$fu_field)
                                                @php
                                                    try {
                                                        $date = \Carbon\Carbon::parse($customer->$fu_field);
                                                        $is_overdue = $date->isPast() && !$date->isToday() && !$customer->{'fu_checkbox_' . ($index + 1)};
                                                        $is_today = $date->isToday();
                                                        $is_pending = !$is_overdue && !$is_today && !$customer->{'fu_checkbox_' . ($index + 1)};
                                                        $fu_number = $index + 1;
                                                    } catch (\Exception $e) {
                                                        $date = null;
                                                        $is_overdue = false;
                                                        $is_today = false;
                                                        $is_pending = false;
                                                        $fu_number = $index + 1;
                                                    }
                                                @endphp
                                                @if($date)
                                                    <div class="text-sm {{ $is_overdue ? 'text-red-600 font-semibold' : ($is_today ? 'text-green-600 font-semibold' : ($is_pending ? 'text-blue-600 font-semibold' : 'text-gray-900')) }}">
                                                        FU ke-{{ $fu_number }}: {{ $date->format('d M Y') }}
                                                        @if($is_overdue)
                                                            <i class="fas fa-exclamation-triangle text-red-500 ml-1"></i>
                                                        @elseif($is_today)
                                                            <i class="fas fa-calendar-day text-green-500 ml-1"></i>
                                                        @elseif($is_pending)
                                                            <i class="fas fa-clock text-blue-500 ml-1"></i>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="text-sm text-red-600">Invalid date for FU ke-{{ $fu_number }}</div>
                                                @endif
                                            @endif
                                            @if($customer->{'fu_checkbox_' . ($index + 1)})
                                                <span class="inline-flex items-center px-3 py-1 text-xs bg-green-100 text-green-800 rounded-full mt-1">
                                                    <i class="fas fa-check mr-1"></i>Completed FU ke-{{ $index + 1 }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            @if($customer->phone)
                                                <a href="{{ $customer->whatsapp_link }}" target="_blank"
                                                   class="bg-teal-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-teal-600 transition-all duration-200 flex items-center">
                                                    <i class="fab fa-whatsapp mr-1"></i>WA
                                                </a>
                                            @endif
                                            <button onclick="openEditModal({{ $customer->id }})"
                                                    class="btn-primary text-white px-3 py-1.5 rounded-lg text-xs font-medium flex items-center">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
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
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Follow-up Date</label>
                        <input type="date" name="followup_date" id="editFollowupDate"
                               class="form-input w-full px-4 py-3 rounded-lg">
                    </div>
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="fu_checkbox" id="editFuCheckbox" 
                                   class="mr-3 w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <span class="text-sm font-medium text-gray-700">Follow-up Completed</span>
                        </label>
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
        const customers = @json($customers->items());
        
            function openEditModal(customerId) {
            const customer = customers.find(c => c.id === customerId);
            if (!customer) return;
            
            document.getElementById('editForm').action = `/dashboard/customer/${customerId}`;
            document.getElementById('editNotes').value = customer.notes || '';
            document.getElementById('editFollowupDate').value = customer.followup_date ? new Date(customer.followup_date).toISOString().split('T')[0] : '';
            document.getElementById('editFuCheckbox').checked = customer.fu_checkbox || false;
            
            document.getElementById('editModal').classList.remove('hidden');
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
