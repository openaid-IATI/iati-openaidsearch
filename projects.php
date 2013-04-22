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
                                <!-- TO DO: keep parameters -->
                                <a href="<?php echo home_url(); ?>/projects/?offset=0&per_page=5" id="dropdown-project-amount-5">5</a>
                                <a href="<?php echo home_url(); ?>/projects/?offset=0&per_page=10" id="dropdown-project-amount-10">10</a>
                                <a href="<?php echo home_url(); ?>/projects/?offset=0&per_page=25" id="dropdown-project-amount-20">25</a>
                                <a href="<?php echo home_url(); ?>/projects/?offset=0&per_page=50" id="dropdown-project-amount-20">50</a>
                            </div>

						</div>
						<a id="save-search-results" href="#save-search-results">SAVE SEARCH RESULTS</a>

					</div>
				</div>
				<div class="span5">
					<div class="projects-sorting hneue-bold">
                        <div id="sort-type-header">
                            SORT BY:
                        </div>

                        <?php
                        $params = $_GET;
                        
                        ?>
                        
                        <div id="sort-type-budget" class="project-sort-type">
                            <span class="project-sort-text hneue-bold">BUDGET</span>
                            <span class="project-sort-icon"></span>
                            <div id="dropdown-project-budget" class="dropdown-project">
                                <?php $params['order_by'] = "statistics__total_budget"; ?>
                                <a href="?<?php echo http_build_query($params); ?>" id="dropdown-project-budget-asc">ASCENDING</a>
                                <?php $params['order_by'] = "-statistics__total_budget"; ?>
                                <a href="?<?php echo http_build_query($params); ?>" id="dropdown-project-budget-desc">DESCENDING</a>
                            </div>
                        </div>

                        

                        <div id="sort-type-startdate" class="project-sort-type">
                            <span class="project-sort-text hneue-bold">START DATE</span>
                            <span class="project-sort-icon"></span>
                            <div id="dropdown-project-startdate" class="dropdown-project">
                                <?php $params['order_by'] = "start_planned"; ?>
                                <a href="?<?php echo http_build_query($params); ?>" id="dropdown-project-startdate-asc">ASCENDING</a>
                                <?php $params['order_by'] = "-start_planned"; ?>
                                <a href="?<?php echo http_build_query($params); ?>" id="dropdown-project-startdate-desc">DESCENDING</a>
                            </div>
                        </div>

                        <?php
                        $params['order_by'] = "country";
                        ?>
                        <div id="sort-type-country" class="project-sort-type">
                            <span class="project-sort-text hneue-bold">COUNTRY</span>
                            <span class="project-sort-icon"></span>
                            <div id="dropdown-project-country" class="dropdown-project">
                                <a href="?<?php echo http_build_query($params); ?>" id="dropdown-project-country-asc">ASCENDING</a>
                                <a href="?<?php echo http_build_query($params); ?>" id="dropdown-project-country-desc">DESCENDING</a>
                            </div>
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
<div id="paginated-loader">
    <div id="paginated-text">Loading projects</div>
    <img src="<?php echo get_template_directory_uri(); ?>/images/ajax-loader.gif" alt="" />
</div>

<?php get_footer(); ?>

<script type="text/javascript">

    $(document).ready(function() {
        initialize_projects_map('http://dev.oipa.openaidsearch.org/json-activities',"projects");
    });

</script>


