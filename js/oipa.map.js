function OipaMap(){
	this.map = null;
	this.selection = null;
	this.slider = null;
	this.basemap = "zimmerman2014.hmpkg505";
	this.tl = null;
	this.compare_left_right = null;
	this.circles = {};
	this.markers = [];
	this.vistype = "geojson";
	this.selected_year = null;

	if (typeof standard_basemap !== 'undefined') {
		this.basemap = standard_basemap;
	}

	this.set_map = function(div_id, zoomposition, no_dragging){

		var mapoptions = {
			attributionControl: false,
			scrollWheelZoom: false,
			zoom: 3,
			minZoom: 2,
			maxZoom:12,
			continuousWorld: 'false'
		}

		if(no_dragging){
			mapoptions.dragging = false;
		}

		if(zoomposition || zoomposition == null){
			mapoptions.zoomControl = false;
		}
		

		jQuery("#"+div_id).css("min-height", "200px");
		this.map = L.map(div_id, mapoptions).setView([3.505, 18.00], 2);

		if (zoomposition){
			new L.Control.Zoom({ position: zoomposition }).addTo(this.map);
		}

		this.tl = L.tileLayer('https://{s}.tiles.mapbox.com/v3/'+this.basemap+'/{z}/{x}/{y}.png', {
			maxZoom: 12
		}).addTo(this.map);

	};

	this.get_markers_bounds = function(){

		var minlat = 0;
		var maxlat = 0;
		var minlng = 0;
		var maxlng = 0;
		var first = true;

		jQuery.each(this.markers, function( index, value ) {

			curlat = value._latlng.lat;
			curlng = value._latlng.lng;

			if (first){
				minlat = curlat;
				maxlat = curlat;
				minlng = curlng;
				maxlng = curlng;
			}

			if (curlat < minlat){
				minlat = curlat;
			}
			if (curlat > maxlat){
				maxlat = curlat;
			}
			if (curlng < minlng){
				minlng = curlng;
			}
			if (curlng > maxlng){
				maxlng = curlng;
			}

			first = false;
		});

		return [[minlat, minlng],[maxlat, maxlng]];
	}

	this.refresh = function(data){

		if (!data){
	
			// get url
			var url = this.get_url();

			// get data
			this.get_data(url);
			
		} else {
			// show data
			this.show_data_on_map(data);
		}
	};

	this.get_url = function(){

		var parameters = this.selection.get_parameters();
		var api_call = "activities";

		if (this.selection.group_by == "activity"){
			api_call = "activities";
		} else if(this.selection.group_by == "country"){
			api_call = "country-activities";
		} else if(this.selection.group_by == "region"){
			api_call = "region-activities";
		} else if(this.selection.group_by == "global"){
			api_call = "global-activities";
		}

		if (this.vistype == "geojson"){
			api_call = "country-geojson";
		}

		return search_url + 'countries?format=json&page_size=999&fields=code,name&fields[aggregations]=count' + parameters;
	};

	this.get_data = function(url){

		if (url === null){
			this.refresh(1);
		}

		var thismap = this;

		jQuery.ajax({
			type: 'GET',
			url: url,
			contentType: "application/json",
			dataType: 'json',
			success: function(data){
				thismap.refresh(data);
			}
		});
	};
	
	
	this.delete_markers = function(){
		for (var i = 0; i < this.markers.length; i++) {
			this.map.removeLayer(this.markers[i]);
		}
	};

	this.show_data_as_markers = function(data){
		// For 0 -> 9, create markers in a circle
		for (var i = 0; i < data.objects.length; i++) {
			if (data.objects[i].id === null){ continue; }
			// Use a little math to position markers.
			// Replace this with your own code.
			if (data.objects[i].latitude !== null || data.objects[i].longitude !== null){
				curmarker = L.marker([
					data.objects[i].latitude,
					data.objects[i].longitude
				], {
					icon: L.divIcon({
						// Specify a class name we can refer to in CSS.
						className: 'country-marker-icon',
						// Define what HTML goes in each marker.
						html: data.objects[i].total_projects,
						// Set a markers width and height.
						iconSize: [36, 44],
						iconAnchor: [18, 34],
					})
				}).bindPopup('<div class="country-marker-popup-header"><a href="'+site_url+'/country/'+data.objects[i].id+'/">'+data.objects[i].name+'</a></div><table><tr><td>START DATE:</td><td>ALL</td></tr><tr><td>PROJECTS:</td><td><a href="'+site_url+'/country/'+data.objects[i].id+'/">' + data.objects[i].total_projects + '</a></td></tr><tr><td>BUDGET:</td><td>US$' + comma_formatted(data.objects[i].total_budget) + '</td></tr></table>', { minWidth: 300, maxWidth: 300, offset: L.point(173, 69), closeButton: false, className: "country-popup"})
				.addTo(this.map);

				this.markers.push(curmarker);
			}
		}
	}

	this.show_data_as_geojson = function(project_geojson){

		var thismap = this;

		// Map polygon styling
		function getColor(d) {
		    return d > 10 ? '#0f567c' :
		           d > 8  ? '#045A8D' :
		           d > 6  ? '#176792' :
		           d > 4   ? '#2476A2' :
		           d > 2   ? '#2B8CBE' :
		           d > 0    ? '#65a8cf' :
		                      'transparent';
		}

		function getWeight(d) {
		    return d > 0  ? 1 :
		                      0;
		}

		function style(feature) {
		    return {
		        fillColor: getColor(feature.properties.count),
		        weight: getWeight(feature.properties.count),
		        opacity: 1,
		        color: '#FFF',
		        dashArray: '',
		        fillOpacity: 0.7
		    };
		}

		function highlightFeature(e) {
		    var layer = e.target;
		    
		    if(typeof layer.feature.properties.count != "undefined"){

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
		    var url_parameters = "todo";
		    url_parameters = url_parameters.replace("?", "&");

		    var popup_html = "<a href='"+home_url+"/country/"+layer.feature.id+"/'><div class='leaflet-popup-wrapper'>"
    		popup_html += "<div class='leaflet-popup-title'>" + layer.feature.properties.name + "</div>"
    		popup_html += "<div class='leaflet-popup-budget-wrapper'>Total projects: <span>" + layer.feature.properties.count + "</span></div>"
    		popup_html += "</div></div></a>"

    		// .setContent('<div id="map-tip-header">' + layer.feature.properties.name + '</div><div id="map-tip-text">Total projects: '+ layer.feature.properties.count + '</div><div id="map-tip-link"><a href="'+home_url+'/country/'+layer.feature.id+'/">View country</a></div>')

		    var popup = L.popup({'closeButton': false, 'classname': ''})
		    .setLatLng(pointToDraw)
		    .setContent(popup_html)
		    .openOn(thismap.map);
		}

		function resetHighlight(e) {
		    thismap.geojson.resetStyle(e.target);
		}

		if (this.geojson != null){
	      this.geojson.clearLayers();
	    }

	    this.geojson = L.geoJson(project_geojson, {style: style,onEachFeature: function(feature,layer) {

	      layer.on({
	          mouseover: highlightFeature,
	          mouseout: resetHighlight,
	          click: showPopup
	      });
	    }});

	    this.geojson.addTo(this.map); 

	}


	this.prepare_data = function(data){

		if (this.vistype == "geojson"){

			// create geojson 

			var geodata = {
			  "type": "FeatureCollection",
			  "features": []
			};

			for (var i = 0; i < data.results.length;i++){
				if(data.results[i].aggregations.count > 0){
					var feature = {
				      "type": "Feature",
				      "id": data.results[i].code,
				      "properties": {
				        "name": data.results[i].name,
				        "iso2": data.results[i].code,
				        "count": data.results[i].aggregations.count
				      },
				      "geometry": country_polygons[data.results[i].code]
				    };

					geodata.features.push(feature);
				}
			}
			return geodata;
		} 

		return data;
	}

	this.show_data_on_map = function(data){

		data = this.prepare_data(data);

		this.delete_markers();

		if(this.vistype == "markers"){
			this.show_data_as_markers(data);
		} else if (this.vistype == "geojson"){
			this.show_data_as_geojson(data);
		}
		
	};

	this.show_project_detail_locations = function(administrative_level, terms){

		if(administrative_level == "exact_location"){


			json_terms = JSON.parse(terms);

			for (var i = 0; i < json_terms.length;i++){
				var marker = L.marker([json_terms[i].latitude, json_terms[i].longitude]).addTo(this.map);
				
			}
			this.map.setView([json_terms[0].latitude,json_terms[0].longitude], 1);

			// show exact location markers if exact location
			// show polygon (if available) if its an adm1 region

		} else if (administrative_level == "countries"){
			// show country polygons
			var self = this;

			terms = terms.split(",");
			var response_count = 0;
			for (var i = 0;i < terms.length; i++){

				var url = site_url + ajax_path + '&format=json&call=country&country=' + terms[i];
				
				jQuery.ajax({
					type: 'GET',
					url: url,
					contentType: "application/json",
					dataType: 'json',
					success: function(data){

						// show polygon on map
						if(data.polygon){
							var pol = JSON.parse(data.polygon);
							var polygon = L.geoJson(pol, {
								style: {
									"color": "#2581D4",
									// "fillColor": "#ff7800",
								    "weight": 1,
								    "opacity": 0.85
								} 
							}).addTo(self.map);
							

							if ((response_count + 1) == terms.length){
								var center = polygon.getBounds().getCenter();
								self.map.setView(center, 1);
								var bounds = polygon.getBounds();
								if (terms.length == 1){
									setTimeout(
									  function(){
									    self.map.fitBounds(bounds);
									  }, 800);
								}
							}
							response_count++;
						}
					}
				});
			}

			
		} else if (administrative_level == "regions"){
			var self = this;

			terms = terms.split(",");
			var response_count = 0;
			for (var i = 0;i < terms.length; i++){

				var url = site_url + ajax_path + '&format=json&call=region&region=' + terms[i];
			
				jQuery.ajax({
					type: 'GET',
					url: url,
					contentType: "application/json",
					dataType: 'json',
					success: function(data){
						if(data.center_longlat){
							var center_longlat = geo_point_to_latlng(data.center_longlat);
							var marker = L.marker(center_longlat).addTo(self.map);
							self.map.setView(center_longlat, 1);
						}
					}
				});
			}
		}
	}

	this.set_bounds_and_center = function(){
		var bounds = this.get_markers_bounds();
		
		if(bounds){
			this.map.fitBounds(bounds);

			var center_lat = (bounds[0][0] + bounds[1][0]) / 2;
			var center_lng = (bounds[0][1] + bounds[1][1]) / 2;
			this.map.setView([center_lat, center_lng], 2);
		}
	}

	this.load_map_listeners = function(){
		// no default listeners, this function should be overriden.
	};

	this.change_basemap = function(basemap_id){
		this.tl._url = "https://{s}.tiles.mapbox.com/v3/"+basemap_id+"/{z}/{x}/{y}.png";
		this.tl.redraw();
	};


	this.zoom_on_dom = function(curelem){
		var latitude = curelem.getAttribute("latitude");
		var longitude = curelem.getAttribute("longitude");
		var country_id = curelem.getAttribute("name");
		var country_name = curelem.getAttribute("country_name");

		this.map.setView([latitude, longitude], 6);
		Oipa.mainSelection.countries.push({"id": country_id, "name": country_name});
		Oipa.refresh_maps();
		Oipa.refresh_lists();
	};

	this.popup_click_project_amount = function(curelem){

		if (this.selection.group_by == "country"){

			var country_id = curelem.getAttribute("data-country-id");
			var country_name = curelem.getAttribute("data-country-name");
			Oipa.mainSelection.countries = [];
			Oipa.mainSelection.countries.push({"id": country_id, "name": country_name});
		}

		if (this.selection.group_by == "region"){

			var region_id = curelem.getAttribute("data-region-id");
			var region_name = curelem.getAttribute("data-region-name");
			Oipa.mainSelection.regions = [];
			Oipa.mainSelection.regions.push({"id": region_id, "name": region_name});
		}

		if (projectlist == null){
			// We are on the homepage, redirect to project page with parameters
			var url = new OipaUrl();
			var urlpars = url.build_parameters();

			var fullurl = site_url + "/projects/" + urlpars + "&scrollto=projectlist";
			
			window.location.href = fullurl;
		}

		Oipa.refresh_maps();
		Oipa.refresh_lists();
		jQuery("#show-all-projects-button").click();

		jQuery('html,body').animate({
	        scrollTop: jQuery("#show-all-projects-button").offset().top - 50
	    }, 500);
	};
}