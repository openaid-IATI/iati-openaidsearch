		<div class="footer">
			<div class="container">
				<div class="row footer-firstrow">
					<div class="col-md-8">
						<div class="navbar">
							<?php 
	                        
	                        wp_nav_menu( array(
		                        'theme_location'    => 'header-menu',
		                        'menu'              => '',
		                        'depth'             => 2,
		                        'container'         => 'div',
		                        'container_class'   => 'collapse navbar-collapse',
		                        'container_id'      => 'bs-example-navbar-collapse-1',
		                        'menu_class'        => 'nav navbar-nav',
		                        'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
		                        'walker'            => new wp_bootstrap_navwalker())
		                    );

		                    ?>
						</div>
					</div>
				</div>
				<div class="row footer-info">
					<div class="col-md-12">
						<div class="footer-info-text">
							<?php dynamic_sidebar( "footer-sidebar" ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>