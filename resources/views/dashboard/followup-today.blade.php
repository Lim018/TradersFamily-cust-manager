@extends('layouts.app')

@section('title', 'Follow-up Hari Ini')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Follow-up Hari Ini</h1>
            <p class="mb-0 text-muted">{{ now()->format('l, d F Y') }} - {{ $customers->count() }} customer perlu di follow-up</p>
        </div>
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
            {{-- <a href="{{ route('dashboard.export', array_merge(request()->query(), ['followup_status' => 'today'])) }}" class="btn btn-success">
                <i class="fas fa-download"></i> Export CSV
            </a> --}}
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Follow-up</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $customers->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Hot Leads</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $customers->where('status_fu', 'hot')->count() + $customers->where('status_fu', 'hot(closeable)')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-fire fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Warm Leads</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $customers->where('status_fu', 'warm')->count() + $customers->where('status_fu', 'warm(potential)')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-thermometer-half fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Dengan Phone</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $customers->whereNotNull('phone')->where('phone', '!=', '')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-phone fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Priority Notice -->
    @if($customers->whereIn('status_fu', ['hot', 'hot(closeable)'])->count() > 0)
    <div class="alert alert-danger mb-4" role="alert">
        <i class="fas fa-exclamation-triangle"></i>
        <strong>Prioritas Tinggi!</strong> Terdapat {{ $customers->whereIn('status_fu', ['hot', 'hot(closeable)'])->count() }} hot leads yang perlu segera di follow-up hari ini.
    </div>
    @endif

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Data</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('followup.today') }}" class="row g-3">
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

                @if(Auth::user()->isAdmin())
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
                @endif

                <div class="col-md-2">
                    <label class="form-label">Interest</label>
                    <select name="interest" class="form-select">
                        <option value="">Semua Interest</option>
                        @foreach($filterOptions['availableInterests'] as $interest)
                            <option value="{{ $interest }}" {{ $filters['interest'] == $interest ? 'selected' : '' }}>
                                {{ $interest }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Pencarian</label>
                    <input type="text" name="search" class="form-control" placeholder="Nama, Email, Phone" value="{{ $filters['search'] }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">FU Count</label>
                    <select name="fu_count" class="form-select">
                        <option value="">Semua</option>
                        <option value="0" {{ $filters['fu_count'] == '0' ? 'selected' : '' }}>Belum pernah FU</option>
                        <option value="1" {{ $filters['fu_count'] == '1' ? 'selected' : '' }}>1x FU</option>
                        <option value="2" {{ $filters['fu_count'] == '2' ? 'selected' : '' }}>2x FU</option>
                        <option value="3" {{ $filters['fu_count'] == '3' ? 'selected' : '' }}>3x FU</option>
                        <option value="4" {{ $filters['fu_count'] == '4' ? 'selected' : '' }}>4x FU</option>
                        <option value="5+" {{ $filters['fu_count'] == '5+' ? 'selected' : '' }}>5+ FU</option>
                    </select>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('followup.today') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Customer List -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Customer Follow-up Hari Ini</h6>
            <div>
                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#bulkActionModal">
                    Bulk Action
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <form id="bulkForm">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="30">
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>Priority</th>
                                <th>Customer Info</th>
                                <th>Contact</th>
                                <th>Status & Interest</th>
                                @if(Auth::user()->isAdmin())
                                <th>Agent</th>
                                @endif
                                <th>FU Count</th>
                                <th>Notes</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $customer)
                                <tr class="{{ str_contains(strtolower($customer->status_fu), 'hot') ? 'table-danger' : (str_contains(strtolower($customer->status_fu), 'warm') ? 'table-warning' : '') }}">
                                    <td>
                                        <input type="checkbox" name="customer_ids[]" value="{{ $customer->id }}" class="customer-checkbox">
                                    </td>
                                    <td class="text-center">
                                        @if(str_contains(strtolower($customer->status_fu), 'hot'))
                                            <i class="fas fa-fire text-danger" title="Hot Lead - Priority Tinggi"></i>
                                        @elseif(str_contains(strtolower($customer->status_fu), 'warm'))
                                            <i class="fas fa-thermometer-half text-warning" title="Warm Lead - Priority Sedang"></i>
                                        @else
                                            <i class="fas fa-user text-secondary" title="Normal Lead"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $customer->nama }}</strong>
                                            <br><small class="text-muted">{{ $customer->tanggal }} | {{ $customer->sheet_month }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mb-1">
                                            @if($customer->phone)
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $customer->phone) }}" 
                                                   target="_blank" class="btn btn-success btn-sm">
                                                    <i class="fab fa-whatsapp"></i> {{ $customer->phone }}
                                                </a>
                                            @else
                                                <span class="text-muted">No Phone</span>
                                            @endif
                                        </div>
                                        @if($customer->email)
                                            <div>
                                                <small><i class="fas fa-envelope"></i> {{ $customer->email }}</small>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="mb-2">
                                            <span class="badge 
                                                @if(str_contains(strtolower($customer->status_fu), 'hot')) badge-danger
                                                @elseif(str_contains(strtolower($customer->status_fu), 'warm')) badge-warning  
                                                @else badge-secondary
                                                @endif">
                                                {{ $customer->status_fu }}
                                            </span>
                                        </div>
                                        @if($customer->interest)
                                            <small class="text-muted">
                                                <i class="fas fa-heart"></i> {{ Str::limit($customer->interest, 20) }}
                                            </small>
                                        @endif
                                        @if($customer->offer)
                                            <br><small class="text-info">
                                                <i class="fas fa-tag"></i> {{ Str::limit($customer->offer, 20) }}
                                            </small>
                                        @endif
                                    </td>
                                    @if(Auth::user()->isAdmin())
                                    <td>
                                        <span class="badge badge-info">{{ $customer->user->name ?? 'Unknown' }}</span>
                                    </td>
                                    @endif
                                    <td class="text-center">
                                        <span class="badge badge-primary">{{ $customer->fu_jumlah }}x</span>
                                    </td>
                                    <td>
                                        @if($customer->notes)
                                            <div class="text-truncate" style="max-width: 150px;" title="{{ $customer->notes }}">
                                                {{ Str::limit($customer->notes, 50) }}
                                            </div>
                                        @else
                                            <span class="text-muted">No notes</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group-vertical" role="group">
                                            @if($customer->phone)
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $customer->phone) }}" 
                                                   target="_blank" class="btn btn-success btn-sm mb-1">
                                                    <i class="fab fa-whatsapp"></i> WA
                                                </a>
                                            @endif
                                            <a href="{{ route('customers.show', $customer) }}" class="btn btn-info btn-sm mb-1">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ Auth::user()->isAdmin() ? '9' : '8' }}" class="text-center py-4">
                                        <div class="text-center">
                                            <i class="fas fa-calendar-check fa-3x text-gray-300 mb-3"></i>
                                            <h5 class="text-gray-500">Tidak ada follow-up hari ini</h5>
                                            <p class="text-muted">Semua customer sudah ter-follow-up atau belum ada yang dijadwalkan hari ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Action - Follow-up Hari Ini</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            {{-- <form action="{{ route('dashboard.bulk-update') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="customer_ids" id="selectedCustomerIds">
                    
                    <div class="mb-3">
                        <label class="form-label">Pilih Aksi</label>
                        <select name="action" id="bulkAction" class="form-select" required>
                            <option value="">-- Pilih Aksi --</option>
                            <option value="update_status">Update Status FU</option>
                            <option value="update_followup">Update Tanggal Follow-up Berikutnya</option>
                            <option value="add_notes">Tambah Catatan Follow-up</option>
                        </select>
                    </div>

                    <div id="statusField" class="mb-3" style="display: none;">
                        <label class="form-label">Status FU Baru</label>
                        <select name="status_fu" class="form-select">
                            <option value="normal">Normal</option>
                            <option value="warm">Warm</option>
                            <option value="hot">Hot</option>
                            <option value="normal(prospect)">Normal (Prospect)</option>
                            <option value="warm(potential)">Warm (Potential)</option>
                            <option value="hot(closeable)">Hot (Closeable)</option>
                        </select>
                        <div class="form-text">Update status berdasarkan hasil follow-up hari ini</div>
                    </div>

                    <div id="followupField" class="mb-3" style="display: none;">
                        <label class="form-label">Tanggal Follow-up Berikutnya</label>
                        <input type="date" name="followup_date" class="form-control" min="{{ now()->addDay()->format('Y-m-d') }}">
                        <div class="form-text">Jadwalkan follow-up berikutnya</div>
                    </div>

                    <div id="notesField" class="mb-3" style="display: none;">
                        <label class="form-label">Catatan Follow-up</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Hasil follow-up hari ini..."></textarea>
                        <div class="form-text">Catatan akan ditambahkan dengan timestamp</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Proses Follow-up</button>
                </div>
            </form> --}}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkbox functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    const customerCheckboxes = document.querySelectorAll('.customer-checkbox');
    
    selectAllCheckbox.addEventListener('change', function() {
        customerCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Bulk action modal
    const bulkAction = document.getElementById('bulkAction');
    const statusField = document.getElementById('statusField');
    const followupField = document.getElementById('followupField');
    const notesField = document.getElementById('notesField');

    bulkAction.addEventListener('change', function() {
        // Hide all fields first
        statusField.style.display = 'none';
        followupField.style.display = 'none';
        notesField.style.display = 'none';

        // Show relevant field
        switch(this.value) {
            case 'update_status':
                statusField.style.display = 'block';
                break;
            case 'update_followup':
                followupField.style.display = 'block';
                break;
            case 'add_notes':
                notesField.style.display = 'block';
                break;
        }
    });

    // Handle bulk action form submission
    document.querySelector('#bulkActionModal form').addEventListener('submit', function(e) {
        const selectedIds = Array.from(document.querySelectorAll('.customer-checkbox:checked'))
                                .map(cb => cb.value);
        
        if (selectedIds.length === 0) {
            e.preventDefault();
            alert('Pilih minimal satu customer!');
            return;
        }

        // Confirm action
        const action = bulkAction.value;
        let confirmMessage = `Anda akan melakukan ${action} untuk ${selectedIds.length} customer. Lanjutkan?`;
        
        if (!confirm(confirmMessage)) {
            e.preventDefault();
            return;
        }

        document.getElementById('selectedCustomerIds').value = JSON.stringify(selectedIds);
    });

    // Auto-refresh notification for new follow-ups
    setInterval(function() {
        // Check if there are new follow-ups (you can implement this via AJAX)
        // This is just a placeholder for the functionality
    }, 300000); // Check every 5 minutes
});
</script>

@endsection