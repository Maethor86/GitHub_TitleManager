<?php
include("../private/initialize.php");
$layout_files_to_load = load_layout("standard");

include($layout_files_to_load["header"]);
include($layout_files_to_load["sidebar_left"]);

if (!($session->is_logged_in() && $session->is_session_valid())) {
  redirect_to("login.php");
}
?>


<?php
$loaners = Loaner::find_all();

$output  = "<h4>Loaners</h4>";
foreach ($loaners as $loaner) {
  $movieloans = $loaner->find_currentloans();
  if (count($movieloans) > 0) {
    
    $output .= $loaner->get_description();
    $output .= "(" . count($movieloans) . ")";
    $output .= "<br />";  # code...
  }
}
// $output .=
// $output .=
// $output .=
// $output .=

?>

<?php
echo make_page_title("Loan Overview");
echo $session->session_message();

echo $output;

?>


<?php
include($layout_files_to_load["sidebar_right"]);
include($layout_files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
