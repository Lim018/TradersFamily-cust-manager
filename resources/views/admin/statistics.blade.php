@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Statistik & Analytics</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="location.reload()">
                <i class="bi bi-arrow-clockwise"></i> Refresh
            </button>
        </div>
    </div>
</div>

<!-- Date Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.statistics') }}" class="row g-3">
            <div class="col-md-4">
                <label for="date_from" class="form-label">Dari Tanggal</label>
                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ $dateFrom }}">
            </div>
            <div class="col-md-4">
                <label for="date_to" class="form-label">Sampai Tanggal</label>
                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ $dateTo }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                <button type="button" class="btn btn-outline-secondary" onclick="setDateRange('today')">Hari Ini</button>
                <button type="button" class="btn btn-outline-secondary ms-1" onclick="setDateRange('week')">Minggu Ini</button>
                <button type="button" class="btn btn-outline-secondary ms-1" onclick="setDateRange('month')">Bulan Ini</button>
            </div>
        </form>
    </div>
</div>

<!-- Overview Stats -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Total Customer</h6>
                        <h3 class="mb-0">{{ number_format($totalCustomers) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-people fs-2 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Total Agent</h6>
                        <h3 class="mb-0 text-success">{{ $totalAgents }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-person-badge fs-2 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Follow-up Hari Ini</h6>
                        <h3 class="mb-0 text-info">{{ $followupStats['today'] }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-calendar-check fs-2 text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card danger">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Follow-up Terlambat</h6>
                        <h3 class="mb-0 text-danger">{{ $followupStats['overdue'] }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-exclamation-triangle fs-2 text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row 1 -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Distribusi Status Customer</h5>
            </div>
            <div class="card-body">
                <canvas id="statusChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Conversion Funnel</h5>
            </div>
            <div class="card-body">
                <canvas id="funnelChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row 2 -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Trend Customer Harian</h5>
            </div>
            <div class="card-body">
                <canvas id="dailyChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Performance Tables -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Top Performing Agent</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Agent</th>
                                <th>Hot Leads</th>
                                <th>Total Customer</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topAgents as $agent)
                            <tr>
                                <td>{{ $agent->name }}</td>
                                <td>
                                    <span class="badge bg-danger">{{ $agent->hot_leads }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $agent->customers_count }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Aktivitas Terbanyak</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Aktivitas</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activitySummary as $activity => $count)
                            <tr>
                                <td>{{ ucfirst(str_replace('_', ' ', $activity)) }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $count }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Agent Performance Detail -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Detail Performance Agent</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Agent</th>
                        <th>Total Customer</th>
                        <th>Normal</th>
                        <th>Warm</th>
                        <th>Hot</th>
                        <th>Conversion Rate</th>
                        <th>Performance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($agentStats as $agent)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-person-circle me-2"></i>
                                {{ $agent->name }}
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $agent->customers_count }}</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $agent->customers->where('status_fu', 'normal')->count() }}</span>
                        </td>
                        <td>
                            <span class="badge bg-warning">{{ $agent->customers->where('status_fu', 'warm')->count() }}</span>
                        </td>
                        <td>
                            <span class="badge bg-danger">{{ $agent->customers->where('status_fu', 'hot')->count() }}</span>
                        </td>
                        <td>
                            @php
                                $total = $agent->customers_count;
                                $hot = $agent->customers->where('status_fu', 'hot')->count();
                                $rate = $total > 0 ? round(($hot / $total) * 100, 1) : 0;
                            @endphp
                            <span class="badge bg-{{ $rate > 20 ? 'success' : ($rate > 10 ? 'warning' : 'secondary') }}">
                                {{ $rate }}%
                            </span>
                        </td>
                        <td>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-success" style="width: {{ $rate }}%">
                                    {{ $rate }}%
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Status Distribution Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Normal', 'Warm', 'Hot'],
        datasets: [{
            data: [
                {{ $statusStats['normal'] ?? 0 }}, 
                {{ $statusStats['warm'] ?? 0 }}, 
                {{ $statusStats['hot'] ?? 0 }}
            ],
            backgroundColor: ['#6c757d', '#ffc107', '#dc3545'],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Conversion Funnel Chart
const funnelCtx = document.getElementById('funnelChart').getContext('2d');
new Chart(funnelCtx, {
    type: 'bar',
    data: {
        labels: ['Total', 'Normal', 'Warm', 'Hot', 'Closed'],
        datasets: [{
            label: 'Customer',
            data: [
                {{ $conversionFunnel['total'] }},
                {{ $conversionFunnel['normal'] }},
                {{ $conversionFunnel['warm'] }},
                {{ $conversionFunnel['hot'] }},
                {{ $conversionFunnel['closed'] }}
            ],
            backgroundColor: ['#007bff', '#6c757d', '#ffc107', '#dc3545', '#28a745'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Daily Trend Chart
const dailyCtx = document.getElementById('dailyChart').getContext('2d');
new Chart(dailyCtx, {
    type: 'line',
    data: {
        labels: [
            @foreach($dailyStats as $stat)
            '{{ \Carbon\Carbon::parse($stat->date)->format('d/m') }}',
            @endforeach
        ],
        datasets: [{
            label: 'Customer Baru',
            data: [
                @foreach($dailyStats as $stat)
                {{ $stat->count }},
                @endforeach
            ],
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Date Range Functions
function setDateRange(range) {
    const today = new Date();
    let fromDate, toDate;
    
    switch(range) {
        case 'today':
            fromDate = toDate = today.toISOString().split('T')[0];
            break;
        case 'week':
            const weekStart = new Date(today.setDate(today.getDate() - today.getDay()));
            fromDate = weekStart.toISOString().split('T')[0];
            toDate = new Date().toISOString().split('T')[0];
            break;
        case 'month':
            fromDate = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
            toDate = new Date().toISOString().split('T')[0];
            break;
    }
    
    document.getElementById('date_from').value = fromDate;
    document.getElementById('date_to').value = toDate;
}
</script>
@endpush
