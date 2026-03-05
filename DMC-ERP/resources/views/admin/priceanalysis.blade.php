@extends('layouts.admin')
@section('title', 'Price Analysis')

@section('content')

<div class="space-y-8">

    <!-- HEADER -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 bg-gradient-to-br from-[#1C446D] to-blue-700
                        rounded-2xl flex items-center justify-center shadow-lg">
                <i data-feather="bar-chart-2" class="w-7 h-7 text-white"></i>
            </div>
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Price Analysis</h2>
                <p class="text-gray-500">Compare and analyze item prices across projects</p>
            </div>
        </div>
        <div class="flex gap-3">
            <div class="flex-1 relative min-w-80" id="searchInputWrapper">
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
                       text-white rounded-xl hover:shadow-lg transition-all duration-300 font-semibold"
                style="white-space: nowrap;">
                <i data-feather="search" class="w-4 h-4 inline mr-2"></i>
                Search
            </button>
        </div>
    </div>

    <!-- STATISTICS CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <!-- Lowest Price Card -->
        <div class="relative overflow-hidden rounded-2xl
                    bg-gradient-to-r from-orange-500 to-red-600
                    p-6 shadow-lg text-white
                    transition-all duration-300
                    hover:scale-[1.02] hover:shadow-[0_20px_60px_rgba(249,115,22,0.4)]">
            
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>
            
            <div class="relative z-10">
                <div class="flex items-center space-x-2 mb-3">
                    <div class="w-8 h-8 bg-white/20 backdrop-blur-md rounded-lg flex items-center justify-center">
                        <i data-feather="trending-down" class="w-4 h-4"></i>
                    </div>
                    <p class="text-xs font-semibold opacity-90">Lowest Price</p>
                </div>
                <p class="text-2xl font-extrabold" id="lowestPriceValue">₱ 0.00</p>
                <p class="text-xs opacity-75 mt-1" id="lowestPriceItem">-</p>
            </div>
        </div>

        <!-- Highest Price Card -->
        <div class="relative overflow-hidden rounded-2xl
                    bg-gradient-to-r from-emerald-500 to-teal-600
                    p-6 shadow-lg text-white
                    transition-all duration-300
                    hover:scale-[1.02] hover:shadow-[0_20px_60px_rgba(16,185,129,0.4)]">
            
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>
            
            <div class="relative z-10">
                <div class="flex items-center space-x-2 mb-3">
                    <div class="w-8 h-8 bg-white/20 backdrop-blur-md rounded-lg flex items-center justify-center">
                        <i data-feather="trending-up" class="w-4 h-4"></i>
                    </div>
                    <p class="text-xs font-semibold opacity-90">Highest Price</p>
                </div>
                <p class="text-2xl font-extrabold" id="highestPriceValue">₱ 0.00</p>
                <p class="text-xs opacity-75 mt-1" id="highestPriceItem">-</p>
            </div>
        </div>

    </div>

    <!-- GRID RESULTS -->
    <div class="relative overflow-hidden rounded-3xl bg-white p-8 shadow-2xl">
        <h3 class="text-xl font-bold text-gray-800 mb-6">Search Results</h3>
        
        <!-- GRID CONTAINER -->
        <div id="itemsGrid" class="grid grid-cols-1 mobile:grid-cols-2 tablet:grid-cols-3 lg:grid-cols-4 gap-6">
            <div class="col-span-full text-center py-12 text-gray-400">
                <i data-feather="inbox" class="w-16 h-16 mx-auto mb-4 opacity-50"></i>
                <p class="text-lg">Start by searching for an item to see available options</p>
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
            
            <div id="modalItemInfo" class="mb-6 p-4 bg-gray-50 rounded-xl">
                <p class="text-sm text-gray-600">Item: <span id="modalItemName" class="font-semibold text-gray-900"></span></p>
                <p class="text-sm text-gray-600 mt-1">Supplier: <span id="modalSupplier" class="font-semibold text-gray-900"></span></p>
                <p class="text-sm text-gray-600 mt-1">Price: <span id="modalPrice" class="font-semibold text-blue-600"></span></p>
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
    const itemsGrid = document.getElementById('itemsGrid');
    const modal = document.getElementById('addToProjectModal');
    const projectSelect = document.getElementById('projectSelect');
    const addToProjectForm = document.getElementById('addToProjectForm');

    // Create search suggestions container
    const suggestionsContainer = document.createElement('div');
    suggestionsContainer.id = 'searchSuggestions';
    suggestionsContainer.className = 'absolute top-full left-0 right-0 mt-1 bg-white border border-gray-300 rounded-xl shadow-lg z-50 max-h-96 overflow-y-auto hidden';
    document.getElementById('searchInputWrapper').appendChild(suggestionsContainer);

    let currentSelectedItem = null;
    let searchTimeout;

    function formatPrice(value) {
        return new Intl.NumberFormat('en-PH', {
            style: 'currency',
            currency: 'PHP',
            minimumFractionDigits: 2
        }).format(value);
    }

    function updatePriceCards(filteredData) {
        if (filteredData.length === 0) {
            document.getElementById('lowestPriceValue').textContent = '₱ 0.00';
            document.getElementById('lowestPriceItem').textContent = '-';
            document.getElementById('highestPriceValue').textContent = '₱ 0.00';
            document.getElementById('highestPriceItem').textContent = '-';
            return;
        }

        // Get all prices from all suppliers
        let allPrices = [];
        filteredData.forEach(item => {
            item.suppliers.forEach(supplier => {
                allPrices.push({
                    price: supplier.price,
                    itemName: item.name,
                    supplier: supplier.supplier
                });
            });
        });

        if (allPrices.length > 0) {
            const lowest = allPrices.reduce((min, p) => p.price < min.price ? p : min);
            const highest = allPrices.reduce((max, p) => p.price > max.price ? p : max);

            document.getElementById('lowestPriceValue').textContent = formatPrice(lowest.price);
            document.getElementById('lowestPriceItem').textContent = `${lowest.itemName} (${lowest.supplier})`;
            document.getElementById('highestPriceValue').textContent = formatPrice(highest.price);
            document.getElementById('highestPriceItem').textContent = `${highest.itemName} (${highest.supplier})`;
        }
    }

    function renderGrid(filteredData) {
        if (filteredData.length === 0) {
            itemsGrid.innerHTML = `
                <div class="col-span-full text-center py-12 text-gray-400">
                    <i data-feather="inbox" class="w-16 h-16 mx-auto mb-4 opacity-50"></i>
                    <p class="text-lg">No items found</p>
                </div>
            `;
            feather.replace();
            return;
        }

        itemsGrid.innerHTML = filteredData.map(item => {
            // Sort suppliers by price (lowest first)
            const sortedSuppliers = [...item.suppliers].sort((a, b) => a.price - b.price);
            const lowestPrice = sortedSuppliers[0].price;

            return `
                <div class="group bg-white border border-gray-200 rounded-2xl overflow-hidden hover:shadow-lg hover:border-blue-300 transition-all duration-300">
                    <!-- IMAGE -->
                    <div class="relative h-48 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                        <img src="${item.image || '/images/placeholder.jpg'}" alt="${item.name}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute top-3 right-3 bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-semibold">
                            ${item.number}
                        </div>
                    </div>

                    <!-- CONTENT -->
                    <div class="p-5">
                        <!-- ITEM NAME AND PRICE -->
                        <h4 class="font-semibold text-gray-900 text-sm mb-1 line-clamp-2">${item.name}</h4>
                        <p class="text-2xl font-bold text-blue-600 mb-4">${formatPrice(lowestPrice)}</p>

                        <!-- SUPPLIERS -->
                        <div class="space-y-2 mb-4">
                            <p class="text-xs font-semibold text-gray-600 uppercase">Suppliers</p>
                            ${sortedSuppliers.slice(0, 3).map((supplier, idx) => `
                                <div class="flex items-center justify-between p-2.5 rounded-lg ${idx === 0 ? 'bg-green-50 border border-green-200' : 'bg-gray-50'} text-xs">
                                    <div>
                                        <p class="font-semibold text-gray-900">${supplier.supplier}${idx === 0 ? ' ✓' : ''}</p>
                                        <p class="text-gray-600">${formatPrice(supplier.price)}</p>
                                    </div>
                                    <button 
                                        class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-xs font-semibold"
                                        onclick="openAddToProjectModal('${item.name}', '${supplier.supplier}', ${supplier.price})">
                                        Add
                                    </button>
                                </div>
                            `).join('')}
                        </div>

                        <!-- MORE SUPPLIERS -->
                        ${sortedSuppliers.length > 3 ? `
                            <p class="text-xs text-gray-500 text-center">+${sortedSuppliers.length - 3} more suppliers</p>
                        ` : ''}
                    </div>
                </div>
            `;
        }).join('');

        feather.replace();
    }

    function displaySuggestions(items) {
        if (items.length === 0) {
            suggestionsContainer.classList.add('hidden');
            return;
        }

        suggestionsContainer.innerHTML = items.map(item => {
            const lowestPrice = item.suppliers.length > 0 ? item.suppliers[0].price : 0;
            return `
                <div class="px-4 py-3 border-b border-gray-100 hover:bg-blue-50 cursor-pointer transition-colors"
                     onclick="selectSuggestion('${item.number}', '${item.name}', ${JSON.stringify(item).replace(/'/g, "&apos;")})">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">${item.number} - ${item.name}</p>
                            <p class="text-xs text-gray-600">${item.suppliers.length} supplier${item.suppliers.length !== 1 ? 's' : ''} • From ${formatPrice(lowestPrice)}</p>
                        </div>
                        <i data-feather="arrow-right" class="w-4 h-4 text-gray-400"></i>
                    </div>
                </div>
            `;
        }).join('');

        suggestionsContainer.classList.remove('hidden');
        feather.replace();
    }

    function performSearch(query) {
        console.log('Performing search for:', query);
        
        if (query.trim().length === 0) {
            suggestionsContainer.classList.add('hidden');
            itemsGrid.innerHTML = `
                <div class="col-span-full text-center py-12 text-gray-400">
                    <i data-feather="inbox" class="w-16 h-16 mx-auto mb-4 opacity-50"></i>
                    <p class="text-lg">Start by searching for an item to see available options</p>
                </div>
            `;
            updatePriceCards([]);
            return;
        }

        // Make API call
        const url = `/api/search-items?q=${encodeURIComponent(query)}`;
        console.log('API URL:', url);

        fetch(url)
            .then(response => {
                console.log('API Response Status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('API Response Data:', data);
                displaySuggestions(data);
                if (data.length > 0) {
                    renderGrid(data);
                    updatePriceCards(data);
                } else {
                    suggestionsContainer.classList.add('hidden');
                    itemsGrid.innerHTML = `
                        <div class="col-span-full text-center py-12 text-gray-400">
                            <i data-feather="inbox" class="w-16 h-16 mx-auto mb-4 opacity-50"></i>
                            <p class="text-lg">No items found matching your search</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                alert('Error searching items: ' + error.message);
            });
    }

    function selectSuggestion(itemNumber, itemName, itemData) {
        searchInput.value = itemName;
        suggestionsContainer.classList.add('hidden');
        renderGrid([itemData]);
        updatePriceCards([itemData]);
    }

    // Real-time search as typing
    searchInput.addEventListener('input', (e) => {
        clearTimeout(searchTimeout);
        const query = e.target.value.trim();
        console.log('Input event triggered, query:', query);
        
        if (query.length === 0) {
            suggestionsContainer.classList.add('hidden');
        } else {
            // Debounce search to avoid too many requests
            searchTimeout = setTimeout(() => {
                performSearch(query);
            }, 300);
        }
    });

    // Search button click
    searchBtn.addEventListener('click', () => {
        performSearch(searchInput.value);
    });

    // Enter key
    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            clearTimeout(searchTimeout);
            performSearch(searchInput.value);
        }
    });

    // Hide suggestions when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('#searchInput') && !e.target.closest('#searchSuggestions')) {
            suggestionsContainer.classList.add('hidden');
        }
    });

    function openAddToProjectModal(itemName, supplier, price) {
        currentSelectedItem = { itemName, supplier, price };
        
        document.getElementById('modalItemName').textContent = itemName;
        document.getElementById('modalSupplier').textContent = supplier;
        document.getElementById('modalPrice').textContent = formatPrice(price);
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
                    option.textContent = project.name;
                    projectSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading projects:', error);
                // Fallback: keep empty state if API fails
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

        // TODO: Send to backend API
        console.log('Adding to project:', {
            itemName: currentSelectedItem.itemName,
            supplier: currentSelectedItem.supplier,
            price: currentSelectedItem.price,
            projectId: projectId,
            quantity: quantity
        });

        alert('Item added to project successfully!');
        closeAddToProjectModal();
    });

    // Close modal when clicking outside
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeAddToProjectModal();
    });

    // Initialize
    loadProjects();
</script>
@endpush
