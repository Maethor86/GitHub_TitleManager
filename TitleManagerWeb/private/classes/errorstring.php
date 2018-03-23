<?php

require_once(CLASS_PATH.DS."sqlserverdatabase.php");

class Errorstring extends DatabaseObject {


  protected static $table_name = "Errorstrings";
  protected static $table_id_name = "ErrorstringID";

  protected $ErrorstringID;
  protected $Errorstring;
  protected $DateTimeFirstLogged;

  public function get_errorstringid() {
    if (isset($this->ErrorstringID)) {
      return $this->ErrorstringID;
    }
    else {
      return "";
    }
  }

  public function get_errorstring() {
    if (isset($this->Errorstring)) {
      return $this->Errorstring;
    }
    else {
      return "";
    }
  }

  public function get_datetimefirstlogged() {
    if (isset($this->DateTimeFirstLogged)) {
      return $this->DateTimeFirstLogged;
    }
    else {
      return "";
    }
  }


  public static function find_errorstring($error_message) {
    $query  = "SELECT TOP 1 * FROM " . self::$table_name;
    $query .= " WHERE Errorstring = ?";

    $params = array($error_message);

    $errorstring = self::find_by_sql($query, $params);
    return (!empty($errorstring)) ? array_shift($errorstring) : FALSE;
  }

  public static function create_errorstring($error_message) {
    $query  = "INSERT INTO " . self::$table_name;
    $query .= " (Errorstring, DateTimeFirstLogged)";
    $query .= " VALUES";
    $query .= " (?, ?)";
    $query .= "; SELECT SCOPE_IDENTITY() AS id";

    $params = array($error_message, generate_datetime_for_sql());

    $createderrorstring_id = self::create_by_sql($query, $params);
    $createderrorstring = Errorstring::find_by_id($createderrorstring_id);
    return $createderrorstring;
  }

}

?>
