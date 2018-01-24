<?php

require_once(CLASS_PATH.DS."sqlserverdatabase.php");

class Movie extends DatabaseObject {

  protected static $table_name = "Movies";
  protected static $table_id_name = "MovieID";

  // protected $subtable_name = "Web_Pages";

  protected $MovieID;
  protected $DateTimeCreated;
  protected $CreatedByUser;
  protected $Title;
  protected $IMDBID;
  protected $IMDBRating;
  protected $RunningTime;
  protected $IMDBVotes;
  protected $Plot;
  protected $ReleasedYear;

  // -------------------------------
  public function get_movieid() {
    if (isset($this->MovieID)) {
      return $this->MovieID;
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

  public function get_title() {
    if (isset($this->Title)) {
      return $this->Title;
    }
    else {
      return "";
    }
  }

  public function get_imdbrating() {
    if (isset($this->IMDBRating)) {
      return $this->IMDBRating;
    }
    else {
      return "";
    }
  }

  public function get_imdbid() {
    if (isset($this->IMDBID)) {
      return $this->IMDBID;
    }
    else {
      return "";
    }
  }

  public function get_runningtime() {
    if (isset($this->RunningTime)) {
      return $this->RunningTime;
    }
    else {
      return "";
    }
  }

  public function get_imdbvotes() {
    if (isset($this->IMDBVotes)) {
      return $this->IMDBVotes;
    }
    else {
      return "";
    }
  }

  public function get_plot() {
    if (isset($this->Plot)) {
      return $this->Plot;
    }
    else {
      return "";
    }
  }

  public function get_releasedyear() {
    if (isset($this->ReleasedYear)) {
      return $this->ReleasedYear;
    }
    else {
      return "";
    }
  }

  public function get_runningtime_hours() {
    $minutes = $this->get_runningtime();
    $hours = intdiv($minutes,60);
    $remaining_minutes = $minutes % 60;
    return array($hours,$remaining_minutes);
  }

  public static function get_from_imdb($title) {

    $clean_title = Movie::clean_title_for_imdb($title);
    $search_term = Movie::get_imdb_url() . $clean_title;
    $response = file_get_contents($search_term);
    if ($response) {
      $data = json_decode($response, TRUE);
      if ($data["Response"] == "True") {
        $runtime = substr($data["Runtime"], 0, -4);
        $imdbvotes = str_replace(",", "", $data["imdbVotes"]);
        $data["Runtime"] = $runtime;
        $data["imdbVotes"] = $imdbvotes;
        return $data;
      }
      else {
        return FALSE;
      }
    }
    else {
      return FALSE;
    }
  }

  public static function clean_title_for_imdb($title) {
    $title = str_replace(" ", "+", $title);
    $title = str_replace(":", "%3A", $title);
    return $title;
  }

  public static function get_imdb_url() {
    return "http://www.omdbapi.com/?apikey=ce86382d&t=";
  }


  public static function create($title) {

    $data = Movie::get_from_imdb($title);
    if ($data) {
      $query  = "INSERT INTO " . self::$table_name;
      $query .= " (DateTimeCreated, CreatedByUser, Title, IMDBID, IMDBRating, RunningTime, IMDBVotes, Plot, ReleasedYear)";
      $query .= " VALUES";
      $query .= " (?, ?, ?, ?, ?, ?, ?, ?, ?)";

      $params = array(generate_datetime_for_sql(),$_SESSION["user_id"], $data["Title"],$data["imdbID"],$data["imdbRating"],$data["Runtime"],$data["imdbVotes"], $data["Plot"],$data["Year"]);
    }
    else {
      $query  = "INSERT INTO " . self::$table_name;
      $query .= " (DateTimeCreated, CreatedByUser, Title)";
      $query .= " VALUES";
      $query .= " (?, ?, ?)";

      $params = array(generate_datetime_for_sql(),$_SESSION["user_id"], $title);
    }

    $created_movie = self::create_by_sql($query, $params);
    return $created_movie;
  }

//
// {"Title":"Kingsman: The Secret Service",
//   "Year":"2014",
//   "Rated":"R",
//   "Released":"13 Feb 2015",
//   "Runtime":"129 min",
//   "Genre":"Action, Adventure, Comedy",
//   "Director":"Matthew Vaughn",
//   "Writer":"Jane Goldman (screenplay), Matthew Vaughn (screenplay), Mark Millar (comic book \"The Secret Service\"), Dave Gibbons (comic book \"The Secret Service\")",
//   "Actors":"Adrian Quinton, Colin Firth, Mark Strong, Jonno Davies",
//   "Plot":"A spy organization recruits an unrefined, but promising street kid into the agency's ultra-competitive training program, just as a global threat emerges from a twisted tech genius.",
//   "Language":"English, Arabic, Swedish",
//   "Country":"UK, USA",
//   "Awards":"7 wins & 26 nominations.",
//   "Poster":"https://images-na.ssl-images-amazon.com/images/M/MV5BMTkxMjgwMDM4Ml5BMl5BanBnXkFtZTgwMTk3NTIwNDE@._V1_SX300.jpg",
//   "Ratings":[{"Source":"Internet Movie Database","Value":"7.7/10"},
//   {"Source":"Rotten Tomatoes","Value":"74%"},
//   {"Source":"Metacritic","Value":"60/100"}],
//   "Metascore":"60",
//   "imdbRating":"7.7",
//   "imdbVotes":"486,942",
//   "imdbID":"tt2802144",
//   "Type":"movie","DVD":"09 Jun 2015","BoxOffice":"$119,469,511","Production":"20th Century Fox","Website":"http://www.KingsmanMovie.com","Response":"True"}
//
//

  public function update($post, $movie_id=0) {

    $title = $post["new_title"];
    $title = str_replace(" ", "+", $title);
    $title = str_replace(":", "%3A", $title);
    $search_term = "http://www.omdbapi.com/?apikey=ce86382d&t=" . $title;
    $response = file_get_contents($search_term);
    $data = json_decode($response, TRUE);

    $runtime = substr($data["Runtime"], 0, -4);
    $imdbvotes = str_replace(",", "", $data["imdbVotes"]);

    $query  = "UPDATE " . self::$table_name;
    $query .= " SET Title = ?, IMDBID = ?, IMDBRating = ?, RunningTime = ?, IMDBVotes = ?, Plot = ?, ReleasedYear = ?";
    $query .= " WHERE";
    $query .= " MovieID = ?";

    $params = array($data["Title"],$data["imdbID"],$data["imdbRating"],$runtime,$imdbvotes, $data["Plot"],$data["Year"], $movie_id);

    $updated_movie = self::update_by_sql($query, $params);
    return $updated_movie;

  }

  public static function delete($movie_id=0) {

    $query  = "DELETE FROM " . self::$table_name;
    $query .= " WHERE MovieID = ?";

    $params = array($movie_id);

    $deleted_movie = self::delete_by_sql($query, $params);
    return $deleted_movie;

  }

  public static function find_movie_by_title($title) {

    // returns a movie if successful, or FALSE if unsuccessful
    $query = "SELECT TOP 1 * FROM " . self::$table_name . " WHERE Title = ? ";
    $params = array($title);
    $movie_array = self::find_by_sql($query, $params);
    return (!empty($movie_array)) ? array_shift($movie_array) : FALSE;

  }

  public static function find_movie_set_by_title($title) {
    // returns array with the movies that contains title

    // want to use contains instead of like, but needs configuring
    // like is much slower than contains
    // $query .= "WHERE CONTAINS(Title, ?)";
    $query  = "SELECT * FROM " . self::$table_name;
    $query .= " WHERE Title LIKE ?";
    $query .= " ORDER BY Title ASC";

    $title = "%" . $title . "%";
    $params = array($title);

    $movie_set = NULL;
    try {
      $movie_set = self::find_by_sql($query, $params);
    }
    catch (exception $e) {
      echo "Exception.";
      /*
      sql_log_errors($e, sqlsrv_errors());
      if ($e->getCode() == EXCEPTION_CODE_SQL_CONFIRM_QUERY) {
        $_SESSION["message"] .= "Couldn't find any movie with title containing '" . $title . "'.<br />";
      }
      else {
        $_SESSION["error"] .= make_exception_message_to_user($e);
      }
      */
    }
    return $movie_set;
  }

  public static function find_all_movies() {
    // returns array with movies if successful

    $query  = "SELECT * FROM Movies";
    $query .= " ORDER BY Title ASC";

    $params = array();

    $movie_set = NULL;
    try {
      $movie_set = self::find_by_sql($query, $params);
    }
    catch (exception $e) {
      echo "Exception.";
      /*
      sql_log_errors($e, sqlsrv_errors());
      if ($e->getCode() == EXCEPTION_CODE_SQL_CONFIRM_QUERY) {
        $_SESSION["message"] .= "Couldn't find any movie with title containing '" . $title . "'.<br />";
      }
      else {
        $_SESSION["error"] .= make_exception_message_to_user($e);
      }
      */
    }
    return $movie_set;
  }




}



?>
