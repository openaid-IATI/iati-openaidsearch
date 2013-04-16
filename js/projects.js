var geojson;

function initialize_projects_map(url){
    var project_geojson = get_project_data(url);
    unload_project_map();
    load_project_map(project_geojson);
}


function get_project_data(url){

    var project_geojson = [];

    $.ajax({
        type: 'GET',
         url: url,
         async: false,
         contentType: "application/json",
         dataType: 'json',
         success: function(data){
          indicator_json_data = data;
         }
    });

    return project_geojson;
}

function unload_project_map(){
    // remove geojson layer from map
}

function load_project_map(project_geojson){
    geojson = L.geoJson(countryData, {style: style,onEachFeature: function(feature,layer) {

    layer.on({
        mouseover: highlightFeature,
        mouseout: resetHighlight,
        click: showPopup
    });
    }}).addTo(map); 
}




function getColor(d) {
    return d > 6  ? '#045A8D' :
           d > 1   ? '#2476A2' :
           d > 0   ? '#2B8CBE' :
    // return d > 8  ? '#FE6305' :
    //        d > 4   ? '#FE7421' :
    //        d > 0   ? '#FE8236' :
           //d > 220   ? '#2B8CBE' :
                      'transparent';
}

      // return d > 8  ? '#045A8D' :
      //      d > 4   ? '#2476A2' :
      //      d > 0   ? '#2B8CBE' :

function getWeight(d) {
    return d > 0  ? 1 :
                      0;
}

function style(feature) {
    return {
        fillColor: getColor(feature.properties.projects),
        weight: getWeight(feature.properties.projects),
        opacity: 1,
        color: '#FFF',
        dashArray: '',
        fillOpacity: 0.7
    };
}

function highlightFeature(e) {
    var layer = e.target;
    
    if(typeof layer.feature.properties.projects != "undefined"){
        
        if (currently_selected_country != layer.feature.properties.name){
            set_currently_selected_country(layer.feature.properties.name);
            showPopup(e);
        }

        layer.setStyle({
            weight: 2,
            fillOpacity: 0.9
        });

        if (!L.Browser.ie && !L.Browser.opera) {
            layer.bringToFront();
        }
    }
}

function showPopup(e){
    var layer = e.target;
    var mostNorth = layer.getBounds().getNorthWest().lat;
    var mostSouth = layer.getBounds().getSouthWest().lat;
    var center = layer.getBounds().getCenter();
    var heightToDraw = ((mostNorth - mostSouth) / 4) + center.lat;
    var pointToDraw = new L.LatLng(heightToDraw, center.lng);

    var popup = L.popup()
    .setLatLng(pointToDraw)
    .setContent('<div id="map-tip-header">' + layer.feature.properties.name + '</div><div id="map-tip-text">Total projects: '+ layer.feature.properties.projects + '</div><div id="map-tip-link"><a href="?s=&countries='+layer.feature.properties.iso+'">Click to view related projects</a></div>')
    .openOn(map);
}

function resetHighlight(e) {
    geojson.resetStyle(e.target);
}