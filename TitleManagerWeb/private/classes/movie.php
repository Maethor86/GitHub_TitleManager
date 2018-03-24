<?php

require_once(CLASS_PATH.DS."sqlserverdatabase.php");

class Movie extends DatabaseObject {

  protected static $table_name = "Movies";
  protected static $table_id_name = "MovieID";

  protected $MovieID;
  protected $DateTimeCreated;
  protected $CreatedByUser;
  protected $DateTimeDeleted;
  protected $DeletedByUser;
  protected $Title;
  protected $IMDBID;
  protected $IMDBRating;
  protected $RunningTime;
  protected $IMDBVotes;
  protected $PlotSummary;
  protected $Plot;
  protected $ReleasedYear;

  protected $Language;
  protected $Country;
  protected $Genre;
  protected $Director;
  protected $Cast;
  protected $PosterURL;

  protected $MoviestatusID;
  protected $MoviequalityID;
  protected $LoanerID;



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

  public function get_datetimedeleted() {
    if (isset($this->DateTimeDeleted)) {
      return $this->DateTimeDeleted;
    }
    else {
      return "";
    }
  }

  public function get_deletedbyuser() {
    if (isset($this->DeletedByUser)) {
      return $this->DeletedByUser;
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

  public function get_plotsummary() {
    if (isset($this->PlotSummary)) {
      return $this->PlotSummary;
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

  public function get_language() {
    if (isset($this->Language)) {
      return $this->Language;
    }
    else {
      return "";
    }
  }

  public function get_country() {
    if (isset($this->Country)) {
      return $this->Country;
    }
    else {
      return "";
    }
  }

  public function get_genre() {
    if (isset($this->Genre)) {
      return $this->Genre;
    }
    else {
      return "";
    }
  }

  public function get_director() {
    if (isset($this->Director)) {
      return $this->Director;
    }
    else {
      return "";
    }
  }

  public function get_cast() {
    if (isset($this->Cast)) {
      return $this->Cast;
    }
    else {
      return "";
    }
  }

  public function get_posterurl() {
    if (isset($this->PosterURL)) {
      return $this->PosterURL;
    }
    else {
      return "";
    }
  }

  public function get_moviestatusid() {
    if (isset($this->MoviestatusID)) {
      return $this->MoviestatusID;
    }
    else {
      return "";
    }
  }

  public function get_moviequalityid() {
    if (isset($this->MoviequalityID)) {
      return $this->MoviequalityID;
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

  public function get_posterfilename() {
    return $this->get_imdbid();
    // return $this->get_moviestatusid() . $this->get_moviequalityid() . $this->get_imdbid();
  }


  public static function search_extdb_title($title, $page) {

    $clean_title = Movie::prepare_title_for_extdb_search($title);
    $url = Movie::get_extdb_url_title() . $clean_title;
    $url = $url . "&page=" . $page;
    // $connected = connection_status();
    // if ($connected == 0) {
    //   echo "Connected!";
    // }
    // else {
    //   echo "Not connected.";
    // }
    // try {
      $response = @file_get_contents($url);
      if ($response) {
        $data = json_decode($response, TRUE);
        if ($data["Response"] == "True") {
          return $data;
        }
        else {
          return FALSE;
        }
      }
      else {
        // maybe not connected to the internet
        $_SESSION["message"] .= "Could not perform search. Check that you have an Internet connection.";
        return FALSE;
      }
    // }
    // catch (Exception $e) {
    //   // handle exception
    //   return FALSE;
    // }



  }

    // helper functions for search_extdb_title()
    public static function prepare_title_for_extdb_search($title) {
      $title = str_replace(" ", "+", $title);
      $title = str_replace(":", "%3A", $title);
      return $title;
    }
    public static function get_extdb_url_title() {
      return "http://www.omdbapi.com/?apikey=ce86382d&type=movie&s=";
    }
    // -- helper functions for search_extdb_title() --

  public static function search_extdb_imdbid($imdbid) {

    $url = Movie::get_extdb_url_imdbid() . $imdbid;
    $url_short = $url . "&plot=short";
    $url_full = $url . "&plot=full";
    $response_short = @file_get_contents($url_short);
    $response_full = @file_get_contents($url_full);
    if ($response_short && $response_full) {
      $data = json_decode($response_short, TRUE);
      $data_full = json_decode($response_full, TRUE);
      if ($data["Response"] == "True" && $data_full["Response"] == "True") {
        $runtime = substr($data["Runtime"], 0, -4);
        $imdbvotes = str_replace(",", "", $data["imdbVotes"]);
        $data["Runtime"] = $runtime;
        $data["imdbVotes"] = $imdbvotes;
        $data["PlotSummary"] = $data["Plot"];
        $data["Plot"] = $data_full["Plot"];
        $movie = Movie::create_object($data);
        return $movie;
      }
      else {
        return FALSE;
      }
    }
    else {
      // maybe not connected to the internet
      $_SESSION["message"] .= "Could not perform search. Check that you have an Internet connection.";
      return FALSE;
    }
  }

    // helper functions for search_extdb_imdbid()
    public static function create_object($data) {
      $movie = new Movie;

      $movie->MovieID = FALSE;
      $movie->DateTimeCreated = FALSE;
      $movie->CreatedByUser = FALSE;
      $movie->Title = $data["Title"];
      $movie->IMDBID = $data["imdbID"];
      $movie->IMDBRating = $data["imdbRating"];
      $movie->RunningTime = $data["Runtime"];
      $movie->IMDBVotes = $data["imdbVotes"];
      $movie->PlotSummary = $data["PlotSummary"];
      $movie->Plot = $data["Plot"];
      $movie->ReleasedYear = $data["Year"];
      $movie->Language = $data["Language"];
      $movie->Country = $data["Country"];
      $movie->Genre = $data["Genre"];
      $movie->Director = $data["Director"];
      $movie->Cast = $data["Actors"];
      $movie->PosterURL = $data["Poster"];

      return $movie;
    }
    public static function get_extdb_url_imdbid() {
      return "http://www.omdbapi.com/?apikey=ce86382d&i=";
    }
    // -- helper functions for search_extdb_imdbid() --




  public static function create_from_imdbid($imdbid=0, $status=0, $quality=0, $loanerid=FALSE) {

    $movie = Movie::search_extdb_imdbid($imdbid);

    if ($movie) {

      $params = array(generate_datetime_for_sql(),$_SESSION["user_id"],$movie->get_title(),$movie->get_imdbid(),$movie->get_imdbrating(),$movie->get_runningtime(),$movie->get_imdbvotes(),$movie->get_plotsummary(),$movie->get_plot(),$movie->get_releasedyear(),$movie->get_language(),$movie->get_country(),$movie->get_genre(),$movie->get_director(),$movie->get_cast(),$movie->get_posterurl(),$status,$quality);

      $query  = "INSERT INTO " . self::$table_name;
      $query .= " (DateTimeCreated, CreatedByUser, Title, IMDBID, IMDBRating, RunningTime, IMDBVotes, PlotSummary, Plot, ReleasedYear, Language, Country, Genre, Director, Cast, PosterURL, MoviestatusID, MoviequalityID)";
      $query .= " VALUES";
      $query .= " (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
      $query .= "; SELECT SCOPE_IDENTITY() AS id";


      $createdmovie_id = self::create_by_sql($query, $params);

      $createdmovie = Movie::find_by_id($createdmovie_id);

      if ($status == Moviestatus::get_loanedoutmoviestatusid() && $loanerid) {
        Movieloan::create($createdmovie->get_movieid(),$loanerid);
      }

      if ($status == Moviestatus::get_missingmoviestatusid()) {
        Missingmovie::create($createdmovie->get_movieid());
      }

      return $createdmovie;
    }
    else {
      return FALSE;
    }
  }




  public function update($movie_id=0) {

    $local_movie = Movie::find_by_id($movie_id);

    if ($local_movie) {
      $ext_movie = Movie::search_extdb_imdbid($local_movie->get_imdbid());
    }
    else {
      $ext_movie = FALSE;
    }
    if ($ext_movie) {
      $query  = "UPDATE " . self::$table_name;
      $query .= " SET Title = ?, IMDBID = ?, IMDBRating = ?, RunningTime = ?, IMDBVotes = ?, PlotSummary = ?, Plot = ?, ReleasedYear = ?, Language = ?, Country = ?, Genre = ?, Director = ?, Cast = ?, PosterURL = ?";
      $query .= " WHERE";
      $query .= " MovieID = ?";
      $query .= " SELECT TOP 1 * FROM " . self::$table_name . " WHERE MovieID = ?";

      $params = array($ext_movie->get_title(),$ext_movie->get_imdbid(),$ext_movie->get_imdbrating(),$ext_movie->get_runningtime(),$ext_movie->get_imdbvotes(),$ext_movie->get_plotsummary(),$ext_movie->get_plot(),$ext_movie->get_releasedyear(),$ext_movie->get_language(),$ext_movie->get_country(),$ext_movie->get_genre(),$ext_movie->get_director(),$ext_movie->get_cast(),$ext_movie->get_posterurl(),$movie_id,$movie_id);

      $updatedmovie_id = self::update_by_sql($query, $params);

      $movie = Movie::find_by_id($updatedmovie_id);

      return $movie;
    }
    else {
      return FALSE;
    }
  }

  public function update_movieoptions($movie_id=0, $status=0, $quality=0,  $loanerid=FALSE) {

    $movie = Movie::find_by_id($movie_id);

    if ($movie) {

      if ($status == 0 && $quality == 0) {
        return $movie;
      }

      if ($status == Moviestatus::get_loanedoutmoviestatusid() && $loanerid) {
        Movieloan::create($movie->get_movieid(),$loanerid);
      }
      elseif ($movie->get_moviestatusid() == Moviestatus::get_loanedoutmoviestatusid() && ($status != Moviestatus::get_loanedoutmoviestatusid()) && ($status != 0)) {
        Movieloan::return_movieloan($movie->get_movieid());
      }

      if ($status == Moviestatus::get_missingmoviestatusid()) {
        Missingmovie::create($movie->get_movieid());
      }
      elseif ($movie->get_moviestatusid() == Moviestatus::get_missingmoviestatusid() && ($status != Moviestatus::get_missingmoviestatusid()) && ($status != 0)) {
        Missingmovie::return_missingmovie($movie->get_movieid());
      }

      $params = array();

      $query  = "UPDATE " . self::$table_name;
      $query .= " SET";
      if ($status != 0) {
      $query .= " MoviestatusID = ?,";
        array_push($params, $status);
      }
      if ($quality != 0) {
      $query .= " MoviequalityID = ?";
        array_push($params, $quality);
      }
      $query .= " WHERE";
      $query .= " MovieID = ?";
      $query .= " SELECT TOP 1 * FROM " . self::$table_name . " WHERE MovieID = ?";

      array_push($params, $movie->get_movieid(),$movie->get_movieid());

      $updatedmovie_id = self::update_by_sql($query, $params);

      $updatedmovie = Movie::find_by_id($updatedmovie_id);

      return $updatedmovie;
    }
    else {
      return FALSE;
    }
  }




  public static function delete($movie_id=0) {

    $movie = Movie::find_by_id($movie_id);

    if ($movie) {
      // first delete the poster for the movie
      $deleted_poster = Poster::delete($movie->get_imdbid(),$movie->get_movieid());

      // then flags the movie as deleted in the database
      $query  = "UPDATE " . self::$table_name;
      $query .= " SET DateTimeDeleted = ?, DeletedByUser = ?";
      $query .= " WHERE";
      $query .= " MovieID = ?";
      $query .= " SELECT TOP 1 * FROM " . self::$table_name . " WHERE MovieID = ?";

      $params = array(generate_datetime_for_sql(), $_SESSION["user_id"],$movie->get_movieid(),$movie->get_movieid());

      $deletedmovie_id = self::update_by_sql($query, $params);

      $deletedmovie = Movie::find_by_id($deletedmovie_id);

      return $deletedmovie;
    }
    else {
      return FALSE;
    }
  }

  public static function find_movie_by_title($title) {

    // returns a movie if successful, or FALSE if unsuccessful
    $query = "SELECT TOP 1 * FROM " . self::$table_name . " WHERE Title = ? ";
    $params = array($title);
    $movie_array = self::find_by_sql($query, $params);
    return (!empty($movie_array)) ? array_shift($movie_array) : FALSE;

  }

  public static function find_movie_by_imdbid($imdbid) {

    // returns a movie if successful, or FALSE if unsuccessful
    $query = "EXEC sp.find_movie_by_imdbid @imdbid = ?";
    // $query  = "SELECT * FROM " . self::$table_name . " WHERE IMDBID = ?";
    // $query .= " AND DateTimeDeleted IS NULL";
    // $query .= " AND IMDBID = ? ";

    $params = array($imdbid);
    // $params = array(array($imdbid, SQLSRV_PARAM_IN));

    $movie_array = self::find_by_sql($query, $params);
    return $movie_array;
    // return (!empty($movie_array)) ? array_shift($movie_array) : FALSE;

  }

  public static function find_movie_set_by_title($title, $status="all", $quality="all", $sortingid=1, $per_page=10, $offset=10) {
    // returns array with the movies that contains title

    // want to use contains instead of like, but needs configuring
    // like is much slower than contains
    // $query .= "WHERE CONTAINS(Title, ?)";
    $title = "%" . $title . "%";
    $params = array($title);
    $sorting = Moviesorting::find_by_id($sortingid);

    $query  = "SELECT * FROM " . self::$table_name;
    $query .= " WHERE Title LIKE ?";
    if (!($status == "all")) {
      $query .= " AND MoviestatusID = ?";
      array_push($params, $status);
    }
    if (!($quality == "all")) {
      $query .= " AND MoviequalityID = ?";
      array_push($params, $quality);
    }

    $query .= " AND DateTimeDeleted IS NULL";
    $query .= " ORDER BY " . $sorting->get_sorttype();
    $query .= " OFFSET ? ROWS";
    $query .= " FETCH NEXT ? ROWS ONLY";

    array_push($params, $offset, $per_page);

    // $movie_set = NULL;
    try {
      $movie_set = self::find_by_sql($query, $params);
      return $movie_set;
    }
    catch (exception $e) {
      echo "Exception. " . $e->getMessage();
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
  }

  public static function count_movies() {
    // returns array with movies if successful
    return Movie::count_movie_set_by_title("");
  }


  public static function count_movie_set_by_title($title, $status="all", $quality="all") {
    // returns array with the movies that contains title

    // want to use contains instead of like, but needs configuring
    // like is much slower than contains
    // $query .= "WHERE CONTAINS(Title, ?)";
    $query  = "SELECT COUNT (MovieID)";
    $query .= "FROM " . self::$table_name;
    $query .= " WHERE Title LIKE ?";
    if (!($status == "all")) {
      $query .= " AND MoviestatusID = ?";
    }
    if (!($quality == "all")) {
      $query .= " AND MoviequalityID = ?";
    }
    $query .= " AND DateTimeDeleted IS NULL";

    $title = "%" . $title . "%";
    $params = array($title);
    if (!($status == "all")) {
      array_push($params, $status);
    }
    if (!($quality == "all")) {
      array_push($params, $quality);
    }

    global $database;
    $result_set = $database->query($query, $params);
    $count = $database->fetch_array($result_set);
    return array_shift($count);
  }



  public static function url_to_image($url) {

    if ($url == "N/A" || empty($url)) {
      $image = file_get_contents(SITEIMAGE_PATH.DS. "no_poster.jpg");
      $image_encoded = base64_encode($image);
      return $image_encoded;
    }
    try {

      $arrContextOptions=array(
          "ssl"=>array(
              "cafile" => CERTIFICATE_PATH.DS."cacert.pem",
              "verify_peer"=> true,
              "verify_peer_name"=> true,
          ),
      );

      $image = file_get_contents($url, false, stream_context_create($arrContextOptions));
      $image_encoded = base64_encode($image);
      return $image_encoded;
    }
    catch (Exception $e) {
      $image = file_get_contents(SITEIMAGE_PATH.DS. "no_poster.jpg");
      $image_encoded = base64_encode($image);
      return $image_encoded;
    }
  }




}













//
// public static function create($title) {
//
//   // $url = 'http://example.com/image.php';
//   // $img = '/my/folder/flower.gif';
//   // copy($img, file_get_contents($url)); or use copy()
//
//   $data = Movie::get_from_imdb($title);
//   if ($data) {
//     $query  = "INSERT INTO " . self::$table_name;
//     $query .= " (DateTimeCreated, CreatedByUser, Title, IMDBID, IMDBRating, RunningTime, IMDBVotes, Plot, ReleasedYear, Language, Country, Genre, Director, Cast)";
//     $query .= " VALUES";
//     $query .= " (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
//     // $query .= " SELECT SCOPE_IDENTITY AS id";
//
//     $params = array(generate_datetime_for_sql(),$_SESSION["user_id"], $data["Title"],$data["imdbID"],$data["imdbRating"],$data["Runtime"],$data["imdbVotes"], $data["Plot"],$data["Year"],$data["Language"],$data["Country"],$data["Genre"],$data["Director"],$data["Actors"]);
//   }
//   else {
//     $query  = "INSERT INTO " . self::$table_name;
//     $query .= " (DateTimeCreated, CreatedByUser, Title)";
//     $query .= " VALUES";
//     $query .= " (?, ?, ?)";
//     // $query .= " SELECT SCOPE_IDENTITY AS id";
//
//     $params = array(generate_datetime_for_sql(),$_SESSION["user_id"], $title);
//   }
//
//   $created_movie_id = self::create_by_sql($query, $params);
//
//   $movie = Movie::find_by_id($created_movie_id);
//
//   return $movie;
//
//   //
//   // {"Title":"Kingsman: The Secret Service",
//   //   "Year":"2014",
//   //   "Rated":"R",
//   //   "Released":"13 Feb 2015",
//   //   "Runtime":"129 min",
//   //   "Genre":"Action, Adventure, Comedy",
//   //   "Director":"Matthew Vaughn",
//   //   "Writer":"Jane Goldman (screenplay), Matthew Vaughn (screenplay), Mark Millar (comic book \"The Secret Service\"), Dave Gibbons (comic book \"The Secret Service\")",
//   //   "Actors":"Adrian Quinton, Colin Firth, Mark Strong, Jonno Davies",
//   //   "Plot":"A spy organization recruits an unrefined, but promising street kid into the agency's ultra-competitive training program, just as a global threat emerges from a twisted tech genius.",
//   //   "Language":"English, Arabic, Swedish",
//   //   "Country":"UK, USA",
//   //   "Awards":"7 wins & 26 nominations.",
//   //   "Poster":"https://images-na.ssl-images-amazon.com/images/M/MV5BMTkxMjgwMDM4Ml5BMl5BanBnXkFtZTgwMTk3NTIwNDE@._V1_SX300.jpg",
//   //   "Ratings":[{"Source":"Internet Movie Database","Value":"7.7/10"},
//   //   {"Source":"Rotten Tomatoes","Value":"74%"},
//   //   {"Source":"Metacritic","Value":"60/100"}],
//   //   "Metascore":"60",
//   //   "imdbRating":"7.7",
//   //   "imdbVotes":"486,942",
//   //   "imdbID":"tt2802144",
//   //   "Type":"movie","DVD":"09 Jun 2015","BoxOffice":"$119,469,511","Production":"20th Century Fox","Website":"http://www.KingsmanMovie.com","Response":"True"}
//   //
//   //
//
// }
//
// imdb methods
//
// public static function get_from_imdb($title) {
//
//   $clean_title = Movie::clean_title_for_imdb($title);
//   $search_term = Movie::get_imdb_url() . $clean_title;
//   $response = file_get_contents($search_term);
//   if ($response) {
//     $data = json_decode($response, TRUE);
//     if ($data["Response"] == "True") {
//       $runtime = substr($data["Runtime"], 0, -4);
//       $imdbvotes = str_replace(",", "", $data["imdbVotes"]);
//       $data["Runtime"] = $runtime;
//       $data["imdbVotes"] = $imdbvotes;
//       return $data;
//     }
//     else {
//       return FALSE;
//     }
//   }
//   else {
//     return FALSE;
//   }
// }
//
// public static function clean_title_for_imdb($title) {
//   $title = str_replace(" ", "+", $title);
//   $title = str_replace(":", "%3A", $title);
//   return $title;
// }
//
// public static function get_imdb_url() {
//   return "http://www.omdbapi.com/?apikey=ce86382d&t=";
// }
//
//


  // public static function get_poster($poster_filename="", $poster_filetype="") {
  //   if (file_exists(POSTER_PATH.DS. $poster_filename . "." . $poster_filetype)) {
  //     $image = file_get_contents(POSTER_PATH.DS. $poster_filename . "." . $poster_filetype);
  //   }
  //   else {
  //     $image = file_get_contents(SITEIMAGE_PATH.DS. "no_poster.jpg");
  //   }
  //   $image_encoded = base64_encode($image);
  //   return $image_encoded;
  // }
  //
  // public static function poster($poster) {
  //   $image = file_get_contents(POSTER_PATH.DS. $poster->get_filename() . "." . $poster->get_type());
  //   $image_encoded = base64_encode($image);
  //   return $image_encoded;
  // }
  //
  // public static function no_poster() {
  //   $image = file_get_contents(SITEIMAGE_PATH.DS. "no_poster.jpg");
  //   $image_encoded = base64_encode($image);
  //   return $image_encoded;
  // }



  //
  //
  // public static function search_for_movie($search_term, $page) {
  //
  //
  //   $clean_title = Movie::clean_title_for_imdb($search_term);
  //   $search_term = Movie::get_imdb_url_search() . $clean_title;
  //   $search_term = $search_term . "&page=" . $page;
  //   $response = file_get_contents($search_term);
  //   if ($response) {
  //     $data = json_decode($response, TRUE);
  //     if ($data["Response"] == "True") {
  //       // if ($data["totalResults"] > 1) {
  //       //   foreach ($data["Search"] as $title) {
  //       //     echo $title["Title"];
  //       //   }
  //       // }
  //       // $runtime = substr($data["Runtime"], 0, -4);
  //       // $imdbvotes = str_replace(",", "", $data["imdbVotes"]);
  //       // $data["Runtime"] = $runtime;
  //       // $data["imdbVotes"] = $imdbvotes;
  //       return $data;
  //     }
  //     else {
  //       return FALSE;
  //     }
  //   }
  //   else {
  //     return FALSE;
  //   }
  //
  //   // return '<img src="data:image/jpeg;base64,' . Movie::url_to_image('https://images-na.ssl-images-amazon.com/images/M/MV5BN2EyZjM3NzUtNWUzMi00MTgxLWI0NTctMzY4M2VlOTdjZWRiXkEyXkFqcGdeQXVyNDUzOTQ5MjY@._V1_SX300.jpg') .'" height="400">';
  //
  //
  //   // return TRUE;
  //   // success
  //     // {"Search":
  //     //   [
  //     //     {"Title":"The Lord of the Rings: The Fellowship of the Ring","Year":"2001","imdbID":"tt0120737","Type":"movie","Poster":"https://images-na.ssl-images-amazon.com/images/M/MV5BN2EyZjM3NzUtNWUzMi00MTgxLWI0NTctMzY4M2VlOTdjZWRiXkEyXkFqcGdeQXVyNDUzOTQ5MjY@._V1_SX300.jpg"},
  //     //     {"Title":"The Lord of the Rings: The Return of the King","Year":"2003","imdbID":"tt0167260","Type":"movie","Poster":"https://images-na.ssl-images-amazon.com/images/M/MV5BYWY1ZWQ5YjMtMDE0MS00NWIzLWE1M2YtODYzYTk2OTNlYWZmXkEyXkFqcGdeQXVyNDUyOTg3Njg@._V1_SX300.jpg"},
  //     //     {"Title":"The Lord of the Rings: The Two Towers","Year":"2002","imdbID":"tt0167261","Type":"movie","Poster":"https://images-na.ssl-images-amazon.com/images/M/MV5BMDY0NmI4ZjctN2VhZS00YzExLTkyZGItMTJhOTU5NTg4MDU4XkEyXkFqcGdeQXVyNjU0OTQ0OTY@._V1_SX300.jpg"},
  //     //     {"Title":"The Lord of the Rings","Year":"1978","imdbID":"tt0077869","Type":"movie","Poster":"https://images-na.ssl-images-amazon.com/images/M/MV5BOGMyNWJhZmYtNGQxYi00Y2ZjLWJmNjktNTgzZWJjOTg4YjM3L2ltYWdlXkEyXkFqcGdeQXVyNTAyODkwOQ@@._V1_SX300.jpg"},{"Title":"The Lord of the Rings: The Two Towers","Year":"2002","imdbID":"tt0347436","Type":"game","Poster":"https://images-na.ssl-images-amazon.com/images/M/MV5BODI0Mzk3OTM4N15BMl5BanBnXkFtZTgwMTM4MTk4MDE@._V1_SX300.jpg"},
  //     //     {"Title":"The Lord of the Rings: The Return of the King","Year":"2003","imdbID":"tt0387360","Type":"game","Poster":"https://images-na.ssl-images-amazon.com/images/M/MV5BMjE5NTQwMTY5MV5BMl5BanBnXkFtZTgwODcwNjUwMTE@._V1_SX300.jpg"},{"Title":"The Lord of the Rings: The Battle for Middle-Earth","Year":"2004","imdbID":"tt0412935","Type":"game","Poster":"http://ia.media-imdb.com/images/M/MV5BMTY0NTE4NjgzMV5BMl5BanBnXkFtZTcwNzI2MzcyMQ@@._V1_SX300.jpg"},
  //     //     {"Title":"The Lord of the Rings: The Battle for Middle-Earth II","Year":"2006","imdbID":"tt0760172","Type":"game","Poster":"http://ia.media-imdb.com/images/M/MV5BMTkyMzk4MDkzOF5BMl5BanBnXkFtZTcwMjYwNjIzMQ@@._V1_SX300.jpg"},{"Title":"The Lord of the Rings: The Battle for Middle-earth II - The Rise of the Witch-king","Year":"2006","imdbID":"tt1058040","Type":"game","Poster":"http://ia.media-imdb.com/images/M/MV5BMjYwMDIxNjg3MV5BMl5BanBnXkFtZTgwMTk5MTE4MDE@._V1_SX300.jpg"},
  //     //     {"Title":"The Lord of the Rings: The Third Age","Year":"2004","imdbID":"tt0415947","Type":"game","Poster":"http://ia.media-imdb.com/images/M/MV5BMTMwMzM2NzU1M15BMl5BanBnXkFtZTcwOTQ3MzcyMQ@@._V1_SX300.jpg"}
  //     //   ],
  //     //   "totalResults":"43","Response":"True"
  //     // }
  //
  //   // failure
  //     // {"Response":"False","Error":"Movie not found!"}
  //     // {"Response":"False","Error":"Invalid API key!"}
  //
  //
  //
  // }
  //
  // public static function url_to_image($url) {
  //
  //   if ($url == "N/A") {
  //     return FALSE;
  //   }
  //   try {
  //
  //     $arrContextOptions=array(
  //         "ssl"=>array(
  //             "cafile" => "../../../Certificates/cacert.pem",
  //             "verify_peer"=> true,
  //             "verify_peer_name"=> true,
  //         ),
  //     );
  //
  //     $image = $url;
  //     $imageData = base64_encode(file_get_contents($image, false, stream_context_create($arrContextOptions)));
  //
  //     return $imageData;
  //   }
  //   catch (Exception $e) {
  //     return FALSE;
  //   }
  // }
  //
  // public static function get_from_imdb_search($title) {
  //
  //   $clean_title = Movie::clean_title_for_imdb_search($title);
  //   $search_term = Movie::get_imdb_url_search() . $clean_title;
  //   $response = file_get_contents($search_term);
  //   if ($response) {
  //     $data = json_decode($response, TRUE);
  //     if ($data["Response"] == "True") {
  //       $runtime = substr($data["Runtime"], 0, -4);
  //       $imdbvotes = str_replace(",", "", $data["imdbVotes"]);
  //       $data["Runtime"] = $runtime;
  //       $data["imdbVotes"] = $imdbvotes;
  //       return $data;
  //     }
  //     else {
  //       return FALSE;
  //     }
  //   }
  //   else {
  //     return FALSE;
  //   }
  // }
  //
  // public static function clean_title_for_imdb_search($title) {
  //   $title = str_replace(" ", "+", $title);
  //   $title = str_replace(":", "%3A", $title);
  //   return $title;
  // }
  //
  // public static function get_imdb_url_search() {
  //   return "http://www.omdbapi.com/?apikey=ce86382d&type=movie&s=";
  // }


  //
  //
  // public static function get_from_imdb_with_imdbid($imdbid) {
  //
  //   $search_term = Movie::get_imdb_url_imdbid() . $imdbid;
  //   $response = file_get_contents($search_term);
  //   if ($response) {
  //     $data = json_decode($response, TRUE);
  //     if ($data["Response"] == "True") {
  //       $runtime = substr($data["Runtime"], 0, -4);
  //       $imdbvotes = str_replace(",", "", $data["imdbVotes"]);
  //       $data["Runtime"] = $runtime;
  //       $data["imdbVotes"] = $imdbvotes;
  //       $movie = Movie::create_object($data);
  //       return $movie;
  //     }
  //     else {
  //       return FALSE;
  //     }
  //   }
  //   else {
  //     return FALSE;
  //   }
  // }
  //
  // public static function clean_title_for_imdb_imdbid($title) {
  //   $title = str_replace(" ", "+", $title);
  //   $title = str_replace(":", "%3A", $title);
  //   return $title;
  // }
  //
  // public static function get_imdb_url_imdbid() {
  //   return "http://www.omdbapi.com/?apikey=ce86382d&plot=full&i=";
  // }
  //
  // public static function create_object($data) {
  //   $movie = new Movie;
  //
  //   $movie->MovieID = FALSE;
  //   $movie->DateTimeCreated = FALSE;
  //   $movie->CreatedByUser = FALSE;
  //   $movie->Title = $data["Title"];
  //   $movie->IMDBID = $data["imdbID"];
  //   $movie->IMDBRating = $data["imdbRating"];
  //   $movie->RunningTime = $data["Runtime"];
  //   $movie->IMDBVotes = $data["imdbVotes"];
  //   $movie->Plot = $data["Plot"];
  //   $movie->ReleasedYear = $data["Year"];
  //   $movie->Language = $data["Language"];
  //   $movie->Country = $data["Country"];
  //   $movie->Genre = $data["Genre"];
  //   $movie->Director = $data["Director"];
  //   $movie->Cast = $data["Actors"];
  //
  //   return $movie;
  // }
  //


    //
    // public static function create_from_imdbid($imdbid) {
    //
    //   $data = Movie::get_from_imdb_with_imdbid($imdbid);
    //   $movie = Movie::create_object($data);
    //
    //   $query  = "INSERT INTO " . self::$table_name;
    //   $query .= " (DateTimeCreated, CreatedByUser, Title, IMDBID, IMDBRating, RunningTime, IMDBVotes, Plot, ReleasedYear, Language, Country, Genre, Director, Cast)";
    //   $query .= " VALUES";
    //   $query .= " (?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?)";
    //   $query .= "; SELECT SCOPE_IDENTITY() AS id";
    //   $params = array(generate_datetime_for_sql(),$_SESSION["user_id"],$movie->get_title(),$movie->get_imdbid(),$movie->get_imdbrating(),$movie->get_runningtime(),$movie->get_imdbvotes(),$movie->get_plot(),$movie->get_releasedyear(),$movie->get_language(),$movie->get_country(),$movie->get_genre(),$movie->get_director(),$movie->get_cast());
    //
    //   $created_movie_id = self::create_by_sql($query, $params);
    //
    //   $movie = Movie::find_by_id($created_movie_id);
    //
    //   return $movie;
    // }
    //
    // public static function create($title) {
    //
    //   // $url = 'http://example.com/image.php';
    //   // $img = '/my/folder/flower.gif';
    //   // copy($img, file_get_contents($url)); or use copy()
    //
    //   $data = Movie::get_from_imdb($title);
    //   if ($data) {
    //     $query  = "INSERT INTO " . self::$table_name;
    //     $query .= " (DateTimeCreated, CreatedByUser, Title, IMDBID, IMDBRating, RunningTime, IMDBVotes, Plot, ReleasedYear, Language, Country, Genre, Director, Cast)";
    //     $query .= " VALUES";
    //     $query .= " (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    //     // $query .= " SELECT SCOPE_IDENTITY AS id";
    //
    //     $params = array(generate_datetime_for_sql(),$_SESSION["user_id"], $data["Title"],$data["imdbID"],$data["imdbRating"],$data["Runtime"],$data["imdbVotes"], $data["Plot"],$data["Year"],$data["Language"],$data["Country"],$data["Genre"],$data["Director"],$data["Actors"]);
    //   }
    //   else {
    //     $query  = "INSERT INTO " . self::$table_name;
    //     $query .= " (DateTimeCreated, CreatedByUser, Title)";
    //     $query .= " VALUES";
    //     $query .= " (?, ?, ?)";
    //     // $query .= " SELECT SCOPE_IDENTITY AS id";
    //
    //     $params = array(generate_datetime_for_sql(),$_SESSION["user_id"], $title);
    //   }
    //
    //   $created_movie_id = self::create_by_sql($query, $params);
    //
    //   $movie = Movie::find_by_id($created_movie_id);
    //
    //   return $movie;
    //
    //   //
    //   // {"Title":"Kingsman: The Secret Service",
    //   //   "Year":"2014",
    //   //   "Rated":"R",
    //   //   "Released":"13 Feb 2015",
    //   //   "Runtime":"129 min",
    //   //   "Genre":"Action, Adventure, Comedy",
    //   //   "Director":"Matthew Vaughn",
    //   //   "Writer":"Jane Goldman (screenplay), Matthew Vaughn (screenplay), Mark Millar (comic book \"The Secret Service\"), Dave Gibbons (comic book \"The Secret Service\")",
    //   //   "Actors":"Adrian Quinton, Colin Firth, Mark Strong, Jonno Davies",
    //   //   "Plot":"A spy organization recruits an unrefined, but promising street kid into the agency's ultra-competitive training program, just as a global threat emerges from a twisted tech genius.",
    //   //   "Language":"English, Arabic, Swedish",
    //   //   "Country":"UK, USA",
    //   //   "Awards":"7 wins & 26 nominations.",
    //   //   "Poster":"https://images-na.ssl-images-amazon.com/images/M/MV5BMTkxMjgwMDM4Ml5BMl5BanBnXkFtZTgwMTk3NTIwNDE@._V1_SX300.jpg",
    //   //   "Ratings":[{"Source":"Internet Movie Database","Value":"7.7/10"},
    //   //   {"Source":"Rotten Tomatoes","Value":"74%"},
    //   //   {"Source":"Metacritic","Value":"60/100"}],
    //   //   "Metascore":"60",
    //   //   "imdbRating":"7.7",
    //   //   "imdbVotes":"486,942",
    //   //   "imdbID":"tt2802144",
    //   //   "Type":"movie","DVD":"09 Jun 2015","BoxOffice":"$119,469,511","Production":"20th Century Fox","Website":"http://www.KingsmanMovie.com","Response":"True"}
    //   //
    //   //
    //
    // }
    //
    // // imdb methods
    //
    // public static function get_from_imdb($title) {
    //
    //   $clean_title = Movie::clean_title_for_imdb($title);
    //   $search_term = Movie::get_imdb_url() . $clean_title;
    //   $response = file_get_contents($search_term);
    //   if ($response) {
    //     $data = json_decode($response, TRUE);
    //     if ($data["Response"] == "True") {
    //       $runtime = substr($data["Runtime"], 0, -4);
    //       $imdbvotes = str_replace(",", "", $data["imdbVotes"]);
    //       $data["Runtime"] = $runtime;
    //       $data["imdbVotes"] = $imdbvotes;
    //       return $data;
    //     }
    //     else {
    //       return FALSE;
    //     }
    //   }
    //   else {
    //     return FALSE;
    //   }
    // }
    //
    // public static function clean_title_for_imdb($title) {
    //   $title = str_replace(" ", "+", $title);
    //   $title = str_replace(":", "%3A", $title);
    //   return $title;
    // }
    //
    // public static function get_imdb_url() {
    //   return "http://www.omdbapi.com/?apikey=ce86382d&t=";
    // }
    //
    //
    //
    //
    //
    // public function update($post, $movie_id=0) {
    //
    //   $title = $post["new_title"];
    //   $title = str_replace(" ", "+", $title);
    //   $title = str_replace(":", "%3A", $title);
    //   $search_term = "http://www.omdbapi.com/?apikey=ce86382d&plot=full&t=" . $title;
    //   $response = file_get_contents($search_term);
    //   $data = json_decode($response, TRUE);
    //
    //   $runtime = substr($data["Runtime"], 0, -4);
    //   $imdbvotes = str_replace(",", "", $data["imdbVotes"]);
    //
    //   $query  = "UPDATE " . self::$table_name;
    //   $query .= " SET Title = ?, IMDBID = ?, IMDBRating = ?, RunningTime = ?, IMDBVotes = ?, Plot = ?, ReleasedYear = ?, Language = ?, Country = ?, Genre = ?, Director = ?, Cast = ?";
    //   $query .= " WHERE";
    //   $query .= " MovieID = ?";
    //
    //   $params = array($data["Title"],$data["imdbID"],$data["imdbRating"],$runtime,$imdbvotes, $data["Plot"],$data["Year"], $data["Language"],$data["Country"],$data["Genre"],$data["Director"],$data["Actors"], $movie_id);
    //
    //   $updated_movie = self::update_by_sql($query, $params);
    //   $movie = Movie::find_by_id($movie_id);
    //   $poster = Poster::save($data["Poster"],$data["imdbID"],$movie);
    //   return $updated_movie;
    //
    // }
    //
    // public static function delete($movie_id=0) {
    //
    //   $query  = "DELETE FROM " . self::$table_name;
    //   $query .= " WHERE MovieID = ?";
    //
    //   $params = array($movie_id);
    //
    //   $deleted_movie = self::delete_by_sql($query, $params);
    //   return $deleted_movie;
    //
    // }
    //
    // public static function find_movie_by_title($title) {
    //
    //   // returns a movie if successful, or FALSE if unsuccessful
    //   $query = "SELECT TOP 1 * FROM " . self::$table_name . " WHERE Title = ? ";
    //   $params = array($title);
    //   $movie_array = self::find_by_sql($query, $params);
    //   return (!empty($movie_array)) ? array_shift($movie_array) : FALSE;
    //
    // }
    //
    // public static function find_movie_by_imdbid($imdbid) {
    //
    //   // returns a movie if successful, or FALSE if unsuccessful
    //   $query = "SELECT TOP 1 * FROM " . self::$table_name . " WHERE IMDBID = ? ";
    //   $params = array($imdbid);
    //   $movie_array = self::find_by_sql($query, $params);
    //   return (!empty($movie_array)) ? array_shift($movie_array) : FALSE;
    //
    // }
    //
    // public static function find_movie_set_by_title($title) {
    //   // returns array with the movies that contains title
    //
    //   // want to use contains instead of like, but needs configuring
    //   // like is much slower than contains
    //   // $query .= "WHERE CONTAINS(Title, ?)";
    //   $query  = "SELECT * FROM " . self::$table_name;
    //   $query .= " WHERE Title LIKE ?";
    //   $query .= " ORDER BY Title ASC";
    //
    //   $title = "%" . $title . "%";
    //   $params = array($title);
    //
    //   $movie_set = NULL;
    //   try {
    //     $movie_set = self::find_by_sql($query, $params);
    //   }
    //   catch (exception $e) {
    //     echo "Exception.";
    //     /*
    //     sql_log_errors($e, sqlsrv_errors());
    //     if ($e->getCode() == EXCEPTION_CODE_SQL_CONFIRM_QUERY) {
    //       $_SESSION["message"] .= "Couldn't find any movie with title containing '" . $title . "'.<br />";
    //     }
    //     else {
    //       $_SESSION["error"] .= make_exception_message_to_user($e);
    //     }
    //     */
    //   }
    //   return $movie_set;
    // }
    //
    // public static function find_all_movies() {
    //   // returns array with movies if successful
    //
    //   $query  = "SELECT * FROM Movies";
    //   $query .= " ORDER BY Title ASC";
    //
    //   $params = array();
    //
    //   $movie_set = NULL;
    //   try {
    //     $movie_set = self::find_by_sql($query, $params);
    //   }
    //   catch (exception $e) {
    //     echo "Exception.";
    //     /*
    //     sql_log_errors($e, sqlsrv_errors());
    //     if ($e->getCode() == EXCEPTION_CODE_SQL_CONFIRM_QUERY) {
    //       $_SESSION["message"] .= "Couldn't find any movie with title containing '" . $title . "'.<br />";
    //     }
    //     else {
    //       $_SESSION["error"] .= make_exception_message_to_user($e);
    //     }
    //     */
    //   }
    //   return $movie_set;
    // }
    //
    //
    //
    // public static function search_for_movie($search_term, $page) {
    //
    //
    //   $clean_title = Movie::clean_title_for_imdb($search_term);
    //   $search_term = Movie::get_imdb_url_search() . $clean_title;
    //   $search_term = $search_term . "&page=" . $page;
    //   $response = file_get_contents($search_term);
    //   if ($response) {
    //     $data = json_decode($response, TRUE);
    //     if ($data["Response"] == "True") {
    //       // if ($data["totalResults"] > 1) {
    //       //   foreach ($data["Search"] as $title) {
    //       //     echo $title["Title"];
    //       //   }
    //       // }
    //       // $runtime = substr($data["Runtime"], 0, -4);
    //       // $imdbvotes = str_replace(",", "", $data["imdbVotes"]);
    //       // $data["Runtime"] = $runtime;
    //       // $data["imdbVotes"] = $imdbvotes;
    //       return $data;
    //     }
    //     else {
    //       return FALSE;
    //     }
    //   }
    //   else {
    //     return FALSE;
    //   }
    //
    //   // return '<img src="data:image/jpeg;base64,' . Movie::url_to_image('https://images-na.ssl-images-amazon.com/images/M/MV5BN2EyZjM3NzUtNWUzMi00MTgxLWI0NTctMzY4M2VlOTdjZWRiXkEyXkFqcGdeQXVyNDUzOTQ5MjY@._V1_SX300.jpg') .'" height="400">';
    //
    //
    //   // return TRUE;
    //   // success
    //     // {"Search":
    //     //   [
    //     //     {"Title":"The Lord of the Rings: The Fellowship of the Ring","Year":"2001","imdbID":"tt0120737","Type":"movie","Poster":"https://images-na.ssl-images-amazon.com/images/M/MV5BN2EyZjM3NzUtNWUzMi00MTgxLWI0NTctMzY4M2VlOTdjZWRiXkEyXkFqcGdeQXVyNDUzOTQ5MjY@._V1_SX300.jpg"},
    //     //     {"Title":"The Lord of the Rings: The Return of the King","Year":"2003","imdbID":"tt0167260","Type":"movie","Poster":"https://images-na.ssl-images-amazon.com/images/M/MV5BYWY1ZWQ5YjMtMDE0MS00NWIzLWE1M2YtODYzYTk2OTNlYWZmXkEyXkFqcGdeQXVyNDUyOTg3Njg@._V1_SX300.jpg"},
    //     //     {"Title":"The Lord of the Rings: The Two Towers","Year":"2002","imdbID":"tt0167261","Type":"movie","Poster":"https://images-na.ssl-images-amazon.com/images/M/MV5BMDY0NmI4ZjctN2VhZS00YzExLTkyZGItMTJhOTU5NTg4MDU4XkEyXkFqcGdeQXVyNjU0OTQ0OTY@._V1_SX300.jpg"},
    //     //     {"Title":"The Lord of the Rings","Year":"1978","imdbID":"tt0077869","Type":"movie","Poster":"https://images-na.ssl-images-amazon.com/images/M/MV5BOGMyNWJhZmYtNGQxYi00Y2ZjLWJmNjktNTgzZWJjOTg4YjM3L2ltYWdlXkEyXkFqcGdeQXVyNTAyODkwOQ@@._V1_SX300.jpg"},{"Title":"The Lord of the Rings: The Two Towers","Year":"2002","imdbID":"tt0347436","Type":"game","Poster":"https://images-na.ssl-images-amazon.com/images/M/MV5BODI0Mzk3OTM4N15BMl5BanBnXkFtZTgwMTM4MTk4MDE@._V1_SX300.jpg"},
    //     //     {"Title":"The Lord of the Rings: The Return of the King","Year":"2003","imdbID":"tt0387360","Type":"game","Poster":"https://images-na.ssl-images-amazon.com/images/M/MV5BMjE5NTQwMTY5MV5BMl5BanBnXkFtZTgwODcwNjUwMTE@._V1_SX300.jpg"},{"Title":"The Lord of the Rings: The Battle for Middle-Earth","Year":"2004","imdbID":"tt0412935","Type":"game","Poster":"http://ia.media-imdb.com/images/M/MV5BMTY0NTE4NjgzMV5BMl5BanBnXkFtZTcwNzI2MzcyMQ@@._V1_SX300.jpg"},
    //     //     {"Title":"The Lord of the Rings: The Battle for Middle-Earth II","Year":"2006","imdbID":"tt0760172","Type":"game","Poster":"http://ia.media-imdb.com/images/M/MV5BMTkyMzk4MDkzOF5BMl5BanBnXkFtZTcwMjYwNjIzMQ@@._V1_SX300.jpg"},{"Title":"The Lord of the Rings: The Battle for Middle-earth II - The Rise of the Witch-king","Year":"2006","imdbID":"tt1058040","Type":"game","Poster":"http://ia.media-imdb.com/images/M/MV5BMjYwMDIxNjg3MV5BMl5BanBnXkFtZTgwMTk5MTE4MDE@._V1_SX300.jpg"},
    //     //     {"Title":"The Lord of the Rings: The Third Age","Year":"2004","imdbID":"tt0415947","Type":"game","Poster":"http://ia.media-imdb.com/images/M/MV5BMTMwMzM2NzU1M15BMl5BanBnXkFtZTcwOTQ3MzcyMQ@@._V1_SX300.jpg"}
    //     //   ],
    //     //   "totalResults":"43","Response":"True"
    //     // }
    //
    //   // failure
    //     // {"Response":"False","Error":"Movie not found!"}
    //     // {"Response":"False","Error":"Invalid API key!"}
    //
    //
    //
    // }
    //
    // public static function url_to_image($url) {
    //
    //   if ($url == "N/A") {
    //     return FALSE;
    //   }
    //   try {
    //
    //     $arrContextOptions=array(
    //         "ssl"=>array(
    //             "cafile" => "../../../Certificates/cacert.pem",
    //             "verify_peer"=> true,
    //             "verify_peer_name"=> true,
    //         ),
    //     );
    //
    //     $image = $url;
    //     $imageData = base64_encode(file_get_contents($image, false, stream_context_create($arrContextOptions)));
    //
    //     return $imageData;
    //   }
    //   catch (Exception $e) {
    //     return FALSE;
    //   }
    // }
    //
    // public static function get_from_imdb_search($title) {
    //
    //   $clean_title = Movie::clean_title_for_imdb_search($title);
    //   $search_term = Movie::get_imdb_url_search() . $clean_title;
    //   $response = file_get_contents($search_term);
    //   if ($response) {
    //     $data = json_decode($response, TRUE);
    //     if ($data["Response"] == "True") {
    //       $runtime = substr($data["Runtime"], 0, -4);
    //       $imdbvotes = str_replace(",", "", $data["imdbVotes"]);
    //       $data["Runtime"] = $runtime;
    //       $data["imdbVotes"] = $imdbvotes;
    //       return $data;
    //     }
    //     else {
    //       return FALSE;
    //     }
    //   }
    //   else {
    //     return FALSE;
    //   }
    // }
    //
    // public static function clean_title_for_imdb_search($title) {
    //   $title = str_replace(" ", "+", $title);
    //   $title = str_replace(":", "%3A", $title);
    //   return $title;
    // }
    //
    // public static function get_imdb_url_search() {
    //   return "http://www.omdbapi.com/?apikey=ce86382d&type=movie&s=";
    // }
    //
    //
    //
    //
    // public static function get_from_imdb_with_imdbid($imdbid) {
    //
    //   $search_term = Movie::get_imdb_url_imdbid() . $imdbid;
    //   $response = file_get_contents($search_term);
    //   if ($response) {
    //     $data = json_decode($response, TRUE);
    //     if ($data["Response"] == "True") {
    //       $runtime = substr($data["Runtime"], 0, -4);
    //       $imdbvotes = str_replace(",", "", $data["imdbVotes"]);
    //       $data["Runtime"] = $runtime;
    //       $data["imdbVotes"] = $imdbvotes;
    //       $movie = Movie::create_object($data);
    //       return $movie;
    //     }
    //     else {
    //       return FALSE;
    //     }
    //   }
    //   else {
    //     return FALSE;
    //   }
    // }
    //
    // public static function clean_title_for_imdb_imdbid($title) {
    //   $title = str_replace(" ", "+", $title);
    //   $title = str_replace(":", "%3A", $title);
    //   return $title;
    // }
    //
    // public static function get_imdb_url_imdbid() {
    //   return "http://www.omdbapi.com/?apikey=ce86382d&plot=full&i=";
    // }
    //
    // public static function create_object($data) {
    //   $movie = new Movie;
    //
    //   $movie->MovieID = FALSE;
    //   $movie->DateTimeCreated = FALSE;
    //   $movie->CreatedByUser = FALSE;
    //   $movie->Title = $data["Title"];
    //   $movie->IMDBID = $data["imdbID"];
    //   $movie->IMDBRating = $data["imdbRating"];
    //   $movie->RunningTime = $data["Runtime"];
    //   $movie->IMDBVotes = $data["imdbVotes"];
    //   $movie->Plot = $data["Plot"];
    //   $movie->ReleasedYear = $data["Year"];
    //   $movie->Language = $data["Language"];
    //   $movie->Country = $data["Country"];
    //   $movie->Genre = $data["Genre"];
    //   $movie->Director = $data["Director"];
    //   $movie->Cast = $data["Actors"];
    //
    //   return $movie;
    // }
    //

?>
