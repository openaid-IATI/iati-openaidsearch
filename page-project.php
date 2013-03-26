<?php
/*
Template Name: Single project page
*/
?>

<?php get_header(); ?>

<?php get_template_part( "project", "filters" ); ?>
<?php get_template_part( "map" ); ?>

<div class="page-wrapper">
	<div class="container">
		<div class="page-content">

			<div class="row-fluid">
				<div class="span12">
					<button class="project-back-button">BACK TO SEARCH RESULTS</button>
					
				</div>
			</div>

			<div class="row-fluid">
				<div class="span12 project-navbar">
					    <ul class="nav nav-pills">
					    <li class="active"><a id="project-description-link" href="#project-description">Description</a></li>
					    <li><a id="project-commitments-link" href="#project-commitments">Commitments</a></li>
					    <li><a id="project-documents-link" href="#project-documents">Documents</a></li>
					    <li><a id="project-related-projects-link" href="#project-related-projects">Related projects</a></li>
					    <li><a id="project-related-indicator-link" href="#project-related-indicator">Related indicators</a></li>
					    </ul>
				</div>
			</div>

			<?php get_template_part( 'project', 'description' ); ?>
			<?php get_template_part( 'project', 'commitments' ); ?>
			<?php get_template_part( 'project', 'documents' ); ?>
			<?php get_template_part( 'project', 'realted-indicators' ); ?>
			<?php get_template_part( 'project', 'related-projects' ); ?>





		</div>
	</div>
</div>

<?php get_footer(); ?>