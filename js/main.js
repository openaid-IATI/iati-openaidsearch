// Global variables
var current_selection = new Object();
var selected_type = '';
var standard_mapheight = '45em';

//HTML function to create filters
function create_filter_attributes(objects, columns){
    var html = '';
    var counter = 0;
    var per_col = 20;

//     var sortable = [];
//     for (var key in objects){
//       sortable.push([key, objects[key]]);
//     }

//     sortable.sort(function(a, b){
//      var nameA=a.name.toLowerCase(), nameB=b.name.toLowerCase()
//      if (nameA < nameB) //sort string ascending
//       return -1 
//      if (nameA > nameB)
//       return 1
//      return 0 //default return value (no sorting)
//     });
//     console.log(sortable);

//     for (var i = 0; i < sortable.length; i++) {
//       if (i%per_col == 0){
//             html += '<div class="span' + (12 / columns) + '">';
//         } 
//         html += '<div class="squaredThree"><div>';
//         html += '<input type="checkbox" value="'+ sortable[i][0] +'" id="land'+sortable[i][1]+'" name="check" />';
//         html += '<label class="map-filter-cb-value" for="land'+sortable[i][1]+'"></label>';
//         html += '</div><div><span>'+sortable[i][1]+'</span></div></div>';

//         if (i%per_col == (per_col - 1)){
//           html += '</div>';
//         }

//         if ((i+1) > ((per_col * columns) - 1)) { break }

    // }

    $.each(objects, function(key, value){

        if (counter%per_col == 0){
            html += '<div class="span' + (12 / columns) + '">';
        } 
        html += '<div class="squaredThree"><div>';
        html += '<input type="checkbox" value="'+ key +'" id="'+value.toString().replace(/ /g,'').replace(',', '').replace('&', '').replace('%', 'perc')+'" name="'+value+'" />';
        html += '<label class="map-filter-cb-value" for="'+value.toString().replace(/ /g,'').replace(',', '').replace('&', '').replace('%', 'perc')+'"></label>';
        html += '</div><div><span>'+value+'</span></div></div>';
        if (counter%per_col == (per_col - 1)){
          html += '</div>';
        }
        counter++;
        if (counter > ((per_col * columns) - 1)) { return false; }
    });
    return html;
}

function CommaFormatted(amount) {
  var delimiter = "."; // replace comma if desired
  amount = new String(amount);
  var a = amount.split('.',2)
  var d = a[1];
  var i = parseInt(a[0]);
  if(isNaN(i)) { return ''; }
  var minus = '';
  if(i < 0) { minus = '-'; }
  i = Math.abs(i);
  var n = new String(i);
  var a = [];
  while(n.length > 3)
  {
    var nn = n.substr(n.length-3);
    a.unshift(nn);
    n = n.substr(0,n.length-3);
  }
  if(n.length > 0) { a.unshift(n); }
  n = a.join(delimiter);
  if(d.length < 1) { amount = n; }
  else { amount = n + '.' + d; }
  amount = minus + amount;
  return amount;
}

// XXXXXXX MAP (GLOBAL) XXXXXXXXXXX


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

	function hide_map()
	{
    // TO DO: TEST IF THIS WORKS
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
});


// MAP FILTER FUNCTIONS

  $('.filter-button').click(function(e){

    var curId = $(this).attr('id');
    var filterContainerName = curId.replace("-button", "");
    $('.filter-button.filter-selected').removeClass("filter-selected");
    
    if($('#map-filter-overlay').is(":hidden")){

      $('#map-filter-overlay').show("blind", { direction: "vertical" }, 600);         
      $(this).addClass("filter-selected");
      hide_all_filters();

      if($('#map-hide-show-button').hasClass("map-hide")){
        show_map();
      }

      // load the project filter options based on the current selection
      if(selected_type == 'projects'){
        initialize_project_filter_options();
      }

      initialize_filters();
      $("#" + filterContainerName).show();

    } else {
      if($("#" + filterContainerName).is(":visible")){
        $('#map-filter-overlay').hide("blind", { direction: "vertical" }, 600);
        hide_all_filters();
        save_selection();
      } else {
        $(this).addClass("filter-selected");
        hide_all_filters();
        $("#" + filterContainerName).show();
      }
    }
  });

  function initialize_filters(){
    $('#map-filter-overlay input:checked').prop('checked', false);

    if (!(typeof current_selection.sector === "undefined")) init_filters_loop(current_selection.sector);
    if (!(typeof current_selection.country === "undefined")) init_filters_loop(current_selection.country);
    if (!(typeof current_selection.budget === "undefined")) init_filters_loop(current_selection.budget);
    if (!(typeof current_selection.region === "undefined")) init_filters_loop(current_selection.region);
    if (!(typeof current_selection.indicator === "undefined")) init_filters_loop(current_selection.indicator);
    if (!(typeof current_selection.city === "undefined")) init_filters_loop(current_selection.city);
  }

  function init_filters_loop(arr){
    for(var i = 0; i < arr.length;i++){
      var curId = arr[i].name.toString().replace(/ /g,'').replace(',', '').replace('&', '').replace('%', 'perc');
      $('#'+curId).prop('checked', true);
    }
  }

  function hide_all_filters(){
    $('#country-filters').hide();
    $('#region-filters').hide();
    $('#budget-filters').hide();
    $('#sector-filters').hide();
    $('#indicator-filters').hide();
    $('#city-filters').hide();
  }

	$('#map-filter-cancel').click(function(){
    $('.filter-button.filter-selected').removeClass("filter-selected");
		$('#map-filter-overlay').hide("blind", { direction: "vertical" }, 1000);
	});

	$('#map-filter-save').click(function(){
    $('.filter-button.filter-selected').removeClass("filter-selected");
    save_selection();
  });

// FILTER SAVE FUNCTIONS

function save_selection(){
    
    var new_selection = new Object();
    new_selection.sector = [];
    new_selection.country = [];
    new_selection.budget = [];
    new_selection.region = [];
    new_selection.indicator = [];
    new_selection.city = [];

    // set selection as filter and load results
    $('#map-filter-overlay').hide("blind", { direction: "vertical" }, 1000);

    get_checked_by_filter("sector", new_selection);
    get_checked_by_filter("country", new_selection);
    get_checked_by_filter("budget", new_selection);
    get_checked_by_filter("region", new_selection);
    get_checked_by_filter("indicator", new_selection);
    get_checked_by_filter("city", new_selection);
    current_selection = new_selection;

    fill_selection_box();
    reload_map();
}

function get_checked_by_filter(filtername, new_selection){
  $('#' + filtername + '-filters input:checked').each(function(index, value){ 
        new_selection[filtername].push({"id":value.value, "name":value.name});
    });
}

// MAP RELOAD FUNCTIONS 

function reload_map(){
  
  var dlmtr = ',';

  var str_sector = reload_map_prepare_parameter_string("sector", dlmtr);
  var str_country = reload_map_prepare_parameter_string("country", dlmtr);
  var str_budget = reload_map_prepare_parameter_string("budget", dlmtr);
  var str_region = reload_map_prepare_parameter_string("region", dlmtr);
  var str_indicator = reload_map_prepare_parameter_string("indicator", dlmtr);
  var str_city = reload_map_prepare_parameter_string("city", dlmtr);

  // if project filter container is on the page (= projects page)
  if (selected_type=='projects'){
  initialize_projects_map('http://dev.oipa.openaidsearch.org/json-activities?sectors=' + str_sector + '&budgets=' + str_budget + '&countries=' + str_country + '&regions=' + str_region, 'projects');
  } else if (selected_type=='indicator'){
    initialize_map('http://dev.oipa.openaidsearch.org/json?sectors=' + str_sector + '&budgets=' + str_budget + '&countries=' + str_country + '&regions=' + str_region + '&indicator=' + str_indicator + '&city=' + str_city,2015,'',"", "", "");
    move_slider_to_available_year(2015);
  } else if (selected_type=='cpi'){
    initialize_map('http://dev.oipa.openaidsearch.org/json-city?sectors=' + str_sector + '&budgets=' + str_budget + '&countries=' + str_country + '&regions=' + str_region + '&indicator=' + str_indicator + '&city=' + str_city,2012,'',"", "", "");
  }
}


function reload_map_prepare_parameter_string(filtername, dlmtr){
  var str = '';
  if(!(typeof current_selection[filtername] === 'undefined')){
    var arr = current_selection[filtername];
    if(arr.length > 0){
      for(var i = 0; i < arr.length; i++){
        str += arr[i].id + dlmtr;
      }
      str = str.substring(0, str.length-1);
    }
  }
  return str;
}

function move_slider_to_available_year(standard_year){
  
  for (var i = standard_year; i > 1949;i--){
    
    if ($("#year-"+i).hasClass("slider-active")){
      if(i == standard_year){break;}
      refresh_circles(i);
      $( "#map-slider-tooltip" ).val(i);
      $( "#map-slider-tooltip div" ).text(i.toString());
      $( ".slider-year").removeClass("active");
      $( "#year-" + i.toString()).addClass("active");
      break;
    }
  }
}



// SELECTION BOX FUNCTIONS

  $("#selection-hide-show-button").click(function(){

    if($('#selection-box').is(":hidden")){
        $('#selection-box').show("blind", { direction: "vertical" }, 500);
        $('#selected-clear').show("blind", { direction: "vertical" }, 500);
        $('#selection-hide-show-text').html("HIDE SELECTION");
    } else {
        $('#selection-box').hide("blind", { direction: "vertical" }, 500);
        $('#selected-clear').hide("blind", { direction: "vertical" }, 500);
        $('#selection-hide-show-text').html("SHOW SELECTION");
    }
  });

function fill_selection_box(){

  var html = '';
  var indicatorhtml = '';
  if (!(typeof current_selection.sector === "undefined") && (current_selection.sector.length > 0)) html += fill_selection_box_single_filter("SECTORS", "sector", current_selection.sector);
  if (!(typeof current_selection.country === "undefined") && (current_selection.country.length > 0)) html += fill_selection_box_single_filter("COUNTRIES", "country", current_selection.country);
  if (!(typeof current_selection.budget === "undefined") && (current_selection.budget.length > 0)) html += fill_selection_box_single_filter("BUDGETS","budget", current_selection.budget);
  if (!(typeof current_selection.region === "undefined") && (current_selection.region.length > 0)) html += fill_selection_box_single_filter("REGIONS", "region", current_selection.region);
  if (!(typeof current_selection.city === "undefined") && (current_selection.city.length > 0)) html += fill_selection_box_single_filter("CITIES", "city", current_selection.city);
  if (!(typeof current_selection.indicator === "undefined") && (current_selection.indicator.length > 0)) indicatorhtml = fill_selection_box_single_filter("INDICATORS", "indicator", current_selection.indicator);
  $("#selection-box").html(html);
  $("#selection-box-indicators").html(indicatorhtml);
  init_remove_filters_from_selection_box();
}

function fill_selection_box_single_filter(header, singlename, arr){
  var html = '<div class="select-box" id="selected-' + singlename + '">';
      html += '<div class="select-box-header">';
      if (header == "INDICATORS" && selected_type == "cpi"){ header = "DIMENSIONS";};
      html += header;
      html += '</div>';

      for(var i = 0; i < arr.length; i++){
        html += '<div class="select-box-selected">';
        html += '<div id="selected-' + arr[i].id.toString().replace(/ /g,'').replace(',', '').replace('&', '').replace('%', 'perc') + '" class="selected-remove-button"></div>';
        html += '<div>' + arr[i].name + '</div>';
        html += '</div>';
      }

      html += '</div>';
      return html;
}

function init_remove_filters_from_selection_box(){
  $(".selected-remove-button").click(function(){
    var id = $(this).attr('id');
    id = id.replace("selected-", "");
    var filtername = $(this).parent().parent().attr('id');
    filtername = filtername.replace("selected-", "");
    var arr = current_selection[filtername];
    for (var i = 0; i < arr.length;i++){
      if(arr[i].id == id){
        arr.splice(i, 1);
        break;
      }
    }
    fill_selection_box();
    reload_map();
  });
}

$(".selection-clear-div").click(function(){
  current_selection = new Object();
  current_selection.indicator = [];
  current_selection.indicator.push({"id":"population", "name":"Total population"});
  fill_selection_box();
  reload_map();
});


// XXXXXXXXX Ajax wordpress simple pagination XXXXXXXX
jQuery(function($){


  // load listeners at page initialisation:
  load_projects_listeners();

  

  function load_new_page(link){
    
   $('#page-wrapper').fadeOut(100, function(){ //fade out the content area
   $("#paginated-loader").show();
   }).load(link + ' #page-wrapper', function(){ 
    $("#paginated-loader").hide();
    $('#page-wrapper').fadeIn(200, function(){
      load_projects_listeners();

   
   }); });
   $('html,body').animate({
           scrollTop: ($("#map-hide-show").offset().top - 150)},
           'slow');
  }


  function load_projects_listeners(){
    
    // Reload projects on pagination container click
    $('#pagination a').click(function(){ 
     var link = $(this).attr('href');
     load_new_page(link);
     return false;
    });

    // Reload projects on sort type click
    $('.project-sort-type a').click(function(){ 
     var link = $(this).attr('href');
     load_new_page(link);
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
    // TO DO: show the whole description.

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
  }

  // IE: prevent focus on internet explorer
  var _preventDefault = function(evt) { evt.preventDefault(); };
  $("#map-slider-tooltip div").bind("dragstart", _preventDefault).bind("selectstart", _preventDefault);


});






  $("#project-share-bookmark").click(function(){
    var href = $(this).attr('href');
    var title = $(this).attr('alt');
    title = title.substring(9);
    bookmarksite(title, href);
    return false;
  });

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





//   $('.saveresults').click(function(){
//     generate_download_file();
//     return false;
//   }); 
  
//   $('.export').click(function(){
//     var id = $(this).attr('id');
//     generate_download_file(id);
//     return false;
//   }); 


// function generate_download_file(id) {
//   var url = sThemePath + "/export.php?author=" + sBlogName,
//       urlSep = "&", country_fltr = '', region_fltr = '', sector_fltr = '', budget_fltr = '', sep = '';
  
//   if(id) {
//     url +=  urlSep + "id=" + id;
//   }
  
//   $('.filterbox input[type=checkbox]:checked').each(function(){
//     var control_name = $(this).attr('name');
//     var key = $(this).val();
//     switch(control_name) {
//       case 'countries':
//         if(country_fltr.length==0) sep = '';
//         country_fltr += sep + key;
//         sep = "|";
//         break;
//       case 'regions':
//         if(region_fltr.length==0) sep = '';
//         region_fltr += sep + key;
//         sep = "|";
//         break;
//       case 'sectors':
//         if(sector_fltr.length==0) sep = '';
//         sector_fltr += sep + key;
//         sep = "|";
//         break;
//       case 'budgets':
//         if(budget_fltr.length==0) sep = '';
//         budget_fltr += sep + key;
//         sep = "|";
//         break;
//     }
//   });
  
//   country_fltr = country_fltr.replace(/(All\|)|(\|All)|(All)/g, '');
//   region_fltr = region_fltr.replace(/(All\|)|(\|All)|(All)/g, '');
//   sector_fltr = sector_fltr.replace(/(All\|)|(\|All)|(All)/g, '');
//   budget_fltr = budget_fltr.replace(/(All\|)|(\|All)|(All)/g, '');

//   var keyword = jQuery('#s').val();
//   if(keyword) {
//     url +=  urlSep + "query=" + encodeURI(keyword);
//     urlSep = "&";
//   }
  
  
//   if(country_fltr.length>0) {
//     url +=  urlSep + "countries=" + country_fltr;
//     urlSep = "&";
//     isFilter = true;
//   }
//   if(region_fltr.length>0) {
//     url +=  urlSep + "regions=" + region_fltr;
//     urlSep = "&";
//     isFilter = true;
//   }
//   if(sector_fltr.length>0) {
//     url +=  urlSep + "sectors=" + sector_fltr;
//     urlSep = "&";
//     isFilter = true;
//   }
//   if(budget_fltr.length>0) {
//     url +=  urlSep + "budgets=" + budget_fltr;
//     urlSep = "&";
//     isFilter = true;
//   }
  
  
//   $("#secretIFrame").attr("src",url);
// }
