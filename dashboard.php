<?php
ob_start();
?>
<div class="min-h-screen px-6 py-8 lg:px-10">
    <header class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between mb-8">
        <div>
            <p class="text-cyan-300 uppercase tracking-[0.3em] text-sm">AstraVPN Enterprise</p>
            <h1 class="text-4xl font-semibold text-white">Welcome back, <?= htmlspecialchars($user['name']) ?></h1>
            <p class="mt-2 text-slate-400">Manage servers, subscriptions, and VPN configs from one secure panel.</p>
        </div>
        <div class="flex items-center gap-3">
            <button id="themeToggle" class="rounded-2xl border border-white/10 bg-slate-900/80 px-4 py-3 text-sm text-slate-200 transition hover:bg-slate-800">Toggle Theme</button>
            <form method="POST" action="/logout">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(App\Core\Helpers::csrfToken()) ?>">
                <button type="submit" class="rounded-2xl bg-cyan-500 px-4 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-400">Logout</button>
            </form>
        </div>
    </header>

    <section class="grid gap-6 lg:grid-cols-3 mb-8">
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl backdrop-blur-xl">
            <p class="text-sm text-slate-400">Active servers</p>
            <p class="mt-4 text-4xl font-semibold text-white"><?= count($servers) ?></p>
            <p class="mt-2 text-slate-500">Online and monitored.</p>
        </div>
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl backdrop-blur-xl">
            <p class="text-sm text-slate-400">Your configs</p>
            <p class="mt-4 text-4xl font-semibold text-white"><?= count($configs) ?></p>
            <p class="mt-2 text-slate-500">Ready for download and QR scan.</p>
        </div>
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl backdrop-blur-xl">
            <p class="text-sm text-slate-400">Support</p>
            <p class="mt-4 text-4xl font-semibold text-white">24/7</p>
            <p class="mt-2 text-slate-500">Guides for leak protection and kill switch.</p>
        </div>
    </section>

    <section class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl backdrop-blur-xl">
            <h2 class="text-xl font-semibold text-white">Server roster</h2>
            <p class="text-slate-400 mb-4">Multi-server support with ping and region details.</p>
            <div class="space-y-4">
                <?php foreach ($servers as $server): ?>
                    <div class="rounded-3xl border border-white/5 bg-slate-900/80 p-4">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="font-semibold text-white"><?= htmlspecialchars($server['name']) ?></p>
                                <p class="text-slate-500 text-sm"><?= htmlspecialchars($server['region']) ?> • <?= htmlspecialchars($server['type']) ?></p>
                            </div>
                            <span class="rounded-full bg-slate-800 px-3 py-1 text-xs text-slate-300"><?= htmlspecialchars($server['status']) ?></span>
                        </div>
                        <div class="mt-3 flex items-center justify-between text-slate-400 text-sm">
                            <span>Ping: <?= htmlspecialchars($server['ping_ms'] ?: '—') ?>ms</span>
                            <span>Users: <?= htmlspecialchars($server['max_users']) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl backdrop-blur-xl">
            <h2 class="text-xl font-semibold text-white">Configuration generator</h2>
            <p class="text-slate-400 mb-4">Generate OpenVPN or WireGuard profiles with QR code support.</p>
            <form id="configForm" class="space-y-4" method="POST" action="/api/configs/generate">
                <label class="block text-sm text-slate-300">Select server</label>
                <select name="server_id" class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white">
                    <?php foreach ($servers as $server): ?>
                        <option value="<?= $server['id'] ?>"><?= htmlspecialchars($server['name'] . ' - ' . $server['region']) ?></option>
                    <?php endforeach; ?>
                </select>
                <label class="block text-sm text-slate-300">Connection protocol</label>
                <select name="protocol" class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white">
                    <option value="udp">OpenVPN UDP</option>
                    <option value="tcp">OpenVPN TCP</option>
                    <option value="wireguard">WireGuard</option>
                </select>
                <button type="submit" class="w-full rounded-2xl bg-cyan-500 py-3 text-sm font-semibold text-slate-950 hover:bg-cyan-400">Generate config</button>
            </form>
        </div>
    </section>
</div>
<?php
$content = ob_get_clean();
require dirname(__DIR__) . '/layout.php';
