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

$users = User::find_all();
$output  = "<ul class=\"users\">";
$output .= "<li class=\"users\">";
$output .= "<div><h3 class=\"users\">Username</h3></div>";
$output .= "<div><h3 class=\"users\">Action</h3></div>";
$output .= "</li>";
foreach ($users as $user) {
  $output .= "<li><div>";
  $output .= $user->get_username();
  $output .= "</div><div><a href=\"edit_user.php?userID=".$user->get_userid()."\">Edit</a>";
  $output .= "</div><div><a href=\"delete_user.php?userID=".$user->get_userid()."\">Delete</a>";
  $output .= "</div></li>";
}
$output .= "</ul>";
echo $output;
?>
<a href="new_user.php">Add new user</a>


<?php
include($files_to_load["sidebar_right"]);
include($files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
