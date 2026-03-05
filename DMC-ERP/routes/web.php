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

Route::post('/admin/items/upload-image', function () {
    request()->validate([
        'item_id' => 'required|integer|exists:items,id',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp,heic,heif|max:10240', // Max 10MB
    ]);

    try {
        $itemId = request('item_id');
        $item = DB::table('items')->where('id', $itemId)->first();
        
        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item not found'], 404);
        }

        // Create slug for project name
        $projectSlug = strtolower(preg_replace('/\s+/', '_', trim($item->project)));
        
        // Create slug for item name
        $itemSlug = strtolower(preg_replace('/\s+/', '_', trim($item->item_name)));
        
        // Get file extension
        $extension = request()->file('image')->getClientOriginalExtension();
        $filename = $itemSlug . '.' . $extension;

        // Create directory structure
        $projectDir = 'items/projects/' . $projectSlug;
        
        // Store the file
        $path = request()->file('image')->storeAs($projectDir, $filename, 'public');

        // Update database
        DB::table('items')
            ->where('id', $itemId)
            ->update([
                'image_path' => $path,
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Image uploaded successfully',
            'image_path' => $path
        ]);

    } catch (\Exception $e) {
        \Log::error('Image upload failed: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Upload failed: ' . $e->getMessage()
        ], 500);
    }
})->middleware('auth');

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
        'base64_image' => 'nullable|string',
    ]);

    $projectName = request('project_name');
    $itemName = request('item_name');
    $imagePath = null;

    // Handle image upload
    if (request('base64_image')) {
        try {
            // Create slug for project name (lowercase, spaces to underscores)
            $projectSlug = strtolower(preg_replace('/\s+/', '_', trim($projectName)));
            
            // Create slug for item name (lowercase, spaces to underscores)
            $itemSlug = strtolower(preg_replace('/\s+/', '_', trim($itemName))) . '.jpg';

            // Create directory structure if it doesn't exist
            $projectDir = storage_path('app/public/items/projects/' . $projectSlug);
            if (!is_dir($projectDir)) {
                mkdir($projectDir, 0755, true);
            }

            // Extract base64 data
            $base64Image = request('base64_image');
            if (strpos($base64Image, 'data:image') === 0) {
                $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
            }

            // Decode and save image
            $imageData = base64_decode($base64Image);
            $filepath = $projectDir . '/' . $itemSlug;

            file_put_contents($filepath, $imageData);

            // Store relative path in database
            $imagePath = 'items/projects/' . $projectSlug . '/' . $itemSlug;
        } catch (\Exception $e) {
            \Log::error('Image upload failed: ' . $e->getMessage());
        }
    }

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
        'image_path' => $imagePath,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->route('admin.additem')->with('success', 'Item added successfully!');
})->middleware('auth')->name('admin.additem.store');

Route::get('/admin/priceanalysis', function () {
    return view('admin.priceanalysis');
})->middleware('auth')->name('admin.priceanalysis');

Route::get('/admin/liquidation', function () {
    return view('admin.liquidation');
})->middleware('auth')->name('admin.liquidation');


/*
|--------------------------------------------------------------------------
| API - Search Items
|--------------------------------------------------------------------------
*/

Route::get('/api/search-items', function (Request $request) {
    $query = $request->query('q', '');
    
    if (strlen($query) < 1) {
        return response()->json([]);
    }

    // Get all matching items
    $itemDetails = DB::table('items')
        ->where('item_number', 'LIKE', '%' . $query . '%')
        ->orWhere('item_name', 'LIKE', '%' . $query . '%')
        ->select('item_number', 'item_name', 'project', 'supplier', 'price', 'quantity')
        ->get();

    if ($itemDetails->isEmpty()) {
        return response()->json([]);
    }

    // Group by item number and name
    $groupedItems = [];
    foreach ($itemDetails as $item) {
        $key = $item->item_number . '|' . $item->item_name;
        
        if (!isset($groupedItems[$key])) {
            $groupedItems[$key] = [
                'number' => $item->item_number,
                'name' => $item->item_name,
                'project' => $item->project,
                'image' => '/images/placeholder.jpg',
                'suppliers' => []
            ];
        }

        // Add supplier if not already added
        $supplierExists = false;
        foreach ($groupedItems[$key]['suppliers'] as $s) {
            if ($s['supplier'] === $item->supplier) {
                $supplierExists = true;
                break;
            }
        }

        if (!$supplierExists) {
            $groupedItems[$key]['suppliers'][] = [
                'supplier' => $item->supplier,
                'price' => (float)$item->price,
                'quantity' => $item->quantity
            ];
        }
    }

    // Sort suppliers by price in each item
    foreach ($groupedItems as &$item) {
        usort($item['suppliers'], function($a, $b) {
            return $a['price'] <=> $b['price'];
        });
    }

    return response()->json(array_values($groupedItems));
})->middleware('auth')->name('api.search-items');

Route::get('/api/projects', function () {
    $projects = DB::table('projects')
        ->select('id', 'project_name as name')
        ->orderBy('project_name')
        ->get();
    
    return response()->json($projects);
})->middleware('auth')->name('api.projects');

// DEBUG TEST
Route::get('/test-items', function () {
    $items = DB::table('items')->limit(5)->get();
    return response()->json([
        'total' => DB::table('items')->count(),
        'sample' => $items
    ]);
})->middleware('auth');


/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');