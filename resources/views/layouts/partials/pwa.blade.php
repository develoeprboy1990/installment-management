<link rel="manifest" href="{{ url('/manifest.webmanifest') }}">
<meta name="theme-color" content="#1ab394">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="{{ getUserSetting('project_name') ?? config('app.name', 'Installments') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('icons/icon-32.png') }}">
<link rel="apple-touch-icon" href="{{ asset('icons/icon-192.png') }}">
