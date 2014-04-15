var map = L.map('map', {
    attributionControl: false, 
    scrollWheelZoom: false,
    zoom: 3,
    minZoom: 2,
    maxZoom:6,
    continuousWorld: 'true'
}).setView([10.505, 25.09], 3);


L.tileLayer('https://{s}.tiles.mapbox.com/v3/zimmerman2014.hmj09g6h/{z}/{x}/{y}.png', {
    maxZoom: 6
}).addTo(map);

var currently_selected_country = "none";

function set_currently_selected_country(value){
    currently_selected_country = value;
}
