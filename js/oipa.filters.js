function OipaFilters(){

	this.data = null;
	this.selection = null;
	this.firstLoad = true;
	this.perspective = null;
	this.filter_wrapper_div = ".projects-filter";
	this.active_filters = ["countries", "regions", "sectors", "reporting_organisations", "budgets"];
	this.filters_loaded = 0;
}

OipaFilters.prototype.init = function(){

	// check url parameters -> selection
	this.selection.update_selection_from_url();

	// load options from oipa api
	this.load_filter_options();
};

OipaFilters.prototype.load_filter_options = function(){

	for(var i =0;i < this.active_filters.length;i++){
		url = this.get_url_by_filter(this.active_filters[i]);
		this.get_data(url, 4, this.active_filters[i]);
	}
}

OipaFilters.prototype.get_url_by_filter = function(filter_name){

	if (filter_name == "countries"){
		return search_url + "countries?page_size=999&format=json&fields=code,name&fields[aggregations]=count";
	} else if (filter_name == "regions"){
		return search_url + "regions?page_size=999&format=json&fields=code,name&fields[aggregations]=count";
	} else if (filter_name == "sectors"){
		return search_url + "sectors?page_size=999&format=json&fields=code,name&fields[aggregations]=count";
	} else if (filter_name == "reporting_organisations"){
		// return search_url + "reporting_organisations?page_size=999&format=json&fields=code,name&fields[aggregations]=count";
	}
}

OipaFilters.prototype.save = function(dont_update_selection){
		
	if(!dont_update_selection){
		// update OipaSelection object
		this.update_selection_object();
	}

	// reload maps
	Oipa.refresh_maps();

	// reload lists
	Oipa.refresh_lists();

	// reload visualisations
	Oipa.refresh_visualisations();
};


OipaFilters.prototype.update_selection_object = function(){
	
	// set selection as filter and load results
	this.selection.sectors = this.get_checked_by_filter("sectors");
	this.selection.countries = this.get_checked_by_filter("countries");
	this.selection.budgets = this.get_checked_by_filter("budgets");
	this.selection.regions = this.get_checked_by_filter("regions"); 
	this.selection.indicators = this.get_checked_by_filter("indicators");
	this.selection.cities = this.get_checked_by_filter("cities");
	this.selection.start_planned_years = this.get_checked_by_filter("start_planned_years");
	this.selection.donors = this.get_checked_by_filter("donors");
	
	if (!Oipa.default_organisation_id){
		this.selection.reporting_organisations = this.get_checked_by_filter("reporting_organisations");
	}
	// this.selection_box.fill();
};

OipaFilters.prototype.fill_filter_selection_string = function(){

	var html = "";
	var is_selection = false;
	// region, country, sector, budget, search

	// for each of the above
	function add_to_selection_string(html, arr){
		for (var i = 0;i < arr.length;i++){
			
			if (html.length > 0){ // add comma
				html += ", " + arr[i].name;
			} else {
				html = arr[i].name;
			}
		}
		return html;
	}

	html = add_to_selection_string(html, this.selection.regions);
	html = add_to_selection_string(html, this.selection.countries);
	html = add_to_selection_string(html, this.selection.sectors);
	html = add_to_selection_string(html, this.selection.budgets);

	if (this.selection.query){
		html += "'" + this.selection.query + "'";
	}

	if (html.length > 0){
		html = "Filters selected: " + html;
	} else {
		html = "No filter selected";
	}

	$(".filters-selected-text").html(html);
}

OipaFilters.prototype.get_selection_object = function(){
	// set selection as filter and load results
	var current_selection = new OipaSelection();

	current_selection.sectors = this.get_checked_by_filter("sectors");
	current_selection.countries = this.get_checked_by_filter("countries");
	current_selection.budgets = this.get_checked_by_filter("budgets");
	current_selection.regions = this.get_checked_by_filter("regions");
	current_selection.indicators = this.get_checked_by_filter("indicators");
	current_selection.cities = this.get_checked_by_filter("cities");
	current_selection.start_planned_years = this.get_checked_by_filter("start_planned_years");
	current_selection.donors = this.get_checked_by_filter("donors");
	
	if (!Oipa.default_organisation_id){
		current_selection.reporting_organisations = this.get_checked_by_filter("reporting_organisations");
	}
	return current_selection;
};

OipaFilters.prototype.get_checked_by_filter = function(filtername){

	var arr = [];
	jQuery('select[data-filter-name="'+filtername+'"] option:selected').each(function(index, value){
		arr.push({"id":value.value, "name":value.text});
	});
	return arr;
};

OipaFilters.prototype.get_url = function(selection, parameters_set){
	// override
};

OipaFilters.prototype.get_data = function(url, columns, key){
	// filters
	var filters = this;
	
	jQuery.ajax({
		type: 'GET',
		url: url,
		contentType: "application/json",
		dataType: 'json',
		success: function(data){
			filters.create_filter_attributes(data, columns, key);
			filters.filters_loaded++;

			if(filters.loaded == filters.active_filters.count){
				filters.on_filters_loaded();
			}

		}
	});
};

OipaFilters.prototype.on_filters_loaded = function(){
	this.load_paginate_listeners();
	this.initialize_filters();
}

OipaFilters.prototype.get_budget_filter_options = function(){

	var budgetfilters = [];
	budgetfilters.push(["0-20000", { "name": "0 - 20,000" }]);
	budgetfilters.push(["20000-100000", { "name": "20,000 - 100,000" }]);
	budgetfilters.push(["100000-1000000", { "name": "100,000 - 1,000,000" }]);
	budgetfilters.push(["1000000-5000000", { "name": "1,000,000 - 5,000,000" }]);
	budgetfilters.push(["5000000", { "name": "> 5,000,000" }]);

	return budgetfilters;
}

OipaFilters.prototype.initialize_filters = function(){

	jQuery('#map-filter-overlay input:checked').prop('checked', false);
	if (typeof this.selection.sectors !== "undefined") { this.init_filters_loop(this.selection.sectors) };
	if (typeof this.selection.countries !== "undefined") { this.init_filters_loop(this.selection.countries) };
	if (typeof this.selection.budgets !== "undefined") { this.init_filters_loop(this.selection.budgets) };
	if (typeof this.selection.regions !== "undefined") { this.init_filters_loop(this.selection.regions) };
	if (typeof this.selection.indicators !== "undefined") { this.init_filters_loop(this.selection.indicators) };
	if (typeof this.selection.cities !== "undefined") { this.init_filters_loop(this.selection.cities) };
	if (typeof this.selection.reporting_organisations !== "undefined") { this.init_filters_loop(this.selection.reporting_organisations) };

	//this.selection_box.fill();
};

OipaFilters.prototype.init_filters_loop = function(arr){
	for(var i = 0; i < arr.length;i++){
		jQuery(':checkbox[value=' + arr[i].id + ']').prop('checked', true);
	}
};

OipaFilters.prototype.create_filter_attributes = function(objects, columns, attribute_type){

	var html = '';
	var per_col = 10;
	var sortable = [];

	for (var i = 0;i < objects.results.length;i++){
		if(objects.results[i].aggregations.count > 0){
			sortable.push(
				[objects.results[i].code, {
					"name": objects.results[i].name, 
					"count": objects.results[i].aggregations.count
				}]
			);
		}
	}

	sortable.sort(function(a, b){
		var nameA=a[1].name.toString(), nameB=b[1].name.toString();
		if (nameA < nameB) { //sort string ascending
			return -1; 
		}
		if (nameA > nameB) {
			return 1;
		}
		return 0; //default return value (no sorting)
	});

	var page_counter = 1;
	html += '<div class="row filter-page filter-page-1">';

	for (var i = 0;i < sortable.length;i++){

		if (i%per_col == 0){
			if (columns == 2){
				html += '<div class="col-md-6 col-sm-6 col-xs-12">';
			} else {
				html += '<div class="col-md-3 col-sm-3 col-xs-6">';
			}
		}

		var sortablename = sortable[i][1].name;
		if (columns == 4 && sortablename.length > 32){
			sortablename = sortablename.substr(0,28) + "...";
		} else if (columns == 3 && sortablename.length > 40){
			sortablename = sortablename.substr(0,36) + "...";
		}

		html += '<div class="checkbox">';
		html += '<label><input type="checkbox" value="'+ sortable[i][0] +'" id="'+sortable[i][1].name.toString().replace(/ /g,'').replace(',', '').replace('&', '').replace('%', 'perc')+'" name="'+sortable[i][1].name+'" />'+sortablename+' ('+sortable[i][1].count+')</label></div>';

		if (i%per_col == (per_col - 1)){
			html += '</div>';
		}
		if ((i + 1) > ((page_counter * (per_col * columns))) - 1) { 
	
			html += '</div>';
			page_counter = page_counter + 1;
			html += '<div class="row filter-page filter-page-' + page_counter + '">';
		}
	}

	/// if paginated, close the pagination.
	if (page_counter > 1){
		html += '</div>';
	}

	html += '<input class="filter-total-pages" type="hidden" value="'+page_counter+'">';
	jQuery("#"+attribute_type+"-filters").html(html);
};


OipaFilters.prototype.paginate = function(cur_page, total_pages){

	// range of num links to show
	var range = 2;
	var paging_block = "";

	if (cur_page == 1){ paging_block += '<a href="#" class="pagination-btn-previous btn-prev"></a>'; } 
	else { paging_block += '<a href="#" class="pagination-btn-previous btn-prev">&lt; previous</a>'; }
	paging_block += "<ul>";

	if (cur_page > (1 + range)){ paging_block += "<li><a href='#'>1</a></li>"; }
	if (cur_page > (2 + range)){ paging_block += "<li>...</li>"; }

	// loop to show links to range of pages around current page
	for (var x = (cur_page - range); x < ((cur_page + range) + 1); x++) { 
	   // if it's a valid page number...
	   if ((x > 0) && (x <= total_pages)) {
		  if (x == cur_page) { paging_block += "<li class='active'><a>"+x+"</a></li>"; } 
		  else { paging_block += "<li><a href='#'>"+x+"</a></li>"; } // end else
	   } // end if 
	} // end for

	if(cur_page < (total_pages - (1 + range))){ paging_block += "<li>...</li>"; }
	if(cur_page < (total_pages - range)){ paging_block += "<li><a href='#' class='page'><span>"+total_pages+"</span></a></li>"; }	   
	paging_block += "</ul>";

	// if not on last page, show forward and last page links		
	if (cur_page != total_pages) { paging_block += '<a href="#" class="pagination-btn-next btn-next">next &gt;</a>'; } 
	else { paging_block += '<a href="#" class="pagination-btn-next btn-next"></a>'; } // end if
	/****** end build pagination links ******/
	
	$("#map-filter-pagination").html(paging_block);
};

OipaFilters.prototype.load_paginate_listeners = function(){

	var that = this;

	$('.filter-button').click(function(e){

		var filter_wrapper_name = jQuery(this).data("type") + "-filters";
		var filter_wrapper = jQuery("#" + filter_wrapper_name);

		$('.filter-button.filter-selected').removeClass("filter-selected");
		$('#map-filter-errorbox').text("");
		$('.filter-page').hide();
		$('.filter-page-1').show();

		var total_pages = filter_wrapper.find(".filter-total-pages").val();
		if (total_pages > 1){
		  that.paginate(total_pages, 1);
		} else{
		  $('#map-filter-pagination').html('');
		}

		if($('#map-filter-overlay').is(":hidden")){
			$(this).addClass("filter-selected");

			if($('#map-hide-show-button').hasClass("map-hide")){ show_map(); }
			$('#map-filter-overlay').show("blind", { direction: "vertical" }, 600);
			
			hide_all_filters();
			filter_wrapper.show();

		} else if(filter_wrapper.is(":visible")){
		    // that.save();
		  } else {
		    $(this).addClass("filter-selected");
		    hide_all_filters();
		    filter_wrapper.show();
		  }
		}
	});


	// // load pagination filters
	// jQuery("#"+attribute_type+"-pagination ul a").click(function(e){
	// 	e.preventDefault();
	// 	var page_number = jQuery(this).text();
	// 	jQuery("#"+attribute_type+"-pagination").html(Oipa.mainFilter.paginate(page_number, total_pages));
	// 	Oipa.mainFilter.load_paginate_page(attribute_type, page_number);
	// 	Oipa.mainFilter.load_paginate_listeners(attribute_type, total_pages);
	// });

	// jQuery("#"+attribute_type+"-pagination .pagination-btn-next").click(function(e){
	// 	e.preventDefault();
	// 	var page_number = jQuery("#"+attribute_type+"-pagination .active a").text();
	// 	page_number = parseInt(page_number) + 1;
	// 	jQuery("#"+attribute_type+"-pagination").html(Oipa.mainFilter.paginate(page_number, total_pages));
	// 	Oipa.mainFilter.load_paginate_page(attribute_type, page_number);
	// 	Oipa.mainFilter.load_paginate_listeners(attribute_type, total_pages);
	// });

	// jQuery("#"+attribute_type+"-pagination .pagination-btn-previous").click(function(e){
	// 	e.preventDefault();
	// 	var page_number = jQuery("#"+attribute_type+"-pagination .active a").text();
	// 	page_number = parseInt(page_number) - 1;
	// 	jQuery("#"+attribute_type+"-pagination").html(Oipa.mainFilter.paginate(page_number, total_pages));
	// 	Oipa.mainFilter.load_paginate_page(attribute_type, page_number);
	// 	Oipa.mainFilter.load_paginate_listeners(attribute_type, total_pages);
	// });
	
};

OipaFilters.prototype.load_paginate_page = function(attribute_type, page_number){
	// hide all pages
	jQuery("#"+attribute_type+"-filters .filter-page").hide();
	jQuery("#"+attribute_type+"-filters .filter-page-"+page_number).show();
};

OipaFilters.prototype.reset_filters = function(){
	// $('.selectpicker').selectpicker('deselectAll');
	jQuery('.selectpickr option:selected').prop('selected', false);
	jQuery('.selectpickr').selectpicker('refresh');
	jQuery('.selectpicker').removeClass('select-orange');
	Oipa.mainFilter.selection.search = "";
	Oipa.mainFilter.selection.query = "";
	Oipa.mainFilter.selection.country = "";
	Oipa.mainFilter.selection.region = "";
	Oipa.mainFilter.save();
}

// OipaFilters.prototype.reload_specific_filter = function(filter_name, data){

// 	if (!data){
// 		filters = this;

// 		// get selection
// 		selection = this.get_selection_object();

// 		// get data
// 		if (filter_name === "left-cities") { var url = this.get_url(null, "&indicators__in=" + get_parameters_from_selection(selection.indicators) + "&countries__in=" + get_parameters_from_selection(selection.left.countries) ); }
// 		if (filter_name === "right-cities") { var url = this.get_url(null, "&indicators__in=" + get_parameters_from_selection(selection.indicators) + "&countries__in=" + get_parameters_from_selection(selection.right.countries) ); }
// 		if (filter_name === "indicators") { var url = this.get_url(null, "&regions__in=" + get_parameters_from_selection(selection.regions) + "&countries__in=" + get_parameters_from_selection(selection.countries) + "&cities__in=" + get_parameters_from_selection(selection.cities) ); }
// 		if (filter_name === "regions") { var url = this.get_url(null, "&indicators__in=" + get_parameters_from_selection(selection.indicators) ); }
// 		if (filter_name === "countries") { var url = this.get_url(null, "&indicators__in=" + get_parameters_from_selection(selection.indicators) + "&regions__in=" + get_parameters_from_selection(selection.regions) ); }
// 		if (filter_name === "cities") { var url = this.get_url(null, "&indicators__in=" + get_parameters_from_selection(selection.indicators) + "&regions__in=" + get_parameters_from_selection(selection.regions) + "&countries__in=" + get_parameters_from_selection(selection.countries) ); }


// 		jQuery.ajax({
// 			type: 'GET',
// 			url: url,
// 			contentType: "application/json",
// 			dataType: 'json',
// 			success: function(data){
// 				filters.reload_specific_filter(filter_name, data);
// 			}
// 		});
		

// 	} else {
// 		// reload filters
// 		columns = 4;
// 		if (filter_name === "left-cities") { this.create_filter_attributes(data.cities, columns, 'left-cities'); }
// 		if (filter_name === "right-cities") { this.create_filter_attributes(data.cities, columns, 'right-cities'); }
// 		if (filter_name === "indicators" && Oipa.pageType == "compare") { 
// 			this.create_filter_attributes(data.countries, columns, 'left-countries');
// 			this.create_filter_attributes(data.countries, columns, 'right-countries');
// 			this.create_filter_attributes(data.cities, columns, 'left-cities');
// 			this.create_filter_attributes(data.cities, columns, 'left-cities');
// 		}
// 		if (filter_name === "regions") { this.create_filter_attributes(data.regions, 2, 'regions'); }
// 		if (filter_name === "countries") { this.create_filter_attributes(data.countries, columns, 'countries'); }
// 		if (filter_name === "cities") { this.create_filter_attributes(data.cities, columns, 'cities'); }
// 		if (filter_name === "indicators" && Oipa.pageType == "indicators") { this.create_filter_attributes(data.indicators, 2, 'indicators'); }

// 		this.initialize_filters(selection);
// 	}
// };
