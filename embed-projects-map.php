<?php
/*
Template Name: Embed project map page
*/
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php wp_title(''); ?></title>
   

    <!-- Bootstrap -->

    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" type="image/vnd.microsoft.icon"/>
    <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" type="image/x-ico"/>

    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/leaflet.css" />

    <link href="<?php echo get_template_directory_uri(); ?>/style.css" rel="stylesheet" media="screen">

    <style>
    	body {margin:0; background-color: transparent;}

    </style>

    <!--[if lte IE 8]>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/leaflet.ie.css" />
     <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/ie-8-and-down.css" />
    <![endif]-->
    
    

    <?php wp_head(); ?>
</head>




<div id="map-wrapper">

<div id="map-loader">
    <div id="map-loader-text">Loading project map</div>
    <img src="<?php echo get_template_directory_uri(); ?>/images/ajax-loader.gif" alt="" />
</div>
<div id="map" style="height:290px;"></div>
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
<script src="<?php echo get_template_directory_uri(); ?>/js/projects.js"></script>
</script>

<script type="text/javascript">

    $(document).ready(function() {
      selected_type = "projects";
      save_selection(true);
      map.setZoom(2);
      map.options.minZoom = 1;
    });

</script>


