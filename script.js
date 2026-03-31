let canvas=document.getElementById("canvas")
let ctx=canvas.getContext("2d")

function generate(){

let name=document.getElementById("name").value
let event=document.getElementById("event").value
let date=document.getElementById("date").value
let type=document.getElementById("type").value
let signer=document.getElementById("signer").value

let file=document.getElementById("bg").files[0]

let img=new Image()

img.onload=function(){

ctx.drawImage(img,0,0,1600,900)

ctx.fillStyle="white"

ctx.font="70px Arial"
ctx.fillText(name,500,400)

ctx.font="40px Arial"
ctx.fillText(event,500,500)

ctx.fillText(date,500,580)

ctx.fillText(type,500,650)

ctx.fillText("Signed by "+signer,500,720)

let id="MC-"+Math.floor(Math.random()*100000)

let verifyURL=window.location.origin+"/certificate.html?id="+id

QRCode.toCanvas(canvas,verifyURL,{width:120},function(err,qr){

ctx.drawImage(qr,1400,720)

ctx.font="20px Arial"
ctx.fillText(id,100,850)

})

}

img.src=URL.createObjectURL(file)

}

function download(){

let link=document.createElement("a")

link.download="certificate.png"

link.href=canvas.toDataURL()

link.click()

}
