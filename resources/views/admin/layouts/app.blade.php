<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>@yield('title','Admin')</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
/* minimal dark admin styles (sidebar + header) */
:root{--bg:#0f172a;--card:#0b1220;--text:#e5e7eb;--muted:#9ca3af;--primary:#359EFF;--border:#1f2937;}
*{box-sizing:border-box} body{margin:0;background:#0a0f1c;color:var(--text);font-family:system-ui,Segoe UI,Roboto,sans-serif}
.layout{display:grid;grid-template-columns:240px 1fr;min-height:100vh}
.aside{background:var(--bg);border-right:1px solid var(--border);padding:14px;position:sticky;top:0;height:100vh}
.aside .brand{font-weight:800;margin-bottom:12px}
.aside a{display:block;padding:10px 12px;border-radius:10px;color:var(--text);text-decoration:none}
.aside a.active,.aside a:hover{background:#0e1a2f;border:1px solid var(--primary)}
.header{background:#111827;border-bottom:1px solid var(--border);padding:10px 14px;display:flex;justify-content:space-between;align-items:center}
.main{padding:16px}
.card{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:14px;margin-bottom:12px}
.table{width:100%;border-collapse:collapse}
.table th,.table td{padding:10px;border-top:1px solid var(--border);text-align:left}
.input{background:#0b1220;border:1px solid var(--border);color:var(--text);border-radius:10px;padding:10px 12px;width:100%}
.btn{background:var(--primary);border:0;color:#001427;border-radius:10px;padding:10px 12px;cursor:pointer}
.btn.ghost{background:transparent;color:var(--text);border:1px solid var(--border)}
.badge{display:inline-block;padding:4px 8px;border-radius:9999px;border:1px solid var(--border);color:#cbd5e1}
@media(max-width:900px){.layout{grid-template-columns:1fr}.aside{position:fixed;transform:translateX(-100%);transition:.2s}.aside.open{transform:translateX(0)}}
</style>
@stack('head')
</head>
<body>
<div class="layout">
  <aside class="aside" id="aside">
    <div class="brand">Admin Panel</div>
    <nav>
      <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard')?'active':'' }}">🏠 Dashboard</a>
      <a href="{{ route('admin.students.index') }}" class="{{ request()->routeIs('admin.students.*')?'active':'' }}">👤 Students</a>
      <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*')?'active':'' }}">🏷️ Categories</a>
      <a href="{{ route('admin.questions.index') }}" class="{{ request()->routeIs('admin.questions.*')?'active':'' }}">❓ Questions</a>
      <a href="{{ route('admin.answers.index') }}" class="{{ request()->routeIs('admin.answers.*')?'active':'' }}">💬 Answers</a>
    </nav>
    <form method="POST" action="{{ route('admin.logout') }}" style="margin-top:12px">@csrf
      <button class="btn" style="width:100%">Logout</button>
    </form>
  </aside>
  <div>
    <header class="header">
      <button class="btn ghost" id="menuBtn">☰</button>
      <div>@yield('title','Admin')</div>
      <div>{{ auth()->user()->email ?? '' }}</div>
    </header>
    <main class="main">@yield('content')</main>
  </div>
</div>
<script>
document.getElementById('menuBtn')?.addEventListener('click',()=>document.getElementById('aside').classList.toggle('open'));
</script>
@stack('body')
</body>
</html>
