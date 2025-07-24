@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Dashboard Admin</h1>
            <p class="mb-0 text-muted">Monitoring & Analytics</p>
        </div>
        <div>
            <a href="{{ route('dashboard.followup-today') }}" class="btn btn-warning me-2">
                <i class="fas fa-clock"></i> Follow-up Hari Ini
            </a>
            <a href="{{ route('dashboard.export', request()->query()) }}" class="btn btn-success">
                <i class="fas fa-download"></i> Export CSV
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Customer</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_customers'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Agent</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_agents'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Closed Deals</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['closed_deals'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-handshake fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Avg. Customer/Agent</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['avg_customers_per_agent'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Agent Performance Chart -->
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Agent Performance</h6>
                </div>
                <div class="card-body">
                    <canvas id="agentChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Status Distribution -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Status Distribution</h6>
                </div>
                <div class="card-body">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Trends -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Monthly Trends</h6>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Data</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('dashboard') }}" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">Status FU</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="normal" {{ $filters['status'] == 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="warm" {{ $filters['status'] == 'warm' ? 'selected' : '' }}>Warm</option>
                        <option value="hot" {{ $filters['status'] == 'hot' ? 'selected' : '' }}>Hot</option>
                        <option value="normal(prospect)" {{ $filters['status'] == 'normal(prospect)' ? 'selected' : '' }}>Normal (Prospect)</option>
                        <option value="warm(potential)" {{ $filters['status'] == 'warm(potential)' ? 'selected' : '' }}>Warm (Potential)</option>
                        <option value="hot(closeable)" {{ $filters['status'] == 'hot(closeable)' ? 'selected' : '' }}>Hot (Closeable)</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Bulan</label>
                    <select name="month" class="form-select">
                        <option value="">Semua Bulan</option>
                        @foreach($filterOptions['availableMonths'] as $month)
                            <option value="{{ $month }}" {{ $filters['month'] == $month ? 'selected' : '' }}>
                                {{ $month }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Agent</label>
                    <select name="agent" class="form-select">
                        <option value="">Semua Agent</option>
                        @foreach($filterOptions['availableAgents'] as $agent)
                            <option value="{{ $agent->id }}" {{ $filters['agent'] == $agent->id ? 'selected' : '' }}>
                                {{ $agent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Follow-up Status</label>
                    <select name="followup_status" class="form-select">
                        <option value="">Semua</option>
                        <option value="today" {{ $filters['followup_status'] == 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="overdue" {{ $filters['followup_status'] == 'overdue' ? 'selected' : '' }}>Terlambat</option>
                        <option value="upcoming" {{ $filters['followup_status'] == 'upcoming' ? 'selected' : '' }}>Mendatang</option>
                        <option value="no_followup" {{ $filters['followup_status'] == 'no_followup' ? 'selected' : '' }}>Belum Ada FU</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Pencarian</label>
                    <input type="text" name="search" class="form-control" placeholder="Nama, Email, Phone" value="{{ $filters['search'] }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Closing Status</label>
                    <select name="closing_status" class="form-select">
                        <option value="">Semua</option>
                        <option value="closed" {{ $filters['closing_status'] == 'closed' ? 'selected' : '' }}>Sudah Closing</option>
                        <option value="open" {{ $filters['closing_status'] == 'open' ? 'selected' : '' }}>Belum Closing</option>
                    </select>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Agents Performance Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Agent Performance Detail</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Agent Name</th>
                            <th>Total Customer</th>
                            <th>Hot Leads</th>
                            <th>Closed Deals</th>
                            <th>Follow-up Hari Ini</th>
                            <th>Conversion Rate</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($agents as $agent)
                            @php
                                $totalCustomers = $agent->customers->count();
                                $conversionRate = $totalCustomers > 0 ? round(($agent->customers->whereNotNull('tanggal_closing')->where('tanggal_closing', '!=', '')->count() / $totalCustomers) * 100, 1) : 0;
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $agent->name }}</strong>
                                    <br><small class="text-muted">{{ $agent->email }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-primary">{{ $totalCustomers }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-danger">
                                        {{ $agent->customers->where('status_fu', 'hot')->count() + $agent->customers->where('status_fu', 'hot(closeable)')->count() }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-success">
                                        {{ $agent->customers->whereNotNull('tanggal_closing')->where('tanggal_closing', '!=', '')->count() }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-warning">
                                        {{ $agent->customers->where('followup_date', today())->count() }}
                                    </span>
                                </td>
                                <td>{{ $conversionRate }}%</td>
                                <td>
                                    <a href="{{ route('dashboard', ['agent' => $agent->id]) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Follow-up Summary</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center">
                    <div class="border-left-warning p-3">
                        <h5 class="text-warning">{{ $stats['followup_today'] }}</h5>
                        <small>Follow-up Hari Ini</small>
                    </div>
                </div>
                <div class="col-md-3 text-center">
                    <div class="border-left-danger p-3">
                        <h5 class="text-danger">{{ $stats['followup_overdue'] }}</h5>
                        <small>Follow-up Terlambat</small>
                    </div>
                </div>
                <div class="col-md-3 text-center">
                    <div class="border-left-info p-3">
                        <h5 class="text-info">{{ $stats['followup_upcoming'] }}</h5>
                        <small>Follow-up Mendatang</small>
                    </div>
                </div>
                <div class="col-md-3 text-center">
                    <div class="border-left-success p-3">
                        <h5 class="text-success">{{ $stats['with_phone'] }}</h5>
                        <small>Customer dengan Phone</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Agent Performance Chart
    const agentCtx = document.getElementById('agentChart').getContext('2d');
    const agentData = @json($agentPerformance);
    
    new Chart(agentCtx, {
        type: 'bar',
        data: {
            labels: agentData.map(agent => agent.name),
            datasets: [{
                label: 'Total Customer',
                data: agentData.map(agent => agent.total_customers),
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }, {
                label: 'Hot Leads',
                data: agentData.map(agent => agent.hot_leads),
                backgroundColor: 'rgba(255, 99, 132, 0.8)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }, {
                label: 'Closed Deals',
                data: agentData.map(agent => agent.closed_deals),
                backgroundColor: 'rgba(75, 192, 192, 0.8)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Status Distribution Pie Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Normal', 'Warm', 'Hot'],
            datasets: [{
                data: [
                    {{ $stats['normal'] + $stats['normal_prospect'] }},
                    {{ $stats['warm'] + $stats['warm_potential'] }},
                    {{ $stats['hot'] + $stats['hot_closeable'] }}
                ],
                backgroundColor: [
                    'rgba(108, 117, 125, 0.8)',
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(220, 53, 69, 0.8)'
                ],
                borderColor: [
                    'rgba(108, 117, 125, 1)',
                    'rgba(255, 193, 7, 1)',
                    'rgba(220, 53, 69, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Monthly Trends Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyData = @json($monthlyTrends);
    
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: monthlyData.map(item => item.sheet_month),
            datasets: [{
                label: 'Total Customer',
                data: monthlyData.map(item => item.total),
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Hot Leads',
                data: monthlyData.map(item => item.hot_leads),
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.1)',
                tension: 0.4,
                fill: false
            }, {
                label: 'Closed Deals',
                data: monthlyData.map(item => item.closed_deals),
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.4,
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });
});
</script>

@endsection