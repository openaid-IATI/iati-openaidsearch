<?php
/*
Template Name: Projects page
*/
?>

<?php wp_generate_results_v2($objects, $meta); ?>

<?php get_header(); ?>
<?php get_template_part( "projects", "filters" ); ?>
<?php get_template_part( "map" ); ?>

<div id="page-wrapper">
		
<?php include( TEMPLATEPATH .'/projects-description.php' ); ?>

</div>
<div id="paginated-loader">
    <div id="paginated-text">Loading projects</div>
    <img src="<?php echo get_template_directory_uri(); ?>/images/ajax-loader.gif" alt="" />
</div>

<?php get_template_part('footer-scripts'); ?>
<script>
    $(document).ready(function() {
      selected_type = "projects";
      save_selection(true);
    });
</script>
<?php get_footer(); ?>



