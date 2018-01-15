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
include($files_to_load["sidebar_right"]);
include($files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
