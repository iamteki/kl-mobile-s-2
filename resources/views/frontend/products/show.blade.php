@extends('layouts.app')

@section('title', $product->name . ' - KL Mobile Equipment Rental')
@section('description', $product->meta_description ?? $product->short_description)

@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Equipment</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Product Detail Section -->
    <div class="container my-5">
        <div class="row">
            <!-- Product Images -->
            <div class="col-lg-6">
                @include('frontend.products.partials.gallery')
            </div>

            <!-- Product Info -->
            <div class="col-lg-6">
                @include('frontend.products.partials.info')
            </div>
        </div>

        <!-- Product Tabs -->
        @include('frontend.products.partials.tabs')

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            @include('frontend.products.partials.related')
        @endif
    </div>

    <!-- Quick View Modal (if needed) -->
    @include('frontend.products.partials.quick-view-modal')
@endsection

@push('styles')
    @include('frontend.products.partials.styles')
@endpush

@push('scripts')
    @include('frontend.products.partials.scripts')
@endpush