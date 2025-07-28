<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Follow-up Hari Ini - CustomerSync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    <div class="flex h-screen">
        <div class="w-64 bg-white shadow-lg">
            <div class="p-6">
                <h1 class="text-xl font-bold text-gray-800">CustomerSync</h1>
                <p class="text-sm text-gray-600">Agent: {{ Auth::user()->name }}</p>
            </div>
            
            <nav class="mt-6">
                <a href="{{ route('dashboard') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-chart-bar mr-3"></i>
                    Dashboard
                </a>
                <a href="{{ route('followup.today') }}" class="flex items-center px-6 py-3 text-gray-700 bg-blue-50 border-r-2 border-blue-500">
                    <i class="fas fa-calendar-check mr-3"></i>
                    Follow-up Hari Ini
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
        <div class="flex-1 overflow-hidden">
            <div class="p-6">
                <!-- Header -->
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Follow-up Hari Ini</h2>
                    <p class="text-gray-600">{{ \Carbon\Carbon::today()->format('l, d F Y') }}</p>
                </div>

                <!-- Summary Card -->
                <div class="bg-white p-6 rounded-lg shadow mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Total Follow-up Hari Ini</h3>
                            <p class="text-3xl font-bold text-blue-600">{{ $customers->count() }}</p>
                        </div>
                        <div class="p-4 bg-blue-100 rounded-full">
                            <i class="fas fa-calendar-check text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                @if($customers->count() > 0)
                    <!-- Customer Cards -->
                    <div class="grid gap-6">
                        @foreach($customers as $customer)
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 {{ $customer->is_overdue ? 'border-red-500' : 'border-blue-500' }}">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $customer->nama ?? 'No Name' }}</h3>
                                        <span class="ml-3 inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $customer->status_color }}">
                                            {{ $customer->status_display }}
                                        </span>
                                        @if($customer->is_overdue)
                                            <span class="ml-2 inline-flex items-center px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>Overdue
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600 mb-4">
                                        <div>
                                            <p><span class="font-medium">Email:</span> {{ $customer->email ?? 'N/A' }}</p>
                                            <p><span class="font-medium">Phone:</span> {{ $customer->phone ?? 'N/A' }}</p>
                                            <p><span class="font-medium">Regis:</span> {{ $customer->regis }}</p>
                                        </div>
                                        <div>
                                            <p><span class="font-medium">Interest:</span> {{ $customer->interest ?? 'N/A' }}</p>
                                            <p><span class="font-medium">First Visit:</span> {{ $customer->first_visit ?? 'N/A' }}</p>
                                            <p><span class="font-medium">FU Jumlah:</span> {{ $customer->fu_jumlah }}</p>
                                        </div>
                                    </div>

                                    @if($customer->notes)
                                        <div class="bg-gray-50 p-3 rounded mb-4">
                                            <p class="text-sm text-gray-700"><span class="font-medium">Notes:</span> {{ $customer->notes }}</p>
                                        </div>
                                    @endif

                                    <div class="flex items-center space-x-4">
                                        @if($customer->phone)
                                            <a href="{{ $customer->whatsapp_link }}" target="_blank" 
                                               class="inline-flex items-center bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition">
                                                <i class="fab fa-whatsapp mr-2"></i>
                                                Hubungi via WhatsApp
                                            </a>
                                        @endif

                                        <button onclick="openQuickUpdate({{ $customer->id }})" 
                                                class="inline-flex items-center bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
                                            <i class="fas fa-edit mr-2"></i>
                                            Quick Update
                                        </button>

                                        @if(!$customer->fu_checkbox)
                                            <form method="POST" action="{{ route('customer.mark-completed', $customer->id) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="inline-flex items-center bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition">
                                                    <i class="fas fa-check mr-2"></i>
                                                    Mark Completed
                                                </button>
                                            </form>
                                        @else
                                            <span class="inline-flex items-center bg-gray-100 text-gray-800 px-4 py-2 rounded-md">
                                                <i class="fas fa-check mr-2"></i>
                                                Completed
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="text-right text-sm text-gray-500">
                                    <p>Follow-up: {{ $customer->followup_date->format('H:i') }}</p>
                                    <p>{{ $customer->followup_date->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="bg-white rounded-lg shadow p-12 text-center">
                        <div class="w-24 h-24 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-calendar-check text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada follow-up hari ini</h3>
                        <p class="text-gray-500 mb-6">Selamat! Anda tidak memiliki follow-up yang dijadwalkan untuk hari ini.</p>
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Dashboard
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Update Modal -->
    <div id="quickUpdateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Update</h3>
                <form id="quickUpdateForm" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Follow-up Notes</label>
                        <textarea name="notes" id="quickNotes" rows="3" 
                                  placeholder="Tambahkan catatan follow-up..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Next Follow-up Date</label>
                        <input type="date" name="followup_date" id="quickFollowupDate" 
                               min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="fu_checkbox" id="quickFuCheckbox" class="mr-2">
                            <span class="text-sm text-gray-700">Mark as completed</span>
                        </label>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeQuickUpdate()" 
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
        const customers = @json($customers);
        
        function openQuickUpdate(customerId) {
            const customer = customers.find(c => c.id === customerId);
            if (!customer) return;
            
            document.getElementById('quickUpdateForm').action = `/dashboard/customer/${customerId}`;
            document.getElementById('quickNotes').value = customer.notes || '';
            document.getElementById('quickFollowupDate').value = '';
            document.getElementById('quickFuCheckbox').checked = false;
            
            document.getElementById('quickUpdateModal').classList.remove('hidden');
        }
        
        function closeQuickUpdate() {
            document.getElementById('quickUpdateModal').classList.add('hidden');
        }
        
        // Close modal when clicking outside
        document.getElementById('quickUpdateModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeQuickUpdate();
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