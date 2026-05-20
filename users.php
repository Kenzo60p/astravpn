<?php
ob_start();
?>
<div class="min-h-screen px-6 py-8 lg:px-10">
    <header class="mb-8">
        <h1 class="text-4xl font-semibold text-white">User Management</h1>
        <p class="mt-2 text-slate-400">Review account status, subscriptions, and login logs.</p>
    </header>
    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl backdrop-blur-xl overflow-x-auto">
        <table class="min-w-full text-left text-sm text-slate-300">
            <thead class="border-b border-slate-700 text-slate-400">
                <tr>
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr class="border-b border-slate-800 hover:bg-slate-900/50">
                    <td class="px-4 py-3"><?= htmlspecialchars($user['id']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($user['name']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($user['email']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($user['status']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php
$content = ob_get_clean();
require dirname(__DIR__) . '/layout.php';
