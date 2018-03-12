<?php
include("../private/initialize.php");
$layout_files_to_load = load_layout("login");

include($layout_files_to_load["header"]);
include($layout_files_to_load["sidebar_left"]);

if ($session->is_logged_in() && $session->is_session_valid()) {
  redirect_to("index.php");
}
echo $session->session_message();
?>



<?php
if (isset($_POST["login"])) {
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // check db if username/pwd exists
  $found_user = User::authenticate($username, $password);

  if ($found_user) {
    $session->login($found_user);
    $logger->database_create_user_log($found_user);
    redirect_to("index.php");
  }
  else {
    $message = "Username/pwd incorrect.";
  }

}
else {
  $username = "";
  $password = "";
  $message = "";
}

$output  = "<form action=\"login.php\" method=\"post\">";
$output .= "<ul class=\"form\">";
$output .= "Please enter username and password.";
$output .= "<li class=\"form\">";
$output .= "<div>Username:</div> <div><input type=\"text\" name=\"username\" value=$username ></div></li>";
$output .= "<li class=\"form\">";
$output .= "<div>Password:</div> <div><input type=\"password\" name=\"password\" value=\"\" ></div></li>";
$output .= "<li class=\"form\">";
$output .= "<div><input type=\"submit\" name=\"login\" value=\"Log in\" /></div></li>";
$output .= "</ul></form>";
$output .= $message;

echo $output;
?>






<?php
include($layout_files_to_load["sidebar_right"]);
include($layout_files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
