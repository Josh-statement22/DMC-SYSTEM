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

        $user = User::where('employee_id', $request->employee_id)->first();

        if ($user && Hash::check($request->password, $user->password)) {
                session([
                    'user_id' => $user->id,
                    'role_id' => $user->role_id,
                    'is_superadmin' => $user->role_id == 1,
                    'is_admin' => $user->role_id == 2
                ]);

                if ($user->role_id == 1) {
                    return redirect('/superadmin');
                } else {
                    return redirect('/admin/dashboard');
                }
        }

        return back()->with('error', 'Invalid employee ID or password');
    });


     /*
    |--------------------------------------------------------------------------
    | SUPERADMIN ROUTE
    |--------------------------------------------------------------------------
    */

    Route::get('/superadmin', function () {

        $roleId = session('role_id');
        if (empty($roleId) || $roleId != 1) {
            return redirect()->route('login');
        }

        return view('superadmin.dashboard');

    })->name('superadmin.dashboard');


      /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTES
    |--------------------------------------------------------------------------
    */
    //Dashboard Routes (Admin)

    Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        $roleId = session('role_id');
        if (empty($roleId) || $roleId != 2) {
            return redirect()->route('login');
        }
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Pricelist Page
    Route::get('/pricelist', function () {
        $roleId = session('role_id');
        if (empty($roleId) || $roleId != 2) {
            return redirect()->route('login');
        }
        return view('admin.pricelist');
    })->name('admin.pricelist');

    // Purchases Page
    Route::get('/purchase', function () {
        $roleId = session('role_id');
        if (empty($roleId) || $roleId != 2) {
            return redirect()->route('login');
        }
        return view('admin.purchase');
    })->name('admin.purchase'); 

});

});