const DW_GLOBAL_NAVBAR = document.getElementById("DW__GLOBAL_NAVBAR");
const apiUrl = "https://demenzworld.com/wp-json/dw/navbar";

setTimeout(() => {
	document.querySelector(".dw__global_nav_embed_wrapper").style.opacity = "1";
}, 100);

const DW_GLOBAL_FOOTER = document.querySelector("#DW__GLOBAL_FOOTER");
document.body.appendChild(DW_GLOBAL_FOOTER);
