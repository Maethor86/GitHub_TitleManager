<?php

require_once(CLASS_PATH.DS."sqlserverdatabase.php");

class Loaner extends DatabaseObject {

  protected static $table_name = "Loaners";
  protected static $table_id_name = "LoanerID";

  protected $LoanerID;
  protected $Description;

  // -------------------------------
  public function get_loanerid() {
    if (isset($this->LoanerID)) {
      return $this->LoanerID;
    }
    else {
      return "";
    }
  }

  public function get_description() {
    if (isset($this->Description)) {
      return $this->Description;
    }
    else {
      return "";
    }
  }

  public function find_currentloans() {

    $query  = "SELECT * FROM Movieloans";
    $query .= " INNER JOIN Movies ON Movies.MovieID = Movieloans.MovieID ";
    $query .= " WHERE Movieloans.LoanerID = ?";
    $query .= " AND Movieloans.DateTimeReturn IS NULL";
    $query .= " AND Movies.DateTimeDeleted IS NULL";

    $params = array($this->get_loanerid());

    $movieloans = $this->find_by_sql($query, $params);

    return $movieloans;

  }

  public static function create($description="none") {

    $query  = "INSERT INTO " . self::$table_name;
    $query .= " (Description)";
    $query .= " VALUES";
    $query .= " (?)";
    $query .= "; SELECT SCOPE_IDENTITY() AS id";

    $params = array($description);

    $createdloaner_id = self::create_by_sql($query, $params);

    $loaner = Loaner::find_by_id($createdloaner_id);

    return $loaner;

  }

}

?>
