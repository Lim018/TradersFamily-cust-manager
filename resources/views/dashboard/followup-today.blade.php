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
        
        .sidebar {
            transition: transform 0.3s ease-in-out;
        }

        .sidebar-hidden {
            transform: translateX(-100%);
        }

        .hamburger {
            display: none;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100%;
                z-index: 1000;
                width: 280px;
            }

            .sidebar-hidden {
                transform: translateX(-100%);
            }

            .hamburger {
                display: block;
            }

            .main-content {
                margin-left: 0 !important;
            }
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
            background: #6b7280;
            color: white;
            border: none;
            transition: all 0.2s ease;
            font-weight: 500;
        }
        
        .btn-neutral:hover {
            background: #4b5563;
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

        /* === Responsive Utilities === */
        @media (max-width: 1024px) {
            .sidebar-link {
                font-size: 15px;
                padding: 10px 12px;
            }

            .summary-card {
                margin-bottom: 1rem;
            }

            .btn-neutral,
            .btn-success {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }

        @media (max-width: 768px) {
            body {
                font-size: 14px;
            }

            .form-input {
                width: 100%;
                font-size: 14px;
            }

            .modal-content {
                width: 90%;
                margin: auto;
            }

            .summary-card,
            .customer-card {
                font-size: 14px;
            }

            .customer-card:hover {
                transform: none;
            }

            .btn-neutral:hover,
            .btn-success:hover {
                transform: none;
            }

            .sidebar-link {
                display: block;
                padding: 10px;
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .btn-neutral,
            .btn-success {
                font-size: 13px;
                padding: 10px;
            }

            .sidebar-link {
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-72 bg-white shadow-lg border-r border-gray-200 sidebar" id="sidebar">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h1 class="text-xl font-bold bg-gradient-to-r from-[#2D5A27] to-cyan-600 bg-clip-text text-transparent">
                        Traders Family
                    </h1>
                    <p class="text-sm text-gray-600 mt-1">Agent: {{ Auth::user()->name }}</p>
                </div>
                <button class="hamburger md:hidden" onclick="toggleSidebar()">
                    <i class="fas fa-times text-gray-600 text-lg"></i>
                </button>
            </div>
            
            <nav class="mt-6 px-4">
                <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 font-medium">
                    <i class="fas fa-chart-bar mr-3"></i>
                    Dashboard
                </a>
                <a href="{{ route('followup.today') }}" class="sidebar-link active flex items-center px-4 py-3 text-white font-medium">
                    <i class="fas fa-calendar-check mr-3"></i>
                    Follow-up Hari Ini
                    @if($customers->count() > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ $customers->count() }}</span>
                    @endif
                </a>
                <a href="{{ route('dashboard.archived') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 font-medium">
                    <i class="fas fa-archive mr-3"></i>
                    Arsip Customer
                </a>
            </nav>
            
            <!-- Logout -->
            <div class="absolute bottom-6 left-4 right-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center px-4 py-2 text-gray-700 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto bg-gray-50 main-content" id="main-content">
            <div class="p-6">
                <!-- Header -->
                <div class="mb-6 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Follow-up Hari Ini</h2>
                        <p class="text-gray-600 mt-1 flex items-center">
                            <i class="fas fa-calendar mr-2 text-gray-400"></i>
                            {{ \Carbon\Carbon::today()->format('l, d F Y') }}
                        </p>
                    </div>
                    <button class="hamburger md:hidden" onclick="toggleSidebar()">
                        <i class="fas fa-bars text-gray-600 text-lg"></i>
                    </button>
                </div>

                <!-- Summary Card -->
                <div class="summary-card p-4 lg:p-6 rounded-lg shadow-sm mb-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">Total Follow-up Hari Ini</h3>
                            <p class="text-3xl font-bold bg-gradient-to-r from-[#2D5A27] to-cyan-600 bg-clip-text text-transparent">
                                {{ $customers->count() }}
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                Customer yang perlu di follow-up hari ini
                            </p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-[#2D5A27] to-cyan-500 rounded-xl ml-4">
                            <i class="fas fa-calendar-check text-white text-2xl"></i>
                        </div>
                    </div>
                </div>

                @if($customers->count() > 0)
                    <!-- Customer Cards -->
                    <div class="space-y-4">
                        @foreach($customers as $customer)
                        @php
                            // Tentukan follow-up date mana yang hari ini
                            $today = \Carbon\Carbon::today()->format('Y-m-d');
                            $followupType = '';
                            $followupNumber = 0;
                            
                            if($customer->fu_ke_1 == $today) {
                                $followupType = 'First Follow-up';
                                $followupNumber = 1;
                            } elseif($customer->next_fu_2 == $today) {
                                $followupType = '2nd Follow-up';
                                $followupNumber = 2;
                            } elseif($customer->next_fu_3 == $today) {
                                $followupType = '3rd Follow-up';
                                $followupNumber = 3;
                            } elseif($customer->next_fu_4 == $today) {
                                $followupType = '4th Follow-up';
                                $followupNumber = 4;
                            } elseif($customer->next_fu_5 == $today) {
                                $followupType = '5th Follow-up';
                                $followupNumber = 5;
                            }
                            
                            // Cek apakah sudah di-check
                            $isCompleted = false;
                            if($followupNumber >= 2) {
                                $isCompleted = $customer->{"fu_{$followupNumber}_checked"};
                            }
                            
                            // Generate WhatsApp link jika ada phone
                            $whatsappLink = '';
                            if($customer->phone) {
                                $cleanPhone = preg_replace('/[^0-9]/', '', $customer->phone);
                                if(substr($cleanPhone, 0, 1) == '0') {
                                    $cleanPhone = '62' . substr($cleanPhone, 1);
                                }
                                $message = urlencode("Halo {$customer->nama}, ini dari Traders Family. Ada waktu untuk follow-up hari ini?");
                                $whatsappLink = "https://wa.me/{$cleanPhone}?text={$message}";
                            }
                        @endphp
                        
                        <div class="customer-card today rounded-lg shadow-sm p-4 lg:p-6">
                            <div class="space-y-4">
                                <!-- Customer Header -->
                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between space-y-3 sm:space-y-0">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $customer->nama ?? 'No Name' }}</h3>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $followupType }}
                                            </span>
                                            <span class="inline-flex items-center px-3 py-1 text-xs bg-green-100 text-green-800 rounded-full">
                                                <i class="fas fa-clock mr-1"></i>Today
                                            </span>
                                            @if($isCompleted)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1"></i>Completed
                                                </span>
                                            @endif
                                            @if($customer->status_fu)
                                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    {{ $customer->status_fu }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-left sm:text-right text-sm text-gray-500">
                                        <p class="font-medium">{{ \Carbon\Carbon::today()->format('d M Y') }}</p>
                                        <p class="text-xs">Follow-up #{{ $followupNumber }}</p>
                                    </div>
                                </div>

                                <!-- Customer Details -->
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
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
                                @if($followupNumber >= 2 && $customer->{"fu_{$followupNumber}_note"})
                                    <div class="bg-gradient-to-r from-gray-50 to-gray-50 border border-gray-100 p-4 rounded-lg">
                                        <div class="flex items-start">
                                            <i class="fas fa-sticky-note mr-2 text-gray-400 mt-0.5 flex-shrink-0"></i>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm font-medium text-gray-700 mb-1">Follow-up Notes:</p>
                                                <p class="text-sm text-gray-600 break-words">{{ $customer->{"fu_{$followupNumber}_note"} }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Action Buttons -->
                                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                                    @if($whatsappLink)
                                        <a href="{{ $whatsappLink }}" target="_blank"
                                           class="btn-success text-white px-4 py-2.5 rounded-lg text-sm font-medium flex items-center justify-center">
                                            <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                                        </a>
                                    @endif
                                    
                                    <button onclick="openQuickUpdate({{ $customer->id }}, {{ $followupNumber }})"
                                            class="btn-neutral text-white px-4 py-2.5 rounded-lg text-sm font-medium flex items-center justify-center">
                                        <i class="fas fa-edit mr-2"></i>Quick Update
                                    </button>
                                    
                                    @if(!$isCompleted && $followupNumber >= 2)
                                        <form method="POST" action="{{ route('customer.mark-fu-completed', [$customer->id, $followupNumber]) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="btn-success text-white px-4 py-2.5 rounded-lg text-sm font-medium flex items-center justify-center">
                                                <i class="fas fa-check mr-2"></i>Mark Completed
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
                    <div class="empty-state rounded-lg shadow-sm p-12 text-center">
                        <div class="w-24 h-24 mx-auto bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-6">
                            <i class="fas fa-calendar-check text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak ada follow-up hari ini</h3>
                        <p class="text-gray-500 mb-6 max-w-md mx-auto">
                            Selamat! Anda tidak memiliki follow-up yang dijadwalkan untuk hari ini. 
                            Gunakan waktu ini untuk merencanakan strategi follow-up selanjutnya.
                        </p>
                        <a href="{{ route('dashboard') }}" class="btn-neutral text-white px-6 py-3 rounded-lg font-medium inline-flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Update Modal -->
    <div id="quickUpdateModal" class="fixed inset-0 modal-backdrop hidden overflow-y-auto h-full w-full z-50 p-4">
        <div class="relative top-20 mx-auto border-0 w-full max-w-md shadow-2xl rounded-xl bg-white modal-content">
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-edit mr-2 text-gray-600"></i>Quick Update
                </h3>
                <form id="quickUpdateForm" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <input type="hidden" id="followupNumber" name="followup_number" value="">
                    
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Follow-up Notes</label>
                        <textarea name="notes" id="quickNotes" rows="4"
                                  placeholder="Tambahkan catatan follow-up..."
                                  class="form-input w-full px-4 py-3 rounded-lg"></textarea>
                    </div>
                    
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Next Follow-up Date</label>
                        <input type="date" name="next_followup_date" id="quickFollowupDate"
                               min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"
                               class="form-input w-full px-4 py-3 rounded-lg">
                    </div>
                    
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="mark_completed" id="quickFuCheckbox" 
                                   class="mr-3 w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <span class="text-sm font-medium text-gray-700">Mark this follow-up as completed</span>
                        </label>
                    </div>
                    
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeQuickUpdate()"
                                class="btn-neutral text-white px-6 py-2.5 rounded-lg">
                            Cancel
                        </button>
                        <button type="submit" class="btn-success text-white px-6 py-2.5 rounded-lg">
                            Update Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('sidebar-hidden');
        }

        const customers = @json($customers);
        
        function openQuickUpdate(customerId, followupNumber) {
            const customer = customers.find(c => c.id === customerId);
            if (!customer) return;
            
            document.getElementById('quickUpdateForm').action = `/dashboard/customer/${customerId}/followup-update`;
            document.getElementById('followupNumber').value = followupNumber;
            
            // Set existing notes jika ada
            const existingNotes = followupNumber >= 2 ? customer[`fu_${followupNumber}_note`] : '';
            document.getElementById('quickNotes').value = existingNotes || '';
            
            document.getElementById('quickFollowupDate').value = '';
            document.getElementById('quickFuCheckbox').checked = false;
            
            document.getElementById('quickUpdateModal').classList.remove('hidden');
        }
        
        function closeQuickUpdate() {
            document.getElementById('quickUpdateModal').classList.add('hidden');
        }
        
        document.getElementById('quickUpdateModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeQuickUpdate();
            }
        });
    </script>

    @if(session('success'))
        <div class="fixed top-6 right-6 bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4 rounded-xl shadow-2xl z-50 success-toast">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
        <script>
            setTimeout(() => {
                const toast = document.querySelector('.success-toast');
                if (toast) {
                    toast.style.animation = 'slideInRight 0.4s ease-out reverse';
                    setTimeout(() => toast.remove(), 400);
                }
            }, 400);
        </script>
    @endif
</body>
</html>