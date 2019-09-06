@php
    $languages = language()->allowed();
     $config = [
            'locale' => $locale = app()->getLocale(),
            'appName' => config('app.name')
        ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Styles -->
    <link href="{{ mix('dist/css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <section class="id-section id-section-xlarge">
        <div class="id-container id-container-xsmall">
            <div class="id-width-2-3@m id-margin-auto">
                <img src="{{ asset('img/logo.png') }}" srcset="{{ asset('img/logo@2x.png') }} 2x" alt="InvoiceDroid logo" width="100" class="id-display-block id-margin-auto id-margin-large-bottom">
                @yield('content')
            </div>
        </div>
    </section>
</div>
<script>
    window.languages = @json($languages);
    window.config = @json($config);
    window.messages = @json($messages);
</script>
<script src="{{ mix('dist/js/install.js') }}" defer></script>
</body>
</html>

