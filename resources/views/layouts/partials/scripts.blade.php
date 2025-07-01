<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Alpine.js for Livewire -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Global JavaScript -->
<script>
    // CSRF Token for AJAX requests
    window.axios = window.axios || {};
    window.axios.defaults = window.axios.defaults || {};
    window.axios.defaults.headers = window.axios.defaults.headers || {};
    window.axios.defaults.headers.common = window.axios.defaults.headers.common || {};
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Global utility functions
    window.KLMobile = {
        // Format currency
        formatCurrency: function(amount) {
            return 'LKR ' + new Intl.NumberFormat('en-US').format(amount);
        },
        
        // Show loading state
        showLoading: function(element) {
            element.classList.add('loading');
            element.disabled = true;
        },
        
        // Hide loading state
        hideLoading: function(element) {
            element.classList.remove('loading');
            element.disabled = false;
        },
        
        // Show toast notification
        showToast: function(message, type = 'success') {
            // Implementation for toast notifications
            console.log(type + ': ' + message);
        },
        
        // Smooth scroll to element
        scrollTo: function(element) {
            element.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    };

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        // Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Initialize popovers
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl)
        });
    });

    // Handle Livewire loading states
    document.addEventListener('livewire:load', function () {
        Livewire.hook('message.sent', (message, component) => {
            // Show loading state
        });

        Livewire.hook('message.received', (message, component) => {
            // Hide loading state
        });

        Livewire.hook('message.failed', (message, component) => {
            // Handle errors
            KLMobile.showToast('Something went wrong. Please try again.', 'error');
        });
    });
</script>