<?php
include("../private/initialize.php");
$layout_files_to_load = load_layout("standard");

include($layout_files_to_load["header"]);
include($layout_files_to_load["sidebar_left_back_browse"]);

if (!($session->is_logged_in() && $session->is_session_valid())) {
  redirect_to("login.php");
}
echo $session->session_message();
?>


<?php

if (isset($_POST["update_movie_in_local_db"])) {

  $movie = Movie::find_by_id($_GET["movieID"]);
  $updated_movie = $movie->update($movie->get_movieid());
  if ($updated_movie) {
    $poster = Poster::save($updated_movie->get_posterurl(),$updated_movie->get_imdbid(),$updated_movie);
    $_SESSION["message"] .= "The movie '" . $updated_movie->get_title() . "' was updated in the local db.";
  }
  else {
    $_SESSION["message"] .= "Something went wrong. Movie was not updated.";
  }
  redirect_to("movie_info.php?movieID=" . $movie->get_movieid());
}

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

    $output  = "<a href=\"http://www.imdb.com/title/" . $movie->get_imdbid() . "\">";
    $output .= "<h2>".$movie->get_title() . " (" . $movie->get_releasedyear() . ")</h2>";

    $local_db_img = base64_encode(file_get_contents(SITEIMAGE_PATH.DS."database_local01.jpg"));

    $output .= "<image style=\"float:right\" src=\"data:image/jpg;base64," . $local_db_img . "\" height=\"100px\">";

    $output .= "</a>";
    $output .= "<table border=0>";
    $output .= "<tr>";
    $output .= "</tr>";
    $output .= "<tr>";
    $output .= "<td><a href=\"http://www.imdb.com/title/" . $movie->get_imdbid() . "\">";
    $output .= "<image src=\"data:image/jpg;base64," . $poster_encoded . "\" height=\"500px\" title=\"" . $mouseovertitle . "\" />"; //onerror=\"this.src='data:image/jpg;base64," . Movie::no_poster() . "'\"
    $output .= "</a></td>";
    $output .= "<td>";
    $output .= "<h4>Plot Summary: </h4>".$movie->get_plotsummary()."<br /><br />";
    $output .= "Year: ".$movie->get_releasedyear()."<br />";
    $output .= "IMDB Rating: ".$movie->get_imdbrating()."/10<br />";
    $output .= "Votes: ".$movie->get_imdbvotes()."<br />";
    $output .= "Runtime: ".$movie->get_runningtime()." min (".$movie->get_runningtime_hours()[0]."h ".$movie->get_runningtime_hours()[1]."min)<br />";
    $output .= "Language ".$movie->get_language()."<br /><br />";
    $output .= "Country: ".$movie->get_country()."<br />";
    $output .= "Genre: ".$movie->get_genre()."<br />";
    $output .= "Director: ".$movie->get_director()."<br /><br />";
    $output .= "<h4>Plot: </h4>".$movie->get_plot()."<br /><br />";
    $output .= "<h4>Cast:</h4> ".$movie->get_cast()."<br /><br /><br />";
    $output .= "</td>";
    $output .= "</tr>";
    $output .= "</table>";

    $actions  = "<ul class=\"form\">";
    $actions .= "<li class=\"form\">";
    $actions .= "<div>";
    if (1) {
      $actions .= "<form action=\"movie_info.php?movieID=" . $movie->get_movieid() . "\" method=\"POST\">";
      $actions .= "<input type=\"submit\" name=\"update_movie_in_local_db\" value=\"Update movie\" />";
      $actions .= "</form>";
    }
    $actions .= "</div>";
    $actions .= "</li>";
    $actions .= "<li class=\"form\">";
    $actions .= "<div>";
    $actions .= "<a href=\"delete_movie.php?movieID=";
    $actions .= $movie->get_movieid();
    $actions .= "\">";
    $actions .= "Delete";
    $actions .= "</a>";
    $actions .= "</div>";
    $actions .= "</li>";
    $actions .= "</ul>";
  }
}
else {
  redirect_to("browse_movies.php");
}

?>


<?php
echo $output;
echo "<hr />";
echo $actions;
?>



<?php
include($layout_files_to_load["sidebar_right"]);
include($layout_files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
