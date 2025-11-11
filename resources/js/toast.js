/**
 * Toast Notification System for VeCarCMS
 * Modern, animated toast notifications
 */

class ToastNotification {
    constructor() {
        this.container = null;
        this.init();
    }

    init() {
        // Create toast container if it doesn't exist
        if (!document.getElementById('toast-container')) {
            this.container = document.createElement('div');
            this.container.id = 'toast-container';
            this.container.className = 'fixed top-4 right-4 z-[9999] space-y-3';
            document.body.appendChild(this.container);
        } else {
            this.container = document.getElementById('toast-container');
        }
    }

    show(message, type = 'success', duration = 3000) {
        const toast = this.createToast(message, type);
        this.container.appendChild(toast);

        // Animate in
        setTimeout(() => {
            toast.classList.add('toast-show');
        }, 10);

        // Auto remove
        if (duration > 0) {
            setTimeout(() => {
                this.remove(toast);
            }, duration);
        }

        return toast;
    }

    createToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type} transform translate-x-full transition-all duration-300 ease-out`;

        const icons = {
            success: '<i class="fas fa-check-circle text-white text-xl"></i>',
            error: '<i class="fas fa-times-circle text-white text-xl"></i>',
            warning: '<i class="fas fa-exclamation-triangle text-white text-xl"></i>',
            info: '<i class="fas fa-info-circle text-white text-xl"></i>',
        };

        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            warning: 'bg-yellow-500',
            info: 'bg-blue-500',
        };

        toast.innerHTML = `
            <div class="${colors[type]} text-white px-6 py-4 rounded-lg shadow-xl flex items-center gap-3 min-w-[300px] max-w-md">
                ${icons[type]}
                <span class="flex-1">${message}</span>
                <button onclick="window.toast.remove(this.closest('.toast'))" class="text-white hover:text-gray-200 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        return toast;
    }

    remove(toast) {
        toast.classList.remove('toast-show');
        toast.classList.add('translate-x-full', 'opacity-0');
        
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }

    success(message, duration = 3000) {
        return this.show(message, 'success', duration);
    }

    error(message, duration = 4000) {
        return this.show(message, 'error', duration);
    }

    warning(message, duration = 3000) {
        return this.show(message, 'warning', duration);
    }

    info(message, duration = 3000) {
        return this.show(message, 'info', duration);
    }
}

// Initialize global toast instance
window.toast = new ToastNotification();

// Add CSS
const style = document.createElement('style');
style.textContent = `
.toast-show {
    transform: translateX(0) !important;
    opacity: 1 !important;
}

.toast {
    opacity: 0;
}
`;
document.head.appendChild(style);

// Export for use in modules
export default ToastNotification;

