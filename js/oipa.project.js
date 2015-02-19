function OipaProject(){
	this.id = "";

	this.get_url = function(){

		var url =  search_url + "activities/"+ this.id +"/?format=json";
		return url;
	}

	this.export = function(format){

		var url = this.get_url();
		url = url.replace("format=json", "format=" + format);
		jQuery("#ExportListHiddenWrapper").remove();

		iframe = document.createElement('a');
        iframe.id = "ExportListHiddenWrapper";
        iframe.style.display = 'none';
        document.body.appendChild(iframe);

        var export_func_url = template_directory + "/export.php?path=" + encodeURIComponent(url);

        jQuery("#ExportListHiddenWrapper").attr("href", export_func_url);
        jQuery("#ExportListHiddenWrapper").attr("target", "_blank");
        jQuery("#ExportListHiddenWrapper").bind('click', function() {
			window.location.href = this.href;
			return false;
		});
        jQuery("#ExportListHiddenWrapper").click();
		jQuery("#download-dialog").toggle();
	}

};