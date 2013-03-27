<!DOCTYPE html>
<html>
<head>
    <title>Unhabitat main</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->

    <link href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/leaflet.css" />
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>
    /css/leaflet.ie.css" />
    <![endif]-->

    <link href="<?php echo get_template_directory_uri(); ?>/style.css" rel="stylesheet" media="screen">

    <script type="text/javascript" src="http://fast.fonts.com/jsapi/ce8c2ae2-ba23-4999-b0e8-e4b80c83ea8f.js"></script>
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

                <a href="/" class="brand">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/logo2.png" />
                </a>

                <div class="nav-collapse collapse">
                <ul class="nav">
                    <li><a href="">H</a></li>
                    <li><a href="<?php echo site_url(); ?>/projects/">Projects</a></li>
                    <li><a href="<?php echo site_url(); ?>/indicators/">Indicators</a></li>
                    <li><a href="<?php echo site_url(); ?>/city-prosperity/">City Prosperity</a></li>
                    <li><a href="<?php echo site_url(); ?>/faq/">FAQ</a></li>
                    <li><a href="<?php echo site_url(); ?>/about/">About</a></li>
                    <li><a href="<?php echo site_url(); ?>/contact/">Contact</a></li>
                </ul>

                <form class="navbar-form pull-right">
                    <input type="text" class="input-large" placeholder="What are you looking for?"></li>
                    <button type="submit" class="btn header-btn-search hneue-bold"><img src="<?php echo get_template_directory_uri(); ?>/images/search-icon.png" alt=""/> Search</button>                   
                </form>
                </div>
                <?php        
                    //wp_nav_menu( array('menu' =>'header-menu', 'container' => 'div', 'container_class' => 'nav-collapse collapse','menu_class' => 'menu','items_wrap' => '<ul class="nav pull-left">%3$s</ul>') ); 
                ?>

            </div>
        </div>
    </div>