
<script>
var search_url = '<?php echo OIPA_URL; ?>';
var home_url = "<?php echo bloginfo("url"); ?>";
var site_url = home_url;
var template_directory = "<?php echo bloginfo("template_url"); ?>";
var site_title = "<?php echo wp_title(''); ?>";
var standard_basemap = "zimmerman2014.hmpkg505";
var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
var ajax_path = "/wp-admin/admin-ajax.php?action=refresh_elements";
var theme_path = "<?php echo get_stylesheet_directory_uri(); ?>";
</script>

<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.2/jquery-ui.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/dependencies/jquery.bootpag.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/dependencies/country_polygons.js"></script>

<script src="<?php echo get_template_directory_uri(); ?>/js/oipa.selection.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/oipa.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/oipa.selectionbox.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/oipa.filters.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/oipa.map.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/oipa.list.js"></script>

<script src="<?php echo get_template_directory_uri(); ?>/js/scripts.js"></script>
<script src='https://api.tiles.mapbox.com/mapbox.js/v2.1.5/mapbox.js'></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

<script>var switchTo5x=true;</script>
<script src="http://w.sharethis.com/button/buttons.js"></script>
<script>

stLight.options({
	publisher: "6315865b-353c-419f-8f1d-2ef900de2fd0",
	onhover: false
});

</script>