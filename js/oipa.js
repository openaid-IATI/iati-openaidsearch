var Oipa = {
	default_organisation_id: null,
	default_organisation_name: null,
	pageType: null,
	mainSelection: new OipaSelection(1),
	mainFilter: null,
	maps : [],
	refresh_maps : function(){
		for (var i = 0; i < this.maps.length; i++){
			this.maps[i].refresh();
		}
	},
	visualisations : [],
	refresh_visualisations : function(){
		for (var i = 0; i < this.visualisations.length; i++){
			this.visualisations[i].refresh();
		}
	},
	lists: [],
	refresh_lists : function(){
		for (var i = 0; i < this.lists.length; i++){
			this.lists[i].refresh();
		}
	}
};

function geo_point_to_latlng(point_string){
	point_string = point_string.replace("POINT (", "");
	point_string = point_string.substring(0, point_string.length - 1);
	lnglat = point_string.split(" ");
	latlng = [lnglat[1], lnglat[0]];
	return latlng;
}

function comma_formatted(amount) {

	sep = ",";

	if (amount){
		return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, sep);
	} else {
		return "-";
	}
}

function replaceAll(o,t,r,c){
	var cs = "";
	if(c===1){
		cs = "g";
	} else {
		cs = "gi";
	}
	var mp=new RegExp(t,cs);
	ns=o.replace(mp,r);
	return ns;
}
