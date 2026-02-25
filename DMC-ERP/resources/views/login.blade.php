<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - DMC ERP</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
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

                <!-- USERNAME FLOATING -->
                <div class="relative">
                    <span class="absolute left-4 top-4 text-gray-400">
                        <i data-feather="user"></i>
                    </span>

                    <input type="text" name="employee_id" placeholder=" "
                        class="peer w-full pl-12 pr-4 pt-6 pb-3 bg-gray-50 
                               border border-gray-300 rounded-xl
                               focus:outline-none focus:ring-2 
                               focus:ring-blue-600 focus:border-blue-600
                               focus:bg-white transition-all duration-200">

                    <label class="absolute left-12 top-2 text-xs text-gray-500
                                   peer-placeholder-shown:top-4 
                                   peer-placeholder-shown:text-sm
                                   peer-placeholder-shown:text-gray-400
                                   peer-focus:top-2 peer-focus:text-xs
                                   peer-focus:text-blue-600 transition-all">
                        Employee ID
                    </label>
                </div>

                <!-- PASSWORD FLOATING -->
                <div class="relative">
                    <span class="absolute left-4 top-4 text-gray-400">
                        <i data-feather="lock"></i>
                    </span>

                    <input type="password" name="password" placeholder=" "
                        class="peer w-full pl-12 pr-4 pt-6 pb-3 bg-gray-50 
                               border border-gray-300 rounded-xl
                               focus:outline-none focus:ring-2 
                               focus:ring-blue-600 focus:border-blue-600
                               focus:bg-white transition-all duration-200">

                    <label class="absolute left-12 top-2 text-xs text-gray-500
                                   peer-placeholder-shown:top-4 
                                   peer-placeholder-shown:text-sm
                                   peer-placeholder-shown:text-gray-400
                                   peer-focus:top-2 peer-focus:text-xs
                                   peer-focus:text-blue-600 transition-all">
                        Password
                    </label>
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
</script>

</body>
</html>