<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? config('app.name') }}</title>

<!-- CSRF token for AJAX requests -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Optional CloudFront base URL for serving media (set in .env as MEDIA_CLOUDFRONT_URL) -->
@if(config('app.env') !== 'testing')
    <meta name="media-cloudfront-url" content="{{ config('services.media.cloudfront_url', '') }}">
@endif

<link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
<link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
<link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
