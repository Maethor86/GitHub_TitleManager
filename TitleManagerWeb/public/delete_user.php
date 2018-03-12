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
$user_id = $_GET["userID"];

$deleted_user = User::delete($user_id);
if ($deleted_user) {
  $_SESSION["message"] .= "User deleted.<br />";
}
else {
  $_SESSION["message"] .= "User not deleted.<br />";
}
redirect_to("manage_users.php");
?>

<?php
include($layout_files_to_load["sidebar_right"]);
include($layout_files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
