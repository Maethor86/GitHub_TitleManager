<?php
include("../private/initialize.php");
$layout_files_to_load = load_layout("standard");

include($layout_files_to_load["header"]);
include($layout_files_to_load["sidebar_left_back_main"]);

if (!($session->is_logged_in() && $session->is_session_valid())) {
  redirect_to("login.php");
}
echo $session->session_message();
?>

<?php

$user = User::find_by_id($_SESSION["user_id"]);

$username = $user->get_username();
$created_datetime = $user->get_datetimecreated();
$user_level = $user->get_userroleid();

$output  = "<ul class=\"form\">";
$output .= "<li class=\"form\">";
$output .= "<div>Username:</div><div><i>$username</i></div>";
$output .= "</li>";
$output .= "<li class=\"form\">";
$output .= "<div>User level:</div><div><i>$user_level</i></div>";
$output .= "</li>";
$output .= "<li class=\"form\">";
$output .= "<div>User created:</div><div><i>$created_datetime</i></div>";
$output .= "</li>";
$output .= "</ul>";

echo $output;



?>


<?php
include($layout_files_to_load["sidebar_right"]);
include($layout_files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
