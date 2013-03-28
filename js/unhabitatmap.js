var map = L.map('map', {attributionControl: false, scrollWheelZoom: false}).setView([10.505, 25.09], 3);
L.tileLayer('http://{s}.tile.cloudmade.com/88497ce4207141ec955d7c63aaf2b673/997/256/{z}/{x}/{y}.png', {
    maxZoom: 18
}).addTo(map);
//L.geoJson(countryData, {style: style, onEachFeature: onEachFeature}).addTo(map);

function getColor(d) {
    return d > 100  ? '#045A8D' :
           d > 50   ? '#2B8CBE' :
           d > 20   ? '#74A9CF' :
           d > 10   ? '#BDC9E1' :
                      '#F1EEF6';
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

function highlightFeature(e) {
    var layer = e.target;

    layer.setStyle({
        weight: 3,
        color: '#666',
        dashArray: '',
        fillOpacity: 0.7
    });

    if (!L.Browser.ie && !L.Browser.opera) {
        layer.bringToFront();
    }
	
	info.update(layer.feature.properties);
}

function resetHighlight(e) {
    geojson.resetStyle(e.target);
	info.update();
}

var geojson;

function zoomToFeature(e) {
    map.fitBounds(e.target.getBounds());
}

function onEachFeature(feature, layer) {
    layer.on({
        mouseover: highlightFeature,
        mouseout: resetHighlight,
        click: zoomToFeature
    });
}

var info = L.control();

info.onAdd = function (map) {
    this._div = L.DomUtil.create('div', 'info'); // create a div with a class "info"
    this.update();
    return this._div;
};

// method that we will use to update the control based on feature properties passed
info.update = function (props) {
    this._div.innerHTML = '<h4>Amount of IATI projects</h4>' +  (props ?
        '<b>' + props.name + '</b><br />' + props.projects + ' projects'
        : 'Hover over a country');
};

info.addTo(map);




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


//for (var key in countryloc){
//	var circle = L.circle(new L.LatLng(countryloc[key].longitude, countryloc[key].latitude), 100000, {
//		color: 'red',
//		fillColor: '#f03',
//		fillOpacity: 0.5
//	}).addTo(map);
//}