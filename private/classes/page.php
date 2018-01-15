<?php

require_once(CLASS_PATH.DS."sqlserverdatabase.php");

class Page extends DatabaseObject {

  protected static $table_name = "Web_Pages";
  protected static $table_id_name = "PageID";

  protected $PageID;
  protected $SubjectID;
  protected $MenuName;
  protected $Position;
  protected $Visible;

  // -------------------------------
  public function get_pageid() {
    if (isset($this->PageID)) {
      return $this->PageID;
    }
    else {
      return "";
    }
  }

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



  public static function find_pages($SubjectID=0) {
    // $called_class = get_called_class();
    $query = "SELECT * FROM " . Page::$table_name . " WHERE SubjectID = ? ";
    $params = array($SubjectID);
    $result_array = Page::find_by_sql($query, $params);
    // $result_set = $database->query($query, $params);
    return $result_array;
  }


}



?>
