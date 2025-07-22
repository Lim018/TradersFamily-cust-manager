@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Log Aktivitas</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.activity-logs', array_merge(request()->all(), ['export' => 'csv'])) }}" class="btn btn-sm btn-outline-success">
                <i class="bi bi-download"></i> Export CSV
            </a>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.activity-logs') }}" class="row g-3">
            <div class="col-md-3">
                <label for="user_id" class="form-label">Agent</label>
                <select name="user_id" id="user_id" class="form-select">
                    <option value="">Semua Agent</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="action" class="form-label">Aksi</label>
                <select name="action" id="action" class="form-select">
                    <option value="">Semua Aksi</option>
                    @foreach($actions as $action)
                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $action)) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="date_from" class="form-label">Dari Tanggal</label>
                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label for="date_to" class="form-label">Sampai Tanggal</label>
                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                <a href="{{ route('admin.activity-logs') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Activity Logs -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Riwayat Aktivitas ({{ $activityLogs->total() }} total)</h5>
    </div>
    <div class="card-body">
        @if($activityLogs->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Agent</th>
                            <th>Customer</th>
                            <th>Aksi</th>
                            <th>Deskripsi</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activityLogs as $log)
                        <tr>
                            <td>
                                <small>
                                    {{ $log->created_at->format('d/m/Y H:i') }}
                                    <br>
                                    <span class="text-muted">{{ $log->created_at->diffForHumans() }}</span>
                                </small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person-circle me-2"></i>
                                    {{ $log->user->name }}
                                </div>
                            </td>
                            <td>
                                @if($log->customer)
                                    <strong>{{ $log->customer->nama }}</strong>
                                    @if($log->customer->phone)
                                    <br>
                                    <small class="text-muted">{{ $log->customer->phone }}</small>
                                    @endif
                                @else
                                    <span class="text-muted">Customer dihapus</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $log->action == 'created_from_spreadsheet' ? 'success' : ($log->action == 'updated_manually' ? 'primary' : 'secondary') }}">
                                    {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                </span>
                            </td>
                            <td>{{ $log->description }}</td>
                            <td>
                                @if($log->old_data || $log->new_data)
                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#detailModal{{ $log->id }}">
                                    <i class="bi bi-eye"></i> Detail
                                </button>
                                @endif
                            </td>
                        </tr>

                        <!-- Detail Modal -->
                        @if($log->old_data || $log->new_data)
                        <div class="modal fade" id="detailModal{{ $log->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail Perubahan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            @if($log->old_data)
                                            <div class="col-md-6">
                                                <h6>Data Lama:</h6>
                                                <div class="bg-light p-3 rounded">
                                                    @foreach($log->old_data as $key => $value)
                                                        @if(!in_array($key, ['id', 'created_at', 'updated_at']))
                                                        <div class="mb-1">
                                                            <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                            {{ is_array($value) ? json_encode($value) : ($value ?: 'N/A') }}
                                                        </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif
                                            
                                            @if($log->new_data)
                                            <div class="col-md-6">
                                                <h6>Data Baru:</h6>
                                                <div class="bg-light p-3 rounded">
                                                    @foreach($log->new_data as $key => $value)
                                                        @if(!in_array($key, ['id', 'created_at', 'updated_at']))
                                                        <div class="mb-1">
                                                            <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                            {{ is_array($value) ? json_encode($value) : ($value ?: 'N/A') }}
                                                        </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $activityLogs->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-list-ul fs-1 text-muted"></i>
                <h5 class="text-muted mt-3">Tidak ada log aktivitas</h5>
                <p class="text-muted">Log aktivitas akan muncul ketika ada perubahan data customer</p>
            </div>
        @endif
    </div>
</div>
@endsection
