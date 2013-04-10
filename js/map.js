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

//L.control.zoom({position : "topright"}).addTo(map);
//L.geoJson(countryData, {style: style, onEachFeature: onEachFeature}).addTo(map);
var isiPad = navigator.userAgent.match(/iPad/i) != null;
if (isiPad){
    $(".footer").html("is ipad");
    map.setZoom(2);
}

function getColor(d) {
    return d > 8  ? '#045A8D' :
           d > 6   ? '#2B8CBE' :
           d > 4   ? '#74A9CF' :
           d > 2   ? '#BDC9E1' :
                      'transparent';
}

function style(feature) {
    return {
        fillColor: getColor(feature.properties.projects),
        weight: 0,
        opacity: 1,
        color: 'white',
        dashArray: '',
        fillOpacity: 0.7
    };
}

// function highlightFeature(e) {
//     var layer = e.target;

//     layer.setStyle({
//         weight: 3,
//         color: '#666',
//         dashArray: '',
//         fillOpacity: 0.7
//     });

//     if (!L.Browser.ie && !L.Browser.opera) {
//         layer.bringToFront();
//     }
	
// 	info.update(layer.feature.properties);
// }

// function resetHighlight(e) {
//     geojson.resetStyle(e.target);
// 	info.update();
// }

// var geojson;

// function zoomToFeature(e) {
//     map.fitBounds(e.target.getBounds());
// }

// function onEachFeature(feature, layer) {
//     layer.on({
//         mouseover: highlightFeature,
//         mouseout: resetHighlight,
//         click: zoomToFeature
//     });
// }
//var info = L.control();
//
//info.onAdd = function (map) {
//    this._div = L.DomUtil.create('div', 'info'); // create a div with a class "info"
//    this.update();
//    return this._div;
//};
//
//// method that we will use to update the control based on feature properties passed
//info.update = function (props) {
//    this._div.innerHTML = '<h4>Amount of IATI projects</h4>' +  (props ?
//        '<b>' + props.name + '</b><br />' + props.projects + ' projects'
//        : 'Hover over a country');
//};
//
//info.addTo(map);
/*
var legend = L.control({position: 'bottomright'});

legend.onAdd = function (map) {

    var div = L.DomUtil.create('div', 'info legend'),
        grades = [0, 10, 20, 50, 100],
        labels = [];

    // loop through our density intervals and generate a label with a colored square for each interval
    for (var i = 0; i < grades.length; i++) {
        div.innerHTML +=
            '<i style="background:' + getColor(grades[i] + 1) + '"></i> ' +
            grades[i] + (grades[i + 1] ? '&ndash;' + grades[i + 1] + '<br>' : '+');
    }

    return div;
};

legend.addTo(map);
*/


//for (var key in countryloc){
//	var circle = L.circle(new L.LatLng(countryloc[key].longitude, countryloc[key].latitude), 100000, {
//		color: 'red',
//		fillColor: '#f03',
//		fillOpacity: 0.5
//	}).addTo(map);
//}