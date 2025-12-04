🎯 Project: laravel11-api-crud

✅ Overview

This project demonstrates a Products API CRUD using Laravel 11, providing endpoints to:

Create Product → /api/products/add

List All Products → /api/products

Get Single Product → /api/products/{id}

Edit Product → /api/products/edit/{id}

Update Product → /api/products/update/{id}

Soft Delete Product → /api/products/delete/{id}

Features:

Laravel 11 new routing structure (bootstrap/app.php)

Soft deletes using Eloquent

JSON responses for all endpoints

Validation for name, status, and optional detail

🛠️ Project Setup & Configuration
🔧 Step 1: Create a New Laravel 11 Project
composer create-project laravel/laravel:^11.0 laravel11-api-crud
cd laravel11-api-crud
cp .env.example .env
php artisan key:generate

🗄 Step 2: Configure Database

Open .env and update:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel11_api
DB_USERNAME=root
DB_PASSWORD=

# Charset & Collation for emojis / unicode
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci


Then create the database laravel11_api in MySQL.

🧱 Step 3: Run Migrations
php artisan migrate

🧾 Step 4: Create Model
php artisan make:model Product -m


(Add fields: name, detail, status, created_by, updated_by, timestamps, soft deletes.)

🧱 Step 5: Create API Controller
php artisan make:controller Api/ProductApiController


This controller will handle all API CRUD operations.

🛣 Step 6: Define API Routes

File: routes/api.php
Endpoints: /products, /products/{id}, /products/add, /products/edit/{id}, /products/update/{id}, /products/delete/{id}

🧪 Step 7: Start Server
php artisan serve


Open in browser or Postman: http://localhost:8000

🧑‍💻 Application Code Structure
app/
  └── Models/
       └── Product.php               # Product model
  └── Http/
       └── Controllers/
            └── Api/
                 └── ProductApiController.php  # API CRUD controller
database/
  └── migrations/
       └── create_products_table.php          # Products table migration
routes/
  └── api.php                                 # API routes
bootstrap/
  └── app.php                                 # Laravel 11 routing config
.env                                           # Environment configuration

📌 8. API Endpoints Overview
Action	Endpoint
Get All Products	/api/products
Get Single	/api/products/{id}
Add Product	/api/products/add
Edit Product	/api/products/edit/{id}
Update Product	/api/products/update/{id}
Soft Delete	/api/products/delete/{id}

All responses are returned in JSON format.

🧪 9. API Testing (Postman)

Get All Products → GET /api/products

Get Single Product → GET /api/products/{id}

Add Product → POST /api/products/add (JSON body: name, detail, status, created_by)

Update Product → POST /api/products/update/{id} (JSON body: name, detail, status, updated_by)

Soft Delete Product → POST /api/products/delete/{id}

🎉 Project Complete
Your laravel11-api-crud project is fully ready with API endpoints, database integration, soft deletes, and Postman testing.
