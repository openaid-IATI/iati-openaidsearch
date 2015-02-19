<?php
/*
Template Name: Projects page
*/

get_header();

include( TEMPLATEPATH .'/projects-filters.php' );
include( TEMPLATEPATH .'/map.php' ); 
include( TEMPLATEPATH .'/projects-list.php' ); ?>

<div id="paginated-loader">
    <div id="paginated-text">Loading projects</div>
    <img src="<?php echo get_template_directory_uri(); ?>/images/ajax-loader.gif" alt="" />
</div>

<?php get_template_part('footer-scripts'); ?>
<script>

	var projectlist = null;
	var filters = null;

    $(document).ready(function() {

	    Oipa.pageType = "activities";

	    var selection = new OipaSelection(1, 1);
	    Oipa.mainSelection = selection;

    	filters = new OipaFilters();
    	filters.selection = Oipa.mainSelection;
    	filters.init();
    	Oipa.mainFilter = filters;

    	projectlist = new OipaProjectList();
	    projectlist.list_div = "#project-list-wrapper";
	    projectlist.pagination_div = "#project-list-pagination";
	    projectlist.activity_count_div = "#page-total-count";
	    projectlist.per_page = 25;
	    projectlist.selection = Oipa.mainSelection;
	    Oipa.lists.push(projectlist);
	    projectlist.init();

	    var map = new OipaMap();
	    map.set_map("map", "topright");
	    map.selection = Oipa.mainSelection;
	    map.selection.group_by = "country";
	    Oipa.maps.push(map);
	    map.refresh();

    });
</script>
<?php get_footer();