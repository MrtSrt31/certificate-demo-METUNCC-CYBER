<?php
// Demo admin sayfası
// İleride authentication ve database buraya eklenecek
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>METU NCC Cyber | Production Command Center</title>

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800&family=JetBrains+Mono&display=swap" rel="stylesheet">

<style>
*{box-sizing:border-box;}
body{
    background:#000;
    color:#fff;
    font-family:'Inter',sans-serif;
    background-image:radial-gradient(#1f2937 0.8px,transparent 0.8px);
    background-size:30px 30px;
    margin:0;
    overflow:hidden;
}

.cyber-input{
    background:#0d1117;
    border:1px solid #30363d;
    color:white;
    border-radius:4px;
    font-size:11px;
    padding:8px 10px;
    width:100%;
    transition:border-color 0.2s;
}
.cyber-input:focus{
    border-color:#10b981;
    outline:none;
}

.sidebar{
    height:100vh;
    overflow-y:auto;
    border-right:1px solid #30363d;
    background:rgba(13,17,23,0.98);
    scrollbar-width:thin;
    scrollbar-color:#30363d transparent;
}

.section-title{
    font-size:9px;
    font-weight:700;
    color:#6b7280;
    text-transform:uppercase;
    letter-spacing:.12em;
    margin-bottom:8px;
    display:block;
}

.btn{
    font-size:10px;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:.1em;
    border-radius:6px;
    padding:9px 14px;
    cursor:pointer;
    transition:all .15s;
    border:none;
    width:100%;
}
.btn-ghost{
    background:transparent;
    border:1px solid #30363d;
    color:#9ca3af;
}
.btn-ghost:hover{background:#1f2937;color:#fff;}
.btn-emerald{background:#10b981;color:#000;}
.btn-emerald:hover{background:#34d399;}
.btn-dim{
    background:#0d2419;
    border:1px solid #064e2b;
    color:#10b981;
}
.btn-dim:hover{background:#10b981;color:#000;}

#mainArea{
    flex:1;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:24px;
    overflow:hidden;
    min-width:0;
}

#canvas-wrapper{
    position:relative;
    /* boyut JS tarafından ayarlanır */
    flex-shrink:0;
}

#guideOverlay{
    position:absolute;
    top:0;left:0;
    pointer-events:none;
    z-index:999;
}

#qrPreviewBox{
    background:#0d1117;
    border:1px solid #30363d;
    border-radius:8px;
    padding:10px;
    display:flex;
    align-items:center;
    gap:10px;
}

#propPanel{
    background:#0d1117;
    border:1px solid #30363d;
    border-radius:8px;
    padding:10px;
    font-size:10px;
}

.tab-btn{
    font-size:9px;
    font-weight:700;
    letter-spacing:.08em;
    text-transform:uppercase;
    padding:6px 10px;
    border-radius:4px;
    cursor:pointer;
    border:1px solid transparent;
    color:#6b7280;
    background:transparent;
    transition:all .15s;
}
.tab-btn.active{
    background:#161b22;
    border-color:#30363d;
    color:#10b981;
}

.color-pick{
    width:32px;height:32px;border-radius:4px;
    border:1px solid #30363d;cursor:pointer;
    padding:0;background:transparent;
}

/* Fabric canvas element'in kendisi inline block olarak gelir,
   wrapper ile tam örtüşmesi için: */
#canvas-wrapper canvas{
    display:block;
}
</style>
</head>

<body class="flex" style="height:100vh;overflow:hidden;">

<!-- ═══════════════ SIDEBAR ═══════════════ -->
<div class="w-96 sidebar p-5 flex flex-col shrink-0 gap-5">

    <div class="border-b border-gray-800 pb-4">
        <h1 class="text-[#10b981] font-black text-xs tracking-widest uppercase">Command Center v6.0</h1>
        <p class="text-[10px] opacity-50 font-mono mt-1">OPERATOR: <span class="text-white">ADMIN DEMO</span></p>
    </div>

    <!-- TABS -->
    <div class="flex gap-1">
        <button class="tab-btn active" onclick="switchTab('design',this)">Tasarım</button>
        <button class="tab-btn" onclick="switchTab('layers',this)">Katmanlar</button>
        <button class="tab-btn" onclick="switchTab('bulk',this)">Toplu Üretim</button>
    </div>

    <!-- ── TAB: TASARIM ── -->
    <div id="tab-design" class="flex flex-col gap-4">

        <div>
            <span class="section-title">1. Arka Plan Şablonu</span>
            <input type="file" id="bgInput" accept="image/*" class="text-[10px] text-gray-400">
        </div>

        <button class="btn btn-dim" onclick="setupLayout()">⚡ Akıllı Layout Oluştur</button>

        <hr class="border-gray-800">

        <div>
            <span class="section-title">Yazı Ekle</span>
            <div class="flex gap-2 mb-2">
                <input id="addTextVal" class="cyber-input flex-1" placeholder="Metin içeriği...">
            </div>
            <div class="grid grid-cols-2 gap-2 mb-2">
                <input id="addTextSize" type="number" value="60" class="cyber-input" placeholder="Font px">
                <input id="addTextColor" type="color" value="#ffffff" class="color-pick w-full h-[34px]">
            </div>
            <button class="btn btn-ghost" onclick="addCustomText()">+ Yazı Ekle</button>
        </div>

        <hr class="border-gray-800">

        <div>
            <span class="section-title">Resim / Logo Ekle</span>
            <input type="file" id="imgInput" accept="image/*" class="text-[10px] text-gray-400 mb-2 block">
            <button class="btn btn-ghost" onclick="addCustomImage()">+ Resim Ekle</button>
        </div>

        <hr class="border-gray-800">

        <div>
            <span class="section-title">QR Kodu</span>
            <input id="qrUrl" class="cyber-input mb-2" placeholder="https://verify.example.com/?id=">
            <div id="qrPreviewBox">
                <canvas id="qrPreviewCanvas" width="60" height="60" style="border-radius:4px;background:#000;image-rendering:pixelated;"></canvas>
                <div>
                    <p class="text-[10px] text-gray-400">Canvas üzerinde sürükleyerek konumlandırabilirsin.</p>
                    <p id="qrStatus" class="text-[9px] text-emerald-500 mt-1">—</p>
                </div>
            </div>
            <button class="btn btn-ghost mt-2" onclick="addQRToCanvas()">+ QR'ı Canvas'a Ekle</button>
        </div>

        <hr class="border-gray-800">

        <div id="propPanel">
            <span class="section-title" style="color:#10b981;">Seçili Nesne</span>
            <div id="propContent" class="text-gray-500 text-[10px]">Bir nesneye tıkla…</div>
        </div>

    </div>

    <!-- ── TAB: KATMANLAR ── -->
    <div id="tab-layers" class="hidden flex-col gap-2">
        <span class="section-title">Katman Listesi</span>
        <div id="layerList" class="flex flex-col gap-1 text-[10px]"></div>
        <button class="btn btn-ghost mt-2" onclick="refreshLayers()">↺ Yenile</button>
    </div>

    <!-- ── TAB: TOPLU ÜRETİM ── -->
    <div id="tab-bulk" class="hidden flex-col gap-4">

        <div>
            <span class="section-title">Katılımcı Listesi (her satır bir isim)</span>
            <textarea id="bulkNames" rows="7" class="cyber-input" placeholder="Mert Sert&#10;Ahmet Yılmaz&#10;Ayşe Demir"></textarea>
        </div>

        <div>
            <span class="section-title">Sertifika ID Ayarları</span>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="text-[9px] text-gray-600 mb-1 block">Prefix</label>
                    <input id="prefix" value="METUNCC_CYBER" class="cyber-input">
                </div>
                <div>
                    <label class="text-[9px] text-gray-600 mb-1 block">Başlangıç No</label>
                    <input id="startNum" type="number" value="202600" class="cyber-input">
                </div>
            </div>
        </div>

        <div>
            <span class="section-title">Etkinlik Adı (layerEvent katmanına yazar)</span>
            <input id="eventTag" value="Cyber Workshop" class="cyber-input">
        </div>

        <div id="bulkProgress" class="hidden">
            <div class="text-[10px] text-gray-400 mb-1" id="bulkProgressText">Hazırlanıyor…</div>
            <div class="w-full bg-gray-800 rounded-full h-1.5">
                <div id="bulkProgressBar" class="bg-emerald-500 h-1.5 rounded-full transition-all" style="width:0%"></div>
            </div>
        </div>

        <button class="btn btn-emerald" onclick="startBulkProduction()">▶ TÜMÜNÜ BAS & İNDİR</button>
    </div>

</div>

<!-- ═══════════════ CANVAS AREA ═══════════════ -->
<div id="mainArea">
    <div id="canvas-wrapper" style="box-shadow:0 0 0 1px #30363d;">
        <canvas id="fCanvas"></canvas>
        <svg id="guideOverlay" style="display:none;">
            <line id="guideH" x1="0" y1="50%" x2="100%" y2="50%" stroke="#10b981" stroke-width="0.5" stroke-dasharray="4,4" opacity="0.8"/>
            <line id="guideV" x1="50%" y1="0" x2="50%" y2="100%" stroke="#10b981" stroke-width="0.5" stroke-dasharray="4,4" opacity="0.8"/>
        </svg>
    </div>
</div>

<script>
// ── Sabitler ─────────────────────────────────────────────────
const CANVAS_W = 1600;
const CANVAS_H = 900;
const CANVAS_RATIO = CANVAS_W / CANVAS_H; // 16/9

// ── Canvas Init ──────────────────────────────────────────────
const fCanvas = new fabric.Canvas('fCanvas', {
    width: CANVAS_W,
    height: CANVAS_H,
    backgroundColor: '#161b22',
    preserveObjectStacking: true,
    stopContextMenu: true,
    fireRightClick: true,
});

let currentQRDataURL = null;
let qrObjectOnCanvas = null;

// ── Resize — canvas'ı mevcut alana tam sığdır ────────────────
function resize() {
    const area    = document.getElementById('mainArea');
    const padding = 48; // her yönde 24px boşluk
    const availW  = area.clientWidth  - padding;
    const availH  = area.clientHeight - padding;

    // Aspect ratio'yu koruyarak her iki eksende de sığacak scale
    const scale = Math.min(availW / CANVAS_W, availH / CANVAS_H);

    const displayW = Math.floor(CANVAS_W * scale);
    const displayH = Math.floor(CANVAS_H * scale);

    // Fabric canvas'ın ekrandaki piksel boyutunu güncelle
    fCanvas.setDimensions({ width: displayW, height: displayH });

    // İç koordinat sistemi hâlâ 1600×900 kalır — sadece görsel ölçek değişir
    fCanvas.setViewportTransform([scale, 0, 0, scale, 0, 0]);
    fCanvas.renderAll();

    // Wrapper boyutunu da güncelle (SVG overlay için)
    const wrapper = document.getElementById('canvas-wrapper');
    wrapper.style.width  = displayW + 'px';
    wrapper.style.height = displayH + 'px';

    // SVG overlay boyutu
    const ov = document.getElementById('guideOverlay');
    ov.setAttribute('width',  displayW);
    ov.setAttribute('height', displayH);
    ov.style.width  = displayW + 'px';
    ov.style.height = displayH + 'px';
}

window.addEventListener('resize', resize);
// Fabric yüklendikten sonra bir tick bekle, sonra boyutlandır
setTimeout(resize, 100);

// ── Tab system ───────────────────────────────────────────────
function switchTab(name, btn) {
    ['design','layers','bulk'].forEach(t => {
        document.getElementById('tab-'+t).classList.add('hidden');
        document.getElementById('tab-'+t).classList.remove('flex');
    });
    document.getElementById('tab-'+name).classList.remove('hidden');
    document.getElementById('tab-'+name).classList.add('flex');
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    if (name === 'layers') refreshLayers();
}

// ── Background ───────────────────────────────────────────────
document.getElementById('bgInput').onchange = e => {
    const reader = new FileReader();
    reader.onload = f => {
        fabric.Image.fromURL(f.target.result, img => {
            img.selectable = false;
            img.evented    = false;
            const scaleX = CANVAS_W / img.width;
            const scaleY = CANVAS_H / img.height;
            const scale  = Math.max(scaleX, scaleY); // cover
            img.set({
                scaleX: scale,
                scaleY: scale,
                left: (CANVAS_W - img.width  * scale) / 2,
                top:  (CANVAS_H - img.height * scale) / 2,
            });
            fCanvas.setBackgroundImage(img, fCanvas.renderAll.bind(fCanvas));
        });
    };
    reader.readAsDataURL(e.target.files[0]);
};

// ── Clear layers ─────────────────────────────────────────────
function clearLayers() {
    fCanvas.getObjects().forEach(o => fCanvas.remove(o));
    qrObjectOnCanvas = null;
}

// ── Default Layout ───────────────────────────────────────────
function setupLayout() {
    clearLayers();

    const nameLayer = new fabric.IText("KATILIMCI ADI", {
        left: 800, top: 430,
        fontSize: 85, fontFamily: 'Inter', fontWeight: '800',
        fill: '#ffffff', originX: 'center', name: 'layerName',
    });
    const eventLayer = new fabric.IText(document.getElementById('eventTag').value.toUpperCase(), {
        left: 800, top: 540,
        fontSize: 40, fontFamily: 'Inter', fontWeight: '700',
        fill: '#10b981', originX: 'center', name: 'layerEvent',
    });
    const idLayer = new fabric.IText("CERT_ID", {
        left: 1560, top: 875,
        fontSize: 14, fontFamily: 'JetBrains Mono',
        fill: '#ffffff', opacity: 0.3,
        originX: 'right', name: 'layerID',
    });

    fCanvas.add(nameLayer, eventLayer, idLayer);
    fCanvas.setActiveObject(nameLayer);
    fCanvas.requestRenderAll();
    refreshLayers();
}

// ── Add Custom Text ──────────────────────────────────────────
function addCustomText() {
    const val   = document.getElementById('addTextVal').value || 'Yeni Metin';
    const size  = parseInt(document.getElementById('addTextSize').value) || 60;
    const color = document.getElementById('addTextColor').value;
    const t = new fabric.IText(val, {
        left: 800, top: 450,
        fontSize: size, fontFamily: 'Inter', fontWeight: '700',
        fill: color, originX: 'center',
        name: 'customText_' + Date.now(),
    });
    fCanvas.add(t);
    fCanvas.setActiveObject(t);
    fCanvas.requestRenderAll();
    refreshLayers();
}

// ── Add Custom Image ─────────────────────────────────────────
function addCustomImage() {
    const file = document.getElementById('imgInput').files[0];
    if (!file) { alert('Önce bir resim seç.'); return; }
    const reader = new FileReader();
    reader.onload = f => {
        fabric.Image.fromURL(f.target.result, img => {
            img.set({
                left: 400, top: 300,
                scaleX: 0.3, scaleY: 0.3,
                name: 'customImg_' + Date.now(),
            });
            fCanvas.add(img);
            fCanvas.setActiveObject(img);
            fCanvas.requestRenderAll();
            refreshLayers();
        });
    };
    reader.readAsDataURL(file);
}

// ── QR Generate ──────────────────────────────────────────────
async function generateQR(url, size = 140) {
    return new Promise(resolve => {
        const c = document.createElement('canvas');
        QRCode.toCanvas(c, url || 'https://example.com', {
            width: size, margin: 1,
            color: { dark: '#ffffff', light: '#00000000' }
        }, () => resolve(c.toDataURL()));
    });
}

document.getElementById('qrUrl').addEventListener('input', async function () {
    if (!this.value) return;
    const dataURL = await generateQR(this.value, 60);
    const previewCanvas = document.getElementById('qrPreviewCanvas');
    const ctx = previewCanvas.getContext('2d');
    const img = new Image();
    img.onload = () => {
        ctx.clearRect(0, 0, 60, 60);
        ctx.fillStyle = '#000';
        ctx.fillRect(0, 0, 60, 60);
        ctx.drawImage(img, 0, 0, 60, 60);
    };
    img.src = dataURL;
    document.getElementById('qrStatus').textContent = "QR hazır — Canvas'a ekle butonuna bas";
    currentQRDataURL = await generateQR(this.value, 140);
});

// ── Add QR to Canvas ─────────────────────────────────────────
async function addQRToCanvas(url, forBulk = false) {
    const qrUrl  = url || document.getElementById('qrUrl').value || 'https://example.com';
    const dataURL = await generateQR(qrUrl, 140);
    return new Promise(resolve => {
        if (qrObjectOnCanvas) { fCanvas.remove(qrObjectOnCanvas); qrObjectOnCanvas = null; }
        fabric.Image.fromURL(dataURL, img => {
            img.set({
                left: 1380, top: 700,
                scaleX: 1, scaleY: 1,
                name: 'layerQR',
                hasControls: true, hasBorders: true,
            });
            fCanvas.add(img);
            qrObjectOnCanvas = img;
            if (!forBulk) {
                fCanvas.setActiveObject(img);
                document.getElementById('qrStatus').textContent = "✓ Canvas'a eklendi — sürükle & konumlandır";
            }
            fCanvas.requestRenderAll();
            refreshLayers();
            resolve(img);
        });
    });
}

// ── Snap / Center guides ─────────────────────────────────────
const SNAP_THRESHOLD = 12;

fCanvas.on('object:moving', function (e) {
    const obj     = e.target;
    const centerX = CANVAS_W / 2;
    const centerY = CANVAS_H / 2;
    const objCX   = obj.getCenterPoint().x;
    const objCY   = obj.getCenterPoint().y;

    const overlay = document.getElementById('guideOverlay');
    const lineH   = document.getElementById('guideH');
    const lineV   = document.getElementById('guideV');

    let snappedH = false, snappedV = false;

    if (Math.abs(objCX - centerX) < SNAP_THRESHOLD) {
        if (obj.originX === 'center') obj.set({ left: centerX });
        else obj.set({ left: centerX - obj.getScaledWidth() / 2 });
        snappedV = true;
    }
    if (Math.abs(objCY - centerY) < SNAP_THRESHOLD) {
        if (obj.originY === 'center') obj.set({ top: centerY });
        else obj.set({ top: centerY - obj.getScaledHeight() / 2 });
        snappedH = true;
    }

    overlay.style.display = (snappedH || snappedV) ? 'block' : 'none';
    lineH.style.display   = snappedH ? 'block' : 'none';
    lineV.style.display   = snappedV ? 'block' : 'none';

    fCanvas.requestRenderAll();
    updatePropPanel(obj);
});

fCanvas.on('object:modified', () => { document.getElementById('guideOverlay').style.display = 'none'; });
fCanvas.on('mouse:up', () => { document.getElementById('guideOverlay').style.display = 'none'; refreshLayers(); });

// ── Property Panel ───────────────────────────────────────────
fCanvas.on('selection:created', e => updatePropPanel(e.selected[0]));
fCanvas.on('selection:updated', e => updatePropPanel(e.selected[0]));
fCanvas.on('selection:cleared', () => {
    document.getElementById('propContent').innerHTML = '<span class="text-gray-600">Bir nesneye tıkla…</span>';
});
fCanvas.on('object:scaling', e => updatePropPanel(e.target));
fCanvas.on('object:rotating', e => updatePropPanel(e.target));

function updatePropPanel(obj) {
    if (!obj) return;
    const cx = Math.round(obj.getCenterPoint().x);
    const cy = Math.round(obj.getCenterPoint().y);
    const w  = Math.round(obj.getScaledWidth());
    const h  = Math.round(obj.getScaledHeight());
    const r  = Math.round(obj.angle || 0);
    const isText = obj.type === 'i-text' || obj.type === 'text';

    let html = `
    <div class="grid grid-cols-2 gap-1 text-[10px]">
        <div><span class="text-gray-600">Ad:</span> <span class="text-white">${obj.name || '—'}</span></div>
        <div><span class="text-gray-600">Tür:</span> <span class="text-emerald-400">${obj.type}</span></div>
        <div><span class="text-gray-600">X:</span> <span class="text-white">${cx}px</span></div>
        <div><span class="text-gray-600">Y:</span> <span class="text-white">${cy}px</span></div>
        <div><span class="text-gray-600">W:</span> <span class="text-white">${w}px</span></div>
        <div><span class="text-gray-600">H:</span> <span class="text-white">${h}px</span></div>
        <div><span class="text-gray-600">Açı:</span> <span class="text-white">${r}°</span></div>
    </div>`;

    if (isText) {
        html += `
        <div class="mt-2 flex gap-2 items-center">
            <label class="text-gray-600 text-[9px]">Renk</label>
            <input type="color" value="${obj.fill || '#ffffff'}" class="color-pick w-7 h-7" onchange="changeSelectedColor(this.value)">
            <label class="text-gray-600 text-[9px] ml-2">Boyut</label>
            <input type="number" value="${Math.round(obj.fontSize)}" class="cyber-input w-16" onchange="changeSelectedSize(this.value)">
        </div>`;
    }

    html += `
    <div class="mt-2 flex gap-2">
        <button class="btn btn-ghost flex-1 py-1" onclick="centerSelectedH()">↔ Yatay Ortala</button>
        <button class="btn btn-ghost flex-1 py-1" onclick="centerSelectedV()">↕ Dikey Ortala</button>
    </div>
    <button class="btn btn-ghost mt-1 py-1" style="color:#ef4444;border-color:#3f1515;" onclick="deleteSelected()">🗑 Sil</button>`;

    document.getElementById('propContent').innerHTML = html;
}

function changeSelectedColor(v) { const o = fCanvas.getActiveObject(); if (o) { o.set({ fill: v }); fCanvas.requestRenderAll(); } }
function changeSelectedSize(v)  { const o = fCanvas.getActiveObject(); if (o) { o.set({ fontSize: parseInt(v) }); fCanvas.requestRenderAll(); } }

function centerSelectedH() {
    const o = fCanvas.getActiveObject(); if (!o) return;
    o.set({ left: o.originX === 'center' ? CANVAS_W / 2 : CANVAS_W / 2 - o.getScaledWidth() / 2 });
    fCanvas.requestRenderAll();
}
function centerSelectedV() {
    const o = fCanvas.getActiveObject(); if (!o) return;
    o.set({ top: o.originY === 'center' ? CANVAS_H / 2 : CANVAS_H / 2 - o.getScaledHeight() / 2 });
    fCanvas.requestRenderAll();
}
function deleteSelected() {
    const o = fCanvas.getActiveObject();
    if (o) { fCanvas.remove(o); fCanvas.requestRenderAll(); refreshLayers(); }
}

// ── Layer List ───────────────────────────────────────────────
function refreshLayers() {
    const list = document.getElementById('layerList');
    const objs = fCanvas.getObjects().slice().reverse();
    list.innerHTML = objs.length === 0
        ? '<div class="text-gray-600">Henüz katman yok.</div>'
        : objs.map((o, i) => `
            <div class="flex items-center gap-2 bg-gray-900 border border-gray-800 rounded px-2 py-1.5 cursor-pointer hover:border-emerald-900"
                 onclick="selectLayer(${fCanvas.getObjects().length - 1 - i})">
                <span class="text-[8px] text-gray-600">${fCanvas.getObjects().length - 1 - i}</span>
                <span class="flex-1 text-[10px] truncate">${o.name || o.type}</span>
                <span class="text-[8px] text-emerald-700">${o.type}</span>
            </div>`).join('');
}

function selectLayer(idx) {
    const obj = fCanvas.item(idx);
    if (obj) { fCanvas.setActiveObject(obj); fCanvas.requestRenderAll(); updatePropPanel(obj); }
}

// ── Bulk Production ──────────────────────────────────────────
async function startBulkProduction() {
    const names = document.getElementById('bulkNames').value
        .split('\n').map(n => n.trim()).filter(n => n.length > 0);
    if (names.length === 0) { alert('Liste boş'); return; }

    const prefix   = document.getElementById('prefix').value;
    const startNum = parseInt(document.getElementById('startNum').value) || 202600;
    const eventTag = document.getElementById('eventTag').value;

    const nameObj  = fCanvas.getObjects().find(o => o.name === 'layerName');
    const idObj    = fCanvas.getObjects().find(o => o.name === 'layerID');
    const eventObj = fCanvas.getObjects().find(o => o.name === 'layerEvent');

    let qrLeft = 1380, qrTop = 700, qrSX = 1, qrSY = 1;
    if (qrObjectOnCanvas) {
        qrLeft = qrObjectOnCanvas.left;
        qrTop  = qrObjectOnCanvas.top;
        qrSX   = qrObjectOnCanvas.scaleX;
        qrSY   = qrObjectOnCanvas.scaleY;
        fCanvas.remove(qrObjectOnCanvas);
        qrObjectOnCanvas = null;
    }

    const progress = document.getElementById('bulkProgress');
    const progText = document.getElementById('bulkProgressText');
    const progBar  = document.getElementById('bulkProgressBar');
    progress.classList.remove('hidden');

    for (let i = 0; i < names.length; i++) {
        const name   = names[i];
        const certID = `${prefix}-${startNum + i}`;
        const qrUrl  = (document.getElementById('qrUrl').value || window.location.origin + '/index.php') + '?id=' + certID;

        if (nameObj)  nameObj.text  = name.toUpperCase();
        if (idObj)    idObj.text    = 'CERT_ID: ' + certID;
        if (eventObj) eventObj.text = eventTag.toUpperCase();
        fCanvas.requestRenderAll();

        const qrData = await generateQR(qrUrl, 140);

        await new Promise(resolve => {
            fabric.Image.fromURL(qrData, qr => {
                qr.set({ left: qrLeft, top: qrTop, scaleX: qrSX, scaleY: qrSY, selectable: false });
                fCanvas.add(qr);
                fCanvas.requestRenderAll();
                setTimeout(() => {
                    const link   = document.createElement('a');
                    link.download = `${certID}_${name.replace(/\s+/g, '_')}.png`;
                    link.href     = fCanvas.toDataURL({ format: 'png', quality: 1, multiplier: 1 });
                    link.click();
                    fCanvas.remove(qr);
                    resolve();
                }, 80);
            });
        });

        const pct = Math.round(((i + 1) / names.length) * 100);
        progBar.style.width    = pct + '%';
        progText.textContent   = `${i + 1}/${names.length} — ${name} ✓`;
        await new Promise(r => setTimeout(r, 200));
    }

    progText.textContent = `✅ ${names.length} sertifika başarıyla üretildi!`;

    if (document.getElementById('qrUrl').value) {
        const qrData = await generateQR(document.getElementById('qrUrl').value + '?id=PREVIEW', 140);
        fabric.Image.fromURL(qrData, qr => {
            qr.set({ left: qrLeft, top: qrTop, scaleX: qrSX, scaleY: qrSY, name: 'layerQR' });
            fCanvas.add(qr);
            qrObjectOnCanvas = qr;
            fCanvas.requestRenderAll();
        });
    }
}

// ── Delete key ───────────────────────────────────────────────
document.addEventListener('keydown', e => {
    if ((e.key === 'Delete' || e.key === 'Backspace') && !['INPUT','TEXTAREA'].includes(document.activeElement.tagName)) {
        deleteSelected();
    }
});
</script>
</body>
</html>
