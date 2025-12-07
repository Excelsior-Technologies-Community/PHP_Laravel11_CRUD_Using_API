# 🚀 Laravel 11 – Products API CRUD (Full Documentation)

## 📌 1. Project Overview

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

## 📌 2. Installation Commands
# Create Laravel 11 project
```bash

composer create-project laravel/laravel:^11.0 laravel11-api-crud

cd laravel11-api-crud

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
Update .env database settings:

env
Copy code
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel11_api
DB_USERNAME=root
DB_PASSWORD=
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci
Run migrations:

bash
Copy code
php artisan migrate
Start server:

bash
Copy code
php artisan serve
📌 3. Folder Structure
pgsql
Copy code
laravel11-api-crud/
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
📌 4. bootstrap/app.php (Laravel 11 Routing Configuration)
php
Copy code
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
📌 5. Migration – Products Table
php
Copy code
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('detail')->nullable();
            $table->enum('status', ['active','inactive'])->default('active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
📌 6. Product Model
php
Copy code
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'detail',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $dates = ['deleted_at'];
}
📌 7. API Controller
File: app/Http/Controllers/Api/ProductApiController.php

(Code exactly same as provided by you)

📌 8. API Routes
php
Copy code
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
📌 9. Blade Views (Tailwind CSS)
index.blade.php – List Products

create.blade.php – Add Product

edit.blade.php – Edit Product

show.blade.php – Show Product Details

✅ Code exactly same as provided

📌 10. API Testing (Postman)
1️⃣ Get All Products
nginx
Copy code
GET http://127.0.0.1:8000/api/products
2️⃣ Get Single Product
ruby
Copy code
GET http://127.0.0.1:8000/api/products/1
3️⃣ Add Product
ruby
Copy code
POST http://127.0.0.1:8000/api/products/add
json
Copy code
{
  "name": "MacBook Pro",
  "detail": "16-inch, M1 Max",
  "status": "active",
  "created_by": 1
}
4️⃣ Update Product
nginx
Copy code
POST http://127.0.0.1:8000/api/products/update/1
json
Copy code
{
  "name": "MacBook Pro 2025",
  "detail": "16-inch, M2 Max",
  "status": "active",
  "updated_by": 1
}
5️⃣ Soft Delete Product
perl
Copy code
POST http://127.0.0.1:8000/api/products/delete/1
📌 11. Example JSON Responses
Add Product Response
json
Copy code
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
Copy code
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
