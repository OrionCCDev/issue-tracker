<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>Projects Issue Tracker</title>
    <meta name="description" content="Projects Issue Tracker System" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/imgs/favicon.png') }}">
    <link rel="icon" href="{{ asset('assets/imgs/favicon.png') }}" type="image/x-icon">

    <!-- vector map CSS -->
    <link href="{{ asset('assets/vendors/vectormap/jquery-jvectormap-2.0.3.css') }}" rel="stylesheet" type="text/css" />

    <!-- Toggles CSS -->
    <link href="{{ asset('assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/jquery-toggles/css/toggles.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendors/jquery-toggles/css/themes/toggles-light.css') }}" rel="stylesheet" type="text/css">

    <!-- Toastr CSS -->
    <link href="{{ asset('assets/vendors/jquery-toast-plugin/dist/jquery.toast.min.css') }}" rel="stylesheet" type="text/css">

    <!-- Custom CSS -->
    <link href="{{ asset('assets/dist/css/style.css') }}" rel="stylesheet" type="text/css">
    @yield('custom_css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @livewireStyles()
    <style>
        .hk-pg-wrapper {
            padding: 30px 30px !important;margin-top: 50px;

        }
        .hk-wrapper .hk-navbar.navbar-dark {
            background-color: #0f5874 !important;
        }
        .hk-wrapper.hk-vertical-nav .hk-nav.hk-nav-dark {
            background-color: #114e67 !important;
        }
        .dropdown-notifications .badge-indicator {
            position: absolute;
            top: 5px;
            right: 2px;
            font-size: 20px;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .notification-dropdown {
            width: 300px;
            max-height: 400px;
            overflow-y: auto;
        }

        .notifications-wrap {
            max-height: 250px;
            overflow-y: auto;
        }

        .notifications-text {
            font-size: 14px;
            line-height: 1.3;
            font-weight: 600;
        }

        .notifications-info {
            font-size: 13px;
            line-height: 1.2;
        }

        .notifications-time {
            font-size: 12px;
            color: #6c757d;
        }

        .notification-number-requests {
            animation: flash 1s infinite;
        }

        .bg-light-info {
            background-color: #e8f4ff;
            border-left: 4px solid #2c95ff;
        }

        @keyframes flash {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.85);
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        .navbar-nav .nav-item.active .nav-link {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }

        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            transform: translate(50%, -50%);
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .avatar-img {
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        @media (max-width: 991.98px) {
            .navbar-collapse {
                background-color: #0f5874;
                padding: 1rem;
                border-radius: 0.5rem;
                margin-top: 1rem;
            }
        }

        .nav-link i {
            width: 24px;
            text-align: center;
        }

        /* Modern Loader Style */
        #modern-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #0f5874;
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: opacity 0.8s ease-out, visibility 0.8s ease-out;
        }

        #modern-loader.fade-out {
            opacity: 0;
            visibility: hidden;
        }

        .loader-content {
            text-align: center;
        }

        .spinner-container {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto 30px;
        }

        .spinner {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 6px solid transparent;
            border-top-color: #ffffff;
            animation: spin 1.2s linear infinite;
        }

        .spinner-inner {
            width: 80%;
            height: 80%;
            top: 10%;
            left: 10%;
            border-top-color: #4cd2ff;
            animation-duration: 1.6s;
            animation-direction: reverse;
        }

        .spinner-center {
            width: 50%;
            height: 50%;
            top: 25%;
            left: 25%;
            border-top-color: #ffffff;
            animation-duration: 2s;
        }

        .loader-title {
            color: #ffffff;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
            animation: pulse 1.5s infinite;
        }

        .loader-subtitle {
            color: rgba(255, 255, 255, 0.7);
            font-size: 16px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes pulse {
            0% { opacity: 0.6; }
            50% { opacity: 1; }
            100% { opacity: 0.6; }
        }
    </style>
</head>

<body>
    <!-- Modern Loader -->
    <div id="modern-loader">
        <div class="loader-content">
            <div class="spinner-container">
                <div class="spinner"></div>
                <div class="spinner spinner-inner"></div>
                <div class="spinner spinner-center"></div>
            </div>
            <h2 class="loader-title">Loading...</h2>
            <p class="loader-subtitle">Preparing your issue tracker</p>
        </div>
    </div>

    <!-- Preloader (original - can be removed if you prefer the new one) -->
    <div class="preloader-it" style="display: none;">
        <div class="loader-pendulums"></div>
    </div>
    <!-- /Preloader -->

    <!-- HK Wrapper -->
    <div class="hk-wrapper">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-xl navbar-dark fixed-top hk-navbar" style="background-color: #0f5874; padding: 0.5rem 1rem;">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}" style="padding: 0;">
                    <img class="brand-img d-inline-block" src="{{ asset('assets/logo-white.webp') }}" width="100px" height="75px" alt="brand" />
                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="gap: 1rem;">
                        <li class="nav-item {{ Request::is('dashboard') ? 'active' : '' }}">
                            <a class="nav-link d-flex align-items-center" href="{{ route('dashboard') }}" style="padding: 0.5rem 1rem;">
                                <i class="fa fa-home fa-lg me-2 mr-2"></i>
                                <span class="nav-link-text">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item {{ Request::is('projects*') ? 'active' : '' }}">
                            <a class="nav-link d-flex align-items-center" href="{{ route('projects.index') }}" style="padding: 0.5rem 1rem;">

                                <span class="nav-link-text">Projects</span>
                            </a>
                        </li>
                        <li class="nav-item {{ Request::is('issues*') ? 'active' : '' }}">
                            <a class="nav-link d-flex align-items-center" href="{{ route('issues.my-issues') }}" style="padding: 0.5rem 1rem;">
                                <i class="fa fa-bug fa-lg me-2 mr-2"></i>
                                <span class="nav-link-text">Issues</span>
                            </a>
                        </li>
                        <li class="nav-item {{ Request::is('users*') ? 'active' : '' }}">
                            <a class="nav-link d-flex align-items-center" href="{{ route('users.index') }}" style="padding: 0.5rem 1rem;">
                                <i class="fa fa-users fa-lg me-2 mr-2"></i>
                                <span class="nav-link-text">Users</span>
                            </a>
                        </li>
                    </ul>

                    <ul class="navbar-nav ms-auto align-items-center" style="gap: 1rem;">
                        <li class="nav-item dropdown dropdown-notifications">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 0.5rem 1rem;">
                                <i class="fa fa-bell fa-lg me-2"></i>
                                @if(auth()->user()->unreadNotifications()->count() > 0)
                                    <span class="badge badge-danger notification-badge">
                                        {{ auth()->user()->unreadNotifications()->count() }}
                                    </span>
                                @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-right notification-dropdown" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                <h6 class="dropdown-header">Notifications</h6>
                                <div class="notifications-wrap">
                                    @php
                                        $userNotifications = auth()->user()->notifications()->latest()->take(5)->get();
                                    @endphp

                                    @if($userNotifications->count() > 0)
                                        @foreach($userNotifications as $notification)
                                            <a href="{{ isset($notification->data['url']) ? $notification->data['url'] : '#' }}"
                                               class="dropdown-item notification-item {{ is_null($notification->read_at) ? 'bg-light-info' : '' }}"
                                               onclick="markNotificationAsRead({{ $notification->id }})">
                                                <div class="media">
                                                    <div class="media-body">
                                                        <div class="notifications-text">
                                                            {{ isset($notification->data['title']) ? $notification->data['title'] : ucfirst(str_replace('_', ' ', $notification->type)) }}
                                                        </div>
                                                        <div class="notifications-info">
                                                            {{ isset($notification->data['message']) ? $notification->data['message'] : '' }}
                                                        </div>
                                                        <div class="notifications-time">
                                                            {{ $notification->created_at->diffForHumans() }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="dropdown-divider"></div>
                                        @endforeach
                                        <a href="{{ route('notifications.mark-all-as-read') }}"
                                           onclick="event.preventDefault(); document.getElementById('mark-all-read-form').submit();"
                                           class="dropdown-item text-center mark-all-read">
                                            <i class="fa fa-check-double me-2"></i>Mark all as read
                                        </a>
                                        <form id="mark-all-read-form" action="{{ route('notifications.mark-all-as-read') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    @else
                                        <div class="dropdown-item">
                                            <div class="media">
                                                <div class="media-body">
                                                    <div class="notifications-text">No new notifications</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <a href="{{ route('notifications.index') }}" class="dropdown-item text-center">
                                    <i class="fa fa-list me-2"></i>View all notifications
                                </a>
                            </div>
                        </li>
                        <li class="nav-item dropdown dropdown-authentication">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 0.5rem 1rem;">
                                <div class="media d-flex align-items-center">
                                    <div class="media-img-wrap me-2">
                                        <div class="avatar" style="width: 40px !important; height: 40px !important;">
                                            <img src="{{ asset('storage/' . Auth::user()->image_path) }}" alt="user"
                                                class="avatar-img rounded-circle" style="object-fit: cover; object-position: top;">
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>

                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" data-dropdown-in="flipInX" data-dropdown-out="flipOutX">
                                <form action="{{ route('logout') }}" method="post">
                                    @csrf
                                    <button class="dropdown-item" type="submit">
                                        <i class="fa fa-sign-out-alt me-2"></i>
                                        <span>Log out</span>
                                    </button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- /Top Navbar -->

        <!-- Main Content -->
        <div class="hk-pg-wrapper">
            @yield('content')
        </div>
        <!-- /Main Content -->
    </div>
    <!-- /HK Wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('assets/vendors/jquery/dist/jquery.min.js') }}"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{ asset('assets/vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    <!-- Slimscroll JavaScript -->
    <script src="{{ asset('assets/dist/js/jquery.slimscroll.js') }}"></script>

    <!-- Fancy Dropdown JS -->
    <script src="{{ asset('assets/dist/js/dropdown-bootstrap-extended.js') }}"></script>

    <!-- FeatherIcons JavaScript -->
    <script src="{{ asset('assets/dist/js/feather.min.js') }}"></script>

    <!-- Toggles JavaScript -->
    <script src="{{ asset('assets/vendors/jquery-toggles/toggles.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/toggle-data.js') }}"></script>

    <!-- Counter Animation JavaScript -->
    <script src="{{ asset('assets/vendors/waypoints/lib/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jquery.counterup/jquery.counterup.min.js') }}"></script>

    <!-- Morris Charts JavaScript -->
    <script src="{{ asset('assets/vendors/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/morris.js/morris.min.js') }}"></script>

    <!-- EChartJS JavaScript -->
    <script src="{{ asset('assets/vendors/echarts/dist/echarts-en.min.js') }}"></script>

    <!-- Sparkline JavaScript -->
    <script src="{{ asset('assets/vendors/jquery.sparkline/dist/jquery.sparkline.min.js') }}"></script>

    <!-- Vector Maps JavaScript -->
    <script src="{{ asset('assets/vendors/vectormap/jquery-jvectormap-2.0.3.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/vectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('assets/dist/js/vectormap-data.js') }}"></script>

    <!-- Owl JavaScript -->
    <script src="{{ asset('assets/vendors/owl.carousel/dist/owl.carousel.min.js') }}"></script>

    <!-- Toastr JS -->
    <script src="{{ asset('assets/vendors/jquery-toast-plugin/dist/jquery.toast.min.js') }}"></script>

    <!-- Init JavaScript -->
    <script src="{{ asset('assets/dist/js/init.js') }}"></script>
    <script src="{{ asset('assets/dist/js/dashboard-data.js') }}"></script>

    <!-- Pusher and Echo -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Initialize Bootstrap Toasts -->
    <script>
        $(document).ready(function() {
            // Enable Bootstrap toasts
            $('.toast').toast({
                delay: 5000,
                animation: true
            });
        });
    </script>

    <script>
    function markNotificationAsRead(notificationId) {
        fetch(`/notifications/${notificationId}/mark-as-read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(response => {
            if (response.ok) {
                // Update the notification count
                const badge = document.querySelector('.notification-badge');
                if (badge) {
                    const count = parseInt(badge.textContent);
                    if (count > 1) {
                        badge.textContent = count - 1;
                    } else {
                        badge.remove();
                    }
                }
            }
        });
    }
    </script>

    @livewireScripts()
    @yield('custom_js')

    <!-- Loader Script -->
    <script>
        // Modern loader animation
        document.addEventListener('DOMContentLoaded', function() {
            // Allow a minimum time for the loader to be visible
            setTimeout(function() {
                const loader = document.getElementById('modern-loader');
                if (loader) {
                    // Add fade-out class to initiate transition
                    loader.classList.add('fade-out');
                    // Remove loader from DOM after transition completes
                    setTimeout(function() {
                        loader.style.display = 'none';
                    }, 800); // Match this to the CSS transition time
                }
            }, 1200); // Minimum loader display time (adjust as needed)
        });

        // Also hide loader when window is fully loaded
        window.addEventListener('load', function() {
            const loader = document.getElementById('modern-loader');
            if (loader && !loader.classList.contains('fade-out')) {
                loader.classList.add('fade-out');
                setTimeout(function() {
                    loader.style.display = 'none';
                }, 800);
            }
        });
    </script>

</body>

</html>
