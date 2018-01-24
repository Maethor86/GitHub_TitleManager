<?php
include("../private/initialize.php");
$layout_files_to_load = load_layout("standard");

include($layout_files_to_load["header"]);
include($layout_files_to_load["sidebar_left"]);

if (!($session->is_logged_in() && $session->is_session_valid())) {
  redirect_to("login.php");
}
echo $session->session_message();
echo make_page_title("Browse Movies");
?>


<?php

$movies = Movie::find_all_movies();
$found_movies = FALSE;

$output  = "Movies in the database:";
$output .= "<ul class=\"movies\">";
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

// $output .= "<hr />";
// $output .= "<a href=\"new_movie.php\">Add new movie</a>";

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
include($layout_files_to_load["sidebar_right"]);
include($layout_files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
