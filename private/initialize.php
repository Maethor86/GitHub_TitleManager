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
defined("SITE_ROOT") ? NULL : define("SITE_ROOT", "C:".DS."Maethor".DS."Program Filez".DS."Web Development".DS."Atom".DS."TitleManager");
defined("PUBLIC_PATH") ? NULL : define("PUBLIC_PATH", SITE_ROOT.DS."public");
defined("LIB_PATH") ? NULL : define("LIB_PATH", SITE_ROOT.DS."private");
defined("CLASS_PATH") ? NULL : define("CLASS_PATH", LIB_PATH.DS."classes");
defined("LAYOUT_PATH") ? NULL : define("LAYOUT_PATH", LIB_PATH.DS."layouts");

defined("STYLESHEET_PATH") ? NULL : define("STYLESHEET_PATH", SITE_ROOT.DS."public".DS."stylesheets.php");

// ----------------


// load config file first
require_once(LIB_PATH.DS."config.php");

// load basic functions next so that everything after can use them
require_once(LIB_PATH.DS."functions.php");
require_once(LIB_PATH.DS."validation_functions.php");

// load core objects
require_once(CLASS_PATH.DS."sqlserverdatabase.php");
require_once(CLASS_PATH.DS."session.php");
require_once(CLASS_PATH.DS."logger.php");

// load database-related classes
require_once(CLASS_PATH.DS."databaseobject.php");
require_once(CLASS_PATH.DS."user.php");
require_once(CLASS_PATH.DS."subject.php");
require_once(CLASS_PATH.DS."page.php");
require_once(CLASS_PATH.DS."mydatetime.php");
require_once(CLASS_PATH.DS."movie.php");

// load other classes


// must require now, but want to get rid of
/*
require_once(LIB_PATH.DS."filenames.php");
require_once(LIB_PATH.DS."functions.php");
require_once(LIB_PATH.DS."validation_functions.php");
require_once(LIB_PATH.DS."sql_functions.php");
require_once(LIB_PATH.DS."db_connection.php");
*/

?>
