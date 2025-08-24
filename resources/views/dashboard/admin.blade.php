@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-title', 'Admin Dashboard')
@section('page-description', 'Monitoring dan statistik seluruh agent')

@section('content')
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
@endsection

@section('scripts')
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
@endsection