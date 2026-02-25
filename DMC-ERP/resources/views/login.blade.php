<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - DMC ERP</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        /* Hide number input spinners */
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type="number"] {
            -moz-appearance: textfield;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center relative overflow-hidden
bg-gradient-to-br from-blue-950 via-blue-700 to-blue-200">

    <!-- Decorative blurred background -->
    <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-blue-500 opacity-30 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-40 -right-40 w-[500px] h-[500px] bg-blue-300 opacity-30 rounded-full blur-3xl"></div>

    <!-- LOGIN CARD -->
    <div class="relative z-10 w-[420px] bg-white rounded-3xl shadow-2xl overflow-hidden">

        <!-- TOP BLUE LINE -->
        <div class="h-2 bg-gradient-to-r from-blue-800 to-blue-500"></div>

        <div class="p-10">

            <div class="flex justify-center mb-6">
    <img src="{{ asset('images/logo.png') }}" 
         alt="Company Logo"
         class="w-24 h-24 object-contain drop-shadow-lg">
</div>

            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-800">
                    DMC Enterprises Corp
                </h1>
            </div>

            <form method="POST" action="/login" class="space-y-6">
                @csrf

                <!-- ERROR ALERT -->
                @if ($errors->any())
                    <div id="error_alert" class="p-4 bg-red-50 border border-red-300 rounded-xl flex items-start gap-3">
                        <i data-feather="alert-circle" class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-semibold text-red-800 mb-2">Login Failed</p>
                            @foreach ($errors->all() as $error)
                                <p class="text-sm text-red-700">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- SESSION ERROR ALERT -->
                @if (session('error'))
                    <div id="session_error_alert" class="p-4 bg-red-50 border border-red-300 rounded-xl flex items-start gap-3">
                        <i data-feather="alert-circle" class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <!-- USERNAME FLOATING -->
                <div class="relative">
                    <span class="absolute left-4 top-4 text-gray-400">
                        <i data-feather="user"></i>
                    </span>

                    <input type="number" id="employee_id" name="employee_id" placeholder=" " 
                        required
                        value="{{ old('employee_id') }}"
                        class="peer w-full pl-12 pr-4 pt-6 pb-3 bg-gray-50 
                               border border-gray-300 rounded-xl
                               focus:outline-none focus:ring-2 
                               focus:ring-blue-600 focus:border-blue-600
                               focus:bg-white transition-all duration-200
                               {{ $errors->has('employee_id') ? 'border-red-500 focus:ring-red-600' : '' }}"
                        oninput="validateEmployeeID()">

                    <label class="absolute left-12 top-2 text-xs text-gray-500
                                   peer-placeholder-shown:top-4 
                                   peer-placeholder-shown:text-sm
                                   peer-placeholder-shown:text-gray-400
                                   peer-focus:top-2 peer-focus:text-xs
                                   peer-focus:text-blue-600 transition-all">
                        Employee ID
                    </label>

                    <div id="employee_id_error" class="hidden text-xs text-red-600 mt-1"></div>
                </div>

                <!-- PASSWORD FLOATING -->
                <div class="relative">
                    <span class="absolute left-4 top-4 text-gray-400">
                        <i data-feather="lock"></i>
                    </span>

                    <input type="password" id="password" name="password" placeholder=" " 
                        required minlength="6"
                        class="peer w-full pl-12 pr-4 pt-6 pb-3 bg-gray-50 
                               border border-gray-300 rounded-xl
                               focus:outline-none focus:ring-2 
                               focus:ring-blue-600 focus:border-blue-600
                               focus:bg-white transition-all duration-200
                               {{ $errors->has('password') ? 'border-red-500 focus:ring-red-600' : '' }}"
                        oninput="validatePassword()">

                    <label class="absolute left-12 top-2 text-xs text-gray-500
                                   peer-placeholder-shown:top-4 
                                   peer-placeholder-shown:text-sm
                                   peer-placeholder-shown:text-gray-400
                                   peer-focus:top-2 peer-focus:text-xs
                                   peer-focus:text-blue-600 transition-all">
                        Password
                    </label>

                    <div id="password_error" class="hidden text-xs text-red-600 mt-1"></div>
                </div>

                <!-- BUTTON -->
                <button type="submit"
                    class="w-full bg-blue-800 hover:bg-blue-900
                           text-white py-4 rounded-xl
                           font-semibold tracking-wide
                           shadow-lg transition duration-300
                           hover:shadow-xl active:scale-[0.98]">
                    Sign In
                </button>

            </form>

        </div>
    </div>

<script>
    feather.replace()

    function validateEmployeeID() {
        const input = document.getElementById('employee_id');
        const errorDiv = document.getElementById('employee_id_error');
        let error = '';

        if (input.value.trim() === '') {
            error = 'This field is required';
        } else if (input.value.length < 5) {
            error = 'Employee ID must be at least 5 digits';
        }

        if (error) {
            errorDiv.textContent = error;
            errorDiv.classList.remove('hidden');
            input.classList.add('border-red-500');
            input.classList.remove('border-gray-300');
        } else {
            errorDiv.classList.add('hidden');
            input.classList.remove('border-red-500');
            input.classList.add('border-gray-300');
        }
    }

    function validatePassword() {
        const input = document.getElementById('password');
        const errorDiv = document.getElementById('password_error');
        let error = '';

        if (input.value.trim() === '') {
            error = 'This field is required';
        } else if (input.value.length < 6) {
            error = 'Password must be at least 6 characters';
        }

        if (error) {
            errorDiv.textContent = error;
            errorDiv.classList.remove('hidden');
            input.classList.add('border-red-500');
            input.classList.remove('border-gray-300');
        } else {
            errorDiv.classList.add('hidden');
            input.classList.remove('border-red-500');
            input.classList.add('border-gray-300');
        }
    }

    // Run validation on page load if there are old values (from failed submission)
    window.addEventListener('load', () => {
        if (document.getElementById('employee_id').value) {
            validateEmployeeID();
        }
        if (document.getElementById('password').value) {
            validatePassword();
        }

        // Auto-hide error alerts after 5 seconds
        const errorAlert = document.getElementById('error_alert');
        const sessionErrorAlert = document.getElementById('session_error_alert');

        if (errorAlert) {
            setTimeout(() => {
                errorAlert.style.transition = 'opacity 0.3s ease';
                errorAlert.style.opacity = '0';
                setTimeout(() => errorAlert.remove(), 300);
            }, 5000);
        }

        if (sessionErrorAlert) {
            setTimeout(() => {
                sessionErrorAlert.style.transition = 'opacity 0.3s ease';
                sessionErrorAlert.style.opacity = '0';
                setTimeout(() => sessionErrorAlert.remove(), 300);
            }, 5000);
        }
    });
</script>

</body>
</html>