<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
// define( 'OIPA_URL', 'http://oipa.murrx.me/api/');
define( 'OIPA_URL', 'http://localhost:8000/api/');
if (function_exists("site_url")){
	define( 'SITE_URL', site_url());
}
define( 'EMPTY_LABEL', 'No information available');
define( 'DEFAULT_ORGANISATION_ID', '');
define( 'DEFAULT_ORGANISATION_NAME', '');
define( 'OIPA_PER_PAGE', 25);
define( 'GOOGLE_ANALYTICS_CODE', '');

include( TEMPLATEPATH . '/codelists.php' ); 