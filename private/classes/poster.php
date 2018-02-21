<?php

require_once(CLASS_PATH.DS."sqlserverdatabase.php");

class Poster extends DatabaseObject {

  protected static $table_name = "Posters";
  protected static $table_id_name = "PosterID";

  protected $PosterID;
  protected $DateTimeCreated;
  protected $CreatedByUser;
  protected $MovieID;
  protected $Filename;
  protected $Type;
  protected $Size;
  protected $MouseoverTitle;

  // -------------------------------
  public function get_posterid() {
    if (isset($this->PosterID)) {
      return $this->PosterID;
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

  public function get_createdbyuser() {
    if (isset($this->CreatedByUser)) {
      return $this->CreatedByUser;
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

  public function get_filename() {
    if (isset($this->Filename)) {
      return $this->Filename;
    }
    else {
      return "";
    }
  }

  public function get_type() {
    if (isset($this->Type)) {
      return $this->Type;
    }
    else {
      return "";
    }
  }

  public function get_size() {
    if (isset($this->Size)) {
      return $this->Size;
    }
    else {
      return "";
    }
  }

  public function get_mouseovertitle() {
    if (isset($this->MouseoverTitle)) {
      return $this->MouseoverTitle;
    }
    else {
      return "";
    }
  }


  public static function find_poster_by_movieid($movieid) {
      // should i have filename as tt... or more unique? now its a little of both
    // returns a poster if successful, or FALSE if unsuccessful

    // check if poster is in db
    $query = "SELECT TOP 1 * FROM " . self::$table_name . " WHERE MovieID = ? ";
    $params = array($movieid);
    $poster_array = self::find_by_sql($query, $params);
    $poster_in_db = (!empty($poster_array)) ? array_shift($poster_array) : FALSE;

    // check to see if can find the file in question
    if ($poster_in_db) {
      $poster_on_disk = file_exists(POSTER_PATH.DS.$poster_in_db->get_filename() ."." . $poster_in_db->get_type());
      if ($poster_on_disk) {
        return $poster_in_db;
      }
      else {
        // found poster in db but not on disk
        $_SESSION["message"] .= "Discrepancy detected between database and files in system. <br />";
        $_SESSION["message"] .= "Contact administrator and give the following code: 0001. <br />";
        return FALSE;
      }
    }
    else {
      return FALSE;
    }
  }


  public static function encode_poster($poster_filename="none", $poster_filetype="none") {

    $poster_on_disk = file_exists(POSTER_PATH.DS.$poster_filename ."." . $poster_filetype);
    if ($poster_on_disk) {
      $image = file_get_contents(POSTER_PATH.DS.$poster_filename ."." . $poster_filetype);
      $image_encoded = base64_encode($image);
      return $image_encoded;
    }
    else {
      $image = file_get_contents(SITEIMAGE_PATH.DS. "no_poster.jpg");
      $image_encoded = base64_encode($image);
      return $image_encoded;
    }
  }

  public static function encode_icon($icon_filename="none", $icon_filetype="none") {

    $icon_on_disk = file_exists(SITEIMAGE_PATH.DS.$icon_filename ."." . $icon_filetype);
    if ($icon_on_disk) {
      $image = file_get_contents(SITEIMAGE_PATH.DS.$icon_filename ."." . $icon_filetype);
      $image_encoded = base64_encode($image);
      return $image_encoded;
    }
    else {
      $image = file_get_contents(SITEIMAGE_PATH.DS. "unknown_quality-logo.jpg");
      $image_encoded = base64_encode($image);
      return $image_encoded;
    }
  }


  public static function save($url,$filename,$movie) {



    if ($url == "N/A") {
      return FALSE;
    }
    try {
      // save to folder
      $arrContextOptions=array(
          "ssl"=>array(
              "cafile" => CERTIFICATE_PATH.DS."cacert.pem",
              "verify_peer"=> true,
              "verify_peer_name"=> true,
          ),
      );
      $image = file_get_contents($url, false, stream_context_create($arrContextOptions));
      file_put_contents(POSTER_PATH.DS. $filename . ".jpg", $image);

      // save to db
      $query  = "INSERT INTO " . self::$table_name;
      $query .= " (DateTimeCreated, CreatedByUser, MovieID, Filename, Type, Size, MouseoverTitle)";
      $query .= " VALUES";
      $query .= " (?, ?, ?, ?, ?, ?, ?)";
      $query .= " SELECT SCOPE_IDENTITY() AS id";

      $params = array(generate_datetime_for_sql(),$_SESSION["user_id"], $movie->get_movieid(),$movie->get_posterfilename(),"jpg",strlen($image),$movie->get_title());

      $poster_id = self::create_by_sql($query, $params);

      $poster = Poster::find_by_id($poster_id);
      return $poster;
    }
    catch (Exception $e) {
      $_SESSION["error"] .= var_dump($e);
      return FALSE;
    }

  }

  public static function delete($filename,$movieid) {

    try {
      // delete from folder
      unlink(POSTER_PATH.DS.$filename.".jpg");

      // delete from db
      $query  = "DELETE FROM " . self::$table_name;
      $query .= " WHERE MovieID = ?";

      $params = array($movieid);

      $deleted_poster = self::delete_by_sql($query, $params);
      return $deleted_poster;
    }
    catch (Exception $e) {
      return FALSE;
    }

  }


}



?>
