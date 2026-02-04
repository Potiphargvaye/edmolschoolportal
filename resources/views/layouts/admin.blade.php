<!DOCTYPE html>
<html lang="en">
<head>
     <!-- Favicon for various devices -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" sizes="32x32">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @include('partials.admin.admin-head')
    @livewireStyles
</head>

<body class="text-gray-800 bg-[#F8FAFC] min-h-screen">

    {{-- Sidebar --}}
    @include('partials.admin.admin-sidebar')

    {{-- Mobile overlay --}}
    <div class="fixed top-0 left-0 w-full h-full bg-black/50 z-40 md:hidden sidebar-overlay hidden"></div>

    {{-- Main --}}
   <main class="w-full md:w-[calc(100%-256px)] md:ml-64 bg-white min-h-screen transition-all main">


        {{-- Navbar --}} 
        @include('partials.admin.admin-navbar')

        {{-- Page Content --}}
        <section class="p-6">
            @yield('content')
        </section>

    </main>

    {{-- Scripts --}}
    @include('partials.admin.admin-scripts')
 @livewireScripts
</body>
</html>



