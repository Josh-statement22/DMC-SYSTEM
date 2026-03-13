<!-- Project Dropdown Component - Available for future use -->
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

@push('scripts')
<script>
    // Project Dropdown Logic
    document.addEventListener('DOMContentLoaded', function() {
        const projectSelect = document.getElementById('project_select');
        const newProjectDiv = document.getElementById('new_project_div');
        const projectInput = document.getElementById('project_name');
        const form = document.querySelector('form');

        // Handle dropdown change
        projectSelect?.addEventListener('change', function() {
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
        form?.addEventListener('submit', function(e) {
            const selectedValue = projectSelect?.value;
            const inputValue = projectInput?.value.trim();

            // Check if user is adding new project
            if (selectedValue === 'add_new') {
                // Must have entered a project name
                if (!inputValue) {
                    e.preventDefault();
                    alert('Please enter a project name');
                    projectInput?.focus();
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
