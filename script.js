const canvas = document.getElementById('certCanvas');
const ctx = canvas.getContext('2d');

function generate() {
    const file = document.getElementById('bgLoad').files[0];
    if(!file) return alert("Hata: Şablon yüklenmedi!");

    const reader = new FileReader();
    reader.onload = function(e) {
        const img = new Image();
        img.onload = function() {
            // Arkaplanı çiz
            ctx.drawImage(img, 0, 0, 1600, 900);
            
            // Yazı ayarları
            ctx.fillStyle = "#ffffff";
            ctx.textAlign = "center";
            ctx.font = "bold 70px Inter";
            
            // Bilgileri yazdır
            ctx.fillText(document.getElementById('name').value.toUpperCase(), 800, 420);
            ctx.font = "40px Inter";
            ctx.fillText(document.getElementById('event').value, 800, 520);
            ctx.fillText(document.getElementById('date').value, 800, 600);

            // QR Kod Oluştur (Verification Link)
            const certID = "MC-" + Math.floor(Math.random() * 100000);
            const verifyURL = window.location.origin + "/index.html?id=" + certID;
            
            QRCode.toCanvas(verifyURL, { width: 140, margin: 1 }, (err, qrCanvas) => {
                ctx.drawImage(qrCanvas, 1400, 700);
                alert("Sertifika Başarıyla Mühürlendi! ID: " + certID);
            });
        };
        img.src = e.target.result;
    };
    reader.readAsDataURL(file);
}

function download() {
    const link = document.createElement('a');
    link.download = 'metu-cyber-cert.png';
    link.href = canvas.toDataURL();
    link.click();
}
