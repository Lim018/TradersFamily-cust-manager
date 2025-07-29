<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Dashboard - CustomerSync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    <div class="flex h-screen">
        <div class="w-64 bg-white shadow-lg">
            <div class="p-6">
                <h1 class="text-xl font-bold text-gray-800">Traders Family</h1>
                <p class="text-sm text-gray-600">Agent: {{ Auth::user()->name }}</p>
            </div>
            
            <nav class="mt-6">
                <a href="{{ route('dashboard') }}" class="flex items-center px-6 py-3 text-gray-700 bg-blue-50 border-r-2 border-blue-500">
                    <i class="fas fa-chart-bar mr-3"></i>
                    Dashboard
                </a>
                <a href="{{ route('followup.today') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-calendar-check mr-3"></i>
                    Follow-up Hari Ini
                    @if($stats['followup_today'] > 0)
                        <span class="ml-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ $stats['followup_today'] }}</span>
                    @endif
                </a>
            </nav>
            
            <!-- Logout -->
            <div class="absolute bottom-4 left-0 right-0 px-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto">
            <div class="p-6">
                <!-- Header -->
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Dashboard Agent</h2>
                    <p class="text-gray-600">Kelola customer dan follow-up Anda</p>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-full">
                                <i class="fas fa-users text-blue-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Total Customer</p>
                                <p class="text-2xl font-semibold">{{ $stats['total_customers'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="p-3 bg-grey-100 rounded-full">
                                <i class="fas fa-thermometer-half text-grey-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Normal</p>
                                <p class="text-2xl font-semibold">{{ $stats['normal_status']}}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="p-3 bg-yellow-100 rounded-full">
                                <i class="fas fa-thermometer-half text-yellow-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Warm</p>
                                <p class="text-2xl font-semibold">{{ $stats['warm_status'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="p-3 bg-red-100 rounded-full">
                                <i class="fas fa-thermometer-half text-red-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Hot</p>
                                <p class="text-2xl font-semibold">{{ $stats['hot_status'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-full">
                                <i class="fas fa-calendar-check text-green-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Follow-up Hari Ini</p>
                                <p class="text-2xl font-semibold">{{ $stats['followup_today'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="p-3 bg-red-100 rounded-full">
                                <i class="fas fa-exclamation-triangle text-red-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Overdue</p>
                                <p class="text-2xl font-semibold">{{ $stats['overdue_followup'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-white p-6 rounded-lg shadow mb-6">
                    <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cari Customer</label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Nama customer..." 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Status</option>
                                <option value="normal" {{ request('status') == 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="warm" {{ request('status') == 'warm' ? 'selected' : '' }}>Warm</option>
                                <option value="hot" {{ request('status') == 'hot' ? 'selected' : '' }}>Hot</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bulan Sheet</label>
                            <select name="month" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Bulan</option>
                                @foreach($availableMonths as $month)
                                    <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>{{ $month }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status Follow-up</label>
                            <select name="followup_status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua</option>
                                <option value="pending" {{ request('followup_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="overdue" {{ request('followup_status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                <option value="completed" {{ request('followup_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>

                        <div class="md:col-span-2 lg:col-span-4">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 mr-2">
                                <i class="fas fa-search mr-2"></i>Filter
                            </button>
                            <a href="{{ route('dashboard') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Customer Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Data Customer ({{ $customers->total() }})</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kontak</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Follow-up</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($customers as $customer)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $customer->nama ?? 'No Name' }}</div>
                                            <div class="text-sm text-gray-500">{{ $customer->regis }}</div>
                                            <div class="text-sm text-gray-500">{{ $customer->interest }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $customer->email }}</div>
                                        <div class="text-sm text-gray-500">{{ $customer->phone }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $customer->status_color }}">
                                            {{ $customer->status_display }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($customer->followup_date)
                                            <div class="text-sm {{ $customer->is_overdue ? 'text-red-600' : ($customer->is_followup_today ? 'text-green-600' : 'text-gray-900') }}">
                                                {{ $customer->followup_date->format('d M Y') }}
                                                @if($customer->is_overdue)
                                                    <i class="fas fa-exclamation-triangle text-red-500 ml-1"></i>
                                                @endif
                                            </div>
                                        @endif
                                        @if($customer->fu_checkbox)
                                            <span class="inline-flex items-center px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">
                                                <i class="fas fa-check mr-1"></i>Completed
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            @if($customer->phone)
                                                <a href="{{ $customer->whatsapp_link }}" target="_blank" 
                                                   class="bg-green-500 text-white px-3 py-1 rounded text-xs hover:bg-green-600">
                                                    <i class="fab fa-whatsapp mr-1"></i>WA
                                                </a>
                                            @endif
                                            <button onclick="openEditModal({{ $customer->id }})" 
                                                    class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        Tidak ada data customer
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $customers->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Customer</h3>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" id="editNotes" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Follow-up Date</label>
                        <input type="date" name="followup_date" id="editFollowupDate" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="fu_checkbox" id="editFuCheckbox" class="mr-2">
                            <span class="text-sm text-gray-700">Follow-up Completed</span>
                        </label>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeEditModal()" 
                                class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                            Cancel
                        </button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const customers = @json($customers->items());
        
        function openEditModal(customerId) {
            const customer = customers.find(c => c.id === customerId);
            if (!customer) return;
            
            document.getElementById('editForm').action = `/dashboard/customer/${customerId}`;
            document.getElementById('editNotes').value = customer.notes || '';
            document.getElementById('editFollowupDate').value = customer.followup_date || '';
            document.getElementById('editFuCheckbox').checked = customer.fu_checkbox || false;
            
            document.getElementById('editModal').classList.remove('hidden');
        }
        
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
        
        // Close modal when clicking outside
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    </script>

    @if(session('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50">
            {{ session('success') }}
        </div>
        <script>
            setTimeout(() => {
                document.querySelector('.fixed.top-4.right-4').remove();
            }, 3000);
        </script>
    @endif
</body>
</html>