@extends('layouts.app')

@section('title', 'Shopping Cart - KL Mobile')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
@endsection

@section('content')
    <div class="container my-5">
        <h1 class="text-white mb-4">Shopping Cart</h1>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(count($cart['items']) > 0)
            @livewire('cart-page')
        @else
            <div class="empty-cart-section text-center py-5">
                <div class="empty-cart-content">
                    <i class="fas fa-shopping-cart fa-5x text-muted mb-4"></i>
                    <h3 class="text-white mb-3">Your cart is empty</h3>
                    <p class="text-muted mb-4">Looks like you haven't added any items to your cart yet.</p>
                    <a href="{{ route('categories.index') }}" class="btn btn-primary">
                        <i class="fas fa-box me-2"></i>Browse Equipment
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('styles')
<style>
.empty-cart-section {
    background: var(--bg-card);
    border-radius: 15px;
    padding: 80px 40px;
    border: 1px solid var(--border-dark);
}

.empty-cart-content {
    max-width: 500px;
    margin: 0 auto;
}
</style>
@endpush