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
include($layout_files_to_load["sidebar_left_back"]);
echo $session->session_message();
// include($content_files_to_load["title"]);
?>

<?php
$username = "";
if (isset($_POST["create_user"])) {

  $fields_required = array("username", "password", "confirm_password");
  $errors = field_validation($_POST, $fields_required, $errors);
  if (!($_POST["password"] == $_POST["confirm_password"])) {
    $errors["password_not_confirmed"] = "Passwords don't match";
  }
  if (empty($errors)) {
    $created_user = User::create($_POST);
    if ($created_user) {
      $_SESSION["message"] .= "User created.";
    }
    else {
      $_SESSION["message"] .= "User not created.";
    }
    redirect_to("manage_users.php");
  }
  $username = $_POST["username"];
}

echo form_errors($errors);
$output  = "<form action=\"new_user.php\" method=\"post\">";
$output .= "<ul class=\"form\">";
$output .= "<li class=\"form\">";
$output .= "<div>Username:</div><div><input type=\"text\" name=\"username\" value=$username></div>";
$output .= "</li>";
$output .= "<li class=\"form\">";
$output .= "<div>Password:</div><div><input type=\"password\" name=\"password\" value=\"\" ></div>";
$output .= "</li>";
$output .= "<li class=\"form\">";
$output .= "<div>Confirm password:</div><div><input type=\"password\" name=\"confirm_password\" value=\"\" ></div>";
$output .= "</li>";
$output .= "</ul>";
$output .= "<input type=\"submit\" name=\"create_user\" value=\"Create User\" /> <br /><br />";
$output .= "</form>";
$output .= "<a href=\"manage_users.php\">Cancel</a>";
echo $output;
?>

<?php
include($layout_files_to_load["sidebar_right"]);
include($layout_files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
