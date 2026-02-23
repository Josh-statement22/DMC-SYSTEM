@extends('layouts.admin')
@section('title', 'Purchase Orders')

@section('content')

<!-- ================= HEADER / BREADCRUMB ================= -->
<div class="rounded-3xl p-8 text-white mb-8 shadow-xl"
     style="background: linear-gradient(to right, #ADC4ED, #1B3463);">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <p class="text-sm opacity-80">Project ></p>
            <h2 class="text-2xl font-bold">Purchase Orders</h2>
        </div>

        <div id="backButton" class="hidden">
            <button onclick="goBack()"
                class="bg-white text-[#1B3463] px-5 py-2 rounded-xl shadow font-semibold hover:opacity-90 transition">
                ← Back to Projects
            </button>
        </div>

    </div>
</div>


<!-- ================= PROJECT LIST VIEW ================= -->
<div id="projectList" class="grid md:grid-cols-3 gap-6">

    <div onclick="openProject('Surigao')"
        class="cursor-pointer bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl hover:-translate-y-1 transition duration-300">

        <h3 class="text-xl font-bold text-[#1C446D]">Surigao</h3>
        <p class="text-gray-500 mt-2">View purchase orders</p>
    </div>

    <div onclick="openProject('Cam Sur')"
        class="cursor-pointer bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl hover:-translate-y-1 transition duration-300">

        <h3 class="text-xl font-bold text-[#1C446D]">Cam Sur</h3>
        <p class="text-gray-500 mt-2">View purchase orders</p>
    </div>

    <div onclick="openProject('Agusan')"
        class="cursor-pointer bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl hover:-translate-y-1 transition duration-300">

        <h3 class="text-xl font-bold text-[#1C446D]">Agusan</h3>
        <p class="text-gray-500 mt-2">View purchase orders</p>
    </div>

</div>


<!-- ================= PURCHASE TABLE VIEW ================= -->
<div id="purchaseTable" class="hidden bg-white rounded-3xl shadow-xl p-8">

    <!-- TOP CONTROLS -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

        <input type="text"
            placeholder="Search purchase orders..."
            class="border rounded-xl px-4 py-3 w-full md:w-80 focus:outline-none focus:ring-2 focus:ring-[#1C446D]">

        <div class="flex gap-3">
            <button class="bg-green-600 text-white px-4 py-2 rounded-xl shadow hover:opacity-90 transition">
                Export Excel
            </button>

            <button class="bg-red-600 text-white px-4 py-2 rounded-xl shadow hover:opacity-90 transition">
                Export PDF
            </button>
        </div>
    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto">
        <table class="w-full text-left">

            <thead>
                <tr class="border-b text-gray-500 text-sm">
                    <th class="py-3">Item No</th>
                    <th>Description</th>
                    <th>Unit</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                    <th>Date</th>
                    <th>PO No.</th>
                    <th>Supplier</th>
                </tr>
            </thead>

            <tbody class="text-gray-700">

                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="py-4">001</td>
                    <td>Cement Type 1 (40kg)</td>
                    <td>Bag</td>
                    <td>500</td>
                    <td>₱ 250</td>
                    <td class="font-semibold">₱ 125,000</td>
                    <td>Feb 10, 2026</td>
                    <td>PO-001</td>
                    <td>ABC Trading</td>
                </tr>

                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="py-4">002</td>
                    <td>Steel Bars 10mm</td>
                    <td>Piece</td>
                    <td>300</td>
                    <td>₱ 150</td>
                    <td class="font-semibold">₱ 45,000</td>
                    <td>Feb 12, 2026</td>
                    <td>PO-002</td>
                    <td>XYZ Supplies</td>
                </tr>

            </tbody>

        </table>
    </div>

</div>


<!-- ================= SCRIPT ================= -->
<script>

function openProject(projectName) {

    document.getElementById('projectList').classList.add('hidden')
    document.getElementById('purchaseTable').classList.remove('hidden')
    document.getElementById('backButton').classList.remove('hidden')

}

function goBack() {

    document.getElementById('purchaseTable').classList.add('hidden')
    document.getElementById('projectList').classList.remove('hidden')
    document.getElementById('backButton').classList.add('hidden')

}

</script>

@endsection