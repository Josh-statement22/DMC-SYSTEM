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
        ->select('id', 'project_name')
        ->orderBy('project_name')
        ->get();

    $selectedProjectId = (int) $request->query('project');
    $projectIds = $projects->pluck('id');

    if (!$selectedProjectId || !$projectIds->contains($selectedProjectId)) {
        $selectedProjectId = optional($projects->first())->id;
    }

    $selectedProject = optional($projects->firstWhere('id', $selectedProjectId))->project_name;

    $items = collect();

    if ($selectedProjectId) {
        $items = DB::table('project_items')
            ->join('items', 'project_items.item_id', '=', 'items.id')
            ->leftJoin('suppliers', 'project_items.supplier_id', '=', 'suppliers.id')
            ->where('project_items.project_id', $selectedProjectId)
            ->select(
                'items.id',
                'items.item_number',
                'items.item_name',
                'items.item_description',
                'suppliers.supplier_name',
                'project_items.quantity',
                'project_items.unit_price as price',
                'items.image_path'
            )
            ->orderBy('items.item_number')
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

        // Create slug for item name
        $itemSlug = strtolower(preg_replace('/\s+/', '_', trim($item->item_name)));
        
        // Get file extension
        $extension = request()->file('image')->getClientOriginalExtension();
        $filename = $itemSlug . '.' . $extension;

        // Store images in a schema-safe path that does not depend on removed columns.
        $projectDir = 'items/uploads';
        
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
    $categories = DB::table('categories')->orderBy('category_name')->get();
    return view('admin.additem', compact('projects', 'categories'));
})->middleware('auth')->name('admin.additem');

Route::post('/admin/additem', function () {
    // Validate based on whether user is creating a new category or selecting existing
    if (request('category_id') === 'add_new') {
        request()->validate([
            'item_number' => 'required|string|max:255',
            'item_name' => 'required|string|max:255',
            'item_description' => 'required|string',
            'supplier_name' => 'required|string|max:255',
            'supplier_phone' => 'required|string|max:255',
            'supplier_address' => 'required|string',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
            'new_category' => 'required|string|max:255',
            'base64_image' => 'nullable|string',
        ]);
    } else {
        request()->validate([
            'item_number' => 'required|string|max:255',
            'item_name' => 'required|string|max:255',
            'item_description' => 'required|string',
            'category_id' => 'required|integer|exists:categories,id',
            'supplier_name' => 'required|string|max:255',
            'supplier_phone' => 'required|string|max:255',
            'supplier_address' => 'required|string',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
            'base64_image' => 'nullable|string',
        ]);
    }

    $itemName = request('item_name');
    $imagePath = null;

    // Handle image upload
    if (request('base64_image')) {
        try {
            // Create slug for item name (lowercase, spaces to underscores)
            $itemSlug = strtolower(preg_replace('/\s+/', '_', trim($itemName))) . '_' . uniqid() . '.jpg';

            // Create directory structure if it doesn't exist
            $projectDir = storage_path('app/public/items');
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
            $imagePath = 'items/' . $itemSlug;
        } catch (\Exception $e) {
            \Log::error('Image upload failed: ' . $e->getMessage());
        }
    }

    // Handle category: create new if add_new is selected
    if (request('category_id') === 'add_new') {
        $categoryId = DB::table('categories')->insertGetId([
            'category_name' => request('new_category'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    } else {
        $categoryId = request('category_id');
    }

    // Create/find supplier first, then store supplier_id in items table.
    $supplierId = DB::table('suppliers')->where('supplier_name', request('supplier_name'))->value('id');

    if (!$supplierId) {
        $supplierId = DB::table('suppliers')->insertGetId([
            'supplier_name' => request('supplier_name'),
            'phone_number' => request('supplier_phone'),
            'address' => request('supplier_address'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    } else {
        DB::table('suppliers')
            ->where('id', $supplierId)
            ->update([
                'phone_number' => request('supplier_phone'),
                'address' => request('supplier_address'),
                'updated_at' => now(),
            ]);
    }

    DB::table('items')->insert([
        'item_number' => request('item_number'),
        'item_name' => request('item_name'),
        'item_description' => request('item_description'),
        'category_id' => $categoryId,
        'supplier_id' => $supplierId,
        'quantity' => request('quantity'),
        'price' => request('price'),
        'purchase_date' => request('purchase_date'),
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
        ->select('id', 'project_name')
        ->orderBy('project_name')
        ->get();
    
    return response()->json($projects);
})->middleware('auth')->name('api.projects');

Route::post('/api/projects', function (Request $request) {
    try {
        $request->validate([
            'project_name' => 'required|string|max:255|unique:projects',
            'project_date' => 'required|date'
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed: ' . implode(', ', array_map(fn($errors) => implode(', ', $errors), $e->errors()))
        ], 422);
    }
    
    $projectId = DB::table('projects')->insertGetId([
        'project_name' => $request->project_name,
        'project_date' => $request->project_date,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    
    return response()->json([
        'success' => true,
        'project' => [
            'id' => $projectId,
            'project_name' => $request->project_name,
            'project_date' => $request->project_date
        ]
    ]);
})->middleware('auth')->name('api.projects.store');

// Price Analysis API - Search for items with all supplier prices
Route::get('/api/price-analysis/search', function (Request $request) {
    $query = $request->query('q');
    
    if (!$query) {
        return response()->json(['success' => false, 'message' => 'Query required']);
    }

    // Find ALL matching items by item_number or item_name
    $matchingItems = DB::table('items')
        ->where('item_number', 'LIKE', "%{$query}%")
        ->orWhere('item_name', 'LIKE', "%{$query}%")
        ->select('item_number', 'item_name', 'item_description', 'image_path')
        ->distinct()
        ->limit(10) // Limit to top 10 matches
        ->get();

    if ($matchingItems->isEmpty()) {
        return response()->json(['success' => false, 'message' => 'No items found']);
    }

    // For each unique item, get all suppliers and prices
    $results = [];
    foreach ($matchingItems as $matchedItem) {
        $allPurchases = DB::table('items')
            ->leftJoin('suppliers', 'items.supplier_id', '=', 'suppliers.id')
            ->where('items.item_number', $matchedItem->item_number)
            ->select(
                'items.id',
                'items.item_number',
                'items.item_name',
                'items.item_description',
                'items.image_path',
                'items.price',
                'items.quantity',
                'items.purchase_date',
                'items.supplier_id',
                'suppliers.supplier_name',
                'suppliers.phone_number',
                'suppliers.address'
            )
            ->orderBy('items.price', 'asc')
            ->get();

        // Group suppliers for this item
        $suppliers = $allPurchases->map(function($purchase) {
            return [
                'item_id' => $purchase->id,
                'supplier_id' => $purchase->supplier_id,
                'supplier_name' => $purchase->supplier_name ?? 'N/A',
                'phone_number' => $purchase->phone_number,
                'address' => $purchase->address,
                'price' => (float) $purchase->price,
                'quantity' => $purchase->quantity,
                'purchase_date' => $purchase->purchase_date
            ];
        });

        $results[] = [
            'item_number' => $matchedItem->item_number,
            'item_name' => $matchedItem->item_name,
            'item_description' => $matchedItem->item_description,
            'image_path' => $matchedItem->image_path,
            'suppliers' => $suppliers
        ];
    }

    return response()->json([
        'success' => true,
        'items' => $results
    ]);
})->middleware('auth')->name('api.price-analysis.search');

// Price Analysis API - Add item to project
Route::post('/api/price-analysis/add-to-project', function (Request $request) {
    try {
        $data = $request->validate([
            'project_id' => 'required|integer|exists:projects,id',
            'item_id' => 'required|integer|exists:items,id',
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0'
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed: ' . implode(', ', array_map(fn($errors) => implode(', ', $errors), $e->errors()))
        ], 422);
    }

    $item = DB::table('items')
        ->select('id', 'quantity')
        ->where('id', $data['item_id'])
        ->first();

    if (!$item) {
        return response()->json([
            'success' => false,
            'message' => 'Item not found'
        ], 404);
    }

    $allocatedQuantity = (int) DB::table('project_items')
        ->where('item_id', $data['item_id'])
        ->sum('quantity');

    $remainingStock = (int) $item->quantity - $allocatedQuantity;

    if ((int) $data['quantity'] > $remainingStock) {
        return response()->json([
            'success' => false,
            'message' => 'Requested quantity exceeds remaining stock',
            'remaining_stock' => max(0, $remainingStock)
        ], 422);
    }

    // Insert/Update in project_items pivot table
    $existingLink = DB::table('project_items')
        ->where('project_id', $data['project_id'])
        ->where('item_id', $data['item_id'])
        ->where('supplier_id', $data['supplier_id'])
        ->first();

    if ($existingLink) {
        // Update quantity and unit_price if already linked
        DB::table('project_items')
            ->where('id', $existingLink->id)
            ->update([
                'quantity' => (int) $existingLink->quantity + (int) $data['quantity'],
                'unit_price' => $data['unit_price'],
                'updated_at' => now()
            ]);
    } else {
        // Create new link
        DB::table('project_items')->insert([
            'project_id' => $data['project_id'],
            'item_id' => $data['item_id'],
            'supplier_id' => $data['supplier_id'],
            'quantity' => $data['quantity'],
            'unit_price' => $data['unit_price'],
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    return response()->json([
        'success' => true,
        'message' => 'Item added to project successfully'
    ]);
})->middleware('auth')->name('api.price-analysis.add-to-project');

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
| Price Analysis - Category-Based Navigation
|--------------------------------------------------------------------------
*/

// Get all categories
Route::get('/api/categories', function () {
    $categories = DB::table('categories')
        ->select('id', 'category_name')
        ->orderBy('category_name', 'asc')
        ->get();
    
    return response()->json([
        'success' => true,
        'categories' => $categories
    ]);
})->middleware('auth')->name('api.categories');

// Get items by category
Route::get('/api/categories/{categoryId}/items', function ($categoryId) {
    $category = DB::table('categories')
        ->where('id', $categoryId)
        ->select('id', 'category_name')
        ->first();
    
    if (!$category) {
        return response()->json(['success' => false, 'message' => 'Category not found'], 404);
    }
    
    $items = DB::table('items')
        ->where('category_id', $categoryId)
        ->select('id', 'item_number', 'item_name', 'item_description', 'image_path')
        ->distinct()
        ->get();
    
    return response()->json([
        'success' => true,
        'category' => $category,
        'items' => $items
    ]);
})->middleware('auth')->name('api.categories.items');

// Get item details with suppliers
Route::get('/api/categories/items/{itemId}/details', function ($itemId) {
    $item = DB::table('items')
        ->where('id', $itemId)
        ->select('id', 'item_number', 'item_name', 'item_description', 'image_path', 'category_id')
        ->first();
    
    if (!$item) {
        return response()->json(['success' => false, 'message' => 'Item not found'], 404);
    }
    
    // Get all suppliers for this item
    $suppliers = DB::table('items')
        ->leftJoin('suppliers', 'items.supplier_id', '=', 'suppliers.id')
        ->where('items.item_number', $item->item_number)
        ->select(
            'items.id',
            'items.price',
            'items.quantity',
            'items.purchase_date',
            'items.supplier_id',
            'suppliers.supplier_name',
            'suppliers.phone_number',
            'suppliers.address'
        )
        ->orderBy('items.price', 'asc')
        ->get();
    
    $suppliers = $suppliers->map(function($purchase) {
        return [
            'item_id' => $purchase->id,
            'supplier_id' => $purchase->supplier_id,
            'supplier_name' => $purchase->supplier_name ?? 'N/A',
            'phone_number' => $purchase->phone_number,
            'address' => $purchase->address,
            'price' => (float) $purchase->price,
            'quantity' => $purchase->quantity,
            'purchase_date' => $purchase->purchase_date
        ];
    });
    
    return response()->json([
        'success' => true,
        'item' => [
            'id' => $item->id,
            'item_number' => $item->item_number,
            'item_name' => $item->item_name,
            'item_description' => $item->item_description,
            'image_path' => $item->image_path,
            'suppliers' => $suppliers
        ]
    ]);
})->middleware('auth')->name('api.items.details');

// Create new category
Route::post('/api/categories', function (Request $request) {
    $validated = $request->validate([
        'category_name' => 'required|string|max:255|unique:categories,category_name'
    ]);
    
    $category = DB::table('categories')->insertGetId([
        'category_name' => $validated['category_name'],
        'created_at' => now(),
        'updated_at' => now()
    ]);
    
    return response()->json([
        'success' => true,
        'message' => 'Category created successfully',
        'category_id' => $category,
        'category_name' => $validated['category_name']
    ]);
})->middleware('auth')->name('api.categories.store');


/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');