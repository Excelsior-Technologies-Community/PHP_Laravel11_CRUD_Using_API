<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class ProductApiController extends Controller
{
    
    public function getAllProducts()
    {
        $products = Product::all(); // Fetch all non-deleted products
        return response()->json($products, 200);
    }

    
    public function getProduct($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json($product, 200);
    }

    public function addProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'detail' => 'nullable|string',
            'status' => 'nullable|in:active,inactive'
        ]);

        $product = Product::create([
            'name'       => $request->name,
            'detail'     => $request->detail,
            'status'     => $request->status ?? 'active',
            'created_by' => $request->created_by,
        ]);

        return response()->json($product, 201);
    }

    
    public function editProduct($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json($product, 200);
    }

    
    public function updateProduct(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $request->validate([
            'name'   => 'required|string|max:255',
            'detail' => 'nullable|string',
            'status' => 'nullable|in:active,inactive'
        ]);

        $product->update([
            'name'       => $request->name,
            'detail'     => $request->detail,
            'status'     => $request->status ?? $product->status,
            'updated_by' => $request->updated_by,
        ]);

        return response()->json($product, 200);
    }

    
    public function softDeleteProduct($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}
