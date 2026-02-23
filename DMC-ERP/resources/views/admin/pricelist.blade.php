@extends('layouts.admin')
@section('title', 'Price List')

@section('content')

<!-- ================= HEADER / BREADCRUMB ================= -->
<div class="rounded-3xl p-8 text-white mb-8 shadow-xl"
     style="background: linear-gradient(to right, #ADC4ED, #1B3463);">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <p class="text-sm opacity-80">Project ></p>
            <h2 class="text-2xl font-bold">DepEd Agusan del Norte</h2>
        </div>

        <select class="bg-white text-gray-700 px-4 py-2 rounded-xl shadow focus:outline-none">
            <option>DepEd Agusan del Norte</option>
            <option>Project B</option>
            <option>Project C</option>
        </select>

    </div>
</div>

<!-- ================= REQUIRED ITEMS TABLE ================= -->
<div class="bg-white rounded-3xl shadow-xl p-8">

    <h3 class="text-xl font-semibold mb-6 text-gray-700">
        Required Items
    </h3>

    <div class="overflow-x-auto">
        <table class="w-full text-left">

            <thead>
                <tr class="text-gray-500 text-sm border-b">
                    <th class="py-3">Item No.</th>
                    <th>Item Name</th>
                    <th>Unit</th>
                    <th>Quantity</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>

            <tbody class="text-gray-700">

                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="py-4">001</td>

                    <td>
                        <button onclick="openItemModal()"
                            class="text-[#1C446D] font-semibold hover:underline">
                            Cement Type 1
                        </button>
                    </td>

                    <td>Bag</td>
                    <td>500</td>

                    <td class="text-right">
                        <button onclick="openPriceModal()"
                            class="bg-[#1C446D] text-white px-4 py-2 rounded-xl shadow hover:opacity-90 transition">
                            Add Supplier Price
                        </button>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

</div>

@endsection


{{-- ======================================================= --}}
{{-- ================= GLOBAL MODALS ======================= --}}
{{-- IMPORTANT: NASA LABAS NG @section('content') --}}
{{-- ======================================================= --}}

<!-- ITEM DESCRIPTION MODAL -->
<div id="itemModal"
     class="fixed inset-0 hidden items-center justify-center"
     style="z-index: 999999; background: rgba(0,0,0,0.65);">

    <div class="bg-white w-full max-w-lg rounded-3xl p-8 shadow-2xl relative">

        <button onclick="closeItemModal()"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl">
            ✕
        </button>

        <h2 class="text-xl font-bold text-[#1C446D] mb-4">
            Item Description
        </h2>

        <p class="text-gray-600 leading-relaxed">
            Cement Type 1 – High quality cement suitable for structural
            foundations and vertical applications. 40kg per bag.
        </p>

    </div>
</div>


<!-- ADD SUPPLIER PRICE MODAL -->
<div id="priceModal"
     class="fixed inset-0 hidden items-center justify-center"
     style="z-index: 999999; background: rgba(0,0,0,0.65);">

    <div class="bg-white w-full max-w-md rounded-3xl p-8 shadow-2xl relative">

        <button onclick="closePriceModal()"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl">
            ✕
        </button>

        <h2 class="text-xl font-bold text-[#1C446D] mb-6">
            Add Supplier Price
        </h2>

        <form class="space-y-4">

            <input type="text"
                placeholder="Supplier Name"
                class="w-full border rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#1C446D]">

            <input type="number"
                placeholder="Price"
                class="w-full border rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#1C446D]">

            <button type="submit"
                class="w-full bg-[#1C446D] text-white py-3 rounded-xl shadow hover:opacity-90 transition">
                Save Price
            </button>

        </form>

    </div>
</div>


<script>
function openItemModal() {
    document.getElementById('itemModal').classList.remove('hidden')
    document.getElementById('itemModal').classList.add('flex')
    document.body.style.overflow = 'hidden'
}

function closeItemModal() {
    document.getElementById('itemModal').classList.add('hidden')
    document.getElementById('itemModal').classList.remove('flex')
    document.body.style.overflow = 'auto'
}

function openPriceModal() {
    document.getElementById('priceModal').classList.remove('hidden')
    document.getElementById('priceModal').classList.add('flex')
    document.body.style.overflow = 'hidden'
}

function closePriceModal() {
    document.getElementById('priceModal').classList.add('hidden')
    document.getElementById('priceModal').classList.remove('flex')
    document.body.style.overflow = 'auto'
}
</script>