
function OipaMainStats(){
	this.reporting_organisation = null;

	this.get_total_projects = function(reporting_organisation){

		var url = site_url + ajax_path + '&call=total-projects';
		var stats = this;
		jQuery.ajax({
			type: 'GET',
			url: url,
			contentType: "application/json",
			dataType: 'json',
			success: function(data){
				jQuery("#project-list-amount, #total-project-amount").text(data[0].aggregation_field);
				jQuery(".total-project-amount").text(data[0].aggregation_field);
			}
		});
	};

	this.get_total_donors = function(reporting_organisation){

		var url = site_url + ajax_path + '&call=total-donors';
		var stats = this;
		jQuery.ajax({
			type: 'GET',
			url: url,
			contentType: "application/json",
			dataType: 'json',
			success: function(data){
				jQuery("#total-donor-amount").text(data.meta.total_count);
			}
		});
	};

	this.get_total_countries = function(reporting_organisation){

		var url = site_url + ajax_path + '&call=total-countries';
		var stats = this;
		jQuery.ajax({
			type: 'GET',
			url: url,
			contentType: "application/json",
			dataType: 'json',
			success: function(data){
				jQuery("#total-country-amount").text(data.meta.total_count);
			}
		});
	};

	this.get_total_budget = function(reporting_organisation){

		var url = site_url + ajax_path + '&call=homepage-total-budget';
		var stats = this;
		
		jQuery.ajax({
			type: 'GET',
			url: url,
			contentType: "application/json",
			dataType: 'json',
			success: function(data){
				var options = {
				  useEasing : true, 
				  useGrouping : true, 
				  separator : ',', 
				  decimal : '.',
				  prefix : '',
				  suffix : '' 
				}

				var total_budget = new countUp("total-budget", 0, data[0].aggregation_field, 0, 2.5, options);
				total_budget.start();
			}
		});
	};


}
