<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Login Page
Route::get('/login', function() {
    return view('login');
})->name('login');

// Handle Login Form POST
Route::post('/login', function (Request $request) {
    // Dummy authentication
    if ($request->email === 'admin@dmc.com' && $request->password === 'password123') {
        session(['is_admin' => true]);
        return redirect('/admin-dashboard'); // Redirect sa admin-dashboard
    }
    return back()->with('error', 'Invalid credentials');
});

// Admin Dashboard (Super Admin) Page
Route::get('/admin-dashboard', function () {
    if (!session('is_admin')) {
        return redirect('/login');
    }
    return view('admin-dashboard'); // <-- filename mo na admin-dashboard.blade.php
});

require __DIR__.'/settings.php';