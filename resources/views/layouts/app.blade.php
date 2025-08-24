<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Traders Family')</title>
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

        .dropdown-menu a.active {
            background: linear-gradient(135deg, #2D5A27, #40E0D0);
            color: white;
        }

        .dropdown-toggle {
            transition: all 0.2s ease;
        }

        .dropdown.open .dropdown-toggle {
            background: #f8fafc;
        }

        .dropdown.open .dropdown-toggle.has-active-child {
            background: linear-gradient(135deg, #2D5A27, #40E0D0);
            color: white;
        }

        .dropdown-arrow {
            transition: transform 0.2s ease;
        }

        .dropdown.open .dropdown-arrow {
            transform: rotate(180deg);
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

        .btn-neutral {
            background: #6b7280;
            color: white;
            border: none;
            transition: all 0.2s ease;
            font-weight: 500;
        }
        
        .btn-neutral:hover {
            background: #4b5563;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(55, 65, 81, 0.2);
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

        .summary-card {
            background: white;
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        
        .summary-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(135deg, #2D5A27, #40E0D0);
        }
        
        .summary-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .customer-card {
            background: white;
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        
        .customer-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border-color: #d1d5db;
        }
        
        .customer-card.today::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(135deg, #2D5A27, #40E0D0);
        }

        .empty-state {
            background: white;
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease;
        }
        
        .empty-state:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
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
            .btn-danger,
            .btn-success,
            .btn-neutral {
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
            .btn-danger,
            .btn-success,
            .btn-neutral {
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

        @stack('styles')
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
                <a href="{{ route('dashboard') }}" 
                   class="sidebar-link flex items-center px-4 py-3 font-medium {{ request()->routeIs('dashboard') ? 'active text-white' : 'text-gray-700' }}">
                    <i class="fas fa-chart-bar mr-3"></i>
                    Dashboard
                </a>
                
                <a href="{{ route('followup.today') }}" 
                   class="sidebar-link flex items-center px-4 py-3 font-medium {{ request()->routeIs('followup.today') ? 'active text-white' : 'text-gray-700' }}">
                    <i class="fas fa-calendar-check mr-3"></i>
                    Follow-up Hari Ini
                    @if(isset($stats) && isset($stats['followup_today']) && $stats['followup_today'] > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ $stats['followup_today'] }}</span>
                    @endif
                </a>
                
                <!-- Investor Dropdown -->
                <div class="dropdown {{ request()->routeIs(['dashboard.archived_maintain', 'dashboard.archived_keep']) ? 'open' : '' }}">
                    <button class="sidebar-link dropdown-toggle flex items-center px-4 py-3 font-medium w-full text-left {{ request()->routeIs(['dashboard.archived_maintain', 'dashboard.archived_keep']) ? 'has-active-child text-white' : 'text-gray-700' }}" 
                            onclick="toggleDropdown(this)">
                        <i class="fas fa-archive mr-3"></i>
                        Investor
                        <i class="fas fa-chevron-down ml-auto dropdown-arrow"></i>
                    </button>
                    <div class="dropdown-menu">
                       <a href="{{ route('dashboard.archived_maintain') }}"
                        class="flex items-center px-4 py-3 font-medium text-gray-700 hover:bg-gray-100
                                {{ request()->routeIs('dashboard.archived_maintain') ? ' bg-gray-300 text-gray-900 rounded-lg' : '' }}">
                            <i class="fas fa-cog mr-3 text-blue-600"></i>
                            Maintain
                        </a>

                        <a href="{{ route('dashboard.archived_keep') }}"
                        class="flex items-center px-4 py-3 font-medium text-gray-700 hover:bg-gray-100
                                {{ request()->routeIs('dashboard.archived_keep') ? ' bg-gray-300 text-gray-900 rounded-lg' : '' }}">
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
                        <h2 class="text-2xl font-bold text-gray-900">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-gray-600 mt-1">@yield('page-description', 'Kelola customer dan follow-up Anda dengan mudah')</p>
                    </div>
                    <button class="hamburger md:hidden" onclick="toggleSidebar()">
                        <i class="fas fa-bars text-gray-600 text-lg"></i>
                    </button>
                </div>

                <!-- Main Content Area -->
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Base JavaScript -->
        <script>
            function toggleDropdown(button) {
        const dropdown = button.parentElement;
        const isOpen = dropdown.classList.contains('open');
        const isActive = button.classList.contains('has-active-child');
        
        // Close all other dropdowns
        document.querySelectorAll('.dropdown.open').forEach(d => {
            if (d !== dropdown) {
                d.classList.remove('open');
            }
        });

        // Kalau dropdown punya child aktif, jangan izinkan menutup
        if (isActive && isOpen) {
            return; // biarkan tetap terbuka
        }

        // Toggle current dropdown
        dropdown.classList.toggle('open', !isOpen);
    }


        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown.open').forEach(dropdown => {
                    // Don't close if it has active child
                    if (!dropdown.querySelector('.dropdown-toggle').classList.contains('has-active-child')) {
                        dropdown.classList.remove('open');
                    }
                });
            }
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('sidebar-hidden');
        }

        // Auto close mobile sidebar when clicking on content
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const hamburger = e.target.closest('.hamburger');
            const sidebarContent = e.target.closest('.sidebar');
            
            if (window.innerWidth <= 768 && !hamburger && !sidebarContent) {
                sidebar.classList.add('sidebar-hidden');
            }
        });
    </script>

    @stack('scripts')

    <!-- Success Toast -->
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

    <!-- Error Toast -->
    @if(session('error'))
        <div class="fixed top-6 right-6 bg-gradient-to-r from-red-500 to-red-600 text-white px-6 py-4 rounded-xl shadow-2xl z-50 success-toast">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
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