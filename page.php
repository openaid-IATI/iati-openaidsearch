<?php
/*
Template Name: Standard page
*/
?>

<?php get_header(); ?>

<?php get_template_part( "map" ); ?>


<div id="page-wrapper">
	<div class="page-header">
		<div class="container">
			<div class="row-fluid">
				<div class="span8"></div>
				<?php get_template_part("global", "page-navbar-right"); ?>
			</div>
		</div>
	</div>



	<div class="container">
		<div class="page-content">
			<div class="row-fluid">
				<div class="span8">
					
					<div class="drop-shadow unhabitat-page">
						
						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						<?php the_content(); ?>
						<?php endwhile; endif; ?>
					
					</div>

				</div>
				<div class="span4">
				 <?php 
				if ( dynamic_sidebar('unh-page-sidebar') ) : 
				else : 
				?>
				<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_template_part('footer-scripts'); ?>
<script type="text/javascript">

    $(document).ready(function() {
      selected_type = "projects";
      query_string_to_selection();
      reload_map();
      initialize_filters();
      fill_selection_box();
    });

</script>
<?php get_footer(); ?>
