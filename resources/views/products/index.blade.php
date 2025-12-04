<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Products List</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

<h2 class="text-2xl font-bold mb-4">Products List</h2>

<a href="{{ route('products.add') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Add Product</a>

@if(session('success'))
<div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
@endif

<table class="w-full border">
    <tr class="bg-gray-200">
        <th>ID</th><th>Name</th><th>Detail</th><th>Status</th><th>Actions</th>
    </tr>
    @foreach($products as $product)
    <tr>
        <td>{{ $product->id }}</td>
        <td>{{ $product->name }}</td>
        <td>{{ $product->detail }}</td>
        <td>{{ $product->status }}</td>
        <td>
            <a href="{{ route('products.list',$product->id) }}" class="text-blue-600">View</a>
            <a href="{{ route('products.edit',$product->id) }}" class="text-yellow-600 mx-2">Edit</a>
            <form action="{{ route('products.delete',$product->id) }}" method="POST" style="display:inline;">
                @csrf 
                <button type="submit" class="text-red-600">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

</body>
</html>
