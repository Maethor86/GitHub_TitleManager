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
$user = User::find_by_id($_GET["userID"]);
$new_username = "";
if (isset($_POST["edit_user"])) {
  $new_username = $_POST["new_username"];
  if (!empty($_POST["new_password"]) || !empty($_POST["confirm_new_password"])) {
    $fields_required = array("new_username", "old_password", "new_password", "confirm_new_password");
    $errors = field_validation($_POST, $fields_required, $errors);
    if (!($_POST["new_password"] == $_POST["confirm_new_password"])) {
      $errors["new_password_not_confirmed"] = "New passwords don't match";
    }
  }
  if (isset($_POST["new_username"])) {
    $fields_required = array("new_username", "old_password");
    $errors = field_validation($_POST, $fields_required, $errors);
    if (!User::authenticate($user->get_username(), $_POST["old_password"])) {
      $errors["password_not_confirmed"] = "Incorrect password for user";
    }
  }

  if (empty($errors)) {
    $edited_user = $user->update($_POST, $user->get_userid());
    if ($edited_user) {
      $_SESSION["message"] .= "User edited.";
    }
    else {
      $_SESSION["message"] .= "User not edited.";
    }
    redirect_to("manage_users.php");
  }
}

?>

<?php
echo form_errors($errors);
?>

<?php
$output  = "<form action=\"edit_user.php?userID=".$user->get_userid()."\" method=\"post\">";
$output .= "<ul class=\"form\">";
$output .= "<li class=\"form\">";
$output .= "<div>Current username:</div><div><i>".$user->get_username()."</i></div>";
$output .= "</li>";
$output .= "<li class=\"form\">";
$output .= "<div>New username:</div><div><input type=\"text\" name=\"new_username\" value=$new_username></div>";
$output .= "</li>";
$output .= "<li class=\"form\">";
$output .= "<div>Old password:</div><div><input type=\"password\" name=\"old_password\" value=\"\" ></div>";
$output .= "</li>";
$output .= "<li class=\"form\">";
$output .= "<div>New password:</div><div><input type=\"password\" name=\"new_password\" value=\"\" ></div>";
$output .= "</li>";
$output .= "<li class=\"form\">";
$output .= "<div>Confirm new password:</div><div><input type=\"password\" name=\"confirm_new_password\" value=\"\" ></div>";
$output .= "</li>";
$output .= "</ul>";
$output .= "<input type=\"submit\" name=\"edit_user\" value=\"Edit User\" /> <br /><br />";
$output .= "</form>";
$output .= "<a href=\"manage_users.php\">Cancel</a>";

echo $output;
?>

<?php
include($files_to_load["sidebar_right"]);
include($files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
