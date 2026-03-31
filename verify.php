<?php
$id = htmlspecialchars($_GET['id'] ?? "N/A");
$name = htmlspecialchars($_GET['name'] ?? "Participant");
$event = htmlspecialchars($_GET['event'] ?? "Cyber Security Workshop");
$date = htmlspecialchars($_GET['date'] ?? "");
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Certificate Verification</title>

<style>

body{
background:#0d1117;
color:white;
font-family:Arial,sans-serif;
text-align:center;
padding:50px;
}

.card{
background:#161b22;
padding:40px;
border-radius:10px;
max-width:600px;
margin:auto;
box-shadow:0 4px 15px rgba(0,0,0,0.5);
}

h1{color:#58a6ff;}
h2{margin:10px 0;color:#ffffff;}

#cid{
color:#8b949e;
font-family:monospace;
}

button{
background-color:#0a66c2;
color:white;
border:none;
padding:12px 24px;
font-size:16px;
border-radius:5px;
cursor:pointer;
font-weight:bold;
margin-top:20px;
transition:background 0.3s;
}

button:hover{
background-color:#004182;
}

</style>
</head>

<body>

<div class="card">

<h1>Certificate Verification</h1>

<p id="cid">Certificate ID: <?php echo $id; ?></p>

<p>This certificate was issued to</p>
<h2 id="participantName"><?php echo $name; ?></h2>

<p>for completing</p>
<h2 id="eventName"><?php echo $event; ?></h2>

<p>Issued by</p>
<h3>METU NCC Cyber</h3>

<a id="linkedin" target="_blank">
<button>Add to LinkedIn</button>
</a>

</div>

<script>

const certID = "<?php echo $id; ?>";
const name = "<?php echo $name; ?>";
const event = "<?php echo $event; ?>";
const date = "<?php echo $date; ?>";

let linkedinURL =
"https://www.linkedin.com/profile/add?startTask=CERTIFICATION_NAME"+
"&name="+encodeURIComponent(event)+
"&organizationName="+encodeURIComponent("METU NCC Cyber")+
"&certId="+encodeURIComponent(certID)+
"&issueYear="+new Date().getFullYear()+
"&issueMonth="+(new Date().getMonth()+1);

document.getElementById("linkedin").href = linkedinURL;

</script>

</body>
</html>
