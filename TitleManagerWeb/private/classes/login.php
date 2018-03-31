<?php

require_once(CLASS_PATH.DS."sqlserverdatabase.php");

class Login extends DatabaseObject {

  protected static $table_name = "Logins";
  protected static $table_id_name = "LoginID";

  protected $LoginID;
  protected $UserID;
  protected $DateTimeLogin;
  protected $DateTimeLastActivity;

  // get methods

  public function get_loginid() {
    if (isset($this->LoginID)) {
      return $this->LoginID;
    }
    else {
      return "";
    }
  }

  public function get_userid() {
    if (isset($this->UserID)) {
      return $this->UserID;
    }
    else {
      return "";
    }
  }

  public function get_datetimelogin() {
    if (isset($this->DateTimeLogin)) {
      return $this->DateTimeLogin;
    }
    else {
      return "";
    }
  }

  public function get_datetimelastactivity() {
    if (isset($this->DateTimeLastActivity)) {
      return $this->DateTimeLastActivity;
    }
    else {
      return "";
    }
  }

  // -------------------------------

  public static function find_previous_login_by_userid($user_id, $offset=0) {

    // offset=0 means the current login, offset=1 means the previous login etc

    $query  = "SELECT * FROM " . self::$table_name;
    $query .= " WHERE UserID = ?";
    $query .= " ORDER BY DateTimeLogin DESC";
    $query .= " OFFSET ? ROWS";
    $query .= " FETCH NEXT 1 ROWS ONLY";

    $params = array($user_id, $offset);

    $login = self::find_by_sql($query, $params);
    return (!empty($login)) ? array_shift($login) : FALSE;
  }

  public static function find_logins_by_userid($user_id) {
    $query  = "SELECT * FROM " . self::$table_name;
    $query .= " WHERE UserID = ?";

    $params = array($user_id);

    $logins = self::find_by_sql($query, $params);
    return $logins;
  }

  public static function create_user_login($user_id) {
    $datetime_login = generate_datetime_for_sql();
    $datetime_last_activity = $datetime_login;

    $query  = "INSERT INTO " . self::$table_name;
    $query .= " (UserID, DateTimeLogin, DateTimeLastActivity)";
    $query .= " VALUES (?, ?, ?)";
    $query .= " ; SELECT SCOPE_IDENTITY() as id";

    $params = array($user_id, $datetime_login, $datetime_last_activity);

    $login_id = self::create_by_sql($query, $params);
    $login = self::find_by_id($login_id);

    return $login;
  }

  public function update_user_login($login_id) {
    $datetime_last_activity = generate_datetime_for_sql();

    $query  = "UPDATE " . self::$table_name;;
    $query .= " SET DateTimeLastActivity = ?";
    $query .= " WHERE LoginID = ?";
    $query .= " SELECT TOP 1 * FROM " . self::$table_name . " WHERE LoginID = ?";

    $params = array($datetime_last_activity, $login_id, $login_id);

    $newlogin_id = self::update_by_sql($query, $params);
    $login = self::find_by_id($newlogin_id);
    return $login;
  }

}

?>
