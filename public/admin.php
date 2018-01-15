<?php
include("../private/initialize.php");
$files_to_load = load_layout("standard");

include($files_to_load["header"]);
include($files_to_load["sidebar_left"]);

if (!($session->is_logged_in() && $session->is_session_valid())) {
  redirect_to("login.php");
}
if (!$session->is_admin()) {
  redirect_to("login.php");
}
echo $session->session_message();
?>

<?php

$subject = Subject::find_by_id($_SESSION["subject_id"]);
$pages = Page::find_pages($subject->get_subjectid());
$subject_menuname = $subject->get_menuname();
echo "<ul class=\"subjects\">";
echo "<li>";
echo $subject_menuname;
foreach ($pages as $page) {
  $page_menuname = $page->get_menuname();
  $page_id = $page->get_pageid();
  echo "<ul class=\"pages\">";
  echo "<li>";
  echo "<a href=\"main.php?page=".$page_id."\">$page_menuname</a>";
  echo "</li>";
  echo "</ul>";
}
echo "</li>";
echo "</ul>";







?>

<?php
include($files_to_load["sidebar_right"]);
include($files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
