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
include($layout_files_to_load["sidebar_right"]);
echo $session->session_message();
// include($content_files_to_load["title"]);
?>

<?php

$users = User::find_all();

$output  = "<table border=0>";
$output .= "<tr>";
$output .= "<th align=\"left\" width=200px>Username</th>";
$output .= "<th align=\"left\" width=100px>Action</th>";
$output .= "</tr>";
foreach ($users as $user) {
  $output .= "<tr><td>";
  $output .= $user->get_username();
  $output .= "</td><td><a href=\"edit_user.php?userID=".$user->get_userid()."\">Edit</a>";
  $output .= " <a href=\"delete_user.php?userID=".$user->get_userid()."\">Delete</a></td>";
  $output .= "</tr>";
}
$output .= "</table>";
$output .= "<br />";
$output .= "<a href=\"new_user.php\">Add new user</a>";

echo $output;
?>


<?php
include($layout_files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
