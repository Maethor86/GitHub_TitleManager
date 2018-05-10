<?php
include("../private/initialize.php");
$layout_files_to_load = load_layout("standard");

// ***
$_SESSION["subject_id"] = NULL;
$_SESSION["page_id"] = NULL;

include($layout_files_to_load["header"]);
include($layout_files_to_load["sidebar_left_back"]);
include($layout_files_to_load["sidebar_right"]);

if (!($session->is_logged_in() && $session->is_session_valid())) {
  redirect_to("login.php");
}
?>


<?php

if (isset($_POST["update_movie_in_local_db"])) {

  $movie = Movie::find_by_id($_GET["movieID"]);
  $updated_movie = $movie->update($movie->get_movieid());
  if ($updated_movie) {
    $poster = Poster::save($updated_movie->get_posterurl(),$updated_movie->get_posterfilename(),$updated_movie);
    $_SESSION["message"] .= "The movie '" . $updated_movie->get_title() . "' was updated in the local db.";
  }
  else {
    $_SESSION["message"] .= "Something went wrong. Movie was not updated.";
  }
  redirect_to("browse_movies.php");
}

if (isset($_POST["update_movie_options"])) {

  if (isset($_POST["new_loaner"]) && !(empty($_POST["new_loaner"]))) {
    $loaner_id = Loaner::create($_POST["new_loaner"])->get_loanerid();
  }
  elseif (isset($_POST["loaners"]) && !(empty($_POST["loaners"]))) {
    $loaner_id = $_POST["loaners"];
  }
  else {
    $loaner_id = FALSE;
  }
  $movie = Movie::find_by_id($_GET["movieID"]);
  $updated_movie = $movie->update_movieoptions($movie->get_movieid(),$_POST["status"],$_POST["quality"], $loaner_id);
  if ($updated_movie) {
    $_SESSION["message"] .= "Options for the movie '" . $updated_movie->get_title() . "' was updated in the local db.";
  }
  else {
    $_SESSION["message"] .= "Something went wrong. Movie was not updated.";
  }
  redirect_to("browse_movies.php");
}

$output = "";
if (isset($_GET["movieID"])) {
  $movie = Movie::find_by_id($_GET["movieID"]);
  $poster = Poster::find_poster_by_movieid($movie->get_movieid());
  if ($movie) {
    if ($poster) {
      $poster_encoded = Poster::encode_poster($poster->get_filename(),$poster->get_type());
      $mouseovertitle = $poster->get_mouseovertitle();
    }
    else {
      $poster_encoded = Poster::encode_poster();
      $mouseovertitle = $movie->get_title();
    }
    $current_moviestatus = Moviestatus::find_by_id($movie->get_moviestatusid());
    $moviequality = $movie->get_moviequalityid();
    switch ($moviequality) {
      case '1':
        $icon = "blu_ray-logo-transparent";
        $icon_type = "png";
        break;
      case '2':
        $icon = "dvd-logo-transparent";
        $icon_type = "png";
        break;

      default:
        $icon = "unknown_quality-logo";
        $icon_type = "jpg";
        break;
    }

    $output .= "<div class=\"container-fluid\">";
    $output .= "<div class=\"row\">";
    $output .= "<div class=\"col-sm-6\">";
    $output .= "<div class=\"card h-100\">";

    $output .= "<div class=\"card-header\">";
    $output .= "<a class=\"text-dark\" style=\"text-decoration: none\" href=\"http://www.imdb.com/title/" . $movie->get_imdbid() . "\">";
    $output .= "<h5 class=\"card-title text-center\">".$movie->get_title()."</h5>";
    $output .= "</a>";
    $output .= "</div>";

    $output .= "<img class=\"card-img-top\" src=\"data:image/jpg;base64," . $poster_encoded . "\" height=\"\" title=\"" . $mouseovertitle . "\" />"; //onerror=\"this.src='data:image/jpg;base64," . Movie::no_poster() . "'\"

    $output .= "<div class=\"card-body\">";
    $output .= "<h6 class=\"card-subtitle mb-2 text-muted\">";
    $output .= $movie->get_releasedyear();
    $output .= "<img class=\"float-right\" src=\"data:image/jpeg;base64,".Poster::encode_icon($icon,$icon_type)."\" style=\"width: 15%;\">";
    $output .= "<small class=\"font-italic\">";
    $output .= "<br />Last updated ".generate_datetime_diff(new TMDateTime($movie->get_datetimelastmodified()));
    $output .= "</small>";
    $output .= "</h6>";
    $output .= "</div>";

    $output .= "<div class=\"card-footer text-muted text-center\">";
    $output .= Moviestatus::find_by_id($movie->get_moviestatusid())->get_description();
    $output .= "</div>";

    $output .= "</div>";
    $output .= "</div>";

    $output .= "<div class=\"col-sm-6\">";
    $output .= "<div class=\"card h-100\">";

    $output .= "<div class=\"card-header\">";
    $output .= "<ul class=\"nav nav-tabs card-header-tabs\">";
    // $output .= "<li class=\"active\">";
    // $output .= "<a class=\"\" data-toggle=\"tab\" href=\"#about1\">About</a>";
    // $output .= "</li>";
    $output .= "<li class=\"nav-item\">";
    $output .= "<a class=\"nav-link active\" data-toggle=\"tab\" href=\"#about1\">About</a>";
    $output .= "</li>";
    $output .= "<li class=\"nav-item\">";
    $output .= "<a class=\"nav-link\" data-toggle=\"tab\" href=\"#keyinfo1\">Key info</a>";
    $output .= "</li>";
    // $output .= "<li class=\"\">";
    // $output .= "<a class=\"\" data-toggle=\"tab\" href=\"#keyinfo1\">Key info</a>";
    // $output .= "</li>";
    $output .= "</ul>";
    $output .= "</div>";

    $output .= "<div class=\"card-body\">";

    $output .= "<div class=\"tab-content\">";
    $output .= "<div id=\"about1\" class=\"tab-pane fade show active\">";
    $output .= "<h6>About</h6>";
    $output .= "Added to library: ". generate_datetime_diff(new TMDateTime($movie->get_datetimecreated())) . " by " . User::find_by_id($movie->get_createdbyuser())->get_username() . ". <br />";
    $output .= "Last updated: ". generate_datetime_diff(new TMDateTime($movie->get_datetimelastmodified())) . " by " . User::find_by_id($movie->get_createdbyuser())->get_username() . ". <br /><br />";
    $output .= "<h6>Status</h6>";
    $output .= $current_moviestatus->get_description();
    if ($current_moviestatus->get_moviestatusid() == Moviestatus::get_loanedoutmoviestatusid()) {
      $loaner = Loaner::find_by_id(Movieloan::find_by_movieid($movie->get_movieid())->get_loanerid());
      $output .= " to ";
      $output .= $loaner->get_description().".";
    }
    $output .= "</div>";
    $output .= "<div id=\"keyinfo1\" class=\"tab-pane fade\">";
    $output .= "<h6>Plot Summary</h6>".$movie->get_plotsummary()."<br /><br />";
    $output .= "<h6>Key info</h6>";
    $output .= "Year: ".$movie->get_releasedyear()."<br />";
    $output .= "IMDB Rating: ".$movie->get_imdbrating()."/10<br />";
    $output .= "Votes: ".$movie->get_imdbvotes()."<br />";
    $output .= "Runtime: ".$movie->get_runningtime()." min (".$movie->get_runningtime_hours()[0]."h ".$movie->get_runningtime_hours()[1]."min)<br />";
    $output .= "Language: ".$movie->get_language()."<br /><br />";
    $output .= "Country: ".$movie->get_country()."<br />";
    $output .= "Genre: ".$movie->get_genre()."<br />";
    $output .= "Director: ".$movie->get_director()."<br /><br />";
    $output .= "</div>";
    $output .= "</div>";

    $output .= "</div>";

    // $output .= "<img src=\"data:image/jpeg;base64," . Poster::encode_icon($icon,$icon_type) ."\" height=\"30px\" class=\"icon\">";  // ***

    // $output .= "<td><a href=\"http://www.imdb.com/title/" . $movie->get_imdbid() . "\">";
    // $output .= "<image src=\"data:image/jpg;base64," . $poster_encoded . "\" height=\"500px\" class=\"poster\" title=\"" . $mouseovertitle . "\" />"; //onerror=\"this.src='data:image/jpg;base64," . Movie::no_poster() . "'\"
    // $output .= "<img src=\"data:image/jpeg;base64," . Poster::encode_icon($icon,$icon_type) ."\" height=\"30px\" class=\"icon\">";  // ***
    // $output .= "</a></td>";
    // $output .= "<td>";
    // $output .= "<h4>About</h4>";
    // $output .= "Added to library: ". generate_datetime_diff(new TMDateTime($movie->get_datetimecreated())) . " by " . User::find_by_id($movie->get_createdbyuser())->get_username() . ". <br />";
    // $output .= "Last updated: ". generate_datetime_diff(new TMDateTime($movie->get_datetimelastmodified())) . " by " . User::find_by_id($movie->get_createdbyuser())->get_username() . ". <br />";
    // $output .= "Status: ". $current_moviestatus->get_description();
    // if ($current_moviestatus->get_moviestatusid() == Moviestatus::get_loanedoutmoviestatusid()) {
    //   $loaner = Loaner::find_by_id(Movieloan::find_by_movieid($movie->get_movieid())->get_loanerid());
    //   $output .= " to ";
    //   $output .= $loaner->get_description();
    // }
    // $output .= "<br />";
    // $output .= "Quality: ".Moviequality::find_by_id($moviequality)->get_description()."<br />";
    // $output .= "<h4>Plot Summary </h4>".$movie->get_plotsummary()."<br /><br />";
    // $output .= "Year: ".$movie->get_releasedyear()."<br />";
    // $output .= "IMDB Rating: ".$movie->get_imdbrating()."/10<br />";
    // $output .= "Votes: ".$movie->get_imdbvotes()."<br />";
    // $output .= "Runtime: ".$movie->get_runningtime()." min (".$movie->get_runningtime_hours()[0]."h ".$movie->get_runningtime_hours()[1]."min)<br />";
    // $output .= "Language: ".$movie->get_language()."<br /><br />";
    // $output .= "Country: ".$movie->get_country()."<br />";
    // $output .= "Genre: ".$movie->get_genre()."<br />";
    // $output .= "Director: ".$movie->get_director()."<br /><br />";

    $output .= "<div class=\"card-footer text-muted text-center\">";
    $output .= Moviestatus::find_by_id($movie->get_moviestatusid())->get_description();
    $output .= "</div>";

    $output .= "</div>";
    $output .= "</div>";
    $output .= "</div>";
    $output .= "</div>";

    $local_db_img_encoded = base64_encode(file_get_contents(SITEIMAGE_PATH.DS."database_local01.jpg"));

    $local_db_img  = "<image style=\"float:right\" src=\"data:image/jpg;base64," . $local_db_img_encoded . "\" height=\"100px\">";

    $update_from_imdb  = "<form action=\"movie_info.php?movieID=" . $movie->get_movieid() . "\" method=\"POST\" style=\"clear:both; float:right\">";
    $update_from_imdb .= "<input type=\"submit\" name=\"update_movie_in_local_db\" value=\"Update from IMDB\" />";
    $update_from_imdb .= "</form>";
    $actions  = "<a href=\"delete_movie.php?movieID=";
    $actions .= $movie->get_movieid();
    $actions .= "\" style=\"clear:both; float:right\">";
    $actions .= "Delete";
    $actions .= "</a>";

    $info_moviestatus  = "Status &nbsp;";
    $info_moviestatus .= "<select name=\"status\" id=\"status\" />";
    $moviestatuses = Moviestatus::find_all();
    $info_moviestatus .= "<option value=\"0\" hidden>" . $current_moviestatus->get_description() . "</option>";
    foreach ($moviestatuses as $moviestatus) {
      if (!($current_moviestatus->get_moviestatusid() == $moviestatus->get_moviestatusid())) {
        $info_moviestatus .= "<option value=\"" . $moviestatus->get_moviestatusid() . "\" >" . $moviestatus->get_description() . "</option>";
      }
    }
    $info_moviestatus .= "</select>";

    $info_loan  = "<select name=\"loaners\" id=\"loaners\" class=\"hidden\" />";
    $loaners = Loaner::find_all();
    $info_loan .= "<option value=\"\" hidden>Please choose...</option>";
    foreach ($loaners as $loaner) {
      $info_loan .= "<option value=\"" . $loaner->get_loanerid() . "\">" . $loaner->get_description() . "</option>";
    }
    $info_loan .= "<option value=\"\" disabled>---</option>";
    $info_loan .= "<option value=\"add_loaner\">Add...</option>";
    $info_loan .= "</select>";
    $info_loan .= "<input type=\"text\" name=\"new_loaner\" id=\"new_loaner\" class=\"hidden\" placeholder=\"Type new loaner here...\"/>";
    $info_loan .= "<script type=\"text/javascript\" src=\"javascripts/loaners.js\" ></script>";

    $current_moviequality = Moviequality::find_by_id($movie->get_moviequalityid());
    $info_moviequality  = "Quality &nbsp;";
    $info_moviequality .= "<select name=\"quality\"/>";
    $moviequalities = Moviequality::find_all();
    foreach ($moviequalities as $moviequality) {
      $info_moviequality .= "<option value=\"" . $moviequality->get_moviequalityid() . "\" ";
      if ($current_moviequality->get_moviequalityid() == $moviequality->get_moviequalityid()) {
        $info_moviequality .= "selected=\"selected\"";
      }
      $info_moviequality .= ">" . $moviequality->get_description() . "</option>";
    }
    $info_moviequality .= "</select>";
    $info_moviequality .= "";

    $form_update_options  = "<form action=\"movie_info.php?movieID=" . $movie->get_movieid() . "\" method=\"POST\">";
    $form_update_options .= $info_moviestatus;
    $form_update_options .= $info_loan;
    $form_update_options .= " &nbsp; | &nbsp;";
    $form_update_options .= $info_moviequality;
    $form_update_options .= " &nbsp; | &nbsp;";
    $form_update_options .= "<input type=\"submit\" name=\"update_movie_options\" value=\"Update\" />";
    $form_update_options .= "</form>";


  }
}
else {
  redirect_to("browse_movies.php");
}

?>


<?php
echo $session->session_message();

echo "<script type=\"text/javascript\" src=\"http://code.jquery.com/jquery-latest.min.js\"></script>";
echo $output;
echo "<hr />";
// echo $local_db_img;
// echo $update_from_imdb;
// echo $actions;
// echo $form_update_options;
// echo "<ul style=\"float: right\">";
// echo "<li class=\"form\">";
// echo $info_moviestatus;
// echo "<div>|</div>";
// echo $info_moviequality;
// echo "</li>";
// echo "</ul>";

?>



<?php
include($layout_files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
