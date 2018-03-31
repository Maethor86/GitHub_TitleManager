<?php

require_once(CLASS_PATH.DS."sqlserverdatabase.php");

class Userrole extends DatabaseObject {

  protected static $table_name = "Web_UserRoles";
  protected static $table_id_name = "UserRoleID";

  protected $UserRoleID;
  protected $UserRoleName;

  // -------------------------------
  public function get_userroleid() {
    if (isset($this->UserRoleID)) {
      return $this->UserRoleID;
    }
    else {
      return "";
    }
  }

  public function get_userrolename() {
    if (isset($this->UserRoleName)) {
      return $this->UserRoleName;
    }
    else {
      return "";
    }
  }


}



?>
