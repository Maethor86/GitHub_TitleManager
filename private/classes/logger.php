<?php


class Logger {

  private $sql_error_file = "sql_errors.log";

  function __construct() {

  }


  // to database
  public function database_create_user_log($user) {
    //rename to create_user_log($user) ?
    global $database;

    $user_id = $user->get_userid();
    $datetime_login = generate_datetime_for_sql();

    $datetime_last_activity = $datetime_login;

    $query  = "INSERT INTO Web_Logins (UserID, DateTimeLogin, DateTimeLastActivity)";
    $query .= " VALUES (?, ?, ?)";
    $query .= " ; SELECT SCOPE_IDENTITY() as id";

    $params = array($user_id, $datetime_login, $datetime_last_activity);

    $logged_user = $database->query($query, $params);
    $_SESSION["login_id"] = $database->get_scope_identity($logged_user);
    $_SESSION["last_activity"] = $datetime_last_activity;
    return $logged_user;
  }

  public function database_update_user_log($login_id) {
    global $database;

    $datetime_last_activity = generate_datetime_for_sql();

    $query  = "UPDATE Web_Logins";
    $query .= " SET DateTimeLastActivity = ?";
    $query .= " WHERE Web_LoginID = ?";

    $params = array($datetime_last_activity, $login_id);

    $logged = $database->query($query, $params);
    return $logged;
  }


  // to file
  public function log_to_file($file_name, $message) {
    $file = fopen($file_name, "a");
    if ($file) {
      $message .= "\n";
      fwrite($file, $message);
      fclose($file);
    }
    else {
      die("Unable to open file!");
    }
  }

// -------------------------------------------------

/*

public function log_sql_errors_in_database($exception, $error_string = " ") {

    $datetime_log = generate_datetime_for_sql();
    $error_message = $error_string;
    $exception_message = $exception->getMessage();
    $exception_code = $exception->getCode();
    $exception_trace = $exception->getTraceAsString();

    $query  = "INSERT INTO Web_Errors (DateTimeLog, ErrorMessage, ExceptionMessage, ExceptionCode, ExceptionTrace)";
    $query .= " VALUES (?, ?, ?, ?, ?)";
    //$query .= " ; SELECT SCOPE_IDENTITY() as id";

    $params = array($datetime_log, $error_message, $exception_message, $exception_code, $exception_trace);

    $logged_error = query($query, $params, TRUE);
    return $logged_error;
}

public function sql_log_errors($exception, $sql_errors) {
  $error_string = sql_formatted_errors($sql_errors);

  if (log_sql_errors_in_database($exception, $error_string)) {
    // errors logged in database
    $error_string .= "Errors logged in the database.";
  }
  else {
    // errors not logged in database
    $error_string .= "Errors not logged in the database.";
  }

  $sql_error_log = fopen("sql_errors.log", "a") or die("Unable to open file!");
  $error_string .= "\n";
  fwrite($sql_error_log, $error_string);
  fclose($sql_error_log);
}


*/


}

$logger = new Logger();

?>
