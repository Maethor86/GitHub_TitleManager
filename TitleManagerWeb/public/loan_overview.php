<?php
include("../private/initialize.php");
$layout_files_to_load = load_layout("standard");

include($layout_files_to_load["header"]);
include($layout_files_to_load["sidebar_left"]);
include($layout_files_to_load["sidebar_right"]);

if (!($session->is_logged_in() && $session->is_session_valid())) {
  redirect_to("login.php");
}
?>

<?php
$current_loans = "";
$expanded_loan = "";

if (isset($_GET["loaner"])) {
  $loaner_expanded = Loaner::find_by_id($_GET["loaner"]);
  if ($loaner_expanded) {
    $movieloans = $loaner_expanded->find_currentloans();
    if ($movieloans) {
      foreach ($movieloans as $movieloan) {
        $movie = Movie::find_by_id($movieloan->get_movieid());

        $redirect_page = "loan_overview.php?loaner=" . $loaner_expanded->get_loanerid();

        if (!(isset($_GET["movie"]) && $_GET["movie"] == $movie->get_movieid())) {
          $redirect_page .= "&movie=" . $movie->get_movieid();
        }
        if (isset($_GET["movie"]) && $_GET["movie"] == $movie->get_movieid()) {
          $movieloan_expanded = Movieloan::find_by_movieid($_GET["movie"]);
          if ($movieloan_expanded) {
              $expanded_loan .= "<br />";
              $expanded_loan .= " &nbsp; &nbsp; Loaned by " . $loaner_expanded->get_description() . " " . generate_datetime_diff(new TMDateTime($movieloan_expanded->get_datetimeloan())) . ". <br />";
              $expanded_loan .= " &nbsp; &nbsp; The loan was registered by the user: " . User::find_by_id($movieloan_expanded->get_registeredbyuser())->get_username() . ". <br />";
          }
        }
        $current_loans .= " &nbsp; ";
        $current_loans .= "<a href=\"" . $redirect_page . "\">";
        $current_loans .= $movie->get_title();
        $current_loans .= "</a>";
        $current_loans .= $expanded_loan;
        $current_loans .= "<br />";

        $expanded_loan = "";
      }
    }
  }
}

$output = "";
$total_movieloans = count(Movieloan::find_all_currentloans());
if ($total_movieloans > 0) {
  $loaners = Loaner::find_all();
  $output .= "A total of " . $total_movieloans . " movies are currently loaned out.<br />";
  $output .= "<h4>Loaners</h4>";
  foreach ($loaners as $loaner) {
    $movieloans = $loaner->find_currentloans();
    if (count($movieloans) > 0) {
      $redirect_page = "loan_overview.php";
      if (!(isset($_GET["loaner"]) && $_GET["loaner"] == $loaner->get_loanerid())) {
        $redirect_page .= "?loaner=" . $loaner->get_loanerid();
      }
      $output .= "<a href=\"" . $redirect_page . "\">";
      $output .= $loaner->get_description();
      $output .= "</a>";
      $output .= " (" . count($movieloans) . ")";
      $output .= "<br />";
      if (isset($_GET["loaner"]) && $_GET["loaner"] == $loaner->get_loanerid()) {
        $output .= $current_loans;
        $output .= "<br />";
      }
    }
  }
}
else {
  $output .= "No movies has been loaned out.";
}

?>

<?php
echo make_page_title("Loan Overview");
echo $session->session_message();

echo $output;
?>


<?php
include($layout_files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
