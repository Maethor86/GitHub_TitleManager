<?php

require_once(CLASS_PATH.DS."sqlserverdatabase.php");

class Subject extends DatabaseObject {

  protected static $table_name = "Web_Subjects";
  protected static $table_id_name = "SubjectID";

  protected $subtable_name = "Web_Pages";

  protected $SubjectID;
  protected $MenuName;
  protected $Position;
  protected $Visible;
  protected $Admin;

  // -------------------------------
  public function get_subjectid() {
    if (isset($this->SubjectID)) {
      return $this->SubjectID;
    }
    else {
      return "";
    }
  }

  public function get_menuname() {
    if (isset($this->MenuName)) {
      return $this->MenuName;
    }
    else {
      return "";
    }
  }

  public function get_position() {
    if (isset($this->Position)) {
      return $this->Position;
    }
    else {
      return "";
    }
  }

  public function get_visible() {
    if (isset($this->Visible)) {
      return $this->Visible;
    }
    else {
      return "";
    }
  }

  public function get_admin() {
    if (isset($this->Admin)) {
      return $this->Admin;
    }
    else {
      return "";
    }
  }

  public function find_pages() {
    global $database;
    // $called_class = get_called_class();
    $query = "SELECT * FROM " . $this->subtable_name . " WHERE " . $this->SubjectID . " = ? ";
    $params = array($this->SubjectID);
    // $result_array = $called_class::find_by_sql($query, $params);
    $result_set = $database->query($query, $params);
    if ($result_set) {
      while ($row = $database->fetch_array($result_set)) {
        $result_array[] = Page::instantiate($row); // can also use static::...
      }
      return $result_array;
    }
  }

  public function is_admin() {
    if ($this->get_admin() == 1) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

}



?>
