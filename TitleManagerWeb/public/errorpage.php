<?php
include("../private/config.php");
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
echo $output;
?>
