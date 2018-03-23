<?php

$config = parse_ini_file("config.ini");

// debug environemt
defined("DEBUG") ? NULL : define("DEBUG", $config["debug"]);

// error reporting
DEBUG ? error_reporting(E_ALL) : error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);

// PHP related config
date_default_timezone_set($config["timezone"]);


// SQL related config
defined("DB_SERVER") ? NULL : define("DB_SERVER",$config["db_server"]);
defined("DB_DATABASE") ? NULL : define("DB_DATABASE",$config["db_database"]);
defined("DB_USER") ? NULL : define("DB_USER",$config["db_user"]);
defined("DB_PASS") ? NULL : define("DB_PASS",$config["db_password"]);


defined("SQL_DATETIME_FRAC_SEC_PRECISION") ? NULL : define("SQL_DATETIME_FRAC_SEC_PRECISION", 7);
// defined("SQL_DATETIME_FRAC_SEC_PRECISIONz") ? NULL : define("SQL_DATETIME_FRAC_SEC_PRECISIONz", 7);
// $sql_datetime_fractional_second_precision = 7;

// exception code definitions
defined("ExceptionCode_Database") ? NULL : define("ExceptionCode_Database",(int) $config["DatabaseException"]);
defined("ExceptionCode_DatabaseConnectionFailed") ? NULL : define("ExceptionCode_DatabaseConnectionFailed",(int) $config["DatabaseConnectionFailedException"]);
defined("ExceptionCode_DatabaseQueryFailed") ? NULL : define("ExceptionCode_DatabaseQueryFailed",(int) $config["DatabaseQueryFailedException"]);

defined("ExceptionCode_Error") ? NULL : define("ExceptionCode_Error",(int) $config["ErrorException"]);

defined("ExceptionCode_CannotFindClass") ? NULL : define("ExceptionCode_CannotFindClass",(int) $config["CannotFindClassException"]);

// legacy exception codes, want to delete
// defined("EXCEPTION_CODE_SQL_CONFIRM_QUERY") ? NULL : define("EXCEPTION_CODE_SQL_CONFIRM_QUERY",200);
// defined("EXCEPTION_CODE_FILE_NOT_FOUND") ? NULL : define("EXCEPTION_CODE_FILE_NOT_FOUND",300);

// session config
defined("SESSION_INACTIVITY") ? NULL : define("SESSION_INACTIVITY",(int) $config["SessionInactivity"]);


// other config
// defined("CriticalDBException_min") ? NULL : define("CriticalDBException_min",(int) $config["CriticalDBException_min"]);
// defined("CriticalDBException_max") ? NULL : define("CriticalDBException_max",(int) $config["CriticalDBException_max"]);

?>
