<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Login Page
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('login');
});

Route::get('/login', function () {
    return view('login');
})->name('login');


/*
|--------------------------------------------------------------------------
| Login Handler
|--------------------------------------------------------------------------
*/

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'employee_id' => 'required',
        'password' => 'required'
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        
        $user = Auth::user();
        
        // Redirect based on role_id
        if ($user->role_id == 1) {
            return redirect('/superadmin/dashboard');
        }
        
        if ($user->role_id == 2) {
            return redirect('/admin/dashboard');
        }
        
        // If unknown role
        Auth::logout();
        return back()->with('error', 'Unauthorized role');
    }

    return back()->with('error', 'Invalid credentials');
});


/*
|--------------------------------------------------------------------------
| Superadmin Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/superadmin/dashboard', function () {
    $perPage = 10;
    $page = request('page', 1);
    $allUsers = \App\Models\User::with('role')->get();
    
    $totalUsers = $allUsers->count();
    $totalPages = ceil($totalUsers / $perPage);
    $startIndex = ($page - 1) * $perPage;
    $users = $allUsers->slice($startIndex, $perPage)->values();
    
    return view('superadmin.dashboard', [
        'users' => $users,
        'currentPage' => $page,
        'totalPages' => $totalPages,
        'totalUsers' => $totalUsers
    ]);
})->middleware('auth')->name('superadmin.dashboard');

Route::post('/superadmin/users/store', function (Request $request) {
    $validated = $request->validate([
        'employee_id' => 'required|unique:users,employee_id',
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|unique:users,email',
        'password' => 'required|min:6',
        'role_id' => 'required|exists:roles,id'
    ]);

    $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);

    \App\Models\User::create($validated);

    return redirect()->route('superadmin.dashboard')->with('success', 'User created successfully');
})->middleware('auth')->name('superadmin.users.store');

Route::delete('/superadmin/users/{id}', function ($id) {
    $user = \App\Models\User::findOrFail($id);
    $userName = $user->name;
    $user->delete();

    return redirect()->route('superadmin.dashboard')->with('success', "User '{$userName}' has been removed successfully");
})->middleware('auth')->name('superadmin.users.destroy');

Route::put('/superadmin/users/{id}', function (Request $request, $id) {
    $user = \App\Models\User::findOrFail($id);

    $rules = [
        'employee_id' => 'required|unique:users,employee_id,' . $id,
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|unique:users,email,' . $id,
        'role_id' => 'required|exists:roles,id'
    ];

    // Only validate password if provided
    if ($request->filled('password')) {
        $rules['password'] = 'required|min:6|confirmed';
    }

    $validated = $request->validate($rules);

    // Hash password if provided
    if ($request->filled('password')) {
        $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
    } else {
        unset($validated['password']);
    }

    $user->update($validated);

    return redirect()->route('superadmin.dashboard')->with('success', "User '{$user->name}' has been updated successfully");
})->middleware('auth')->name('superadmin.users.update');


/*
|--------------------------------------------------------------------------
| Admin Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware('auth')->name('admin.dashboard');

Route::get('/admin/pricelist', function (Request $request) {
    $projects = DB::table('projects')
        ->orderBy('project_name')
        ->pluck('project_name');

    $selectedProject = $request->query('project');

    if (!$selectedProject || !$projects->contains($selectedProject)) {
        $selectedProject = $projects->first();
    }

    $items = collect();

    if ($selectedProject) {
        $items = DB::table('items')
            ->where('project', $selectedProject)
            ->orderBy('item_number')
            ->get();
    }

    return view('admin.pricelist', compact('projects', 'selectedProject', 'items'));
})->middleware('auth')->name('admin.pricelist');

Route::get('/admin/purchase', function () {
    return view('admin.purchase');
})->middleware('auth')->name('admin.purchase');

Route::get('/admin/additem', function () {
    $projects = DB::table('projects')->orderBy('project_name')->get();
    return view('admin.additem', compact('projects'));
})->middleware('auth')->name('admin.additem');

Route::post('/admin/additem', function () {
    request()->validate([
        'project_name' => 'required|string|max:255',
        'item_number' => 'required|string|max:255',
        'item_name' => 'required|string|max:255',
        'item_description' => 'required|string',
        'supplier' => 'required|string|max:255',
        'quantity' => 'required|integer|min:0',
        'price' => 'required|numeric|min:0',
    ]);

    $projectName = request('project_name');

    // Auto-create project if it doesn't exist
    DB::table('projects')->insertOrIgnore([
        'project_name' => $projectName,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('items')->insert([
        'project' => $projectName,
        'item_number' => request('item_number'),
        'item_name' => request('item_name'),
        'item_description' => request('item_description'),
        'supplier' => request('supplier'),
        'quantity' => request('quantity'),
        'price' => request('price'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->route('admin.additem')->with('success', 'Item added successfully!');
})->middleware('auth')->name('admin.additem.store');


/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');