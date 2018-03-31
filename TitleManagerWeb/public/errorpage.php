<?php
include("../private/config.php");
// require_once("../private/classes/logger.php");
?>
<?php
$code = -1;
$reason = "";
if (isset($_POST["ExceptionCode"])) {
  $code = $_POST["ExceptionCode"];
  switch ($code) {
    case ExceptionCode_DatabaseConnectionFailed:
      $reason = "We're having some problems connecting to the database at the moment. <br />";
      break;

    default:
      $reason = "We're having some problems at the moment. <br />";
      break;
  }
}
$output  = "<h1>Oops...</h1> The website encountered a problem. We're sorry about that. <br /><br />";
$output .= $reason;
$output .= "Click <a href=\"main.php\">here</a> to go back to website.<br />";
if (DEBUG) {
  $output .= "<br /><br />";
  $output .= "Last three errors from todays log:<br />";
  $file = file("C:\Maethor\Projects\TitleManager\TitleManagerWeb\private\logs" . "\[" . date("Y-m-d") . "]" . "errors.log");
  if (count($file) >= 6) {
    $output .= $file[count($file)-6] . $file[count($file)-3] . "<br />";
  }
  if (count($file) >= 4) {
    $output .= $file[count($file)-4] . $file[count($file)-2] . "<br /><br />";
  }
  if (count($file) >= 2) {
    $output .= "Most recent error: <br />";
    $output .= $file[count($file)-2] . $file[count($file)-1] . "<br />";
  }



}
echo $output;
?>
