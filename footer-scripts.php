
<script>
var site = '<?php echo SITE_URL; ?>';
var home_url = "<?php echo bloginfo("url"); ?>";
var template_directory = "<?php echo bloginfo("template_url"); ?>";
var site_title = "<?php echo wp_title(''); ?>";
<?php
global $_DEFAULT_ORGANISATION_ID;
echo 'var organisation_id = "' . $_DEFAULT_ORGANISATION_ID . '";';
?>

</script>

<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/dependencies/jquery-ui.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/dependencies/leaflet.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/dependencies/bootstrap.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/map.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/main.js"></script>

<?php 

if (is_page('indicators') or is_page('city-prosperity')) { ?>

	<script src="<?php echo get_template_directory_uri(); ?>/js/dependencies/jquery.nouislider.min.js"></script>

	<script src="https://www.google.com/jsapi"></script>

	<script src="http://canvg.googlecode.com/svn/trunk/rgbcolor.js"></script>
    <script src="http://canvg.googlecode.com/svn/trunk/canvg.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/dependencies/html2canvas.min.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/dependencies/jquery.plugin.html2canvas.js"></script>

	<script src="<?php echo get_template_directory_uri(); ?>/js/indicators.js"></script>
	<?php
}
if (is_page('project')) {
	echo '<script src="' . get_template_directory_uri() . '/js/dependencies/countries.js"></script>';
	echo '<script src="' . get_template_directory_uri() . '/js/project.js"></script>';

}
if (!is_page('indicators') and !is_page('city-prosperity')) {
	echo '<script src="' . get_template_directory_uri() . '/js/projects.js"></script>';
}

?>

<script>var switchTo5x=true;</script>
<script src="http://w.sharethis.com/button/buttons.js"></script>
<script>

stLight.options({
	publisher: "6315865b-353c-419f-8f1d-2ef900de2fd0",
	onhover: false
});

</script>