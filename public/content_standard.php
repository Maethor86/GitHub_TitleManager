<?php
$subject = Subject::find_by_id($_SESSION["subject_id"]);
$output = "<ul class=\"page_title\">";
$output .= "<li>";
$output .= $subject->get_menuname();
$output .= "</li>";
$output .= "</ul>";

$pages = Page::find_pages($subject->get_subjectid());
foreach ($pages as $page) {
  $output .= "<ul class=\"pages\">";
  $output .= "<li>";
  $output .= "<a href=\"main.php?page=".$page->get_pageid()."\">".$page->get_menuname()."</a>";
  $output .= "</ul>";
  $output .= "</li>";
}

echo $output;
?>
