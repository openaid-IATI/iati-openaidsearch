function OipaList(){

	this.page = 1;
	this.per_page = 10;
	this.amount = 0;
	this.order_by = null;
	this.order_asc_desc = null;
	this.selection = null;
	this.api_resource = "activity-list";

	this.list_div = "#oipa-list";
	this.pagination_div = "#oipa-list-pagination";
	this.activity_count_div = ".project-list-amount";
}

OipaList.prototype.init = function(){
	var thislist = this;

	// init pagination
	jQuery(this.pagination_div).bootpag({
	   total: 5,
	   page: 1,
	   maxVisible: 6
	}).on('page', function(event, num){
		thislist.go_to_page(num);
	});

	this.update_pagination();
	this.load_listeners();
	this.extra_init();
}

OipaList.prototype.extra_init = function(){
	// override
}

OipaList.prototype.refresh = function(data){
	
	if (!data){
		// get URL
		var url = this.get_url();

		// get data
		this.get_data(url);

	} else {
		// set amount of results
		this.update_list(data);
		this.update_pagination(data);
	}
}

OipaList.prototype.reset_pars = function(){
	this.selection.query = "";
	this.page = 0;
	this.per_page = 10;
	this.amount = 0;
	this.order_by = null;
	this.order_asc_desc = null;
	this.refresh();
}

OipaList.prototype.get_url = function(){
	// overriden in children, unused if called correctly
	return site_url + ajax_path + '&format=json&per_page=10&call=activities';
};

OipaList.prototype.get_data = function(url){

	var curlist = this;
	jQuery.ajax({
		type: 'GET',
		url: url,
		dataType: 'html',
		success: function(data){
			curlist.refresh(data);
		}
	});
};

OipaList.prototype.update_list = function(data){
	// generate list html and add to this.list_div
	jQuery(this.list_div).html(data);
};

OipaList.prototype.load_listeners = function(){
	// override
};

OipaList.prototype.update_pagination = function(data){

	var total = jQuery(this.list_div + " .list-amount-input").val();
	this.amount = total;
	jQuery(this.activity_count_div).text(total);

	var total_pages = Math.ceil(this.amount / this.per_page);
	var current_page = this.page;
	jQuery(this.pagination_div).bootpag({total: total_pages});
};

OipaList.prototype.go_to_page = function(page_id){
	this.page = page_id;
	this.refresh();
};

OipaList.prototype.export = function(format){

	var url = this.get_url();
	url = url.replace("format=json", "format=" + format);
	url_splitted = url.split("?");
	url = search_url + this.api_resource + "/?" + url_splitted[1];

	jQuery("#ExportListHiddenWrapper").remove();

	iframe = document.createElement('a');
    iframe.id = "ExportListHiddenWrapper";
    iframe.style.display = 'none';
    document.body.appendChild(iframe);

    var export_func_url = base_url + "/" + theme_path + "/export.php?path=" + encodeURIComponent(url);

    jQuery("#ExportListHiddenWrapper").attr("href", export_func_url);
    jQuery("#ExportListHiddenWrapper").attr("target", "_blank");
    jQuery("#ExportListHiddenWrapper").bind('click', function() {
		window.location.href = this.href;
		return false;
	});
    jQuery("#ExportListHiddenWrapper").click();
	jQuery("#download-dialog").toggle();
}



function OipaProjectList(){
	this.only_regional = false;
	this.only_country = false;
	this.api_resource = "activities";

	this.get_url = function(){
		var parameters = this.selection.get_parameters();
		
		if(Oipa.pageType == "activities"){
			project_path = "projects";
		} else {
			project_path = "projects-on-detail";
		}

		var extra_par = "";
		var desc = "";
		if (this.only_country == true){ extra_par = "&countries__code__gte=0"; }
		else if (this.only_regional == true){ extra_par = "&regions__code__gte=0"; }
		else if(this.only_global == true){ extra_par = "&activity_scope=1"; }
		else if(this.only_other == true){ extra_par = "&regions=None&countries=None&activity_scope=None"; }

		if(this.order_asc_desc == "desc"){ desc = "-"; }
		if(this.order_by){ extra_par += "&order_by=" + desc + this.order_by; }
		var url = site_url + ajax_path + "&format=json&per_page=" + this.per_page + "&page=" + this.page + parameters + extra_par + "&call=" + project_path;
		url = replaceAll(url, " ", "%20");
		url = replaceAll(url, "start_planned__in", "start_year_planned__in");

		return url;
	};	
}

OipaProjectList.prototype = new OipaList();

function OipaCountryList(){
	this.api_resource = "country-activities";

	this.get_url = function(){
		var parameters = this.selection.get_parameters();
		var extra_par = "";
		if(this.order_by){ extra_par += "&order_by=" + this.order_by; }
		if(this.order_asc_desc){ extra_par += "&order_asc_desc=" + this.order_asc_desc; }
		return site_url + ajax_path + "&call=countries&format=json&per_page=" + this.per_page + "&page=" + this.page + parameters + extra_par;
	};

	this.update_pagination = function(data){

		var total = jQuery(this.list_div + " .list-amount-input").val();
		this.amount = total;

		var total_pages = Math.ceil(this.amount / this.per_page);
		var current_page = this.page;
		jQuery(this.pagination_div).bootpag({total: total_pages});

		jQuery("#current-grouped-list-count").html(jQuery("#grouped-list-wrapper .list-amount-input").val());
	};

	this.load_listeners = function(){

		jQuery("#grouped-list-search").keyup(function() {
			if (jQuery(this).val().length == 0){
				otherlist.selection.country = "";
				otherlist.refresh();
				Oipa.refresh_maps();
			} 
		});

		jQuery(".country-list-wrapper form").submit(function(e){
			e.preventDefault();
			otherlist.selection.country = jQuery("#grouped-list-search").val();
			otherlist.page = 1;
			otherlist.refresh();
			Oipa.refresh_maps();
		});
		
	};
}
OipaCountryList.prototype = new OipaList();

function OipaRegionList(){
	this.api_resource = "region-activities";

	this.get_url = function(){
		var parameters = this.selection.get_parameters();
		var extra_par = "";
		if(this.order_by){ extra_par += "&order_by=" + this.order_by; }
		if(this.order_asc_desc){ extra_par += "&order_asc_desc=" + this.order_asc_desc; }
		return site_url + ajax_path + "&call=regions&format=json&per_page=" + this.per_page + "&page=" + this.page + parameters + extra_par;
	};
}
OipaRegionList.prototype = new OipaList();

function OipaSectorList(){
	this.api_resource = "sector-activities";

	this.get_url = function(){
		var parameters = this.selection.get_parameters();
		var extra_par = "";
		if(this.order_by){ extra_par += "&order_by=" + this.order_by; }
		if(this.order_asc_desc){ extra_par += "&order_asc_desc=" + this.order_asc_desc; }
		return site_url + ajax_path + "&call=sectors&format=json&per_page=" + this.per_page + "&page=" + this.page + parameters + extra_par;
	};
}
OipaSectorList.prototype = new OipaList();

function OipaDonorList(){
	this.api_resource = "donor-activities";

	this.get_url = function(){
		var parameters = this.selection.get_parameters();
		var extra_par = "";
		if(this.order_by){ extra_par += "&order_by=" + this.order_by; }
		if(this.order_asc_desc){ extra_par += "&order_asc_desc=" + this.order_asc_desc; }
		if(this.query) {extra_par += "&query=" + this.query; }
		return site_url + ajax_path + "&call=donors&format=json&per_page=" + this.per_page + "&page=" + this.page + parameters + extra_par;
	};

	this.update_pagination = function(data){

		var total = jQuery(this.list_div + " .list-amount-input").val();
		this.amount = total;

		var total_pages = Math.ceil(this.amount / this.per_page);
		var current_page = this.page;
		jQuery(this.pagination_div).bootpag({total: total_pages});

		jQuery("#current-grouped-list-count").html(jQuery("#grouped-list-wrapper .list-amount-input").val());
	};

	this.load_listeners = function(){

		jQuery("#grouped-list-search").keyup(function() {
			if (jQuery(this).val().length == 0){
				otherlist.selection.donor = "";
				otherlist.refresh();
			} 
		});

		jQuery(".donor-list-wrapper form").submit(function(e){
			e.preventDefault();
			otherlist.selection.donor = jQuery("#grouped-list-search").val();
			otherlist.page = 1;
			otherlist.refresh();
		});
		
	};
}
OipaDonorList.prototype = new OipaList();
