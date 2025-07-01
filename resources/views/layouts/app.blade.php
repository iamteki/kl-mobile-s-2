<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'KL Mobile - Event Equipment Rental & DJ Services')</title>
    <meta name="description" content="@yield('description', 'Rent professional event equipment, sound systems, lighting, and book DJ services. Instant booking with real-time availability in Kuala Lumpur.')">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    @include('layouts.partials.styles')
    
    <!-- Page Specific CSS -->
    @stack('styles')
    
    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body>
    <!-- Header -->
    @include('layouts.partials.header')
    
    <!-- Navigation -->
    @include('layouts.partials.navigation')
    
    <!-- Breadcrumb -->
    @hasSection('breadcrumb')
        <div class="breadcrumb-section">
            <div class="container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        @yield('breadcrumb')
                    </ol>
                </nav>
            </div>
        </div>
    @endif
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('layouts.partials.footer')
    
    <!-- Scripts -->
    @include('layouts.partials.scripts')
    
    <!-- Page Specific Scripts -->
    @stack('scripts')
    
    <!-- Livewire Scripts -->
    @livewireScripts
</body>
</html>