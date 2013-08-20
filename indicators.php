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
								<button id="dropdown-line-graph-png">LINE GRAPH TO IMAGE</button>
							</div>
						</a>
						<a href="#" id="project-share-embed" class="project-share-button hneue-bold">
							<div class="share-icon"></div>
							<div class="share-text">EMBED</div>
							<div id="dropdown-type-embed" class="dropdown-menu-page-header">
								<button id="dropdown-embed-map">MAP</button>
								<button id="dropdown-embed-line-graph">LINE GRAPH</button>
							</div>
							<div id="dropdown-embed-url" class="dropdown-menu-page-header">
								Code to embed: <br>
								<input type="text" name="embed-url" value="">
								<div id="project-share-embed-close">close</div>
							</div>
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
				<h2 id="line-chart-name1"></h2>
				<div id="line-chart-filter1"></div>
				<div id="line-chart-placeholder1"></div>

				<h2 id="line-chart-name2"></h2>
				<div id="line-chart-filter2"></div>
				<div id="line-chart-placeholder2"></div>

				<h2 id="line-chart-name3"></h2>
				<div id="line-chart-filter3"></div>
				<div id="line-chart-placeholder3"></div>

				<h2 id="line-chart-name4"></h2>
				<div id="line-chart-filter4"></div>
				<div id="line-chart-placeholder4"></div>

				<h2 id="line-chart-name5"></h2>
				<div id="line-chart-filter5"></div>
				<div id="line-chart-placeholder5"></div>
			</div>
			<div id="charts-column-2" class="span5">
				<h2>Selection data by year <span></span></h2>
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

  if (current_selection.indicators === undefined){
  	current_selection.indicators = [];
  	current_selection.indicators.push({"id":"population", "name":"Total population"});
  }
  reload_map();
  initialize_filters(fill_selection_box, initialize_charts);
});
</script>
