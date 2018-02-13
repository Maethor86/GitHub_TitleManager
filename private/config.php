<?php

// PHP related config
date_default_timezone_set("Europe/Oslo");


// SQL related config
defined("DB_SERVER") ? NULL : define("DB_SERVER","H710-MAETHOR\SQLEXPRESS");
defined("DB_DATABASE") ? NULL : define("DB_DATABASE","TestDatabase");
defined("DB_USER") ? NULL : define("DB_USER","TestLogin");
defined("DB_PASS") ? NULL : define("DB_PASS","TestLogin2");


defined("SQL_DATETIME_FRAC_SEC_PRECISION") ? NULL : define("SQL_DATETIME_FRAC_SEC_PRECISION", 7);
// defined("SQL_DATETIME_FRAC_SEC_PRECISIONz") ? NULL : define("SQL_DATETIME_FRAC_SEC_PRECISIONz", 7);
// $sql_datetime_fractional_second_precision = 7;

// exception code definitions
defined("EXCEPTION_CODE_SQL_CONFIRM_QUERY") ? NULL : define("EXCEPTION_CODE_SQL_CONFIRM_QUERY",200);

defined("EXCEPTION_CODE_FILE_NOT_FOUND") ? NULL : define("EXCEPTION_CODE_FILE_NOT_FOUND",300);

// session config
defined("SESSION_INACTIVITY") ? NULL : define("SESSION_INACTIVITY",30*60);


// other config

?>
