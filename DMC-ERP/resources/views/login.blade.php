<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - DMC ERP</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <h1 class="text-2xl font-bold mb-6 text-center">DMC ERP Login</h1>
        <form method="POST" action="/login">
            @csrf
            <div class="mb-4">
                <label class="block mb-2">Email</label>
                <input type="email" name="email" class="w-full border rounded p-2" placeholder="admin@dmc.com">
            </div>
            <div class="mb-4">
                <label class="block mb-2">Password</label>
                <input type="password" name="password" class="w-full border rounded p-2" placeholder="password123">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                Login
            </button>
        </form>
    </div>
</body>
</html>