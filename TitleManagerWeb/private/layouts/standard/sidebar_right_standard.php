

<?php
$navbaritemsize = [
  "y" => "my-1",
];
$currentpage = basename($_SERVER["PHP_SELF"]);
$current_user_page = "current_user.php";
$active = ($currentpage == $current_user_page) ? " active" : "";

if ($session->is_logged_in() && $session->get_userid()) {
  $current_user = User::find_by_id($session->get_userid());
  $output  = "";
  $output .= "<ul class=\"navbar-nav ml-auto mr-3 text-left ".$navbaritemsize["y"]."\">";
  $output .= "<li class=\"nav-item dropdown ml-2".$active."\">";
  $output .= "<a class=\"nav-link dropdown-toggle\" href=\"\" id=\"dropdown_currentuser\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">" . $current_user->get_gravatar($current_user->get_username(), 30, "identicon", "g", TRUE) . " &nbsp; " . $current_user->get_username() . "</a>";
  $output .= "<div class=\"dropdown-menu\" aria-labelledby=\"dropdown_currentuser\">";
  $output .= "<a class=\"dropdown-item\" href=\"".$current_user_page."\">Me</a>";
  $output .= "<a class=\"dropdown-item\" href=\"\"><hr /></a>";
  $output .= "<a class=\"dropdown-item\" href=\"logout.php\">Log out</a>";
  $output .= "</div>";
  $output .= "</li>";
  $output .= "</ul>";
  $output .= "</div>";
  $output .= "</nav>";
  echo $output;
}
?>

<div class="container-fluid mh-100 my-3">
<div class="row">
<div class="col-xl-6 offset-xl-3 text-left">


<!-- <div class="container mh-100 my-3 text-left"> -->
