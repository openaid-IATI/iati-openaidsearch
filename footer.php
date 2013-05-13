
<div class="footer">
	<div class="container">

		<div class="row-fluid footer-firstrow">
			<div class="span8">

				<div class="navbar">
					<?php 
                        wp_nav_menu( array('menu' => 'footer-menu', 'container' => 'div', 'container_class' => 'nav-inner','menu_class' => 'menu','items_wrap' => '<ul class="nav">%3$s</ul>') ); 
                    ?>
				</div>

			</div>
			<div class="span4">

				<div id="footer-social-media-wrapper">

					<a class="footer-social-media-icon footer-facebook" href="https://www.facebook.com/pages/UN-HABITAT/127012777443"></a>
					<a class="footer-social-media-icon footer-flickr" href="http://www.flickr.com/photos/66729176@N02/"></a>
					<a class="footer-social-media-icon footer-twitter" href="https://twitter.com/#!/unhabitat"></a>
					<a class="footer-social-media-icon footer-youtube" href="http://www.youtube.com/user/epitunhabitat"></a>
					<a class="footer-social-media-icon footer-scribd" href="http://www.scribd.com/UN-HABITAT"></a>

				</div>

			</div>
		</div>
		<div class="row-fluid footer-info">
			<div class="span12">
				<div class="footer-info-text">
					Open UN-Habitat Transparency Initiative.
					<a href="http://www.unhabitat.org">Back to main website of UN-Habitat</a>
					<br/>
					Content licensed under a Creative Commons Attribution 3.0 Unported License
				</div>
			</div>
		</div>

	</div>
</div>

<script type="text/javascript">
var site = '<?php echo SITE_URL; ?>';
var home_url = "<?php echo bloginfo("url"); ?>";
var template_directory = "<?php echo bloginfo("template_url"); ?>";
<?php
global $_DEFAULT_ORGANISATION_ID;
echo 'var organisation_id = "' . $_DEFAULT_ORGANISATION_ID . '"';
?>

</script>

<script src="<?php echo get_template_directory_uri(); ?>/js/dependencies/jquery.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/dependencies/jquery-ui.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/dependencies/leaflet.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/dependencies/bootstrap.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/map.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/main.js"></script>

<?php 

if (is_page('indicators') or is_page('city-prosperity')) { ?>

	<script src="<?php echo get_template_directory_uri(); ?>/js/dependencies/jquery.nouislider.min.js"></script>

	<script src="https://www.google.com/jsapi"></script>

	<script type="text/javascript" src="http://canvg.googlecode.com/svn/trunk/rgbcolor.js"></script>
    <script type="text/javascript" src="http://canvg.googlecode.com/svn/trunk/canvg.js"></script>
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

<script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">

stLight.options({
	publisher: "6315865b-353c-419f-8f1d-2ef900de2fd0",
	onhover: false
});

</script>

</body>
</html>