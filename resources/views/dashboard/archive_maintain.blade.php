<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Maintain - CustomerSync</title>
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

        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s ease;
        }

        .dropdown.open .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-menu a {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: #374151;
            text-decoration: none;
            font-medium;
            transition: all 0.2s ease;
            border-radius: 6px;
            margin: 4px;
        }

        .dropdown-menu a:hover {
            background: #f3f4f6;
            color: #2D5A27;
            transform: translateX(2px);
        }

        .dropdown-toggle {
            transition: all 0.2s ease;
        }

        .dropdown.open .dropdown-toggle {
            background: #f8fafc;
        }

        .dropdown-arrow {
            transition: transform 0.2s ease;
        }

        .dropdown.open .dropdown-arrow {
            transform: rotate(180deg);
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
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
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
                    {{-- @if($stats['followup_today'] > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ $stats['followup_today'] }}</span>
                    @endif --}}
                </a>
                
                <!-- Investor Dropdown -->
                <div class="dropdown">
                    <button class="sidebar-link dropdown-toggle flex items-center px-4 py-3 text-gray-700 font-medium w-full text-left" onclick="toggleDropdown(this)">
                        <i class="fas fa-archive mr-3"></i>
                        Investor
                        <i class="fas fa-chevron-down ml-auto dropdown-arrow"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a href="{{ route('dashboard.archived_maintain') }}">
                            <i class="fas fa-cog mr-3 text-blue-600"></i>
                            Maintain
                        </a>
                        <a href="{{ route('dashboard.archived_keep') }}">
                            <i class="fas fa-handshake mr-3 text-green-600"></i>
                            Closing
                        </a>
                    </div>
                </div>
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
                        <h2 class="text-2xl font-bold text-gray-900">Arsip Maintain</h2>
                        <p class="text-gray-600 mt-1">Kelola data investor maintain</p>
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
                                        <button onclick="showNotesModal({{ $item->id }})" 
                                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors duration-200 flex items-center {{ $hasNotes ? '' : 'opacity-50' }}">
                                            <i class="fas fa-sticky-note mr-1"></i>
                                            {{ $hasNotes ? 'View Notes' : 'No Notes' }}
                                        </button>
                                    </td>
                                    
                                    <!-- Actions -->
                                    {{-- <td class="px-4 py-4">
                                        <div class="flex flex-col space-y-2">
                                            <form method="POST" action="{{ route('maintain.delete', $item->id) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Yakin ingin menghapus data ini?')"
                                                        class="btn-danger px-2 py-1 rounded text-xs font-medium flex items-center justify-center w-full">
                                                    <i class="fas fa-trash mr-1"></i>Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr> --}}
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

                    <!-- Notes Modal -->
                    <div id="notesModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
                            <button onclick="closeNotesModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                                <i class="fas fa-times"></i>
                            </button>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Maintain Notes</h3>
                            <div id="notesContent" class="space-y-2 text-sm text-gray-700">
                                <!-- Konten notes akan dimasukkan via JS -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleDropdown(button) {
            const dropdown = button.parentElement;
            const isOpen = dropdown.classList.contains('open');
            
            // Close all other dropdowns
            document.querySelectorAll('.dropdown.open').forEach(d => {
                if (d !== dropdown) {
                    d.classList.remove('open');
                }
            });
            
            // Toggle current dropdown
            dropdown.classList.toggle('open', !isOpen);
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown.open').forEach(dropdown => {
                    dropdown.classList.remove('open');
                });
            }
        });
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('sidebar-hidden');
        }

        function showNotesModal(id) {
            const modal = document.getElementById('notesModal');
            const notesContent = document.getElementById('notesContent');
            // Simulasi data notes (ganti dengan AJAX jika perlu)
            const notes = [
                @if($maintain)
                    @foreach($maintain as $item)
                        @if($item->id == '{{ $item->id }}')
                            @if($item->alasan_depo)
                                `<p><strong>Alasan Depo:</strong> {{ addslashes($item->alasan_depo) }}</p>`,
                            @endif
                            @for($i = 1; $i <= 5; $i++)
                                @php $note = "fu_{$i}_note"; @endphp
                                @if($item->$note)
                                    `<p><strong>FU-{{ $i }} Note:</strong> {{ addslashes($item->$note) }}</p>`,
                                @endif
                            @endfor
                        @endif
                    @endforeach
                @endif
            ].filter(note => note).join('');
            notesContent.innerHTML = notes || '<p>No notes available.</p>';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeNotesModal() {
            const modal = document.getElementById('notesModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
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