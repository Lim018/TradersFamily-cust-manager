@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Agent</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="location.reload()">
                <i class="bi bi-arrow-clockwise"></i> Refresh
            </button>
        </div>
    </div>
</div>

<!-- Stats Cards -->
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
        <div class="card stats-card warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Warm Leads</h6>
                        <h3 class="mb-0 text-warning">{{ $stats['warm'] }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-thermometer-half fs-2 text-warning"></i>
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

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('dashboard') }}" class="row g-3">
            <div class="col-md-3">
                <label for="status" class="form-label">Filter Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="normal" {{ request('status') == 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="warm" {{ request('status') == 'warm' ? 'selected' : '' }}>Warm</option>
                    <option value="hot" {{ request('status') == 'hot' ? 'selected' : '' }}>Hot</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="month" class="form-label">Filter Bulan Sheet</label>
                <select name="month" id="month" class="form-select">
                    <option value="">Semua Bulan</option>
                    @foreach($availableMonths as $month)
                    <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                        {{ $month }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle"></i> Reset
                </a>
                @if(request('status') || request('month'))
                <div class="ms-3 d-flex align-items-center">
                    <small class="text-muted">
                        <i class="bi bi-funnel-fill"></i> 
                        Filter aktif:
                        @if(request('status'))
                            <span class="badge bg-primary ms-1">{{ ucfirst(request('status')) }}</span>
                        @endif
                        @if(request('month'))
                            <span class="badge bg-info ms-1">{{ request('month') }}</span>
                        @endif
                    </small>
                </div>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Customer List -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Customer</h5>
        @if(request('status') || request('month'))
        <small class="text-muted">
            Menampilkan {{ $customers->total() }} dari {{ Auth::user()->customers->count() }} total customer
        </small>
        @endif
    </div>
    <div class="card-body">
        @if($customers->count() > 0)
            <div class="row">
                @foreach($customers as $customer)
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card customer-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-0">{{ $customer->nama }}</h6>
                                <span class="badge bg-{{ $customer->status_fu == 'hot' ? 'danger' : ($customer->status_fu == 'warm' ? 'warning' : 'secondary') }} status-badge">
                                    {{ ucfirst($customer->status_fu) }}
                                </span>
                            </div>
                            
                            @if($customer->sheet_month)
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-calendar3"></i> Sheet: {{ $customer->sheet_month }}
                                </small>
                            </div>
                            @endif
                            
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-envelope"></i> {{ $customer->email ?: 'N/A' }}
                                </small>
                            </div>
                            
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-telephone"></i> {{ $customer->phone ?: 'N/A' }}
                                </small>
                            </div>
                            
                            @if($customer->interest)
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-heart"></i> {{ $customer->interest }}
                                </small>
                            </div>
                            @endif
                            
                            @if($customer->followup_date)
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-calendar"></i> Follow-up: {{ $customer->followup_date->format('d/m/Y') }}
                                    @if($customer->followup_date->isToday())
                                        <span class="badge bg-warning text-dark ms-1">Hari Ini</span>
                                    @elseif($customer->followup_date->isPast())
                                        <span class="badge bg-danger ms-1">Terlambat</span>
                                    @endif
                                </small>
                            </div>
                            @endif
                            
                            @if($customer->notes)
                            <div class="mb-3">
                                <small class="text-muted">{{ Str::limit($customer->notes, 100) }}</small>
                            </div>
                            @endif
                            
                            <div class="d-flex gap-2">
                                @if($customer->phone)
                                <a href="{{ $customer->getWhatsAppUrl() }}" target="_blank" class="btn btn-success btn-sm">
                                    <i class="bi bi-whatsapp"></i> WA
                                </a>
                                @endif
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $customer->id }}">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal{{ $customer->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('customers.update', $customer) }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Customer: {{ $customer->nama }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="status_fu{{ $customer->id }}" class="form-label">Status</label>
                                        <select name="status_fu" id="status_fu{{ $customer->id }}" class="form-select">
                                            <option value="normal" {{ $customer->status_fu == 'normal' ? 'selected' : '' }}>Normal</option>
                                            <option value="warm" {{ $customer->status_fu == 'warm' ? 'selected' : '' }}>Warm</option>
                                            <option value="hot" {{ $customer->status_fu == 'hot' ? 'selected' : '' }}>Hot</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="followup_date{{ $customer->id }}" class="form-label">Tanggal Follow-up</label>
                                        <input type="date" name="followup_date" id="followup_date{{ $customer->id }}" class="form-control" value="{{ $customer->followup_date ? $customer->followup_date->format('Y-m-d') : '' }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="notes{{ $customer->id }}" class="form-label">Catatan</label>
                                        <textarea name="notes" id="notes{{ $customer->id }}" class="form-control" rows="3">{{ $customer->notes }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $customers->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted"></i>
                <h5 class="text-muted mt-3">
                    @if(request('status') || request('month'))
                        Tidak ada customer dengan filter yang dipilih
                    @else
                        Belum ada customer
                    @endif
                </h5>
                <p class="text-muted">
                    @if(request('status') || request('month'))
                        Coba ubah filter atau <a href="{{ route('dashboard') }}">reset filter</a>
                    @else
                        Customer akan muncul otomatis ketika data dikirim dari Google Spreadsheet
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
@endsection
