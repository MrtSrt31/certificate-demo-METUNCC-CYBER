const canvas = document.getElementById("canvas");
const ctx = canvas.getContext("2d");

async function generate() {
    const bgFile = document.getElementById("bgUpload").files[0];
    if(!bgFile) return alert("Önce bir şablon yükle!");

    const name = document.getElementById("name").value;
    const event = document.getElementById("event").value;
    const date = document.getElementById("date").value;
    const signer = document.getElementById("signer").value;
    const certID = "MC-" + Date.now().toString().slice(-6); [cite: 973]

    const reader = new FileReader();
    reader.onload = function(e) {
        const img = new Image();
        img.onload = function() {
            ctx.drawImage(img, 0, 0); [cite: 1241]
            
            // Profesyonel Yazı Tipi ve Konumlandırma
            ctx.fillStyle = "#1a1a1a"; // Şablon rengine göre ayarla
            ctx.textAlign = "center";

            // İsim (Koordinatlar şablona göre ayarlanmalı)
            ctx.font = "bold 70px Inter"; [cite: 1242]
            ctx.fillText(name, 800, 420); [cite: 1243]

            // Etkinlik ve Tarih
            ctx.font = "40px Inter";
            ctx.fillText(event, 800, 520); [cite: 1244]
            ctx.fillText(date, 800, 600); [cite: 1245]
            
            // Alt Bilgi ve İmza
            ctx.font = "25px Inter";
            ctx.fillText("Signed by: " + signer, 800, 750); [cite: 972]

            // QR Kod Entegrasyonu (Doğrulama Linki)
            const verifyURL = window.location.origin + "/index.html?id=" + certID; [cite: 974, 1250]
            QRCode.toCanvas(verifyURL, { width: 120, margin: 1 }, (err, qr) => {
                ctx.drawImage(qr, 1400, 700); [cite: 976, 1230]
                document.getElementById("downloadArea").style.display = "block";
            });
        };
        img.src = e.target.result;
    };
    reader.readAsDataURL(bgFile);
}

function download() {
    const link = document.createElement('a');
    link.download = 'METU-Cyber-Certificate.png'; [cite: 1419]
    link.href = canvas.toDataURL();
    link.click();
}
