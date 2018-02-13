<?php
$subject = Subject::find_by_id($_SESSION["subject_id"]);

$pages = Page::find_pages($subject->get_subjectid());
$output = "";
foreach ($pages as $page) {
  $output .= "<ul class=\"pages\">";
  $output .= "<li>";
  $output .= "<a href=\"main.php?page=".$page->get_pageid()."\">".$page->get_menuname()."</a>";
  $output .= "</ul>";
  $output .= "</li>";
}

echo $output;
?>
