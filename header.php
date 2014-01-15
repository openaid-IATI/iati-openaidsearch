<!DOCTYPE html>
<html>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php wp_title(''); ?></title>


    <!-- Bootstrap -->

    <!-- <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" type="image/vnd.microsoft.icon"/>-->
    <!-- <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" type="image/x-ico"/>-->

    <link href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/leaflet.css" />
    <!--[if lte IE 8]>
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/leaflet.ie.css" />
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/ie-8-and-down.css" />
    <![endif]-->

    <link href="<?php echo get_template_directory_uri(); ?>/style.css" rel="stylesheet" media="screen">

    


    <?php wp_head(); ?>
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
                                            
                        <?php 

                        $navmenargs = array(
                            'menu' => 'Header menu',
                            'container' => '',
                            'container_class' => '',
                            'menu_class' => 'nav',
                            );
                        wp_nav_menu($navmenargs);
                        ?>


                    <form action='<?php echo site_url(); ?>/projects/' method='GET' class="navbar-form pull-right">

                        <input type="text" class="input-large" placeholder="What are you looking for?" name='query'>

                        <input type="submit" class="btn header-btn-search hneue-bold" value="Search">                  

                    </form>
                </div>
                <?php        
                    //wp_nav_menu( array('menu' =>'header-menu', 'container' => 'div', 'container_class' => 'nav-collapse collapse','menu_class' => 'menu','items_wrap' => '<ul class="nav pull-left">%3$s</ul>') ); 
                ?>

            </div>
        </div>
    </div>