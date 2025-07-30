<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Follow-up Hari Ini - Traders Family</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
        }
        
        .sidebar-link {
            transition: all 0.2s ease;
            border-radius: 8px;
            margin-bottom: 4px;
        }
        
        .sidebar-link:hover {
            background: #f8fafc;
            transform: translateX(2px);
        }
        
        .sidebar-link.active {
            background: linear-gradient(135deg, #2D5A27, #40E0D0);
            color: white;
            box-shadow: 0 2px 8px rgba(45, 90, 39, 0.2);
        }
        
        .btn-neutral {
            background: #6b7280; /* Neutral gray */
            color: white;
            border: none;
            transition: all 0.2s ease;
            font-weight: 500;
        }
        
        .btn-neutral:hover {
            background: #4b5563; /* Darker neutral gray */
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(55, 65, 81, 0.2);
        }
        
        .btn-success {
            background: #10b981;
            color: white;
            transition: all 0.2s ease;
            font-weight: 500;
        }
        
        .btn-success:hover {
            background: #059669;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }
        
        .form-input {
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease;
            background: white;
        }
        
        .form-input:focus {
            border-color: #2D5A27;
            box-shadow: 0 0 0 3px rgba(45, 90, 39, 0.1);
            outline: none;
        }
        
        .summary-card {
            background: white;
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        
        .summary-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(135deg, #2D5A27, #40E0D0);
        }
        
        .summary-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .customer-card {
            background: white;
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        
        .customer-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border-color: #d1d5db;
        }
        
        .customer-card.overdue::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 4px;
            background: #ef4444;
        }
        
        .customer-card.today::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(135deg, #2D5A27, #40E0D0);
        }
        
        .modal-backdrop {
            backdrop-filter: blur(8px);
            background: rgba(0, 0, 0, 0.4);
        }
        
        .modal-content {
            animation: modalSlideIn 0.3s ease-out;
        }
        
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .success-toast {
            animation: slideInRight 0.4s ease-out;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .empty-state {
            background: white;
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease;
        }
        
        .empty-state:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        /* Mobile Sidebar */
        .mobile-sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }
        
        .mobile-sidebar.open {
            transform: translateX(0);
        }
        
        .mobile-overlay {
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease-in-out;
        }
        
        .mobile-overlay.open {
            opacity: 1;
            visibility: visible;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .customer-card:hover {
                transform: none;
            }
            
            .btn-neutral:hover,
            .btn-success:hover {
                transform: none;
            }
        }
    </style>
</head>
<body>
    <div class="flex h-screen">
        <!-- Mobile Overlay -->
        <div id="mobileOverlay" class="mobile-overlay fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"></div>
        
        <!-- Sidebar -->
        <div id="sidebar" class="mobile-sidebar fixed lg:relative lg:translate-x-0 w-72 bg-white shadow-lg border-r border-gray-200 z-50 lg:z-auto">
            <div class="p-4 lg:p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-lg lg:text-xl font-bold bg-gradient-to-r from-[#2D5A27] to-cyan-600 bg-clip-text text-transparent">
                            Traders Family
                        </h1>
                        <p class="text-xs lg:text-sm text-gray-600 mt-1">Agent: {{ Auth::user()->name }}</p>
                    </div>
                    <button id="closeSidebar" class="lg:hidden p-2 text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <nav class="mt-6 px-4">
                <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 font-medium">
                    <i class="fas fa-chart-bar mr-3"></i>
                    Dashboard
                </a>
                <a href="{{ route('followup.today') }}" class="sidebar-link active flex items-center px-4 py-3 text-white font-medium">
                    <i class="fas fa-calendar-check mr-3"></i>
                    <span class="hidden sm:inline">Follow-up Hari Ini</span>
                    <span class="sm:hidden">Follow-up</span>
                    @if($customers->count() > 0)
                        <span class="ml-auto bg-white/20 text-white text-xs px-2 py-1 rounded-full">{{ $customers->count() }}</span>
                    @endif
                </a>
            </nav>
            
            <!-- Logout -->
            <div class="absolute bottom-6 left-4 right-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-2 text-gray-700 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto bg-gray-50">
            <!-- Mobile Header -->
            <div class="lg:hidden bg-white border-b border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <button id="openSidebar" class="p-2 text-gray-500 hover:text-gray-700">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-lg font-semibold text-gray-900">Follow-up Today</h1>
                    <div class="w-10"></div> <!-- Spacer -->
                </div>
            </div>
            
            <div class="p-4 lg:p-6">
                <!-- Header -->
                <div class="mb-4 lg:mb-6">
                    <h2 class="text-xl lg:text-2xl font-bold text-gray-900">Follow-up Hari Ini</h2>
                    <p class="text-sm lg:text-base text-gray-600 mt-1 flex items-center">
                        <i class="fas fa-calendar mr-2 text-gray-400"></i>
                        <span class="hidden sm:inline">{{ \Carbon\Carbon::today()->format('l, d F Y') }}</span>
                        <span class="sm:hidden">{{ \Carbon\Carbon::today()->format('d M Y') }}</span>
                    </p>
                </div>

                <!-- Summary Card -->
                <div class="summary-card p-4 lg:p-6 rounded-lg shadow-sm mb-4 lg:mb-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="text-base lg:text-lg font-semibold text-gray-900 mb-1">Total Follow-up Hari Ini</h3>
                            <p class="text-2xl lg:text-3xl font-bold bg-gradient-to-r from-[#2D5A27] to-cyan-600 bg-clip-text text-transparent">
                                {{ $customers->count() }}
                            </p>
                            <p class="text-xs lg:text-sm text-gray-500 mt-1">
                                <span class="block sm:inline">{{ $customers->where('is_overdue', true)->count() }} overdue</span>
                                <span class="hidden sm:inline">, </span>
                                <span class="block sm:inline">{{ $customers->where('is_overdue', false)->count() }} on schedule</span>
                            </p>
                        </div>
                        <div class="p-3 lg:p-4 bg-gradient-to-br from-[#2D5A27] to-cyan-500 rounded-xl ml-4">
                            <i class="fas fa-calendar-check text-white text-xl lg:text-2xl"></i>
                        </div>
                    </div>
                </div>

                @if($customers->count() > 0)
                    <!-- Customer Cards -->
                    <div class="space-y-3 lg:space-y-4">
                        @foreach($customers as $customer)
                        <div class="customer-card {{ $customer->is_overdue ? 'overdue' : 'today' }} rounded-lg shadow-sm p-4 lg:p-6">
                            <div class="space-y-4">
                                <!-- Customer Header -->
                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between space-y-3 sm:space-y-0">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $customer->nama ?? 'No Name' }}</h3>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="inline-flex px-2 lg:px-3 py-1 text-xs font-semibold rounded-full {{ $customer->status_color }}">
                                                {{ $customer->status_display }}
                                            </span>
                                            @if($customer->is_overdue)
                                                <span class="inline-flex items-center px-2 lg:px-3 py-1 text-xs bg-red-100 text-red-800 rounded-full">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    <span class="hidden sm:inline">Overdue</span>
                                                    <span class="sm:hidden">Late</span>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 lg:px-3 py-1 text-xs bg-green-100 text-green-800 rounded-full">
                                                    <i class="fas fa-clock mr-1"></i>Today
                                                </span>
                                            @endif
                                            @if($customer->fu_checkbox)
                                                <span class="inline-flex items-center px-2 lg:px-3 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                                    <i class="fas fa-check mr-1"></i>
                                                    <span class="hidden sm:inline">Completed</span>
                                                    <span class="sm:hidden">Done</span>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-left sm:text-right text-sm text-gray-500">
                                        <p class="font-medium">{{ $customer->followup_date->format('H:i') }}</p>
                                        <p class="text-xs">{{ $customer->followup_date->diffForHumans() }}</p>
                                    </div>
                                </div>

                                <!-- Customer Details -->
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 lg:gap-4">
                                    <div class="space-y-2">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-envelope mr-2 text-gray-400 w-4 flex-shrink-0"></i>
                                            <span class="font-medium mr-2">Email:</span>
                                            <span class="truncate">{{ $customer->email ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-phone mr-2 text-gray-400 w-4 flex-shrink-0"></i>
                                            <span class="font-medium mr-2">Phone:</span>
                                            <span>{{ $customer->phone ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-id-card mr-2 text-gray-400 w-4 flex-shrink-0"></i>
                                            <span class="font-medium mr-2">Regis:</span>
                                            <span>{{ $customer->regis }}</span>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-heart mr-2 text-gray-400 w-4 flex-shrink-0"></i>
                                            <span class="font-medium mr-2">Interest:</span>
                                            <span class="truncate">{{ $customer->interest ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-calendar-alt mr-2 text-gray-400 w-4 flex-shrink-0"></i>
                                            <span class="font-medium mr-2">First Visit:</span>
                                            <span>{{ $customer->first_visit ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-redo mr-2 text-gray-400 w-4 flex-shrink-0"></i>
                                            <span class="font-medium mr-2">FU Count:</span>
                                            <span>{{ $customer->fu_jumlah }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Notes -->
                                @if($customer->notes)
                                    <div class="bg-gradient-to-r from-gray-50 to-gray-50 border border-gray-100 p-3 lg:p-4 rounded-lg">
                                        <div class="flex items-start">
                                            <i class="fas fa-sticky-note mr-2 text-gray-400 mt-0.5 flex-shrink-0"></i>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm font-medium text-gray-700 mb-1">Notes:</p>
                                                <p class="text-sm text-gray-600 break-words">{{ $customer->notes }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Action Buttons -->
                                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
                                    @if($customer->phone)
                                        <a href="{{ $customer->whatsapp_link }}" target="_blank"
                                           class="btn-success text-white px-4 py-2.5 rounded-lg text-sm font-medium flex items-center justify-center">
                                            <i class="fab fa-whatsapp mr-2"></i>
                                            <span class="hidden sm:inline">WhatsApp</span>
                                            <span class="sm:hidden">WA</span>
                                        </a>
                                    @endif
                                    
                                    <button onclick="openQuickUpdate({{ $customer->id }})"
                                            class="btn-neutral text-white px-4 py-2.5 rounded-lg text-sm font-medium flex items-center justify-center">
                                        <i class="fas fa-edit mr-2"></i>
                                        <span class="hidden sm:inline">Quick Update</span>
                                        <span class="sm:hidden">Update</span>
                                    </button>
                                    
                                    @if(!$customer->fu_checkbox)
                                        <form method="POST" action="{{ route('customer.mark-completed', $customer->id) }}" class="flex-1 sm:flex-initial">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="btn-neutral text-white w-full px-4 py-2.5 rounded-lg text-sm font-medium flex items-center justify-center">
                                                <i class="fas fa-check mr-2"></i>
                                                <span class="hidden sm:inline">Mark Completed</span>
                                                <span class="sm:hidden">Complete</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="empty-state rounded-lg shadow-sm p-8 lg:p-12 text-center">
                        <div class="w-20 h-20 lg:w-24 lg:h-24 mx-auto bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-4 lg:mb-6">
                            <i class="fas fa-calendar-check text-gray-400 text-2xl lg:text-3xl"></i>
                        </div>
                        <h3 class="text-lg lg:text-xl font-semibold text-gray-900 mb-2">Tidak ada follow-up hari ini</h3>
                        <p class="text-gray-500 mb-4 lg:mb-6 max-w-md mx-auto text-sm lg:text-base">
                            Selamat! Anda tidak memiliki follow-up yang dijadwalkan untuk hari ini. 
                            <span class="hidden sm:inline">Gunakan waktu ini untuk merencanakan strategi follow-up selanjutnya.</span>
                        </p>
                        <a href="{{ route('dashboard') }}" class="btn-neutral text-white px-4 lg:px-6 py-2.5 lg:py-3 rounded-lg font-medium inline-flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            <span class="hidden sm:inline">Kembali ke Dashboard</span>
                            <span class="sm:hidden">Dashboard</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Update Modal -->
    <div id="quickUpdateModal" class="fixed inset-0 modal-backdrop hidden overflow-y-auto h-full w-full z-50 p-4">
        <div class="relative top-4 lg:top-20 mx-auto border-0 w-full max-w-md shadow-2xl rounded-xl bg-white modal-content">
            <div class="p-4 lg:p-6">
                <h3 class="text-lg lg:text-xl font-bold text-gray-900 mb-4 lg:mb-6 flex items-center">
                    <i class="fas fa-edit mr-2 text-gray-600"></i>
                    Quick Update
                </h3>
                <form id="quickUpdateForm" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-4 lg:mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Follow-up Notes</label>
                        <textarea name="notes" id="quickNotes" rows="4"
                                  placeholder="Tambahkan catatan follow-up..."
                                  class="form-input w-full px-3 lg:px-4 py-2.5 lg:py-3 rounded-lg text-sm lg:text-base"></textarea>
                    </div>
                    
                    <div class="mb-4 lg:mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Next Follow-up Date</label>
                        <input type="date" name="followup_date" id="quickFollowupDate"
                               min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"
                               class="form-input w-full px-3 lg:px-4 py-2.5 lg:py-3 rounded-lg text-sm lg:text-base">
                    </div>
                    
                    <div class="mb-4 lg:mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="fu_checkbox" id="quickFuCheckbox" 
                                   class="mr-3 w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <span class="text-sm font-medium text-gray-700">Mark as completed</span>
                        </label>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3">
                        <button type="button" onclick="closeQuickUpdate()"
                                class="btn-neutral text-white px-4 lg:px-6 py-2.5 rounded-lg order-2 sm:order-1">
                            Cancel
                        </button>
                        <button type="submit" class="btn-neutral text-white px-4 lg:px-6 py-2.5 rounded-lg order-1 sm:order-2">
                            Update Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const customers = @json($customers);
        
        // Mobile sidebar functionality
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        const openSidebarBtn = document.getElementById('openSidebar');
        const closeSidebarBtn = document.getElementById('closeSidebar');
        
        function openSidebar() {
            sidebar.classList.add('open');
            mobileOverlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        
        function closeSidebar() {
            sidebar.classList.remove('open');
            mobileOverlay.classList.remove('open');
            document.body.style.overflow = '';
        }
        
        openSidebarBtn?.addEventListener('click', openSidebar);
        closeSidebarBtn?.addEventListener('click', closeSidebar);
        mobileOverlay?.addEventListener('click', closeSidebar);
        
        // Close sidebar on window resize if desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                closeSidebar();
            }
        });
        
        function openQuickUpdate(customerId) {
            const customer = customers.find(c => c.id === customerId);
            if (!customer) return;
            
            document.getElementById('quickUpdateForm').action = `/dashboard/customer/${customerId}`;
            document.getElementById('quickNotes').value = customer.notes || '';
            document.getElementById('quickFollowupDate').value = '';
            document.getElementById('quickFuCheckbox').checked = false;
            
            document.getElementById('quickUpdateModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeQuickUpdate() {
            document.getElementById('quickUpdateModal').classList.add('hidden');
            document.body.style.overflow = '';
        }
        
        // Close modal when clicking outside
        document.getElementById('quickUpdateModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeQuickUpdate();
            }
        });
        
        // Close modal with escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeQuickUpdate();
                closeSidebar();
            }
        });
    </script>

    @if(session('success'))
        <div class="fixed top-4 right-4 bg-gradient-to-r from-green-500 to-green-600 text-white px-4 lg:px-6 py-3 lg:py-4 rounded-xl shadow-2xl z-50 success-toast max-w-sm">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2 flex-shrink-0"></i>
                <span class="text-sm lg:text-base">{{ session('success') }}</span>
            </div>
        </div>
        <script>
            setTimeout(() => {
                const toast = document.querySelector('.success-toast');
                if (toast) {
                    toast.style.animation = 'slideInRight 0.4s ease-out reverse';
                    setTimeout(() => toast.remove(), 400);
                }
            }, 3000);
        </script>
    @endif
</body>
</html>