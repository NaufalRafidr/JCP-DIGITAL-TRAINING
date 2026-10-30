<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login Admin')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: Inter, system-ui, -apple-system, sans-serif; }</style>
</head>
<body class="min-h-screen bg-brand-ivory text-[#10243A] antialiased">
    <main class="min-h-screen flex items-center justify-center px-4 py-12">
        @yield('content')
    </main>
</body>
</html>
