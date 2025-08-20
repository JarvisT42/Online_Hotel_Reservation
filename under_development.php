<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Site Under Development</title>
  <meta name="description" content="This site is currently under development. Check back soon!" />
  <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ctext y='0.9em' font-size='90'%3E%F0%9F%9A%A7%3C/text%3E%3C/svg%3E" />
  <style>
    :root {
      --bg: #0b1020;
      --card: #121833;
      --muted: #aab2d5;
      --text: #e8ecff;
      --brand: #6ea8fe;
      --brand-2: #a17cf5;
      --ok: #7ee787;
      --warn: #ffdd57;
      --shadow: 0 20px 50px rgba(0,0,0,0.35), inset 0 1px 0 rgba(255,255,255,0.02);
      --radius-2xl: 24px;
    }
    * { box-sizing: border-box; }
    html, body { height: 100%; }
    body {
      margin: 0;
      font-family: "Inter", system-ui, -apple-system, Segoe UI, Roboto, Arial, "Apple Color Emoji", "Segoe UI Emoji";
      background: radial-gradient(1200px 800px at 20% 10%, #1c2450 0%, transparent 60%),
                  radial-gradient(1000px 800px at 90% 40%, #1a2a60 0%, transparent 55%),
                  var(--bg);
      color: var(--text);
      display: grid;
      place-items: center;
      padding: 32px;
    }
    .wrap {
      width: min(980px, 100%);
      display: grid;
      gap: 20px;
    }
    .card {
      background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.00)), var(--card);
      border: 1px solid rgba(255,255,255,0.06);
      border-radius: var(--radius-2xl);
      box-shadow: var(--shadow);
      padding: clamp(20px, 4vw, 36px);
    }
    .hero {
      display: grid;
      grid-template-columns: 1.2fr 1fr;
      gap: clamp(16px, 3vw, 28px);
      align-items: center;
    }
    @media (max-width: 900px) {
      .hero { grid-template-columns: 1fr; }
      .art { order: -1; }
    }
    h1 {
      margin: 0 0 6px 0;
      font-size: clamp(28px, 4.2vw, 44px);
      line-height: 1.1;
      letter-spacing: 0.2px;
    }
    .lead { color: var(--muted); margin: 0 0 18px 0; font-size: clamp(14px, 1.6vw, 16px); }

    .pill {
      display: inline-flex; align-items: center; gap: 10px;
      font-size: 14px; color: var(--text);
      padding: 8px 12px; border-radius: 999px; border: 1px solid rgba(255,255,255,0.08);
      background: linear-gradient(180deg, rgba(255,255,255,0.04), rgba(255,255,255,0.01));
    }
    .dot { width: 9px; height: 9px; border-radius: 50%; background: var(--warn); box-shadow: 0 0 10px var(--warn); }

    .progress {
      height: 12px; border-radius: 999px; background: rgba(255,255,255,0.07);
      position: relative; overflow: hidden; border: 1px solid rgba(255,255,255,0.08);
    }
    .bar {
      position: absolute; inset: 0; width: var(--pct, 38%);
      background: linear-gradient(90deg, var(--brand), var(--brand-2));
    }
    .bar::after {
      content: ""; position: absolute; inset: 0; background:
      linear-gradient(120deg, transparent 0%, rgba(255,255,255,0.35) 50%, transparent 100%);
      width: 120px; transform: translateX(-120px) skewX(-15deg);
      animation: slide 2s infinite; opacity: 0.6;
    }
    @keyframes slide { to { transform: translateX(120% ) skewX(-15deg);} }

    .row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; align-items: start; }
    @media (max-width: 700px){ .row { grid-template-columns: 1fr; } }

    ul.checklist { list-style: none; padding: 0; margin: 0; display: grid; gap: 10px; }
    .check {
      display: flex; align-items: flex-start; gap: 10px; padding: 10px 12px; border-radius: 14px;
      background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);
    }
    .check svg { flex: none; }
    .check small { color: var(--muted); display: block; }

   
    

    .art {
      width: 100%; aspect-ratio: 4/3; background: radial-gradient(60% 60% at 50% 35%, rgba(255,255,255,0.06), transparent 70%);
      border: 1px solid rgba(255,255,255,0.06); border-radius: var(--radius-2xl);
      display: grid; place-items: center; position: relative; overflow: hidden; box-shadow: var(--shadow);
    }
    .crane { position: absolute; width: 70%; max-width: 520px; }
    .cone { position: absolute; width: 70px; opacity: 0.9; }
    .cone.c1 { left: 14%; bottom: 16%; transform: rotate(-10deg); }
    .cone.c2 { right: 12%; bottom: 14%; transform: rotate(6deg); }
    .sparkle { position: absolute; width: 10px; height: 10px; border-radius: 50%; background: var(--ok); filter: blur(1px); animation: pop 2.8s infinite; }
    .sparkle:nth-child(1){ top: 16%; left: 20%; animation-delay: .2s; }
    .sparkle:nth-child(2){ top: 28%; right: 24%; animation-delay: 1.1s; }
    .sparkle:nth-child(3){ bottom: 26%; left: 40%; animation-delay: 1.8s; }
    @keyframes pop { 0%,100%{ transform: scale(.6); opacity:.5 } 50%{ transform: scale(1.4); opacity: 1 } }
  </style>
</head>
<body>
  <main class="wrap">
    <div class="card hero">
      <section>
        <span class="pill"><span class="dot"></span> Status: Under Development</span>
        <h1>We're building something awesome 🚧</h1>
        <p class="lead">This page (and the whole site) is currently in active development. New features and content are on the way. Thanks for your patience!</p>

        <div class="panel">
          <div aria-label="Overall progress" class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="38">
            <div class="bar" style="--pct: 38%"></div>
          </div>
          <div class="row">
            <ul class="checklist">
              <li class="check">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <div>
                  <strong>Core layout</strong>
                  <small>Navigation and responsive grid are in place.</small>
                </div>
              </li>
              <li class="check">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 3v18M3 12h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                <div>
                  <strong>Content</strong>
                  <small>Pages and copy are being polished.</small>
                </div>
              </li>
              <li class="check">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5-5 5 5M12 5v13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <div>
                  <strong>Infrastructure</strong>
                  <small>Deploy pipeline & performance tuning in progress.</small>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </section>
      <figure class="art">
        <svg class="crane" viewBox="0 0 600 400" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
          <defs>
            <linearGradient id="g1" x1="0" x2="1">
              <stop offset="0%" stop-color="#6ea8fe"/>
              <stop offset="100%" stop-color="#a17cf5"/>
            </linearGradient>
          </defs>
          <g fill="none" stroke="url(#g1)" stroke-width="6" stroke-linecap="round" stroke-linejoin="round">
            <path d="M40 360H560" opacity="0.5"/>
            <path d="M120 360L120 120 340 120 520 60"/>
            <path d="M120 200L260 200 420 160"/>
            <path d="M120 160L200 140 260 160 320 140 380 160 440 140" opacity="0.7"/>
            <path d="M520 60L520 220"/>
            <path d="M500 220H540"/>
            <rect x="505" y="220" width="30" height="40" rx="4"/>
            <path d="M520 260V320"/>
            <path d="M505 320H535"/>
            <path d="M160 360L220 240 280 360"/>
            <rect x="208" y="238" width="24" height="10" rx="2"/>
          </g>
          <g fill="url(#g1)">
            <rect x="340" y="82" width="60" height="30" rx="6"/>
            <rect x="405" y="72" width="36" height="20" rx="4"/>
          </g>
        </svg>
        <svg class="cone c1" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
          <path d="M30 6l22 48H8L30 6z" fill="#ff8a00"/>
          <rect x="12" y="38" width="36" height="8" fill="#fff" opacity="0.9"/>
        </svg>
        <svg class="cone c2" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
          <path d="M30 6l22 48H8L30 6z" fill="#ff8a00"/>
          <rect x="12" y="38" width="36" height="8" fill="#fff" opacity="0.9"/>
        </svg>
        <span class="sparkle" aria-hidden="true"></span>
        <span class="sparkle" aria-hidden="true"></span>
        <span class="sparkle" aria-hidden="true"></span>
      </figure>
    </div>

   
  </main>

  <script>
    document.getElementById('year').textContent = new Date().getFullYear();
  </script>
</body>
</html>
