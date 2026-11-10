<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
    <title>{{ isset($title) ? $title.' · ' : '' }}مدار للمستقلين</title>
    <meta name="description" content="مدار: مساحة عمل المستقل السعودي لإدارة العملاء والعروض والمشاريع والفواتير والمواعيد.">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lalezar&family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    {{ $slot }}

    @if (session('status'))
        <div class="toast" role="status">{{ session('status') }}</div>
    @endif
    <template id="sar"><x-sar /></template>
</body>
</html>
