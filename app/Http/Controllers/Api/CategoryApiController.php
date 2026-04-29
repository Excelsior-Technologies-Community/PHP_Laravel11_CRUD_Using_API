<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryApiController extends Controller
{
    // 1. Get all categories with search/filter/pagination
    public function getAllCategories(Request $request)
    {
        try {
            $query = Category::query();

            // Search by name
            if ($request->has('search') && $request->search != '') {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            // Filter by status (active/inactive)
            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }

            // Sort by created_at
            if ($request->has('sort') && in_array($request->sort, ['asc', 'desc'])) {
                $query->orderBy('created_at', $request->sort);
            } else {
                $query->orderBy('id', 'ASC');
            }

            // Pagination (2 per page as per your product logic)
            $categories = $query->paginate(2);

            return response()->json($categories);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
    }

    // 2. Get single category
    public function getCategory($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['error' => true, 'message' => 'Category not found'], 404);
        }
        return response()->json($category);
    }

    // 3. Add new category
    public function addCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => true, 'messages' => $validator->errors()], 422);
        }

        $category = Category::create($request->all());
        return response()->json(['success' => true, 'data' => $category], 201);
    }

    // 4. Fetch category for editing
    public function editCategory($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['error' => true, 'message' => 'Category not found'], 404);
        }
        return response()->json($category);
    }

    // 5. Update category
    public function updateCategory(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['error' => true, 'message' => 'Category not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => true, 'messages' => $validator->errors()], 422);
        }

        $category->update($request->all());
        return response()->json(['success' => true, 'data' => $category]);
    }

    // 6. Soft delete category
    public function softDeleteCategory($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['error' => true, 'message' => 'Category not found'], 404);
        }

        $category->delete();
        return response()->json(['success' => true, 'message' => 'Category deleted successfully']);
    }
}