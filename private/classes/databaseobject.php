<?php

require_once(CLASS_PATH.DS."sqlserverdatabase.php");

abstract class DatabaseObject {


  // Common databaseobject methods

  protected function attributes() {
    // return an array of attribute keys and their values
    // get object vars returns an associative array with all attributes
    // (incl. private ones!) as the keys and their current values as the value
    return get_object_vars($this);
  }

  private function has_attribute($attribute) {
    $object_vars = $this->attributes();
    // we dont care about the value, we just want to know if the attribute exists or not
    // returns true or false
    return array_key_exists($attribute, $object_vars);
  }

  public static function find_all() {
    $called_class = get_called_class();
    $query = "SELECT * FROM " . $called_class::$table_name;
    $params = array();
    $result_array = $called_class::find_by_sql($query, $params);
    return $result_array;
  }

  public static function find_by_id($id=0) {
    $called_class = get_called_class();
    $query = "SELECT * FROM " . $called_class::$table_name . " WHERE " . $called_class::$table_id_name . " = ? ";
    $params = array($id);
    $result_array = $called_class::find_by_sql($query, $params);
    return (!empty($result_array)) ? array_shift($result_array) : FALSE;
  }

  public static function find_by_sql($sql="", $params=array()) {
    global $database;
    $called_class = get_called_class();
    $object_array = array();

    $result_set = $database->query($sql, $params);
    if ($result_set) {
      while ($row = $database->fetch_array($result_set)) {
        $object_array[] = $called_class::instantiate($row); // can also use static::...
      }
      return $object_array;
    }
    else {
      return FALSE;
    }
  }


  public static function create_by_sql($sql="", $params=array()) {
    global $database;

    $result = $database->query($sql, $params);
    return $result;
  }

  public static function update_by_sql($sql="", $params=array()) {
    global $database;

    $result = $database->query($sql, $params);
    return $result;
  }

  public static function delete_by_sql($sql="", $params=array()) {
    global $database;

    $result = $database->query($sql, $params);
    return $result;
  }

  private static function instantiate($row) {
    // should check to see if record exists and is an array
    $called_class = get_called_class();
    $object = new $called_class;

    foreach ($row as $attribute => $value) {
      if ($object->has_attribute($attribute)) {
        $object->$attribute = $value;
      }
    }
    return $object;
  }

}

?>
