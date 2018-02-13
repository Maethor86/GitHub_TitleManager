<?php
include("../private/initialize.php");
$layout_files_to_load = load_layout("standard");

include($layout_files_to_load["header"]);
include($layout_files_to_load["sidebar_left_back"]);

if (!($session->is_logged_in() && $session->is_session_valid())) {
  redirect_to("login.php");
}
echo $session->session_message();
?>

<?php

$movie_id = $_GET["movieID"];
$movie = Movie::find_by_id($movie_id);
if ($movie) {
  $current_title = $movie->get_title();
}
else {
  // i dont think this can ever happen
  $_SESSION["message"] .= "Couldn't find movie.<br />";
  redirect_to("browse_movies.php");
}



$new_title = "";
if (isset($_POST["edit_movie"])) {
  $new_title = $_POST["new_title"];
  if (isset($_POST["new_title"])) {
    $fields_required = array("new_title");
    $errors = field_validation($_POST, $fields_required, $errors);
  }
  if (isset($_POST["new_imdbrating"])) {
    $fields_required = array("new_title");
    $errors = field_validation($_POST, $fields_required, $errors);
  }
  if (isset($_POST["new_runningtime"])) {
    $fields_required = array("new_title");
    $errors = field_validation($_POST, $fields_required, $errors);
  }

  if (empty($errors)) {
    $movie = Movie::find_by_id($movie_id);
    if ($movie) {
      $edited_movie = $movie->update($_POST, $movie_id);
    }
    else {
      // i dont think this can ever happen
      $_SESSION["message"] .= "Couldn't find movie.<br />";
    }
    if ($edited_movie) {
      $_SESSION["message"] .= "Movie edited.";
    }
    else {
      $_SESSION["message"] .= "Movie not edited.";
    }
    redirect_to("browse_movies.php");
  }
}


?>

<?php
echo form_errors($errors);

$output  = "<form action= \"edit_movie.php?movieID=$movie_id\" method=\"post\">";
$output .= "<ul class=\"form\">";
$output .= "<li class=\"form\">";
$output .= "<div>Current title:</div><div><i>$current_title</i></div>";
$output .= "</li>";
$output .= "<li class=\"form\">";
$output .= "<div>New title:</div><div><input type=\"text\" name=\"new_title\" value=$new_title></div>";
$output .= "</li>";
/*
$output .= "<li class=\"form\">";
$output .= "<div>Old password:</div><div><input type=\"password\" name=\"old_password\" value=\"\" ></div>";
$output .= "</li>";
$output .= "<li class=\"form\">";
$output .= "<div>New password:</div><div><input type=\"password\" name=\"new_password\" value=\"\" ></div>";
$output .= "</li>";
$output .= "<li class=\"form\">";
$output .= "<div>Confirm new password:</div><div><input type=\"password\" name=\"confirm_new_password\" value=\"\" ></div>";
$output .= "</li>";
*/
$output .= "</ul>";
$output .= "<input type=\"submit\" name=\"edit_movie\" value=\"Edit Movie\" /> <br /><br />";
$output .= "</form>";
$output .= "<a href=\"browse_movies.php\">Cancel</a>";

echo $output;

?>

<?php
include($layout_files_to_load["sidebar_right"]);
include($layout_files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
