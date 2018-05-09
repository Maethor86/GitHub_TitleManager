<?php
include("../private/initialize.php");
$layout_files_to_load = load_layout("standard");

include($layout_files_to_load["header"]);
include($layout_files_to_load["sidebar_left_back"]);
include($layout_files_to_load["sidebar_right"]);

if (!($session->is_logged_in() && $session->is_session_valid())) {
  redirect_to("login.php");
}
echo $session->session_message();
?>

<?php
$deleted_movie = Movie::delete($_GET["movieID"]);

if ($deleted_movie) {
  $_SESSION["message"] .= "Movie deleted.<br />";
}
else {
  $_SESSION["message"] .= "Movie not deleted.<br />";
}
redirect_to("browse_movies.php");

?>

<?php
include($layout_files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
