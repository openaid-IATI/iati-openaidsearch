<?php
/*
Template Name: About page
*/
?>

<?php get_header(); ?>

<?php get_template_part( "map" ); ?>

<div class="page-wrapper">
	<div class="container">
		<div class="page-content">

			<div class="row-fluid postit-page">
				<div class="span4">

				<?php 

				$cat_obj = get_category_by_slug('row-1'); 
				$row_cat_id = $cat_obj->term_id;

				$args = array(
				'posts_per_page'  => 15,
				'numberposts'     => 15,
				'category'        => $row_cat_id,
				'post_type'       => 'about-item',
				);

			 	$row1_posts_array = get_posts( $args );

 				foreach( $row1_posts_array as $post ) :	setup_postdata($post); ?>

					<div class="drop-shadow postit about-item">
						<div class="postit-title"><?php the_title(); ?></div>
						<div class="postit-text"><?php the_content(); ?></div>
					</div>

				<?php endforeach; ?>
				</div>



				<div class="span4">
				<?php 

				$cat_obj = get_category_by_slug('row-2'); 
				$row_cat_id = $cat_obj->term_id;

				$args = array(
				'posts_per_page'  => 15,
				'numberposts'     => 15,
				'category'        => $row_cat_id,
				'post_type'       => 'about-item',
				);


			 	$row1_posts_array = get_posts( $args );

 				foreach( $row1_posts_array as $post ) :	setup_postdata($post); ?>

					<div class="drop-shadow postit about-item">
						<div class="postit-title"><?php the_title(); ?></div>
						<div class="postit-text"><?php the_content(); ?></div>
					</div>

				<?php endforeach; ?>
				</div>

				<div class="span4">
				<?php 

				$cat_obj = get_category_by_slug('row-3'); 
				$row_cat_id = $cat_obj->term_id;

				$args = array(
				'posts_per_page'  => 15,
				'numberposts'     => 15,
				'category'        => $row_cat_id,
				'post_type'       => 'about-item',
				);


			 	$row1_posts_array = get_posts( $args );

 				foreach( $row1_posts_array as $post ) :	setup_postdata($post); ?>

					<div class="drop-shadow postit about-item">
						<div class="postit-title"><?php the_title(); ?></div>
						<div class="postit-text"><?php the_content(); ?></div>
					</div>

				<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>