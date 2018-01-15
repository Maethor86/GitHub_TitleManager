<?php
include("../private/initialize.php");
$files_to_load = load_layout("standard");

include($files_to_load["header"]);
include($files_to_load["sidebar_left"]);

if (!($session->is_logged_in() && $session->is_session_valid())) {
  redirect_to("login.php");
}
echo $session->session_message();
?>

<?php

$_SESSION["subject_id"] = NULL;
$_SESSION["page_id"] = NULL;
if (!empty($_GET["subject"])) {

  $subject_id = $_GET["subject"];
  $_SESSION["subject_id"] = $subject_id;
  // $subject = Subject::find_by_id($subject_id);

  switch ($subject_id) {

    case "1":
      redirect_to("about.php");
      break;

    case "2":
      redirect_to("admin.php");
      break;

    case "3":
      redirect_to("search_movie.php");
      break;

    default:
      redirect_to("main.php");
      break;

  }

}
elseif (!empty($_GET["page"])) {

  $page_id = $_GET["page"];
  $_SESSION["page_id"] = $page_id;
  // $page = Page::find_by_id($page_id);

  switch ($page_id) {

    case "1":
      redirect_to("about_sql.php");
      break;

    case "2":
      redirect_to("about_php.php");
      break;

    case "3":
      redirect_to("manage_users.php");
      break;

    case "4":
      redirect_to("about_OS_browser.php");
      break;

    case "5":
      redirect_to("about_php.php");
      break;

    case "6":
      redirect_to("about_php.php");
      break;


    default:
      redirect_to("main.php");
      break;

  }
}



else {
  echo "hello";
}












?>

<?php
include($files_to_load["sidebar_right"]);
include($files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
