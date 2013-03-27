<?php
/*
Template Name: Projects page
*/
?>

<?php get_header(); ?>
<?php get_template_part( "project", "filters" ); ?>
<?php get_template_part( "map" ); ?>

<div class="page-wrapper">
	<div class="container">
		<div class="page-content">

			<div class="row-fluid projects-search-navbar">
				<div class="span7">
					<div class="projects-pagination-info hneue-bold">RESULTS 1 - 5 OF 114</div>
					
				</div>
				<div class="span5">
					<div class="projects-sorting">
						<div class="projects-sort-text hneue-bold">SORT BY:</div>
						<div class="projects-sort-type"><a class="sort-type-budget" href="#">Budget</a><a id="sort-by-budget" class="sort-desc" href="#"></a></div>
						<div class="projects-sort-type"><a class="sort-type-startdate" href="#">Start date</a><a id="sort-by-startdate" class="sort-desc" href="#"></a></div>
						<div class="projects-sort-type"><a class="sort-type-country" href="#">Country</a><a id="sort-by-country" class="sort-desc" href="#"></a></div>
					</div>
				</div>
			</div>

		
			<?php get_template_part( "projects", "description" ); ?>
		
		</div>
	</div>
</div>

<?php get_footer(); ?>