<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.user.partials.head')
</head>

<body>
    <div class="wrapper">
        <div class="main-header">
            @include('layouts.user.partials.header')
            @include('layouts.user.partials.navbar')
        </div>

        @include('layouts.user.partials.sidebar')

        <div class="main-panel">
            <div class="container">
                <div class="page-inner">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    @include('layouts.user.partials.scripts')
    @stack('script')
    <script>
        @if (session('success'))
            $.notify({
                icon: 'fas fa-check-circle',
                message: "{{ session('success') }}"
            }, {
                type: 'success',
                delay: 3000,
                placement: {
                    from: "top",
                    align: "right"
                },
                animate: {
                    enter: 'animated fadeInDown',
                    exit: 'animated fadeOutUp'
                },
                template: '<div data-notify="container" class="col-11 col-md-4 alert alert-{0} alert-with-icon" role="alert">' +
                    '<span data-notify="icon" class="me-2"></span> ' +
                    '<span data-notify="message">{2}</span>' +
                    '</div>'
            });
        @endif

        @if (session('error'))
            $.notify({
                icon: 'fas fa-times-circle',
                message: "{{ session('error') }}"
            }, {
                type: 'danger',
                delay: 3000,
                placement: {
                    from: "top",
                    align: "right"
                },
                animate: {
                    enter: 'animated fadeInDown',
                    exit: 'animated fadeOutUp'
                },
                template: '<div data-notify="container" class="col-11 col-md-4 alert alert-{0} alert-with-icon" role="alert">' +
                    '<span data-notify="icon" class="me-2"></span> ' +
                    '<span data-notify="message">{2}</span>' +
                    '</div>'
            });
        @endif
    </script>
</body>

</html>
