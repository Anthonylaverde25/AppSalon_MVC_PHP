function iniciarApp(){buscarPorFecha()}function buscarPorFecha(){document.querySelector("#fecha").addEventListener("input",n=>{const e=n.target.value;window.location="?fecha="+e,console.log(e)})}document.addEventListener("DOMContentLoaded",(function(){iniciarApp()}));