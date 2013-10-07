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

    <?php if ($meta->total_count > 0){ ?>
	<div class="page-header">
		<div class="container">
			<div id="projects-search-navbar" class="row-fluid">
				<div class="span7">
					<div class="projects-pagination-info hneue-bold">
						<div id="pagination-totals">
							RESULTS <?php echo $meta->offset + 1; ?> - <?php if(($meta->offset + $meta->limit)>$meta->total_count) { echo $meta->total_count; } else { echo ($meta->offset + $meta->limit); } ?> OF <?php echo $meta->total_count; ?>
						</div>

						<div id="sort-type-amount" class="project-sort-type">
                            <span class="project-sort-text hneue-bold">SHOW <?php echo $meta->limit; ?></span>
                            <span class="project-sort-icon"></span>

                            <div id="dropdown-project-amount" class="dropdown-project">
                                <a href="?per_page=5" class="dropdown-project-amount-link">5</a>
                                <a href="?per_page=10" class="dropdown-project-amount-link">10</a>
                                <a href="?per_page=25" class="dropdown-project-amount-link">25</a>
                                <a href="?per_page=50" class="dropdown-project-amount-link">50</a>
                            </div>

						</div>
						<a id="save-search-results" href="#save-search-results">EXPORT SEARCH RESULTS</a>
                        <a href="#" id="project-share-embed" class="project-page project-share-button hneue-bold">
                            <div class="share-icon"></div>
                            <div class="share-text">EMBED MAP</div>
                            <div id="dropdown-embed-url" class="dropdown-menu-page-header">
                                Code to embed: <br>
                                <input type="text" name="embed-url" value="">
                                <div id="project-share-embed-close">close</div>
                            </div>
                        </a>
					</div>
				</div>
				<div class="span5">
					<div class="projects-sorting hneue-bold">
                        <div id="sort-type-header">
                            SORT BY:
                        </div>

                        <div id="sort-type-budget" class="project-sort-type">
                            <span class="project-sort-text hneue-bold">BUDGET</span>
                            <span class="project-sort-icon"></span>
                            <div id="dropdown-project-budget" class="dropdown-project">
                                <a href="?order_by=statistics__total_budget" class="project-sort-item">ASCENDING</a>
                                <a href="?order_by=-statistics__total_budget" class="project-sort-item">DESCENDING</a>
                            </div>
                        </div>

                        <div id="sort-type-startdate" class="project-sort-type">
                            <span class="project-sort-text hneue-bold">START DATE</span>
                            <span class="project-sort-icon"></span>
                            <div id="dropdown-project-startdate" class="dropdown-project">
                                <a href="?order_by=start_planned" class="project-sort-item">ASCENDING</a>
                                <a href="?order_by=-start_planned" class="project-sort-item">DESCENDING</a>
                            </div>
                        </div>

					</div>
				</div>
			</div>
		</div>
	</div>
    <?php } // close if projects > 0 ?>


	<div class="page-content">
		
		
		<?php get_template_part( "projects", "description" ); ?>

        <?php if ($meta->total_count > 0){ ?>
		<div class="container">
		<div class="row-fluid">
			<div class="span12">
				<div id="pagination">
					<?php wp_generate_paging($meta); ?>
				</div>
			</div>
		</div>
        <?php } else {

            echo '<div id="no-projects-found">Your selection didn\'t find any matches.</div>';

        } ?>


		</div>
	</div>
</div>
<div id="paginated-loader">
    <div id="paginated-text">Loading projects</div>
    <img src="<?php echo get_template_directory_uri(); ?>/images/ajax-loader.gif" alt="" />
</div>

<?php get_template_part('footer-scripts'); ?>
<script>
    $(document).ready(function() {
      selected_type = "projects";
      query_string_to_selection();
      reload_map();
      initialize_project_filter_options(fill_selection_box);

    });
</script>
<?php get_footer(); ?>



