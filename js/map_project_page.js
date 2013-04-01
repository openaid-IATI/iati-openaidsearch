

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