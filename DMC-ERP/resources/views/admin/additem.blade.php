@extends('layouts.admin')
@section('title', 'Add Item')

@section('content')

<!-- TOAST NOTIFICATIONS -->
@if(session('success'))
    <div id="successToast" class="fixed top-6 right-6 bg-green-500 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center space-x-3 animate-in fade-in slide-in-from-top z-50">
        <i data-feather="check-circle" class="w-6 h-6 flex-shrink-0"></i>
        <div>
            <h4 class="font-semibold">Success!</h4>
            <p class="text-sm text-green-100">{{ session('success') }}</p>
        </div>
    </div>
@endif

@if($errors->any())
    <div id="errorToast" class="fixed top-6 right-6 bg-red-500 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center space-x-3 animate-in fade-in slide-in-from-top z-50">
        <i data-feather="alert-circle" class="w-6 h-6 flex-shrink-0"></i>
        <div>
            <h4 class="font-semibold">Error!</h4>
            <p class="text-sm text-red-100">{{ $errors->first() }}</p>
        </div>
    </div>
@endif

<div class="max-w-4xl mx-auto">

    <!-- FORM CARD -->
    <div class="relative overflow-hidden rounded-3xl bg-white p-10 shadow-2xl">

        <!-- Decorative Glow -->
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-blue-200 opacity-20 rounded-full blur-3xl"></div>

        <div class="relative z-10">

            <!-- HEADER -->
            <div class="flex items-center space-x-4 mb-8">
                <div class="w-14 h-14 bg-gradient-to-br from-[#1C446D] to-blue-700
                            rounded-2xl flex items-center justify-center shadow-lg">
                    <i data-feather="plus-circle" class="w-7 h-7 text-white"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Add New Item</h2>
                    <p class="text-gray-500">Enter item details to add to inventory</p>
                </div>
            </div>

            <!-- FORM -->
            <form action="{{ route('admin.additem.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Item Number -->
                <div>
                    <label for="item_number" class="block text-sm font-semibold text-gray-700 mb-2">
                        Item Number
                    </label>
                    <input type="text"
                           id="item_number"
                           name="item_number"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl
                                  focus:ring-2 focus:ring-[#1C446D] focus:border-transparent
                                  transition-all duration-200"
                           placeholder="Enter item number">
                </div>

                <!-- Item Name -->
                <div>
                    <label for="item_name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Item Name
                    </label>
                    <input type="text"
                           id="item_name"
                           name="item_name"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl
                                  focus:ring-2 focus:ring-[#1C446D] focus:border-transparent
                                  transition-all duration-200"
                           placeholder="Enter item name">
                </div>

                <!-- Item Description -->
                <div>
                    <label for="item_description" class="block text-sm font-semibold text-gray-700 mb-2">
                        Item Description
                    </label>
                    <textarea id="item_description"
                              name="item_description"
                              required
                              rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl
                                     focus:ring-2 focus:ring-[#1C446D] focus:border-transparent
                                     transition-all duration-200"
                              placeholder="Enter item description"></textarea>
                </div>

                <!-- Supplier Name -->
                <div>
                    <label for="supplier_name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Supplier Name
                    </label>
                    <input type="text"
                           id="supplier_name"
                           name="supplier_name"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl
                                  focus:ring-2 focus:ring-[#1C446D] focus:border-transparent
                                  transition-all duration-200"
                           placeholder="Enter supplier name">
                </div>

                <!-- Supplier Phone Number -->
                <div>
                    <label for="supplier_phone" class="block text-sm font-semibold text-gray-700 mb-2">
                        Phone Number
                    </label>
                    <input type="text"
                           id="supplier_phone"
                           name="supplier_phone"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl
                                  focus:ring-2 focus:ring-[#1C446D] focus:border-transparent
                                  transition-all duration-200"
                           placeholder="Enter phone number">
                </div>

                <!-- Supplier Address -->
                <div>
                    <label for="supplier_address" class="block text-sm font-semibold text-gray-700 mb-2">
                        Address
                    </label>
                    <textarea id="supplier_address"
                              name="supplier_address"
                              required
                              rows="2"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl
                                     focus:ring-2 focus:ring-[#1C446D] focus:border-transparent
                                     transition-all duration-200"
                              placeholder="Enter supplier address"></textarea>
                </div>

                <!-- Quantity -->
                <div>
                    <label for="quantity" class="block text-sm font-semibold text-gray-700 mb-2">
                        Quantity
                    </label>
                    <input type="number"
                           id="quantity"
                           name="quantity"
                           required
                           min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl
                                  focus:ring-2 focus:ring-[#1C446D] focus:border-transparent
                                  transition-all duration-200"
                           placeholder="Enter quantity">
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">
                        Price
                    </label>
                    <input type="number"
                           id="price"
                           name="price"
                           required
                           min="0"
                           step="0.01"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl
                                  focus:ring-2 focus:ring-[#1C446D] focus:border-transparent
                                  transition-all duration-200"
                           placeholder="Enter price">
                </div>

                <!-- Purchase Date -->
                <div>
                    <label for="purchase_date" class="block text-sm font-semibold text-gray-700 mb-2">
                        Purchase Date
                    </label>
                    <input type="date"
                           id="purchase_date"
                           name="purchase_date"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl
                                  focus:ring-2 focus:ring-[#1C446D] focus:border-transparent
                                  transition-all duration-200">
                </div>

                <!-- Image Upload -->
                <div>
                    <label for="item_image" class="block text-sm font-semibold text-gray-700 mb-2">
                        Item Image
                    </label>
                    <div class="relative">
                        <input type="file"
                               id="item_image"
                               name="item_image"
                               accept="image/*"
                               class="hidden"
                               onchange="handleImageSelect(event)">
                        <div onclick="document.getElementById('item_image').click()"
                             class="w-full px-4 py-8 border-2 border-dashed border-gray-300 rounded-xl
                                    hover:border-[#1C446D] hover:bg-blue-50
                                    transition-all duration-200 cursor-pointer text-center">
                            <i data-feather="image" class="w-8 h-8 text-gray-400 mx-auto mb-2"></i>
                            <p class="text-sm text-gray-600">Click to upload image or drag and drop</p>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF, WebP, HEIC, HEIF up to 5MB</p>
                        </div>
                    </div>

                    <!-- Image Preview -->
                    <div id="imagePreviewContainer" class="mt-4 hidden">
                        <div class="relative inline-block">
                            <img id="imagePreview"
                                 src=""
                                 alt="Preview"
                                 class="h-32 w-32 object-cover rounded-lg shadow-md">
                            <button type="button"
                                    onclick="removeImage()"
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition">
                                <i data-feather="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" id="base64_image" name="base64_image" value="">
                </div>

                <!-- BUTTONS -->
                <div class="flex items-center space-x-4 pt-4">
                    <button type="submit"
                            class="flex-1 bg-gradient-to-r from-[#0A3562] via-[#255EC7] to-[#6999F1]
                                   text-white font-semibold py-3 px-6 rounded-xl
                                   hover:shadow-xl hover:scale-[1.02]
                                   transition-all duration-300
                                   flex items-center justify-center space-x-2">
                        <i data-feather="save" class="w-5 h-5"></i>
                        <span>Save Item</span>
                    </button>

                    <a href="{{ route('admin.dashboard') }}"
                       class="flex-1 bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-xl
                              hover:bg-gray-300 hover:scale-[1.02]
                              transition-all duration-300
                              flex items-center justify-center space-x-2">
                        <i data-feather="x-circle" class="w-5 h-5"></i>
                        <span>Cancel</span>
                    </a>
                </div>

            </form>

        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>
    // Image Upload Functions
    function handleImageSelect(event) {
        const file = event.target.files[0];
        if (file) {
            validateAndPreviewImage(file);
        }
    }

    function validateAndPreviewImage(file) {
        // Validate file type
        const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/heic', 'image/heif'];
        if (!validTypes.includes(file.type)) {
            alert('Please select a valid image file (JPG, PNG, GIF, WebP, HEIC, HEIF)');
            return;
        }

        // Validate file size (5MB max)
        const maxSize = 5 * 1024 * 1024;
        if (file.size > maxSize) {
            alert('Image size must be less than 5MB');
            return;
        }

        // Read and preview image
        const reader = new FileReader();
        reader.onload = function(e) {
            const base64Data = e.target.result;
            document.getElementById('base64_image').value = base64Data;

            // Show preview
            document.getElementById('imagePreview').src = base64Data;
            document.getElementById('imagePreviewContainer').classList.remove('hidden');

            // Update button text
            document.querySelector('[onclick="document.getElementById(\'item_image\').click()"]').innerHTML = `
                <i data-feather="check-circle" class="w-8 h-8 text-green-500 mx-auto mb-2"></i>
                <p class="text-sm text-green-600 font-semibold">Image selected!</p>
                <p class="text-xs text-gray-500">${file.name}</p>
            `;
            feather.replace();
        };
        reader.readAsDataURL(file);
    }

    function removeImage() {
        document.getElementById('item_image').value = '';
        document.getElementById('base64_image').value = '';
        document.getElementById('imagePreviewContainer').classList.add('hidden');
        document.querySelector('[onclick="document.getElementById(\'item_image\').click()"]').innerHTML = `
            <i data-feather="image" class="w-8 h-8 text-gray-400 mx-auto mb-2"></i>
            <p class="text-sm text-gray-600">Click to upload image or drag and drop</p>
            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 5MB</p>
        `;
        feather.replace();
    }

    // Drag and Drop
    document.addEventListener('DOMContentLoaded', function() {
        const dropZone = document.querySelector('[onclick="document.getElementById(\'item_image\').click()"]');
        
        if (dropZone) {
            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('border-[#1C446D]', 'bg-blue-50');
            });

            dropZone.addEventListener('dragleave', () => {
                dropZone.classList.remove('border-[#1C446D]', 'bg-blue-50');
            });

            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-[#1C446D]', 'bg-blue-50');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    document.getElementById('item_image').files = files;
                    validateAndPreviewImage(files[0]);
                }
            });
        }
    });

    // Auto-hide toast notifications after 3 seconds
    function initializeToasts() {
        const successToast = document.getElementById('successToast');
        const errorToast = document.getElementById('errorToast');

        if (successToast) {
            setTimeout(() => {
                successToast.style.transition = 'opacity 0.3s ease-out';
                successToast.style.opacity = '0';
                setTimeout(() => successToast.remove(), 300);
            }, 3000);
        }

        if (errorToast) {
            setTimeout(() => {
                errorToast.style.transition = 'opacity 0.3s ease-out';
                errorToast.style.opacity = '0';
                setTimeout(() => errorToast.remove(), 300);
            }, 3000);
        }
    }

    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        feather.replace();
        initializeToasts();
    });
</script>
@endpush
