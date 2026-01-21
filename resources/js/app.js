import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Toast Notification System
window.showToast = function(message, type = 'info') {
    const toast = document.createElement('div');
    const bgColors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        warning: 'bg-yellow-500',
        info: 'bg-blue-500'
    };
    
    toast.className = `fixed top-4 right-4 ${bgColors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
};

// Loading state helper
window.setLoading = function(element, isLoading) {
    if (isLoading) {
        element.disabled = true;
        element.classList.add('opacity-50', 'cursor-not-allowed');
        if (!element.querySelector('.spinner')) {
            const spinner = document.createElement('span');
            spinner.className = 'spinner ml-2';
            element.appendChild(spinner);
        }
    } else {
        element.disabled = false;
        element.classList.remove('opacity-50', 'cursor-not-allowed');
        const spinner = element.querySelector('.spinner');
        if (spinner) {
            spinner.remove();
        }
    }
};

// AJAX helper with loading and error handling
window.ajaxRequest = async function(url, options = {}) {
    const {
        method = 'GET',
        data = null,
        onSuccess = null,
        onError = null,
        loadingElement = null,
        showToastOnError = true
    } = options;
    
    try {
        if (loadingElement) {
            setLoading(loadingElement, true);
        }
        
        const config = {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json'
            }
        };
        
        if (data && method !== 'GET') {
            config.body = JSON.stringify(data);
        }
        
        const response = await fetch(url, config);
        const result = await response.json();
        
        if (!response.ok) {
            throw new Error(result.message || 'Có lỗi xảy ra');
        }
        
        if (onSuccess) {
            onSuccess(result);
        }
        
        return result;
    } catch (error) {
        if (showToastOnError) {
            showToast(error.message || 'Có lỗi xảy ra. Vui lòng thử lại.', 'error');
        }
        
        if (onError) {
            onError(error);
        }
        
        throw error;
    } finally {
        if (loadingElement) {
            setLoading(loadingElement, false);
        }
    }
};

// Update cart count in navigation
window.updateCartCount = function() {
    fetch('/cart/count')
        .then(response => response.json())
        .then(data => {
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                const count = data.count || 0;
                cartCountElement.textContent = count;
                // Chỉ hiển thị khi có sản phẩm trong giỏ
                if (count > 0) {
                    cartCountElement.style.display = 'flex';
                } else {
                    cartCountElement.style.display = 'none';
                }
            }
        })
        .catch(() => {});
};

// Initialize cart count on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
    
    // Show flash messages as toasts
    const flashMessages = document.querySelectorAll('.alert');
    flashMessages.forEach(alert => {
        const type = alert.classList.contains('alert-success') ? 'success' :
                    alert.classList.contains('alert-error') ? 'error' :
                    alert.classList.contains('alert-warning') ? 'warning' : 'info';
        const message = alert.textContent.trim();
        if (message) {
            showToast(message, type);
        }
    });
});
