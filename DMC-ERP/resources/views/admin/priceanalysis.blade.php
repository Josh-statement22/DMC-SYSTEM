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

    <!-- SEARCH BAR -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="flex gap-3">
            <div class="flex-1 relative" id="searchInputWrapper">
                <div class="absolute left-4 top-3 text-gray-400 pointer-events-none z-10">
                    <i data-feather="search" class="w-5 h-5"></i>
                </div>
                <input 
                    type="text" 
                    id="searchInput"
                    placeholder="Search by item number or name..."
                    class="w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-xl
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                           transition-all duration-200"
                    autocomplete="off"
                />
            </div>
            <button 
                id="searchBtn"
                class="px-6 py-2.5 bg-gradient-to-r from-[#0A3562] via-[#255EC7] to-[#6999F1]
                       text-white rounded-xl hover:shadow-lg transition-all duration-300 font-semibold">
                <i data-feather="search" class="w-4 h-4 inline mr-2"></i>
                Search
            </button>
        </div>
    </div>

    <!-- SEARCH RESULTS -->
    <div id="searchResults" class="hidden">
        <!-- ITEM DETAILS CARD -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Item Details</h3>
            
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

    <!-- EMPTY STATE -->
    <div id="emptyState" class="bg-white rounded-2xl shadow-lg p-12">
        <div class="text-center text-gray-400">
            <i data-feather="search" class="w-20 h-20 mx-auto mb-4 opacity-50"></i>
            <p class="text-lg font-medium">Search for an item to compare supplier prices</p>
            <p class="text-sm mt-2">Enter an item number or name above to get started</p>
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

    // DOM Elements
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');
    const searchResults = document.getElementById('searchResults');
    const emptyState = document.getElementById('emptyState');
    const suppliersTableBody = document.getElementById('suppliersTableBody');
    const modal = document.getElementById('addToProjectModal');
    const projectSelect = document.getElementById('projectSelect');
    const addToProjectForm = document.getElementById('addToProjectForm');

    let currentSelectedItem = null;
    let currentItemData = null;
    let searchTimeout = null;
    let isSearching = false;

    // Debounce function to limit API calls
    function debounce(func, delay) {
        return function(...args) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => func.apply(this, args), delay);
        };
    }

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

    function displayItemDetails(item) {
        // Show item details
        document.getElementById('itemImage').src = item.image_path ? `/storage/${item.image_path}` : '/images/placeholder.jpg';
        document.getElementById('itemNumber').textContent = item.item_number;
        document.getElementById('itemName').textContent = item.item_name;
        document.getElementById('itemDescription').textContent = item.item_description;

        // Display suppliers
        const suppliers = item.suppliers || [];
        document.getElementById('supplierCount').textContent = suppliers.length;

        // Sort suppliers by price (lowest first)
        const sortedSuppliers = [...suppliers].sort((a, b) => a.price - b.price);

        suppliersTableBody.innerHTML = sortedSuppliers.map((supplier, index) => {
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

        // Show results, hide empty state
        searchResults.classList.remove('hidden');
        emptyState.classList.add('hidden');
        
        feather.replace();
    }

    function displayMultipleItems(items) {
        // Build HTML for multiple items
        let html = `
            <!-- Results Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl shadow-lg p-6 mb-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold mb-1">Search Results</h3>
                        <p class="text-blue-100">Found <span class="font-bold">${items.length}</span> matching item${items.length > 1 ? 's' : ''}</p>
                    </div>
                    <div class="text-right">
                        <i data-feather="package" class="w-12 h-12 text-blue-200"></i>
                    </div>
                </div>
            </div>
        `;
        
        items.forEach((item, itemIndex) => {
            const suppliers = item.suppliers || [];
            const sortedSuppliers = [...suppliers].sort((a, b) => a.price - b.price);
            
            html += `
                <!-- Item ${itemIndex + 1} -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <div class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <!-- Item Image -->
                            <div class="md:col-span-1">
                                <div class="relative bg-gray-100 rounded-xl overflow-hidden" style="height: 180px;">
                                    <img src="${item.image_path ? '/storage/' + item.image_path : '/images/placeholder.jpg'}" alt="${item.item_name}" class="w-full h-full object-contain">
                                </div>
                            </div>

                            <!-- Item Info -->
                            <div class="md:col-span-3">
                                <div class="space-y-2">
                                    <div>
                                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full mb-2">${item.item_number}</span>
                                        <h4 class="text-xl font-bold text-gray-900">${item.item_name}</h4>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-600 mb-1">Description:</p>
                                        <p class="text-gray-700 text-sm leading-relaxed">${item.item_description}</p>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <span class="font-semibold">${suppliers.length}</span> supplier(s) available
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Suppliers Table -->
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
                            <tbody>
            `;
            
            sortedSuppliers.forEach((supplier, index) => {
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
                
                html += `
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
            });
            
            html += `
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
        });

        // Update the search results area
        searchResults.innerHTML = html;
        searchResults.classList.remove('hidden');
        emptyState.classList.add('hidden');
        
        feather.replace();
    }

    function showNoResults(query) {
        emptyState.innerHTML = `
            <div class="text-center text-gray-400">
                <i data-feather="search" class="w-20 h-20 mx-auto mb-4 opacity-50"></i>
                <p class="text-lg font-medium">No items found matching "${query}"</p>
                <p class="text-sm mt-2">Try a different search term or check for typos</p>
            </div>
        `;
        feather.replace();
        searchResults.classList.add('hidden');
        emptyState.classList.remove('hidden');
    }

    function showDefaultState() {
        emptyState.innerHTML = `
            <div class="text-center text-gray-400">
                <i data-feather="search" class="w-20 h-20 mx-auto mb-4 opacity-50"></i>
                <p class="text-lg font-medium">Search for an item to compare supplier prices</p>
                <p class="text-sm mt-2">Enter an item number or name above to get started</p>
            </div>
        `;
        feather.replace();
        searchResults.classList.add('hidden');
        emptyState.classList.remove('hidden');
    }

    function showSearchingState() {
        emptyState.innerHTML = `
            <div class="text-center text-gray-400">
                <div class="w-20 h-20 mx-auto mb-4 flex items-center justify-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                </div>
                <p class="text-lg font-medium">Searching...</p>
            </div>
        `;
        searchResults.classList.add('hidden');
        emptyState.classList.remove('hidden');
    }

    function performSearch(query) {
        console.log('Performing search for:', query);
        
        // Clear any pending searches
        clearTimeout(searchTimeout);
        
        // If query is empty, show default state
        if (query.trim().length === 0) {
            showDefaultState();
            return;
        }

        // Minimum 1 character for search (allows partial matching from first character)
        if (query.trim().length < 1) {
            return;
        }

        // Show searching state
        if (!isSearching) {
            showSearchingState();
        }
        isSearching = true;

        // Make API call to search items
        const url = `/api/price-analysis/search?q=${encodeURIComponent(query)}`;
        console.log('API URL:', url);

        fetch(url)
            .then(response => {
                console.log('API Response Status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('API Response Data:', data);
                isSearching = false;
                
                if (data.success && data.items && data.items.length > 0) {
                    // Multiple items returned
                    if (data.items.length === 1) {
                        // Single item - use original display
                        currentItemData = data.items[0];
                        displayItemDetails(data.items[0]);
                    } else {
                        // Multiple items - use new display
                        displayMultipleItems(data.items);
                    }
                } else if (data.success && data.item) {
                    // Fallback for old API format (single item)
                    currentItemData = data.item;
                    displayItemDetails(data.item);
                } else {
                    // No results found
                    showNoResults(query);
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                isSearching = false;
                showDefaultState();
            });
    }

    // Live search - debounced input event
    const debouncedSearch = debounce((query) => {
        performSearch(query);
    }, 200); // 200ms delay for faster response

    searchInput.addEventListener('input', (e) => {
        const query = e.target.value;
        debouncedSearch(query);
    });

    // Search button click - immediate search
    searchBtn.addEventListener('click', () => {
        clearTimeout(searchTimeout);
        performSearch(searchInput.value);
    });

    // Enter key - immediate search
    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            clearTimeout(searchTimeout);
            performSearch(searchInput.value);
        }
    });

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

    // Populate projects dropdown
    function loadProjects() {
        fetch('/api/projects')
            .then(response => response.json())
            .then(projects => {
                projectSelect.innerHTML = '<option value="">-- Choose a project --</option>';
                projects.forEach(project => {
                    const option = document.createElement('option');
                    option.value = project.id;
                    option.textContent = project.project_name;
                    projectSelect.appendChild(option);
                });
            })
            .catch(error => {
                    console.error('Error loading projects:', error);
                projectSelect.innerHTML = '<option value="">-- Choose a project --</option>';
            });
    }

    // Handle form submission
    addToProjectForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const projectId = document.getElementById('projectSelect').value;
        const quantity = document.getElementById('quantityInput').value;

        if (!projectId) {
            alert('Please select a project');
            return;
        }

        // Prepare data to send
        const data = {
            project_id: parseInt(projectId),
            item_id: currentSelectedItem.item_id,
            supplier_id: currentSelectedItem.supplier_id,
            quantity: parseInt(quantity),
            unit_price: parseFloat(currentSelectedItem.price)
        };

        console.log('Adding to project:', data);

        // Get CSRF token
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Send to backend API
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
                searchInput.value = '';
                searchResults.classList.add('hidden');
                emptyState.classList.remove('hidden');
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
    loadProjects();
</script>
@endpush
