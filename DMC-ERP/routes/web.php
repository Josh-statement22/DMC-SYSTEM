<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

Route::middleware('web')->group(function () {

    // Login Page
    Route::get('/login', function () {
        return view('login');
    })->name('login');

    // Handle Login
    Route::post('/login', function (Request $request) {

        $user = User::where('employee_id', $request->employee_id)->first(); // <- FIXED

        if ($user && Hash::check($request->password, $user->password)) {
            session([
                'user_id' => $user->id,
                'is_admin' => $user->role_id == 1
            ]);

            if ($user->role_id == 1) {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('staff.dashboard');
            }
        }

        return back()->with('error', 'Invalid employee ID or password');
    });

    Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        if (!session('is_admin')) {
            return redirect()->route('login');
        }
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/pricelist', function () {
        return view('admin.pricelist');
    })->name('admin.pricelist');

    Route::get('/purchase', function () {
        return view('admin.purchase');
    })->name('admin.purchase'); // <- DITO
});

});