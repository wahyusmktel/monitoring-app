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
</body>

</html>
