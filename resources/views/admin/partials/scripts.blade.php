<!-- Core JS -->

<!-- build:js assets/vendor/js/core.js -->
<script src="{{ asset('dashboard/assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('dashboard/assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('dashboard/assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('dashboard/assets/vendor/libs/node-waves/node-waves.js') }}"></script>
<script src="{{ asset('dashboard/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('dashboard/assets/vendor/libs/hammer/hammer.js') }}"></script>
<script src="{{ asset('dashboard/assets/vendor/libs/i18n/i18n.js') }}"></script>
<script src="{{ asset('dashboard/assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
<script src="{{ asset('dashboard/assets/vendor/js/menu.js') }}"></script>

<!-- endbuild -->

<!-- Vendors JS -->
<script src="{{ asset('dashboard/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script src="{{ asset('dashboard/assets/vendor/libs/swiper/swiper.js') }}"></script>
<script src="{{ asset('dashboard/assets/vendor/libs/toastr/toastr.js') }}"></script>

<!-- Main JS -->
<script src="{{ asset('dashboard/assets/js/main.js') }}"></script>

<!-- Page JS -->
<script src="{{ asset('dashboard/assets/js/dashboards-analytics.js') }}"></script>

{{-- axios cdn --}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

{{-- select2 cdn --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
    integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

{{-- Sortable.js for drag and drop --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script src="{{ asset('dashboard/assets/vendor/libs/bs-stepper/bs-stepper.js') }}"></script>
<script src="{{ asset('dashboard/assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
<script src="{{ asset('dashboard/assets/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('dashboard/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('dashboard/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('dashboard/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>

<script src="{{ asset('dashboard/assets/js/form-wizard-numbered.js') }}"></script>
<script src="{{ asset('dashboard/assets/js/form-wizard-validation.js') }}"></script>

<script src="https://cdn.ckeditor.com/ckeditor5/38.1.0/classic/ckeditor.js"></script>

{{-- Global Loading State Management --}}
<script>
    // Global loading overlay management for better UX
    (function() {
        const loadingOverlay = document.getElementById('global-loading-overlay');

        // Show loading overlay
        window.showLoading = function() {
            if (loadingOverlay) {
                loadingOverlay.classList.remove('d-none');
                loadingOverlay.setAttribute('aria-busy', 'true');
            }
        };

        // Hide loading overlay
        window.hideLoading = function() {
            if (loadingOverlay) {
                loadingOverlay.classList.add('d-none');
                loadingOverlay.setAttribute('aria-busy', 'false');
            }
        };

        // Intercept form submissions to show loading state
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.tagName === 'FORM' && !form.dataset.noLoading) {
                showLoading();
            }
        });

        // Intercept AJAX requests
        if (typeof axios !== 'undefined') {
            // Request interceptor
            axios.interceptors.request.use(function(config) {
                if (!config.headers['X-No-Loading']) {
                    showLoading();
                }
                return config;
            }, function(error) {
                hideLoading();
                return Promise.reject(error);
            });

            // Response interceptor
            axios.interceptors.response.use(function(response) {
                hideLoading();
                return response;
            }, function(error) {
                hideLoading();
                return Promise.reject(error);
            });
        }

        // Hide loading when page is fully loaded
        window.addEventListener('load', function() {
            hideLoading();
        });
    })();
</script>

{{-- Beautiful Toastr Configuration --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof toastr !== 'undefined') {
            // Configure toastr for beautiful pop-ups
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut",
                "tapToDismiss": true
            };

            // Custom styling for toastr
            toastr.options.rtl = document.documentElement.dir === 'rtl';
        }

        // Global notification helper function
        window.showNotification = function(message, type = 'info', title = '') {
            if (typeof toastr === 'undefined') {
                // Fallback to alert if toastr is not available
                alert(message);
                return;
            }

            const titles = {
                'success': 'Success',
                'error': 'Error',
                'warning': 'Warning',
                'info': 'Information'
            };

            const notificationTitle = title || titles[type] || 'Notification';

            switch (type) {
                case 'success':
                    toastr.success(message, notificationTitle);
                    break;
                case 'error':
                    toastr.error(message, notificationTitle);
                    break;
                case 'warning':
                    toastr.warning(message, notificationTitle);
                    break;
                case 'info':
                default:
                    toastr.info(message, notificationTitle);
                    break;
            }
        };

        // Override native alert() function to use toastr
        window.originalAlert = window.alert;
        window.alert = function(message) {
            if (typeof toastr !== 'undefined') {
                toastr.info(message, 'Information');
            } else {
                window.originalAlert(message);
            }
        };

        // Beautiful Confirmation Dialog
        window.showConfirm = function(message, title = 'Confirm Action', type = 'warning', okText = 'Confirm',
            cancelText = 'Cancel') {
            return new Promise((resolve, reject) => {
                const modal = document.getElementById('confirmModal');
                if (!modal) {
                    // Fallback to native confirm if modal doesn't exist
                    const result = window.originalConfirm ? window.originalConfirm(message) :
                        confirm(message);
                    resolve(result);
                    return;
                }

                const bsModal = new bootstrap.Modal(modal);
                const messageEl = document.getElementById('confirmMessage');
                const titleEl = document.getElementById('confirmModalLabel');
                const iconContainer = document.getElementById('confirmIconContainer');
                const icon = document.getElementById('confirmIcon');
                const okBtn = document.getElementById('confirmOkBtn');
                const cancelBtn = document.getElementById('confirmCancelBtn');

                // Set message and title
                messageEl.textContent = message;
                titleEl.textContent = title;
                okBtn.innerHTML = `<i class="mdi mdi-check me-1"></i>${okText}`;
                cancelBtn.innerHTML = `<i class="mdi mdi-close me-1"></i>${cancelText}`;

                // Set icon and colors based on type
                const types = {
                    'danger': {
                        icon: 'mdi-alert-circle-outline',
                        bgClass: 'bg-label-danger',
                        btnClass: 'btn-danger'
                    },
                    'warning': {
                        icon: 'mdi-alert-outline',
                        bgClass: 'bg-label-warning',
                        btnClass: 'btn-warning'
                    },
                    'info': {
                        icon: 'mdi-information-outline',
                        bgClass: 'bg-label-info',
                        btnClass: 'btn-info'
                    },
                    'success': {
                        icon: 'mdi-check-circle-outline',
                        bgClass: 'bg-label-success',
                        btnClass: 'btn-success'
                    }
                };

                const config = types[type] || types['warning'];
                iconContainer.className = `avatar avatar-xl mx-auto mb-3 ${config.bgClass}`;
                icon.innerHTML = `<i class="mdi ${config.icon} mdi-48px"></i>`;
                okBtn.className = `btn ${config.btnClass}`;

                // Remove previous event listeners
                const newOkBtn = okBtn.cloneNode(true);
                const newCancelBtn = cancelBtn.cloneNode(true);
                okBtn.parentNode.replaceChild(newOkBtn, okBtn);
                cancelBtn.parentNode.replaceChild(newCancelBtn, cancelBtn);

                // Add event listeners
                newOkBtn.addEventListener('click', function() {
                    bsModal.hide();
                    resolve(true);
                });

                newCancelBtn.addEventListener('click', function() {
                    bsModal.hide();
                    resolve(false);
                });

                // Handle modal close events
                modal.addEventListener('hidden.bs.modal', function() {
                    resolve(false);
                }, {
                    once: true
                });

                // Show modal
                bsModal.show();
            });
        };

        // Override native confirm() function
        window.originalConfirm = window.confirm;
        window.confirm = function(message) {
            return window.showConfirm(message, 'Confirm Action', 'warning', 'OK', 'Cancel');
        };

        // Handle inline confirm() in form onsubmit attributes
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form[onsubmit*="confirm"]');
            forms.forEach(form => {
                const originalOnsubmit = form.getAttribute('onsubmit');
                if (originalOnsubmit && originalOnsubmit.includes('confirm')) {
                    form.removeAttribute('onsubmit');

                    // Extract confirm message
                    const match = originalOnsubmit.match(/confirm\(['"]([^'"]+)['"]\)/);
                    const confirmMessage = match ? match[1] : 'Are you sure?';

                    form.addEventListener('submit', async function(e) {
                        e.preventDefault();
                        const confirmed = await showConfirm(confirmMessage,
                            'Confirm Action', 'warning', 'Confirm', 'Cancel');
                        if (confirmed) {
                            form.submit();
                        }
                    });
                }
            });

            // Handle onclick confirm() in buttons
            const buttons = document.querySelectorAll(
                'button[onclick*="confirm"], a[onclick*="confirm"]');
            buttons.forEach(button => {
                const originalOnclick = button.getAttribute('onclick');
                if (originalOnclick && originalOnclick.includes('confirm')) {
                    button.removeAttribute('onclick');

                    // Extract confirm message
                    const match = originalOnclick.match(/confirm\(['"]([^'"]+)['"]\)/);
                    const confirmMessage = match ? match[1] : 'Are you sure?';

                    button.addEventListener('click', async function(e) {
                        if (button.type === 'submit') {
                            e.preventDefault();
                            const form = button.closest('form');
                            if (form) {
                                const confirmed = await showConfirm(confirmMessage,
                                    'Confirm Action', 'warning', 'Confirm',
                                    'Cancel');
                                if (confirmed) {
                                    form.submit();
                                }
                            }
                        }
                    });
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        // When the select all checkbox is changed
        $('.selectAllCheckboxInputs').change(function() {
            // Check or uncheck all checkboxes based on the select all checkbox state
            $('.checkboxInput').prop('checked', $(this).prop('checked'));
        });

        // When any individual checkbox is changed
        $('.checkboxInput').change(function() {
            // If not all checkboxes are checked, uncheck the select all checkbox
            if ($('.checkboxInput:checked').length !== $('.checkboxInput').length) {
                $('.selectAllCheckboxInputs').prop('checked', false);
            } else {
                // If all checkboxes are checked, check the select all checkbox
                $('.selectAllCheckboxInputs').prop('checked', true);
            }
        });
    });
</script>

<script>
    $(document).on('click', '.delete-selection', function() {
        let checkedInputs = $('.checkboxInput:checked');
        let ids = [];

        checkedInputs.each(function() {
            ids.push($(this).val());
        });

        $('.checked-inputs').val(ids);
        $('#delete-selection-form').attr('action', $(this).data('url'));

    });

    $(document).on('click', '.assign-selection', function() {
        let checkedInputs = $('.checkboxInput:checked');
        let ids = [];

        checkedInputs.each(function() {
            ids.push($(this).val());
        });

        $('.checked-inputs').val(ids);
        $('#assign-selection-form').attr('action', $(this).data('url'));

    });

    $(document).on('click', '#whatsapp-btn', function() {
        let checkedInputs = $('.checkboxInput:checked');
        let ids = [];

        checkedInputs.each(function() {
            ids.push($(this).val());
        });

        $('.checked-leads').val(ids);
    });
</script>

<script>
    $(document).on('click', '.delete-btn', function() {
        let url = $(this).data('url');
        $('#delete-selection-form').attr('action', url);
        $('#delete-selection-form').append('<input type="hidden" name="_method" value="DELETE">');
    });

    $(document).on('click', '.assign-btn', function() {
        let url = $(this).data('url');
        $('#delete-selection-form').attr('action', url);
        $('#delete-selection-form').append('<input type="hidden" name="_method" value="PUT">');
    });
</script>

<script>
    function passLimit() {
        const limit = document.querySelector('select[name="limit"]').value;
        const url = new URL(window.location.href);

        url.searchParams.set('limit', limit);
        window.location.href = url.toString();
    }
</script>

{{-- Real-Time Notifications --}}
@if (config('broadcasting.default') !== 'null' && config('broadcasting.default') !== 'log')
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        // Initialize real-time notifications
        document.addEventListener('DOMContentLoaded', function() {
            const userId = document.querySelector('meta[name="user-id"]')?.getAttribute('content');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const pusherKey = '{{ config('broadcasting.connections.pusher.key') }}';
            const pusherCluster = '{{ config('broadcasting.connections.pusher.options.cluster') ?? 'mt1' }}';

            if (userId && typeof Pusher !== 'undefined' && pusherKey && pusherKey !== 'your-pusher-app-key' &&
                pusherKey !== '') {
                try {
                    // Initialize Pusher
                    const pusher = new Pusher(pusherKey, {
                        cluster: pusherCluster,
                        encrypted: true,
                        authEndpoint: '/broadcasting/auth',
                        auth: {
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            }
                        }
                    });

                    // Subscribe to user notifications
                    const channel = pusher.subscribe('private-user.' + userId);

                    // Listen for notifications
                    channel.bind('Illuminate\\Notifications\\Events\\BroadcastNotificationCreated', function(data) {
                        updateNotificationCounter();
                        addNotificationToList(data);

                        // Show toast notification
                        if (typeof toastr !== 'undefined') {
                            toastr.info(data.message || data.title, data.title || 'Notification');
                        }
                    });

                    // Listen for custom events
                    channel.bind('.RealTimeNotification', function(data) {
                        updateNotificationCounter();
                        addNotificationToList(data);

                        if (typeof toastr !== 'undefined') {
                            toastr[data.type || 'info'](data.message, data.title || 'Notification');
                        }
                    });

                    // Subscribe to CRM leads channel
                    const leadsChannel = pusher.subscribe('private-crm.leads');
                    leadsChannel.bind('.LeadCreated', function(data) {
                        if (typeof toastr !== 'undefined') {
                            toastr.info('New lead: ' + (data.name || data.lead?.full_name || 'Unknown'),
                                'New Lead');
                        }
                        // Refresh page if on leads page
                        if (window.location.pathname.includes('/crm/leads')) {
                            setTimeout(() => window.location.reload(), 2000);
                        }
                    });

                    // Update notification counter
                    function updateNotificationCounter() {
                        fetch('/api/notifications/unread-count')
                            .then(response => response.json())
                            .then(data => {
                                const counters = document.querySelectorAll('.notification-counter');
                                counters.forEach(counter => {
                                    counter.textContent = data.count || 0;
                                    counter.style.display = (data.count > 0) ? 'block' : 'none';
                                });
                            })
                            .catch(error => console.error('Error fetching notification count:', error));
                    }

                    // Add notification to list
                    function addNotificationToList(data) {
                        const list = document.querySelector('.notification-list');
                        if (list) {
                            const item = document.createElement('li');
                            item.className = 'list-group-item list-group-item-action dropdown-notifications-item';
                            item.innerHTML = `
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar">
                                        <span class="avatar-initial rounded-circle bg-label-${data.type || 'info'}">
                                            <i class="mdi mdi-${getNotificationIcon(data.type)}"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">${data.title || 'Notification'}</h6>
                                    <small class="text-muted">${data.message || ''}</small>
                                    <small class="d-block text-muted mt-1">Just now</small>
                                </div>
                            </div>
                        `;
                            list.insertBefore(item, list.firstChild);

                            // Remove "No notifications" message if exists
                            const noNotifications = list.querySelector(
                                '.text-muted:contains("You\'re all caught up")');
                            if (noNotifications) {
                                noNotifications.closest('li').remove();
                            }
                        }
                    }

                    // Get notification icon based on type
                    function getNotificationIcon(type) {
                        const icons = {
                            'success': 'check-circle',
                            'error': 'alert-circle',
                            'warning': 'alert',
                            'info': 'information'
                        };
                        return icons[type] || 'information';
                    }

                    // Initial load of notification count
                    updateNotificationCounter();
                } catch (error) {
                    console.warn('Failed to initialize Pusher:', error);
                    console.log('Real-time features will be disabled. Events will be logged only.');
                }
            } else {
                console.log('Pusher not configured or user not authenticated. Real-time features disabled.');
            }
        });
    </script>
@else
    <script>
        // Log driver mode - events will be logged but not broadcast
        console.log('Broadcasting is set to log mode. Real-time features are disabled.');
        console.log('To enable real-time features, set BROADCAST_DRIVER=pusher in .env and configure Pusher credentials.');
    </script>
@endif
