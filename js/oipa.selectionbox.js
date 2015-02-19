function OipaSelectionBox() {
	this.selection = null;
}

OipaSelectionBox.prototype.fill = function(){

	var html = '';
	if ((typeof this.selection.sectors !== "undefined") && (current_selection.sectors.length > 0)) { html += this.fill_single_filter("SECTORS", current_selection.sectors); }
	if ((typeof this.selection.countries !== "undefined") && (current_selection.countries.length > 0)) { html += this.fill_single_filter("COUNTRIES", current_selection.countries); }
	if ((typeof this.selection.budgets !== "undefined") && (current_selection.budgets.length > 0)) { html += this.fill_single_filter("BUDGETS", current_selection.budgets); }
	if ((typeof this.selection.regions !== "undefined") && (current_selection.regions.length > 0)) { html += this.fill_single_filter("REGIONS", current_selection.regions); }
	if ((typeof this.selection.reporting_organisations !== "undefined") && (current_selection.reporting_organisations.length > 0)) { html += this.fill_single_filter("REPORTING_ORGANISATIONS", current_selection.reporting_organisations); }
	if ((typeof this.selection.query !== "undefined") && (current_selection.query.length > 0)) { html += this.fill_single_filter("QUERY", current_selection.query); }
	jQuery("#selection-box").html(html);
	this.load_remove_filters_listeners();
}

OipaSelectionBox.prototype.fill_single_filter = function(header, arr){

	var html = '<div class="select-box" id="selected-' + header.toLowerCase() + '">';
	html += '<div class="select-box-header">';
	if (header === "QUERY"){header = "SEARCH"; }
	if (header === "REPORTING_ORGANISATIONS"){header = "REPORTING ORGANISATIONS"; }
	html += header;
	html += '</div>';

	for(var i = 0; i < arr.length; i++){
		html += '<div class="select-box-selected">';
		html += '<div id="selected-' + arr[i].id.toString().replace(/ /g,'').replace(',', '').replace('&', '').replace('%', 'perc') + '" class="selected-remove-button"></div>';

		if (arr[i].name.toString() == 'unknown'){
			arr[i].name = jQuery(':checkbox[value=' + arr[i].id + ']').attr("name");
		}

		html += '<div>' + arr[i].name + '</div>';
		html += '</div>';
	}

	html += '</div>';
	return html;
}

OipaSelectionBox.prototype.load_remove_filters_listeners = function (){

	jQuery(".selected-remove-button").click(function(){
		var id = jQuery(this).attr('id');
		id = id.replace("selected-", "");
		var filtername = jQuery(this).parent().parent().attr('id');
		filtername = filtername.replace("selected-", "");
		var arr = current_selection[filtername];
		for (var i = 0; i < arr.length;i++){
			if(arr[i].id === id){
				jQuery('input[name="' + arr[i].name + '"]').attr('checked', false);
				break;
			}
		}
		this.save_selection();
	});
}