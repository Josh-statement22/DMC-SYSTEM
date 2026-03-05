@extends('layouts.admin')
@section('title', 'Liquidation')

@section('content')

<div class="space-y-8">

    <!-- HEADER -->
    <div class="flex items-center space-x-4">
        <div class="w-14 h-14 bg-gradient-to-br from-[#1C446D] to-blue-700
                    rounded-2xl flex items-center justify-center shadow-lg">
            <i data-feather="trash-2" class="w-7 h-7 text-white"></i>
        </div>
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Liquidation</h2>
            <p class="text-gray-500">Manage item liquidation and disposal records</p>
        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="flex items-center space-x-4">
        <button class="inline-flex items-center space-x-2 px-6 py-3
                       bg-gradient-to-r from-[#0A3562] via-[#255EC7] to-[#6999F1]
                       text-white font-semibold rounded-xl
                       hover:shadow-xl hover:scale-[1.02]
                       transition-all duration-300">
            <i data-feather="plus" class="w-5 h-5"></i>
            <span>Add Liquidation Record</span>
        </button>
    </div>

    <!-- STATISTICS CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Total Liquidated -->
        <div class="relative overflow-hidden rounded-3xl
                    bg-gradient-to-r from-red-500 to-orange-600
                    p-8 shadow-2xl text-white
                    transition-all duration-300
                    hover:scale-[1.03] hover:shadow-[0_20px_60px_rgba(239,68,68,0.4)]">
            
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>
            
            <div class="relative z-10">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center">
                        <i data-feather="package" class="w-5 h-5"></i>
                    </div>
                    <p class="text-sm font-semibold opacity-90">Total Liquidated</p>
                </div>
                <p class="text-3xl font-extrabold">0</p>
                <p class="text-xs opacity-75 mt-2">Items disposed</p>
            </div>
        </div>

        <!-- Liquidation Value -->
        <div class="relative overflow-hidden rounded-3xl
                    bg-gradient-to-r from-purple-500 to-indigo-600
                    p-8 shadow-2xl text-white
                    transition-all duration-300
                    hover:scale-[1.03] hover:shadow-[0_20px_60px_rgba(139,92,246,0.4)]">
            
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>
            
            <div class="relative z-10">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center">
                        <i data-feather="dollar-sign" class="w-5 h-5"></i>
                    </div>
                    <p class="text-sm font-semibold opacity-90">Total Value</p>
                </div>
                <p class="text-3xl font-extrabold">₱ 0.00</p>
                <p class="text-xs opacity-75 mt-2">Liquidated amount</p>
            </div>
        </div>

        <!-- Pending Review -->
        <div class="relative overflow-hidden rounded-3xl
                    bg-gradient-to-r from-yellow-500 to-amber-600
                    p-8 shadow-2xl text-white
                    transition-all duration-300
                    hover:scale-[1.03] hover:shadow-[0_20px_60px_rgba(217,119,6,0.4)]">
            
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>
            
            <div class="relative z-10">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center">
                        <i data-feather="clock" class="w-5 h-5"></i>
                    </div>
                    <p class="text-sm font-semibold opacity-90">Pending Review</p>
                </div>
                <p class="text-3xl font-extrabold">0</p>
                <p class="text-xs opacity-75 mt-2">Awaiting approval</p>
            </div>
        </div>

    </div>

    <!-- LIQUIDATION RECORDS TABLE -->
    <div class="relative overflow-hidden rounded-3xl bg-white p-8 shadow-2xl">
        
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-800">Liquidation Records</h3>
            <div class="flex items-center space-x-2">
                <input type="text" placeholder="Search records..."
                       class="px-4 py-2 border border-gray-300 rounded-xl
                              focus:ring-2 focus:ring-[#1C446D] focus:border-transparent
                              transition-all duration-200">
                <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition">
                    <i data-feather="download" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-gray-200">
                        <th class="text-left py-4 px-4 text-sm font-semibold text-gray-700">Item Number</th>
                        <th class="text-left py-4 px-4 text-sm font-semibold text-gray-700">Item Name</th>
                        <th class="text-left py-4 px-4 text-sm font-semibold text-gray-700">Quantity</th>
                        <th class="text-left py-4 px-4 text-sm font-semibold text-gray-700">Reason</th>
                        <th class="text-center py-4 px-4 text-sm font-semibold text-gray-700">Status</th>
                        <th class="text-right py-4 px-4 text-sm font-semibold text-gray-700">Date</th>
                        <th class="text-center py-4 px-4 text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="7" class="text-center py-12 text-gray-400">
                            <i data-feather="inbox" class="w-16 h-16 mx-auto mb-4 opacity-50"></i>
                            <p class="text-lg">No liquidation records found.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    feather.replace();
</script>
@endpush
