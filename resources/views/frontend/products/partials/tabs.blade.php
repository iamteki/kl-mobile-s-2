<!-- Product Tabs -->
<div class="tabs-section mt-5">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#specifications">Specifications</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#features">Features</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#included">What's Included</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#requirements">Requirements</a>
        </li>
    </ul>

    <div class="tab-content">
        <!-- Specifications Tab -->
        <div class="tab-pane fade show active" id="specifications">
            @include('frontend.products.partials.specifications')
        </div>

        <!-- Features Tab -->
        <div class="tab-pane fade" id="features">
            @if(is_array($features) && count($features) > 0)
                @if(isset($features[0]['icon']))
                    <!-- Features with icons and descriptions -->
                    <div class="features-grid">
                        @foreach($features as $feature)
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="{{ $feature['icon'] ?? 'fas fa-check' }}"></i>
                                </div>
                                <div class="feature-content">
                                    <h6>{{ $feature['title'] ?? 'Feature' }}</h6>
                                    <p>{{ $feature['description'] ?? '' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Simple feature list -->
                    <ul class="features-list">
                        @foreach($features as $feature)
                            <li>
                                <i class="fas fa-check-circle text-success me-2"></i>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            @else
                <p class="text-muted">No features listed.</p>
            @endif
        </div>

        <!-- What's Included Tab -->
        <div class="tab-pane fade" id="included">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-3">Standard Package Includes:</h5>
                    <ul class="list-unstyled">
                        @forelse($included as $item)
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>{{ $item }}
                            </li>
                        @empty
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>Equipment as described
                            </li>
                        @endforelse
                    </ul>
                </div>
                <div class="col-md-6">
                    <h5 class="mb-3">Optional Add-ons:</h5>
                    <ul class="list-unstyled">
                        @if(isset($product->addons) && is_array($product->addons))
                            @foreach($product->addons as $addon)
                                <li class="mb-2">
                                    <i class="fas fa-plus text-primary me-2"></i>
                                    {{ $addon['name'] ?? $addon }} 
                                    @if(isset($addon['price']))
                                        - LKR {{ number_format($addon['price']) }}
                                    @endif
                                </li>
                            @endforeach
                        @else
                            <li class="mb-2 text-muted">Contact us for available add-ons</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <!-- Requirements Tab -->
        <div class="tab-pane fade" id="requirements">
            @if(is_array($requirements) && count($requirements) > 0)
                @if(isset($requirements['venue']) || isset($requirements['rental']))
                    <!-- Grouped requirements -->
                    @foreach($requirements as $group => $items)
                        <h5 class="mb-3">{{ ucfirst($group) }} Requirements:</h5>
                        <ul class="requirements-list mb-4">
                            @foreach($items as $requirement)
                                <li>
                                    <i class="fas fa-info-circle text-warning me-2"></i>
                                    {{ $requirement }}
                                </li>
                            @endforeach
                        </ul>
                    @endforeach
                @else
                    <!-- Simple requirements list -->
                    <ul class="requirements-list">
                        @foreach($requirements as $requirement)
                            <li>
                                <i class="fas fa-info-circle text-warning me-2"></i>
                                {{ $requirement }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            @else
                <p class="text-muted">No specific requirements listed.</p>
            @endif
        </div>
    </div>
</div>