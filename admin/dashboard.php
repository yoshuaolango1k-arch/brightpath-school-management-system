<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_role('admin');
$_SESSION['csrf_token'] ??= bin2hex(random_bytes(32));
$users = $pdo->query('SELECT id, full_name, email, role, created_at FROM users ORDER BY created_at DESC')->fetchAll();
$counts = ['student'=>0,'instructor'=>0,'parent'=>0,'admin'=>0];
foreach ($pdo->query('SELECT role, COUNT(*) total FROM users GROUP BY role') as $row) $counts[$row['role']] = (int)$row['total'];
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin Dashboard | BrightPath</title><script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script><link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@700;800&display=swap" rel="stylesheet"><style>body{font-family:'DM Sans',sans-serif}.display{font-family:'Manrope',sans-serif}</style></head>
<body class="min-h-screen bg-slate-50 text-slate-800">
<header class="border-b border-slate-200 bg-white"><div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4"><a href="#" class="flex items-center gap-3 font-bold"><span class="grid h-10 w-10 place-items-center rounded-xl bg-teal-600 text-white">B</span> BrightPath <span class="hidden text-slate-400 sm:inline">/ Admin</span></a><div class="flex items-center gap-3"><span class="hidden text-sm text-slate-500 sm:inline"><?= htmlspecialchars($_SESSION['full_name']) ?></span><a href="../logout.php" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold hover:bg-slate-50">Log out</a></div></div></header>
<main class="mx-auto max-w-7xl px-5 py-10">
  <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end"><div><p class="text-sm font-bold uppercase tracking-widest text-teal-700">Administration</p><h1 class="display mt-2 text-3xl font-extrabold">Good day, <?= htmlspecialchars(explode(' ', $_SESSION['full_name'])[0]) ?>.</h1><p class="mt-2 text-slate-500">Here is a quick overview of your school portal.</p></div><button id="openModal" class="rounded-xl bg-slate-900 px-5 py-3 font-bold text-white hover:bg-teal-700">+ Add a user</button></div>
  <section class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
  <?php foreach (['student'=>'Students','instructor'=>'Instructors','parent'=>'Parents','admin'=>'Administrators'] as $key=>$label): ?><div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm"><p class="text-sm text-slate-500"><?= $label ?></p><p class="display mt-2 text-3xl font-extrabold"><?= $counts[$key] ?></p></div><?php endforeach; ?>
  </section>
  <section class="mt-8 overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm"><div class="flex flex-col gap-3 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between"><div><h2 class="text-lg font-bold">Users</h2><p class="text-sm text-slate-500">Accounts registered in the school portal</p></div><input id="searchUsers" placeholder="Search name or email..." class="rounded-lg border border-slate-200 px-4 py-2 text-sm outline-none focus:border-teal-500"></div>
  <div class="overflow-x-auto"><table class="w-full min-w-[650px] text-left text-sm"><thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500"><tr><th class="px-5 py-4">Name</th><th class="px-5 py-4">Email</th><th class="px-5 py-4">Role</th><th class="px-5 py-4">Joined</th><th class="px-5 py-4">Action</th></tr></thead><tbody id="usersTable" class="divide-y divide-slate-100">
  <?php foreach ($users as $u): ?><tr class="user-row"><td class="px-5 py-4 font-semibold"><?= htmlspecialchars($u['full_name']) ?></td><td class="px-5 py-4 text-slate-500"><?= htmlspecialchars($u['email']) ?></td><td class="px-5 py-4"><span class="rounded-full bg-teal-50 px-3 py-1 text-xs font-bold capitalize text-teal-800"><?= htmlspecialchars($u['role']) ?></span></td><td class="px-5 py-4 text-slate-500"><?= htmlspecialchars(date('d M Y', strtotime($u['created_at']))) ?></td><td class="px-5 py-4"><?php if ((int)$u['id'] !== (int)$_SESSION['user_id']): ?><form action="../api/delete-user.php" method="POST" onsubmit="return confirm('Delete this account? This cannot be undone.');"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>"><input type="hidden" name="id" value="<?= (int)$u['id'] ?>"><button class="font-semibold text-red-600 hover:underline">Delete</button></form><?php else: ?><span class="text-xs text-slate-400">You</span><?php endif; ?></td></tr><?php endforeach; ?>
  </tbody></table></div></section>
</main>
<div id="userModal" class="fixed inset-0 z-20 hidden items-center justify-center bg-slate-950/50 p-4"><div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl"><div class="flex items-center justify-between"><div><h2 class="text-xl font-bold">Create a user account</h2><p class="text-sm text-slate-500">Add a student, instructor or parent.</p></div><button id="closeModal" class="rounded-lg px-3 py-2 text-xl text-slate-400 hover:bg-slate-100" aria-label="Close">×</button></div>
<form action="../api/add-user.php" method="POST" class="mt-6 space-y-4"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>"><div><label class="mb-1 block text-sm font-semibold">Full name</label><input name="full_name" required maxlength="100" class="w-full rounded-lg border p-3"></div><div><label class="mb-1 block text-sm font-semibold">Email</label><input name="email" type="email" required maxlength="190" class="w-full rounded-lg border p-3"></div><div><label class="mb-1 block text-sm font-semibold">Temporary password</label><input name="password" type="password" required minlength="8" class="w-full rounded-lg border p-3"><p class="mt-1 text-xs text-slate-400">Share this securely with the new user.</p></div><div><label class="mb-1 block text-sm font-semibold">User role</label><select name="role" required class="w-full rounded-lg border bg-white p-3"><option value="student">Student</option><option value="instructor">Instructor</option><option value="parent">Parent</option></select></div><div class="flex justify-end gap-3 pt-2"><button type="button" id="cancelModal" class="rounded-lg border px-4 py-3 font-semibold">Cancel</button><button class="rounded-lg bg-teal-700 px-5 py-3 font-bold text-white">Create account</button></div></form></div></div>
<script>
const modal=document.getElementById('userModal');
const show=()=>{modal.classList.remove('hidden');modal.classList.add('flex')};
const hide=()=>{modal.classList.add('hidden');modal.classList.remove('flex')};
document.getElementById('openModal').addEventListener('click',show);
['closeModal','cancelModal'].forEach(id=>document.getElementById(id).addEventListener('click',hide));
modal.addEventListener('click',e=>{if(e.target===modal)hide()});
document.addEventListener('keydown',e=>{if(e.key==='Escape')hide()});
document.getElementById('searchUsers').addEventListener('input',e=>{
 const term=e.target.value.toLowerCase();
 document.querySelectorAll('.user-row').forEach(row=>row.style.display=row.innerText.toLowerCase().includes(term)?'':'none');
});
</script></body></html>
