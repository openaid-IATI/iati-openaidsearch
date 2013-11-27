<?php
/*
Template Name: Projects page
*/
?>

<?php wp_generate_results_v2($objects, $meta); ?>

<?php get_header(); ?>
<?php get_template_part( "map" ); ?>

<div id="page-wrapper">
	<div class="page-content">
		<div id="no-projects-found">Page not found.</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>




