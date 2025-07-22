@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Follow-up Hari Ini</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <span class="badge bg-info fs-6">{{ $customers->count() }} Customer</span>
        </div>
    </div>
</div>

<!-- Month Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('followup.today') }}" class="row g-3">
            <div class="col-md-4">
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
            <div class="col-md-8 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                <a href="{{ route('followup.today') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle"></i> Reset
                </a>
                @if(request('month'))
                <div class="ms-3 d-flex align-items-center">
                    <small class="text-muted">
                        <i class="bi bi-funnel-fill"></i> 
                        Filter aktif: <span class="badge bg-info ms-1">{{ request('month') }}</span>
                    </small>
                </div>
                @endif
            </div>
        </form>
    </div>
</div>

@if($customers->count() > 0)
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i>
        <strong>{{ $customers->count() }} customer</strong> perlu di-follow up hari ini ({{ now()->format('d F Y') }})
        @if(request('month'))
        <br><small>Menampilkan data untuk bulan sheet: <strong>{{ request('month') }}</strong></small>
        @endif
    </div>

    <div class="row">
        @foreach($customers as $customer)
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card customer-card h-100 border-{{ $customer->status_fu == 'hot' ? 'danger' : ($customer->status_fu == 'warm' ? 'warning' : 'secondary') }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="card-title mb-0">{{ $customer->nama }}</h6>
                        <span class="badge bg-{{ $customer->status_fu == 'hot' ? 'danger' : ($customer->status_fu == 'warm' ? 'warning' : 'secondary') }} status-badge">
                            {{ ucfirst($customer->status_fu) }}
                        </span>
                    </div>
                    
                    @if(Auth::user()->isAdmin())
                    <div class="mb-2">
                        <small class="text-muted">
                            <i class="bi bi-person"></i> Agent: {{ $customer->user->name }}
                        </small>
                    </div>
                    @endif
                    
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
                    
                    @if($customer->fu_jumlah)
                    <div class="mb-2">
                        <small class="text-muted">
                            <i class="bi bi-arrow-repeat"></i> Follow-up ke: {{ $customer->fu_jumlah }}
                        </small>
                    </div>
                    @endif
                    
                    @if($customer->notes)
                    <div class="mb-3">
                        <small class="text-muted">
                            <strong>Catatan:</strong> {{ Str::limit($customer->notes, 100) }}
                        </small>
                    </div>
                    @endif
                    
                    <div class="d-flex gap-2">
                        @if($customer->phone)
                        <a href="{{ $customer->getWhatsAppUrl() }}" target="_blank" class="btn btn-success btn-sm">
                            <i class="bi bi-whatsapp"></i> WhatsApp
                        </a>
                        @endif
                        
                        @if(!Auth::user()->isAdmin() || $customer->user_id == Auth::id())
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $customer->id }}">
                            <i class="bi bi-pencil"></i> Update
                        </button>
                        @endif
                    </div>
                </div>
                
                <div class="card-footer bg-light">
                    <small class="text-muted">
                        <i class="bi bi-calendar-check"></i> 
                        Dijadwalkan: {{ $customer->followup_date->format('d/m/Y') }}
                    </small>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        @if(!Auth::user()->isAdmin() || $customer->user_id == Auth::id())
        <div class="modal fade" id="editModal{{ $customer->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('customers.update', $customer) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Update Follow-up: {{ $customer->nama }}</h5>
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
                                <label for="followup_date{{ $customer->id }}" class="form-label">Tanggal Follow-up Berikutnya</label>
                                <input type="date" name="followup_date" id="followup_date{{ $customer->id }}" class="form-control" value="{{ $customer->followup_date ? $customer->followup_date->format('Y-m-d') : '' }}">
                            </div>
                            <div class="mb-3">
                                <label for="notes{{ $customer->id }}" class="form-label">Catatan Follow-up</label>
                                <textarea name="notes" id="notes{{ $customer->id }}" class="form-control" rows="3" placeholder="Tambahkan catatan hasil follow-up...">{{ $customer->notes }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-calendar-check fs-1 text-muted"></i>
            <h5 class="text-muted mt-3">
                @if(request('month'))
                    Tidak ada follow-up hari ini untuk bulan {{ request('month') }}
                @else
                    Tidak ada follow-up hari ini
                @endif
            </h5>
            <p class="text-muted">
                @if(request('month'))
                    Coba pilih bulan lain atau <a href="{{ route('followup.today') }}">lihat semua bulan</a>
                @else
                    Semua follow-up sudah selesai atau belum ada yang dijadwalkan untuk hari ini.
                @endif
            </p>
            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>
@endif
@endsection
