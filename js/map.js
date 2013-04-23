var map = L.map('map', {
    attributionControl: false, 
    scrollWheelZoom: false,
    zoom: 3,
    minZoom: 2,
    maxZoom:6
}).setView([10.505, 25.09], 3);

L.tileLayer('http://{s}.tile.cloudmade.com/07c00b1d0e4c4bed9a926bdca23d2232/90076/256/{z}/{x}/{y}.png', {
    maxZoom: 6
}).addTo(map);

var currently_selected_country = "none";

function set_currently_selected_country(value){
    currently_selected_country = value;
}
