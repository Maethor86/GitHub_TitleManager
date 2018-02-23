<?php

require_once(CLASS_PATH.DS."sqlserverdatabase.php");

class Missingmovie extends DatabaseObject {

  protected static $table_name = "Missingmovies";
  protected static $table_id_name = "MissingmovieID";

  protected $MissingmovieID;
  protected $MovieID;
  protected $RegisteredByUser;
  protected $DateTimeMissing;
  protected $DateTimeReturn;

  // -------------------------------
  public function get_missingmovieid() {
    if (isset($this->MissingmovieID)) {
      return $this->MissingmovieID;
    }
    else {
      return "";
    }
  }

  public function get_movieid() {
    if (isset($this->MovieID)) {
      return $this->MovieID;
    }
    else {
      return "";
    }
  }

  public function get_registeredbyuser() {
    if (isset($this->RegisteredByUser)) {
      return $this->RegisteredByUser;
    }
    else {
      return "";
    }
  }

  public function get_datetimemissing() {
    if (isset($this->DateTimeMissing)) {
      return $this->DateTimeMissing;
    }
    else {
      return "";
    }
  }

  public function get_datetimereturn() {
    if (isset($this->DateTimeReturn)) {
      return $this->DateTimeReturn;
    }
    else {
      return "";
    }
  }

  public static function create($movieid) {

    $query  = "INSERT INTO " . self::$table_name;
    $query .= " (MovieID, RegisteredByUser, DateTimeMissing)";
    $query .= " VALUES";
    $query .= " (?, ?, ?)";
    $query .= "; SELECT SCOPE_IDENTITY() AS id";

    $params = array($movieid, $_SESSION["user_id"], generate_datetime_for_sql());

    $createdmissingmovie_id = self::create_by_sql($query, $params);

    $missingmovie = Missingmovie::find_by_id($createdmissingmovie_id);

    return $missingmovie;

  }

  public static function find_by_movieid($movieid) {
    $query  = "SELECT TOP 1 * FROM " . self::$table_name . " WHERE MovieID = ? AND DateTimeReturn IS NULL";

    $params = array($movieid);
    // $params = array(array($imdbid, SQLSRV_PARAM_IN));

    $missingmovie = self::find_by_sql($query, $params);
    return (!empty($missingmovie)) ? array_shift($missingmovie) : FALSE;
  }

  public static function return_missingmovie($movieid) {

    $missingmovie = Missingmovie::find_by_movieid($movieid);

    if ($missingmovie) {

      $query  = "UPDATE " . self::$table_name;
      $query .= " SET DateTimeReturn = ?";
      $query .= " WHERE";
      $query .= " MissingmovieID = ?";
      $query .= " SELECT TOP 1 * FROM " . self::$table_name . " WHERE MissingmovieID = ?";

      $params = array(generate_datetime_for_sql(), $missingmovie->get_missingmovieid(), $missingmovie->get_missingmovieid());

      $updatedmissingmovie_id = self::update_by_sql($query, $params);

      $updatedmissingmovie = Missingmovie::find_by_id($updatedmissingmovie_id);

      return $updatedmissingmovie;
    }
    else {
      return FALSE;
    }

  }

}

?>
