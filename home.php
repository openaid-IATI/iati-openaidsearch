<?php get_header(); ?>
<?php get_template_part( "map" ); ?>



<div id="page-wrapper">
	<div class="container">
		<div class="page-content">
			<div class="row postit-page home-page">
				<div class="col-md-4">
				<?php 

				$cat_obj = get_category_by_slug('row-1'); 
				$row_cat_id = $cat_obj->term_id;

				$args = array(
				'posts_per_page'  => 15,
				'numberposts'     => 15,
				'category'        => $row_cat_id,
				'order'           => 'ASC',
				'post_type'       => 'homepage-item',
				);

			 	$row1_posts_array = get_posts( $args );

 				foreach( $row1_posts_array as $post ) :	setup_postdata($post); ?>
 				<a class="homepage-link" href="<?php echo bloginfo("url"); ?>/projects/">
					<div class="drop-shadow postit">
						<div class="postit-title hneue-light"><?php the_title(); ?></div>
						<div class="postit-text"><?php the_content(); ?></div>
						<div class="postit-thumb"><?php the_post_thumbnail(); ?></div>
					</div>
				</a>
				<?php endforeach; ?>

				

				</div>
				<div class="col-md-4">
							<?php 

				$cat_obj = get_category_by_slug('row-2'); 
				$row_cat_id = $cat_obj->term_id;

				$args = array(
				'posts_per_page'  => 15,
				'numberposts'     => 15,
				'category'        => $row_cat_id,
				'order'           => 'ASC',
				'post_type'       => 'homepage-item',
				);

			 	$row1_posts_array = get_posts( $args );

 				foreach( $row1_posts_array as $post ) :	setup_postdata($post); ?>
 				<a class="homepage-link" href="<?php echo bloginfo("url"); ?>/indicators/">
					<div class="drop-shadow postit">
						<div class="postit-title hneue-light"><?php the_title(); ?></div>
						<div class="postit-text"><?php the_content(); ?></div>
						<div class="postit-thumb"><?php the_post_thumbnail(); ?></div>
					</div>
				</a>
				<?php endforeach; ?>
				</div>
				<div class="col-md-4">
							<?php 

				$cat_obj = get_category_by_slug('row-3'); 
				$row_cat_id = $cat_obj->term_id;

				$args = array(
				'posts_per_page'  => 15,
				'numberposts'     => 15,
				'category'        => $row_cat_id,
				'order'           => 'ASC',
				'post_type'       => 'homepage-item',
				);

			 	$row1_posts_array = get_posts( $args );

 				foreach( $row1_posts_array as $post ) :	setup_postdata($post); ?>
 				<a class="homepage-link" href="<?php echo bloginfo("url"); ?>/city-prosperity/">
					<div class="drop-shadow postit">
						<div class="postit-title hneue-light"><?php the_title(); ?></div>
						<div class="postit-text"><?php the_content(); ?></div>
						<div class="postit-thumb"><?php the_post_thumbnail(); ?></div>
					</div>
				</a>
				<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_template_part('footer-scripts'); ?>

<script>

    $(document).ready(function() {

	    Oipa.pageType = "activities";

	    var selection = new OipaSelection(1, 1);
	    Oipa.mainSelection = selection;

    	var filters = new OipaFilters();
    	filters.init();
    	Oipa.mainFilter = filters;

	    var map = new OipaMap();
	    map.set_map("map", "topright");
	    map.selection = Oipa.mainSelection;
	    map.selection.group_by = "country";
	    Oipa.maps.push(map);
	    map.refresh();

    });

</script>
<?php get_footer(); 