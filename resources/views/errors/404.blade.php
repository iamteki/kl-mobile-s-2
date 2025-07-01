@extends('layouts.app')

@section('title', 'Page Not Found - KL Mobile')

@section('content')
<div class="container my-5">
    <div class="error-page text-center py-5">
        <i class="fas fa-exclamation-triangle fa-5x text-warning mb-4"></i>
        <h1 class="display-1 fw-bold text-white">404</h1>
        <h2 class="text-white mb-4">Page Not Found</h2>
        <p class="text-muted mb-5">Sorry, the page you are looking for doesn't exist or has been moved.</p>
        <a href="{{ route('home') }}" class="btn btn-primary">
            <i class="fas fa-home me-2"></i>Back to Home
        </a>
    </div>
</div>

<style>
.error-page {
    min-height: 60vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}
</style>
@endsection