<?php
ob_start();
?>
<div class="min-h-screen px-6 py-8 lg:px-10">
    <header class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between mb-8">
        <div>
            <p class="text-cyan-300 uppercase tracking-[0.3em] text-sm">Admin Console</p>
            <h1 class="text-4xl font-semibold text-white">AstraVPN Operations</h1>
            <p class="mt-2 text-slate-400">Monitor users, revenue, and server health in real time.</p>
        </div>
        <div class="grid grid-cols-3 gap-4">
            <div class="rounded-3xl bg-slate-900/80 p-5 text-center">
                <p class="text-sm text-slate-400">Users</p>
                <p class="mt-3 text-3xl font-semibold text-white"><?= $stats['users'] ?></p>
            </div>
            <div class="rounded-3xl bg-slate-900/80 p-5 text-center">
                <p class="text-sm text-slate-400">Servers</p>
                <p class="mt-3 text-3xl font-semibold text-white"><?= $stats['servers'] ?></p>
            </div>
            <div class="rounded-3xl bg-slate-900/80 p-5 text-center">
                <p class="text-sm text-slate-400">Revenue</p>
                <p class="mt-3 text-3xl font-semibold text-white">$<?= number_format($stats['revenue'], 2) ?></p>
            </div>
        </div>
    </header>
    <section class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl backdrop-blur-xl">
            <h2 class="text-xl font-semibold text-white">Live activity</h2>
            <p class="text-slate-400 mt-2">User login records, config generation events, and subscription updates.</p>
            <div class="mt-5 space-y-4">
                <div class="rounded-3xl bg-slate-900/80 p-4 text-slate-300">No live events yet. Integrate with your VPN logs for real-time feeds.</div>
            </div>
        </div>
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl backdrop-blur-xl">
            <h2 class="text-xl font-semibold text-white">Server control</h2>
            <p class="text-slate-400 mt-2">Manage server availability and monitor network performance.</p>
            <div class="mt-5 space-y-4">
                <?php foreach ($servers as $server): ?>
                <div class="rounded-3xl bg-slate-900/80 p-4">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="font-semibold text-white"><?= htmlspecialchars($server['name']) ?></p>
                            <p class="text-slate-400 text-sm"><?= htmlspecialchars($server['host']) ?> • <?= htmlspecialchars($server['region']) ?></p>
                        </div>
                        <span class="rounded-full bg-slate-800 px-3 py-1 text-xs text-slate-300"><?= htmlspecialchars($server['status']) ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="mt-8 rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl backdrop-blur-xl">
        <h2 class="text-xl font-semibold text-white">Recent user management</h2>
        <div class="mt-5 overflow-x-auto">
            <table class="min-w-full text-left text-sm text-slate-300">
                <thead class="border-b border-slate-700 text-slate-400">
                    <tr>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($users, 0, 5) as $user): ?>
                    <tr class="border-b border-slate-800">
                        <td class="px-4 py-3"><?= htmlspecialchars($user['name']) ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($user['email']) ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($user['status']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?php
$content = ob_get_clean();
require dirname(__DIR__) . '/layout.php';
