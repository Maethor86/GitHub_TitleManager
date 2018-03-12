<?php

require_once(CLASS_PATH.DS."sqlserverdatabase.php");

class Moviesorting extends DatabaseObject {

  protected static $table_name = "Moviesortings";
  protected static $table_id_name = "MoviesortingID";

  protected $MoviesortingID;
  protected $SortType;
  protected $Description;

  // -------------------------------
  public function get_moviesortingid() {
    if (isset($this->MoviesortingID)) {
      return $this->MoviesortingID;
    }
    else {
      return "";
    }
  }

  public function get_sorttype() {
    if (isset($this->SortType)) {
      return $this->SortType;
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

}

?>
