<?php

require_once(CLASS_PATH.DS."sqlserverdatabase.php");

class Moviestatus extends DatabaseObject {

  protected static $table_name = "Moviestatuses";
  protected static $table_id_name = "MoviestatusID";

  protected $MoviestatusID;
  protected $Description;

  // -------------------------------
  public function get_moviestatusid() {
    if (isset($this->MoviestatusID)) {
      return $this->MoviestatusID;
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

  public static function get_loanedoutmoviestatusid() {
    return 2;
  }

}

?>
