<?php

// require_once("../config.php");
// require_once("logger.php");

class SQLSERVERDatabase {

  private $connection;
  private $last_query;
  private $last_query_params;

  function __construct() {
    $this->open_connection();
  }

  public function open_connection() {

    $serverName = DB_SERVER;
    $connectionOptions = array(
        "Database"              => DB_DATABASE,
        "Uid"                   => DB_USER,
        "PWD"                   => DB_PASS,
        "ReturnDatesAsStrings"  => TRUE
        );
    // establishes the connection
    $this->connection = sqlsrv_connect($serverName, $connectionOptions);
    if(!$this->connection) {
      $message = "Couldn't connect to database '" . DB_DATABASE . "'. " . $this->sql_formatted_errors(sqlsrv_errors());
      throw new \DatabaseConnectionFailedException($message);
    }
  }

  public function close_connection() {
    if (isset($this->connection)) {
      sqlsrv_close($this->connection);
      unset($this->connection);
    }
  }

  public function query($sql, $params) {
    try {
      $result_set = sqlsrv_query($this->connection, $sql, $params);
      $this->confirm_query($result_set);
      return $result_set;
    }
    catch (\Exception $e) {
      $class = get_class($e);
      throw new $class($e->getMessage() . "SQL query: '" . $sql . "', with parameters: " . json_encode($params), $e->getCode());
    }


  }

  private function confirm_query($result_set) {
    if (!$result_set) {
      throw new \DatabaseQueryFailedException("Error confirming query. " . $this->sql_formatted_errors(sqlsrv_errors()));
    }
    else {
      return TRUE;
    }
  }

  public function fetch_array($result_set) {
    $result = sqlsrv_fetch_array($result_set, SQLSRV_FETCH_ASSOC);
    return $result;
  }

  private function sql_prep($string) {
    // prepare the statement, to prevent SQL injection
    return $string;
  }

  private function release_query($query) {
    sqlsrv_free_stmt($query);
  }

  public function sql_formatted_errors($errors) {
    // shows a list of errors when trying to connect with sql server
    if (empty($errors)) {
      return "No SQL errors. ";
    }

    $output  = "SQL errors: ";
    $output .= "[";
    $output .= generate_datetime_for_sql();
    $output .= "] ";
    $output .= "Error information: ";

    $count = 1;
    foreach ($errors as $error) {
      $output .= "Error number " . $count . ", ";
      $output .= "SQLSTATE: ".$error['SQLSTATE'].", ";
      $output .= "Code: ".$error['code'].", ";
      $output .= "Message: ".$error['message'] . " ";
      $count++;
    }
    $output .= "End of SQL errors. ";
    return $output;
  }

  public function get_scope_identity($logged_user) {
    sqlsrv_next_result($logged_user);
    sqlsrv_fetch($logged_user);
    $output = sqlsrv_get_field($logged_user, 0);
    return $output;

  }


// -------------------------------------------------

// public function log_sql_errors_in_database($exception, $error_string = " ") {
//
//      $datetime_log = generate_datetime_for_sql();
//      $error_message = $error_string;
//      $exception_message = $exception->getMessage();
//      $exception_code = $exception->getCode();
//      $exception_trace = $exception->getTraceAsString();
//
//      $query  = "INSERT INTO Web_Errors (DateTimeLog, ErrorMessage, ExceptionMessage, ExceptionCode, ExceptionTrace)";
//      $query .= " VALUES (?, ?, ?, ?, ?)";
//      //$query .= " ; SELECT SCOPE_IDENTITY() as id";
//
//      $params = array($datetime_log, $error_message, $exception_message, $exception_code, $exception_trace);
//
//      $logged_error = query($query, $params, TRUE);
//      return $logged_error;
//  }

 // public function sql_log_errors($exception, $sql_errors) {
 //   $error_string = sql_formatted_errors($sql_errors);
 //
 //   if (log_sql_errors_in_database($exception, $error_string)) {
 //     // errors logged in database
 //     $error_string .= "Errors logged in the database.";
 //   }
 //   else {
 //     // errors not logged in database
 //     $error_string .= "Errors not logged in the database.";
 //   }
 //
 //   $sql_error_log = fopen("sql_errors.log", "a") or die("Unable to open file!");
 //   $error_string .= "\n";
 //   fwrite($sql_error_log, $error_string);
 //   fclose($sql_error_log);
 // }



}

$database = new SQLSERVERDatabase();

?>
