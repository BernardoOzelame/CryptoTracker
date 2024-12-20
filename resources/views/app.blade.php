<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crypto Tracker</title>
    @routes
    @inertiaHead
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
</head>
<body>
    @inertia
</body>
</html>

<script>
    window.userId = @json(auth()->check() ? auth()->id() : null);
</script>
