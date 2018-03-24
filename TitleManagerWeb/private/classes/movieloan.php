<?php

require_once(CLASS_PATH.DS."sqlserverdatabase.php");

class Movieloan extends DatabaseObject {

  protected static $table_name = "Movieloans";
  protected static $table_id_name = "MovieloanID";

  protected $MovieloanID;
  protected $MovieID;
  protected $LoanerID;
  protected $RegisteredByUser;
  protected $DateTimeLoan;
  protected $DateTimeReturn;

  // -------------------------------
  public function get_movieloanid() {
    if (isset($this->MovieloanID)) {
      return $this->MovieloanID;
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

  public function get_loanerid() {
    if (isset($this->LoanerID)) {
      return $this->LoanerID;
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

  public function get_datetimeloan() {
    if (isset($this->DateTimeLoan)) {
      return $this->DateTimeLoan;
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

  public static function create($movieid, $loanerid) {

    $query  = "INSERT INTO " . self::$table_name;
    $query .= " (MovieID, LoanerID, RegisteredByUser, DateTimeLoan)";
    $query .= " VALUES";
    $query .= " (?, ?, ?, ?)";
    $query .= "; SELECT SCOPE_IDENTITY() AS id";

    $params = array($movieid, $loanerid, $_SESSION["user_id"], generate_datetime_for_sql());

    $createdmovieloan_id = self::create_by_sql($query, $params);

    $movieloan = Movieloan::find_by_id($createdmovieloan_id);

    return $movieloan;

  }

  public static function find_by_movieid($movieid) {

    $query  = "SELECT TOP 1 * FROM " . self::$table_name;
    $query .= " INNER JOIN Movies ON Movies.MovieID = Movieloans.MovieID ";
    $query .= " WHERE Movieloans.MovieID = ?";
    $query .= " AND Movieloans.DateTimeReturn IS NULL";
    $query .= " AND Movies.DateTimeDeleted IS NULL";

    $params = array($movieid);
    // $params = array(array($imdbid, SQLSRV_PARAM_IN));

    $movieloan = self::find_by_sql($query, $params);
    return (!empty($movieloan)) ? array_shift($movieloan) : FALSE;
  }

  public static function find_all_currentloans() {
    $query  = "SELECT * FROM " . self::$table_name;
    $query .= " INNER JOIN Movies ON Movies.MovieID = Movieloans.MovieID ";
    $query .= " AND Movieloans.DateTimeReturn IS NULL";
    $query .= " AND Movies.DateTimeDeleted IS NULL";

    $params = array();

    $movieloans = self::find_by_sql($query, $params);
    return $movieloans;
  }


  public static function return_movieloan($movieid) {

    $movieloan = Movieloan::find_by_movieid($movieid);

    $query  = "UPDATE " . self::$table_name;
    $query .= " SET DateTimeReturn = ?";
    $query .= " WHERE";
    $query .= " MovieloanID = ?";
    $query .= " SELECT TOP 1 * FROM " . self::$table_name . " WHERE MovieloanID = ?";

    $params = array(generate_datetime_for_sql(), $movieloan->get_movieloanid(), $movieloan->get_movieloanid());

    $updatedmovieloan_id = self::update_by_sql($query, $params);

    $updatedmovieloan = Movieloan::find_by_id($updatedmovieloan_id);

    return $updatedmovieloan;
  }

}

?>
