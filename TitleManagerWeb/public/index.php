<?php
include("../private/initialize.php");
$layout_files_to_load = load_layout("login");

include($layout_files_to_load["header"]);
include($layout_files_to_load["sidebar_left"]);


if (!($session->is_logged_in() && $session->is_session_valid())) {
  redirect_to("login.php");
}
else {
  redirect_to("main.php");
}
?>

<?php
include($layout_files_to_load["sidebar_right"]);
include($layout_files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
