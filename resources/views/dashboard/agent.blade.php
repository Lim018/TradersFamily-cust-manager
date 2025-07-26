@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Agent</h1>
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

<!-- Enhanced Stats Cards -->
<div class="row mb-4">
    <div class="col-md-2">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Total Customer</h6>
                        <h3 class="mb-0">{{ $stats['total_customers'] }}</h3>
                        <small class="text-success">+{{ $stats['total_this_month'] }} bulan ini</small>
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
                        <h6 class="card-title text-muted">Closing Rate</h6>
                        <h3 class="mb-0 text-success">{{ $stats['conversion_rate'] }}%</h3>
                        <small class="text-muted">{{ $stats['closed_deals'] }} deals</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-graph-up fs-2 text-success"></i>
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
                        <h6 class="card-title text-muted">Dengan Phone</h6>
                        <h3 class="mb-0 text-primary">{{ $stats['with_phone'] }}</h3>
                        <small class="text-muted">{{ round(($stats['with_phone'] / max($stats['total_customers'], 1)) * 100) }}%</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-telephone fs-2 text-primary"></i>
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
                        <h6 class="card-title text-muted">Dengan Email</h6>
                        <h3 class="mb-0 text-secondary">{{ $stats['with_email'] }}</h3>
                        <small class="text-muted">{{ round(($stats['with_email'] / max($stats['total_customers'], 1)) * 100) }}%</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-envelope fs-2 text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Filter -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="bi bi-funnel"></i> Filter & Pencarian
            @if(collect($filters)->filter()->count() > 0)
            <span class="badge bg-primary ms-2">{{ collect($filters)->filter()->count() }} aktif</span>
            @endif
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('dashboard') }}" id="filterForm">
            <div class="row g-3">
                <!-- Row 1: Basic Filters -->
                <div class="col-md-3">
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
                
                <div class="col-md-3">
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
                
                <div class="col-md-3">
                    <label for="followup_status" class="form-label">Status Follow-up</label>
                    <select name="followup_status" id="followup_status" class="form-select form-select-sm">
                        <option value="">Semua Follow-up</option>
                        <option value="today" {{ $filters['followup_status'] == 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="overdue" {{ $filters['followup_status'] == 'overdue' ? 'selected' : '' }}>Terlambat</option>
                        <option value="upcoming" {{ $filters['followup_status'] == 'upcoming' ? 'selected' : '' }}>Akan Datang</option>
                        <option value="no_followup" {{ $filters['followup_status'] == 'no_followup' ? 'selected' : '' }}>Belum Dijadwalkan</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="search" class="form-label">Cari Customer</label>
                    <input type="text" name="search" id="search" class="form-control form-control-sm" 
                           placeholder="Nama, email, atau phone..." value="{{ $filters['search'] }}">
                </div>

                <!-- Row 2: Advanced Filters (Collapsible) -->
                <div class="col-12">
                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#advancedFilters">
                        <i class="bi bi-plus-circle"></i> Filter Lanjutan
                    </button>
                </div>
                
                <div class="collapse" id="advancedFilters">
                    <div class="row g-3 pt-3 border-top">
                        <div class="col-md-3">
                            <label for="offer" class="form-label">Offer</label>
                            <select name="offer" id="offer" class="form-select form-select-sm">
                                <option value="">Semua Offer</option>
                                @foreach($filterOptions['availableOffers'] as $offer)
                                <option value="{{ $offer }}" {{ $filters['offer'] == $offer ? 'selected' : '' }}>
                                    {{ $offer }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="fu_count" class="form-label">Jumlah Follow-up</label>
                            <select name="fu_count" id="fu_count" class="form-select form-select-sm">
                                <option value="">Semua</option>
                                <option value="0" {{ $filters['fu_count'] == '0' ? 'selected' : '' }}>Belum Pernah (0)</option>
                                <option value="1" {{ $filters['fu_count'] == '1' ? 'selected' : '' }}>1 kali</option>
                                <option value="2" {{ $filters['fu_count'] == '2' ? 'selected' : '' }}>2 kali</option>
                                <option value="3" {{ $filters['fu_count'] == '3' ? 'selected' : '' }}>3 kali</option>
                                <option value="4" {{ $filters['fu_count'] == '4' ? 'selected' : '' }}>4 kali</option>
                                <option value="5+" {{ $filters['fu_count'] == '5+' ? 'selected' : '' }}>5+ kali</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="closing_status" class="form-label">Status Closing</label>
                            <select name="closing_status" id="closing_status" class="form-select form-select-sm">
                                <option value="">Semua</option>
                                <option value="closed" {{ $filters['closing_status'] == 'closed' ? 'selected' : '' }}>Sudah Closing</option>
                                <option value="open" {{ $filters['closing_status'] == 'open' ? 'selected' : '' }}>Belum Closing</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <label for="has_phone" class="form-label">Phone</label>
                            <select name="has_phone" id="has_phone" class="form-select form-select-sm">
                                <option value="">Semua</option>
                                <option value="yes" {{ $filters['has_phone'] == 'yes' ? 'selected' : '' }}>Ada Phone</option>
                                <option value="no" {{ $filters['has_phone'] == 'no' ? 'selected' : '' }}>Tanpa Phone</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <label for="has_email" class="form-label">Email</label>
                            <select name="has_email" id="has_email" class="form-select form-select-sm">
                                <option value="">Semua</option>
                                <option value="yes" {{ $filters['has_email'] == 'yes' ? 'selected' : '' }}>Ada Email</option>
                                <option value="no" {{ $filters['has_email'] == 'no' ? 'selected' : '' }}>Tanpa Email</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="date_from" class="form-label">Dari Tanggal</label>
                            <input type="date" name="date_from" id="date_from" class="form-control form-control-sm" 
                                   value="{{ $filters['date_from'] }}">
                        </div>
                        
                        <div class="col-md-3">
                            <label for="date_to" class="form-label">Sampai Tanggal</label>
                            <input type="date" name="date_to" id="date_to" class="form-control form-control-sm" 
                                   value="{{ $filters['date_to'] }}">
                        </div>
                    </div>
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
                        
                        @if(collect($filters)->filter()->count() > 0)
                        <div class="ms-3">
                            <small class="text-muted">
                                <i class="bi bi-funnel-fill"></i> 
                                {{ collect($filters)->filter()->count() }} filter aktif:
                                @foreach($filters as $key => $value)
                                    @if($value)
                                        <span class="badge bg-info ms-1">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                    @endif
                                @endforeach
                            </small>
                        </div>
                        @endif
                        
                        <!-- Quick Filter Buttons -->
                        <div class="ms-auto">
                            <div class="btn-group" role="group">
                                <a href="{{ route('dashboard', ['followup_status' => 'today']) }}" 
                                   class="btn btn-sm {{ $filters['followup_status'] == 'today' ? 'btn-info' : 'btn-outline-info' }}">
                                    Follow-up Hari Ini
                                </a>
                                <a href="{{ route('dashboard', ['followup_status' => 'overdue']) }}" 
                                   class="btn btn-sm {{ $filters['followup_status'] == 'overdue' ? 'btn-danger' : 'btn-outline-danger' }}">
                                    Terlambat
                                </a>
                                <a href="{{ route('dashboard', ['status' => 'hot']) }}" 
                                   class="btn btn-sm {{ $filters['status'] == 'hot' ? 'btn-warning' : 'btn-outline-warning' }}">
                                    Hot Leads
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Bulk Actions -->
@if($customers->count() > 0)
<div class="card mb-3">
    <div class="card-body py-2">
        {{-- <form method="POST" action="{{ route('customers.bulk-update') }}" id="bulkForm">
            @csrf
            <div class="row g-2 align-items-center">
                <div class="col-md-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAll">
                        <label class="form-check-label" for="selectAll">
                            Pilih Semua
                        </label>
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="action" class="form-select form-select-sm" required>
                        <option value="">Pilih Aksi</option>
                        <option value="update_status">Update Status</option>
                        <option value="update_followup">Set Follow-up</option>
                        <option value="add_notes">Tambah Catatan</option>
                    </select>
                </div>
                <div class="col-md-2" id="statusField" style="display: none;">
                    <select name="status_fu" class="form-select form-select-sm">
                        <option value="normal">Normal</option>
                        <option value="warm">Warm</option>
                        <option value="hot">Hot</option>
                        <option value="normal(prospect)">Normal (Prospect)</option>
                        <option value="warm(potential)">Warm (Potential)</option>
                        <option value="hot(closeable)">Hot (Closeable)</option>
                    </select>
                </div>
                <div class="col-md-2" id="dateField" style="display: none;">
                    <input type="date" name="followup_date" class="form-control form-control-sm">
                </div>
                <div class="col-md-3" id="notesField" style="display: none;">
                    <input type="text" name="notes" class="form-control form-control-sm" placeholder="Catatan...">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary btn-sm" id="bulkSubmit" disabled>
                        <i class="bi bi-check2-all"></i> Terapkan
                    </button>
                </div>
            </div>
        </form> --}}
    </div>
</div>
@endif

<!-- Customer List -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            Daftar Customer
            @if(collect($filters)->filter()->count() > 0)
            <small class="text-muted">(Terfilter)</small>
            @endif
        </h5>
        <div>
            <small class="text-muted">
                Menampilkan {{ $customers->firstItem() ?? 0 }}-{{ $customers->lastItem() ?? 0 }} 
                dari {{ $customers->total() }} customer
                @if(collect($filters)->filter()->count() > 0)
                ({{ Auth::user()->customers->count() }} total)
                @endif
            </small>
        </div>
    </div>
    <div class="card-body">
        @if($customers->count() > 0)
            <div class="row">
                @foreach($customers as $customer)
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card customer-card h-100 border-{{ $customer->status_fu == 'hot' || $customer->status_fu == 'hot(closeable)' ? 'danger' : ($customer->status_fu == 'warm' || $customer->status_fu == 'warm(potential)' ? 'warning' : 'secondary') }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="form-check">
                                    <input class="form-check-input customer-checkbox" type="checkbox" 
                                           name="customer_ids[]" value="{{ $customer->id }}" id="customer{{ $customer->id }}">
                                    <label class="form-check-label" for="customer{{ $customer->id }}">
                                        <h6 class="card-title mb-0">{{ $customer->nama }}</h6>
                                    </label>
                                </div>
                                <span class="badge bg-{{ $customer->status_fu == 'hot' || $customer->status_fu == 'hot(closeable)' ? 'danger' : ($customer->status_fu == 'warm' || $customer->status_fu == 'warm(potential)' ? 'warning' : 'secondary') }} status-badge">
                                    {{ str_replace(['(', ')'], ['<br><small>(', ')</small>'], ucfirst($customer->status_fu)) }}{!! str_contains($customer->status_fu, '(') ? '' : '' !!}
                                </span>
                            </div>
                            
                            <div class="row mb-2">
                                @if($customer->sheet_month)
                                <div class="col-6">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar3"></i> {{ $customer->sheet_month }}
                                    </small>
                                </div>
                                @endif
                                @if($customer->fu_jumlah > 0)
                                <div class="col-6">
                                    <small class="text-muted">
                                        <i class="bi bi-arrow-repeat"></i> FU: {{ $customer->fu_jumlah }}x
                                    </small>
                                </div>
                                @endif
                            </div>
                            
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-envelope"></i> {{ $customer->email ?: 'No Email' }}
                                </small>
                            </div>
                            
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-telephone"></i> {{ $customer->phone ?: 'No Phone' }}
                                </small>
                            </div>
                            
                            @if($customer->interest)
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-heart"></i> {{ Str::limit($customer->interest, 20) }}
                                </small>
                            </div>
                            @endif
                            
                            @if($customer->offer)
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-gift"></i> {{ Str::limit($customer->offer, 20) }}
                                </small>
                            </div>
                            @endif
                            
                            @if($customer->followup_date)
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-calendar"></i> Follow-up: {{ $customer->followup_date->format('d/m/Y') }}
                                    @if($customer->followup_date->isToday())
                                        <span class="badge bg-info text-dark ms-1">Hari Ini</span>
                                    @elseif($customer->followup_date->isPast())
                                        <span class="badge bg-danger ms-1">Terlambat</span>
                                    @elseif($customer->followup_date->isTomorrow())
                                        <span class="badge bg-warning text-dark ms-1">Besok</span>
                                    @endif
                                </small>
                            </div>
                            @endif
                            
                            @if($customer->tanggal_closing)
                            <div class="mb-2">
                                <small class="text-success">
                                    <i class="bi bi-check-circle"></i> Closing: {{ $customer->tanggal_closing }}
                                </small>
                            </div>
                            @endif
                            
                            @if($customer->notes)
                            <div class="mb-3">
                                <small class="text-muted">
                                    <strong>Notes:</strong> {{ Str::limit($customer->notes, 80) }}
                                </small>
                            </div>
                            @endif
                            
                            <div class="d-flex gap-1 flex-wrap">
                                @if($customer->phone)
                                <a href="{{ $customer->getWhatsAppUrl() }}" target="_blank" class="btn btn-success btn-sm">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                                @endif
                                @if($customer->email)
                                <a href="mailto:{{ $customer->email }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-envelope"></i>
                                </a>
                                @endif
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $customer->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                @if($customer->followup_date && $customer->followup_date->isToday())
                                <button type="button" class="btn btn-warning btn-sm" title="Follow-up Hari Ini">
                                    <i class="bi bi-alarm"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                        
                        @if($customer->followup_date || $customer->tanggal_closing)
                        <div class="card-footer bg-light py-1">
                            <div class="row">
                                @if($customer->followup_date)
                                <div class="col-6">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-event"></i> 
                                        {{ $customer->followup_date->diffForHumans() }}
                                    </small>
                                </div>
                                @endif
                                @if($customer->tanggal_closing)
                                <div class="col-6">
                                    <small class="text-success">
                                        <i class="bi bi-trophy"></i> Closed
                                    </small>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
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
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="status_fu{{ $customer->id }}" class="form-label">Status</label>
                                                <select name="status_fu" id="status_fu{{ $customer->id }}" class="form-select">
                                                    <option value="normal" {{ $customer->status_fu == 'normal' ? 'selected' : '' }}>Normal</option>
                                                    <option value="normal(prospect)" {{ $customer->status_fu == 'normal(prospect)' ? 'selected' : '' }}>Normal (Prospect)</option>
                                                    <option value="warm" {{ $customer->status_fu == 'warm' ? 'selected' : '' }}>Warm</option>
                                                    <option value="warm(potential)" {{ $customer->status_fu == 'warm(potential)' ? 'selected' : '' }}>Warm (Potential)</option>
                                                    <option value="hot" {{ $customer->status_fu == 'hot' ? 'selected' : '' }}>Hot</option>
                                                    <option value="hot(closeable)" {{ $customer->status_fu == 'hot(closeable)' ? 'selected' : '' }}>Hot (Closeable)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="followup_date{{ $customer->id }}" class="form-label">Tanggal Follow-up</label>
                                                <input type="date" name="followup_date" id="followup_date{{ $customer->id }}" class="form-control" 
                                                       value="{{ $customer->followup_date ? $customer->followup_date->format('Y-m-d') : '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Customer Info Display -->
                                    <div class="mb-3">
                                        <h6>Informasi Customer:</h6>
                                        <div class="bg-light p-2 rounded">
                                            <small>
                                                <strong>Sheet:</strong> {{ $customer->sheet_month }}<br>
                                                <strong>Interest:</strong> {{ $customer->interest ?: 'N/A' }}<br>
                                                <strong>Offer:</strong> {{ $customer->offer ?: 'N/A' }}<br>
                                                <strong>FU Count:</strong> {{ $customer->fu_jumlah }}x<br>
                                                @if($customer->tanggal_closing)
                                                <strong class="text-success">Closing:</strong> {{ $customer->tanggal_closing }}
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="notes{{ $customer->id }}" class="form-label">Catatan</label>
                                        <textarea name="notes" id="notes{{ $customer->id }}" class="form-control" rows="4" 
                                                  placeholder="Tambahkan catatan follow-up, hasil kontak, dll...">{{ $customer->notes }}</textarea>
                                        <small class="text-muted">Catatan akan ditambahkan dengan timestamp otomatis</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
                    @if(collect($filters)->filter()->count() > 0)
                        Tidak ada customer dengan filter yang dipilih
                    @else
                        Belum ada customer
                    @endif
                </h5>
                <p class="text-muted">
                    @if(collect($filters)->filter()->count() > 0)
                        Coba ubah filter atau <a href="{{ route('dashboard') }}">reset semua filter</a>
                    @else
                        Customer akan muncul otomatis ketika data dikirim dari Google Spreadsheet
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Data Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Export data customer dengan filter yang sedang aktif:</p>
                <ul class="list-unstyled">
                    @foreach($filters as $key => $value)
                        @if($value)
                            <li><i class="bi bi-check text-success"></i> {{ ucfirst(str_replace('_', ' ', $key)) }}: <strong>{{ $value }}</strong></li>
                        @endif
                    @endforeach
                    @if(collect($filters)->filter()->count() === 0)
                        <li><i class="bi bi-info-circle text-info"></i> Semua data customer akan diexport</li>
                    @endif
                </ul>
                <p class="text-muted">Format: CSV dengan semua kolom customer</p>
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
<script>
// Select All Checkbox
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.customer-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    toggleBulkSubmit();
});

// Individual checkbox change
document.querySelectorAll('.customer-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        toggleBulkSubmit();
        
        // Update select all checkbox
        const allCheckboxes = document.querySelectorAll('.customer-checkbox');
        const checkedCheckboxes = document.querySelectorAll('.customer-checkbox:checked');
        document.getElementById('selectAll').checked = allCheckboxes.length === checkedCheckboxes.length;
    });
});

// Action dropdown change
document.querySelector('select[name="action"]').addEventListener('change', function() {
    const action = this.value;
    
    // Hide all conditional fields
    document.getElementById('statusField').style.display = 'none';
    document.getElementById('dateField').style.display = 'none';
    document.getElementById('notesField').style.display = 'none';
    
    // Show relevant field based on action
    switch(action) {
        case 'update_status':
            document.getElementById('statusField').style.display = 'block';
            break;
        case 'update_followup':
            document.getElementById('dateField').style.display = 'block';
            break;
        case 'add_notes':
            document.getElementById('notesField').style.display = 'block';
            break;
    }
    
    toggleBulkSubmit();
});

function toggleBulkSubmit() {
    const checkedBoxes = document.querySelectorAll('.customer-checkbox:checked');
    const action = document.querySelector('select[name="action"]').value;
    const submitBtn = document.getElementById('bulkSubmit');
    
    submitBtn.disabled = checkedBoxes.length === 0 || !action;
}

// Auto-submit form on filter change (for better UX)
document.querySelectorAll('#filterForm select').forEach(select => {
    select.addEventListener('change', function() {
        if (this.name !== 'action') { // Don't auto-submit for bulk action
            // Add small delay to allow multiple quick changes
            clearTimeout(window.filterTimeout);
            window.filterTimeout = setTimeout(() => {
                document.getElementById('filterForm').submit();
            }, 500);
        }
    });
});

// Prevent auto-submit on search input, use enter key instead
document.getElementById('search').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        document.getElementById('filterForm').submit();
    }
});

// Add loading state to filter form
document.getElementById('filterForm').addEventListener('submit', function() {
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Loading...';
    submitBtn.disabled = true;
    
    // Re-enable after a delay (in case of errors)
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 5000);
});

// Bulk form submission with confirmation
document.getElementById('bulkForm').addEventListener('submit', function(e) {
    const checkedBoxes = document.querySelectorAll('.customer-checkbox:checked');
    const action = document.querySelector('select[name="action"]').value;
    
    if (!confirm(`Yakin ingin ${action.replace('_', ' ')} untuk ${checkedBoxes.length} customer?`)) {
        e.preventDefault();
    }
});

// Add tooltips for status badges
document.querySelectorAll('.status-badge').forEach(badge => {
    const status = badge.textContent.trim();
    let tooltip = '';
    
    switch(status.toLowerCase()) {
        case 'normal':
            tooltip = 'Customer biasa, follow-up standar';
            break;
        case 'normal (prospect)':
            tooltip = 'Prospect normal, berpotensi';
            break;
        case 'warm':
            tooltip = 'Customer tertarik, perlu follow-up intensif';
            break;
        case 'warm (potential)':
            tooltip = 'Potential customer, kemungkinan closing tinggi';
            break;
        case 'hot':
            tooltip = 'Customer sangat tertarik, prioritas tinggi';
            break;
        case 'hot (closeable)':
            tooltip = 'Siap closing, tindak lanjut segera';
            break;
    }
    
    badge.title = tooltip;
});
</script>