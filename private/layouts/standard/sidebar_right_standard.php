</div>
<div id="info">

<?php
if ($session->user_id) {
  $current_user = User::find_by_id($session->user_id);
  $output  = "Currently logged in as: ";
  $output .= "<a href=\"current_user.php\">" . $current_user->get_username() . "</a>";
  if ($session->is_logged_in()) {
      $output .= "<hr /><a href=\"logout.php\">Log out</a>";
  }
  echo $output;
}
?>

</div>
</div>
</div>
