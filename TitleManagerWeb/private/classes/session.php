<?php

// a class to help work with sessions
// primarily to manage users logging in and out

// remember, it is generally inadvisible to store
// database-related objects in sessions

class Session {


  public $user_id;
  private $logged_in = FALSE;

  // in seconds
  private $max_inactivity_time = SESSION_INACTIVITY;


  function __construct() {
    session_save_path("../../../Sessions");
    session_start();
    $this->check_login();
    if ($this->logged_in) {
      // stuff to do right away if user is logged in
    }
    else {
      // stuff to do right away if user not logged in
    }
  }


  // -- get functions --

  public function get_max_inactivity_time() {
    return $this->max_inactivity_time;
  }

  // -- other functions --

  public function is_logged_in() {
    return $this->logged_in;
  }

  public function is_admin() {
    if ($this->is_logged_in()) {
      $current_user = User::find_by_id($this->user_id);
      if ($current_user->get_userroleid() == 1) {
        return TRUE;
      }
      else {
        return FALSE;
      }
    }
    else {
      return FALSE;
    }
  }

  public function login($user) {
    global $logger;
    if ($user) {
      $this->user_id = $_SESSION["user_id"] = $user->get_userid();
      $this->logged_in = TRUE;
      $logger->database_create_user_log($user->get_userid());
    }
  }

  public function logout() {
    unset($_SESSION["user_id"]);
    unset($this->user_id);
    $this->logged_in = FALSE;
  }


  public function session_error() {
    if (!empty($_SESSION["error"])) {
      $output  = "<div class=\"error\">";
      $output .= $_SESSION["error"];
      $output .= "</div>";

      $_SESSION["error"] = "";
      return $output;
    }
  }

  public function session_message() {
    echo $this->session_error();
    if (!empty($_SESSION["message"])) {
      $output  = "<div class=\"message\">";
      $output .= $_SESSION["message"];
      $output .= "</div>";

      $_SESSION["message"] = "";
      return $output;
    }
  }


  public function validate_session() {
    $max_inactivity_time = $this->get_max_inactivity_time();

    if (!isset($_SESSION["last_activity"])) {
      // $_SESSION["message"] .= "Warning: Session last activity not set when it should have been set.";
      return FALSE;
    }
    elseif (isset($_SESSION["last_activity"]) && ((strtotime(substr(generate_datetime_for_sql(),0,19)) - strtotime(substr($_SESSION["last_activity"],0,19))) > $max_inactivity_time)) {

      $_SESSION["message"] .= "Session timed out.";
      // $_SESSION["last_activity"] = NULL;
      return FALSE;
    }
    else {
      // session is valid
      return TRUE;
    }
  }

  public function update_session_activity() {
    global $logger;

    if (isset($_SESSION["last_activity"])) {
      $_SESSION["last_activity"] = generate_datetime_for_sql();

      if (isset($_SESSION["login_id"])) {
        // $_SESSION["login_id"] is set, this should be set whenever $_SESSION["user_id"] is set
        $login_id = $_SESSION["login_id"];
        $logged = $logger->database_update_user_log($login_id);
        if ($logged) {
          return TRUE;
        }
        else {
          return FALSE;
        }
      }
      else {
        $_SESSION["message"] .= "Warning: User will not be logged in the database.";
        return FALSE;
      }
    }
    else {
      // couldn't update session last_activity, it wasn't set
      $_SESSION["message"] .= "Warning: Session last activity not set, cannot update last activity.";
      return FALSE;
    }
  }

  public function is_session_valid() {
    $valid_session = $this->validate_session();
    if ($valid_session) {
      // session is valid
      $updated_session = $this->update_session_activity();
      if ($updated_session) {
        // session last_activity updated successfully
        return TRUE;
      }
      else {
        // something went wrong updating the session
        return FALSE;
      }
    }
    else {
      // session is not valid
      $_SESSION["last_activity"] = NULL;
      return FALSE;
    }
  }

  private function check_login() {
    if (isset($_SESSION["user_id"])) {
      $this->user_id = $_SESSION["user_id"];
      $this->logged_in = TRUE;
    }
    else {
      unset($this->user_id);
      $this->logged_in = FALSE;
    }
  }


}

$session = new Session();



/*
naming conventions for session entities:
all lowercase
  things stored in the sessions now
  $_SESSION["user_id"]
  $_SESSION["error"]
  $_SESSION["message"]
  $_SESSION["last_activity"]
  $_SESSION["login_id"]
  $_SESSION["subject_id"]
  $_SESSION["page_id"]
  $_SESSION["movie_set"] // array of movies
  // $_SESSION["username"]
*/
?>
