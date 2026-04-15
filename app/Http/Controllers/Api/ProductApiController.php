<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ProductApiController extends Controller
{
    // 1. Get all products with search/filter/pagination
    public function getAllProducts(Request $request)
    {
        try {
            $query = Product::query();

            // Search by name or detail
            if ($request->has('search') && $request->search != '') {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('detail', 'like', '%' . $request->search . '%');
                });
            }

            // Filter by status
            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }

            // Sort by created_at
            if ($request->has('sort') && in_array($request->sort, ['asc', 'desc'])) {
                $query->orderBy('created_at', $request->sort);
            }

            // Pagination
            $products = $query->paginate(2);

            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
    }

    // 2. Get single product
    public function getProduct($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => true, 'message' => 'Product not found'], 404);
        }
        return response()->json($product);
    }

    // 3. Add new product
    public function addProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'detail' => 'nullable|string',
            'status' => 'required|string|max:50',
            'created_by' => 'nullable|integer',
            'updated_by' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => true, 'messages' => $validator->errors()], 422);
        }

        $product = Product::create($request->all());
        return response()->json(['success' => true, 'data' => $product], 201);
    }

    // 4. Fetch product for editing
    public function editProduct($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => true, 'message' => 'Product not found'], 404);
        }
        return response()->json($product);
    }

    // 5. Update product
    public function updateProduct(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => true, 'message' => 'Product not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'detail' => 'nullable|string',
            'status' => 'sometimes|required|string|max:50',
            'updated_by' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => true, 'messages' => $validator->errors()], 422);
        }

        $product->update($request->all());
        return response()->json(['success' => true, 'data' => $product]);
    }

    // 6. Soft delete product
    public function softDeleteProduct($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => true, 'message' => 'Product not found'], 404);
        }

        $product->delete();
        return response()->json(['success' => true, 'message' => 'Product deleted successfully']);
    }
}