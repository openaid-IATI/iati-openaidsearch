<!DOCTYPE html>
<html>
<head>
    <title>Unhabitat main</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->

    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" type="image/vnd.microsoft.icon"/>
    <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" type="image/x-ico"/>

    <link href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/leaflet.css" />
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>
    /css/leaflet.ie.css" />
    <![endif]-->

    <link href="<?php echo get_template_directory_uri(); ?>/style.css" rel="stylesheet" media="screen">

</head>
<body>

    <div class="navbar navbar-static-top">
        <div class="navbar-inner">
            <div class="container">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <a href="<?php echo site_url(); ?>" class="brand hneue-bold">
                    <div id="brand-image"></div>
                    <div id="brand-beta">BETA VERSION</div>
                </a>

                <div class="nav-collapse collapse">
                <ul class="nav">
                    <li <?php if (is_home()) {echo 'class="active"';} ?>><a class="navbar-home-link" href="<?php echo site_url(); ?>"><div id="header-home-img"></div></a></li>
                    <li <?php if (is_page('projects')) {echo 'class="active"';} ?>><a href="<?php echo site_url(); ?>/projects/">Projects</a></li>
                    <li <?php if (is_page('indicators')) {echo 'class="active"';} ?>><a href="<?php echo site_url(); ?>/indicators/">Indicators</a></li>
                    <li <?php if (is_page('city-prosperity')) {echo 'class="active"';} ?>><a href="<?php echo site_url(); ?>/city-prosperity/">City Prosperity</a></li>
                    <li <?php if (is_page('faq')) {echo 'class="active"';} ?>><a href="<?php echo site_url(); ?>/faq/">FAQ</a></li>
                    <li <?php if (is_page('about')) {echo 'class="active"';} ?>><a href="<?php echo site_url(); ?>/about/">About</a></li>
                    <li <?php if (is_page('contact')) {echo 'class="active"';} ?>><a href="<?php echo site_url(); ?>/contact/">Contact</a></li>
                </ul>

                <form action='projects' method='GET' class="navbar-form pull-right">
                    <input type="text" class="input-large" placeholder="What are you looking for?" name='query'></li>
                    <button type="submit" class="btn header-btn-search hneue-bold"><img src="<?php echo get_template_directory_uri(); ?>/images/search-icon.png" alt=""/> Search</button>                   
                </form>
                </div>
                <?php        
                    //wp_nav_menu( array('menu' =>'header-menu', 'container' => 'div', 'container_class' => 'nav-collapse collapse','menu_class' => 'menu','items_wrap' => '<ul class="nav pull-left">%3$s</ul>') ); 
                ?>

            </div>
        </div>
    </div>