<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 h-screen flex items-center justify-center">

    <div class="max-w-md w-full p-6 bg-white rounded-md shadow-md">

        <h2 class="text-2xl font-semibold text-center mb-6">Admin Registration</h2>

        <form method="POST" action="{{ route('admin.register') }}">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" class="border rounded-md py-2 px-3 w-full focus:outline-none focus:border-blue-500" required autofocus>
                @error('name')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" class="border rounded-md py-2 px-3 w-full focus:outline-none focus:border-blue-500" required>
                @error('email')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input id="password" type="password" name="password" class="border rounded-md py-2 px-3 w-full focus:outline-none focus:border-blue-500" required autocomplete="new-password">
                @error('password')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="border rounded-md py-2 px-3 w-full focus:outline-none focus:border-blue-500" required>
            </div>

            <div class="mb-4">
                <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone Number</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" class="border rounded-md py-2 px-3 w-full focus:outline-none focus:border-blue-500" required>
                @error('phone')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:shadow-outline-blue active:bg-blue-800">Register</button>
            </div>
        </form>

    </div>

</body>

</html>