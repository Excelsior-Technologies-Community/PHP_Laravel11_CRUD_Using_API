<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Product Details</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

<h2 class="text-2xl font-bold mb-4">Product Details</h2>

<p><strong>ID:</strong> {{ $product->id }}</p>
<p><strong>Name:</strong> {{ $product->name }}</p>
<p><strong>Detail:</strong> {{ $product->detail }}</p>
<p><strong>Status:</strong> {{ $product->status }}</p>
<p><strong>Created By:</strong> {{ $product->created_by }}</p>
<p><strong>Updated By:</strong> {{ $product->updated_by }}</p>
<p><strong>Created At:</strong> {{ $product->created_at }}</p>
<p><strong>Updated At:</strong> {{ $product->updated_at }}</p>

<a href="{{ route('products.allLists') }}" class="inline-block mt-4 text-blue-600">Back to List</a>

</body>
</html>
