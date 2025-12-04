<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Product</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

<h2 class="text-2xl font-bold mb-4">Add Product</h2>

@if($errors->any())
<div class="bg-red-100 text-red-700 p-3 mb-4">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('products.add') }}" method="POST" class="space-y-4">
    @csrf
    <div>
        <label>Name:</label>
        <input type="text" name="name" class="border p-2 w-full" required>
    </div>
    <div>
        <label>Detail:</label>
        <textarea name="detail" class="border p-2 w-full"></textarea>
    </div>
    <div>
        <label>Status:</label>
        <select name="status" class="border p-2 w-full">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 mt-3">Create</button>
</form>

<a href="{{ route('products.allLists') }}" class="inline-block mt-4 text-blue-600">Back to List</a>

</body>
</html>
