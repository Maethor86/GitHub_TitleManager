<?php

require_once(CLASS_PATH.DS."sqlserverdatabase.php");

class User extends DatabaseObject {

  protected static $table_name = "Web_Users";
  protected static $table_id_name = "UserID";

  protected $UserID;
  protected $Username;
  protected $HashedPassword;
  protected $UserRoleID;
  protected $DateTimeCreated;

  // -------------------------------
  public function get_userid() {
    if (isset($this->UserID)) {
      return $this->UserID;
    }
    else {
      return "";
    }
  }

  public function get_username() {
    if (isset($this->Username)) {
      return $this->Username;
    }
    else {
      return "";
    }
  }

  public function get_userroleid() {
    if (isset($this->UserRoleID)) {
      return $this->UserRoleID;
    }
    else {
      return "";
    }
  }

  public function get_datetimecreated() {
    if (isset($this->DateTimeCreated)) {
      return $this->DateTimeCreated;
    }
    else {
      return "";
    }
  }

  public function get_hashed_password() {
    if (isset($this->HashedPassword)) {
      return $this->HashedPassword;
    }
    else {
      return "";
    }
  }

  public static function create($post) {

    $userroleid = 2;

    $hashed_password = User::hash_password($post["password"]);

    $query  = "INSERT INTO " . self::$table_name;
    $query .= " (Username, HashedPassword, UserRoleID, DateTimeCreated)";
    $query .= " VALUES";
    $query .= " (?, ?, ?, ?)";

    $params = array($post["username"], $hashed_password, $userroleid, generate_datetime_for_sql());

    $created_user = self::create_by_sql($query, $params);
    return $created_user;
  }

  public function update($post, $user_id=0) {

    if (isset($_POST["new_password"])) {
      $hashed_password = User::hash_password($post["new_password"]);

      $query  = "UPDATE " . self::$table_name;
      $query .= " SET Username = ?, HashedPassword = ?";
      $query .= " WHERE";
      $query .= " UserID = ?";

      $params = array($post["new_username"], $hashed_password, $user_id);
    }
    else {
      $query  = "UPDATE " . self::$table_name;
      $query .= " SET Username = ?";
      $query .= " WHERE";
      $query .= " UserID = ?";

      $params = array($post["new_username"], $user_id);
    }

    $updated_user = self::update_by_sql($query, $params);
    return $updated_user;

  }

  public static function delete($user_id=0) {

    $user = User::find_by_id($user_id);
    if ($user) {
      if ($user->get_userroleid() == 1) {
        $_SESSION["message"] .= "Can't delete admin.<br />";
        return FALSE;
      }
      elseif ($user_id == $_SESSION["user_id"]) {
        $_SESSION["message"] .= "Can't delete the user that is logged in.<br />";
        return FALSE;
      }
      else {

        $query  = "DELETE FROM " . self::$table_name;
        $query .= " WHERE UserID = ?";

        $params = array($user_id);

        $deleted_user = self::delete_by_sql($query, $params);
        return $deleted_user;
      }
    }
    else {
      // i dont think this can ever happen
      $_SESSION["message"] .= "Couldn't find user.<br />";
      return FALSE;
    }
  }

  public static function user_find_by_username($username="") {


    $query = "SELECT TOP 1 * FROM " . self::$table_name . " WHERE Username = ? ";
    $params = array($username);
    $user_array = self::find_by_sql($query, $params);
    return (!empty($user_array)) ? array_shift($user_array) : FALSE;
  }

  public static function authenticate($username="", $password="") {
    $user = self::user_find_by_username($username);
    if ($user) {
      // found user, now check password
      if (password_verify($password, $user->get_hashed_password())) {
        //password mathces

        // log_user_in_database($user);
        return $user;
      }
      else {
        return FALSE;
      }
    }
    else {
      // user not found
      return FALSE;
    }
  }

  public static function hash_password($password) {
    $hashed_password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
    return $hashed_password;

  }

}



?>
