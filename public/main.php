<?php
include("../private/initialize.php");
$layout_files_to_load = load_layout("standard");

$_SESSION["subject_id"] = NULL;
$_SESSION["page_id"] = NULL;

include($layout_files_to_load["header"]);
include($layout_files_to_load["sidebar_left"]);

if (!($session->is_logged_in() && $session->is_session_valid())) {
  redirect_to("login.php");
}
echo $session->session_message();
?>

<?php
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
      redirect_to("movies.php");
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
      redirect_to("browse_movies.php");
      break;

    case "6":
      redirect_to("search_movie.php");
      break;

    case "7":
      redirect_to("new_movie.php");
      break;

    case "8":
      redirect_to("loan_overview.php");
      break;


    default:
      redirect_to("main.php");
      break;

  }
}



else {
  $user = User::find_by_id($_SESSION["user_id"]);
  echo make_page_title("Welcome!");
  echo "Hello ".$user->get_username().", welcome to Title Manager!<br />";
  echo "Your last login was 1 day ago.<br />";
  echo "Navigate the site by clicking the links on the left.";




}












?>

<?php
include($layout_files_to_load["sidebar_right"]);
include($layout_files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
