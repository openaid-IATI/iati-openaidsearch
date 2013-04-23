<?php
/*
Template Name: City prosperity page
*/
?>

<?php get_header(); ?>

<?php get_template_part( "city-prosperity", "filters" ); ?>
<?php get_template_part( "indicator", "map" ); ?>

<div id="page-wrapper">
	<div class="page-header page-header-less-margin">
		<div class="container">
			<div class="row-fluid">
				<div class="span8">
					<div class="project-share-container share-left">

						<button id="project-share-export" class="project-share-button hneue-bold">
							<div class="share-icon"></div>
							<div class="share-text">EXPORT</div>
							<div id="dropdown-export-indicator" class="dropdown-menu-page-header">
								<button id="dropdown-png">AS IMAGE</button>
								<button id="dropdown-csv">AS CSV</button>
							</div>
						</button>
						<button id="project-share-embed" class="project-share-button hneue-bold">
							<div class="share-icon"></div>
							<div class="share-text">EMBED</div>
						</button>
						
					</div>
				</div>
				<?php get_template_part("global", "page-navbar-right"); ?>
			</div>
		</div>
	</div>

	<div class="container">
		<?php /* <div class="row-fluid">
			<div class="span8">

				<div class="drop-shadow unhabitat-page">

					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<?php the_content(); ?>
					<?php endwhile; endif; ?></div>

			</div>
			<div class="span4">
				<?php get_sidebar( 'unhabitat-pages' ); ?>
			</div>
		</div> */ ?>
		<div class="row-fluid">
			<div class="span12">

				<div id="table-city-prosperity"></div>

			</div>
		</div>

	</div>
</div>

<?php get_footer(); ?>
<script type="text/javascript">

	$(document).ready(function(){
		load_map(site + 'json-city');
		function load_map(url){
			data = initialize_map(url,2012,'cpi',"", "", "");
			current_selection.indicator = [];
		  	current_selection.indicator.push({"id":"cpi", "name":"City prosperity"});
		  	fill_selection_box();
		}

	  selected_type = "cpi";
	  query_string_to_selection();
	  if (typeof current_selection.indicator === "undefined"){
	  	current_selection.indicator = [];
		current_selection.indicator.push({"id":"cpi_5_dimensions", "name":"City prosperity"});
	  }
	  reload_map();
	  initialize_filters();
	  fill_selection_box();
	
	});

</script>