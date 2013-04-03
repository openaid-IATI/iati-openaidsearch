<?php
/*
Template Name: Indicators page
*/
?>

<?php get_header(); ?>

<?php get_template_part( "indicator", "filters" ); ?>
<?php get_template_part( "map-indicator-page" ); ?>

<div id="page-wrapper">
	<div class="page-header page-header-less-margin">
		<div class="container">
			<div class="row-fluid">
				<div class="span8">

					<div class="project-share-container share-left">

						<a href="#" id="project-share-graph" class="project-share-button hneue-bold">
							<div class="share-icon"></div>
							<div class="share-text">TYPE GRAPH</div>
							<div id="dropdown-type-graph">
								<button id="dropdown-line-graph">LINE GRAPH</button>
								<button id="dropdown-table-graph">TABLE GRAPH</button>
							</div>
						</a>
						

						<a id="project-share-export" class="project-share-button hneue-bold">
							<div class="share-icon"></div>
							<div class="share-text">EXPORT</div>
						</a>
						<a id="project-share-embed" class="project-share-button hneue-bold">
							<div class="share-icon"></div>
							<div class="share-text">EMBED</div>
						</a>
					</div>

				</div>
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
				<div class="span7">
					<div id="line-chart-placeholder"></div>
					<div id="table-chart-placeholder"></div>
				</div>
				<div class="span4">
					<div class="drop-shadow postit indicator-postit">

						<div class="postit-title">Information placeholder</div>
						<div class="postit-text">
							Maecenas sed diam eget risus varius blandit sit amet non magna. Aenean lacinia bibendum nulla sed consectetur. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Maecenas faucibus mollis interdum.
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>



<?php get_footer(); ?>