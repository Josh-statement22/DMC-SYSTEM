<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| LOGIN ROUTES
|--------------------------------------------------------------------------
*/

// Login Page
Route::get('/login', function() {
    return view('login');
})->name('login');

// Handle Login Form POST
Route::post('/login', function (Request $request) {

    // Dummy authentication
    if ($request->email === 'admin@dmc.com' && $request->password === 'password123') {

        session(['is_admin' => true]);

        // 👉 Redirect sa NEW admin dashboard
        return redirect()->route('admin.dashboard');
    }

    return back()->with('error', 'Invalid credentials');
});


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {

    Route::get('/dashboard', function () {

        if (!session('is_admin')) {
            return redirect('/login');
        }

        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/pricelist', function () {
        return view('admin.pricelist');
    })->name('admin.pricelist');

    Route::get('/purchase', function () {
        return view('admin.purchase');
    })->name('admin.purchase');

});