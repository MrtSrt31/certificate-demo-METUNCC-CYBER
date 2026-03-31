// script.js
const canvas = document.getElementById('certCanvas');
const ctx = canvas.getContext('2d');

function checkAuth() {
    // Şifreyi 'metucyber2026' yapalım, istersen değiştirirsin
    if(document.getElementById('adminPass').value === 'metucyber2026') {
        document.getElementById('loginOverlay').style.display = 'none';
        document.getElementById('adminPanel').style.display = 'block';
    } else { alert("Unauthorized Access!"); }
}

async function renderCert() {
    const file = document.getElementById('bgInput').files[0];
    if(!file) return alert("Hata: Template missing!");

    const reader = new FileReader();
    reader.onload = function(e) {
        const img = new Image();
        img.onload = function() {
            ctx.drawImage(img, 0, 0, 1600, 900);
            ctx.textAlign = "center";
            
            // Name
            ctx.fillStyle = "#ffffff";
            ctx.font = "800 85px 'Inter'";
            ctx.fillText(document.getElementById('userName').value.toUpperCase(), 800, 430);

            // Event & Date
            ctx.fillStyle = "#10b981";
            ctx.font = "600 40px 'Inter'";
            ctx.fillText(document.getElementById('eventName').value, 800, 540);
            
            ctx.fillStyle = "rgba(255,255,255,0.6)";
            ctx.font = "30px 'JetBrains Mono'";
            ctx.fillText(document.getElementById('eventDate').value, 800, 610);

            // Unique ID Generation [cite: 184]
            const certID = "MNCS-" + Date.now().toString().slice(-6);
            const verifyURL = window.location.origin + "/index.html?id=" + certID;

            // QR & ID Stamp [cite: 224]
            QRCode.toCanvas(verifyURL, { width: 140, margin: 2, color: { dark: '#ffffff', light: '#00000000' } }, (err, qr) => {
                ctx.drawImage(qr, 1420, 720);
                ctx.fillStyle = "rgba(255,255,255,0.3)";
                ctx.font = "12px 'JetBrains Mono'";
                ctx.textAlign = "right";
                ctx.fillText("CERT ID: " + certID, 1560, 880); // ID Mühürü eklendi
                document.getElementById('dlBtn').style.display = 'block';
            });
        };
        img.src = e.target.result;
    };
    reader.readAsDataURL(file);
}

function saveImage() {
    const link = document.createElement('a');
    link.download = `METU-NCC-Cyber-Cert.png`;
    link.href = canvas.toDataURL('image/png');
    link.click();
}
