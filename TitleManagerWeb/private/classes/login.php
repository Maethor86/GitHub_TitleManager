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


  // to database
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
