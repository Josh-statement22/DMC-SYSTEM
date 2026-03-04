@extends('layouts.admin')
@section('title', 'Add Item')

@section('content')

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

                <!-- Project Dropdown -->
                <div>
                    <label for="project_select" class="block text-sm font-semibold text-gray-700 mb-2">
                        Project
                    </label>
                    <div class="relative">
                        <select id="project_select"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl
                                       focus:ring-2 focus:ring-[#1C446D] focus:border-transparent
                                       transition-all duration-200 appearance-none">
                            <option value="">-- Select a Project --</option>
                            @foreach($projects as $proj)
                                <option value="{{ $proj->project_name }}">{{ $proj->project_name }}</option>
                            @endforeach
                            <option value="add_new" style="font-weight: bold;">+ Add New Project</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                            <i data-feather="chevron-down" class="w-5 h-5 text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Project Name Input (Hidden by default) -->
                <div id="new_project_div" class="hidden">
                    <label for="project_name" class="block text-sm font-semibold text-gray-700 mb-2">
                        New Project Name
                    </label>
                    <input type="text"
                           id="project_name"
                           name="project_name"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl
                                  focus:ring-2 focus:ring-[#1C446D] focus:border-transparent
                                  transition-all duration-200"
                           placeholder="Enter new project name">
                </div>

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

                <!-- Supplier -->
                <div>
                    <label for="supplier" class="block text-sm font-semibold text-gray-700 mb-2">
                        Supplier
                    </label>
                    <input type="text"
                           id="supplier"
                           name="supplier"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl
                                  focus:ring-2 focus:ring-[#1C446D] focus:border-transparent
                                  transition-all duration-200"
                           placeholder="Enter supplier name">
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

            <!-- SUCCESS/ERROR MESSAGES -->
            @if(session('success'))
                <div class="mt-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl">
                    <div class="flex items-center space-x-2">
                        <i data-feather="check-circle" class="w-5 h-5"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mt-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl">
                    <div class="flex items-center space-x-2 mb-2">
                        <i data-feather="alert-circle" class="w-5 h-5"></i>
                        <span class="font-semibold">Please fix the following errors:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>
    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        feather.replace();

        // Get DOM elements
        const projectSelect = document.getElementById('project_select');
        const newProjectDiv = document.getElementById('new_project_div');
        const projectInput = document.getElementById('project_name');
        const form = document.querySelector('form');

        // Handle dropdown change
        projectSelect.addEventListener('change', function() {
            if (this.value === 'add_new') {
                // Show input field for new project
                newProjectDiv.classList.remove('hidden');
                projectInput.focus();
                projectInput.value = ''; // Clear the input
            } else {
                // Hide input field for existing projects and SET THE VALUE
                newProjectDiv.classList.add('hidden');
                projectInput.value = this.value; // SET VALUE IMMEDIATELY FOR EXISTING PROJECT
            }
        });

        // Handle form submission
        form.addEventListener('submit', function(e) {
            const selectedValue = projectSelect.value;
            const inputValue = projectInput.value.trim();

            // Check if user is adding new project
            if (selectedValue === 'add_new') {
                // Must have entered a project name
                if (!inputValue) {
                    e.preventDefault();
                    alert('Please enter a project name');
                    projectInput.focus();
                    return;
                }
                // projectInput.value is already set
            } else if (selectedValue) {
                // Existing project selected - value already set on change event
                projectInput.value = selectedValue;
            } else {
                // No project selected
                e.preventDefault();
                alert('Please select or create a project');
                return;
            }
        });
    });
</script>
@endpush
