@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Admin Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="location.reload()">
                <i class="bi bi-arrow-clockwise"></i> Refresh
            </button>
            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#exportModal">
                <i class="bi bi-download"></i> Export
            </button>
        </div>
    </div>
</div>

<!-- Admin Stats Cards -->
<div class="row mb-4">
    <div class="col-md-2">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Total Customer</h6>
                        <h3 class="mb-0">{{ $stats['total_customers'] }}</h3>
                        <small class="text-info">{{ $stats['total_agents'] }} agent</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-people fs-2 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Hot Leads</h6>
                        <h3 class="mb-0 text-danger">{{ $stats['hot'] + $stats['hot_closeable'] }}</h3>
                        <small class="text-muted">{{ $stats['hot'] }} + {{ $stats['hot_closeable'] }} closeable</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-fire fs-2 text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Follow-up Hari Ini</h6>
                        <h3 class="mb-0 text-info">{{ $stats['followup_today'] }}</h3>
                        @if($stats['followup_overdue'] > 0)
                        <small class="text-danger">{{ $stats['followup_overdue'] }} terlambat</small>
                        @endif
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-calendar-check fs-2 text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Closed Deals</h6>
                        <h3 class="mb-0 text-success">{{ $stats['closed_deals'] }}</h3>
                        <small class="text-muted">{{ $stats['avg_customers_per_agent'] }} avg/agent</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-trophy fs-2 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Warm Leads</h6>
                        <h3 class="mb-0 text-warning">{{ $stats['warm'] + $stats['warm_potential'] }}</h3>
                        <small class="text-muted">{{ $stats['warm'] }} + {{ $stats['warm_potential'] }} potential</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-thermometer-half fs-2 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Normal</h6>
                        <h3 class="mb-0 text-secondary">{{ $stats['normal'] + $stats['normal_prospect'] }}</h3>
                        <small class="text-muted">{{ $stats['normal'] }} + {{ $stats['normal_prospect'] }} prospect</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-person fs-2 text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Admin Filter -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="bi bi-funnel"></i> Filter & Pencarian (Admin)
            @if(collect($filters)->filter()->count() > 0)
            <span class="badge bg-primary ms-2">{{ collect($filters)->filter()->count() }} aktif</span>
            @endif
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('dashboard') }}" id="filterForm">
            <div class="row g-3">
                <!-- Row 1: Basic Filters -->
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="normal" {{ $filters['status'] == 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="normal(prospect)" {{ $filters['status'] == 'normal(prospect)' ? 'selected' : '' }}>Normal (Prospect)</option>
                        <option value="warm" {{ $filters['status'] == 'warm' ? 'selected' : '' }}>Warm</option>
                        <option value="warm(potential)" {{ $filters['status'] == 'warm(potential)' ? 'selected' : '' }}>Warm (Potential)</option>
                        <option value="hot" {{ $filters['status'] == 'hot' ? 'selected' : '' }}>Hot</option>
                        <option value="hot(closeable)" {{ $filters['status'] == 'hot(closeable)' ? 'selected' : '' }}>Hot (Closeable)</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="month" class="form-label">Bulan Sheet</label>
                    <select name="month" id="month" class="form-select form-select-sm">
                        <option value="">Semua Bulan</option>
                        @foreach($filterOptions['availableMonths'] as $month)
                        <option value="{{ $month }}" {{ $filters['month'] == $month ? 'selected' : '' }}>
                            {{ $month }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="agent" class="form-label">Agent</label>
                    <select name="agent" id="agent" class="form-select form-select-sm">
                        <option value="">Semua Agent</option>
                        @foreach($filterOptions['availableAgents'] as $agent)
                        <option value="{{ $agent->id }}" {{ $filters['agent'] == $agent->id ? 'selected' : '' }}>
                            {{ $agent->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="followup_status" class="form-label">Status Follow-up</label>
                    <select name="followup_status" id="followup_status" class="form-select form-select-sm">
                        <option value="">Semua Follow-up</option>
                        <option value="today" {{ $filters['followup_status'] == 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="overdue" {{ $filters['followup_status'] == 'overdue' ? 'selected' : '' }}>Terlambat</option>
                        <option value="upcoming" {{ $filters['followup_status'] == 'upcoming' ? 'selected' : '' }}>Akan Datang</option>
                        <option value="no_followup" {{ $filters['followup_status'] == 'no_followup' ? 'selected' : '' }}>Belum Dijadwalkan</option>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label for="search" class="form-label">Cari Customer</label>
                    <input type="text" name="search" id="search" class="form-control form-control-sm" 
                           placeholder="Nama, email, atau phone..." value="{{ $filters['search'] }}">
                </div>
                
                <!-- Filter Actions -->
                <div class="col-12">
                    <div class="d-flex gap-2 align-items-center">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-funnel"></i> Terapkan Filter
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-x-circle"></i> Reset Semua
                        </a>
                        
                        <!-- Quick Filter Buttons -->
                        <div class="ms-auto">
                            <div class="btn-group" role="group">
                                <a href="{{ route('followup.today') }}" class="btn btn-sm btn-info">
                                    Follow-up Hari Ini ({{ $stats['followup_today'] }})
                                </a>
                                <a href="{{ route('dashboard', ['followup_status' => 'overdue']) }}" 
                                   class="btn btn-sm {{ $filters['followup_status'] == 'overdue' ? 'btn-danger' : 'btn-outline-danger' }}">
                                    Terlambat ({{ $stats['followup_overdue'] }})
                                </a>
                                <a href="{{ route('dashboard', ['status' => 'hot']) }}" 
                                   class="btn btn-sm {{ $filters['status'] == 'hot' ? 'btn-warning' : 'btn-outline-warning' }}">
                                    Hot Leads ({{ $stats['hot'] + $stats['hot_closeable'] }})
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Performa Agent</h6>
            </div>
            <div class="card-body">
                <canvas id="agentPerformanceChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Trend Bulanan</h6>
            </div>
            <div class="card-body">
                <canvas id="monthlyTrendsChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Agent Overview -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Overview Agent</h5>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach($agents as $agent)
            <div class="col-md-4 mb-3">
                <div class="card border-left-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-1">{{ $agent->name }}</h6>
                                <p class="card-text">
                                    <small class="text-muted">
                                        Total: {{ $agent->customers_count ?? ($agent->filtered_customers_count ?? 0) }} customer
                                        @if($filters['month'])
                                        <br>Bulan {{ $filters['month'] }}
                                        @endif
                                    </small>
                                </p>
                                <div class="mt-2">
                                    @php
                                        $agentCustomers = $agent->customers ?? collect();
                                        $hotCount = $agentCustomers->whereIn('status_fu', ['hot', 'hot(closeable)'])->count();
                                        $warmCount = $agentCustomers->whereIn('status_fu', ['warm', 'warm(potential)'])->count();
                                        $closedCount = $agentCustomers->where('tanggal_closing', '!=', '')->whereNotNull('tanggal_closing')->count();
                                    @endphp
                                    @if($hotCount > 0)
                                    <span class="badge bg-danger me-1">{{ $hotCount }} Hot</span>
                                    @endif
                                    @if($warmCount > 0)
                                    <span class="badge bg-warning me-1">{{ $warmCount }} Warm</span>
                                    @endif
                                    @if($closedCount > 0)
                                    <span class="badge bg-success me-1">{{ $closedCount }} Closed</span>
                                    @endif
                                </div>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-person-circle fs-2 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Data Customer (Admin)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Export semua data customer dengan filter yang sedang aktif:</p>
                <ul class="list-unstyled">
                    @foreach($filters as $key => $value)
                        @if($value)
                            <li><i class="bi bi-check text-success"></i> {{ ucfirst(str_replace('_', ' ', $key)) }}: <strong>{{ $value }}</strong></li>
                        @endif
                    @endforeach
                    @if(collect($filters)->filter()->count() === 0)
                        <li><i class="bi bi-info-circle text-info"></i> Semua data customer dari semua agent akan diexport</li>
                    @endif
                </ul>
                <p class="text-muted">Format: CSV dengan kolom agent tambahan</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                {{-- <a href="{{ route('customers.export', request()->query()) }}" class="btn btn-primary">
                    <i class="bi bi-download"></i> Download CSV
                </a> --}}
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Agent Performance Chart
const agentPerformanceData = @json($agentPerformance);
const agentCtx = document.getElementById('agentPerformanceChart').getContext('2d');
new Chart(agentCtx, {
    type: 'bar',
    data: {
        labels: agentPerformanceData.map(agent => agent.name),
        datasets: [
            {
                label: 'Total Customers',
                data: agentPerformanceData.map(agent => agent.total_customers),
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            },
            {
                label: 'Hot Leads',
                data: agentPerformanceData.map(agent => agent.hot_leads),
                backgroundColor: 'rgba(255, 99, 132, 0.8)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            },
            {
                label: 'Closed Deals',
                data: agentPerformanceData.map(agent => agent.closed_deals),
                backgroundColor: 'rgba(75, 192, 192, 0.8)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }
        ]
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
                display: true,
                position: 'top'
            }
        }
    }
});

// Monthly Trends Chart
const monthlyTrendsData = @json($monthlyTrends);
const trendsCtx = document.getElementById('monthlyTrendsChart').getContext('2d');
new Chart(trendsCtx, {
    type: 'line',
    data: {
        labels: monthlyTrendsData.map(month => month.sheet_month),
        datasets: [
            {
                label: 'Total Customers',
                data: monthlyTrendsData.map(month => month.total),
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                tension: 0.4
            },
            {
                label: 'Hot Leads',
                data: monthlyTrendsData.map(month => month.hot_leads),
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.1)',
                tension: 0.4
            },
            {
                label: 'Closed Deals',
                data: monthlyTrendsData.map(month => month.closed_deals),
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.4
            }
        ]
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
                display: true,
                position: 'top'
            }
        }
    }
});

// Auto-submit form on filter change
document.querySelectorAll('#filterForm select').forEach(select => {
    select.addEventListener('change', function() {
        clearTimeout(window.filterTimeout);
        window.filterTimeout = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 500);
    });
});

// Search on enter
document.getElementById('search').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        document.getElementById('filterForm').submit();
    }
});
</script>
@endpush

@push('styles')
<style>
.card.border-left-primary {
    border-left: 4px solid #007bff;
}

.stats-card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    transition: all 0.3s;
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.25rem 2rem 0 rgba(58, 59, 69, 0.2);
}
</style>
@endpush