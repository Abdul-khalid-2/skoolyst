<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>E-Commerce Admin Dashboard</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/app.css') }}">
    @stack('css')
</head>

<body>


    @include('layouts.navigation')

    <!-- Page Heading -->
    @isset($header)
    <header class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            {{ $header }}
        </div>
    </header>
    @endisset

    <!-- Page Content -->

    {{ $slot }}


    <!-- Footer -->
    <footer class="footer mt-auto">
        <div class="d-flex justify-content-between align-items-center">
            <p class="text-muted mb-0">&copy; 2025 E-Commerce Admin. All rights reserved.</p>
            <div class="d-flex gap-3">
                <a href="#" class="text-muted text-decoration-none">Privacy Policy</a>
                <a href="#" class="text-muted text-decoration-none">Terms of Service</a>
                <a href="#" class="text-muted text-decoration-none">Support</a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <script src="{{ asset('assets/dashboard/js/app.js') }}"></script>

    <script>
        // public/js/notifications.js
        document.addEventListener('DOMContentLoaded', function() {
            const notificationBadge = document.getElementById('notificationBadge');
            const contactNotificationsDropdown = document.getElementById('contactNotificationsDropdown');
            
            // Function to update notification count
            function updateNotificationCount() {
                fetch('/admin/inquiries/notification-count')
                    .then(response => response.json())
                    .then(data => {
                        if (data.count > 0) {
                            if (notificationBadge) {
                                notificationBadge.textContent = data.count;
                            } else {
                                // Create badge if it doesn't exist
                                const badge = document.createElement('span');
                                badge.id = 'notificationBadge';
                                badge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
                                badge.style.fontSize = '0.6rem';
                                badge.textContent = data.count;
                                
                                const bellIcon = document.querySelector('#contactNotifications i');
                                bellIcon.parentNode.appendChild(badge);
                            }
                        } else {
                            // Remove badge if no notifications
                            if (notificationBadge) {
                                notificationBadge.remove();
                            }
                        }
                    })
                    .catch(error => console.error('Error updating notifications:', error));
            }
            
            // Update notification count every 30 seconds
            setInterval(updateNotificationCount, 30000);
            
            // Update when dropdown is shown
            const contactNotifications = document.getElementById('contactNotifications');
            if (contactNotifications) {
                contactNotifications.addEventListener('show.bs.dropdown', function() {
                    updateNotificationCount();
                });
            }
            
            // Mark as read when clicking on notification
            if (contactNotificationsDropdown) {
                contactNotificationsDropdown.addEventListener('click', function(e) {
                    if (e.target.closest('.notification-item')) {
                        const inquiryId = e.target.closest('.notification-item').dataset.id;
                        if (inquiryId) {
                            markAsRead(inquiryId);
                        }
                    }
                });
            }
            
            function markAsRead(inquiryId) {
                fetch(`/admin/inquiries/${inquiryId}/mark-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateNotificationCount();
                    }
                });
            }
        });
    </script>
    @stack('js')
</body>

</html>