#  PHP_Laravel11_CRUD_Using_API (Full Documentation)

## Introduction

- This project is a **Laravel 11 API CRUD application** for managing products.  

- It provides a RESTful API to **create, read, update, and soft delete products**.

- The application uses **Eloquent ORM**, **Soft Deletes**, and **JSON responses** for all API endpoints. 

- All front-end views are built using **Blade templates** and **Tailwind CSS** for styling.

---    

##  1. Project Overview

### API Endpoints:

- Create Product → `/api/products/add`
- List All Products → `/api/products`
- Get Single Product → `/api/products/{id}`
- Edit Product → `/api/products/edit/{id}`
- Update Product → `/api/products/update/{id}`
- Soft Delete Product → `/api/products/delete/{id}`

### Features:

- Laravel 11 new routing structure (`bootstrap/app.php`)
- Soft deletes using Eloquent
- JSON responses for all endpoints
- Validation for name, status, and optional detail

---

##  2. Installation Commands
# Create Laravel 11 project
```bash

composer create-project laravel/laravel:^11.0 PHP_Laravel11_CRUD_Using_API

cd PHP_Laravel11_CRUD_Using_API
```

# Copy environment file
```
cp .env.example .env
```
# Generate application key
```
php artisan key:generate
```
Update .env database settings:

env
```

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel11_api
DB_USERNAME=root
DB_PASSWORD=
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci

```
Run migrations:
```

php artisan migrate

```
Start server:
```
php artisan serve

```
3. Folder Structure
```
PHP_Laravel11_CRUD_Using_API/
│
├── app/
│   ├── Models/
│   │   └── Product.php
│   └── Http/
│       └── Controllers/
│           └── Api/
│               └── ProductApiController.php
│
├── bootstrap/
│   └── app.php        ← Laravel 11 routing configuration
│
├── database/
│   └── migrations/
│       └── 2025_xx_xx_create_products_table.php
│
├── resources/
│   └── views/
│       └── products/
│           ├── index.blade.php
│           ├── create.blade.php
│           ├── edit.blade.php
│           └── show.blade.php
│
├── routes/
│   ├── api.php
│   ├── web.php
│   └── console.php
│
└── .env

```
4. bootstrap/app.php (Laravel 11 Routing Configuration)
```

<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

/**
 * Laravel 11 bootstrap file.
 * This file initializes the application and sets up routing, middleware, and exception handling.
 */

return Application::configure(
    basePath: dirname(__DIR__)
)
->withRouting(
    web: __DIR__.'/../routes/web.php',
    api: __DIR__.'/../routes/api.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up'
)
->withMiddleware(function (Middleware $middleware) {
})
->withExceptions(function (Exceptions $exceptions) {
})
->create();

```
5. Migration – Products Table

# Create migration for products table

```
php artisan make:migration create_products_table --create=products
```
File: database/migrations/2025_xx_xx_create_products_table.php
```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();                     // Primary key
            $table->string('name');           // Product name
            $table->text('detail')->nullable(); // Optional product description
            $table->enum('status', ['active','inactive'])->default('active'); // Status
            $table->unsignedBigInteger('created_by')->nullable(); // Creator ID
            $table->unsignedBigInteger('updated_by')->nullable(); // Updater ID
            $table->timestamps();             // created_at & updated_at
            $table->softDeletes();            // deleted_at for soft delete
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

```
# Run database migrations
```
php artisan migrate

```
6. Product Model

# Create Product model
```
php artisan make:model Product

```
File: app/Models/Product.php
```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    //  Allows factory usage for testing and seeding
    //  Enables soft delete functionality (deleted_at column)
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * These fields can be inserted/updated using
     * Product::create() or $product->update()
     */
    protected $fillable = [
        'name',        // Product name
        'detail',      // Product description/details
        'status',      // Product status (active / inactive)
        'created_by',  // ID of user who created the product
        'updated_by'   // ID of user who last updated the product
    ];

    /**
     * The attributes that should be treated as dates.
     *
     * Laravel automatically converts 'deleted_at'
     * to a Carbon date instance for easy date handling.
     */
    protected $dates = ['deleted_at'];
}

```
7. API Controller

# Create API controller
```
php artisan make:controller Api/ProductApiController
```
File: app/Http/Controllers/Api/ProductApiController.php
```

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class ProductApiController extends Controller
{
    /**
     * -----------------------------------------------------
     * Get All Products
     * -----------------------------------------------------
     * Fetches all products that are NOT soft deleted.
     * Soft deleted records are automatically excluded
     * because of SoftDeletes in Product model.
     *
     * Method: GET
     * URL: /api/products
     */
    public function getAllProducts()
    {
        // Fetch all active (non-deleted) products
        $products = Product::all();

        // Return products as JSON with HTTP 200 (OK)
        return response()->json($products, 200);
    }

    /**
     * -----------------------------------------------------
     * Get Single Product by ID
     * -----------------------------------------------------
     * Returns a specific product using its ID.
     *
     * Method: GET
     * URL: /api/products/{id}
     */
    public function getProduct($id)
    {
        // Find product by primary key
        $product = Product::find($id);

        // If product does not exist, return 404
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Return found product
        return response()->json($product, 200);
    }

    /**
     * -----------------------------------------------------
     * Add New Product
     * -----------------------------------------------------
     * Validates input and stores a new product
     * in the database.
     *
     * Method: POST
     * URL: /api/products
     */
    public function addProduct(Request $request)
    {
        // Validate request data
        $request->validate([
            'name'   => 'required|string|max:255',
            'detail' => 'nullable|string',
            'status' => 'nullable|in:active,inactive'
        ]);

        // Create product using mass assignment
        $product = Product::create([
            'name'       => $request->name,
            'detail'     => $request->detail,
            'status'     => $request->status ?? 'active',
            'created_by' => $request->created_by, // optional user id
        ]);

        // Return created product with HTTP 201 (Created)
        return response()->json($product, 201);
    }

    /**
     * -----------------------------------------------------
     * Edit Product (Fetch Data for Edit)
     * -----------------------------------------------------
     * Used to fetch product details before updating.
     *
     * Method: GET
     * URL: /api/products/{id}/edit
     */
    public function editProduct($id)
    {
        // Find product
        $product = Product::find($id);

        // If product not found, return 404
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Return product data for editing
        return response()->json($product, 200);
    }

    /**
     * -----------------------------------------------------
     * Update Product
     * -----------------------------------------------------
     * Updates an existing product based on ID.
     *
     * Method: PUT / PATCH
     * URL: /api/products/{id}
     */
    public function updateProduct(Request $request, $id)
    {
        // Find product by ID
        $product = Product::find($id);

        // If product not found, return 404
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Validate updated data
        $request->validate([
            'name'   => 'required|string|max:255',
            'detail' => 'nullable|string',
            'status' => 'nullable|in:active,inactive'
        ]);

        // Update product fields
        $product->update([
            'name'       => $request->name,
            'detail'     => $request->detail,
            'status'     => $request->status ?? $product->status,
            'updated_by' => $request->updated_by, // optional user id
        ]);

        // Return updated product
        return response()->json($product, 200);
    }

    /**
     * -----------------------------------------------------
     * Soft Delete Product
     * -----------------------------------------------------
     * Soft deletes the product (sets deleted_at column).
     * Data is NOT permanently removed.
     *
     * Method: DELETE
     * URL: /api/products/{id}
     */
    public function softDeleteProduct($id)
    {
        // Find product
        $product = Product::find($id);

        // If product not found, return 404
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Soft delete the product
        $product->delete();

        // Return success message
        return response()->json([
            'message' => 'Product deleted successfully'
        ], 200);
    }
}

```
8. API Routes

File: routes/api.php
```
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductApiController;

// Get all products
Route::get('products', [ProductApiController::class, 'getAllProducts'])->name('products.allLists');

// Get single product
Route::get('products/{id}', [ProductApiController::class, 'getProduct'])->name('products.list');

// Create product
Route::post('products/add', [ProductApiController::class, 'addProduct'])->name('products.add');

// Edit product
Route::get('products/edit/{id}', [ProductApiController::class, 'editProduct'])->name('products.edit');

// Update product
Route::post('products/update/{id}', [ProductApiController::class, 'updateProduct'])->name('products.update');

// Soft delete product
Route::post('products/delete/{id}', [ProductApiController::class, 'softDeleteProduct'])->name('products.delete');

```
9. Blade Views (Tailwind CSS)

File: resources/views/products/

index.blade.php – 

List Products

Displays all products

Provides View/Edit/Delete buttons
```
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Set character encoding -->
    <meta charset="UTF-8">

    <!-- Make page responsive on all devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Page title -->
    <title>Products List</title>

    <!-- Tailwind CSS CDN for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-8">

    <!-- Page Heading -->
    <h2 class="text-2xl font-bold mb-4">Products List</h2>

    <!-- Add Product Button (Redirects to product create page) -->
    <a href="{{ route('products.add') }}" 
       class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">
        Add Product
    </a>

    <!-- Display success message after create/update/delete -->
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Products Table -->
    <table class="w-full border">
        
        <!-- Table Header -->
        <tr class="bg-gray-200">
            <th>ID</th>
            <th>Name</th>
            <th>Detail</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        <!-- Loop through all products -->
        @foreach($products as $product)
        <tr>
            <!-- Product ID -->
            <td>{{ $product->id }}</td>

            <!-- Product Name -->
            <td>{{ $product->name }}</td>

            <!-- Product Detail -->
            <td>{{ $product->detail }}</td>

            <!-- Product Status (active/inactive) -->
            <td>{{ $product->status }}</td>

            <!-- Action buttons -->
            <td>
                <!-- View product details -->
                <a href="{{ route('products.list',$product->id) }}" class="text-blue-600">
                    View
                </a>

                <!-- Edit product -->
                <a href="{{ route('products.edit',$product->id) }}" 
                   class="text-yellow-600 mx-2">
                    Edit
                </a>

                <!-- Delete product (POST request with CSRF protection) -->
                <form action="{{ route('products.delete',$product->id) }}" 
                      method="POST" 
                      style="display:inline;">
                    @csrf 
                    <button type="submit" class="text-red-600">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
        @endforeach

    </table>

</body>
</html>

```
create.blade.php – Add Product

Form to add new product

Validation errors display

```


<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Set character encoding -->
    <meta charset="UTF-8">

    <!-- Make page responsive on all screen sizes -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Page title -->
    <title>Add Product</title>

    <!-- Tailwind CSS CDN for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-8">

    <!-- Page Heading -->
    <h2 class="text-2xl font-bold mb-4">Add Product</h2>

    <!-- Display validation errors if any -->
    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-3 mb-4">
            <ul>
                <!-- Loop through all validation errors -->
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Product Create Form -->
    <form action="{{ route('products.add') }}" method="POST" class="space-y-4">
        <!-- CSRF token for security -->
        @csrf

        <!-- Product Name Input -->
        <div>
            <label>Name:</label>
            <input type="text" name="name" class="border p-2 w-full" required>
        </div>

        <!-- Product Detail Textarea -->
        <div>
            <label>Detail:</label>
            <textarea name="detail" class="border p-2 w-full"></textarea>
        </div>

        <!-- Product Status Dropdown -->
        <div>
            <label>Status:</label>
            <select name="status" class="border p-2 w-full">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 mt-3">
            Create
        </button>
    </form>

    <!-- Back to Products List -->
    <a href="{{ route('products.allLists') }}" class="inline-block mt-4 text-blue-600">
        Back to List
    </a>

</body>
</html>

```
edit.blade.php – Edit Product

Form to update existing product

Pre-fills current values
```
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Character encoding -->
    <meta charset="UTF-8">

    <!-- Responsive layout for mobile & desktop -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Page title -->
    <title>Edit Product</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-8">

    <!-- Page Heading -->
    <h2 class="text-2xl font-bold mb-4">Edit Product</h2>

    <!-- Display validation errors if any -->
    @if($errors->any())
    <div class="bg-red-100 text-red-700 p-3 mb-4">
        <ul>
            <!-- Loop through all errors -->
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Product Update Form -->
    <form action="{{ route('products.update',$product->id) }}" method="POST" class="space-y-4">
        <!-- CSRF protection -->
        @csrf

        <!-- Spoof PUT method for update -->
        @method('PUT')

        <!-- Product Name Field -->
        <div>
            <label>Name:</label>
            <input type="text" name="name" value="{{ $product->name }}" class="border p-2 w-full" required>
        </div>

        <!-- Product Detail Field -->
        <div>
            <label>Detail:</label>
            <textarea name="detail" class="border p-2 w-full">{{ $product->detail }}</textarea>
        </div>

        <!-- Product Status Dropdown -->
        <div>
            <label>Status:</label>
            <select name="status" class="border p-2 w-full">
                <!-- Active status -->
                <option value="active" {{ $product->status=='active'?'selected':'' }}>Active</option>

                <!-- Inactive status -->
                <option value="inactive" {{ $product->status=='inactive'?'selected':'' }}>Inactive</option>
            </select>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 mt-3">
            Update
        </button>
    </form>

    <!-- Back to Product List -->
    <a href="{{ route('products.allLists') }}" class="inline-block mt-4 text-blue-600">
        Back to List
    </a>

</body>
</html>

```
show.blade.php – Show Product Details

Displays full details: name, status, created_by, updated_by, timestamps
```

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Character encoding -->
    <meta charset="UTF-8">

    <!-- Responsive layout for all screen sizes -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Page title -->
    <title>Product Details</title>

    <!-- Tailwind CSS CDN for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-8">

    <!-- Page Heading -->
    <h2 class="text-2xl font-bold mb-4">Product Details</h2>

    <!-- Display Product Information -->
    <p><strong>ID:</strong> {{ $product->id }}</p>
    <p><strong>Name:</strong> {{ $product->name }}</p>
    <p><strong>Detail:</strong> {{ $product->detail }}</p>
    <p><strong>Status:</strong> {{ $product->status }}</p>

    <!-- Audit Fields -->
    <p><strong>Created By:</strong> {{ $product->created_by }}</p>
    <p><strong>Updated By:</strong> {{ $product->updated_by }}</p>
    <p><strong>Created At:</strong> {{ $product->created_at }}</p>
    <p><strong>Updated At:</strong> {{ $product->updated_at }}</p>

    <!-- Back to Products List -->
    <a href="{{ route('products.allLists') }}" class="inline-block mt-4 text-blue-600">
        Back to List
    </a>

</body>
</html>

```
10. API Testing (Postman)

1️ Get All Products
```
GET http://127.0.0.1:8000/api/products

```
2️ Get Single Product
```

GET http://127.0.0.1:8000/api/products/1

```
3️ Add Product
```
POST http://127.0.0.1:8000/api/products/add

json
{
  "name": "MacBook Pro",
  "detail": "16-inch, M1 Max",
  "status": "active",
  "created_by": 1
}

```
4️ Update Product
```
POST http://127.0.0.1:8000/api/products/update/1

json
{
  "name": "MacBook Pro 2025",
  "detail": "16-inch, M2 Max",
  "status": "active",
  "updated_by": 1
}

```
5️ Soft Delete Product
```

POST http://127.0.0.1:8000/api/products/delete/1

```
11. Example JSON Responses
    
Add Product Response

```
json
{
  "id": 1,
  "name": "MacBook Pro",
  "detail": "16-inch, M1 Max",
  "status": "active",
  "created_by": 1,
  "updated_by": null,
  "created_at": "2025-12-04T11:20:00.000000Z",
  "updated_at": "2025-12-04T11:20:00.000000Z",
  "deleted_at": null
}

Get All Products Response
json
[
  {
    "id": 1,
    "name": "MacBook Pro",
    "detail": "16-inch, M1 Max",
    "status": "active",
    "created_by": 1,
    "updated_by": null,
    "created_at": "2025-12-04T11:20:00.000000Z",
    "updated_at": "2025-12-04T11:20:00.000000Z",
    "deleted_at": null
  }
]
```
## Output:

### Get All Products

```
GET http://127.0.0.1:8000/api/products
```
<img width="1380" height="999" alt="Screenshot 2025-12-12 125402" src="https://github.com/user-attachments/assets/cd4389bb-83ce-4665-a5b6-33a326cc1918" />

### Get Single Product

```
GET http://127.0.0.1:8000/api/products/5
```
<img width="1386" height="1005" alt="Screenshot 2025-12-12 125426" src="https://github.com/user-attachments/assets/71821843-8269-4bc3-aefe-dc2d2ea12d1c" />

### Add Product
```
POST http://127.0.0.1:8000/api/products/add
```
<img width="1375" height="987" alt="Screenshot 2025-12-12 125137" src="https://github.com/user-attachments/assets/72a95d90-33bf-4400-aec6-604361d903f6" />

### Update Product

```
POST http://127.0.0.1:8000/api/products/update/5
```
<img width="1383" height="1048" alt="Screenshot 2025-12-12 125306" src="https://github.com/user-attachments/assets/2f09beb4-8e2d-41f1-9fd9-a0b063936130" />

### Soft Delete Product

```
POST http://127.0.0.1:8000/api/products/delete/5
```
<img width="1379" height="1001" alt="Screenshot 2025-12-12 125500" src="https://github.com/user-attachments/assets/6c55b736-663d-49eb-997d-9b3ac524661c" />


---

Your PHP_Laravel11_CRUD_Using_API documentation is now fully ready with proper structure, comments, bootstrap/app.php integration, Postman testing, and example JSON responses.
