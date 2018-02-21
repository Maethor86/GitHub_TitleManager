<?php

require_once(CLASS_PATH.DS."sqlserverdatabase.php");

class Moviequality extends DatabaseObject {

  protected static $table_name = "Moviequality";
  protected static $table_id_name = "MoviequalityID";

  protected $MoviequalityID;
  protected $Description;

  // -------------------------------
  public function get_moviequalityid() {
    if (isset($this->MoviequalityID)) {
      return $this->MoviequalityID;
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
