<?php
include("../private/initialize.php");
$layout_files_to_load = load_layout("standard");
// $content_files_to_load = load_contents("standard");

if (!($session->is_logged_in() && $session->is_session_valid())) {
  redirect_to("login.php");
}
if (!$session->is_admin()) {
  redirect_to("login.php");
}
?>

<?php
include($layout_files_to_load["header"]);
include($layout_files_to_load["sidebar_left"]);
echo $session->session_message();
// include($content_files_to_load["title"]);
?>

<?php


echo "<h4>Current User:</h4>";
echo sql_show_current_user();
echo "<h4>Current Database:</h4>";
echo sql_show_current_database();
echo "<h4>SQL Version:</h4>";
echo sql_show_sqlversion();
echo "<hr />";




?>

<?php
include($layout_files_to_load["sidebar_right"]);
include($layout_files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
