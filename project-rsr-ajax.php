<?php 
$rsr_loaded = true;
wp_generate_rsr_projects($objects, $iati_id);
include( TEMPLATEPATH . '/project-rsr.php' );
?>