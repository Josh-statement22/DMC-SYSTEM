@extends('layouts.admin')
@section('title', 'Price Analysis & Canvassing')

@section('content')

<!-- Add CSRF token for API requests -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="space-y-8">

    <!-- HEADER -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 bg-gradient-to-br from-[#1C446D] to-blue-700
                        rounded-2xl flex items-center justify-center shadow-lg">
                <i data-feather="bar-chart-2" class="w-7 h-7 text-white"></i>
            </div>
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Price Analysis & Canvassing</h2>
                <p class="text-gray-500">Compare suppliers and find the best prices for your items</p>
            </div>
        </div>
    </div>

    <!-- BREADCRUMB NAVIGATION -->
    <div id="breadcrumb" class="flex items-center gap-2 text-sm text-gray-600">
        <span id="breadcrumb-categories" class="cursor-pointer hover:text-blue-600 font-semibold text-gray-800">Categories</span>
        <span id="breadcrumb-separator-1" class="hidden">
            <i data-feather="chevron-right" class="w-4 h-4"></i>
        </span>
        <span id="breadcrumb-items" class="hidden cursor-pointer hover:text-blue-600"></span>
        <span id="breadcrumb-separator-2" class="hidden">
            <i data-feather="chevron-right" class="w-4 h-4"></i>
        </span>
        <span id="breadcrumb-item-detail" class="hidden text-gray-500"></span>
    </div>

    <!-- CATEGORIES VIEW -->
    <div id="categoriesView" class="space-y-6">
        <div id="categoriesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Categories will be loaded here -->
        </div>
    </div>

    <!-- ITEMS VIEW -->
    <div id="itemsView" class="hidden space-y-6">
        <div id="itemsList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Items will be loaded here -->
        </div>
    </div>

    <!-- ITEM DETAILS VIEW -->
    <div id="itemDetailsView" class="hidden space-y-6">
        <!-- ITEM DETAILS CARD -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-6">Item Details</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Item Image -->
                <div class="md:col-span-1">
                    <div class="relative bg-gray-100 rounded-xl overflow-hidden" style="height: 250px;">
                        <img id="itemImage" src="" alt="Item Image" class="w-full h-full object-contain">
                    </div>
                </div>

                <!-- Item Info -->
                <div class="md:col-span-2">
                    <div class="space-y-3">
                        <div>
                            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full mb-2" id="itemNumber"></span>
                            <h4 class="text-2xl font-bold text-gray-900" id="itemName"></h4>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600 mb-1">Description:</p>
                            <p class="text-gray-700 leading-relaxed" id="itemDescription"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SUPPLIERS COMPARISON TABLE -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-800">Supplier Price Comparison</h3>
                <div class="text-sm text-gray-500">
                    <span id="supplierCount">0</span> supplier(s) found
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Supplier Name</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Price</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Purchase Date</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Contact</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Address</th>
                            <th class="text-center py-3 px-4 text-sm font-semibold text-gray-600">Action</th>
                        </tr>
                    </thead>
                    <tbody id="suppliersTableBody">
                        <!-- Suppliers will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ADD TO PROJECT MODAL -->
    <div id="addToProjectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Add to Project</h3>
                <button onclick="closeAddToProjectModal()" class="text-gray-400 hover:text-gray-600">
                    <i data-feather="x" class="w-6 h-6"></i>
                </button>
            </div>
            
            <div id="modalItemInfo" class="mb-6 p-4 bg-gray-50 rounded-xl space-y-2">
                <p class="text-sm text-gray-600">Item: <span id="modalItemName" class="font-semibold text-gray-900"></span></p>
                <p class="text-sm text-gray-600">Supplier: <span id="modalSupplier" class="font-semibold text-gray-900"></span></p>
                <p class="text-sm text-gray-600">Price: <span id="modalPrice" class="font-semibold text-blue-600"></span></p>
                <p class="text-sm text-gray-600">Contact: <span id="modalContact" class="font-semibold text-gray-900"></span></p>
                <p class="text-sm text-gray-600">Address: <span id="modalAddress" class="font-semibold text-gray-900"></span></p>
            </div>

            <form id="addToProjectForm">
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Select Project</label>
                    <select id="projectSelect" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Choose a project --</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Quantity</label>
                    <input type="number" id="quantityInput" min="1" value="1" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeAddToProjectModal()" class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-semibold">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-gradient-to-r from-[#0A3562] via-[#255EC7] to-[#6999F1] text-white rounded-xl hover:shadow-lg transition-all duration-300 font-semibold">
                        Add to Project
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    feather.replace();

    // State management
    let currentView = 'categories'; // categories, items, itemDetails
    let currentCategoryId = null;
    let currentItemId = null;
    let currentItemData = null;
    let currentSelectedItem = null;

    // DOM Elements
    const categoriesView = document.getElementById('categoriesView');
    const itemsView = document.getElementById('itemsView');
    const itemDetailsView = document.getElementById('itemDetailsView');
    const categoriesGrid = document.getElementById('categoriesGrid');
    const itemsList = document.getElementById('itemsList');
    const modal = document.getElementById('addToProjectModal');
    const projectSelect = document.getElementById('projectSelect');
    const addToProjectForm = document.getElementById('addToProjectForm');

    // Utility functions
    function formatPrice(value) {
        return new Intl.NumberFormat('en-PH', {
            style: 'currency',
            currency: 'PHP',
            minimumFractionDigits: 2
        }).format(value);
    }

    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-PH', { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric' 
        });
    }

    // View management
    function showView(view) {
        currentView = view;
        categoriesView.classList.toggle('hidden', view !== 'categories');
        itemsView.classList.toggle('hidden', view !== 'items');
        itemDetailsView.classList.toggle('hidden', view !== 'itemDetails');
        
        // Update breadcrumb
        document.getElementById('breadcrumb-separator-1').classList.toggle('hidden', view === 'categories');
        document.getElementById('breadcrumb-items').classList.toggle('hidden', view === 'categories');
        document.getElementById('breadcrumb-separator-2').classList.toggle('hidden', view !== 'itemDetails');
        document.getElementById('breadcrumb-item-detail').classList.toggle('hidden', view !== 'itemDetails');
    }

    function updateBreadcrumbs(itemName = null) {
        if (currentView !== 'items' && currentView !== 'itemDetails') return;
        
        const itemName_el = document.getElementById('breadcrumb-items');
        itemName_el.textContent = document.getElementById('breadcrumb-items').dataset.categoryName || 'Items';
        
        if (itemName) {
            document.getElementById('breadcrumb-item-detail').textContent = itemName;
        }
    }

    // Load categories
    async function loadCategories() {
        try {
            const response = await fetch('/api/categories');
            const data = await response.json();
            
            if (data.success && data.categories.length > 0) {
                displayCategories(data.categories);
            } else {
                categoriesGrid.innerHTML = `
                    <div class="col-span-full text-center py-12 text-gray-400">
                        <i data-feather="inbox" class="w-16 h-16 mx-auto mb-4 opacity-50"></i>
                        <p class="text-lg font-medium">No categories found</p>
                        <p class="text-sm mt-2">Please add categories to get started</p>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Error loading categories:', error);
            categoriesGrid.innerHTML = `
                <div class="col-span-full text-center py-12 text-red-400">
                    <i data-feather="alert-circle" class="w-16 h-16 mx-auto mb-4 opacity-50"></i>
                    <p class="text-lg font-medium">Error loading categories</p>
                </div>
            `;
        }
    }

    function displayCategories(categories) {
        categoriesGrid.innerHTML = categories.map(category => `
            <div onclick="selectCategory(${category.id}, '${category.category_name}')" 
                 class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow cursor-pointer p-6 group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl 
                                flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow">
                        <i data-feather="folder" class="w-6 h-6 text-white"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-800 group-hover:text-blue-600 transition-colors">${category.category_name}</h3>
                        <p class="text-sm text-gray-500">${category.description || 'Category'}</p>
                    </div>
                    <i data-feather="arrow-right" class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors"></i>
                </div>
            </div>
        `).join('');
        feather.replace();
    }

    async function selectCategory(categoryId, categoryName) {
        currentCategoryId = categoryId;
        showView('items');
        
        // Update breadcrumb
        const breadcrumbItems = document.getElementById('breadcrumb-items');
        breadcrumbItems.textContent = categoryName;
        breadcrumbItems.dataset.categoryName = categoryName;
        breadcrumbItems.classList.remove('hidden');
        
        await loadItems(categoryId);
    }

    async function loadItems(categoryId) {
        try {
            const response = await fetch(`/api/categories/${categoryId}/items`);
            const data = await response.json();
            
            if (data.success && data.items.length > 0) {
                displayItems(data.items);
            } else {
                itemsList.innerHTML = `
                    <div class="col-span-full text-center py-12 text-gray-400">
                        <i data-feather="package" class="w-16 h-16 mx-auto mb-4 opacity-50"></i>
                        <p class="text-lg font-medium">No items in this category</p>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Error loading items:', error);
            itemsList.innerHTML = `
                <div class="col-span-full text-center py-12 text-red-400">
                    <i data-feather="alert-circle" class="w-16 h-16 mx-auto mb-4 opacity-50"></i>
                    <p class="text-lg font-medium">Error loading items</p>
                </div>
            `;
        }
    }

    function displayItems(items) {
        itemsList.innerHTML = items.map(item => `
            <div onclick="selectItem(${item.id}, '${item.item_name}')" 
                 class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow cursor-pointer overflow-hidden group">
                <div class="relative bg-gray-100 h-48 overflow-hidden">
                    <img src="${item.image_path ? '/storage/' + item.image_path : '/images/placeholder.jpg'}" 
                         alt="${item.item_name}" class="w-full h-full object-contain group-hover:scale-105 transition-transform">
                </div>
                <div class="p-4">
                    <span class="inline-block px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full mb-2">${item.item_number}</span>
                    <h3 class="text-lg font-bold text-gray-800 mb-2 group-hover:text-blue-600 transition-colors">${item.item_name}</h3>
                    <p class="text-sm text-gray-600 line-clamp-2">${item.item_description}</p>
                </div>
            </div>
        `).join('');
        feather.replace();
    }

    async function selectItem(itemId, itemName) {
        currentItemId = itemId;
        showView('itemDetails');
        
        // Update breadcrumb
        document.getElementById('breadcrumb-item-detail').textContent = itemName;
        
        await loadItemDetails(itemId);
    }

    async function loadItemDetails(itemId) {
        try {
            const response = await fetch(`/api/categories/items/${itemId}/details`);
            const data = await response.json();
            
            if (data.success) {
                displayItemDetails(data.item);
            } else {
                itemDetailsView.innerHTML = `
                    <div class="text-center py-12 text-red-400">
                        <i data-feather="alert-circle" class="w-16 h-16 mx-auto mb-4 opacity-50"></i>
                        <p class="text-lg font-medium">${data.message || 'Error loading item details'}</p>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Error loading item details:', error);
        }
    }

    function displayItemDetails(item) {
        // Display item info
        document.getElementById('itemImage').src = item.image_path ? `/storage/${item.image_path}` : '/images/placeholder.jpg';
        document.getElementById('itemNumber').textContent = item.item_number;
        document.getElementById('itemName').textContent = item.item_name;
        document.getElementById('itemDescription').textContent = item.item_description;

        // Display suppliers
        const suppliers = item.suppliers || [];
        document.getElementById('supplierCount').textContent = suppliers.length;

        const sortedSuppliers = [...suppliers].sort((a, b) => a.price - b.price);

        document.getElementById('suppliersTableBody').innerHTML = sortedSuppliers.map((supplier, index) => {
            const isLowest = index === 0;
            const supplierDataJson = JSON.stringify({
                supplier_id: supplier.supplier_id,
                item_id: supplier.item_id,
                supplier_name: supplier.supplier_name,
                item_name: item.item_name,
                price: supplier.price,
                quantity: supplier.quantity,
                phone_number: supplier.phone_number,
                address: supplier.address
            }).replace(/"/g, '&quot;');
            
            return `
                <tr class="border-b border-gray-100 hover:bg-gray-50 ${isLowest ? 'bg-green-50' : ''}">
                    <td class="py-4 px-4">
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-gray-900">${supplier.supplier_name}</span>
                            ${isLowest ? '<span class="inline-block px-2 py-0.5 bg-green-600 text-white text-xs font-bold rounded">LOWEST</span>' : ''}
                        </div>
                    </td>
                    <td class="py-4 px-4">
                        <span class="text-lg font-bold ${isLowest ? 'text-green-600' : 'text-gray-900'}">${formatPrice(supplier.price)}</span>
                    </td>
                    <td class="py-4 px-4 text-gray-700">
                        ${formatDate(supplier.purchase_date)}
                    </td>
                    <td class="py-4 px-4 text-gray-700">
                        ${supplier.phone_number || 'N/A'}
                    </td>
                    <td class="py-4 px-4 text-gray-700 max-w-xs">
                        <div class="flex items-start gap-1">
                            <i data-feather="map-pin" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
                            <span class="text-sm">${supplier.address || 'N/A'}</span>
                        </div>
                    </td>
                    <td class="py-4 px-4 text-center">
                        <button 
                            onclick="openAddToProjectModal(JSON.parse(this.getAttribute('data-supplier')))"
                            data-supplier="${supplierDataJson}"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-semibold">
                            <i data-feather="plus" class="w-4 h-4 inline mr-1"></i>
                            Add
                        </button>
                    </td>
                </tr>
            `;
        }).join('');

        feather.replace();
    }

    // Navigation
    document.getElementById('breadcrumb-categories').addEventListener('click', () => {
        showView('categories');
        currentCategoryId = null;
        currentItemId = null;
    });

    document.getElementById('breadcrumb-items').addEventListener('click', () => {
        if (currentCategoryId) {
            showView('items');
        }
    });

    // Modal functions
    function openAddToProjectModal(supplierData) {
        currentSelectedItem = supplierData;
        
        document.getElementById('modalItemName').textContent = supplierData.item_name;
        document.getElementById('modalSupplier').textContent = supplierData.supplier_name;
        document.getElementById('modalPrice').textContent = formatPrice(supplierData.price);
        document.getElementById('modalContact').textContent = supplierData.phone_number || 'N/A';
        document.getElementById('modalAddress').textContent = supplierData.address || 'N/A';
        document.getElementById('quantityInput').value = 1;
        
        modal.classList.remove('hidden');
        feather.replace();
    }

    function closeAddToProjectModal() {
        modal.classList.add('hidden');
        currentSelectedItem = null;
        addToProjectForm.reset();
    }

    // Load projects
    async function loadProjects() {
        try {
            const response = await fetch('/api/projects');
            const projects = await response.json();
            projectSelect.innerHTML = '<option value="">-- Choose a project --</option>';
            projects.forEach(project => {
                const option = document.createElement('option');
                option.value = project.id;
                option.textContent = project.project_name;
                projectSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading projects:', error);
        }
    }

    // Handle form submission
    addToProjectForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const projectId = projectSelect.value;
        const quantity = document.getElementById('quantityInput').value;

        if (!projectId) {
            alert('Please select a project');
            return;
        }

        const data = {
            project_id: parseInt(projectId),
            item_id: currentSelectedItem.item_id,
            supplier_id: currentSelectedItem.supplier_id,
            quantity: parseInt(quantity),
            unit_price: parseFloat(currentSelectedItem.price)
        };

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('/api/price-analysis/add-to-project', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('Item added to project successfully!');
                closeAddToProjectModal();
            } else {
                alert('Error: ' + (result.message || 'Failed to add item'));
            }
        })
        .catch(error => {
            console.error('Error adding to project:', error);
            alert('Error adding item to project: ' + error.message);
        });
    });

    // Close modal when clicking outside
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeAddToProjectModal();
    });

    // Initialize
    function init() {
        loadProjects();
        loadCategories();
    }

    // Start the app
    init();
</script>
@endpush
