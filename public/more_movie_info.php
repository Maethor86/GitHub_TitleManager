<?php

include("../private/initialize.php");
$layout_files_to_load = load_layout("standard");

include($layout_files_to_load["header"]);
include($layout_files_to_load["sidebar_left_back"]);

if (!($session->is_logged_in() && $session->is_session_valid())) {
  redirect_to("login.php");
}
// echo $session->session_message();
// echo make_page_title("Movie Info");
?>

<?php

if (isset($_POST["add_movie_to_local_db"])) {

  $movie = Movie::create_from_imdbid($_GET["imdbID"]);
  if ($movie) {
    $poster = Poster::save($movie->get_posterurl(),$_GET["imdbID"],$movie);
    $_SESSION["message"] .= "The movie '" . $movie->get_title() . "' was added to the local db.";
    echo "<script language=javascript>window.history.go(-2);</script>";
  }
  else {
    $_SESSION["message"] .= "Something went wrong. Movie was not added to the local db.";
  }
}

$output = "";
$actions = "";

if (isset($_GET["imdbID"])) {

  // check if imdb is valid
  // if (valid_imdbid) {
    $movie = Movie::search_extdb_imdbid($_GET["imdbID"]);
    if ($movie) {
      $in_local_db = Movie::find_movie_by_imdbid($movie->get_imdbid());
      // $no_poster = Movie::no_poster();

      $output .= "<a href=\"http://www.imdb.com/title/" . $movie->get_imdbid() . "\">";
      $output .= "<h2>".$movie->get_title() . " (" . $movie->get_releasedyear() . ")</h2>";
      $output .= "</a>";

      $external_db_img = base64_encode(file_get_contents(SITEIMAGE_PATH.DS."database_external01.jpg"));

      $output .= "<image style=\"float:right\" src=\"data:image/jpg;base64," . $external_db_img . "\" height=\"100px\">";

      $output .= "<table border=0>";
      $output .= "<tr>";
      $output .= "</tr>";
      $output .= "<tr>";
      $output .= "<td><a href=\"http://www.imdb.com/title/" . $movie->get_imdbid() . "\">";
      $output .= "<img src=\"data:image/jpeg;base64," . Movie::url_to_image($movie->get_posterurl()) ."\" height=\"500\" title=\"" . $movie->get_title() . "\" >"; // onerror=\"this.src='data:image/jpg;base64," . $no_poster . "'\"
      $output .= "</a></td>";
      $output .= "<td>";
      $output .= "<h4>Plot Summary: </h4>".$movie->get_plotsummary()."<br /><br />";
      $output .= "Year: ".$movie->get_releasedyear()."<br />";
      $output .= "IMDB Rating: ".$movie->get_imdbrating()."/10<br />";
      $output .= "Votes: ".$movie->get_imdbvotes()."<br />";
      $output .= "Runtime: ".$movie->get_runningtime()." min (".$movie->get_runningtime_hours()[0]."h ".$movie->get_runningtime_hours()[1]."min)<br />";
      $output .= "Language: ".$movie->get_language()."<br /><br />";
      $output .= "Country: ".$movie->get_country()."<br />";
      $output .= "Genre: ".$movie->get_genre()."<br />";
      $output .= "Director: ".$movie->get_director()."<br /><br />";
      $output .= "<h4>Plot: </h4>".$movie->get_plot()."<br /><br />";
      $output .= "<h4>Cast:</h4> ".$movie->get_cast()."<br /><br /><br />";
      $output .= "</td>";
      $output .= "</tr>";
      $output .= "</table>";

      $actions .= "<i style=\"color:darkred\">In local DB: ";
      if ($in_local_db) {
        $actions .= "Yes";
      }
      else {
        $actions .= "No";
      }
      $actions .= "</i> ";
      if (!$in_local_db) {
        $actions .= "<form action=\"more_movie_info.php?imdbID=" . $_GET["imdbID"] . "\" method=\"POST\">";
        $actions .= "<input type=\"submit\" name=\"add_movie_to_local_db\" value=\"Add to local db\" />";
        $actions .= "</form>";
      }
    }

  // }

}
else {
  redirect_to("new_movie.php");
}



?>

<?php
echo $session->session_message();
echo make_page_title("Movie Info");
echo $output;
echo "<hr />";
echo $actions;


?>

<?php
include($layout_files_to_load["sidebar_right"]);
include($layout_files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
