// XXXXXXXXXXXXX PROJECT PAGE XXXXXXXXXXXXX


// plus - min button in project description 
jQuery(function($){
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
});




// XXXXXXX PROJECT DETAIL PAGE XXXXXXXXXXX

jQuery(function($) {



  function change_tab(curlink, newtab){

  	// hide all tab content
  	$(".project-tabs-wrapper > div").hide();
  	// remove active tab
  	$(".nav-pills > li").removeClass("active");
  	// make link of current tab active
	$(curlink).closest("li").addClass("active");
	// show current tab content
	$("#project-" + newtab).show();

  }

  $("#project-description-link").click(function(){
	  change_tab(this, "description");
	  return false;
  });

  $("#project-commitments-link").click(function(){
	  change_tab(this, "commitments");
	  return false;
  });

  $("#project-documents-link").click(function(){
	  change_tab(this, "documents");
	  return false;
  });

  $("#project-related-indicators-link").click(function(){
	  change_tab(this, "related-indicators");
	  return false;
  });

  $("#project-related-projects-link").click(function(){
	  change_tab(this, "related-projects");
	  return false;
  });

  $("#project-located-in-link").click(function(){
	  change_tab(this, "located-in");
	  return false;
  });

});


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

// XXXXXXX MAP FILTERS XXXXXXXXXXX


	$('.filter-button').click(function(){

		if ($('#map-filter-overlay').is(":hidden")){
			
			if($('#map-hide-show-button').hasClass("map-hide")){
				show_map();
			}

			$('#map-filter-overlay').show("blind", { direction: "vertical" }, 1000);

		} else {
			// save selection?
			$('#map-filter-overlay').hide("blind", { direction: "vertical" }, 1000);
		}
		
	});

	$('#map-filter-cancel').click(function(){

		// clear updated selection
		$('#map-filter-overlay').hide("blind", { direction: "vertical" }, 1000);
	});

	$('#map-filter-save').click(function(){

		// set selection as filter and load results
		$('#map-filter-overlay').hide("blind", { direction: "vertical" }, 1000);
	});



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



