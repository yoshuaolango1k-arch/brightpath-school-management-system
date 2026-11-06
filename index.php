<?php
require_once __DIR__ . '/config/database.php';
session_start();

if (!empty($_SESSION['user_id'])) {
    $paths = [
        'admin' => 'admin/dashboard.php',
        'student' => 'students/dashboard.php',
        'instructor' => 'instructors/dashboard.php',
        'parent' => 'parents/dashboard.php'
    ];
    header('Location: ' . ($paths[$_SESSION['role']] ?? 'index.php'));
    exit;
}
$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome | BrightPath School</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com"><link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
  <style>body{font-family:'DM Sans',sans-serif}.display{font-family:'Manrope',sans-serif}</style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800">
  <main class="min-h-screen grid lg:grid-cols-2">
    <section class="relative hidden overflow-hidden bg-slate-900 p-12 text-white lg:flex lg:flex-col lg:justify-between">
      <div class="absolute -right-24 -top-24 h-96 w-96 rounded-full bg-teal-400/20 blur-3xl"></div>
      <div class="absolute -bottom-28 -left-20 h-96 w-96 rounded-full bg-blue-500/20 blur-3xl"></div>
      <a href="index.php" class="relative flex items-center gap-3 text-xl font-bold"><span class="grid h-11 w-11 place-items-center rounded-2xl bg-teal-400 text-slate-950">B</span> BrightPath School</a>
      <div class="relative max-w-xl">
        <p class="mb-5 inline-flex rounded-full border border-white/20 px-4 py-2 text-sm text-teal-200">A better way to stay connected</p>
        <h1 class="display text-5xl font-extrabold leading-tight xl:text-6xl">Every learner.<br><span class="text-teal-300">One bright</span> future.</h1>
        <p class="mt-6 max-w-md text-lg leading-8 text-slate-300">A simple school portal bringing students, families and educators together in one place.</p>
        <div class="mt-10 grid grid-cols-3 gap-3">
          <div class="rounded-2xl border border-white/10 bg-white/5 p-4"><div class="text-2xl font-bold">01</div><div class="mt-1 text-xs text-slate-300">Learn</div></div>
          <div class="rounded-2xl border border-white/10 bg-white/5 p-4"><div class="text-2xl font-bold">02</div><div class="mt-1 text-xs text-slate-300">Connect</div></div>
          <div class="rounded-2xl border border-white/10 bg-white/5 p-4"><div class="text-2xl font-bold">03</div><div class="mt-1 text-xs text-slate-300">Grow</div></div>
        </div>
      </div>
      <p class="relative text-sm text-slate-400">© <?= date('Y') ?> BrightPath School · School Management Portal</p>
    </section>

    <section class="flex items-center justify-center px-5 py-12 sm:px-10">
      <div class="w-full max-w-md">
        <div class="mb-10 lg:hidden"><a href="index.php" class="flex items-center gap-3 text-xl font-bold"><span class="grid h-10 w-10 place-items-center rounded-xl bg-teal-500 text-white">B</span> BrightPath School</a></div>
        <div class="mb-8">
          <p class="text-sm font-bold uppercase tracking-[.18em] text-teal-700">School portal</p>
          <h2 class="display mt-3 text-3xl font-extrabold tracking-tight">Welcome back</h2>
          <p class="mt-2 text-slate-500">Sign in with the email and password provided to you.</p>
        </div>
        <?php if ($error): ?><div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form action="login.php" method="POST" class="space-y-5">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ??= bin2hex(random_bytes(32))) ?>">
          <div><label for="email" class="mb-2 block text-sm font-semibold">Email address</label><input id="email" name="email" type="email" autocomplete="username" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 outline-none transition focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10" placeholder="you@example.com"></div>
          <div><div class="mb-2 flex justify-between"><label for="password" class="text-sm font-semibold">Password</label></div><input id="password" name="password" type="password" autocomplete="current-password" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 outline-none transition focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10" placeholder="Enter your password"></div>
          <button class="w-full rounded-xl bg-slate-900 px-5 py-3.5 font-bold text-white shadow-lg shadow-slate-900/10 transition hover:-translate-y-0.5 hover:bg-teal-700">Sign in <span aria-hidden="true">→</span></button>
        </form>
        <p class="mt-7 text-center text-sm text-slate-500">New student? <a class="font-bold text-teal-700 hover:underline" href="register.html">Create an account</a></p>
        <p class="mt-10 text-center text-xs leading-5 text-slate-400">For account support, please contact your school administrator.<br>Secure access · Role-based dashboards</p>
      </div>
    </section>
  </main>
</body></html>
