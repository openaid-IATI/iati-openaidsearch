
// XXXXXXX PROJECT DETAIL PAGE XXXXXXXXXXX

function sanitize_project_url(){
  var parameters = new Object();
  var query = window.location.search.substring(1);
  //console.log(window.location.search.substring(1));
  if(query != ''){
    var vars = query.split("&");
    for (var i=0;i<vars.length;i++) {
      var pair = vars[i].split("=");
      var vals = pair[1].split(",");

      parameters[pair[0]] = [];
      for(var y=0;y<vals.length;y++){
        parameters[pair[0]] = vals[y];
      }
      
    } 
  }
  var link = home_url + "/project/?id=" + parameters["id"];
  if (!(parameters["backlink"] === undefined)){
  	$("#project-back-button").attr("href", decodeURIComponent(parameters["backlink"]));
  }
  history.pushState(null, null, link);
}


jQuery(function($) {


  function change_tab(curlink, newtab){

  	// hide all tab content
  	$(".project-tabs-wrapper > div").hide();
  	// show the project detail block (hidden on rsr info)
  	$(".project-detail-block").show();
  	$("#project-rsr").hide();

  	// remove active tab
  	$(".nav-pills > li").removeClass("active");
  	// make link of current tab active
	$(curlink).closest("li").addClass("active");
	// show current tab content
	$("#project-" + newtab).show();

  }

  $("#project-back-button").click(function(){
  	  window.history.back();
  });

  $("#project-description-link").click(function(){
	  change_tab(this, "description");
	  return false;
  });

  $("#project-financials-link").click(function(){
	  change_tab(this, "financials");
	  return false;
  });

  $("#project-documents-link").click(function(){
	  change_tab(this, "documents");
	  return false;
  });

  $("#project-related-indicators-link").click(function(){
	  change_tab(this, "related-indicators");
	  return false;
  });

  $("#project-related-projects-link").click(function(){
	  change_tab(this, "related-projects");
	  return false;
  });

  $("#project-located-in-link").click(function(){
	  change_tab(this, "located-in");
	  return false;
  });

  $("#project-rsr-link").click(function(){
	  change_tab(this, "rsr");
	  $(".project-detail-block").hide();
	  return false;
  });


	var geocountries = {"type":"FeatureCollection","features":[]};

	for(var i = 0; i < countryData.features.length; i++)
	{	
		if(jQuery.inArray(countryData.features[i].properties.iso2, project_countries) != -1){
			
			var geocountry = {
			    "type": "Feature",
			    "geometry": {
			        "type": "Polygon",
			        "coordinates": countryData.features[i].geometry.coordinates
			    }
			}

			geocountries.features.push(geocountry);
		}

	}



	L.geoJson(geocountries, {
	    style: function(feature) {
	        	return {color: "orange"};
	        	return {fillColor: "orange"};
	    }
			

	}).addTo(map);

	//map.setView([10.505, 25.09], 4);

});

/*
var locarray = Array();
var sqltext = '';
var counter = 0;
for (var i = 0;i < city_database.length;i++){

	for(var y = 0;y < city_locations.features.length; y++){
		
		if(city_database[i].Name == city_locations.features[y].properties.nameascii || city_database[i].Name == city_locations.features[y].properties.namealt || city_database[i].Name == city_locations.features[y].properties.name){
		
			if(city_database[i].iso2 == city_locations.features[y].properties.iso_a2){
				counter++;
				
				sqltext += "update data_city set latitude='"+ city_locations.features[y].properties.longitude +"', longitude='" +city_locations.features[y].properties.latitude+"' where id = "+city_database[i].id+";";
				
			}
		}		
	}
}
console.log(sqltext);

double or 2 cities in same country with same name:
Fuyang
Teresina
Natal
Jining
Liantungang
Pingxiang
Suzhou
Yichun
Yulin
Aurangabad
Bandar Lampung 
Padang
Windhoek
Denpasar
Columbus
Aurora
Jacksonville
Kansas City
Las vegas
Portland
Richmond
Rochester


*/


