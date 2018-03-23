<?php

// require_once($filename_config);
// require_once($filename_sessions);


// include($filename_standard_header);
// include($filename_standard_sidebar_left);


// define core paths
// define them as absolute paths to make sure that require_once
// works as expected

// DIRECTORY_SEPARATOR is a PHP pre-defined constant
// (\ for Windows / for Unix)
defined("DS") ? NULL : define("DS",DIRECTORY_SEPARATOR);

// define some absolute paths
defined("SITE_ROOT") ? NULL : define("SITE_ROOT", "C:".DS."Maethor".DS."Projects".DS."TitleManager".DS."TitleManagerWeb");
defined("PUBLIC_PATH") ? NULL : define("PUBLIC_PATH", SITE_ROOT.DS."public");
defined("LIB_PATH") ? NULL : define("LIB_PATH", SITE_ROOT.DS."private");
defined("CLASS_PATH") ? NULL : define("CLASS_PATH", LIB_PATH.DS."classes");
defined("LAYOUT_PATH") ? NULL : define("LAYOUT_PATH", LIB_PATH.DS."layouts");
defined("IMAGE_PATH") ? NULL : define("IMAGE_PATH", PUBLIC_PATH.DS."images");
defined("POSTER_PATH") ? NULL : define("POSTER_PATH", IMAGE_PATH.DS."posters");
defined("SITEIMAGE_PATH") ? NULL : define("SITEIMAGE_PATH", IMAGE_PATH.DS."site");

defined("STYLESHEET_PATH") ? NULL : define("STYLESHEET_PATH", SITE_ROOT.DS."public".DS."stylesheets.php");

// other absolute paths
defined("CERTIFICATE_PATH") ? NULL : define("CERTIFICATE_PATH", LIB_PATH.DS."certificates");
defined("LOG_PATH") ? NULL : define("LOG_PATH", LIB_PATH.DS."logs");

// ----------------


// load config file first
require_once(LIB_PATH.DS."config.php");

// load logger class to enable logging
require_once(CLASS_PATH.DS."logger.php");
require_once(CLASS_PATH.DS."exceptions.php");

// load basic functions next so that everything after can use them
require_once(LIB_PATH.DS."functions.php");
require_once(LIB_PATH.DS."validation_functions.php");

// load core objects
require_once(CLASS_PATH.DS."sqlserverdatabase.php");
require_once(CLASS_PATH.DS."session.php");

// load database-related classes
require_once(CLASS_PATH.DS."databaseobject.php");
require_once(CLASS_PATH.DS."user.php");
require_once(CLASS_PATH.DS."subject.php");
require_once(CLASS_PATH.DS."page.php");
require_once(CLASS_PATH.DS."mydatetime.php");
require_once(CLASS_PATH.DS."movie.php");
require_once(CLASS_PATH.DS."poster.php");
require_once(CLASS_PATH.DS."moviestatus.php");
require_once(CLASS_PATH.DS."moviequality.php");
require_once(CLASS_PATH.DS."moviesorting.php");
require_once(CLASS_PATH.DS."loaner.php");
require_once(CLASS_PATH.DS."movieloan.php");
require_once(CLASS_PATH.DS."missingmovie.php");
require_once(CLASS_PATH.DS."myerror.php");
require_once(CLASS_PATH.DS."errorstring.php");

// load other classes
require_once(CLASS_PATH.DS."pagination.php");

// must require now, but want to get rid of
/*
require_once(LIB_PATH.DS."filenames.php");
require_once(LIB_PATH.DS."functions.php");
require_once(LIB_PATH.DS."validation_functions.php");
require_once(LIB_PATH.DS."sql_functions.php");
require_once(LIB_PATH.DS."db_connection.php");
*/

?>
