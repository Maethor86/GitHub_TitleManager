<?php
include("../private/initialize.php");
$files_to_load = load_layout("standard");

include($files_to_load["header"]);
include($files_to_load["sidebar_left"]);

if (!($session->is_logged_in() && $session->is_session_valid())) {
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

$message = "";
if (isset($_POST["search"])) {
  $title = trim($_POST["title"]);

  $movie_set = Movie::find_movie_set_by_title($title);

}
else {
  $title = "";
  $message = "";
}

$output  = "<form action=\"search_movie.php\" method=\"post\">";
$output .= "<ul class=\"form\">";
$output .= "Please enter title:";
$output .= "<li class=\"form\">";
$output .= "<div><input type=\"text\" name=\"title\" value=$title ></div></li>";
$output .= "<li class=\"form\">";
$output .= "<div><input type=\"submit\" name=\"search\" value=\"Search\" /></div></li>";
$output .= "</ul></form>";
$output .= $message;

$output .= "<a href=\"browse_movies.php\">Browse all movies</a>";

echo $output;
echo "<hr />";
if (!empty($movie_set)) {
  // found movie

  $output  = "<ul class=\"movies\">";
  foreach ($movie_set as $movie) {
    // $found_movies = TRUE;
    $output .= "<li class=\"movies\">";
    $output .= "<div>";
    $output .= "<a href=\"movie_info.php?movieID=";
    $output .= $movie->get_movieid();
    $output .= "\">";
    $output .= $movie->get_title();
    $output .= "</a></div></li>";
  }
  $output .= " </ul>";
  echo $output;
}
else {
  // didnt find movie
  echo "Couldn't find any movies.";
}


?>


<?php
include($files_to_load["sidebar_right"]);
include($files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
