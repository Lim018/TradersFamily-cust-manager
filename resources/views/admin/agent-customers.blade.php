@extends('layouts.admin')

@section('title', 'Agent Customers')

@section('page-title')
    {{ $agent->name }} - 
    @if($status)
        {{ ucfirst($status) }} Customers
    @else
        All Customers
    @endif
@endsection

@section('page-description', 'Detail customer untuk agent ' . $agent->name)

@section('content')
<!-- Back Button -->
<div class="mb-6">
    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
        <i class="fas fa-arrow-left mr-2"></i>
        Back to Dashboard
    </a>
</div>

<!-- Agent Info Card -->
<div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 mb-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                <span class="text-blue-600 font-bold text-lg">{{ substr($agent->name, 0, 1) }}</span>
            </div>
            <div>
                <h3 class="text-xl font-semibold text-gray-900">{{ $agent->name }}</h3>
            </div>
        </div>
        
        <!-- Status Filter Buttons -->
        <div class="flex space-x-2">
            <a href="{{ route('admin.agent-customers', ['userId' => $agent->id]) }}" 
               class="px-3 py-1 text-sm rounded-full {{ !$status ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                All ({{ $statusCounts['total'] }})
            </a>
            <a href="{{ route('admin.agent-customers', ['userId' => $agent->id, 'status' => 'normal']) }}" 
               class="px-3 py-1 text-sm rounded-full {{ $status === 'normal' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Normal ({{ $statusCounts['normal'] }})
            </a>
            <a href="{{ route('admin.agent-customers', ['userId' => $agent->id, 'status' => 'warm']) }}" 
               class="px-3 py-1 text-sm rounded-full {{ $status === 'warm' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Warm ({{ $statusCounts['warm'] }})
            </a>
            <a href="{{ route('admin.agent-customers', ['userId' => $agent->id, 'status' => 'hot']) }}" 
               class="px-3 py-1 text-sm rounded-full {{ $status === 'hot' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Hot ({{ $statusCounts['hot'] }})
            </a>
            <a href="{{ route('admin.agent-customers', ['userId' => $agent->id, 'status' => 'closed']) }}" 
               class="px-3 py-1 text-sm rounded-full {{ $status === 'closed' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Closed ({{ $statusCounts['closed'] }})
            </a>
        </div>
    </div>
</div>

<!-- Customers Table -->
<div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-50">
        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
            <i class="fas fa-users mr-2 text-gray-600"></i>
            Customer List
            @if($status)
                - {{ ucfirst($status) }} Status
            @endif
            <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                {{ $customers->total() }} customers
            </span>
        </h3>
    </div>
    
    @if($customers->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contact</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Registration</th>
                    {{-- <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Closing Date</th> --}}
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($customers as $customer)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                <span class="text-gray-600 font-medium text-sm">{{ substr($customer->nama, 0, 1) }}</span>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">{{ $customer->nama }}</div>
                                <div class="text-sm text-gray-500">{{ $customer->regis ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $customer->email ?? 'N/A' }}</div>
                        <div class="text-sm text-gray-500">{{ $customer->phone ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $customer->status_color }}">
                            {{ $customer->status_display }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ $customer->tanggal ? \Carbon\Carbon::parse($customer->tanggal)->format('d M Y') : 'N/A' }}
                    </td>
                    {{-- <td class="px-6 py-4 text-sm text-gray-900">
                        @if($customer->tanggal_closing)
                            <span class="text-green-600 font-medium">
                                {{ \Carbon\Carbon::parse($customer->tanggal_closing)->format('d M Y') }}
                            </span>
                        @else
                            <span class="text-gray-400">Not closed</span>
                        @endif
                    </td> --}}
                    <td class="px-6 py-4">
                        <div class="flex justify-center">
                            @if($customer->phone)
                                <a href="{{ $customer->whatsapp_link }}" 
                                   target="_blank"
                                   class="text-green-600 hover:text-green-800" 
                                   title="WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($customers->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
        {{ $customers->links() }}
    </div>
    @endif
    
    @else
    <div class="px-6 py-12 text-center text-gray-500">
        <i class="fas fa-users text-4xl mb-4 text-gray-300"></i>
        <p class="text-lg font-medium mb-2">No customers found</p>
        <p class="text-sm">
            @if($status)
                This agent doesn't have any customers with {{ $status }} status.
            @else
                This agent doesn't have any customers yet.
            @endif
        </p>
    </div>
    @endif
</div>

<!-- Customer Details Modal -->
<div id="customerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Customer Details</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="modalContent" class="space-y-4">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function showCustomerDetails(customerId) {
    // Show modal
    document.getElementById('customerModal').classList.remove('hidden');
    
    // Load customer data via AJAX
    fetch(`/api/customers/${customerId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modalTitle').textContent = `${data.nama} - Details`;
            
            let content = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Personal Information</h4>
                        <div class="space-y-2 text-sm">
                            <div><span class="font-medium">Name:</span> ${data.nama || 'N/A'}</div>
                            <div><span class="font-medium">Email:</span> ${data.email || 'N/A'}</div>
                            <div><span class="font-medium">Phone:</span> ${data.phone || 'N/A'}</div>
                            <div><span class="font-medium">Registration:</span> ${data.regis || 'N/A'}</div>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Status & Dates</h4>
                        <div class="space-y-2 text-sm">
                            <div><span class="font-medium">Status:</span> <span class="px-2 py-1 rounded-full text-xs ${data.status_color}">${data.status_display}</span></div>
                            <div><span class="font-medium">First Visit:</span> ${data.first_visit || 'N/A'}</div>
                            <div><span class="font-medium">Registration Date:</span> ${data.tanggal || 'N/A'}</div>
                            <div><span class="font-medium">Closing Date:</span> ${data.tanggal_closing || 'Not closed'}</div>
                        </div>
                    </div>
                </div>
                
                ${data.report ? `
                <div class="mt-4">
                    <h4 class="font-semibold text-gray-900 mb-2">Report</h4>
                    <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded">${data.report}</p>
                </div>
                ` : ''}
                
                ${data.interest || data.offer ? `
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    ${data.interest ? `
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Interest</h4>
                        <p class="text-sm text-gray-700">${data.interest}</p>
                    </div>
                    ` : ''}
                    ${data.offer ? `
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Offer</h4>
                        <p class="text-sm text-gray-700">${data.offer}</p>
                    </div>
                    ` : ''}
                </div>
                ` : ''}
            `;
            
            document.getElementById('modalContent').innerHTML = content;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('modalContent').innerHTML = '<p class="text-red-600">Error loading customer details.</p>';
        });
}

function closeModal() {
    document.getElementById('customerModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('customerModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection