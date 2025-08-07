<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CustomerSync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
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

            .stat-card {
                margin-bottom: 1rem;
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

            .stat-card,
            .table-row {
                font-size: 14px;
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
                <a href="{{ route('dashboard') }}" class="sidebar-link active flex items-center px-4 py-3 text-white font-medium">
                    <i class="fas fa-chart-bar mr-3"></i>
                    Dashboard
                </a>
                <a href="{{ route('admin.activity-logs') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 font-medium">
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
                        <h2 class="text-2xl font-bold text-gray-900">Admin Dashboard</h2>
                        <p class="text-gray-600 mt-1">Monitoring dan statistik seluruh agent</p>
                    </div>
                    <button class="hamburger md:hidden" onclick="toggleSidebar()">
                        <i class="fas fa-bars text-gray-600 text-lg"></i>
                    </button>
                </div>

                <!-- Overview Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
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
                                <i class="fas fa-user-tie text-green-600 text-lg"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_agents'] }}</p>
                                <p class="text-sm text-gray-600">Total Agent</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
                    <!-- Status Distribution Chart -->
                    <div class="stat-card p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-chart-pie mr-2 text-gray-600"></i>
                            Status Distribution
                        </h3>
                        <div class="relative h-64">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>

                    <!-- Agent Performance Chart -->
                    <div class="stat-card p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-chart-bar mr-2 text-gray-600"></i>
                            Customer per Agent
                        </h3>
                        <div class="relative h-64">
                            <canvas id="agentChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Agent Performance Table -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-table mr-2 text-gray-600"></i>
                            Performance Agent
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Agent</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Customer</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Normal</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Warm</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Hot</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Closed</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Conversion Rate</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($agentStats as $agent)
                                <tr class="table-row">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-blue-600 font-medium text-sm">{{ substr($agent->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">{{ $agent->name }}</div>
                                                @if($agent->agent_code)
                                                    <div class="text-sm text-gray-500">{{ $agent->agent_code }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $agent->customers_count }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $agent->normal_count }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $agent->warm_count }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $agent->hot_count }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $agent->closed_count }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $conversionRate = $agent->customers_count > 0 ? round(($agent->closed_count / $agent->customers_count) * 100, 1) : 0;
                                        @endphp
                                        <div class="flex items-center">
                                            <div class="flex-1 bg-gray-200 rounded-full h-2 mr-2">
                                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ $conversionRate }}%"></div>
                                            </div>
                                            <span class="text-sm text-gray-900">{{ $conversionRate }}%</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="mt-6 bg-white rounded-lg border border-gray-200 shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-50 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-history mr-2 text-gray-600"></i>
                            Recent Activity
                        </h3>
                        <a href="{{ route('admin.activity-logs') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                            View All <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    
                    <div class="divide-y divide-gray-100">
                        @forelse(\App\Models\ActivityLog::with(['user', 'customer'])->latest()->take(5)->get() as $log)
                        <div class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600 text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900">
                                        <span class="font-semibold">{{ $log->user->name }}</span>
                                        {{ $log->description }}
                                        <span class="font-semibold">{{ $log->customer->nama ?? 'Unknown Customer' }}</span>
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-history text-4xl mb-4 text-gray-300"></i>
                            <p class="text-lg font-medium">No recent activity</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('sidebar-hidden');
        }

        // Status Distribution Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($chartData['status_distribution']['labels']) !!},
                datasets: [{
                    data: {!! json_encode($chartData['status_distribution']['data']) !!},
                    backgroundColor: [
                        '#6B7280',
                        '#F59E0B',
                        '#EF4444'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });

        // Agent Performance Chart
        const agentCtx = document.getElementById('agentChart').getContext('2d');
        new Chart(agentCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartData['agent_performance']['labels']) !!},
                datasets: [{
                    label: 'Total Customers',
                    data: {!! json_encode($chartData['agent_performance']['data']) !!},
                    backgroundColor: '#40E0D0',
                    borderColor: '#2D5A27',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
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