<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crypto Tracker</title>
    @routes
    @inertiaHead
    
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    {{-- @vite('resources/js/app.js') --}}

    {{-- levar lógica do grafico no dashboard --}}
</head>
<body>
    @inertia
</body>
</html>
