<!-- Product Tabs -->
<div class="tabs-section">
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
            <div class="features-grid">
                @foreach($features as $feature)
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="{{ $feature['icon'] }}"></i>
                        </div>
                        <div class="feature-content">
                            <h6>{{ $feature['title'] }}</h6>
                            <p>{{ $feature['description'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- What's Included Tab -->
        <div class="tab-pane fade" id="included">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-3">Standard Package Includes:</h5>
                    <ul class="list-unstyled">
                        @foreach($included as $item)
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>{{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-md-6">
                    <h5 class="mb-3">Optional Add-ons:</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-plus text-primary me-2"></i> Wireless Microphone Set (2 mics)</li>
                        <li class="mb-2"><i class="fas fa-plus text-primary me-2"></i> Additional Wired Microphones</li>
                        <li class="mb-2"><i class="fas fa-plus text-primary me-2"></i> Professional Audio Technician</li>
                        <li class="mb-2"><i class="fas fa-plus text-primary me-2"></i> Extended Cable Sets</li>
                        <li class="mb-2"><i class="fas fa-plus text-primary me-2"></i> Early Morning Delivery</li>
                        <li class="mb-2"><i class="fas fa-plus text-primary me-2"></i> Late Night Pickup</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Requirements Tab -->
        <div class="tab-pane fade" id="requirements">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-3">Venue Requirements:</h5>
                    <ul class="list-unstyled">
                        @foreach($requirements['venue'] as $req)
                            <li class="mb-2">
                                <i class="fas fa-{{ strpos($req, 'Power') !== false ? 'bolt' : (strpos($req, 'Space') !== false ? 'ruler' : (strpos($req, 'Access') !== false ? 'door-open' : 'shield-alt')) }} text-warning me-2"></i>
                                {{ $req }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-md-6">
                    <h5 class="mb-3">Rental Terms:</h5>
                    <ul class="list-unstyled">
                        @foreach($requirements['rental'] as $term)
                            <li class="mb-2">
                                <i class="fas fa-{{ strpos($term, 'ID') !== false ? 'id-card' : (strpos($term, 'Setup') !== false ? 'clock' : (strpos($term, 'Delivery') !== false ? 'truck' : 'file-contract')) }} text-primary me-2"></i>
                                {{ $term }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="alert alert-info mt-4">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Note:</strong> For outdoor events, additional weather protection equipment may be required. 
                Our team will assess your venue and recommend suitable solutions.
            </div>
        </div>
    </div>
</div>