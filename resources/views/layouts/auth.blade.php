<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #f5f5f5; display: flex; min-height: 100vh; align-items: center; justify-content: center; margin: 0; }
        .card { background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,.1); width: 100%; max-width: 380px; }
        label { display: block; font-size: .875rem; margin-bottom: .25rem; }
        input { width: 100%; padding: .5rem; margin-bottom: 1rem; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: .6rem; background: #111827; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
        .error { color: #dc2626; font-size: .8rem; margin-top: -.75rem; margin-bottom: 1rem; }
        .status { color: #16a34a; font-size: .875rem; margin-bottom: 1rem; }
        .links { margin-top: 1rem; font-size: .875rem; text-align: center; }
        a { color: #2563eb; }
    </style>
</head>
<body>
    <div class="card">
        @yield('content')
    </div>
</body>
</html>
