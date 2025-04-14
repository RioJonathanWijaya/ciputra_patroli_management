<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .focus-primary:focus {
            border-color: #1C3A6B;
            box-shadow: 0 0 0 3px rgba(28, 58, 107, 0.2);
        }

        .btn-primary {
            background-color: #1C3A6B;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #142a4f;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-primary:active {
            transform: translateY(0);
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-50">
    <div class="w-full max-w-md mx-4">
        <form action="{{ route('login') }}" method="POST" class="bg-white p-8 rounded-xl shadow-lg border border-gray-100">
            @csrf

            <div class="flex flex-col items-center mb-8">
                <img src="{{ asset('images/ciputra_logo.png') }}" alt="ciputra logo" class="w-32 h-32 mb-4">
                <h1 class="text-2xl font-bold" style="color: #1C3A6B;">Ciputra Patroli</h1>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-medium mb-2" for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus-primary focus:outline-none transition-colors"
                    placeholder="your@email.com">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-medium mb-2" for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus-primary focus:outline-none transition-colors"
                    placeholder="••••••••">
            </div>

            <button
                type="submit"
                class="w-full btn-primary text-white font-semibold px-4 py-3 rounded-lg hover:shadow-md mb-4">
                Sign In
            </button>

            <div class="text-center">
                <p class="text-gray-600 text-sm">Need access?
                    <a href="#" class="font-medium hover:underline" style="color: #0D7C5D;">Contact administrator</a>
                </p>
            </div>
        </form>
    </div>

    @include('components.alert-modal')
</body>

</html>