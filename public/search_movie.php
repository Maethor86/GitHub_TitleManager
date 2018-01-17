<?php
include("../private/initialize.php");
$layout_files_to_load = load_layout("standard");
$content_files_to_load = load_contents("standard");

if (!($session->is_logged_in() && $session->is_session_valid())) {
  redirect_to("login.php");
}
?>

<?php
include($layout_files_to_load["header"]);
include($layout_files_to_load["sidebar_left"]);
echo $session->session_message();
include($content_files_to_load["title"]);
?>

<?php

$message = "";
$results = "";
if (isset($_POST["search"])) {
  $title = trim($_POST["title"]);

  $movie_set = Movie::find_movie_set_by_title($title);

  if (!empty($movie_set)) {
    // found movie
    $results  = "<br />Results:";
    $results .= "<ul class=\"movies\">";
    foreach ($movie_set as $movie) {
      // $found_movies = TRUE;
      $results .= "<li class=\"movies\">";
      $results .= "<div>";
      $results .= "<a href=\"movie_info.php?movieID=";
      $results .= $movie->get_movieid();
      $results .= "\">";
      $results .= $movie->get_title();
      $results .= "</a></div></li>";
    }
    $message .= " </ul>";
  }
  else {
    // didnt find movie
    $message = "Couldn't find any movies containing " . $title . ".";
  }

}
else {
  $title = "";
  $message = "Search results will appear here.";
}

$output  = "<form action=\"search_movie.php\" method=\"post\">";
$output .= "<ul class=\"form\">";
$output .= "Please enter title:";
$output .= "<li class=\"form\">";
$output .= "<div><input type=\"text\" name=\"title\" value=$title ></div></li>";
$output .= "<li class=\"form\">";
$output .= "<div><input type=\"submit\" name=\"search\" value=\"Search\" /></div></li>";
$output .= "</ul></form>";

$output .= "<a href=\"browse_movies.php\">Browse all movies</a>";

echo $output;
echo "<hr />";
echo $message;
echo $results;


?>


<?php
include($layout_files_to_load["sidebar_right"]);
include($layout_files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
