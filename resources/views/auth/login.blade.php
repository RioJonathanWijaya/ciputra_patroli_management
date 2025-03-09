<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <form action="{{ route('login') }}" method="POST" class="bg-white p-8 rounded-lg shadow-md w-96">
        @csrf
        <h2 class="text-2xl font-bold mb-4">Login</h2>

        <div>
            <label>Email</label>
            <input type="email" name="email" required class="w-full border p-2 rounded-md">
        </div>

        <div class="mt-4">
            <label>Password</label>
            <input type="password" name="password" required class="w-full border p-2 rounded-md">
        </div>

        <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Login</button>
    </form>
</body>
</html>
