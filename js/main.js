//HTML function to create filters
    function create_filter_attributes(objects, keys){
        var html = '';
        var counter = 0;
        $.each(objects, function(index, value){
            
            if (counter == 0 || counter == 20 || counter == 40 || counter == 60){
                html += '<div class="span3">';
            }
            html += '<div class="squaredThree">';
            html += '<input type="checkbox" value="'+ index +'" id="land'+index+'" name="check" />';
            html += '<label class="map-filter-cb-value" for="land'+index+'"></label>';
            html += '<span>'+value+'</span></div>'; 
            
            if (counter == 19 || counter == 39 || counter == 59){
                        html += '</div>';

            }
            counter++;
        });
        return html;
    }

// XXXXXXX MAP (GLOBAL) XXXXXXXXXXX


jQuery(function($) {

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

jQuery(function($){

  $('.filter-button').click(function(e){

      if($('#map-filter-overlay').is(":hidden")){
      
        if($('#map-hide-show-button').hasClass("map-hide")){
          show_map();
        }
      }

      $('#map-filter-overlay').show("blind", { direction: "vertical" }, 1000);         
      hide_all_filters();               
      if($(this).attr('id') == 'filter-by-country'){
        $('#country_filters').show();
      }
      if($(this).attr('id') == 'filter-by-region'){                   
        $('#region_filters').show();
      }
      if($(this).attr('id') == 'filter-by-budget'){
        $('#budget_filters').show();
      }
      if($(this).attr('id') == 'filter-by-sector'){
        $('#sector_filters').show();
      }
      if($(this).attr('id') == 'filter-by-indicator'){
        $('#indicator_filters').show();
      }
      if($(this).attr('id') == 'filter-by-dimension'){
        $('#indicator_filters').show();
      }
      if($(this).attr('id') == 'filter-by-city'){
        $('#city_filters').show();
      }
      $(this).addClass("filter-selected");

  });

  function hide_all_filters(){
    $('#country_filters').hide();
    $('#region_filters').hide();
    $('#budget_filters').hide();
    $('#sector_filters').hide();
    $('#indicator_filters').hide();
    $('#city_filters').hide();
    $('.filter-button.filter-selected').removeClass("filter-selected");
  }

	$('#map-filter-cancel').click(function(){

		// clear updated selection
    $('.filter-button.filter-selected').removeClass("filter-selected");
		$('#map-filter-overlay').hide("blind", { direction: "vertical" }, 1000);

	});

	$('#map-filter-save').click(function(){

    $('.filter-button.filter-selected').removeClass("filter-selected");
		// set selection as filter and load results
		$('#map-filter-overlay').hide("blind", { direction: "vertical" }, 1000);
                var str_sector = ''
                $('#sector_filters input:checked').each(function(index, value){ 
                    str_sector += value.value + ',';
                });
                str_sector = str_sector.substring(0, str_sector.length-1);
                
                var str_country = '';
                $('#country_filters input:checked').each(function(index, value){ 
                    str_country += value.value + ',';
                });
                str_country = str_country.substring(0, str_country.length-1);
                
                var str_budget = '';
                $('#budget_filters input:checked').each(function(index, value){ 
                    str_budget += value.value + ',';
                });
                str_budget = str_budget.substring(0, str_budget.length-1);
                
                var str_region = '';
                $('#region_filters input:checked').each(function(index, value){ 
                    str_region += value.value + ',';
                });
                str_region = str_region.substring(0, str_region.length-1);
                var str_indicator = '';
                $('#indicator_filters input:checked').each(function(index, value){ 
                    str_indicator += value.value + ',';
                });
                str_indicator = str_indicator.substring(0, str_indicator.length-1);
                
                var str_city = '';
                $('#city_filters input:checked').each(function(index, value){ 
                    str_city += value.value + ',';
                });
                str_city = str_city.substring(0, str_city.length-1);
                
                initialize_map('http://localhost:8000/json-city?sectors=' + str_sector + '&budgets=' + str_budget + '&countries=' + str_country + '&regions=' + str_region + '&indicator=' + str_indicator + '&city=' + str_city,2012,'',"", "", "");
                
                // window.location = '?sectors=' + str_sector + '&budgets=' + str_budget + '&countries=' + str_country + '&regions=' + str_region + '&indicator=' + str_indicator + '&city=' + str_city;
  });
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

});

