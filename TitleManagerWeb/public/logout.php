<?php
include("../private/initialize.php");
?>

<?php
// update last_activity in sql server to reflect clicking on logout
$session->update_session_activity();

// reset session, and redirect to login page
$_SESSION["user_id"] = NULL;
$_SESSION["username"] = NULL;
$_SESSION["last_activity"] = NULL;
$_SESSION["login_id"] = NULL;
// $_SESSION["message"] = NULL;
// $_SESSION["error"] = NULL;
// $_SESSION = NULL;
// session_destroy();
redirect_to("login.php");
?>
