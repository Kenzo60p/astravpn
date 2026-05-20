<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'AstraVPN') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(59,130,246,0.25),transparent_45%),radial-gradient(circle_at_bottom_right,_rgba(168,85,247,0.16),transparent_45%)] pointer-events-none"></div>
    <div class="relative z-10">
        <?= $content ?>
    </div>
    <script src="/js/app.js"></script>
</body>
</html>
