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

						<a href="#" id="project-share-export" class="project-share-button hneue-bold">
							<div class="share-icon"></div>
							<div class="share-text">EXPORT</div>
							<div id="dropdown-export-indicator" class="dropdown-menu-page-header">
								<button id="dropdown-png">AS IMAGE</button>
								<button id="dropdown-csv">AS CSV</button>
							</div>
						</a>
						<a id="project-share-embed" class="project-share-button hneue-bold">
							<div class="share-icon"></div>
							<div class="share-text">EMBED</div>
						</a>
						
					</div>
				</div>
				<?php get_template_part("global", "page-navbar-right"); ?>
			</div>
		</div>
	</div>
	<?php /*
	<div class="container">
		 <div class="row-fluid">
			<div class="span8">

				<div class="drop-shadow unhabitat-page">

					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<?php the_content(); ?>
					<?php endwhile; endif; ?></div>

			</div>
			<div class="span4">
				<?php get_sidebar( 'unhabitat-pages' ); ?>
			</div>
		</div> 
		<div class="row-fluid">
			<div class="span12">

				<div id="table-city-prosperity"></div>

			</div>
		</div>

	</div>		
	*/ ?>
	<div class="container">
		<div class="row-fluid">
			<div id="charts-column-1" class="span5">
				<h2>City prosperity in 2012</h2>
				<div id="table-chart-placeholder"></div>
			</div>
			<div id="charts-column-2" class="span7">
				<div id="bubble-chart-filter"></div>
				<div id="bubble-chart-placeholder"></div>
			</div>
		</div>
	</div>
</div>
<?php get_template_part('footer-scripts'); ?>
<script type="text/javascript">

	$(document).ready(function(){
	  selected_type = 'cpi';
	  save_selection();
	});

</script>
<?php get_footer(); ?>
