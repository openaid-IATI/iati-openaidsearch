
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
					<div id="footer-powered-by">Powered by</div>
					<a href="http://www.akvo.org"><img src="<?php echo get_template_directory_uri(); ?>/images/logo-akvo.png" width="145" height="34" alt=""/></a>
					<a href="http://www.zimmermanzimmerman.nl"><img src="<?php echo get_template_directory_uri(); ?>/images/logo-zimmerman.png" width="136" height="32" alt=""/></a>
				</div>
			</div>
		</div>

	</div>
</div>


</body>
</html>