
<script>
var site = '<?php echo SITE_URL; ?>';
var search_url = '<?php echo SEARCH_URL; ?>';
var ajax_projects_url = '<?php echo AJAX_PROJECTS_URL; ?>';
var home_url = "<?php echo bloginfo("url"); ?>";
var template_directory = "<?php echo bloginfo("template_url"); ?>";
var site_title = "<?php echo wp_title(''); ?>";
<?php
global $_DEFAULT_ORGANISATION_ID;
echo 'var organisation_id = "' . $_DEFAULT_ORGANISATION_ID . '";';
?>

</script>

<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
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
if (is_page('project') or is_page('explore')) {
	echo '<script src="' . get_template_directory_uri() . '/js/dependencies/countries.js"></script>';
	echo '<script src="' . get_template_directory_uri() . '/js/project.js"></script>';
	?>
	<?php
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



<!-- Piwik -->
<script type="text/javascript">
var _paq = _paq || [];
_paq.push(["setDocumentTitle", document.domain + "/" + document.title]);
_paq.push(["setCookieDomain", "*.openaid.nl"]);
_paq.push(["trackPageView"]);
_paq.push(["enableLinkTracking"]);

(function() {
var u=(("https:" == document.location.protocol) ? "https" : "http") + "://analytics.akvo.org/";
_paq.push(["setTrackerUrl", u+"piwik.php"]);
_paq.push(["setSiteId", "12"]);
var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0]; g.type="text/javascript";
g.defer=true; g.async=true; g.src=u+"piwik.js"; s.parentNode.insertBefore(g,s);
})();
</script>
<!-- End Piwik Code -->
