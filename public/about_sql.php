<?php
include("../private/initialize.php");
$files_to_load = load_layout("standard");

include($files_to_load["header"]);
include($files_to_load["sidebar_left"]);

if (!($session->is_logged_in() && $session->is_session_valid())) {
  redirect_to("login.php");
}
if (!$session->is_admin()) {
  redirect_to("login.php");
}
echo $session->session_message();
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
include($files_to_load["sidebar_right"]);
include($files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
