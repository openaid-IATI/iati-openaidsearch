<?php
/*
Template Name: City prosperity page
*/
?>

<?php get_header(); ?>

<?php get_template_part( "city-prosperity", "filters" ); ?>
<?php get_template_part( "indicator", "map" ); ?>

<div id="page-wrapper">
	<div class="page-header page-header-less-margin">
		<div class="container">
			<div class="row-fluid">
				<div class="span8">
					<div class="project-share-container share-left">

					<button id="project-share-export" class="project-share-button hneue-bold">
						<div class="share-icon"></div>
						<div class="share-text">EXPORT</div>
					</button>
					<button id="project-share-embed" class="project-share-button hneue-bold">
						<div class="share-icon"></div>
						<div class="share-text">EMBED</div>
					</button>
				</div>

				</div>
				<div class="span4">
					<div class="project-share-container share-right">

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
		<div class="row-fluid">
			<div class="span8">

				<div class="drop-shadow unhabitat-page">

					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<?php the_content(); ?>
					<?php endwhile; endif; ?></div>

			</div>
			<div class="span4">
				<?php get_sidebar( 'unhabitat-pages' ); ?></div>
		</div>
		<div class="row-fluid">
			<div class="span12">



				<script type='text/javascript' src='https://www.google.com/jsapi'></script>
			    <script type='text/javascript'>
			      google.load('visualization', '1', {packages:['table']});
			      google.setOnLoadCallback(drawTable);
			      function drawTable() {
			        var data = new google.visualization.DataTable();
			        data.addColumn('string', 'Name');
			        data.addColumn('number', 'Salary');
			        data.addColumn('boolean', 'Full Time Employee');
			        data.addRows([
			          ['Mike',  {v: 10000, f: '$10,000'}, true],
			          ['Jim',   {v:8000,   f: '$8,000'},  false],
			          ['Alice', {v: 12500, f: '$12,500'}, true],
			          ['Bob',   {v: 7000,  f: '$7,000'},  true]
			        ]);

			        var table = new google.visualization.Table(document.getElementById('table_div'));
			        table.draw(data, {showRowNumber: true});
			      }
			    </script>

				<div id='table_div'></div>








			</div>
		</div>

	</div>
</div>

<?php get_footer(); ?>