
// XXXXXXX PROJECT DETAIL PAGE XXXXXXXXXXX
jQuery(function($) {



  function change_tab(curlink, newtab){

  	// hide all tab content
  	$(".project-tabs-wrapper > div").hide();
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

  $("#project-commitments-link").click(function(){
	  change_tab(this, "commitments");
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


var geocountries = {"type":"FeatureCollection","features":[]};

for(var i = 0; i < countryData.features.length; i++)
{	
	// for(var y = 0; y <country_info.length;y++){
	// 	if(country_info[y].ISO3 == countryData.features[i].id){
	// 		countryData.features[i].properties.iso2 = country_info[y].ISO2;
	// 	}


	// }

	// var geocountries = new Array();
	
	// console.log(project_countries);

	if(jQuery.inArray(countryData.features[i].properties.iso2, project_countries) != -1){
		//alert(countryData.features[i].properties.name);

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


