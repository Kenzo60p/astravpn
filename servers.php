<?php
ob_start();
?>
<div class="min-h-screen px-6 py-8 lg:px-10">
    <header class="mb-8">
        <h1 class="text-4xl font-semibold text-white">Server Management</h1>
        <p class="mt-2 text-slate-400">Track status, update ping, and review VPN access points.</p>
    </header>
    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl backdrop-blur-xl overflow-x-auto">
        <table class="min-w-full text-left text-sm text-slate-300">
            <thead class="border-b border-slate-700 text-slate-400">
                <tr>
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Host</th>
                    <th class="px-4 py-3">Region</th>
                    <th class="px-4 py-3">Type</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Ping</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($servers as $server): ?>
                <tr class="border-b border-slate-800 hover:bg-slate-900/50">
                    <td class="px-4 py-3"><?= htmlspecialchars($server['id']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($server['name']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($server['host']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($server['region']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($server['type']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($server['status']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($server['ping_ms'] ?: '—') ?> ms</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php
$content = ob_get_clean();
require dirname(__DIR__) . '/layout.php';
