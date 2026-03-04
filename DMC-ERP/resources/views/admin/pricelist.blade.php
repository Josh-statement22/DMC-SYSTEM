@extends('layouts.admin')
@section('title', 'Price List')

@section('content')

<!-- ================= HEADER / BREADCRUMB ================= -->
<div class="rounded-3xl p-8 text-white mb-8 shadow-xl"
     style="background: linear-gradient(to right, #ADC4ED, #1B3463);">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <p class="text-sm opacity-80">Project ></p>
            <h2 class="text-2xl font-bold">{{ $selectedProject ?? 'No Project Selected' }}</h2>
        </div>

        <form method="GET" action="{{ route('admin.pricelist') }}">
            <select name="project"
                onchange="this.form.submit()"
                class="bg-white text-gray-700 px-4 py-2 rounded-xl shadow focus:outline-none">
                @forelse($projects as $project)
                    <option value="{{ $project }}" {{ $project === $selectedProject ? 'selected' : '' }}>
                        {{ $project }}
                    </option>
                @empty
                    <option value="">No projects available</option>
                @endforelse
            </select>
        </form>

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
                </tr>
            </thead>

            <tbody class="text-gray-700">
                @forelse($items as $item)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="py-4">{{ $item->item_number }}</td>

                        <td>
                            <button
                                onclick="openItemModal(this)"
                                data-item-name="{{ $item->item_name }}"
                                data-item-description="{{ $item->item_description }}"
                                data-item-supplier="{{ $item->supplier }}"
                                data-item-quantity="{{ $item->quantity }}"
                                data-item-price="{{ number_format((float) $item->price, 2) }}"
                                class="text-[#1C446D] font-semibold hover:underline"
                            >
                                {{ $item->item_name }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="py-6 text-center text-gray-500">
                            No items found for this project.
                        </td>
                    </tr>
                @endforelse

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

        <h3 id="modalItemName" class="text-lg font-semibold text-gray-800 mb-3"></h3>

        <div class="grid grid-cols-1 gap-2 text-sm text-gray-700 mb-4">
            <p><span class="font-semibold">Supplier:</span> <span id="modalItemSupplier"></span></p>
            <p><span class="font-semibold">Quantity:</span> <span id="modalItemQuantity"></span></p>
            <p><span class="font-semibold">Price:</span> ₱<span id="modalItemPrice"></span></p>
        </div>

        <p id="modalItemDescription" class="text-gray-600 leading-relaxed">
        </p>

    </div>
</div>


<script>
function openItemModal(button) {
    document.getElementById('modalItemName').textContent = button.dataset.itemName
    document.getElementById('modalItemSupplier').textContent = button.dataset.itemSupplier
    document.getElementById('modalItemQuantity').textContent = button.dataset.itemQuantity
    document.getElementById('modalItemPrice').textContent = button.dataset.itemPrice
    document.getElementById('modalItemDescription').textContent = button.dataset.itemDescription

    document.getElementById('itemModal').classList.remove('hidden')
    document.getElementById('itemModal').classList.add('flex')
    document.body.style.overflow = 'hidden'
}

function closeItemModal() {
    document.getElementById('itemModal').classList.add('hidden')
    document.getElementById('itemModal').classList.remove('flex')
    document.body.style.overflow = 'auto'
}
</script>