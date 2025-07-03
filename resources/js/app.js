/**
 * KL Mobile Events - Main Application JavaScript
 */

// Import Bootstrap JavaScript (if using npm instead of CDN)
// import * as bootstrap from 'bootstrap';
// window.bootstrap = bootstrap;

// Import our bootstrap file
import './bootstrap';

// Import Alpine.js for Livewire
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Global KLMobile namespace
window.KLMobile = {
    // Initialize all modules
    init() {
        this.initializeTooltips();
        this.initializePopovers();
        this.initializeSmoothScroll();
        this.initializeBackToTop();
        this.initializeLazyLoading();
        this.initializeFormValidation();
    },

    // Format currency
    formatCurrency(amount) {
        return 'LKR ' + new Intl.NumberFormat('en-US').format(amount);
    },

    // Show loading state
    showLoading(element) {
        element.classList.add('loading');
        element.disabled = true;
    },

    // Hide loading state
    hideLoading(element) {
        element.classList.remove('loading');
        element.disabled = false;
    },

    // Show toast notification
    showToast(message, type = 'success') {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
            </div>
        `;
        
        // Add to body
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => toast.classList.add('show'), 10);
        
        // Remove after 3 seconds
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    },

    // Initialize Bootstrap tooltips
    initializeTooltips() {
        // Check if bootstrap is available (from CDN or npm)
        if (typeof bootstrap !== 'undefined') {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        }
    },

    // Initialize Bootstrap popovers
    initializePopovers() {
        // Check if bootstrap is available (from CDN or npm)
        if (typeof bootstrap !== 'undefined') {
            const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl));
        }
    },

    // Smooth scroll for anchor links
    initializeSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#' && href !== '#0') {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        });
    },

    // Back to top button
    initializeBackToTop() {
        const backToTop = document.createElement('button');
        backToTop.className = 'back-to-top';
        backToTop.innerHTML = '<i class="fas fa-arrow-up"></i>';
        document.body.appendChild(backToTop);

        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        });

        backToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    },

    // Lazy loading for images
    initializeLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.add('loaded');
                        observer.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    },

    // Form validation helpers
    initializeFormValidation() {
        // Add custom validation for date inputs
        document.querySelectorAll('input[type="date"]').forEach(input => {
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            input.min = tomorrow.toISOString().split('T')[0];
        });

        // Phone number formatting
        document.querySelectorAll('input[type="tel"]').forEach(input => {
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 0) {
                    if (value.length <= 3) {
                        value = value;
                    } else if (value.length <= 7) {
                        value = value.slice(0, 3) + '-' + value.slice(3);
                    } else {
                        value = value.slice(0, 3) + '-' + value.slice(3, 7) + '-' + value.slice(7, 11);
                    }
                }
                e.target.value = value;
            });
        });
    },

    // AJAX request helper
    async request(url, options = {}) {
        const defaultOptions = {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        };

        try {
            const response = await fetch(url, { ...defaultOptions, ...options });
            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.message || 'Request failed');
            }
            
            return data;
        } catch (error) {
            console.error('Request error:', error);
            throw error;
        }
    }
};

// Toast notification styles
const style = document.createElement('style');
style.textContent = `
.toast-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: var(--bg-card);
    border: 1px solid var(--border-dark);
    border-radius: 8px;
    padding: 15px 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    z-index: 9999;
    opacity: 0;
    transform: translateX(100%);
    transition: all 0.3s ease;
}

.toast-notification.show {
    opacity: 1;
    transform: translateX(0);
}

.toast-content {
    display: flex;
    align-items: center;
    gap: 10px;
}

.toast-success {
    border-color: var(--success-green);
}

.toast-success i {
    color: var(--success-green);
}

.toast-error {
    border-color: var(--danger-red);
}

.toast-error i {
    color: var(--danger-red);
}

.back-to-top {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 50px;
    height: 50px;
    background: var(--primary-purple);
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 1000;
}

.back-to-top.show {
    opacity: 1;
    visibility: visible;
}

.back-to-top:hover {
    background: var(--accent-violet);
    transform: translateY(-5px);
}
`;
document.head.appendChild(style);

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    KLMobile.init();
});

// Cart update listener
document.addEventListener('livewire:initialized', () => {
    // Listen for cart updates
    Livewire.on('cartUpdated', () => {
        console.log('Cart updated');
    });
    
    // Auto-close cart dropdown
    Livewire.on('item-added-to-cart', () => {
        console.log('Item added to cart');
    });
});

// Handle Livewire events
document.addEventListener('livewire:load', () => {
    Livewire.on('showToast', (message, type) => {
        KLMobile.showToast(message, type);
    });

    Livewire.hook('message.sent', (message, component) => {
        // Show loading state
    });

    Livewire.hook('message.received', (message, component) => {
        // Hide loading state
    });

    Livewire.hook('message.failed', (message, component) => {
        KLMobile.showToast('Something went wrong. Please try again.', 'error');
    });
});