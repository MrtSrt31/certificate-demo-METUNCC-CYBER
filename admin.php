<?php 
// METU NCC Cyber — Certificate Command Center v8.1 
// Not: İleride veritabanı (DB) veya Session (Giriş) kontrollerini bu alana ekleyebilirsin.

$defaultPrefix = "METUNCC";
$currentYear = date("Y");
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>METU NCC Cyber | Certificate Command Center v8.1</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&family=JetBrains+Mono:wght@400;700&family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0;}
:root{
  --g:#10b981;--gd:#0d2419;--gb:#064e2b;
  --bg:#000;--s:#0d1117;--c:#161b22;--b:#30363d;
  --t:#fff;--m:#6b7280;--m2:#9ca3af;
}
body{background:var(--bg);color:var(--t);font-family:'Inter',sans-serif;
  background-image:radial-gradient(#1f2937 0.8px,transparent 0.8px);
  background-size:30px 30px;height:100vh;overflow:hidden;display:flex;}
::-webkit-scrollbar{width:4px;}::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:#30363d;border-radius:2px;}

.sidebar{width:380px;flex-shrink:0;height:100vh;overflow-y:auto;
  border-right:1px solid var(--b);background:rgba(13,17,23,.98);}

.ci{background:var(--s);border:1px solid var(--b);color:#fff;
  border-radius:4px;font-size:11px;padding:8px 10px;width:100%;transition:border-color .2s;}
.ci:focus{border-color:var(--g);outline:none;}

.st{font-size:9px;font-weight:700;color:var(--m);text-transform:uppercase;
  letter-spacing:.12em;margin-bottom:6px;display:block;}

.btn{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;
  border-radius:6px;padding:8px 12px;cursor:pointer;transition:all .15s;border:none;width:100%;}
.bg{background:transparent;border:1px solid var(--b);color:var(--m2);}
.bg:hover{background:#1f2937;color:#fff;}
.btn-g{background:var(--g);color:#000;}
.btn-g:hover{background:#34d399;}
.btn-d{background:var(--gd);border:1px solid var(--gb);color:var(--g);}
.btn-d:hover{background:var(--g);color:#000;}
.btn-r{background:transparent;border:1px solid #3f1515;color:#ef4444;}
.btn-r:hover{background:#3f1515;}

.tab-btn{font-size:9px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;
  padding:5px 9px;border-radius:4px;cursor:pointer;border:1px solid transparent;
  color:var(--m);background:transparent;transition:all .15s;}
.tab-btn.active{background:var(--c);border-color:var(--b);color:var(--g);}

.tpl{background:var(--s);border:1px solid var(--b);border-radius:8px;
  padding:8px 10px;cursor:pointer;transition:all .18s;position:relative;overflow:hidden;}
.tpl:hover{border-color:#374151;background:#111827;}
.tpl.sel{border-color:var(--g)!important;background:var(--gd);}
.tpl-badge{position:absolute;top:6px;right:6px;font-size:7px;font-weight:700;
  text-transform:uppercase;letter-spacing:.1em;padding:2px 5px;border-radius:3px;}
.tpl-prev{width:100%;height:34px;border-radius:3px;margin-bottom:5px;
  display:flex;align-items:center;justify-content:center;font-size:7px;
  text-transform:uppercase;letter-spacing:.12em;font-weight:700;overflow:hidden;}

.dropzone{border:1px dashed var(--b);border-radius:6px;padding:10px;text-align:center;
  cursor:pointer;transition:all .2s;font-size:10px;color:var(--m);position:relative;}
.dropzone:hover{border-color:var(--g);color:var(--g);}
.dropzone input{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;}

#propPanel{background:var(--s);border:1px solid var(--b);border-radius:8px;padding:10px;}
#qrPrev{background:var(--s);border:1px solid var(--b);border-radius:8px;padding:8px;
  display:flex;align-items:center;gap:8px;}

#mainArea{flex:1;display:flex;align-items:center;justify-content:center;
  padding:20px;overflow:hidden;min-width:0;}
#wrap{position:relative;flex-shrink:0;
  box-shadow:0 0 0 1px #30363d,0 20px 50px rgba(0,0,0,.8);}
#wrap canvas{display:block;}
#gOverlay{position:absolute;top:0;left:0;pointer-events:none;z-index:999;}

#certIdBox{font-family:'JetBrains Mono';font-size:10px;color:var(--g);
  background:var(--gd);border:1px solid var(--gb);
  padding:6px 10px;border-radius:4px;word-break:break-all;letter-spacing:.04em;}

.sdiv{border:none;border-top:1px solid #1a2030;margin:2px 0;}
#tplGrid{display:grid;grid-template-columns:1fr 1fr;gap:6px;}

.color-preset{width:24px;height:24px;border-radius:4px;cursor:pointer;border:2px solid transparent;transition:all .15s;}
.color-preset:hover{border-color:#fff;transform:scale(1.1);}
.color-preset.active{border-color:var(--g);}
</style>
</head>
<body>

<div class="sidebar p-4 flex flex-col gap-4">

  <div style="border-bottom:1px solid #1a2030;padding-bottom:12px;">
    <div style="color:var(--g);font-size:11px;font-weight:900;letter-spacing:.2em;text-transform:uppercase;">
      Command Center <span style="opacity:.35;">v8.1</span></div>
    <div style="font-size:9px;opacity:.3;font-family:'JetBrains Mono';margin-top:3px;">
      METU NCC CYBER · CERT PRODUCTION</div>
  </div>

  <div class="flex gap-1 flex-wrap">
    <button class="tab-btn active" onclick="switchTab('design',this)">Tasarım</button>
    <button class="tab-btn" onclick="switchTab('colors',this)">Renkler</button>
    <button class="tab-btn" onclick="switchTab('assets',this)">Varlıklar</button>
    <button class="tab-btn" onclick="switchTab('layers',this)">Katmanlar</button>
    <button class="tab-btn" onclick="switchTab('bulk',this)">Toplu Üretim</button>
  </div>

  <div id="tab-design" class="flex flex-col gap-3">

    <div>
      <span class="st">Arka Plan (opsiyonel)</span>
      <div class="dropzone" style="height:36px;font-size:9px;">
        📂 PNG/JPG yükle
        <input type="file" id="bgInput" accept="image/*">
      </div>
    </div>

    <hr class="sdiv">

    <div>
      <span class="st">Akıllı Layout Şablonu — 6 seçenek</span>
      <div id="tplGrid">

        <div class="tpl" id="t-achievement" onclick="applyLayout('achievement',this)">
          <div class="tpl-badge" style="background:#78350f;color:#fcd34d;">🏆</div>
          <div class="tpl-prev" style="background:#0d0a00;border:1px solid #78350f;">
            <span style="color:#d4af37;font-family:'Playfair Display',serif;">ACHIEVEMENT</span></div>
          <div style="font-size:10px;color:#d1d5db;font-weight:600;">Başarı Belgesi</div>
          <div style="font-size:8px;color:#6b7280;margin-top:2px;">Altın · 2 imza · Mühür</div>
        </div>

        <div class="tpl" id="t-participation" onclick="applyLayout('participation',this)">
          <div class="tpl-badge" style="background:#0c4a6e;color:#7dd3fc;">📋</div>
          <div class="tpl-prev" style="background:#030d1a;border:1px solid #1e3a5f;">
            <span style="color:#7dd3fc;font-family:'Space Grotesk',sans-serif;">PARTICIPATION</span></div>
          <div style="font-size:10px;color:#d1d5db;font-weight:600;">Katılım Belgesi</div>
          <div style="font-size:8px;color:#6b7280;margin-top:2px;">Mavi · Grid · Rozet</div>
        </div>

        <div class="tpl" id="t-training" onclick="applyLayout('training',this)">
          <div class="tpl-badge" style="background:#14532d;color:#86efac;">🖥️</div>
          <div class="tpl-prev" style="background:#050f0a;border:1px solid #14532d;">
            <span style="color:#10b981;font-family:'JetBrains Mono',monospace;">TRAINING</span></div>
          <div style="font-size:10px;color:#d1d5db;font-weight:600;">Eğitim Sertifikası</div>
          <div style="font-size:8px;color:#6b7280;margin-top:2px;">Terminal · Cyber · Yeşil</div>
        </div>

        <div class="tpl" id="t-sponsored" onclick="applyLayout('sponsored',this)">
          <div class="tpl-badge" style="background:#4a044e;color:#e879f9;">🤝</div>
          <div class="tpl-prev" style="background:#0d0014;border:1px solid #6b21a8;">
            <span style="color:#e879f9;">SPONSORED</span></div>
          <div style="font-size:10px;color:#d1d5db;font-weight:600;">Sponsorlu Etkinlik</div>
          <div style="font-size:8px;color:#6b7280;margin-top:2px;">Mor · 4 sponsor · Logo alan</div>
        </div>

        <div class="tpl" id="t-official" onclick="applyLayout('official',this)">
          <div class="tpl-badge" style="background:#1e1b4b;color:#a5b4fc;">✍️</div>
          <div class="tpl-prev" style="background:#08080f;border:1px solid #312e81;">
            <span style="color:#a5b4fc;font-family:'Playfair Display',serif;">OFFICIAL</span></div>
          <div style="font-size:10px;color:#d1d5db;font-weight:600;">İmzalı Resmi Belge</div>
          <div style="font-size:8px;color:#6b7280;margin-top:2px;">2 imza · Mühür · İndigo</div>
        </div>

        <div class="tpl" id="t-minimal" onclick="applyLayout('minimal',this)">
          <div class="tpl-badge" style="background:#1c1c1c;color:#e5e5e5;">◼</div>
          <div class="tpl-prev" style="background:#111;border:1px solid #333;">
            <span style="color:#fff;font-family:'Space Grotesk',sans-serif;letter-spacing:.3em;">MINIMAL</span></div>
          <div style="font-size:10px;color:#d1d5db;font-weight:600;">Minimalist</div>
          <div style="font-size:8px;color:#6b7280;margin-top:2px;">B&amp;W · Typographic · Şık</div>
        </div>

      </div>
    </div>

    <hr class="sdiv">

    <div>
      <span class="st">Yazı Ekle</span>
      <input id="addTextVal" class="ci mb-1" placeholder="Metin içeriği...">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-bottom:6px;">
        <input id="addTextSize" type="number" value="60" class="ci" placeholder="Font px">
        <input id="addTextColor" type="color" value="#ffffff"
          style="height:34px;width:100%;border-radius:4px;border:1px solid var(--b);cursor:pointer;padding:2px;background:transparent;">
      </div>
      <button class="btn bg" onclick="addCustomText()">+ Yazı Ekle</button>
    </div>

    <hr class="sdiv">

    <div>
      <span class="st">QR Doğrulama Kodu</span>
      <input id="qrUrl" class="ci mb-2" placeholder="https://verify.example.com/?id=">
      <div id="qrPrev">
        <canvas id="qrCanvas" width="54" height="54"
          style="border-radius:3px;background:#000;image-rendering:pixelated;flex-shrink:0;"></canvas>
        <div>
          <div style="font-size:9px;color:#9ca3af;">Canvas'ta sürükleyerek konumlandır</div>
          <div id="qrStatus" style="font-size:8px;color:var(--g);margin-top:3px;">—</div>
        </div>
      </div>
      <button class="btn bg mt-2" onclick="addQRToCanvas()">+ QR'ı Ekle</button>
    </div>

    <hr class="sdiv">

    <div id="propPanel">
      <span class="st" style="color:var(--g);">Seçili Nesne</span>
      <div id="propContent" style="font-size:10px;color:#6b7280;">Bir nesneye tıkla…</div>
    </div>

  </div>

  <div id="tab-colors" class="hidden flex-col gap-3">
    
    <div>
      <span class="st">Renk Paletleri — Tek Tık</span>
      <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:6px;margin-bottom:8px;">
        <div class="color-preset" style="background:#d4af37;" onclick="applyColorPreset('gold')" title="Altın"></div>
        <div class="color-preset" style="background:#0ea5e9;" onclick="applyColorPreset('blue')" title="Mavi"></div>
        <div class="color-preset" style="background:#10b981;" onclick="applyColorPreset('green')" title="Yeşil"></div>
        <div class="color-preset" style="background:#c084fc;" onclick="applyColorPreset('purple')" title="Mor"></div>
        <div class="color-preset" style="background:#6366f1;" onclick="applyColorPreset('indigo')" title="İndigo"></div>
        <div class="color-preset" style="background:#ef4444;" onclick="applyColorPreset('red')" title="Kırmızı"></div>
        <div class="color-preset" style="background:#f59e0b;" onclick="applyColorPreset('orange')" title="Turuncu"></div>
        <div class="color-preset" style="background:#ec4899;" onclick="applyColorPreset('pink')" title="Pembe"></div>
        <div class="color-preset" style="background:#14b8a6;" onclick="applyColorPreset('teal')" title="Turkuaz"></div>
        <div class="color-preset" style="background:#fff;" onclick="applyColorPreset('white')" title="Beyaz"></div>
      </div>
      <div style="font-size:8px;color:#6b7280;line-height:1.5;">
        ℹ️ Palette tıkla → Tüm renkli nesneler otomatik değişir (çerçeveler, yazılar, çizgiler)
      </div>
    </div>

    <hr class="sdiv">

    <div>
      <span class="st">Manuel Renk Değiştir</span>
      <div style="font-size:9px;color:#9ca3af;margin-bottom:8px;">
        1️⃣ Canvas'ta değiştirmek istediğin nesneyi seç<br>
        2️⃣ Aşağıdaki renk seçiciyi kullan
      </div>
      <div style="display:flex;gap:6px;align-items:center;">
        <input type="color" id="manualColor" value="#10b981"
          style="width:50px;height:38px;border-radius:4px;border:1px solid var(--b);cursor:pointer;padding:2px;">
        <button class="btn bg" style="flex:1;" onclick="applyManualColor()">Seçili Nesneye Uygula</button>
      </div>
    </div>

    <hr class="sdiv">

    <div>
      <span class="st">Toplu Renk Değiştir</span>
      <div style="font-size:9px;color:#9ca3af;margin-bottom:8px;">
        Belirli bir renkteki tüm nesneleri değiştir
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-bottom:6px;">
        <div>
          <label style="font-size:8px;color:#6b7280;display:block;margin-bottom:3px;">Eski Renk</label>
          <input type="color" id="oldColorBulk" value="#d4af37"
            style="width:100%;height:34px;border-radius:4px;border:1px solid var(--b);cursor:pointer;padding:2px;">
        </div>
        <div>
          <label style="font-size:8px;color:#6b7280;display:block;margin-bottom:3px;">Yeni Renk</label>
          <input type="color" id="newColorBulk" value="#10b981"
            style="width:100%;height:34px;border-radius:4px;border:1px solid var(--b);cursor:pointer;padding:2px;">
        </div>
      </div>
      <button class="btn bg" onclick="bulkColorReplace()">🔄 Toplu Değiştir</button>
    </div>

  </div>

  <div id="tab-assets" class="hidden flex-col gap-3">

    <div>
      <span class="st">Kurum / Etkinlik Logosu</span>
      <div class="dropzone mb-2" style="height:44px;">
        🏛️ Logo (PNG şeffaf arka plan önerilir)
        <input type="file" id="logoInput" accept="image/*" onchange="uploadAsset('logo',this)">
      </div>
      <div id="logoPrev" style="display:none;font-size:9px;color:var(--g);margin-bottom:5px;">✓ Logo yüklendi</div>
      <button class="btn bg" onclick="placeAsset('logo',200,80,800,100,'layerLogo')">Canvas Ortasına Yerleştir</button>
    </div>

    <hr class="sdiv">

    <div>
      <span class="st">1. İmza — Sol Alan</span>
      <div class="dropzone mb-2" style="height:44px;">
        ✍️ İmza 1 (şeffaf PNG)
        <input type="file" id="sig1Input" accept="image/*" onchange="uploadAsset('sig1',this)">
      </div>
      <div id="sig1Prev" style="display:none;font-size:9px;color:var(--g);margin-bottom:5px;">✓ İmza 1 yüklendi</div>
      <button class="btn bg" onclick="placeAsset('sig1',200,60,285,760,'layerSig1')">Sol İmza Alanına Yerleştir</button>
    </div>

    <hr class="sdiv">

    <div>
      <span class="st">2. İmza — Sağ Alan</span>
      <div class="dropzone mb-2" style="height:44px;">
        ✍️ İmza 2 (şeffaf PNG)
        <input type="file" id="sig2Input" accept="image/*" onchange="uploadAsset('sig2',this)">
      </div>
      <div id="sig2Prev" style="display:none;font-size:9px;color:var(--g);margin-bottom:5px;">✓ İmza 2 yüklendi</div>
      <button class="btn bg" onclick="placeAsset('sig2',200,60,1315,760,'layerSig2')">Sağ İmza Alanına Yerleştir</button>
    </div>

    <hr class="sdiv">

    <div>
      <span class="st">Genel Resim Ekle</span>
      <div class="dropzone mb-2" style="height:44px;">
        📂 Herhangi bir PNG/JPG
        <input type="file" id="imgInput" accept="image/*">
      </div>
      <button class="btn bg" onclick="addCustomImage()">+ Canvas'a Ekle</button>
    </div>

    <hr class="sdiv">

    <div>
      <span class="st">Canvas'ı Dışa Aktar</span>
      <button class="btn btn-d" onclick="exportCanvas()">💾 PNG Olarak İndir</button>
    </div>

  </div>

  <div id="tab-layers" class="hidden flex-col gap-2">
    <span class="st">Katman Listesi (üst = önde)</span>
    <div id="layerList" class="flex flex-col gap-1" style="font-size:10px;"></div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-top:4px;">
      <button class="btn bg" onclick="moveUp()">↑ Öne Al</button>
      <button class="btn bg" onclick="moveDown()">↓ Arkaya At</button>
    </div>
    <button class="btn bg" onclick="refreshLayers()">↺ Yenile</button>
  </div>

  <div id="tab-bulk" class="hidden flex-col gap-3">

    <div>
      <span class="st">Katılımcı Listesi (her satır bir isim)</span>
      <textarea id="bulkNames" rows="6" class="ci"
        placeholder="Ahmet Yılmaz&#10;Ayşe Demir"></textarea>
    </div>

    <hr class="sdiv">

    <div>
      <span class="st">Güvenli Sertifika ID — Stokastik</span>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-bottom:6px;">
        <div>
          <label style="font-size:8px;color:#6b7280;display:block;margin-bottom:3px;">Prefix</label>
          <input id="prefix" value="<?php echo htmlspecialchars($defaultPrefix); ?>" class="ci">
        </div>
        <div>
          <label style="font-size:8px;color:#6b7280;display:block;margin-bottom:3px;">Yıl</label>
          <input id="certYear" value="<?php echo htmlspecialchars($currentYear); ?>" class="ci">
        </div>
      </div>
      <label style="font-size:8px;color:#6b7280;display:block;margin-bottom:3px;">Örnek çıktı:</label>
      <div id="certIdBox">—</div>
      <div style="font-size:8px;color:#374151;margin-top:4px;line-height:1.5;">
        Format: PREFIX-YIL-XXXXXXXX-XXXX<br>
        48-bit crypto · ~281 trilyon kombinasyon<br>
        Her sertifika tamamen bağımsız &amp; tahmin edilemez
      </div>
      <button class="btn bg mt-2" style="font-size:9px;" onclick="previewCertId()">↺ Yeni Örnek</button>
    </div>

    <hr class="sdiv">

    <div>
      <span class="st">Etkinlik Adı</span>
      <input id="eventTag" value="Cyber Workshop <?php echo htmlspecialchars($currentYear); ?>" class="ci">
    </div>

    <div id="bulkProgress" style="display:none;">
      <div style="font-size:9px;color:#9ca3af;margin-bottom:4px;" id="bulkProgressText">Hazırlanıyor…</div>
      <div style="background:#1f2937;border-radius:999px;height:5px;">
        <div id="bulkProgressBar" style="background:var(--g);height:5px;border-radius:999px;
          transition:width .25s;width:0%;"></div>
      </div>
    </div>

    <button class="btn btn-g" onclick="startBulkProduction()">▶ TÜMÜNÜ BAS &amp; İNDİR</button>
    <div style="font-size:8px;color:#374151;text-align:center;line-height:1.6;">
      Her kişiye ayrı PNG · Benzersiz QR kodu<br>Stokastik cert ID · Başkasınınki tahmin edilemez
    </div>

  </div>

</div>

<div id="mainArea">
  <div id="wrap">
    <canvas id="fCanvas"></canvas>
    <svg id="gOverlay" style="display:none;position:absolute;top:0;left:0;pointer-events:none;">
      <line id="gH" x1="0" y1="50%" x2="100%" y2="50%"
        stroke="#10b981" stroke-width="0.5" stroke-dasharray="4,3" opacity="0.9"/>
      <line id="gV" x1="50%" y1="0" x2="50%" y2="100%"
        stroke="#10b981" stroke-width="0.5" stroke-dasharray="4,3" opacity="0.9"/>
    </svg>
  </div>
</div>

<script>
// ════════════════════════════════════
//  INIT
// ════════════════════════════════════
const CW=1600,CH=900;
const fCanvas=new fabric.Canvas('fCanvas',{
  width:CW,height:CH,backgroundColor:'#0d1117',
  preserveObjectStacking:true,stopContextMenu:true,fireRightClick:true,
});
let qrObj=null;
const assets={logo:null,sig1:null,sig2:null};

// ════════════════════════════════════
//  STOCHASTIC CERT-ID
// ════════════════════════════════════
function genCertId(prefix,year){
  const b=new Uint8Array(6);
  crypto.getRandomValues(b);
  const h=Array.from(b).map(x=>x.toString(16).padStart(2,'0')).join('').toUpperCase();
  return `${prefix}-${year}-${h.slice(0,8)}-${h.slice(8,12)}`;
}
function previewCertId(){
  document.getElementById('certIdBox').textContent=
    genCertId(document.getElementById('prefix').value||'CERT',
              document.getElementById('certYear').value||'2026');
}
setTimeout(previewCertId,300);

// ════════════════════════════════════
//  RESIZE
// ════════════════════════════════════
function resize(){
  const a=document.getElementById('mainArea');
  const sc=Math.min((a.clientWidth-40)/CW,(a.clientHeight-40)/CH);
  const dW=Math.floor(CW*sc),dH=Math.floor(CH*sc);
  fCanvas.setDimensions({width:dW,height:dH});
  fCanvas.setViewportTransform([sc,0,0,sc,0,0]);
  fCanvas.renderAll();
  const w=document.getElementById('wrap');
  w.style.width=dW+'px';w.style.height=dH+'px';
  const ov=document.getElementById('gOverlay');
  ov.setAttribute('width',dW);ov.setAttribute('height',dH);
  ov.style.width=dW+'px';ov.style.height=dH+'px';
}
window.addEventListener('resize',resize);
setTimeout(resize,100);

// ════════════════════════════════════
//  TABS
// ════════════════════════════════════
function switchTab(name,btn){
  ['design','colors','assets','layers','bulk'].forEach(t=>{
    const el=document.getElementById('tab-'+t);
    el.classList.add('hidden');el.classList.remove('flex');
  });
  const el=document.getElementById('tab-'+name);
  el.classList.remove('hidden');el.classList.add('flex');
  document.querySelectorAll('.tab-btn').forEach(b=>b.classList.remove('active'));
  btn.classList.add('active');
  if(name==='layers')refreshLayers();
  if(name==='bulk')previewCertId();
}

// ════════════════════════════════════
//  BACKGROUND
// ════════════════════════════════════
document.getElementById('bgInput').onchange=e=>{
  const r=new FileReader();
  r.onload=f=>{fabric.Image.fromURL(f.target.result,img=>{
    img.selectable=false;img.evented=false;
    const sc=Math.max(CW/img.width,CH/img.height);
    img.set({scaleX:sc,scaleY:sc,left:(CW-img.width*sc)/2,top:(CH-img.height*sc)/2});
    fCanvas.setBackgroundImage(img,fCanvas.renderAll.bind(fCanvas));
  });};
  r.readAsDataURL(e.target.files[0]);
};

// ════════════════════════════════════
//  ASSET UPLOAD & PLACE
// ════════════════════════════════════
function uploadAsset(key,input){
  const file=input.files[0];if(!file)return;
  const r=new FileReader();
  r.onload=f=>{
    assets[key]=f.target.result;
    const p=document.getElementById(key+'Prev');
    if(p)p.style.display='block';
  };
  r.readAsDataURL(file);
}
function placeAsset(key,tw,th,cx,cy,layerName){
  if(!assets[key]){alert('Önce '+key+' dosyasını yükle.');return;}
  const old=fCanvas.getObjects().find(o=>o.name===layerName);
  if(old)fCanvas.remove(old);
  fabric.Image.fromURL(assets[key],img=>{
    const sc=Math.min(tw/img.width,th/img.height);
    img.set({left:cx,top:cy,scaleX:sc,scaleY:sc,
      name:layerName,originX:'center',originY:'center'});
    fCanvas.add(img);fCanvas.setActiveObject(img);
    fCanvas.requestRenderAll();refreshLayers();
  });
}

// ════════════════════════════════════
//  FABRIC HELPERS — TÜM ELEMANLAR EDİTABLE
// ════════════════════════════════════
const mkT=(txt,o)=>new fabric.IText(txt,Object.assign(
  {fontFamily:'Inter',fontWeight:'700',fill:'#fff',originX:'center'},o));

// ARTIK TÜM ŞEKİLLER SEÇİLEBİLİR & SİLİNEBİLİR
const mkR=(o)=>new fabric.Rect(Object.assign(
  {fill:'transparent',stroke:'#fff',strokeWidth:2},o));

const mkL=(x1,y1,x2,y2,o)=>new fabric.Line([x1,y1,x2,y2],Object.assign(
  {stroke:'#fff',strokeWidth:1},o));

const mkC=(o)=>new fabric.Circle(Object.assign(
  {fill:'transparent',stroke:'#fff',strokeWidth:2},o));

function mkCorners(x,y,w,h,col,sz=50,sw=2){
  return[[x,y,x+sz,y],[x,y,x,y+sz],[x+w,y,x+w-sz,y],[x+w,y,x+w,y+sz],
    [x,y+h,x+sz,y+h],[x,y+h,x,y+h-sz],[x+w,y+h,x+w-sz,y+h],[x+w,y+h,x+w,y+h-sz]]
    .map(p=>new fabric.Line(p,{stroke:col,strokeWidth:sw}));
}

// Logo placeholder helper — artık silme iznimiz var
function mkLogoPlaceholder(x,y,w,h,label){
  const g=new fabric.Group([
    new fabric.Rect({left:-w/2,top:-h/2,width:w,height:h,
      fill:'rgba(255,255,255,0.02)',stroke:'#374151',strokeWidth:1,strokeDashArray:[4,4],rx:4,ry:4}),
    new fabric.IText(label,{left:0,top:0,fontSize:11,fill:'#6b7280',
      fontFamily:'Inter',originX:'center',originY:'center'})
  ],{left:x,top:y,selectable:true,evented:true,name:'logoPlaceholder'});
  return g;
}

const clearLayers=()=>{fCanvas.getObjects().forEach(o=>fCanvas.remove(o));qrObj=null;};
const today=()=>new Date().toLocaleDateString('tr-TR',{year:'numeric',month:'long',day:'numeric'});

// ════════════════════════════════════
//  LAYOUT 1: BAŞARI BELGESİ (Altın)
// ════════════════════════════════════
function achievement(){
  fCanvas.setBackgroundColor('#0a0700',fCanvas.renderAll.bind(fCanvas));
  fCanvas.add(
    mkR({left:32,top:32,width:CW-64,height:CH-64,stroke:'#b8860b',strokeWidth:3,rx:3,ry:3,name:'border1'}),
    mkR({left:52,top:52,width:CW-104,height:CH-104,stroke:'#d4af37',strokeWidth:.8,name:'border2'}),
    ...mkCorners(32,32,CW-64,CH-64,'#d4af37',70,2.5).map((l,i)=>Object.assign(l,{name:'corner'+i})),
    mkL(180,180,CW-180,180,{stroke:'#d4af37',strokeWidth:.8,opacity:.5,name:'line1'}),
    mkL(180,CH-180,CW-180,CH-180,{stroke:'#d4af37',strokeWidth:.8,opacity:.5,name:'line2'}),
    mkC({left:748,top:85,radius:52,stroke:'#d4af37',strokeWidth:2,name:'circle1'}),
    mkC({left:762,top:99,radius:38,stroke:'#b8860b',strokeWidth:1,name:'circle2'}),
    mkT('★',{left:800,top:137,fontSize:34,fill:'#d4af37',fontFamily:'serif',
      originX:'center',originY:'center',name:'star'}),
    mkLogoPlaceholder(800,100,280,90,'🏛️ LOGO YERLEŞTİR'),
    mkT('CERTIFICATE OF ACHIEVEMENT',{left:800,top:220,fontSize:22,
      fontFamily:'Playfair Display',fontWeight:'900',fill:'#d4af37',letterSpacing:5,name:'layerTitle'}),
    mkT('Bu belge aşağıdaki kişiye takdim edilmiştir',{left:800,top:266,fontSize:16,
      fontFamily:'Inter',fontWeight:'300',fill:'#9ca3af',name:'layerSub'}),
    mkT('KATILIMCI ADI',{left:800,top:374,fontSize:82,
      fontFamily:'Playfair Display',fontWeight:'700',fill:'#fff',name:'layerName'}),
    mkT('Cyber Security Workshop',{left:800,top:488,fontSize:30,
      fontFamily:'Inter',fontWeight:'600',fill:'#d4af37',name:'layerEvent'}),
    mkT('programını başarıyla tamamlamış olup bu belgeye layık görülmüştür.',{
      left:800,top:540,fontSize:15,fontFamily:'Inter',fontWeight:'300',fill:'#9ca3af',name:'layerDesc'}),
    mkT(today(),{left:800,top:594,fontSize:13,fontFamily:'JetBrains Mono',fill:'#6b7280',name:'layerDate'}),
    // İmza 1
    mkL(118,CH-120,435,CH-120,{stroke:'#d4af37',strokeWidth:1,name:'sigLine1'}),
    mkT('Prof. Dr. İsim Soyisim',{left:276,top:CH-108,fontSize:12,
      fontFamily:'Playfair Display',fontStyle:'italic',fill:'#d4af37',opacity:.7,
      originX:'center',name:'sig1Name'}),
    mkT('PROGRAM DİREKTÖRÜ',{left:276,top:CH-88,fontSize:8,
      fontFamily:'JetBrains Mono',letterSpacing:2,fill:'#b8860b',
      originX:'center',name:'sig1Title'}),
    // İmza 2
    mkL(CW-435,CH-120,CW-118,CH-120,{stroke:'#d4af37',strokeWidth:1,name:'sigLine2'}),
    mkT('Prof. Dr. İsim Soyisim',{left:CW-276,top:CH-108,fontSize:12,
      fontFamily:'Playfair Display',fontStyle:'italic',fill:'#d4af37',opacity:.7,
      originX:'center',name:'sig2Name'}),
    mkT('BÖLÜM BAŞKANI',{left:CW-276,top:CH-88,fontSize:8,
      fontFamily:'JetBrains Mono',letterSpacing:2,fill:'#b8860b',
      originX:'center',name:'sig2Title'}),
    // Mühür
    mkC({left:755,top:CH-122,radius:48,stroke:'#d4af37',strokeWidth:1,fill:'rgba(212,175,55,.05)',name:'seal'}),
    mkT('MÜHÜR',{left:800,top:CH-77,fontSize:9,fontFamily:'JetBrains Mono',
      fill:'#d4af37',opacity:.3,originX:'center',name:'sealText'}),
    mkT('CERT_ID',{left:CW-70,top:CH-42,fontSize:11,
      fontFamily:'JetBrains Mono',fill:'#d4af37',opacity:.4,originX:'right',name:'layerID'}),
  );
}

// ════════════════════════════════════
//  LAYOUT 2: KATILIM BELGESİ (Mavi)
// ════════════════════════════════════
function participation(){
  fCanvas.setBackgroundColor('#020c1b',fCanvas.renderAll.bind(fCanvas));
  for(let i=0;i<16;i++){
    fCanvas.add(mkL(0,i*60,CW,i*60,{stroke:'#061a30',strokeWidth:.5,name:'gridH'+i}));
    fCanvas.add(mkL(i*107,0,i*107,CH,{stroke:'#061a30',strokeWidth:.5,name:'gridV'+i}));
  }
  fCanvas.add(
    new fabric.Rect({left:0,top:0,width:10,height:CH,fill:'#0ea5e9',name:'accent1'}),
    new fabric.Rect({left:CW-10,top:0,width:10,height:CH,fill:'#0ea5e9',name:'accent2'}),
    new fabric.Rect({left:10,top:0,width:CW-20,height:5,fill:'#0284c7',name:'accent3'}),
    mkC({left:726,top:65,radius:74,stroke:'#0ea5e9',strokeWidth:2,fill:'#030d1a',name:'badge1'}),
    mkC({left:740,top:79,radius:60,stroke:'#0284c7',strokeWidth:1,name:'badge2'}),
    mkLogoPlaceholder(800,139,240,80,'🏛️ LOGO / ROZET'),
    mkT('CERTIFICATE OF PARTICIPATION',{left:800,top:212,fontSize:20,
      fontFamily:'Space Grotesk',fontWeight:'700',fill:'#0ea5e9',letterSpacing:4,name:'layerTitle'}),
    mkL(240,248,CW-240,248,{stroke:'#0ea5e9',strokeWidth:1,opacity:.3,name:'divider'}),
    mkT('Katılım belgesi aşağıdaki kişiye verilmiştir',{left:800,top:272,fontSize:15,
      fontFamily:'Inter',fontWeight:'300',fill:'#94a3b8',name:'layerSub'}),
    mkT('KATILIMCI ADI',{left:800,top:365,fontSize:78,
      fontFamily:'Space Grotesk',fontWeight:'700',fill:'#f0f9ff',name:'layerName'}),
    mkT('Cyber Workshop',{left:800,top:470,fontSize:28,
      fontFamily:'Space Grotesk',fontWeight:'500',fill:'#7dd3fc',name:'layerEvent'}),
    mkT(today(),{left:800,top:522,fontSize:13,fontFamily:'JetBrains Mono',fill:'#334155',name:'layerDate'}),
    new fabric.Rect({left:628,top:618,width:344,height:52,fill:'#0c2d4a',
      stroke:'#0ea5e9',strokeWidth:1,rx:4,ry:4,name:'verifyBox'}),
    mkT('✓  VERIFIED PARTICIPANT',{left:800,top:644,fontSize:12,
      fontFamily:'JetBrains Mono',fill:'#7dd3fc',letterSpacing:2,name:'verifyText'}),
    mkL(300,CH-100,700,CH-100,{stroke:'#0ea5e9',strokeWidth:1,opacity:.4,name:'sigLine'}),
    mkT('Yetkili İmza',{left:500,top:CH-88,fontSize:12,
      fontFamily:'Playfair Display',fontStyle:'italic',fill:'#7dd3fc',opacity:.55,
      originX:'center',name:'sigText'}),
    mkT('CERT_ID',{left:CW-70,top:CH-42,fontSize:11,
      fontFamily:'JetBrains Mono',fill:'#0ea5e9',opacity:.3,originX:'right',name:'layerID'}),
  );
}

// ════════════════════════════════════
//  LAYOUT 3: EĞİTİM SERTİFİKASI
// ════════════════════════════════════
function training(){
  fCanvas.setBackgroundColor('#030a06',fCanvas.renderAll.bind(fCanvas));
  for(let r=0;r<9;r++) for(let c=0;c<21;c++)
    fCanvas.add(new fabric.Circle({left:38+c*74+(r%2)*37,top:38+r*96,radius:1.5,
      fill:'#0a2414',name:'dot_'+r+'_'+c}));
  fCanvas.add(
    mkR({left:55,top:55,width:CW-110,height:CH-110,stroke:'#10b981',strokeWidth:1.5,name:'mainBorder'}),
    ...mkCorners(55,55,CW-110,CH-110,'#10b981',55,2).map((l,i)=>Object.assign(l,{name:'corner'+i})),
    new fabric.Rect({left:55,top:55,width:CW-110,height:38,fill:'#071a0e',name:'terminalBar'}),
    new fabric.Circle({left:78,top:66,radius:8,fill:'#ef4444',name:'btnClose'}),
    new fabric.Circle({left:100,top:66,radius:8,fill:'#f59e0b',name:'btnMin'}),
    new fabric.Circle({left:122,top:66,radius:8,fill:'#10b981',name:'btnMax'}),
    mkT('METU NCC CYBER — CERTIFICATE TERMINAL v2026',{
      left:800,top:74,fontSize:9,fontFamily:'JetBrains Mono',fontWeight:'400',
      fill:'#4ade80',letterSpacing:2,name:'termTitle'}),
    mkT('> CERTIFICATE OF TRAINING COMPLETION',{left:120,top:128,fontSize:13,
      fontFamily:'JetBrains Mono',fill:'#10b981',originX:'left',name:'layerTitle'}),
    mkL(120,158,CW-120,158,{stroke:'#10b981',strokeWidth:.4,opacity:.3,name:'div1'}),
    mkT('> Aşağıdaki kişi eğitimi başarıyla tamamlamıştır:',{left:120,top:182,fontSize:12,
      fontFamily:'JetBrains Mono',fill:'#4ade80',opacity:.65,originX:'left',name:'layerSub'}),
    mkT('KATILIMCI_ADI',{left:800,top:325,fontSize:80,
      fontFamily:'Space Grotesk',fontWeight:'700',fill:'#fff',name:'layerName'}),
    mkT('> Kurs: Cyber Workshop 2026',{left:120,top:452,fontSize:14,
      fontFamily:'JetBrains Mono',fill:'#10b981',originX:'left',name:'layerEvent'}),
    mkT('> Süre: 40 saat  |  Düzey: Intermediate  |  Başarı: Tam Puan',{
      left:120,top:482,fontSize:11,fontFamily:'JetBrains Mono',
      fill:'#4ade80',opacity:.5,originX:'left',name:'layerMeta'}),
    mkT('> Tarih: '+today(),{left:120,top:510,fontSize:11,
      fontFamily:'JetBrains Mono',fill:'#4ade80',opacity:.5,originX:'left',name:'layerDate'}),
    mkL(120,CH-108,480,CH-108,{stroke:'#10b981',strokeWidth:1,name:'sigLine'}),
    mkT('> YETKİLİ_İMZA',{left:120,top:CH-96,fontSize:10,
      fontFamily:'JetBrains Mono',fill:'#10b981',opacity:.35,
      originX:'left',name:'sigText'}),
    mkT('CERT_ID',{left:CW-75,top:CH-70,fontSize:11,
      fontFamily:'JetBrains Mono',fill:'#10b981',opacity:.35,originX:'right',name:'layerID'}),
  );
}

// ════════════════════════════════════
//  LAYOUT 4: SPONSORLU ETKİNLİK
// ════════════════════════════════════
function sponsored(){
  fCanvas.setBackgroundColor('#08000f',fCanvas.renderAll.bind(fCanvas));
  fCanvas.add(
    new fabric.Rect({left:0,top:0,width:350,height:CH,fill:'rgba(139,92,246,.03)',name:'sidePanel1'}),
    new fabric.Rect({left:CW-350,top:0,width:350,height:CH,fill:'rgba(139,92,246,.03)',name:'sidePanel2'}),
    new fabric.Rect({left:0,top:0,width:CW,height:7,fill:'#7c3aed',name:'topBar'}),
    ...mkCorners(38,38,CW-76,CH-115,'#7c3aed',55,2).map((l,i)=>Object.assign(l,{name:'corner'+i})),
    mkLogoPlaceholder(800,96,256,92,'🏛️ ETKİNLİK LOGOSU'),
    mkT('CERTIFICATE OF PARTICIPATION',{left:800,top:200,fontSize:20,
      fontFamily:'Space Grotesk',fontWeight:'700',fill:'#d946ef',letterSpacing:4,name:'layerTitle'}),
    mkT('Bu etkinliğe katılım sağlayan kişiye verilmiştir',{left:800,top:244,fontSize:15,
      fontFamily:'Inter',fontWeight:'300',fill:'#94a3b8',name:'layerSub'}),
    mkT('KATILIMCI ADI',{left:800,top:335,fontSize:80,
      fontFamily:'Playfair Display',fontWeight:'700',fill:'#fff',name:'layerName'}),
    mkT('Cyber Security Summit 2026',{left:800,top:452,fontSize:28,
      fontFamily:'Space Grotesk',fontWeight:'500',fill:'#c084fc',name:'layerEvent'}),
    mkT(today(),{left:800,top:504,fontSize:13,fontFamily:'JetBrains Mono',fill:'#6b7280',name:'layerDate'}),
    mkL(CW-445,CH-116,CW-98,CH-116,{stroke:'#7c3aed',strokeWidth:1,name:'sigLine'}),
    mkT('Organizatör İmzası',{left:CW-271,top:CH-104,fontSize:12,
      fontFamily:'Playfair Display',fontStyle:'italic',fill:'#c084fc',opacity:.55,
      originX:'center',name:'sigText'}),
    new fabric.Rect({left:0,top:CH-96,width:CW,height:96,fill:'#100020',
      stroke:'#7c3aed',strokeWidth:1,name:'sponsorBar'}),
    mkT('DESTEKÇÜLER',{left:72,top:CH-80,fontSize:8,fontFamily:'JetBrains Mono',
      fill:'#7c3aed',letterSpacing:3,opacity:.6,originX:'left',name:'sponsorLabel'}),
    mkT('CERT_ID',{left:CW-70,top:CH-110,fontSize:10,
      fontFamily:'JetBrains Mono',fill:'#7c3aed',opacity:.35,originX:'right',name:'layerID'}),
  );
  // 4 sponsor placeholder
  [{x:218,n:'SPONSOR 1'},{x:526,n:'SPONSOR 2'},{x:834,n:'SPONSOR 3'},{x:1142,n:'SPONSOR 4'}]
  .forEach(s=>{
    fCanvas.add(mkLogoPlaceholder(s.x,CH-54,180,60,'📦 '+s.n));
  });
}

// ════════════════════════════════════
//  LAYOUT 5: RESMİ BELGE
// ════════════════════════════════════
function official(){
  fCanvas.setBackgroundColor('#06060e',fCanvas.renderAll.bind(fCanvas));
  fCanvas.add(
    mkR({left:28,top:28,width:CW-56,height:CH-56,stroke:'#312e81',strokeWidth:2.5,rx:2,ry:2,name:'border1'}),
    mkR({left:46,top:46,width:CW-92,height:CH-92,stroke:'#4338ca',strokeWidth:.6,name:'border2'}),
    ...mkCorners(28,28,CW-56,CH-56,'#6366f1',65,2.5).map((l,i)=>Object.assign(l,{name:'corner'+i})),
    mkC({left:712,top:50,radius:88,stroke:'#4338ca',strokeWidth:2,name:'seal1'}),
    mkC({left:727,top:65,radius:73,stroke:'#312e81',strokeWidth:1,name:'seal2'}),
    mkLogoPlaceholder(800,122,200,80,'🏛️ KURUM LOGOSU'),
    mkT('RESMİ SERTİFİKA',{left:800,top:206,fontSize:19,
      fontFamily:'Playfair Display',fontWeight:'900',fill:'#c7d2fe',letterSpacing:8,name:'layerTitle'}),
    mkL(180,240,CW-180,240,{stroke:'#4338ca',strokeWidth:.5,name:'div1'}),
    mkT('Aşağıdaki kişinin ilgili programı eksiksiz tamamladığı',{left:800,top:264,fontSize:15,
      fontFamily:'Inter',fontWeight:'300',fill:'#94a3b8',name:'layerSub'}),
    mkT('tasdik ve tescil olunur.',{left:800,top:290,fontSize:15,
      fontFamily:'Playfair Display',fontStyle:'italic',fill:'#94a3b8',name:'layerSub2'}),
    mkT('KATILIMCI ADI',{left:800,top:376,fontSize:80,
      fontFamily:'Playfair Display',fontWeight:'700',fill:'#e0e7ff',name:'layerName'}),
    mkT('Cyber Workshop',{left:800,top:486,fontSize:26,
      fontFamily:'Space Grotesk',fontWeight:'500',fill:'#818cf8',name:'layerEvent'}),
    mkT('Program Süresi: 40 Saat  |  Modül: 8  |  Değerlendirme: Başarılı',{
      left:800,top:534,fontSize:12,fontFamily:'JetBrains Mono',fill:'#374151',name:'layerMeta'}),
    mkT('Veriliş Tarihi: '+today(),{left:800,top:558,fontSize:12,
      fontFamily:'JetBrains Mono',fill:'#374151',name:'layerDate'}),
    mkL(115,CH-122,455,CH-122,{stroke:'#4338ca',strokeWidth:1,name:'sigLine1'}),
    mkT('Prof. Dr. İsim Soyisim',{left:285,top:CH-110,fontSize:12,
      fontFamily:'Playfair Display',fontStyle:'italic',fill:'#818cf8',opacity:.6,
      originX:'center',name:'sig1Name'}),
    mkT('PROGRAM DİREKTÖRÜ',{left:285,top:CH-90,fontSize:8,
      fontFamily:'JetBrains Mono',letterSpacing:2,fill:'#6366f1',
      originX:'center',name:'sig1Title'}),
    mkL(CW-455,CH-122,CW-115,CH-122,{stroke:'#4338ca',strokeWidth:1,name:'sigLine2'}),
    mkT('Prof. Dr. İsim Soyisim',{left:CW-285,top:CH-110,fontSize:12,
      fontFamily:'Playfair Display',fontStyle:'italic',fill:'#818cf8',opacity:.6,
      originX:'center',name:'sig2Name'}),
    mkT('BÖLÜM BAŞKANI',{left:CW-285,top:CH-90,fontSize:8,
      fontFamily:'JetBrains Mono',letterSpacing:2,fill:'#6366f1',
      originX:'center',name:'sig2Title'}),
    mkC({left:752,top:CH-120,radius:46,stroke:'#4338ca',strokeWidth:1,fill:'rgba(67,56,202,.07)',name:'sealCircle'}),
    mkT('MÜHÜR',{left:800,top:CH-78,fontSize:8,fontFamily:'JetBrains Mono',
      fill:'#4338ca',opacity:.3,originX:'center',name:'sealText'}),
    mkT('CERT_ID',{left:CW-70,top:CH-44,fontSize:11,
      fontFamily:'JetBrains Mono',fill:'#4338ca',opacity:.35,originX:'right',name:'layerID'}),
  );
}

// ════════════════════════════════════
//  LAYOUT 6: MİNİMALİST
// ════════════════════════════════════
function minimal(){
  fCanvas.setBackgroundColor('#0c0c0c',fCanvas.renderAll.bind(fCanvas));
  fCanvas.add(
    mkR({left:50,top:50,width:CW-100,height:CH-100,stroke:'#2a2a2a',strokeWidth:1,name:'mainBorder'}),
    new fabric.Rect({left:50,top:50,width:5,height:CH-100,fill:'#fff',name:'accent'}),
    mkL(100,238,CW-100,238,{stroke:'#fff',strokeWidth:.4,opacity:.12,name:'div1'}),
    mkT('CERTIFICATE',{left:118,top:98,fontSize:10,
      fontFamily:'JetBrains Mono',fontWeight:'400',fill:'#fff',opacity:.18,
      letterSpacing:6,originX:'left',name:'certLabel'}),
    mkT('BAŞARILI TAMAMLAMA',{left:118,top:143,fontSize:13,
      fontFamily:'Inter',fontWeight:'300',fill:'#fff',opacity:.35,
      letterSpacing:4,originX:'left',name:'layerTitle'}),
    mkT('Bu belge aşağıdaki kişiye verilmiştir',{left:118,top:183,fontSize:14,
      fontFamily:'Inter',fontWeight:'300',fill:'#fff',opacity:.25,
      originX:'left',name:'layerSub'}),
    mkT('KATILIMCI ADI',{left:118,top:325,fontSize:90,
      fontFamily:'Inter',fontWeight:'800',fill:'#fff',originX:'left',name:'layerName'}),
    mkT('Cyber Workshop',{left:118,top:442,fontSize:22,
      fontFamily:'Inter',fontWeight:'300',fill:'#fff',opacity:.45,
      originX:'left',name:'layerEvent'}),
    mkL(100,495,CW-100,495,{stroke:'#fff',strokeWidth:.4,opacity:.1,name:'div2'}),
    mkT(today(),{left:118,top:514,fontSize:12,fontFamily:'JetBrains Mono',
      fill:'#fff',opacity:.18,originX:'left',name:'layerDate'}),
    mkL(118,CH-100,418,CH-100,{stroke:'#fff',strokeWidth:.5,opacity:.25,name:'sigLine'}),
    mkT('Yetkili İmza',{left:268,top:CH-88,fontSize:12,
      fontFamily:'Playfair Display',fontStyle:'italic',fill:'#fff',opacity:.22,
      originX:'center',name:'sigText'}),
    mkT('CERT_ID',{left:CW-70,top:CH-44,fontSize:11,
      fontFamily:'JetBrains Mono',fill:'#fff',opacity:.1,originX:'right',name:'layerID'}),
  );
}

// ════════════════════════════════════
//  LAYOUT DISPATCHER
// ════════════════════════════════════
function applyLayout(name,el){
  document.querySelectorAll('.tpl').forEach(c=>c.classList.remove('sel'));
  el.classList.add('sel');
  clearLayers();
  ({achievement,participation,training,sponsored,official,minimal})[name]();
  fCanvas.requestRenderAll();refreshLayers();
}

// ════════════════════════════════════
//  COLOR PALETTE PRESETS
// ════════════════════════════════════
const COLOR_MAP={
  gold:{primary:'#d4af37',secondary:'#b8860b',accent:'#fcd34d'},
  blue:{primary:'#0ea5e9',secondary:'#0284c7',accent:'#7dd3fc'},
  green:{primary:'#10b981',secondary:'#059669',accent:'#4ade80'},
  purple:{primary:'#c084fc',secondary:'#7c3aed',accent:'#e879f9'},
  indigo:{primary:'#6366f1',secondary:'#4338ca',accent:'#818cf8'},
  red:{primary:'#ef4444',secondary:'#dc2626',accent:'#f87171'},
  orange:{primary:'#f59e0b',secondary:'#d97706',accent:'#fbbf24'},
  pink:{primary:'#ec4899',secondary:'#db2777',accent:'#f9a8d4'},
  teal:{primary:'#14b8a6',secondary:'#0d9488',accent:'#5eead4'},
  white:{primary:'#ffffff',secondary:'#e5e5e5',accent:'#d4d4d4'},
};

function applyColorPreset(preset){
  const colors=COLOR_MAP[preset];
  if(!colors)return;
  
  fCanvas.getObjects().forEach(obj=>{
    // Çizgiler & çerçeveler
    if(obj.stroke && obj.stroke!=='transparent'){
      if(obj.stroke.includes('#'))obj.set({stroke:colors.primary});
    }
    // Yazılar (bazı vurgular accent renk alabilir)
    if((obj.type==='i-text'||obj.type==='text')&&obj.fill&&obj.fill.includes('#')){
      if(obj.name&&obj.name.includes('Title'))obj.set({fill:colors.primary});
      else if(obj.name&&obj.name.includes('Event'))obj.set({fill:colors.accent});
      else if(obj.opacity&&obj.opacity<0.8)obj.set({fill:colors.secondary});
    }
    // Daireler
    if(obj.type==='circle'&&obj.stroke)obj.set({stroke:colors.primary});
    // Dolgulu dikdörtgenler (accent bar'lar)
    if(obj.type==='rect'&&obj.fill&&obj.fill!=='transparent'&&!obj.fill.includes('rgba')){
      obj.set({fill:colors.primary});
    }
  });
  
  fCanvas.requestRenderAll();
  document.querySelectorAll('.color-preset').forEach(p=>p.classList.remove('active'));
  event.target.classList.add('active');
}

// ════════════════════════════════════
//  MANUAL COLOR CHANGE
// ════════════════════════════════════
function applyManualColor(){
  const obj=fCanvas.getActiveObject();
  if(!obj){alert('Önce canvas\'ta bir nesne seç!');return;}
  const color=document.getElementById('manualColor').value;
  
  if(obj.type==='i-text'||obj.type==='text'){
    obj.set({fill:color});
  }else if(obj.stroke){
    obj.set({stroke:color});
  }else if(obj.fill){
    obj.set({fill:color});
  }
  
  fCanvas.requestRenderAll();
}

// ════════════════════════════════════
//  BULK COLOR REPLACE
// ════════════════════════════════════
function bulkColorReplace(){
  const oldC=document.getElementById('oldColorBulk').value.toLowerCase();
  const newC=document.getElementById('newColorBulk').value;
  let count=0;
  
  fCanvas.getObjects().forEach(obj=>{
    if(obj.stroke&&obj.stroke.toLowerCase()===oldC){
      obj.set({stroke:newC});count++;
    }
    if(obj.fill&&obj.fill.toLowerCase()===oldC){
      obj.set({fill:newC});count++;
    }
  });
  
  fCanvas.requestRenderAll();
  alert(`✓ ${count} nesne rengi değiştirildi!`);
}

// ════════════════════════════════════
//  CUSTOM TEXT / IMAGE
// ════════════════════════════════════
function addCustomText(){
  const v=document.getElementById('addTextVal').value||'Yeni Metin';
  const s=parseInt(document.getElementById('addTextSize').value)||60;
  const c=document.getElementById('addTextColor').value;
  const t=mkT(v,{left:800,top:450,fontSize:s,fill:c,name:'txt_'+Date.now()});
  fCanvas.add(t);fCanvas.setActiveObject(t);
  fCanvas.requestRenderAll();refreshLayers();
}
function addCustomImage(){
  const file=document.getElementById('imgInput').files[0];
  if(!file){alert('Varlıklar sekmesinden resim seç.');return;}
  const r=new FileReader();
  r.onload=f=>{fabric.Image.fromURL(f.target.result,img=>{
    img.set({left:400,top:300,scaleX:.3,scaleY:.3,name:'img_'+Date.now()});
    fCanvas.add(img);fCanvas.setActiveObject(img);
    fCanvas.requestRenderAll();refreshLayers();
  });};
  r.readAsDataURL(file);
}
function exportCanvas(){
  const a=document.createElement('a');
  a.download='sertifika_onizleme.png';
  a.href=fCanvas.toDataURL({format:'png',quality:1,multiplier:1});
  a.click();
}

// ════════════════════════════════════
//  QR
// ════════════════════════════════════
async function genQR(url,sz=140){
  return new Promise(res=>{
    const c=document.createElement('canvas');
    QRCode.toCanvas(c,url||'https://example.com',{
      width:sz,margin:1,color:{dark:'#fff',light:'#00000000'}
    },()=>res(c.toDataURL()));
  });
}
document.getElementById('qrUrl').addEventListener('input',async function(){
  if(!this.value)return;
  const du=await genQR(this.value,54);
  const pc=document.getElementById('qrCanvas');
  const ctx=pc.getContext('2d');
  const img=new Image();
  img.onload=()=>{ctx.clearRect(0,0,54,54);ctx.fillStyle='#000';
    ctx.fillRect(0,0,54,54);ctx.drawImage(img,0,0,54,54);};
  img.src=du;
  document.getElementById('qrStatus').textContent="Hazır — Canvas'a ekle butonuna bas";
});
async function addQRToCanvas(url,forBulk=false){
  const qu=url||document.getElementById('qrUrl').value||'https://example.com';
  const du=await genQR(qu,140);
  return new Promise(res=>{
    if(qrObj){fCanvas.remove(qrObj);qrObj=null;}
    fabric.Image.fromURL(du,img=>{
      img.set({left:1380,top:700,scaleX:1,scaleY:1,
        name:'layerQR',hasControls:true,hasBorders:true});
      fCanvas.add(img);qrObj=img;
      if(!forBulk){
        fCanvas.setActiveObject(img);
        document.getElementById('qrStatus').textContent="✓ Eklendi — sürükle & konumlandır";
      }
      fCanvas.requestRenderAll();refreshLayers();res(img);
    });
  });
}

// ════════════════════════════════════
//  SNAP GUIDES
// ════════════════════════════════════
const SNAP=10;
fCanvas.on('object:moving',function(e){
  const obj=e.target,cx=CW/2,cy=CH/2;
  const ov=document.getElementById('gOverlay');
  const lH=document.getElementById('gH'),lV=document.getElementById('gV');
  let sH=false,sV=false;
  const ocx=obj.getCenterPoint().x,ocy=obj.getCenterPoint().y;
  if(Math.abs(ocx-cx)<SNAP){obj.set({left:obj.originX==='center'?cx:cx-obj.getScaledWidth()/2});sV=true;}
  if(Math.abs(ocy-cy)<SNAP){obj.set({top:obj.originY==='center'?cy:cy-obj.getScaledHeight()/2});sH=true;}
  ov.style.display=(sH||sV)?'block':'none';
  lH.style.display=sH?'block':'none';lV.style.display=sV?'block':'none';
  fCanvas.requestRenderAll();updatePropPanel(obj);
});
fCanvas.on('object:modified',()=>{document.getElementById('gOverlay').style.display='none';});
fCanvas.on('mouse:up',()=>{document.getElementById('gOverlay').style.display='none';refreshLayers();});

// ════════════════════════════════════
//  PROPERTY PANEL
// ════════════════════════════════════
fCanvas.on('selection:created',e=>updatePropPanel(e.selected[0]));
fCanvas.on('selection:updated',e=>updatePropPanel(e.selected[0]));
fCanvas.on('selection:cleared',()=>{
  document.getElementById('propContent').innerHTML='<span style="color:#6b7280;">Bir nesneye tıkla…</span>';
});
fCanvas.on('object:scaling',e=>updatePropPanel(e.target));
fCanvas.on('object:rotating',e=>updatePropPanel(e.target));

function updatePropPanel(obj){
  if(!obj)return;
  const cx=Math.round(obj.getCenterPoint().x),cy=Math.round(obj.getCenterPoint().y);
  const w=Math.round(obj.getScaledWidth()),h=Math.round(obj.getScaledHeight());
  const r=Math.round(obj.angle||0);
  const isTxt=obj.type==='i-text'||obj.type==='text';
  let html=`<div style="display:grid;grid-template-columns:1fr 1fr;gap:3px;font-size:10px;margin-bottom:8px;">
    <div><span style="color:#6b7280;">Ad:</span> <span style="color:#fff;">${obj.name||'—'}</span></div>
    <div><span style="color:#6b7280;">Tür:</span> <span style="color:var(--g);">${obj.type}</span></div>
    <div><span style="color:#6b7280;">X:</span> <span style="color:#fff;">${cx}</span></div>
    <div><span style="color:#6b7280;">Y:</span> <span style="color:#fff;">${cy}</span></div>
    <div><span style="color:#6b7280;">W:</span> <span style="color:#fff;">${w}</span></div>
    <div><span style="color:#6b7280;">H:</span> <span style="color:#fff;">${h}</span></div>
    <div><span style="color:#6b7280;">Açı:</span> <span style="color:#fff;">${r}°</span></div>
  </div>`;
  if(isTxt){
    html+=`<div style="display:flex;gap:6px;align-items:center;margin-bottom:8px;">
      <label style="font-size:8px;color:#6b7280;">Renk</label>
      <input type="color" value="${obj.fill||'#ffffff'}"
        style="width:26px;height:26px;border-radius:3px;border:1px solid var(--b);cursor:pointer;padding:1px;"
        onchange="chColor(this.value)">
      <label style="font-size:8px;color:#6b7280;margin-left:4px;">px</label>
      <input type="number" value="${Math.round(obj.fontSize)}" class="ci"
        style="width:55px;" onchange="chSize(this.value)">
    </div>`;
  }
  html+=`<div style="display:flex;gap:4px;margin-bottom:5px;">
    <button class="btn bg" style="padding:5px;font-size:8px;" onclick="cH()">↔ Yatay</button>
    <button class="btn bg" style="padding:5px;font-size:8px;" onclick="cV()">↕ Dikey</button>
  </div>
  <button class="btn btn-r" style="padding:5px;font-size:9px;" onclick="delSel()">🗑 Sil</button>`;
  document.getElementById('propContent').innerHTML=html;
}
function chColor(v){const o=fCanvas.getActiveObject();if(o){o.set({fill:v});fCanvas.requestRenderAll();}}
function chSize(v){const o=fCanvas.getActiveObject();if(o){o.set({fontSize:parseInt(v)});fCanvas.requestRenderAll();}}
function cH(){const o=fCanvas.getActiveObject();if(!o)return;
  o.set({left:o.originX==='center'?CW/2:CW/2-o.getScaledWidth()/2});fCanvas.requestRenderAll();}
function cV(){const o=fCanvas.getActiveObject();if(!o)return;
  o.set({top:o.originY==='center'?CH/2:CH/2-o.getScaledHeight()/2});fCanvas.requestRenderAll();}
function delSel(){const o=fCanvas.getActiveObject();
  if(o){fCanvas.remove(o);fCanvas.requestRenderAll();refreshLayers();}}

// ════════════════════════════════════
//  LAYER PANEL
// ════════════════════════════════════
function refreshLayers(){
  const list=document.getElementById('layerList');
  const objs=fCanvas.getObjects().slice().reverse();
  if(!objs.length){list.innerHTML='<div style="color:#6b7280;">Henüz katman yok.</div>';return;}
  list.innerHTML=objs.map((o,i)=>`
    <div style="display:flex;align-items:center;gap:5px;background:#0d1117;
      border:1px solid #1a2030;border-radius:4px;padding:5px 7px;cursor:pointer;"
      onclick="selLayer(${fCanvas.getObjects().length-1-i})"
      onmouseover="this.style.borderColor='#064e2b'" onmouseout="this.style.borderColor='#1a2030'">
      <span style="font-size:7px;color:#374151;min-width:12px;">${fCanvas.getObjects().length-1-i}</span>
      <span style="flex:1;font-size:10px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${o.name||o.type}</span>
      <span style="font-size:7px;color:#064e2b;background:#0a1e0f;padding:1px 4px;border-radius:2px;">${o.type}</span>
    </div>`).join('');
}
function selLayer(idx){const o=fCanvas.item(idx);
  if(o){fCanvas.setActiveObject(o);fCanvas.requestRenderAll();updatePropPanel(o);}}
function moveUp(){const o=fCanvas.getActiveObject();if(o){fCanvas.bringForward(o);fCanvas.requestRenderAll();refreshLayers();}}
function moveDown(){const o=fCanvas.getActiveObject();if(o){fCanvas.sendBackwards(o);fCanvas.requestRenderAll();refreshLayers();}}

// ════════════════════════════════════
//  BULK PRODUCTION
// ════════════════════════════════════
async function startBulkProduction(){
  const names=document.getElementById('bulkNames').value
    .split('\n').map(n=>n.trim()).filter(n=>n);
  if(!names.length){alert('Katılımcı listesi boş!');return;}
  const prefix=document.getElementById('prefix').value||'CERT';
  const year=document.getElementById('certYear').value||'2026';
  const eventTag=document.getElementById('eventTag').value;
  const nameObj=fCanvas.getObjects().find(o=>o.name==='layerName');
  const idObj=fCanvas.getObjects().find(o=>o.name==='layerID');
  const eventObj=fCanvas.getObjects().find(o=>o.name==='layerEvent');
  let qL=1380,qT=700,qSX=1,qSY=1;
  if(qrObj){qL=qrObj.left;qT=qrObj.top;qSX=qrObj.scaleX;qSY=qrObj.scaleY;
    fCanvas.remove(qrObj);qrObj=null;}
  const prog=document.getElementById('bulkProgress');
  const pTxt=document.getElementById('bulkProgressText');
  const pBar=document.getElementById('bulkProgressBar');
  prog.style.display='block';
  for(let i=0;i<names.length;i++){
    const name=names[i];
    const certID=genCertId(prefix,year);
    const qrUrl=(document.getElementById('qrUrl').value||
      window.location.origin+'/verify.php')+'?id='+certID;
    if(nameObj) nameObj.text=name.toUpperCase();
    if(idObj)   idObj.text=certID;
    if(eventObj)eventObj.text=eventTag.toUpperCase();
    fCanvas.requestRenderAll();
    const qd=await genQR(qrUrl,140);
    await new Promise(res=>{
      fabric.Image.fromURL(qd,qr=>{
        qr.set({left:qL,top:qT,scaleX:qSX,scaleY:qSY,selectable:false});
        fCanvas.add(qr);fCanvas.requestRenderAll();
        setTimeout(()=>{
          const a=document.createElement('a');
          a.download=`${certID}_${name.replace(/\s+/g,'_')}.png`;
          a.href=fCanvas.toDataURL({format:'png',quality:1,multiplier:1});
          a.click();fCanvas.remove(qr);res();
        },90);
      });
    });
    pBar.style.width=Math.round((i+1)/names.length*100)+'%';
    pTxt.textContent=`${i+1}/${names.length} — ${name}  [${certID}]`;
    await new Promise(r=>setTimeout(r,220));
  }
  pTxt.textContent=`✅ ${names.length} sertifika başarıyla üretildi!`;
}

// ════════════════════════════════════
//  KEYBOARD SHORTCUTS
// ════════════════════════════════════
document.addEventListener('keydown',e=>{
  if(['INPUT','TEXTAREA'].includes(document.activeElement.tagName))return;
  if(e.key==='Delete'||e.key==='Backspace') delSel();
  if(e.key==='ArrowUp')   moveUp();
  if(e.key==='ArrowDown') moveDown();
});
</script>
</body>
</html>
