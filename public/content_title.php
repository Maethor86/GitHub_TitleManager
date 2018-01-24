<?php
$subject = Subject::find_by_id($_SESSION["subject_id"]);
$output  = "<h2 class=\"page_header\">";
$output .= $subject->get_menuname();
$output .= "</h2>";

echo $output;
?>
