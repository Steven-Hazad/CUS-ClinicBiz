<!DOCTYPE html>
<html>
<head>
    <title>MedSync</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-center font-bold text-blue-500">Welcome to MedSync</h1>
        <div x-data="{ open: false }">
            <button @click="open = !open" class="bg-blue-600 text-white px-4 py-2 rounded">
                Toggle
            </button>
            <p x-show="open" class="mt-2 text-green-600">Hello, Alpine.js!</p>
        </div>
    </div>
</body>
</html>