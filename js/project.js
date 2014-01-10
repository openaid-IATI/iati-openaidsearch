
// XXXXXXX PROJECT DETAIL PAGE XXXXXXXXXXX


jQuery(function($) {


  function change_tab(curlink, newtab){

  	// hide all tab content
  	$(".project-tabs-wrapper > div").hide();
  	$("#project-rsr").hide();
  	$(".main-page-content").show();
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
	  $(".main-page-content").hide();
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




function refresh_rsr_projects(iati_id){

  url = site + "/wp-admin/admin-ajax.php?action=rsr_call&iati_id=" + iati_id;
  $.support.cors = true;

  if(window.XDomainRequest){
    var xdr = new XDomainRequest();
    xdr.open("get", url);
    xdr.onprogress = function () { };
    xdr.ontimeout = function () { };
    xdr.onerror = function () { };
    xdr.onload = function() {
      $("#project-rsr").html(xdr.responseText);
    }
    setTimeout(function () {xdr.send();}, 0);
  } else {
    $.ajax({
        type: 'GET',
         url: url,
         contentType: "html",
         dataType: 'html',
         success: function(data){
          $("#project-rsr").html(data);
         }
    });
  }
}



