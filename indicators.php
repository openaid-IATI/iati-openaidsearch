<?php
/*
Template Name: Indicators page
*/
?>

<?php get_header(); ?>

<?php get_template_part( "indicator", "filters" ); ?>
<?php get_template_part( "indicator", "map" ); ?>

<div id="page-wrapper">
	<div class="page-header">
		<div class="container">
			<div class="row-fluid">
				<div class="span8">

					<div class="project-share-container share-left">

						<a href="#" id="project-share-graph" class="project-share-button hneue-bold">
							<div class="share-icon"></div>
							<div class="share-text">TYPE GRAPH</div>
							<div id="dropdown-type-graph" class="dropdown-menu-page-header">
								<button id="dropdown-line-graph">LINE GRAPH</button>
								<button id="dropdown-table-graph">TABLE GRAPH</button>
							</div>
						</a>

						<a href="#" id="project-share-export" class="project-share-button hneue-bold">
							<div class="share-icon"></div>
							<div class="share-text">EXPORT</div>
							<div id="dropdown-export-indicator" class="dropdown-menu-page-header">
								<button id="dropdown-png">MAP TO IMAGE</button>
								<button id="dropdown-csv">DATA TO CSV</button>
							</div>
						</a>
						<a href="#" id="project-share-embed" class="project-share-button hneue-bold">
							<div class="share-icon"></div>
							<div class="share-text">EMBED</div>
						</a>
					</div>

				</div>
				<?php get_template_part("global", "page-navbar-right"); ?>
			</div>

		</div>
	</div>

	<div class="container">
		<div class="row-fluid">
			<div id="charts-column-1" class="span7">
				<div id="line-chart-filter"></div>
				<div id="line-chart-placeholder"></div>
			</div>
			<div id="charts-column-2" class="span5">
				<div id="table-chart-filter"></div>
				<div id="table-chart-placeholder"></div>
			</div>
		</div>
	</div>
</div>


<?php get_footer(); ?>

<script type="text/javascript">
$(document).ready(function(){
  selected_type = "indicator";
  query_string_to_selection();
  if (typeof current_selection.indicators === "undefined"){
  	current_selection.indicators = [];
  	current_selection.indicators.push({"id":"population", "name":"Total population"});
  }
  reload_map();
  initialize_filters();
  fill_selection_box();
  initialize_charts();
});
</script>
