// Global variables
var geojson;

// init/reload the map
function initialize_projects_map(){
    get_project_data(create_api_url("mapdata"));
}

function get_project_data(url){
  $.support.cors = true;
  var project_geojson = [];

  if(window.XDomainRequest){
    var xdr = new XDomainRequest();
    xdr.open("get", url);
    xdr.onprogress = function () { };
    xdr.ontimeout = function () { };
    xdr.onerror = function () { };
    xdr.onload = function() {
       var jsondata = $.parseJSON(xdr.responseText);
       if (jsondata == null || typeof (jsondata) == 'undefined')
       {
          jsondata = $.parseJSON(data.firstChild.textContent);
       }
       unload_project_map();
       load_project_map(jsondata);  
    }
    setTimeout(function () {xdr.send();}, 0);
  } else {
    $.ajax({
        type: 'GET',
         url: url,
         contentType: "application/json",
         dataType: 'json',
         success: function(data){
          unload_project_map();
          load_project_map(data);
         }
    });
  }

}

function unload_project_map(){
    // remove geojson layer from map
    if (geojson != null){
      geojson.clearLayers();
    }
}



// Map polygon styling
function getColor(d) {
    return d > 40  ? '#E27724' :
           d > 10   ? '#F27730' :
           d > 0   ? '#FF872B' :
    // return d > 8  ? '#FE6305' :
    //        d > 4   ? '#FE7421' :
    //        d > 0   ? '#FE8236' :
           //d > 220   ? '#2B8CBE' :
                      'transparent';
}

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
    
    if(typeof layer.feature.properties.project_amount != "undefined"){
        
        if (currently_selected_country != layer.feature.properties.name){
            set_currently_selected_country(layer.feature.properties.name);
            showPopup(e);
        }

        layer.setStyle({
            weight: 2,
            fillOpacity: 0.78
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
    .setContent('<div id="map-tip-header">' + layer.feature.properties.name + '</div><div id="map-tip-text">Total projects: '+ layer.feature.properties.project_amount + '</div><div id="map-tip-link"><a href="/projects/?countries='+layer.feature.id+'">View projects</a></div>')
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


  // load listeners at page initialisation:
  load_projects_listeners();

  

  function load_new_page(reloadmap,keepoffset){
   if (reloadmap === undefined) reloadmap = true;
   if (keepoffset === undefined) keepoffset = false;
   if (!keepoffset){
    current_selection.offset = [];
   }

   var link = document.URL.toString().split("?")[0] + build_current_url();
   if (history.pushState){
    history.pushState(null, null, link);
   }
   $('#page-wrapper').fadeOut(100, function(){ //fade out the content area
   $("#paginated-loader").show();
   }).load(link + ' #page-wrapper', function(){ 
    $("#paginated-loader").hide();
    $('#page-wrapper').fadeIn(200, function(){
      load_projects_listeners();
   
   }); });
   // $('html,body').animate({
   //   scrollTop: ($("#map-hide-show").offset().top - 250)},
   //   'slow', function(){
   //      if(reloadmap){
   //        reload_map();
   //      }
   //   });
  }


  function load_projects_listeners(){


    $('.projects-project-title a').click(function(){
      var link = $(this).attr("href");
      var backlink = build_current_url();
      var newlink = link + "&backlink=" + encodeURIComponent(home_url + "/projects/" + backlink);
      console.log(newlink);
      window.location = newlink;
      return false;
    });

    $(".project-share-whistleblower").click(function(){
      var id = $(this).attr("name");
      var url = "/whistleblower/?referrer=" + encodeURIComponent(home_url + "/project/?id=" + id);
      window.location = url;
      return false;
    });
    
    // Reload projects on pagination container click
    $('#pagination a').click(function(){ 
     var href = $(this).attr('href');
     var splitted = href.split("=");
     var parkey = splitted[0].substr(1);
     var parval = splitted[1];
     current_selection[parkey] = [{"id":parval, "name":"unnamed"}];
     load_new_page(false, true);
     return false;
    });

    // Reload projects on sort type click
    $('.project-sort-type a').click(function(){ 
     var href = $(this).attr('href');
     var splitted = href.split("=");
     var parkey = splitted[0].substr(1);
     var parval = splitted[1];
     current_selection[parkey] = [{"id":parval, "name":"unnamed"}];
     load_new_page();
     return false;
    });

    // $('.projects-description-link').click(function(){
    //  var href = $(this).attr('href');
    //  var splitted = href.split("=");
    //  var parkey = splitted[0].substr(1);
    //  var parval = splitted[1];
    //  current_selection = new Object();
    //  current_selection[parkey] = [{"id":parval, "name":"unnamed"}];
    //  load_new_page(true, false);
    //  return false;
    // });


    $("#project-share-embed").click(function(){
      if($('#dropdown-embed-url').is(":hidden")){
        embed_url = get_embed_url('projects-map');
        $('#dropdown-embed-url input').val(embed_url);
        $('#dropdown-embed-url').show("blind", { direction: "vertical" }, 200);
      } 

      return false;
    });

    $("#project-share-embed-close").click(function(){
      $('#dropdown-embed-url').hide("blind", { direction: "vertical" }, 200);
      return false;
    });
    

    // XXXXXXX sort buttons project page XXXXX

    $(".project-sort-type").click(function(){

      if($('.dropdown-project', this).is(":hidden")){
          $('.dropdown-project', this).show("blind", { direction: "vertical" }, 200);
      } else {
          $('.dropdown-project', this).hide("blind", { direction: "vertical" }, 200);
      }

      return false;
    });


      // plus - min button in project description 

  $('.project-expand-button').click(function(e){
    // TO DO: show the whole description.

    var currentId = $(this).attr('id');

    if($(this).hasClass("expand-plus")){
      $('.project-project-spec-hidden.'+currentId).show();
      $('.projects-project-description.'+currentId).css("height", "auto");
      $(this).removeClass('expand-plus');
      $(this).addClass('expand-min');
    } else {
      $('.project-project-spec-hidden.'+currentId).hide();
      $('.projects-project-description.'+currentId).css("height", "6em");
      $(this).removeClass('expand-min');
      $(this).addClass('expand-plus');
    }
    
  });

  $('#save-search-results').click(function(){
    generate_download_file();
    return false;
  }); 
  
  $('.projects-share-spec #project-share-export').click(function(){
    var id = $(this).attr('name');
    generate_download_file(id);
    return false;
  }); 


  }

 
  
function generate_download_file(id) {
  var author = encodeURIComponent("OIPA search results");
  var url = template_directory + "/export.php?author=";
  if(id) {
    author = encodeURIComponent("Export IATI via OIPA - project details of " + id);
    url += author;
    url += "&id=" + id;
    window.open(url);
    return false;
  }

  url += author;

  var sectors = '', countries = '', budgets = '', regions = '', offset = '&offset=0', per_page = '&limit=200', query = ''
  
  if (!(typeof current_selection.sectors === "undefined")) sectors = build_current_url_add_par("sectors", current_selection.sectors, "|");
  if (!(typeof current_selection.countries === "undefined")) countries = build_current_url_add_par("countries", current_selection.countries, "|");
  if (!(typeof current_selection.budgets === "undefined")) budgets = build_current_url_add_par("budgets", current_selection.budgets, "|");
  if (!(typeof current_selection.regions === "undefined")) regions = build_current_url_add_par("regions", current_selection.regions, "|");
  //if (!(typeof current_selection.offset === "undefined")) offset = build_current_url_add_par("offset", current_selection.offset);
  //if (!(typeof current_selection.per_page === "undefined")) per_page = build_current_url_add_par("per_page", current_selection.per_page);
  if (!(typeof current_selection.query === "undefined")) query = build_current_url_add_par("query", current_selection.query);

  per_page = per_page.replace("per_page", "limit");
  query = encodeURIComponent(query);
  url = url + offset + per_page + sectors + countries + budgets + regions + query;
  console.log(url);
  window.open(url);
  
}




