@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2">
            <i class="bi bi-calendar-check text-info"></i> Follow-up Hari Ini
        </h1>
        <small class="text-muted">{{ now()->format('l, d F Y') }}</small>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="location.reload()">
                <i class="bi bi-arrow-clockwise"></i> Refresh
            </button>
        </div>
    </div>
</div>

<!-- Priority Stats -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-danger">
            <div class="card-body text-center">
                <i class="bi bi-exclamation-triangle-fill fs-1 text-danger"></i>
                <h3 class="text-danger mt-2">{{ $customers->where('status_fu', 'hot(closeable)')->count() }}</h3>
                <small class="text-muted">Hot (Closeable) - PRIORITAS TINGGI</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-warning">
            <div class="card-body text-center">
                <i class="bi bi-fire fs-1 text-warning"></i>
                <h3 class="text-warning mt-2">{{ $customers->where('status_fu', 'hot')->count() }}</h3>
                <small class="text-muted">Hot - Urgent</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-info">
            <div class="card-body text-center">
                <i class="bi bi-thermometer-half fs-1 text-info"></i>
                <h3 class="text-info mt-2">{{ $customers->whereIn('status_fu', ['warm', 'warm(potential)'])->count() }}</h3>
                <small class="text-muted">Warm - Medium</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-secondary">
            <div class="card-body text-center">
                <i class="bi bi-person fs-1 text-secondary"></i>
                <h3 class="text-secondary mt-2">{{ $customers->whereIn('status_fu', ['normal', 'normal(prospect)'])->count() }}</h3>
                <small class="text-muted">Normal - Low</small>
            </div>
        </div>
    </div>
</div>

<!-- Quick Filter untuk Follow-up Today -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="bi bi-funnel"></i> Filter Follow-up Hari Ini
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('followup.today') }}" id="filterForm">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="hot(closeable)" {{ $filters['status'] == 'hot(closeable)' ? 'selected' : '' }}>Hot (Closeable)</option>
                        <option value="hot" {{ $filters['status'] == 'hot' ? 'selected' : '' }}>Hot</option>
                        <option value="warm(potential)" {{ $filters['status'] == 'warm(potential)' ? 'selected' : '' }}>Warm (Potential)</option>
                        <option value="warm" {{ $filters['status'] == 'warm' ? 'selected' : '' }}>Warm</option>
                        <option value="normal(prospect)" {{ $filters['status'] == 'normal(prospect)' ? 'selected' : '' }}>Normal (Prospect)</option>
                        <option value="normal" {{ $filters['status'] == 'normal' ? 'selected' : '' }}>Normal</option>
                    </select>
                </div>
                
                @if(Auth::user()->isAdmin())
                <div class="col-md-3">
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
                @endif
                
                <div class="col-md-3">
                    <label for="search" class="form-label">Cari Customer</label>
                    <input type="text" name="search" id="search" class="form-control form-control-sm" 
                           placeholder="Nama, email, phone..." value="{{ $filters['search'] }}">
                </div>
                
                <div class="col-md-3 d-flex align-items-end">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        <a href="{{ route('followup.today') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-x-circle"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@if($customers->count() > 0)
<!-- Customer List untuk Follow-up Hari Ini -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-list-check"></i> Daftar Follow-up Hari Ini
            <span class="badge bg-info ms-2">{{ $customers->count() }} customer</span>
        </h5>
    </div>
    <div class="card-body">
        <!-- Prioritas Tinggi: Hot Closeable -->
        @php $hotCloseableCustomers = $customers->where('status_fu', 'hot(closeable)'); @endphp
        @if($hotCloseableCustomers->count() > 0)
        <div class="mb-4">
            <h6 class="text-danger mb-3">
                <i class="bi bi-exclamation-triangle-fill"></i> PRIORITAS TINGGI - Hot (Closeable)
                <span class="badge bg-danger">{{ $hotCloseableCustomers->count() }}</span>
            </h6>
            <div class="row">
                @foreach($hotCloseableCustomers as $customer)
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card border-danger shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-0 text-danger">{{ $customer->nama }}</h6>
                                <span class="badge bg-danger">HOT CLOSEABLE</span>
                            </div>
                            
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-telephone"></i> {{ $customer->phone ?: 'No Phone' }}
                                </small>
                            </div>
                            
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-envelope"></i> {{ $customer->email ?: 'No Email' }}
                                </small>
                            </div>
                            
                            @if(Auth::user()->isAdmin())
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-person-badge"></i> {{ $customer->user->name }}
                                </small>
                            </div>
                            @endif
                            
                            @if($customer->interest)
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-heart"></i> {{ Str::limit($customer->interest, 30) }}
                                </small>
                            </div>
                            @endif
                            
                            @if($customer->offer)
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-gift"></i> {{ Str::limit($customer->offer, 30) }}
                                </small>
                            </div>
                            @endif
                            
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-arrow-repeat"></i> FU ke-{{ $customer->fu_jumlah + 1 }} 
                                    @if($customer->sheet_month)
                                    | {{ $customer->sheet_month }}
                                    @endif
                                </small>
                            </div>
                            
                            @if($customer->notes)
                            <div class="mb-3">
                                <small class="text-muted">
                                    <strong>Last Notes:</strong> {{ Str::limit($customer->notes, 60) }}
                                </small>
                            </div>
                            @endif
                            
                            <div class="d-flex gap-1 flex-wrap">
                                @if($customer->phone)
                                <a href="{{ $customer->getWhatsAppUrl() }}" target="_blank" class="btn btn-success btn-sm">
                                    <i class="bi bi-whatsapp"></i> WA
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
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Hot -->
        @php $hotCustomers = $customers->where('status_fu', 'hot'); @endphp
        @if($hotCustomers->count() > 0)
        <div class="mb-4">
            <h6 class="text-warning mb-3">
                <i class="bi bi-fire"></i> Hot Leads
                <span class="badge bg-warning text-dark">{{ $hotCustomers->count() }}</span>
            </h6>
            <div class="row">
                @foreach($hotCustomers as $customer)
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card border-warning shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-0">{{ $customer->nama }}</h6>
                                <span class="badge bg-warning text-dark">HOT</span>
                            </div>
                            
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-telephone"></i> {{ $customer->phone ?: 'No Phone' }}
                                </small>
                            </div>
                            
                            @if(Auth::user()->isAdmin())
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-person-badge"></i> {{ $customer->user->name }}
                                </small>
                            </div>
                            @endif
                            
                            @if($customer->interest || $customer->offer)
                            <div class="mb-2">
                                <small class="text-muted">
                                    @if($customer->interest)
                                    <i class="bi bi-heart"></i> {{ Str::limit($customer->interest, 25) }}
                                    @endif
                                    @if($customer->offer)
                                    <br><i class="bi bi-gift"></i> {{ Str::limit($customer->offer, 25) }}
                                    @endif
                                </small>
                            </div>
                            @endif
                            
                            <div class="d-flex gap-1 flex-wrap">
                                @if($customer->phone)
                                <a href="{{ $customer->getWhatsAppUrl() }}" target="_blank" class="btn btn-success btn-sm">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                                @endif
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $customer->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Warm -->
        @php $warmCustomers = $customers->whereIn('status_fu', ['warm', 'warm(potential)']); @endphp
        @if($warmCustomers->count() > 0)
        <div class="mb-4">
            <h6 class="text-info mb-3">
                <i class="bi bi-thermometer-half"></i> Warm Leads
                <span class="badge bg-info">{{ $warmCustomers->count() }}</span>
            </h6>
            <div class="row">
                @foreach($warmCustomers as $customer)
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card border-info shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-0">{{ $customer->nama }}</h6>
                                <span class="badge bg-info">{{ strtoupper($customer->status_fu) }}</span>
                            </div>
                            
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-telephone"></i> {{ $customer->phone ?: 'No Phone' }}
                                </small>
                            </div>
                            
                            @if(Auth::user()->isAdmin())
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-person-badge"></i> {{ $customer->user->name }}
                                </small>
                            </div>
                            @endif
                            
                            <div class="d-flex gap-1 flex-wrap">
                                @if($customer->phone)
                                <a href="{{ $customer->getWhatsAppUrl() }}" target="_blank" class="btn btn-success btn-sm">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                                @endif
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $customer->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Normal -->
        @php $normalCustomers = $customers->whereIn('status_fu', ['normal', 'normal(prospect)']); @endphp
        @if($normalCustomers->count() > 0)
        <div class="mb-4">
            <h6 class="text-secondary mb-3">
                <i class="bi bi-person"></i> Normal
                <span class="badge bg-secondary">{{ $normalCustomers->count() }}</span>
            </h6>
            <div class="row">
                @foreach($normalCustomers as $customer)
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card border-secondary shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-0">{{ $customer->nama }}</h6>
                                <span class="badge bg-secondary">{{ strtoupper($customer->status_fu) }}</span>
                            </div>
                            
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-telephone"></i> {{ $customer->phone ?: 'No Phone' }}
                                </small>
                            </div>
                            
                            @if(Auth::user()->isAdmin())
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-person-badge"></i> {{ $customer->user->name }}
                                </small>
                            </div>
                            @endif
                            
                            <div class="d-flex gap-1 flex-wrap">
                                @if($customer->phone)
                                <a href="{{ $customer->getWhatsAppUrl() }}" target="_blank" class="btn btn-success btn-sm">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                                @endif
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $customer->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Edit Modals -->
@foreach($customers as $customer)
<div class="modal fade" id="editModal{{ $customer->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('customers.update', $customer) }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Follow-up: {{ $customer->nama }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> <strong>Follow-up hari ini untuk customer {{ $customer->nama }}</strong>
                        <br><small>Update status dan catat hasil follow-up</small>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status_fu{{ $customer->id }}" class="form-label">Update Status</label>
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
                                <label for="followup_date{{ $customer->id }}" class="form-label">Next Follow-up</label>
                                <input type="date" name="followup_date" id="followup_date{{ $customer->id }}" class="form-control" 
                                       value="{{ now()->addDays(1)->format('Y-m-d') }}" min="{{ now()->format('Y-m-d') }}">
                                <small class="text-muted">Kosongkan jika tidak perlu follow-up lagi</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Customer Contact Info -->
                    <div class="mb-3">
                        <h6>Info Kontak:</h6>
                        <div class="bg-light p-2 rounded">
                            <small>
                                <strong>Phone:</strong> {{ $customer->phone ?: 'N/A' }}<br>
                                <strong>Email:</strong> {{ $customer->email ?: 'N/A' }}<br>
                                <strong>Interest:</strong> {{ $customer->interest ?: 'N/A' }}<br>
                                <strong>FU Count:</strong> {{ $customer->fu_jumlah }}x
                            </small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes{{ $customer->id }}" class="form-label">Hasil Follow-up</label>
                        <textarea name="notes" id="notes{{ $customer->id }}" class="form-control" rows="4" 
                                  placeholder="Catat hasil follow-up: respon customer, next action, dll..." required></textarea>
                        <small class="text-muted">Wajib diisi untuk follow-up hari ini</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Selesai Follow-up
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@else
<!-- Empty State -->
<div class="card">
    <div class="card-body text-center py-5">
        <i class="bi bi-calendar-check fs-1 text-muted"></i>
        <h4 class="text-muted mt-3">Tidak Ada Follow-up Hari Ini</h4>
        <p class="text-muted">
            @if(collect($filters)->filter()->count() > 0)
                Tidak ada customer dengan filter yang dipilih untuk follow-up hari ini.
                <br><a href="{{ route('dashboard.followup-today') }}">Reset filter</a> untuk melihat semua.
            @else
                Tidak ada customer yang dijadwalkan untuk follow-up hari ini.
                <br>Kembali ke <a href="{{ route('dashboard') }}">dashboard</a> untuk mengatur jadwal follow-up.
            @endif
        </p>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
// Auto-submit form on filter change
document.querySelectorAll('#filterForm select').forEach(select => {
    select.addEventListener('change', function() {
        clearTimeout(window.filterTimeout);
        window.filterTimeout = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 300);
    });
});

// Search on enter
document.getElementById('search').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        document.getElementById('filterForm').submit();
    }
});

// Set default next followup date based on status
document.querySelectorAll('select[name="status_fu"]').forEach(select => {
    select.addEventListener('change', function() {
        const status = this.value;
        const modalId = this.id.replace('status_fu', '');
        const followupInput = document.getElementById('followup_date' + modalId);
        
        // Set suggested next followup based on status
        let daysToAdd = 3; // default
        
        switch(status) {
            case 'hot(closeable)':
                daysToAdd = 1; // tomorrow
                break;
            case 'hot':
                daysToAdd = 2;
                break;
            case 'warm(potential)':
                daysToAdd = 3;
                break;
            case 'warm':
                daysToAdd = 5;
                break;
            case 'normal(prospect)':
                daysToAdd = 7;
                break;
            case 'normal':
                daysToAdd = 14;
                break;
        }
        
        const nextDate = new Date();
        nextDate.setDate(nextDate.getDate() + daysToAdd);
        followupInput.value = nextDate.toISOString().split('T')[0];
    });
});

// Add confirmation for completing followup
document.querySelectorAll('form').forEach(form => {
    if (form.action.includes('customers')) {
        form.addEventListener('submit', function(e) {
            const customerName = this.querySelector('.modal-title').textContent.split(': ')[1];
            const notes = this.querySelector('textarea[name="notes"]').value;
            
            if (!notes.trim()) {
                e.preventDefault();
                alert('Hasil follow-up wajib diisi!');
                return;
            }
            
            if (!confirm(`Yakin sudah selesai follow-up dengan ${customerName}?`)) {
                e.preventDefault();
            }
        });
    }
});
</script>
@endpush

@push('styles')
<style>
.border-danger {
    border-color: #dc3545 !important;
}

.border-warning {
    border-color: #ffc107 !important;
}

.border-info {
    border-color: #17a2b8 !important;
}

.border-secondary {
    border-color: #6c757d !important;
}

.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

.card:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease-in-out;
}
</style>
@endpush