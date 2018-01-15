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


  $movie_id = $_GET["movieID"];
  $movie = Movie::find_by_id($movie_id);

  if ($movie) {
    $output  = "<ul class=\"form\">";
    $output .= "<li class=\"form\">";
    $output .= "<div>Title:</div><div><i>".$movie->get_title()."</i></div>";
    $output .= "</li>";
    $output .= "<li class=\"form\">";
    $output .= "<div>Title created:</div><div><i>".$movie->get_datetimecreated()."</i></div>";
    $output .= "</li>";
    $output .= "<li class=\"form\">";
    $output .= "<div>";
    $output .= "<a href=\"edit_movie.php?movieID=";
    $output .= $movie->get_movieid();
    $output .= "\">";
    $output .= "Edit";
    $output .= "</a>";
    $output .= "</div>";
    $output .= "</li>";
    $output .= "<li class=\"form\">";
    $output .= "<div>";
    $output .= "<a href=\"delete_movie.php?movieID=";
    $output .= $movie->get_movieid();
    $output .= "\">";
    $output .= "Delete";
    $output .= "</a>";
    $output .= "</div>";
    $output .= "</li>";
    $output .= "</ul>";
    echo $output;
  }


?>


<?php
include($files_to_load["sidebar_right"]);
include($files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
