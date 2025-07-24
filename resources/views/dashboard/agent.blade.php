@extends('layouts.app')

@section('title', 'Dashboard Agent')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Dashboard Agent</h1>
            <p class="mb-0 text-muted">Selamat datang, {{ Auth::user()->name }}</p>
        </div>
        <div>
            <a href="{{ route('followup.today') }}" class="btn btn-warning me-2">
                <i class="fas fa-clock"></i> Follow-up Hari Ini 
                @if(isset($stats['followup_today']) && $stats['followup_today'] > 0)
                    <span class="badge bg-danger">{{ $stats['followup_today'] }}</span>
                @endif
            </a>
            {{-- <a href="{{ route('dashboard.export', request()->query()) }}" class="btn btn-success">
                <i class="fas fa-download"></i> Export CSV
            </a> --}}
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Hot Leads</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['hot'] + $stats['hot_closeable'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-fire fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Conversion Rate</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['conversion_rate'] }}%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Distribution -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Status Distribution</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="badge badge-secondary p-3 mb-2 w-100">
                                Normal: {{ $stats['normal'] + $stats['normal_prospect'] }}
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="badge badge-warning p-3 mb-2 w-100">
                                Warm: {{ $stats['warm'] + $stats['warm_potential'] }}
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="badge badge-danger p-3 mb-2 w-100">
                                Hot: {{ $stats['hot'] + $stats['hot_closeable'] }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Follow-up Status</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <span class="badge badge-warning">Hari Ini: {{ $stats['followup_today'] }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="badge badge-danger">Terlambat: {{ $stats['followup_overdue'] }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="badge badge-info">Mendatang: {{ $stats['followup_upcoming'] }}</span>
                    </div>
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
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] }}">
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Customer List -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Customer ({{ $customers->total() }})</h6>
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
                                <th>Tanggal</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status FU</th>
                                <th>Sheet Month</th>
                                <th>Interest</th>
                                <th>FU Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $customer)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="customer_ids[]" value="{{ $customer->id }}" class="customer-checkbox">
                                    </td>
                                    <td>{{ $customer->tanggal }}</td>
                                    <td>
                                        <strong>{{ $customer->nama }}</strong>
                                        @if($customer->notes)
                                            <br><small class="text-muted">{{ Str::limit($customer->notes, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $customer->email }}</td>
                                    <td>
                                        {{ $customer->phone }}
                                        @if($customer->phone)
                                            <a href="https://wa.me/+62{{ preg_replace('/[^0-9]/', '', $customer->phone) }}" 
                                               target="_blank" class="btn btn-success btn-sm ms-1">
                                                <i class="fab fa-whatsapp"></i>
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if(str_contains(strtolower($customer->status_fu), 'hot')) badge-danger
                                            @elseif(str_contains(strtolower($customer->status_fu), 'warm')) badge-warning  
                                            @else badge-secondary
                                            @endif">
                                            {{ $customer->status_fu }}
                                        </span>
                                    </td>
                                    <td>{{ $customer->sheet_month }}</td>
                                    <td>{{ $customer->interest }}</td>
                                    <td>
                                        @if($customer->followup_date)
                                            <span class="badge 
                                                @if($customer->followup_date->isToday()) badge-warning
                                                @elseif($customer->followup_date->isPast()) badge-danger
                                                @else badge-info
                                                @endif">
                                                {{ $customer->followup_date->format('d/m/Y') }}
                                            </span>
                                        @else
                                            <span class="badge badge-light">Belum diset</span>
                                        @endif
                                    </td>
                                    {{-- <td>
                                        <a href="{{ route('customers.show', $customer) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">Tidak ada data customer</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </form>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $customers->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Action</h5>
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
                            <option value="update_followup">Update Tanggal Follow-up</option>
                            <option value="add_notes">Tambah Catatan</option>
                        </select>
                    </div>

                    <div id="statusField" class="mb-3" style="display: none;">
                        <label class="form-label">Status FU</label>
                        <select name="status_fu" class="form-select">
                            <option value="normal">Normal</option>
                            <option value="warm">Warm</option>
                            <option value="hot">Hot</option>
                            <option value="normal(prospect)">Normal (Prospect)</option>
                            <option value="warm(potential)">Warm (Potential)</option>
                            <option value="hot(closeable)">Hot (Closeable)</option>
                        </select>
                    </div>

                    <div id="followupField" class="mb-3" style="display: none;">
                        <label class="form-label">Tanggal Follow-up</label>
                        <input type="date" name="followup_date" class="form-control">
                    </div>

                    <div id="notesField" class="mb-3" style="display: none;">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Masukkan catatan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Proses</button>
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

        document.getElementById('selectedCustomerIds').value = JSON.stringify(selectedIds);
    });
});
</script>
@endsection