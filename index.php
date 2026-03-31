<?php
$certID = $_GET['id'] ?? "";
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>METU NCC Cybersecurity Society | Verification</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800&display=swap" rel="stylesheet">

<style>

body{
background:#000;
color:#e6edf3;
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
}

.cyber-input:focus{
border-color:#10b981;
outline:none;
box-shadow:0 0 10px rgba(16,185,129,0.2);
}

</style>
</head>

<body class="min-h-screen flex flex-col items-center justify-center p-6">

<div class="w-full max-w-2xl text-center">

<h1 class="text-4xl font-extrabold mb-2 tracking-tighter uppercase">
Sertifika Doğrulama
</h1>

<p class="text-gray-500 mb-8 font-medium">
METU NCC Cybersecurity Society Etkinlik Kayıtları
</p>

<div class="cyber-card p-8 shadow-2xl">

<div class="flex flex-col gap-4">

<input
type="text"
id="certID"
value="<?php echo htmlspecialchars($certID); ?>"
placeholder="Sertifika ID'sini Girin (Örn: MNCS-12345)"
class="cyber-input p-4 rounded-lg text-center font-mono">

<button onclick="verify()"
class="bg-[#10b981] hover:bg-[#0da371] text-black font-black py-4 rounded-lg transition-all tracking-widest uppercase">
Doğrula
</button>

</div>

<div id="result" class="hidden mt-8 pt-8 border-t border-gray-800">

<h2 class="text-[#10b981] font-bold text-xl uppercase mb-2">
✓ Sertifika Geçerli
</h2>

<div class="text-left space-y-2 text-sm text-gray-400 font-mono">

<p id="resName"></p>
<p id="resEvent"></p>
<p id="resID"></p>

</div>

<button id="liBtn"
class="mt-6 w-full bg-[#0a66c2] text-white py-3 rounded-lg font-bold">
LinkedIn'e Ekle
</button>

</div>

</div>

<div class="mt-12 opacity-30 hover:opacity-100 transition">
<a href="admin.php" class="text-xs uppercase tracking-widest">
Yönetim Paneli Girişi
</a>
</div>

</div>

<script>

function verify(){

const id=document.getElementById('certID').value;

if(!id){
alert("ID giriniz.");
return;
}

document.getElementById('result').classList.remove('hidden');

document.getElementById('resName').innerText="SAHİBİ: MERT SERT";

document.getElementById('resEvent').innerText="ETKİNLİK: SİBER GÜVENLİĞE GİRİŞ -1";

document.getElementById('resID').innerText="ID: "+id;

}

window.onload=function(){

const id="<?php echo $certID; ?>";

if(id){
verify();
}

};

</script>

</body>
</html>
