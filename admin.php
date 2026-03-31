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
body{
    background:#000;
    color:#fff;
    font-family:'Inter',sans-serif;
    background-image:radial-gradient(#1f2937 0.8px,transparent 0.8px);
    background-size:30px 30px;
}

.cyber-card{
    background:#161b22;
    border:1px solid #30363d;
    border-radius:12px;
}

.cyber-input{
    background:#0d1117;
    border:1px solid #30363d;
    color:white;
    border-radius:4px;
    font-size:12px;
    padding:10px;
    width:100%;
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
}

#canvas-wrapper{
    display:flex;
    align-items:center;
    justify-content:center;
    width:100%;
    height:100%;
    position:relative;
    background:#161b22; /* background önizleme alanı */
}
</style>
</head>

<body class="flex overflow-hidden">

<!-- Sidebar -->
<div class="w-96 sidebar p-6 flex flex-col shrink-0">

    <div class="mb-6 border-b border-gray-800 pb-4">
        <h1 class="text-[#10b981] font-black text-sm tracking-widest uppercase">Command Center v5.0</h1>
        <p class="text-[10px] opacity-50 font-mono mt-1">OPERATOR: <span class="text-white">ADMIN DEMO</span></p>
    </div>

    <div class="space-y-6 flex-1">
        <div>
            <label class="text-[10px] font-bold text-gray-500 uppercase mb-2 block">1. Şablon Yükle</label>
            <input type="file" id="bgInput" class="text-[10px] text-gray-400">
        </div>

        <div>
            <button onclick="setupLayout()" class="w-full bg-emerald-900/30 text-emerald-500 border border-emerald-900/50 text-[10px] py-3 rounded font-bold hover:bg-emerald-600 hover:text-black transition uppercase tracking-widest">
            Akıllı Tasarımı Başlat
            </button>
        </div>

        <div class="pt-4 border-t border-gray-800">
            <label class="text-[10px] font-bold text-gray-500 uppercase mb-2 block">Katılımcı Listesi</label>
            <textarea id="bulkNames" rows="6" class="cyber-input" placeholder="Mert Sert
Ahmet Yılmaz
Ayşe Demir"></textarea>
        </div>

        <div class="pt-4 border-t border-gray-800">
            <label class="text-[10px] font-bold text-gray-500 uppercase mb-2 block">Seri Üretim</label>
            <div class="grid grid-cols-2 gap-2 mb-4">
                <input id="prefix" value="METUNCC_CYBER" class="cyber-input text-center">
                <input id="eventTag" value="Cyber Workshop" class="cyber-input text-center">
            </div>
            <button onclick="startBulkProduction()" class="w-full bg-emerald-600 text-black font-black py-4 rounded-lg text-[11px] uppercase tracking-widest">
            TÜMÜNÜ BAS VE İNDİR
            </button>
        </div>
    </div>
</div>

<!-- Canvas Preview Area -->
<div class="flex-1 flex items-center justify-center p-12">
    <div id="canvas-wrapper" class="shadow-2xl">
        <canvas id="fCanvas"></canvas>
    </div>
</div>

<script>
const fCanvas = new fabric.Canvas('fCanvas',{
    width:1600,
    height:900,
    backgroundColor:'#161b22',
    preserveObjectStacking:true
});

let currentBg=null;

// Background yükleme
document.getElementById('bgInput').onchange = e => {
    const reader = new FileReader();
    reader.onload = f => {
        fabric.Image.fromURL(f.target.result, img => {
            currentBg = img;
            img.selectable = false;
            img.evented = false;
            img.scaleToWidth(1600);
            img.scaleToHeight(900);
            fCanvas.setBackgroundImage(img, fCanvas.renderAll.bind(fCanvas));
        });
    };
    reader.readAsDataURL(e.target.files[0]);
};

// Layer temizleme
function clearLayers(){
    const objs = [...fCanvas.getObjects()];
    objs.forEach(o => fCanvas.remove(o));
}

// Layer oluşturma
function setupLayout(){
    clearLayers();

    const nameLayer = new fabric.IText("KATILIMCI ADI",{
        left: 800,
        top: 430,
        fontSize: 85,
        fontFamily: 'Inter',
        fontWeight: '800',
        fill: '#ffffff',
        originX: 'center',
        name: 'layerName'
    });

    const eventLayer = new fabric.IText(document.getElementById('eventTag').value.toUpperCase(),{
        left: 800,
        top: 540,
        fontSize: 40,
        fontFamily: 'Inter',
        fontWeight: '700',
        fill: '#10b981',
        originX: 'center',
        name: 'layerEvent'
    });

    const idLayer = new fabric.IText("CERT_ID",{
        left: 1560,
        top: 875,
        fontSize: 14,
        fontFamily: 'JetBrains Mono',
        fill: '#ffffff',
        opacity: 0.3,
        originX: 'right',
        name: 'layerID'
    });

    fCanvas.add(nameLayer, eventLayer, idLayer);
    fCanvas.setActiveObject(nameLayer);
    fCanvas.requestRenderAll();
}

// Seri üretim
async function startBulkProduction(){
    const names = document.getElementById('bulkNames').value
        .split('\n')
        .map(n => n.trim())
        .filter(n => n.length>0);

    if(names.length === 0){
        alert("Liste boş");
        return;
    }

    const prefix = document.getElementById('prefix').value;
    const eventTag = document.getElementById('eventTag').value;

    const nameObj = fCanvas.getObjects().find(o=>o.name==="layerName");
    const idObj = fCanvas.getObjects().find(o=>o.name==="layerID");
    const eventObj = fCanvas.getObjects().find(o=>o.name==="layerEvent");

    for(let i=0; i<names.length; i++){
        const name = names[i];
        const certID = `${prefix}-${202600+i}`;

        if(nameObj) nameObj.text = name.toUpperCase();
        if(idObj) idObj.text = "CERT_ID: "+certID;
        if(eventObj) eventObj.text = eventTag.toUpperCase();

        fCanvas.requestRenderAll();

        const qrData = await generateQR(window.location.origin+"/index.php?id="+certID);

        await new Promise(resolve => {
            fabric.Image.fromURL(qrData, qr => {
                qr.set({
                    left: 1400, // QR yeri (isteğe göre değiştirebilirsin)
                    top: 700,
                    scaleX: 0.8,
                    scaleY: 0.8,
                    selectable: false
                });
                fCanvas.add(qr);
                fCanvas.requestRenderAll();

                const link = document.createElement("a");
                link.download = `${certID}_${name.replace(/\s/g,'_')}.png`;
                link.href = fCanvas.toDataURL({format:"png",quality:1});
                link.click();

                fCanvas.remove(qr);
                resolve();
            });
        });

        await new Promise(r=>setTimeout(r,200));
    }

    alert(names.length+" sertifika üretildi");
}

// QR oluştur
function generateQR(url){
    return new Promise(resolve=>{
        const canvas = document.createElement("canvas");
        QRCode.toCanvas(canvas, url, {
            width:140,
            margin:2,
            color:{dark:'#ffffff', light:'#00000000'}
        }, ()=>{
            resolve(canvas.toDataURL());
        });
    });
}

// Canvas responsive
function resize(){
    const wrapper = document.getElementById("canvas-wrapper");
    const scale = wrapper.clientWidth/1600;
    fCanvas.setDimensions({
        width:1600*scale,
        height:900*scale
    });
    fCanvas.setViewportTransform([scale,0,0,scale,0,0]);
    fCanvas.renderAll();
}

window.addEventListener("resize", resize);
setTimeout(resize,300);

</script>
</body>
</html>
