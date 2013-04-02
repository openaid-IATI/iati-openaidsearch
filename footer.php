






<div class="footer">
	<div class="container">

		<div class="row-fluid footer-firstrow">
			<div class="span8">

				<div class="navbar">
					<?php
                        
                        wp_nav_menu( array('menu' =>
					'footer-menu', 'container' => 'div', 'container_class' => 'nav-inner','menu_class' => 'menu','items_wrap' => '
					<ul class="nav">%3$s</ul>
					') ); 
                        ?>
				</div>

			</div>
			<div class="span4">

				<div id="footer-social-media-wrapper">

					<a class="footer-social-media-icon footer-facebook" href="https://www.facebook.com/pages/UN-HABITAT/127012777443" alt=""></a>
					<a class="footer-social-media-icon footer-flickr" href="http://www.flickr.com/photos/66729176@N02/" alt=""></a>
					<a class="footer-social-media-icon footer-twitter" href="https://twitter.com/#!/unhabitat" alt=""></a>
					<a class="footer-social-media-icon footer-youtube" href="http://www.youtube.com/user/epitunhabitat" alt=""></a>
					<a class="footer-social-media-icon footer-scribd" href="http://www.scribd.com/UN-HABITAT" alt=""></a>

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

<script src="http://code.jquery.com/jquery.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/leaflet.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/countries.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/countries_loc.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/unhabitatmap.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/unhabitat.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/jsonpath-0.8.0.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/countries_isos.js"></script>

<?php if (is_page('indicators') or is_page('city-prosperity')) {
	echo '<script src="https://www.google.com/jsapi"></script>';
	echo '<script src="' . get_template_directory_uri() . '/js/jquery-ui.js"></script>';
	echo '<script src="' . get_template_directory_uri() . '/js/indicators.js"></script>';
} ?>

<?php if (is_page('project')) {echo '<script src="' . get_template_directory_uri() . '/js/map_project_page.js"></script>';} ?>


<!--<script src="<?php echo get_template_directory_uri(); ?>/js/script.js"></script>-->

</body>
</html>