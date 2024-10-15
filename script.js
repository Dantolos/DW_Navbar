const DW_GLOBAL_NAVBAR = document.getElementById("dw__global_navbar");
const apiUrl = "https://demenzworld.com/wp-json/dw/navbar";

fetch(apiUrl)
	.then((response) => {
		if (!response.ok) {
			throw new Error("Netzwerkantwort war nicht OK");
		}
		return response.json(); // Die Antwort als JSON konvertieren
	})
	.then((data) => {
		DW_GLOBAL_NAVBAR.innerHTML = data.toplevel_navbar;
	})
	.catch((error) => {
		console.error("Es gab ein Problem mit der Fetch-Operation:", error);
	});

console.log(da);
