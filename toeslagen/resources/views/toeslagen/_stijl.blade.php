{{-- Extra opmaak voor de toeslag- en aanvraagpagina's. Gebruikt de kleuren uit de layout (Canva-ontwerp). --}}
<style>
.tegels{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:20px;margin:0 auto 40px}
.tegel{background:var(--card);border:1px solid var(--line);border-radius:14px;padding:24px;box-shadow:0 6px 24px rgba(5,27,64,.08);display:flex;flex-direction:column}
.icoon{width:64px;height:64px;border-radius:10px;background:var(--blue);display:grid;place-items:center;margin-bottom:14px}
.icoon svg{width:36px;height:36px;stroke:#fff;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
.tegel h2{margin-bottom:6px}
.tegel p{margin:0 0 12px;font-size:14px;line-height:1.5}
.voorwaarden{list-style:none;padding:0;margin:0 0 14px;font-size:14px}
.voorwaarden li{padding:5px 0 5px 24px;position:relative;line-height:1.4}
.voorwaarden li::before{content:"✓";position:absolute;left:0;color:var(--blue);font-weight:700}
.grens{font-size:13px;color:#5b6b85;margin-bottom:16px}
.tegel .btn{margin-top:auto;text-align:center;text-decoration:none;display:block}
.status-in_behandeling{background:#fff4d6;color:#8a5a00}
.status-goedgekeurd{background:#e2f4ea;color:var(--ok)}
.status-afgewezen{background:#fde8e6;color:var(--bad)}
.gegevens{width:100%;min-width:0;margin:6px 0 4px}
.gegevens th{background:none;color:var(--navy);width:45%;padding:8px 0;border-bottom:1px solid var(--line)}
.gegevens td{padding:8px 0}
.stappen{list-style:none;padding:0;margin:18px 0 0;display:flex;gap:6px}
.stappen li{flex:1;text-align:center;font-size:12px;font-weight:600;padding-top:30px;position:relative;color:#8a97ab}
.stappen li::before{content:"";position:absolute;top:0;left:50%;transform:translateX(-50%);width:22px;height:22px;border-radius:50%;background:var(--line)}
.stappen li.klaar{color:var(--navy)}
.stappen li.klaar::before{background:var(--blue)}
.stappen li.klaar.rood::before{background:var(--bad)}
.check{display:flex;gap:10px;align-items:flex-start;margin-top:14px;font-size:14px}
.check input{width:auto;margin-top:3px}
a.link{color:var(--blue);font-weight:600}
</style>
