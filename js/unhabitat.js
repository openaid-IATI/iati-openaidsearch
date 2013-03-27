jQuery(function($){
  $('.project-back-button').click(function(e){
    history.back();
  });
});

jQuery(function($){
  $('.project-expand-button').click(function(e){
    // TO DO: show the whole description.
  });
});




jQuery(function($) {

  // Way 1
  function hide_all_project_tabs()
  {
  	$(".project-description").hide();
    $(".project-commitments").hide();
    $(".project-documents").hide();
    $(".project-related-projects").hide();
    $(".project-related-indicator").hide();
  }	


  $(".project-description-link").click(function(){
  	  alert('test');
	  hide_all_project_tabs();
	  
	  return false;
  });

  $(".project-commitments-link").click(function(){
	  hide_all_project_tabs();
	  return false;
  });

  $(".project-documents-link").click(function(){
	  hide_all_project_tabs();
	  return false;
  });

  $(".project-related-indicators-link").click(function(){
	  hide_all_project_tabs();
	  return false;
  });

  $(".project-related-projects-link").click(function(){
	  hide_all_project_tabs();
	  return false;
  });

});


jQuery(function($) {
	$('#map-hide-show-button').click(function(){

		var mapheight = '45em';
		if($(this).hasClass("map-show")){
			mapheight = '13.5em';
			$(this).removeClass('map-show');
			$(this).addClass('map-hide');
			$(this).html("SHOW MAP");

		} else {
			mapheight = '45em';
			$(this).removeClass('map-hide');
			$(this).addClass('map-show');
			$(this).html("HIDE MAP");
		}

		$('#map').animate({
			height: mapheight
		}, 1000, function() {
		// Animation complete.
		});
	});
});



jQuery(function($) {
	$('.project-expand-button').click(function(){

		var mapheight = '45em';
		if($(this).hasClass("map-show")){
			mapheight = '13.5em';
			$(this).removeClass('map-show');
			$(this).addClass('map-hide');
			$(this).html("SHOW MAP");

		} else {
			mapheight = '45em';
			$(this).removeClass('map-hide');
			$(this).addClass('map-show');
			$(this).html("HIDE MAP");
		}

		$('#map').animate({
			height: mapheight
		}, 1000, function() {
		// Animation complete.
		});
	});
});


jQuery(function($) {

	$('.project-filter-button').click(function(){

		if ($('#map-filter-overlay').is(":hidden")){
			
			$('#map-filter-overlay').show('slow');
		} else {
			// save selection?
			$('#map-filter-overlay').hide('slow');
		}
		
	});

	

	$('#map-filter-cancel').click(function(){

		// clear updated selection
		$('#map-filter-overlay').hide('slow');
	});

	$('#map-filter-save').click(function(){

		// set selection as filter and load results
		$('#map-filter-overlay').hide('slow');
	});
});

