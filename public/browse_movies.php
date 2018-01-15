<?php
include("../private/initialize.php");
$files_to_load = load_layout("standard");

include($files_to_load["header"]);
include($files_to_load["sidebar_left"]);

if (!($session->is_logged_in() && $session->is_session_valid())) {
  redirect_to("login.php");
}
echo $session->session_message();
?>


<?php
// if ($_SESSION["movie_set"]) {
//   // have some list of movies to show
// }
// else {
//   // list all movies
//
// }
$movies = Movie::find_all_movies();
$found_movies = FALSE;

$output  = "<ul class=\"movies\">";
foreach ($movies as $movie) {
  $found_movies = TRUE;
  $output .= "<li class=\"movies\">";
  $output .= "<div>";
  $output .= "<a href=\"movie_info.php?movieID=";
  $output .= $movie->get_movieid();
  $output .= "\">";
  $output .= $movie->get_title();
  $output .= "</a></div></li>";
}
$output .= " </ul>";

$output .= "<hr />";
$output .= "<a href=\"new_movie.php\">New movie</a>";

if ($found_movies) {

}
else {
  $output  = "<ul class=\"movies\">";
  $output .= "<li class=\"movies\">";
  $output .= "<i>No movies found.</i>";
  $output .= "</li></ul>";
}
echo $output;



?>


<?php
include($files_to_load["sidebar_right"]);
include($files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
