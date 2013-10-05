var map = L.map('map', {
    attributionControl: false, 
    scrollWheelZoom: false,
    zoom: 3,
    minZoom: 2,
    maxZoom:6,
    continuousWorld: 'true'
}).setView([10.505, 25.09], 3);

L.tileLayer('http://{s}.tile.cloudmade.com/f5fcd6d7e17b4d368536e1e6ba5bea74/90076/256/{z}/{x}/{y}.png', {
    maxZoom: 6
}).addTo(map);

var currently_selected_country = "none";

function set_currently_selected_country(value){
    currently_selected_country = value;
}
