@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<section class="cart-section py-5">
    <div class="container">
        <h1 class="mb-4">Shopping Cart</h1>
        
        {{-- Use Livewire component for reactive cart --}}
        <livewire:cart-page />
    </div>
</section>
@endsection
