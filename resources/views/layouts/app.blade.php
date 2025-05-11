<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Laravel App')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <div class="layout-wrapper">
    @include('layouts.header', ['user' => Auth::user()])
        @include('layouts.toastr')
        <div class="layout-body">
            @include('layouts.sidebar')

            <main class="main-content">
                @yield('content')
            </main>
        </div>
        @include('layouts.footer')
    </div>
</body>
</html>
