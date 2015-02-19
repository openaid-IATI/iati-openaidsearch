function OipaSelection(main, has_default_reporter){
	this.countries = [];
	this.regions = [];
	this.sectors = [];
	this.budgets = [];
	this.reporting_organisations = [];
	this.start_actual_years = [];
	this.start_planned_years = [];
	this.country = null;
	this.region = null;
	this.donors = [];
	this.query = "";
	this.donor = "";
	this.group_by = "";
	
	if (has_default_reporter === 1){
		if (Oipa.default_organisation_id){
			this.reporting_organisations.push({"id": Oipa.default_organisation_id, "name": Oipa.default_organisation_name});
		}
	}
}

OipaSelection.prototype.get_parameters = function(){
	var parameters = this.create_parameter(this.regions, "recipient_regions");
	parameters += this.create_parameter(this.countries, "recipient_countries");
	parameters += this.create_parameter(this.sectors, "sectors");
	parameters += this.create_parameter(this.start_planned_years, "start_planned__in");
	parameters += this.create_parameter(this.donors, "participating_organisations");
	parameters += this.create_parameter(this.reporting_organisations, "reporting_organisation__in");
	parameters += this.create_parameter_budget(this.budgets);
	parameters += this.create_parameter_from_query_selection(this.query, "query");
	parameters += this.create_parameter_from_query_selection(this.country, "country");
	parameters += this.create_parameter_from_query_selection(this.region, "region");
	parameters += this.create_parameter_from_query_selection(this.donor, "donor");
	return parameters;
}

OipaSelection.prototype.update_selection_from_url = function(){
	var url_pars = window.location.search.substring(1);
	var selection = [];
	var selection_found = false;
	var that = this;

	if(url_pars !== ''){

		var vars = url_pars.split("&");
		for (var i=0;i<vars.length;i++) {
			var pair = vars[i].split("=");
			var vals = pair[1].split(",");
			for(var y=0;y<vals.length;y++){
				pair[0] = pair[0].replace("__in", "");
				if(that.active_filters.indexOf(pair[0]) > -1 || pair[0] == "query"){
					
					// to do: code to fill check selection boxes

					selection_found = true;
				}
			}
		}
	}
};

OipaSelection.prototype.create_parameter = function(arr, parameter_name){ 

	var parameters = this.parameter_array_to_string(arr);
	if (parameters !== ''){
		return "&" + parameter_name + "=" + parameters;
	} else {
		return '';
	}
}

OipaSelection.prototype.parameter_array_to_string = function(arr){

	dlmtr = ",";
	var str = '';

	if(arr.length > 0){
		for(var i = 0; i < arr.length; i++){
			str += arr[i].id + dlmtr;
		}
		str = str.substring(0, str.length-1);
	}
	return str;
}

OipaSelection.prototype.create_parameter_budget = function(arr){

	var gte = '';
	var lte = '';
	var str = '';

	if(arr.length > 0){
	  gte = '99999999999';
	  lte = '0';
	  for(var i = 0; i < arr.length; i++){
		curid = arr[i].id;
		lower_higher = curid.split('-');

		if(lower_higher[0] < gte){
		  gte = lower_higher[0];
		}

		if(lower_higher.length > 1){
		  if(lower_higher[1] > lte){
			lte = lower_higher[1];
		  }
		}
	  }
	}
  
	if (gte != '' && gte != '99999999999'){
		str += '&min_total_budget=' + gte;
	}
	if (lte != '' && lte != '0'){
		str += '&max_total_budget=' + lte;
	}

	return str;
}

OipaSelection.prototype.create_parameter_from_query_selection = function(str, parameter_name){

	if (str != "" && str != null){
		var str = "&"+parameter_name+"=" + str;
	} else {
		var str = "";
	}
	return str;
}