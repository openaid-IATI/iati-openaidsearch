<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
define( 'SEARCH_URL', 'http://www.oipa.nl/api/v3/');
if (function_exists("site_url")){
	define( 'SITE_URL', site_url());
}
define( 'EMPTY_LABEL', 'No information available');
define( 'AJAX_PROJECTS_URL', TEMPLATEPATH .'/functions-projects.php');

$_DEFAULT_ORGANISATION_ID = 'NL-1';
$_PER_PAGE = 5;
define( 'DEFAULT_ORGANISATION_ID', $_DEFAULT_ORGANISATION_ID);
?>