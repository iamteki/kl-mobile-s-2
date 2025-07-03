<div class="package-booking-component">
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (!$showBookingForm)
        <!-- Quick Add Button -->
        <button wire:click="showForm" 
                class="btn btn-primary btn-lg w-100 mb-3">
            <i class="fas fa-shopping-cart me-2"></i>Book This Package
        </button>
    @else
        <!-- Booking Form -->
        <div class="booking-form-wrapper">
            <h4 class="form-title mb-3">Event Details</h4>
            
            <form wire:submit.prevent="addToCart">
                <!-- Event Date -->
                <div class="mb-3">
                    <label for="eventDate" class="form-label">Event Date <span class="text-danger">*</span></label>
                    <input type="date" 
                           wire:model.defer="eventDate" 
                           id="eventDate"
                           class="form-control @error('eventDate') is-invalid @enderror"
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                    @error('eventDate')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Event Type -->
                <div class="mb-3">
                    <label for="eventType" class="form-label">Event Type <span class="text-danger">*</span></label>
                    <select wire:model.defer="eventType" 
                            id="eventType"
                            class="form-select @error('eventType') is-invalid @enderror">
                        <option value="">Select Event Type</option>
                        <option value="Wedding">Wedding</option>
                        <option value="Birthday Party">Birthday Party</option>
                        <option value="Corporate Event">Corporate Event</option>
                        <option value="Concert">Concert</option>
                        <option value="Private Party">Private Party</option>
                        <option value="Festival">Festival</option>
                        <option value="Other">Other</option>
                    </select>
                    @error('eventType')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Venue Address -->
                <div class="mb-3">
                    <label for="venueAddress" class="form-label">Venue Address <span class="text-danger">*</span></label>
                    <textarea wire:model.defer="venueAddress" 
                              id="venueAddress"
                              class="form-control @error('venueAddress') is-invalid @enderror"
                              rows="2"
                              placeholder="Enter complete venue address"></textarea>
                    @error('venueAddress')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Number of Attendees -->
                <div class="mb-3">
                    <label for="attendees" class="form-label">Number of Attendees <span class="text-danger">*</span></label>
                    <input type="number" 
                           wire:model.defer="attendees" 
                           id="attendees"
                           class="form-control @error('attendees') is-invalid @enderror"
                           min="1"
                           placeholder="Expected number of guests">
                    @error('attendees')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Additional Notes -->
                <div class="mb-3">
                    <label for="notes" class="form-label">Additional Notes (Optional)</label>
                    <textarea wire:model.defer="notes" 
                              id="notes"
                              class="form-control"
                              rows="2"
                              placeholder="Any special requirements or notes"></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-2">
                    <button type="submit" 
                            class="btn btn-primary flex-fill"
                            wire:loading.attr="disabled"
                            wire:target="addToCart">
                        <span wire:loading.remove wire:target="addToCart">
                            <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                        </span>
                        <span wire:loading wire:target="addToCart">
                            <i class="fas fa-spinner fa-spin me-2"></i>Processing...
                        </span>
                    </button>
                    
                    <button type="button" 
                            wire:click="hideForm"
                            class="btn btn-outline-secondary"
                            wire:loading.attr="disabled"
                            wire:target="addToCart">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Quick Contact -->
    <div class="quick-contact mt-3 text-center">
        <p class="text-muted mb-2">Need help with booking?</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="tel:+94777123456" class="contact-link">
                <i class="fas fa-phone"></i> Call Us
            </a>
            <a href="https://wa.me/94777123456?text=Hi, I'm interested in the {{ urlencode($package->name) }} package" 
               class="contact-link">
                <i class="fab fa-whatsapp"></i> WhatsApp
            </a>
        </div>
    </div>
</div>

<style>
.booking-form-wrapper {
    background: var(--bg-darker);
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 20px;
}

.form-title {
    color: var(--text-light);
    font-weight: 600;
    font-size: 20px;
}

.form-label {
    color: var(--text-light);
    font-weight: 500;
    font-size: 14px;
    margin-bottom: 8px;
}

.form-control,
.form-select {
    background-color: var(--bg-card);
    border: 1px solid var(--border-dark);
    color: var(--text-light);
    padding: 12px 15px;
    border-radius: 10px;
    transition: all 0.3s;
}

.form-control:focus,
.form-select:focus {
    background-color: var(--bg-card);
    border-color: var(--primary-purple);
    color: var(--text-light);
    box-shadow: 0 0 0 0.2rem rgba(147, 51, 234, 0.25);
}

.form-control::placeholder {
    color: var(--text-muted);
}

.form-select option {
    background-color: var(--bg-card);
    color: var(--text-light);
}

.is-invalid {
    border-color: var(--danger-red);
}

.invalid-feedback {
    color: var(--danger-red);
    font-size: 13px;
    margin-top: 5px;
}

.quick-contact {
    padding-top: 20px;
    border-top: 1px solid var(--border-dark);
}

.contact-link {
    color: var(--primary-purple);
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s;
}

.contact-link:hover {
    color: var(--secondary-purple);
}

/* Loading state */
.btn[disabled] {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Responsive */
@media (max-width: 576px) {
    .booking-form-wrapper {
        padding: 20px;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
    }
    
    .d-flex.gap-2 .btn {
        width: 100%;
    }
}
</style>