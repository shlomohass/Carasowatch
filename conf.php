<?php
/**************************** Headers and ini *********************************/
header('Content-Type: text/html; charset=UTF-8'); 
error_reporting(-1); // -1 all, 0 don't
ini_set('display_errors', 'on');
date_default_timezone_set('Asia/Jerusalem');

/**************************** set gloable path hooks **************************/

define('DS', DIRECTORY_SEPARATOR);
define( 'PATH_BASE',            "Carasowatch".DS);
define( 'PATH_CLASSES',         "classes".DS);
define( 'PATH_LANG',            "lang".DS);
define( 'PATH_PAGES',           "pages".DS);
define( 'PATH_STRUCT',          "pages".DS."struct".DS);
define( 'GPATH_TPL',            "pages/templates/");
define( 'PATH_LIB_STYLE',       "lib".DS."css".DS);
define( 'GPATH_LIB_STYLE',      "lib/css/" );
define( 'PATH_LIB_JS',          "lib".DS."js".DS);
define( 'GPATH_LIB_JS',         "lib/js/");

/************************** System Configuration ******************************/

$conf = array(
    'host'           => 'localhost',
    'port'           => '3306',
    'dbname'         => 'carasowatchdb',
    'dbuser'         => 'root',
    'dbpass'         => 'sh4hs1sh4hs1',
    'storage_folder' => "C:\\Scraperout\\"
);

define( 'SEND_DB_ERRORS',   false );
define( 'SEND_ERRORS_TO',   'shlomohassid@gmail.com' );
define( 'LOG_DB_ERRORS',    true );
define( 'LOG_DB_TO_TABLE',  'db_error_log' );

define( 'LOG_BAD_PAGE_REQUESTS',    true );
define( 'TOKEN_SALT', '' );

$expose_debuger = true;
define( 'EXPOSE_OP_TRACE', ( defined('PREVENT_OUTPUT') && PREVENT_OUTPUT ) ? false : $expose_debuger); //Don't touch

/************************** Page Configurations *******************************/
$conf['general'] = array(
    "uselang"           => "he",
    "author"            => "SM projects",
    "app_version"       => '0.1',
    "fav_url"           => "/lib/css/fav/",
    "img_path"           => "/lib/images/",
    "site_base_url"     => "/Carasowatch/",
);

/************************** User account Configuration ************************/
$conf['user_account'] = array(
   'cookie_expire'           => 43200
); 