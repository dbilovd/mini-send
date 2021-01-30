<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config("app.name", "Laravel") }}</title>
        <link rel="stylesheet" type="text/css" href="/css/app.css">
    </head>
    <body class="antialiased">
        <div id="app">
            <ms-home></ms-home>
        </div>

        <!-- Scripts -->
        <script type="text/javascript">
            window.miniSend = {
                baseUrl: "{{ config('app.url') }}"
            }
        </script>
        <script src="/js/app.js"></script>
    </body>
</html>
