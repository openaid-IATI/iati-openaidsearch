
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

});


for(var i = 0; i < countryData.features.length; i++)
{	
	//var geocountries = new Array();


	var country_draw_geo = [];
	if(jQuery.inArray(countryData.features[i].properties.name, project_countries) != -1){
		//alert(countryData.features[i].properties.name);

		var geocountry = {
		    "type": "Feature",
		    "properties": {"party": "Republican"},
		    "geometry": {
		        "type": "Polygon",
		        "coordinates": countryData.features[i].geometry.coordinates
		    }
		}

		var geocountries = [geocountry];
	}

} 



L.geoJson(geocountries, {
    style: function(feature) {
        	return {color: "orange"};
        	return {fillColor: "orange"};
    }
		

	}).addTo(map);
//map.setView([10.505, 25.09], 4);


//for (var key in countryloc){
//	var circle = L.circle(new L.LatLng(countryloc[key].longitude, countryloc[key].latitude), 100000, {
//		color: 'red',
//		fillColor: '#f03',
//		fillOpacity: 0.5
//	}).addTo(map);
//}