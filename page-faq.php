<?php
/*
Template Name: FAQ page
*/
?>

<?php get_header(); ?>

<?php get_template_part( "map" ); ?>

<div id="page-wrapper">
	<div class="page-header">
		<div class="container">
			<div class="row-fluid">
				<div class="span8"></div>
				<div class="span4">
					<div class="project-share-container">

						<button id="project-share-share" class="project-share-button hneue-bold">
							<div class="share-icon"></div>
							<div class="share-text">SHARE</div>
						</button>
						<button id="project-share-whistleblower" class="project-share-button hneue-bold">
							<div class="share-icon"></div>
							<div class="share-text">WHISTLEBLOWER</div>
						</button>
						<button id="project-share-bookmark" class="project-share-button hneue-bold">
							<div class="share-icon"></div>
							<div class="share-text">BOOKMARK</div>
						</button>

					</div>
				</div>
			</div>

		</div>
	</div>

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
				'order'           => 'ASC',
				'post_type'       => 'faq-item',
				);

			 	$row1_posts_array = get_posts( $args );

 				foreach( $row1_posts_array as $post ) :	setup_postdata($post); ?>

					<div class="drop-shadow postit faq-item">
						<div class="postit-title hneue-light"><?php the_title(); ?></div>
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
				'order'           => 'ASC',
				'post_type'       => 'faq-item',
				);

			 	$row1_posts_array = get_posts( $args );

 				foreach( $row1_posts_array as $post ) :	setup_postdata($post); ?>

					<div class="drop-shadow postit faq-item">
						<div class="postit-title hneue-light"><?php the_title(); ?></div>
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
				'order'           => 'ASC',
				'post_type'       => 'faq-item',
				);

			 	$row1_posts_array = get_posts( $args );

 				foreach( $row1_posts_array as $post ) :	setup_postdata($post); ?>

					<div class="drop-shadow postit faq-item">
						<div class="postit-title hneue-light"><?php the_title(); ?></div>
						<div class="postit-text"><?php the_content(); ?></div>
					</div>

				<?php endforeach; ?>
				</div>
			</div>

		</div>
	</div>
</div>

<?php get_footer(); ?>