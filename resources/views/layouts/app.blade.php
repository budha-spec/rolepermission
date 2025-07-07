<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Manage Role Permission')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap 5 CDN -->
    <script src="{{ url('rolepermission-assets/js/jquery-3.7.1.min.js') }}"></script>
    <link rel="stylesheet" href="{{ url('rolepermission-assets/css/font-awesome.css') }}">
    <link href="{{ url('rolepermission-assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ url('rolepermission-assets/js/sweetalert2.js') }}"></script>
    <script type="text/javascript">
        window._swalFlashData;
        window._swalShown = false;
    </script>
    @stack('styles')
    <!-- Optional: Custom Styles -->
    <style>
        body { padding-top: 70px; }
        .nav-link.active { font-weight: bold; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Manage Role Permission</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('modules*') ? 'text-white' : '' }}" href="{{ route('modules.index') }}">Manage Modules</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('roles*') ? 'text-white' : '' }}" href="{{ route('roles.index') }}">Manage Roles</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <script src="{{ url('rolepermission-assets/js/bootstrap.bundle.min.js') }}"></script>

    @include('access::partials.swal')
    <!-- Main Content -->
    <main class="container">
        @yield('content')
    </main>
    @stack('scripts')
    <script>
        function initTooltips() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (el) {
                return new bootstrap.Tooltip(el);
            });
        }

        document.addEventListener('DOMContentLoaded', initTooltips);

        function showAlert(type, message) {
            Swal.fire({
                icon: type,
                text: message,
                toast: true,
                showCloseButton: true,
                showCancelButton: true,
                allowEscapeKey: true,
                showCancelButton: false,
                showConfirmButton: false,
                timer: 3000,
                position: "top-right",
                timerProgressBar: true,
            });
        }

        function showSwalFromSession() {
            if (window._swalFlashData) {
                const defaultOptions = {
                    toast: true,
                    showCloseButton: true,
                    showCancelButton: true,
                    allowEscapeKey: true,
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 3000,
                    position: "top-right",
                    timerProgressBar: true,
                };

            // Merge session flash data with defaults
                const swalOptions = Object.assign({}, defaultOptions, window._swalFlashData);

                if (window._swalFlashData && !window._swalShown) {
                    Swal.fire(swalOptions);
                    window._swalFlashData = null;
                    window._swalShown = true;
                }
            }
        }
        document.addEventListener('DOMContentLoaded', showSwalFromSession);

        $(document).ajaxSuccess(function (event, xhr) {
            try {
                let response = JSON.parse(xhr.responseText);
                if (response.alert) {
                    window._swalShown = false;
                    window._swalFlashData = response.alert;
                    showSwalFromSession();
                }
            } catch (e) {
            // Not JSON or no swal
            }
        });
    </script>
</body>
</html>
