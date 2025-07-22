@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Admin</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="location.reload()">
                <i class="bi bi-arrow-clockwise"></i> Refresh
            </button>
        </div>
    </div>
</div>

<!-- Overall Stats -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Total Customer</h6>
                        <h3 class="mb-0">{{ $stats['total_customers'] }}</h3>
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
                        <h3 class="mb-0 text-success">{{ $stats['total_agents'] }}</h3>
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
                        <h6 class="card-title text-muted">Hot Leads</h6>
                        <h3 class="mb-0 text-danger">{{ $stats['hot'] }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-fire fs-2 text-danger"></i>
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
                        <h6 class="card-title text-muted">Follow-up Hari Ini</h6>
                        <h3 class="mb-0 text-info">{{ $stats['followup_today'] }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-calendar-check fs-2 text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Distribution Chart -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Distribusi Status Customer</h5>
            </div>
            <div class="card-body">
                <canvas id="statusChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Performance Agent</h5>
            </div>
            <div class="card-body">
                <canvas id="agentChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Agent Performance Table -->
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
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($agents as $agent)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-person-circle me-2"></i>
                                <div>
                                    <strong>{{ $agent->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $agent->email }}</small>
                                </div>
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
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#agentModal{{ $agent->id }}">
                                <i class="bi bi-eye"></i> Detail
                            </button>
                        </td>
                    </tr>

                    <!-- Agent Detail Modal -->
                    <div class="modal fade" id="agentModal{{ $agent->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detail Agent: {{ $agent->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <strong>Email:</strong> {{ $agent->email }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Agent Code:</strong> {{ $agent->agent_code }}
                                        </div>
                                    </div>
                                    
                                    <h6>Customer Terbaru:</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Nama</th>
                                                    <th>Status</th>
                                                    <th>Tanggal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($agent->customers->take(5) as $customer)
                                                <tr>
                                                    <td>{{ $customer->nama }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $customer->status_fu == 'hot' ? 'danger' : ($customer->status_fu == 'warm' ? 'warning' : 'secondary') }}">
                                                            {{ ucfirst($customer->status_fu) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $customer->created_at->format('d/m/Y') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
            data: [{{ $stats['normal'] }}, {{ $stats['warm'] }}, {{ $stats['hot'] }}],
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

// Agent Performance Chart
const agentCtx = document.getElementById('agentChart').getContext('2d');
new Chart(agentCtx, {
    type: 'bar',
    data: {
        labels: [
            @foreach($agents as $agent)
            '{{ $agent->name }}',
            @endforeach
        ],
        datasets: [{
            label: 'Total Customer',
            data: [
                @foreach($agents as $agent)
                {{ $agent->customers_count }},
                @endforeach
            ],
            backgroundColor: '#007bff',
            borderColor: '#0056b3',
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
</script>
@endpush
