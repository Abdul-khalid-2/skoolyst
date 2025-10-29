// public/js/contact-form.js
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const contactModal = document.getElementById('contactInquiryForm');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            
            // Get form data
            const formData = new FormData(this);
            
            // Get CSRF token - with fallback
            const csrfToken = getCsrfToken();
            
            // Prepare headers
            const headers = {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            };
            
            // Add CSRF token if available
            if (csrfToken) {
                headers['X-CSRF-TOKEN'] = csrfToken;
            }
            
            // Submit via AJAX
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: headers
            })
            .then(response => {
                if (response.status === 401) {
                    // Unauthorized - show login modal
                    return response.json().then(data => {
                        throw new Error('LOGIN_REQUIRED');
                    });
                }
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Show success message
                    showAlert('success', data.message);
                    contactForm.reset();
                    
                    // Close modal if open
                    if (contactModal) {
                        contactModal.style.display = 'none';
                    }
                } else {
                    showAlert('error', data.message || 'Something went wrong');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                if (error.message === 'LOGIN_REQUIRED') {
                    // Show login modal
                    if (contactModal) {
                        contactModal.style.display = 'block';
                    } else {
                        showAlert('error', 'Please login to submit an inquiry.');
                    }
                } else {
                    showAlert('error', 'An error occurred. Please try again.');
                }
            })
            .finally(() => {
                // Reset button state
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
    
    // Close modal functionality
    if (contactModal) {
        const closeBtn = contactModal.querySelector('.close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                contactModal.style.display = 'none';
            });
        }
        
        // Close when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === contactModal) {
                contactModal.style.display = 'none';
            }
        });
    }
    
    function getCsrfToken() {
        // Try multiple ways to get CSRF token
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        if (metaTag) {
            return metaTag.getAttribute('content');
        }
        
        // Fallback: check for token in form input
        const formToken = document.querySelector('input[name="_token"]');
        if (formToken) {
            return formToken.value;
        }
        
        return null;
    }
    
    function showAlert(type, message) {
        // Remove existing alerts
        const existingAlert = document.querySelector('.form-alert');
        if (existingAlert) {
            existingAlert.remove();
        }
        
        // Create new alert
        const alert = document.createElement('div');
        alert.className = `form-alert alert-${type}`;
        alert.innerHTML = `
            <div class="alert-content">
                <span class="alert-message">${message}</span>
                <button class="alert-close" onclick="this.parentElement.parentElement.remove()">×</button>
            </div>
        `;
        
        // Add styles
        alert.style.cssText = `
            padding: 12px 16px;
            margin: 16px 0;
            border-radius: 4px;
            background: ${type === 'success' ? '#d4edda' : '#f8d7da'};
            color: ${type === 'success' ? '#155724' : '#721c24'};
            border: 1px solid ${type === 'success' ? '#c3e6cb' : '#f5c6cb'};
        `;
        
        // Insert before form
        const contactForm = document.getElementById('contactForm');
        if (contactForm) {
            contactForm.parentNode.insertBefore(alert, contactForm);
        }
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 5000);
    }
});