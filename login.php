<?php
ob_start();
?>
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="max-w-xl w-full backdrop-blur-xl bg-white/10 border border-white/10 rounded-3xl shadow-2xl overflow-hidden">
        <div class="p-10 sm:p-14">
            <div class="text-center mb-8">
                <p class="text-sm uppercase tracking-[0.3em] text-cyan-300">AstraVPN Enterprise</p>
                <h1 class="mt-4 text-4xl font-semibold text-white">Secure VPN access made simple</h1>
                <p class="mt-4 text-slate-300">Login to manage your servers, subscriptions, and secure connections.</p>
            </div>
            <form id="loginForm" class="space-y-5" method="POST" action="/login">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(App\Core\Helpers::csrfToken()) ?>">
                <div>
                    <label class="block text-sm text-slate-300">Email</label>
                    <input name="email" type="email" required class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-cyan-500">
                </div>
                <div>
                    <label class="block text-sm text-slate-300">Password</label>
                    <input name="password" type="password" required class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-cyan-500">
                </div>
                <button type="submit" class="w-full rounded-2xl bg-cyan-500 py-3 font-semibold text-slate-950 transition hover:bg-cyan-400">Sign in</button>
            </form>
            <div class="mt-8 text-center text-slate-400">
                <p>Need an account? Contact your admin for access.</p>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require dirname(__DIR__) . '/layout.php';
