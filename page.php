<?php
/*
Template Name: Standard page
*/
?>

<?php get_header(); ?>

<?php get_template_part( "map" ); ?>

<div clas="unhabitat-page-top">


</div>


<div class="page-wrapper">
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
				 <?php get_sidebar( 'unhabitat-pages' ); ?> 
				</div>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>