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

$total_logins = count(Login::find_logins_by_userid($user->get_userid()));
$previous_login = Login::find_previous_login_by_userid($user->get_userid(),1);
$previous_added_movie = Movie::find_movies_added_by_userid($user->get_userid());
if ($previous_added_movie) {
  $previous_added_movie = $previous_added_movie[0];
}

$output  = "<h2 style=\"display: flex; align-items: center\">" . $user->get_username() . " &nbsp; " . $user->get_gravatar($user->get_username(), 40, "identicon", "g", TRUE) . "</h2>";
$output .= "User since " . generate_datetime_diff(new TMDateTime($user->get_datetimecreated())) . ".<br />";
$output .= "<h4>Logins</h4>";
$output .= "Total logins: " . $total_logins . ".<br />";
if ($previous_login) {
  $output .= "Last login: " . generate_datetime_diff(new TMDateTime($previous_login->get_datetimelogin())) . ".<br />";
}
$output .= "<h4>Movies</h4>";
$output .= "Movies added: " . count(Movie::find_movies_added_by_userid($user->get_userid())) . ".<br />";
if ($previous_added_movie) {
  $output .= "Most recently added movie was " . $previous_added_movie->get_title() . " on " . generate_datetime_diff(new TMDateTime($previous_added_movie->get_datetimecreated())) . ".<br />";
}
$output .= "<h4>Account</h4>";
$output .= "User level: " . Userrole::find_by_id($user->get_userroleid())->get_userrolename() . ".";




echo $output;
?>


<?php
include($layout_files_to_load["sidebar_right"]);
include($layout_files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
