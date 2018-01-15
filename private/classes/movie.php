<?php

require_once(CLASS_PATH.DS."sqlserverdatabase.php");

class Movie extends DatabaseObject {

  protected static $table_name = "Movies";
  protected static $table_id_name = "MovieID";

  // protected $subtable_name = "Web_Pages";

  protected $MovieID;
  protected $DateTimeCreated;
  protected $Title;
  protected $IMDBRating;
  protected $RunningTime;

  // -------------------------------
  public function get_movieid() {
    if (isset($this->MovieID)) {
      return $this->MovieID;
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

  public function get_title() {
    if (isset($this->Title)) {
      return $this->Title;
    }
    else {
      return "";
    }
  }

  public function get_imdbrating() {
    if (isset($this->IMDBRating)) {
      return $this->IMDBRating;
    }
    else {
      return "";
    }
  }

  public function get_runningtime() {
    if (isset($this->RunningTime)) {
      return $this->RunningTime;
    }
    else {
      return "";
    }
  }

  public static function create($post) {

    $query  = "INSERT INTO " . self::$table_name;
    $query .= " (DateTimeCreated, Title, IMDBRating, RunningTime)";
    $query .= " VALUES";
    $query .= " (?, ?, ?, ?)";

    $params = array(generate_datetime_for_sql(),$post["title"],$post["imdbrating"], $post["runtime"]);

    $created_movie = self::create_by_sql($query, $params);
    return $created_movie;

  }

  public function update($post, $movie_id=0) {

    $query  = "UPDATE " . self::$table_name;
    $query .= " SET Title = ?";
    $query .= " WHERE";
    $query .= " MovieID = ?";

    $params = array($post["new_title"], $movie_id);

    $updated_movie = self::update_by_sql($query, $params);
    return $updated_movie;

  }

  public static function delete($movie_id=0) {

    $query  = "DELETE FROM " . self::$table_name;
    $query .= " WHERE MovieID = ?";

    $params = array($movie_id);

    $deleted_movie = self::delete_by_sql($query, $params);
    return $deleted_movie;

  }

  public static function find_movie_by_title($title) {

    // returns a movie if successful, or FALSE if unsuccessful
    $query = "SELECT TOP 1 * FROM " . self::$table_name . " WHERE Title = ? ";
    $params = array($title);
    $movie_array = self::find_by_sql($query, $params);
    return (!empty($movie_array)) ? array_shift($movie_array) : FALSE;

  }

  public static function find_movie_set_by_title($title) {
    // returns array with the movies that contains title

    // want to use contains instead of like, but needs configuring
    // like is much slower than contains
    // $query .= "WHERE CONTAINS(Title, ?)";
    $query  = "SELECT * FROM " . self::$table_name;
    $query .= " WHERE Title LIKE ?";
    $query .= " ORDER BY Title ASC";

    $title = "%" . $title . "%";
    $params = array($title);

    $movie_set = NULL;
    try {
      $movie_set = self::find_by_sql($query, $params);
    }
    catch (exception $e) {
      echo "Exception.";
      /*
      sql_log_errors($e, sqlsrv_errors());
      if ($e->getCode() == EXCEPTION_CODE_SQL_CONFIRM_QUERY) {
        $_SESSION["message"] .= "Couldn't find any movie with title containing '" . $title . "'.<br />";
      }
      else {
        $_SESSION["error"] .= make_exception_message_to_user($e);
      }
      */
    }
    return $movie_set;
  }

  public static function find_all_movies() {
    // returns array with movies if successful

    $query  = "SELECT * FROM Movies";
    $query .= " ORDER BY Title ASC";

    $params = array();

    $movie_set = NULL;
    try {
      $movie_set = self::find_by_sql($query, $params);
    }
    catch (exception $e) {
      echo "Exception.";
      /*
      sql_log_errors($e, sqlsrv_errors());
      if ($e->getCode() == EXCEPTION_CODE_SQL_CONFIRM_QUERY) {
        $_SESSION["message"] .= "Couldn't find any movie with title containing '" . $title . "'.<br />";
      }
      else {
        $_SESSION["error"] .= make_exception_message_to_user($e);
      }
      */
    }
    return $movie_set;
  }




}



?>
