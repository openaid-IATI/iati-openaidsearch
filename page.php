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