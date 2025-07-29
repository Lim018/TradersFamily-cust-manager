<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CustomerSync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
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
                <a href="{{ route('dashboard') }}" class="flex items-center px-6 py-3 text-gray-700 bg-blue-50 border-r-2 border-blue-500">
                    <i class="fas fa-chart-bar mr-3"></i>
                    Dashboard
                </a>
                <a href="{{ route('admin.activity-logs') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-history mr-3"></i>
                    Activity Logs
                </a>
                {{-- <a href="{{ route('followup.today') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-calendar-check mr-3"></i>
                    Follow-up Hari Ini
                    @if($stats['followup_today'] > 0)
                        <span class="ml-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ $stats['followup_today'] }}</span>
                    @endif
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
                    <h2 class="text-2xl font-bold text-gray-800">Admin Dashboard</h2>
                    <p class="text-gray-600">Monitoring dan statistik seluruh agent</p>
                </div>

                <!-- Overview Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-full">
                                <i class="fas fa-users text-blue-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Total Customer</p>
                                <p class="text-2xl font-semibold">{{ $stats['total_customers'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-full">
                                <i class="fas fa-user-tie text-green-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Total Agent</p>
                                <p class="text-2xl font-semibold">{{ $stats['total_agents'] }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="p-3 bg-yellow-100 rounded-full">
                                <i class="fas fa-calendar-check text-yellow-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Follow-up Hari Ini</p>
                                <p class="text-2xl font-semibold">{{ $stats['followup_today'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-100 rounded-full">
                                <i class="fas fa-handshake text-purple-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Closed Deals</p>
                                <p class="text-2xl font-semibold">{{ $stats['closed_deals'] }}</p>
                            </div>
                        </div>
                    </div> --}}
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Status Distribution Chart -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Status Distribution</h3>
                        <div class="relative h-64">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>

                    <!-- Agent Performance Chart -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Customer per Agent</h3>
                        <div class="relative h-64">
                            <canvas id="agentChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Agent Performance Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Performance Agent</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Agent</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Normal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Warm</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hot</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Closed</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Conversion Rate</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($agentStats as $agent)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-blue-600 font-medium text-sm">{{ substr($agent->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $agent->name }}</div>
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
                <div class="mt-8 bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
                        <a href="{{ route('admin.activity-logs') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            View All <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    
                    <div class="divide-y divide-gray-200">
                        @forelse(\App\Models\ActivityLog::with(['user', 'customer'])->latest()->take(5)->get() as $log)
                        <div class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600 text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900">
                                        <span class="font-medium">{{ $log->user->name }}</span>
                                        {{ $log->description }}
                                        <span class="font-medium">{{ $log->customer->nama ?? 'Unknown Customer' }}</span>
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="px-6 py-8 text-center text-gray-500">
                            No recent activity
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
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
                    backgroundColor: '#3B82F6',
                    borderColor: '#2563EB',
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
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50">
            {{ session('success') }}
        </div>
        <script>
            setTimeout(() => {
                document.querySelector('.fixed.top-4.right-4').remove();
            }, 3000);
        </script>
    @endif
</body>
</html>