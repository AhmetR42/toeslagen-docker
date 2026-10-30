<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>NL Dienst Toeslagen</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
<style>
:root{--navy:#051b40;--blue:#1560a8;--bg:#fafafa;--card:#fff;--text:#1d2b45;--line:#d8e0ea;--ok:#1a7f4b;--bad:#b3261e}
*{box-sizing:border-box}
body{margin:0;background:var(--bg);color:var(--text);font-family:Montserrat,Arial,sans-serif;min-height:100vh;position:relative;overflow-x:hidden}
.wave{position:fixed;pointer-events:none;z-index:0}.w1{top:0;left:0;width:55vw;max-width:520px}.w2{bottom:0;right:0;width:50vw;max-width:480px}
header,main{position:relative;z-index:1;max-width:1000px;margin:0 auto;padding:0 20px}
header{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px;padding-top:24px;padding-bottom:12px}
.logo{display:flex;align-items:center;gap:10px;color:var(--navy);text-decoration:none;font-weight:500;line-height:1.15;letter-spacing:.5px;font-size:14px}
.dia{width:38px;height:38px;background:linear-gradient(135deg,#1e3fa0,#1a73c4);border-radius:9px;transform:rotate(45deg) scale(.8)}
nav{display:flex;flex-wrap:wrap;gap:6px}
nav a,nav button{font:600 14px Montserrat,Arial,sans-serif;color:var(--navy);background:var(--card);border:1px solid var(--line);padding:8px 14px;border-radius:8px;text-decoration:none;cursor:pointer}
nav a.on{background:var(--blue);color:#fff;border-color:var(--blue)}
h1{font-family:Poppins,Arial,sans-serif;font-weight:700;color:var(--navy);font-size:clamp(38px,9vw,72px);margin:28px 0 10px;text-align:center;letter-spacing:1px}
.band{display:table;margin:0 auto 28px;background:var(--blue);color:#fff;padding:8px 16px;font:600 12px Poppins,Arial,sans-serif;letter-spacing:2px;text-transform:uppercase;text-align:center;max-width:100%}
.card{background:var(--card);border:1px solid var(--line);border-radius:14px;padding:26px;max-width:440px;margin:0 auto 40px;box-shadow:0 6px 24px rgba(5,27,64,.08)}
.card.wide{max-width:100%;overflow-x:auto}
h2{font-family:Poppins,Arial,sans-serif;color:var(--navy);margin:0 0 16px;font-size:22px}
label{display:block;font-weight:600;font-size:13px;margin:12px 0 5px}
input,select{width:100%;padding:11px 12px;border:1px solid var(--line);border-radius:8px;background:var(--bg);color:var(--text);font:400 15px Montserrat,Arial,sans-serif}
.btn{margin-top:18px;width:100%;background:var(--blue);color:#fff;border:0;padding:12px;border-radius:8px;font:600 15px Montserrat,Arial,sans-serif;cursor:pointer}
.btn.sm{width:auto;margin:0;padding:6px 10px;font-size:13px}.btn.red{background:var(--bad)}.btn.grey{background:#6b7a90}
.msg{margin-top:14px;padding:10px 12px;border-radius:8px;font-size:14px}.err{background:#fde8e6;color:var(--bad)}.ok{background:#e2f4ea;color:var(--ok)}
.hint{font-size:13px;opacity:.8;margin-top:14px}.hint a{color:var(--blue);font-weight:600}
table{width:100%;border-collapse:collapse;font-size:14px;min-width:560px}
th{background:var(--blue);color:#fff;text-align:left;padding:10px}td{padding:10px;border-bottom:1px solid var(--line)}
td.c{text-align:center}.yes{color:var(--ok);font-weight:700}.no{color:var(--bad)}
.tag{display:inline-block;padding:2px 9px;border-radius:99px;font-size:12px;font-weight:600;background:var(--line)}
.row{display:flex;gap:6px;flex-wrap:wrap;align-items:center}
</style>
</head>
<body>
<svg class="wave w1" viewBox="0 0 520 300"><path d="M0 0H520C420 20 300 60 200 150C120 220 40 250 0 300Z" fill="#1560a8"/></svg>
<svg class="wave w2" viewBox="0 0 480 300"><path d="M480 0C470 120 400 170 300 220C220 260 130 280 0 300H480Z" fill="#1560a8"/></svg>
<header>
  <a class="logo" href="{{ route('home') }}"><span class="dia"></span><span>NL<br>DIENST<br>TOESLAGEN</span></a>
  <nav>
    @guest
      <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'on' : '' }}">Inloggen</a>
      <a href="{{ route('register') }}" class="{{ request()->routeIs('register') ? 'on' : '' }}">Account aanmaken</a>
    @endguest
    <a href="{{ route('rollen') }}" class="{{ request()->routeIs('rollen') ? 'on' : '' }}">Rollen &amp; rechten</a>
    @auth
      @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.*') ? 'on' : '' }}">Admin</a>
      @endif
      <form method="POST" action="{{ route('logout') }}" style="display:inline">@csrf<button>Uitloggen</button></form>
    @endauth
  </nav>
</header>
<main>
  <h1>Toeslagen</h1>
  <div class="band">Een bijdrage voor de kosten van zorgverzekering, kinderen, huur en kinderopvang</div>
  @if(session('ok'))<div class="msg ok" style="max-width:440px;margin:0 auto 20px">{{ session('ok') }}</div>@endif
  @yield('content')
  <p class="hint" style="text-align:center;margin-bottom:30px">Schoolproject – fictieve dienst, geen echte overheidswebsite.</p>
</main>
</body>
</html>
