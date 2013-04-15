// Global selection functions
var current_selection = new Object();


//HTML function to create filters
function create_filter_attributes(objects, columns){
    var html = '';
    var counter = 0;
    var per_col = 20;
// console.log(objects);
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
        html += '<input type="checkbox" value="'+ key +'" id="'+value.replaceAll("\\s","")+'" name="'+value+'" />';
        html += '<label class="map-filter-cb-value" for="'+value+'"></label>';
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
  var delimiter = ","; // replace comma if desired
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
  		animate_map('45em');
  		show_map_homepage();
  	}

  	function animate_map(mapheight){
  		$('#map').animate({
				height: mapheight
			}, 1000, function() {
			// Animation complete.
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


    // XXXXXX MAP SELECTION BOX XXXXXXX


  $("#selection-hide-show-button").click(function(){

    if($('#selection-box').is(":hidden")){
        $('#selection-box').show("blind", { direction: "vertical" }, 500);
        $('#selection-hide-show-text').html("HIDE SELECTION");
    } else {
        $('#selection-box').hide("blind", { direction: "vertical" }, 500);
        $('#selection-hide-show-text').html("SHOW SELECTION");
    }
  });
});


// XXXXXXX MAP FILTERS XXXXXXXXXXX

  $('.filter-button').click(function(e){

    var curId = $(this).attr('id');
    var filterContainerName = curId.replace("-button", "");

    if($('#map-filter-overlay').is(":hidden")){

      initialize_filters();

      if($('#map-hide-show-button').hasClass("map-hide")){
        show_map();
      }
      $('#map-filter-overlay').show("blind", { direction: "vertical" }, 1000);         
    
      hide_all_filters();
      $("#" + filterContainerName).show();

    } else {
      if($("#" + filterContainerName).is(":visible")){
        $('#map-filter-overlay').hide("blind", { direction: "vertical" }, 1000);
        hide_all_filters();
        save_selection();
      } else {
        hide_all_filters();
        $("#" + filterContainerName).show();
      }
    }
  });

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
    save_selection();
  });

function save_selection(){
   var new_selection = new Object();
    new_selection.sectors = [];
    new_selection.countries = [];
    new_selection.budgets = [];
    new_selection.regions = [];
    new_selection.indicators = [];
    new_selection.cities = [];

    // set selection as filter and load results
    $('#map-filter-overlay').hide("blind", { direction: "vertical" }, 1000);

    var dlmtr = ',';

    if ($('#project-filter-wrapper').length){ 
        dlmtr = '|';
    }

    var str_sector = get_checked_by_filter("sector", dlmtr);
    var str_country = get_checked_by_filter("country", dlmtr);
    var str_budget = get_checked_by_filter("budget", dlmtr);
    var str_region = get_checked_by_filter("region", dlmtr);
    var str_indicator = get_checked_by_filter("indicator", dlmtr);
    var str_city = get_checked_by_filter("city", dlmtr);
    
    current_selection = new_selection;


    // if project filter container is on the page (= projects page)
    if ($('#project-filter-wrapper').length){ 
    window.location = '?sectors=' + str_sector + '&budgets=' + str_budget + '&countries=' + str_country + '&regions=' + str_region;
    } else if (selected_type=='indicator'){
      initialize_map('http://dev.oipa.openaidsearch.org/json?sectors=' + str_sector + '&budgets=' + str_budget + '&countries=' + str_country + '&regions=' + str_region + '&indicator=' + str_indicator + '&city=' + str_city,2010,'',"", "", "");
    } else if (selected_type=='cpi'){
      initialize_map('http://dev.oipa.openaidsearch.org/json-city?sectors=' + str_sector + '&budgets=' + str_budget + '&countries=' + str_country + '&regions=' + str_region + '&indicator=' + str_indicator + '&city=' + str_city,2012,'',"", "", "");
    }

}

function get_checked_by_filter(filtername, dlmtr){
  var str = '';
  $('#' + filtername + '_filters input:checked').each(function(index, value){ 
        str = value.value + dlmtr;
        new_selection[filtername + "s"].push({"id":value.value, "name":value.name});
    });
  str = str.substring(0, str.length-1);
  return str;
}

function initialize_filters(){
  $('#map-filter-overlay input:checked').prop('checked', false);
  init_filters_loop(current_selection.sectors);
  init_filters_loop(current_selection.countries);
  init_filters_loop(current_selection.budgets);
  init_filters_loop(current_selection.regions);
  init_filters_loop(current_selection.indicators);
  init_filters_loop(current_selection.cities);
}

function init_filters_loop(arr){
  for(var i = 0; i < arr.length;i++){
    var curId = arr[i].id.replaceAll("\\s","");
    $('#'+curId).prop('checked', true);
  }
}



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


