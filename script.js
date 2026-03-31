// script.js içeriği
let canvas = document.getElementById("canvas");
let ctx = canvas.getContext("2d");

function generate() {
    let name = document.getElementById("name").value;
    let event = document.getElementById("event").value;
    let date = document.getElementById("date").value;
    let bgFile = document.getElementById("bgUpload").files[0];

    let reader = new FileReader();
    reader.onload = function(eventImg) {
        let img = new Image();
        img.onload = function() {
            ctx.drawImage(img, 0, 0, 1600, 900); // Arkaplanı çiz
            
            // Yazı Ayarları (Cyber Tasarım)
            ctx.fillStyle = "#ffffff";
            ctx.font = "bold 60px Arial";
            ctx.textAlign = "center";
            
            ctx.fillText(name, 800, 450); // İsim tam ortaya
            ctx.font = "40px Arial";
            ctx.fillText(event, 800, 550); // Etkinlik altına
            
            // QR Kod Oluşturma (Doğrulama Linki İçin)
            let certID = "MC-" + Math.floor(Math.random() * 10000);
            let verifyURL = window.location.origin + "/index.html?id=" + certID;
            
            QRCode.toCanvas(verifyURL, { width: 150 }, function (err, qrCanvas) {
                ctx.drawImage(qrCanvas, 1400, 700); // QR sağ alta
                
                // Final görseli göster
                document.getElementById("finalImage").src = canvas.toDataURL("image/png");
            });
        }
        img.src = eventImg.target.result;
    }
    reader.readAsDataURL(bgFile);
}
