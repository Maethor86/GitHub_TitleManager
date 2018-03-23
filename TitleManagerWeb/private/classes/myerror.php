<?php

require_once(CLASS_PATH.DS."sqlserverdatabase.php");

class MyError extends DatabaseObject {

  protected static $table_name = "Errors";
  protected static $table_id_name = "ErrorID";

  protected $ErrorID;
  protected $DateTimeLogged;
  protected $GeneratedByUser;
  protected $ErrorstringID;
  protected $ExceptionCode;
  protected $GeneratedByIPAddress;


  public function get_errorid() {
    if (isset($this->ErrorID)) {
      return $this->ErrorID;
    }
    else {
      return "";
    }
  }

  public function get_datetimelogged() {
    if (isset($this->DateTimeLogged)) {
      return $this->DateTimeLogged;
    }
    else {
      return "";
    }
  }

  public function get_generatedbyuser() {
    if (isset($this->GeneratedByUser)) {
      return $this->GeneratedByUser;
    }
    else {
      return "";
    }
  }

  public function get_errorstringid() {
    if (isset($this->ErrorstringID)) {
      return $this->ErrorstringID;
    }
    else {
      return "";
    }
  }

  public function get_exceptioncode() {
    if (isset($this->ExceptionCode)) {
      return $this->ExceptionCode;
    }
    else {
      return "";
    }
  }

  public function get_generatedbyipaddress() {
    if (isset($this->GeneratedByIPAddress)) {
      return $this->GeneratedByIPAddress;
    }
    else {
      return "";
    }
  }



  public static function log_error($errorstring_id, $exception_code=0) {

    isset($_SESSION["user_id"]) ? $generatedby = $_SESSION["user_id"] : $generatedby = NULL;
    $query  = "INSERT INTO " . self::$table_name;
    $query .= " (DateTimeLogged, GeneratedByUser, ErrorstringID, ExceptionCode, GeneratedByIPAddress)";
    $query .= " VALUES";
    $query .= " (?, ?, ?, ?, ?)";
    $query .= "; SELECT SCOPE_IDENTITY() AS id";

    $params = array(generate_datetime_for_sql(), $generatedby, $errorstring_id, $exception_code, $_SERVER["REMOTE_ADDR"]);

    $createderror_id = self::create_by_sql($query, $params);
    $createderror = MyError::find_by_id($createderror_id);
    return $createderror;
  }
}

?>
