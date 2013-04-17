// Global variables
var geojson;

// Project filter options
function initialize_project_filter_options(){
  //set loading gif in case call takes long
  var url = make_project_filter_options_url();
  var options = get_project_filter_options(url);
  draw_project_options(options);
}

function make_project_filter_options_url(){
  var dlmtr = ",";
  var str_country = reload_map_prepare_parameter_string("country", dlmtr);
  var str_region = reload_map_prepare_parameter_string("region", dlmtr);
  var str_sector = reload_map_prepare_parameter_string("sector", dlmtr);
  var str_budget = reload_map_prepare_parameter_string("budget", dlmtr);
  var url = 'http://dev.oipa.openaidsearch.org/json-project-filters?sectors=' + str_sector + '&budgets=' + str_budget + '&countries=' + str_country + '&regions=' + str_region;
  return url;
}

function get_project_filter_options(url){

  var project_options;
  $.ajax({
        type: 'GET',
         url: url,
         async: false,
         contentType: "application/json",
         dataType: 'json',
         success: function(data){
          project_options = data;
         }
  });
  return project_options;
}

function draw_project_options(options){

  var budget_keys = {};
  budget_keys['all'] = 'All';
  budget_keys[''] = '> US$ 0';
  budget_keys['10000'] = '> US$ 10.000';
  budget_keys['50000'] = '> US$ 50.000';
  budget_keys['100000'] = '> US$ 100.000';
  budget_keys['500000'] = '> US$ 500.000';
  budget_keys['1000000'] = '> US$ 1.000.000';

  var country_html = create_filter_attributes(options.countries, 4);
  $('#country-filters').html(country_html);
  var region_html = create_filter_attributes(options.regions, 4);
  $('#region-filters').html(region_html);
  var sector_html = create_filter_attributes(options.sectors, 3);
  $('#sector-filters').html(sector_html);
  var budget_html = create_filter_attributes(budget_keys, 4);
  $('#budget-filters').html(budget_html);
}





// init/reload the map
function initialize_projects_map(url, type){
    selected_type = type;
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
          project_geojson = data;
         }
    });

    return project_geojson;
}

function unload_project_map(){
    // remove geojson layer from map
    if (geojson != null){
      geojson.clearLayers();
    }
}



// Map polygon styling


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
        fillColor: getColor(feature.properties.project_amount),
        weight: getWeight(feature.properties.project_amount),
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
    .setContent('<div id="map-tip-header">' + layer.feature.properties.name + '</div><div id="map-tip-text">Total projects: '+ layer.feature.properties.project_amount + '</div><div id="map-tip-link"><a href="?s=&countries='+layer.feature.id+'">Click to view related projects</a></div>')
    .openOn(map);
}

function resetHighlight(e) {
    geojson.resetStyle(e.target);
}

function load_project_map(project_geojson){
    geojson = L.geoJson(project_geojson, {style: style,onEachFeature: function(feature,layer) {

      layer.on({
          mouseover: highlightFeature,
          mouseout: resetHighlight,
          click: showPopup
      });
    }});

    geojson.addTo(map); 
}

