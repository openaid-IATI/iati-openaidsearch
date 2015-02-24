jQuery(function($) {

  // zet zoom level on 2 instead of 3 on ipad
  function isiPad(){
      return (navigator.platform.indexOf("iPad") != -1);
  }

  if (isiPad()){
      map.setZoom(2);
  }

  $('#map-lightbox-close').click(function(){
    $('#map-lightbox').hide();
    $('#map-lightbox-bg').hide();
  });

  $('#map-hide-show-button').click(function(){
    if($(this).hasClass("map-show")){
      if ($('#map-filter-overlay').is(":visible")){
        $('#map-filter-overlay').hide('slow');
      }
      hide_map();
    } else {
      show_map();
    }
  });

  function hide_map(){
    standard_mapheight = $('#map').css('height');
    $('#map-hide-show-button').removeClass('map-show');
    $('#map-hide-show-button').addClass('map-hide');
    $('#map-hide-show-text').html("SHOW MAP");
    animate_map('13.5em');
    hide_map_homepage();
  }

  function show_map(){
    $('#map-hide-show-button').removeClass('map-hide');
    $('#map-hide-show-button').addClass('map-show');
    $('#map-hide-show-text').html("HIDE MAP");
    animate_map(standard_mapheight);
    show_map_homepage();
  }

  function animate_map(mapheight){
    $('#map').animate({
      height: mapheight
    }, 1000, function() {
       map.invalidateSize();
    });
  }

  function hide_map_homepage(){
    $('#map-lightbox').animate({
      fontSize: "0.7em",
      top: "3em"
    }, 1000, function() {
      // Animation complete.
    });
    $('#map-lightbox-bg').animate({
        fontSize: "0.7em",
        top: "3em"
      }, 1000, function() {
      // Animation complete.
    });
  }

  function show_map_homepage(){
    $('#map-lightbox').animate({
      fontSize: "1em",
      top: "10.5em"
    }, 1000, function() {
      // Animation complete.
    });
  $('#map-lightbox-bg').animate({
      fontSize: "1em",
      top: "10.5em"
    }, 1000, function() {
      // Animation complete.
    });
  }

  // Reload projects on pagination container click
  $('#pagination a').click(function(){ 
    var url = $(this).attr("href");
    var offset = get_url_parameter_value("offset", url);
    Oipa.lists[0].offset = [{"id":offset, "name":"unnamed"}];
    load_new_page(false, true);
    return false;
  });

  $('.project-sort-item').click(function(){
    var order_by = $(this).data('order-key');
    var asc_desc = $(this).data('order-by');
    Oipa.lists[0].order_by = asc_desc + order_by;
  });

  // sort-type-amount 
  $('#sort-type-amount a').click(function(){ 
    var url = $(this).attr("href");
    var per_page = get_url_parameter_value("per_page", url);
    current_selection["per_page"] = [{"id":per_page, "name":"unnamed"}];
    load_new_page(false, false);
    return false;
  });

  $("#project-share-embed").click(function(){
    if($('#dropdown-embed-url').is(":hidden")){
      embed_url = get_embed_url('projects-map');
      $('#dropdown-embed-url textarea').val(embed_url);
      $('#dropdown-embed-url').show("blind", { direction: "vertical" }, 200);
    } 

    return false;
  });

  $("#project-share-embed-close").click(function(){
    $('#dropdown-embed-url').hide("blind", { direction: "vertical" }, 200);
    return false;
  });
    

  // XXXXXXX sort buttons project page XXXXX

  $(".project-sort-type").click(function(){

    if($('.dropdown-project', this).is(":hidden")){
        $('.dropdown-project', this).show("blind", { direction: "vertical" }, 200);
    } else {
        $('.dropdown-project', this).hide("blind", { direction: "vertical" }, 200);
    }

    return false;
  });


  // plus - min button in project description 
  $('.project-expand-button').click(function(e){

    var currentId = $(this).attr('id');

      if($(this).hasClass("expand-plus")){
        $('.project-project-spec-hidden.'+currentId).show();
        $('.projects-project-description.'+currentId).css("height", "auto");
        $(this).removeClass('expand-plus');
        $(this).addClass('expand-min');
      } else {
        $('.project-project-spec-hidden.'+currentId).hide();
        $('.projects-project-description.'+currentId).css("height", "6em");
        $(this).removeClass('expand-min');
        $(this).addClass('expand-plus');
      }
    });

    $('#save-search-results').click(function(){
      generate_download_file();
      return false;
    }); 
    
    $('.projects-share-spec #project-share-export').click(function(){
      var id = $(this).attr('name');
      generate_download_file(id);
      return false;
    }); 

    $('.projects-share-spec .project-share-export').click(function(){
      var id = $(this).attr('name');
      generate_download_file(id);
      return false;
    }); 

});


  $(document).keyup(function(e) {
    if (e.keyCode == 27) { 
      if($("#map-filter-overlay").is(":visible")){
        Oipa.mainFilter.save();
      } 
    } 
  });
  



  function hide_all_filters(){
    $('#countries-filters').hide();
    $('#regions-filters').hide();
    $('#budgets-filters').hide();
    $('#sectors-filters').hide();
    $('#cities-filters').hide();
    $('#reporting_organisations-filters').hide();
  }

  $('#map-filter-cancel').click(function(){
    $('.filter-button.filter-selected').removeClass("filter-selected");
    $('#map-filter-overlay').hide("blind", { direction: "vertical" }, 1000);
  });

  $('#map-filter-save').click(function(){
    $('.filter-button.filter-selected').removeClass("filter-selected");
    Oipa.mainFilter.save();
  });


$(".selection-clear-div").click(function(){

  $('input:checkbox').each(function(){
    $(this).attr('checked', false);
  });
  current_selection = new Object();
  
  Oipa.mainFilter.save();
  
});




/* page header global dropdowns */
   
 $("#project-share-export").click(function(){

   if($('#dropdown-export-indicator').is(":hidden")){
       $('#dropdown-export-indicator').show("blind", { direction: "vertical" }, 200);
   } else {
       $('#dropdown-export-indicator').hide("blind", { direction: "vertical" }, 200);
   }
   return false;
 });

 $("#project-share-embed").click(function(){
   if($('#dropdown-embed-url').is(":hidden")) {
     if($('#dropdown-type-embed').is(":hidden")){
         $('#dropdown-type-embed').show("blind", { direction: "vertical" }, 200);
     } else {
         $('#dropdown-type-embed').hide("blind", { direction: "vertical" }, 200);
     }
   } 
   return false;
 });


 $("#project-share-embed-close").click(function(){
   $('#dropdown-embed-url').hide("blind", { direction: "vertical" }, 200); 
   return false;
 });

 $("#project-share-share").click(function(){

   if($('#dropdown-share-page').is(":hidden")){
       $('#dropdown-share-page').show("blind", { direction: "vertical" }, 200);
   } else {
       $('#dropdown-share-page').hide("blind", { direction: "vertical" }, 200);
   }
   return false;
 });



  $("#project-share-bookmark").click(function(){
    var href = document.URL.toString().split("?")[0] + build_current_url();
    var title = $(this).attr('alt');
    title = site_title + " - " + title.substring(9);
    bookmarksite(title, href);
    return false;
  });


  $("#page-share-whistleblower").click(function(){
    var url = "/whistleblower/?referrer=" + encodeURIComponent(document.URL.toString());
    window.location = url;
    return false;
  });

  
function get_embed_url(type){
  baseurl = ''; 
  width = '';
  height = '';
  if(type == 'projects-map'){
    baseurl = home_url + "/embed-projects/";
    width = '600';
    height = '290';
  } 
  iframeurl = baseurl + build_current_url();
  iframecode = '<script type="text/javascript" src="' + template_directory + '/js/embed.js"></script> \n';
  iframecode += '<script>\n oipa_embed.options(\n    url = ' + iframeurl + ',\n    width = ' + width + ',\n    height = ' + height + '\n);\n</script>';

  return iframecode;
}


function get_url_parameter_value(VarSearch, url){
    var SearchString = window.location.search.substring(1);
    if(url){
      SearchString = url.split('?')[1];
    }
    var VariableArray = SearchString.split('&');
    for(var i = 0; i < VariableArray.length; i++){
        var KeyValuePair = VariableArray[i].split('=');
        if(KeyValuePair[0] == VarSearch){
            return KeyValuePair[1];
        }
    }
    return null;
}


/***********************************************
* Bookmark site script- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

/* Modified to support Opera */
function bookmarksite(title,url){
if (window.sidebar) // firefox
  window.sidebar.addPanel(title, url, "");
else if(window.opera && window.print){ // opera
  var elem = document.createElement('a');
  elem.setAttribute('href',url);
  elem.setAttribute('title',title);
  elem.setAttribute('rel','sidebar');
  elem.click();
} 
else if(document.all)// ie
  window.external.AddFavorite(url, title);
}

if (navigator.userAgent.indexOf('Chrome') != -1 && parseFloat(navigator.userAgent.substring(navigator.userAgent.indexOf('Chrome') + 7).split(' ')[0]) >= 15){//Chrome
 $(".project-share-bookmark").css("display", "none");
    $("#page-share-bookmark").css("display", "none");
}

if (navigator.userAgent.indexOf("Opera") != -1){
  $(".project-share-bookmark").css("display", "none");
    $("#page-share-bookmark").css("display", "none");
}
