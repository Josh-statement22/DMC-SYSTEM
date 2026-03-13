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
                    <option value="{{ $project->id }}" {{ $project->project_name === $selectedProject ? 'selected' : '' }}>
                        {{ $project->project_name }}
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
                                data-item-id="{{ $item->id }}"
                                data-item-name="{{ $item->item_name }}"
                                data-item-description="{{ $item->item_description }}"
                                data-item-supplier="{{ $item->supplier_name ?? 'N/A' }}"
                                data-item-quantity="{{ $item->quantity }}"
                                data-item-price="{{ number_format((float) $item->price, 2) }}"
                                data-item-image="{{ $item->image_path ?? '' }}"
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

    <div class="bg-white w-full max-w-4xl rounded-3xl p-8 shadow-2xl relative">

        <button onclick="closeItemModal()"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl">
            ✕
        </button>

        <h2 class="text-xl font-bold text-[#1C446D] mb-6">
            Item Description
        </h2>

        <input type="hidden" id="csrfToken" value="{{ csrf_token() }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- LEFT SIDE: Image or Uploader -->
            <div>
                <!-- Image Display (shown when image exists) -->
                <div id="imageDisplay" class="hidden flex items-center justify-center">
                    <img id="modalItemImage" 
                         src="" 
                         alt="Item Image" 
                         class="max-w-full max-h-full rounded-xl shadow-lg"
                         style="max-height: 400px; object-fit: contain;"
                         onerror="this.onerror=null; this.parentElement.classList.add('hidden'); document.getElementById('imageUploader').classList.remove('hidden');">
                </div>

                <!-- Image Uploader (shown when no image) -->
                <div id="imageUploader" class="hidden">
                    <div onclick="document.getElementById('imageInput').click()"
                         class="w-full px-4 py-16 border-2 border-dashed border-gray-300 rounded-xl
                                hover:border-[#1C446D] hover:bg-blue-50
                                transition-all duration-200 cursor-pointer text-center">
                        
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        
                        <p class="mt-2 block text-sm font-medium text-gray-700">
                            Click to upload image
                        </p>
                        
                        <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF, WebP, HEIC, HEIF up to 10MB</p>
                    </div>
                    
                    <input type="file" 
                           id="imageInput" 
                           name="image" 
                           accept="image/*" 
                           class="hidden"
                           onchange="uploadImage()">
                    
                    <div id="uploadProgress" class="hidden mt-4">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div id="progressBar" class="bg-blue-600 h-2 rounded-full" style="width: 0%"></div>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">Uploading...</p>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE: Item Details -->
            <div>
                <h3 id="modalItemName" class="text-lg font-semibold text-gray-800 mb-4"></h3>

                <div class="grid grid-cols-1 gap-3 text-sm text-gray-700 mb-4">
                    <p><span class="font-semibold">Supplier:</span> <span id="modalItemSupplier"></span></p>
                    <p><span class="font-semibold">Quantity:</span> <span id="modalItemQuantity"></span></p>
                    <p><span class="font-semibold">Price:</span> ₱<span id="modalItemPrice"></span></p>
                </div>

                <div class="mt-4">
                    <p class="font-semibold text-sm text-gray-700 mb-2">Description:</p>
                    <p id="modalItemDescription" class="text-gray-600 leading-relaxed text-sm">
                    </p>
                </div>
            </div>

        </div>

    </div>
</div>


<script>
let currentItemId = null;

function openItemModal(button) {
    currentItemId = button.dataset.itemId;
    
    document.getElementById('modalItemName').textContent = button.dataset.itemName
    document.getElementById('modalItemSupplier').textContent = button.dataset.itemSupplier
    document.getElementById('modalItemQuantity').textContent = button.dataset.itemQuantity
    document.getElementById('modalItemPrice').textContent = button.dataset.itemPrice
    document.getElementById('modalItemDescription').textContent = button.dataset.itemDescription

    // Handle image display or uploader
    const imagePath = button.dataset.itemImage;
    const imageDisplay = document.getElementById('imageDisplay');
    const imageUploader = document.getElementById('imageUploader');
    const modalItemImage = document.getElementById('modalItemImage');

    console.log('Image Path from database:', imagePath);

    if (imagePath && imagePath.trim() !== '') {
        // Show image
        const fullImageUrl = '/storage/' + imagePath;
        console.log('Full image URL:', fullImageUrl);
        modalItemImage.src = fullImageUrl;
        imageDisplay.classList.remove('hidden');
        imageUploader.classList.add('hidden');
    } else {
        // Show uploader
        console.log('No image path, showing uploader');
        imageDisplay.classList.add('hidden');
        imageUploader.classList.remove('hidden');
    }

    document.getElementById('itemModal').classList.remove('hidden')
    document.getElementById('itemModal').classList.add('flex')
    document.body.style.overflow = 'hidden'
}

function closeItemModal() {
    document.getElementById('itemModal').classList.add('hidden')
    document.getElementById('itemModal').classList.remove('flex')
    document.body.style.overflow = 'auto'
    
    // Reset form
    document.getElementById('imageInput').value = '';
    document.getElementById('uploadProgress').classList.add('hidden');
    document.getElementById('progressBar').style.width = '0%';
}

async function uploadImage() {
    const fileInput = document.getElementById('imageInput');
    const file = fileInput.files[0];
    
    if (!file) return;

    // Validate file size (10MB)
    if (file.size > 10 * 1024 * 1024) {
        alert('File size must be less than 10MB');
        return;
    }

    const formData = new FormData();
    formData.append('image', file);
    formData.append('item_id', currentItemId);
    formData.append('_token', document.getElementById('csrfToken').value);

    // Show progress
    document.getElementById('uploadProgress').classList.remove('hidden');

    try {
        const response = await fetch('/admin/items/upload-image', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await response.json();

        if (data.success) {
            // Update display
            document.getElementById('modalItemImage').src = '/storage/' + data.image_path;
            document.getElementById('imageDisplay').classList.remove('hidden');
            document.getElementById('imageUploader').classList.add('hidden');
            
            // Update button data attribute
            const buttons = document.querySelectorAll('button[data-item-id="' + currentItemId + '"]');
            buttons.forEach(btn => {
                btn.dataset.itemImage = data.image_path;
            });

            alert('Image uploaded successfully!');
        } else {
            alert('Upload failed: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Upload error:', error);
        alert('Upload failed. Please try again.');
    } finally {
        document.getElementById('uploadProgress').classList.add('hidden');
        document.getElementById('progressBar').style.width = '0%';
    }
}
</script>