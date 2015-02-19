
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
  	$(newtab).show();

  }

  $(".nav-pills a").click(function(){
    var target_div = $(this).data("target-div");
    change_tab(this, "#" + target_div);
  });

  $("#project-back-button").click(function(){
  	  window.history.back();
  });


  $("#project-rsr-link").click(function(){

	  $(".project-detail-block").hide();
	  $(".main-page-content").hide();
	  return false;
  });
});

function refresh_rsr_projects(iati_id){

  url = site_url + "/wp-admin/admin-ajax.php?action=rsr_call&iati_id=" + iati_id;
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