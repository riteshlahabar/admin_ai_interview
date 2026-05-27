<!doctype html>
<html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Login</title>
<style>
body{margin:0;min-height:100vh;display:grid;place-items:center;background:#0a0f1c;color:#e5e7eb;font-family:system-ui,Segoe UI,Roboto,sans-serif}
.box{width:100%;max-width:420px;background:#0b1220;border:1px solid #1f2937;border-radius:12px;padding:18px}
.input{width:100%;padding:12px;border-radius:10px;background:#0b1220;border:1px solid #1f2937;color:#e5e7eb;margin-top:6px}
.btn{width:100%;padding:12px;border-radius:10px;background:#359EFF;border:0;color:#001427;margin-top:12px;font-weight:700}
.err{color:#ef4444;margin-top:6px}
</style>
</head><body>
<div class="box">
  <h2 style="margin:0 0 10px 0">Admin Login</h2>
  @if ($errors->any())
    <div class="err">{{ $errors->first() }}</div>
  @endif
  <form method="POST" action="{{ route('admin.login.post') }}">
    @csrf
    <label>Email</label>
    <input class="input" type="email" name="email" value="{{ old('email') }}" required>
    <label>Password</label>
    <input class="input" type="password" name="password" required>
    <button class="btn" type="submit">Sign in</button>
  </form>
</div>
</body></html>

