
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Maintain - Traders Family</title>
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

        /* Responsive Utilities */
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
                <a href="{{ route('admin.activity-logs') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 font-medium">
                    <i class="fas fa-history mr-3"></i>
                    Activity Logs
                </a>
                <a href="{{ route('maintain-data') }}" class="sidebar-link active flex items-center px-4 py-3 text-white font-medium">
                    <i class="fas fa-archive mr-3"></i>
                    Arsip Maintain
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
                        <h2 class="text-2xl font-bold text-gray-900">Arsip Maintain</h2>
                        <p class="text-gray-600 mt-1">Kelola data investor maintain</p>
                    </div>
                    <button class="hamburger md:hidden" onclick="toggleSidebar()">
                        <i class="fas fa-bars text-gray-600 text-lg"></i>
                    </button>
                </div>

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
                                                {{ $user->agent_code }} ({{ $user->name ?? 'Unknown' }})
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
                                                <div class="text-xs text-gray-500">
                                                    {{ $item->agent_code ?? '-' }}
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
                                    <a href="{{ $maintainData->previousPageUrl() . (request()->getQueryString() ? '&' . request()->getQueryString() : '') }}" class="pagination-btn">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                @endif

                                @foreach($maintainData->getUrlRange(1, $maintainData->lastPage()) as $page => $url)
                                    @if($page == $maintainData->currentPage())
                                        <button class="pagination-btn active">{{ $page }}</button>
                                    @else
                                        <a href="{{ $url . (request()->getQueryString() ? '&' . request()->getQueryString() : '') }}" class="pagination-btn">{{ $page }}</a>
                                    @endif
                                @endforeach

                                @if($maintainData->hasMorePages())
                                    <a href="{{ $maintainData->nextPageUrl() . (request()->getQueryString() ? '&' . request()->getQueryString() : '') }}" class="pagination-btn">
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

                <!-- Success Toast -->
                @if(session('success'))
                    <div class="fixed top-6 right-6 bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4 rounded-xl shadow-2xl z-50 success-toast">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('sidebar-hidden');
        }

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

        @if(session('success'))
            setTimeout(() => {
                const toast = document.querySelector('.success-toast');
                if (toast) {
                    toast.style.animation = 'slideInRight 0.4s ease-out reverse';
                    setTimeout(() => toast.remove(), 400);
                }
            }, 3000);
        @endif
    </script>
</body>
</html>
