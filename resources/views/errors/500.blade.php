<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Server Error</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100 dark:bg-gray-900">
        <div class="max-w-3xl w-full mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-red-600 mb-6">Server Error</h1>
                <p class="text-xl text-gray-700 dark:text-gray-300 mb-8">Sorry, something went wrong on our end.</p>
                
                @if(app()->environment('local') && isset($message))
                    <div class="bg-red-100 border border-red-400 text-red-700 p-4 rounded-lg mb-6 text-left overflow-auto">
                        <h2 class="text-lg font-semibold mb-2">Error Details:</h2>
                        <code class="text-sm">{{ $message }}</code>
                    </div>
                @endif
                
                <div class="space-y-4">
                    <p>Let's try to fix this:</p>
                    <div class="space-x-4">
                        <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md">
                            Go Back
                        </a>
                        <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                            Go Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 