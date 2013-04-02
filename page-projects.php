<?php
/*
Template Name: Projects page
*/
?>

<?php wp_generate_results_v2($objects, $meta); ?>

<?php get_header(); ?>
<?php get_template_part( "project", "filters" ); ?>
<?php get_template_part( "map" ); ?>

<div id="page-wrapper">

	<div class="page-header page-header-less-margin">
		<div class="container">
			<div class="row-fluid projects-search-navbar">
				<div class="span7">
					<div class="projects-pagination-info hneue-bold">
						<div class="pagination-totals">
							RESULTS <?php echo $meta->offset + 1; ?> - <?php if(($meta->offset + $meta->limit)>$meta->total_count) { echo $meta->total_count; } else { echo ($meta->offset + $meta->limit); } ?> OF <?php echo $meta->total_count; ?>
						</div>
						<div class="projects-sort-type">
							<a class="sort-type sort-type-amount" href="#">
								SHOW <?php echo $meta->limit; ?>
								<span class="sort-icon"></span>
							</a>
						</div>
						<a id="save-search-results" href="#">SAVE SEARCH RESULTS</a>

					</div>
				</div>
				<div class="span5">
					<div class="projects-sorting">
						<div class="projects-sort-text hneue-bold">SORT BY:</div>
						<div class="projects-sort-type hneue-bold">
							<a class="sort-type sort-type-budget" href="#">
								BUDGET
								<span class="sort-icon"></span>
							</a>
						</div>
						<div class="projects-sort-type hneue-bold">
							<a class="sort-type sort-type-startdate" href="#">
								START DATE
								<span class="sort-icon"></span>
							</a>
						</div>
						<div class="projects-sort-type hneue-bold">
							<a class="sort-type sort-type-country" href="#">
								COUNTRY
								<span class="sort-icon"></span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="page-content">
		
		

			<?php get_template_part( "projects", "description" ); ?>
		


		<div class="container">
		<div class="row-fluid">
			<div class="span12">
				<div id="pagination">
					<?php wp_generate_paging($meta); ?>
				</div>
			</div>
		</div>



		</div>
	</div>
</div>

<?php get_footer(); ?>