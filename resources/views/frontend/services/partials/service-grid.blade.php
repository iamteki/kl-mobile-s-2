<!-- Services Content -->
<div class="tab-content" id="serviceTabContent">
    <!-- All Services Tab -->
    <div class="tab-pane fade show active" id="all" role="tabpanel">
        <div class="row g-4">
            @foreach($services as $service)
                <div class="col-lg-4 col-md-6">
                    <x-service-card :service="$service" />
                </div>
            @endforeach
        </div>
    </div>
</div>