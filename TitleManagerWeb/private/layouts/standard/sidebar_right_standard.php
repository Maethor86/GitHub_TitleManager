</div>
<div id="info">

<?php
if ($session->get_userid()) {
  $current_user = User::find_by_id($session->get_userid());
  $output  = "";
  $output .= "<a href=\"current_user.php\" style=\"display: flex; align-items: center\">" . $current_user->get_gravatar($current_user->get_username(), 30, "identicon", "g", TRUE) . " &nbsp; " . $current_user->get_username() . "</a>";
  if ($session->is_logged_in()) {
      $output .= "<hr /><a href=\"logout.php\">Log out</a>";
  }
  echo $output;
}
?>

</div>
</div>
</div>
