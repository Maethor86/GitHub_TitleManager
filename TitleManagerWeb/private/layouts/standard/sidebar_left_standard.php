
<?php

include("../private/initialize.php");
$navbaritemsize = [
  "y" => "my-1",
];
$current_user_page = "current_user.php";
$home_page = "main.php";
$movie_info_page = "movie_info.php";

$search_term = "";
$search_term = (isset($_GET["search"])) ? $_GET["search"] : "";
$page_title = "";
$output = "";

$output .= "<ul class=\"navbar-nav mr-auto text-left ".$navbaritemsize["y"]."\">";
// make a list of subjects in the sidebar_left
$admin = FALSE;
if ($session->is_admin()) {
  $admin = TRUE;
}

$subjects = Subject::find_all();
foreach ($subjects as $subject) {
  if (!($admin) && $subject->is_admin()) {
    continue;
  }
  if (!$subject->get_visible()) {
    continue;
  }
  $subject_class = "nav-item dropdown ml-2 ".$navbaritemsize["y"];
  if (isset($_SESSION["subject_id"]) && $_SESSION["subject_id"] == $subject->get_subjectid()) {
    $subject_class .= " active";
  }
  $output .= "<li class=\"".$subject_class."\">";
  $output .= "<a class=\"nav-link dropdown-toggle\" href=\"\" id=\"dropdown_subjectid".$subject->get_subjectid()."\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">".$subject->get_menuname()."</a>";
  $output .= "<div class=\"dropdown-menu\" aria-labelledby=\"dropdown_subjectid".$subject->get_subjectid()."\">";
  // find all pages associated with subject
  $pages = Page::find_pages($subject->get_subjectid());
  foreach ($pages as $page) {
    if (!$page->get_visible()) {
      continue;
    }
    $page_class = "dropdown-item";
    if (isset($_SESSION["page_id"]) && $_SESSION["page_id"] == $page->get_pageid()) {
      $page_class .= " active";
      $page_title = $page->get_menuname();
    }
    $output .= "<a class=\"".$page_class."\" href=\"main.php?page=".$page->get_pageid()."\">".$page->get_menuname()."</a>";
  }
  $output .= "</div>";
  $output .= "</li>";
}
$output .= "<li class=\"ml-2 ".$navbaritemsize["y"]."\">";
$output .= "<form class=\"".$navbaritemsize["y"]."\" action=\"browse_movies.php\" method=\"get\">";
$output .= "<input name=\"search\" class=\"form-control\" type=\"text\" value=\"".$search_term."\" placeholder=\"Browse ".Movie::count_movies()." movies...\" style=\"font-style:italic\">";

// $output .= "<div><select type=\"hidden\" name=\"options\"/>";
// $output .= "<option value=\"option_1\">some option</option>";
// $output .= "<option value=\"option_2\">some other option</option>";
// $output .= "<option value=\"option_3\">a third option</option>";
// $output .= "</select>";
// $output .= "</div>";

$output .= "</form>";
$output .= "</li>";
$output .= "</ul>";
$output .= "<span class=\"navbar-text lead\">";
(basename($_SERVER["PHP_SELF"]) == $current_user_page) ? $page_title = "User info" : FALSE;
(basename($_SERVER["PHP_SELF"]) == $home_page) ? $page_title = "Home" : FALSE;
(basename($_SERVER["PHP_SELF"]) == $movie_info_page) ? $page_title = "Movie info" : FALSE;
$output .= $page_title;
$output .= "</span>";
echo $output;

?>

  <!-- <li class="nav-item active">
  <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
  </li> -->
  <!-- <li class="nav-item">
  <a class="nav-link disabled" href="#">Disabled</a>
  </li> -->


  <!-- <li class="nav-item">
  <a class="nav-link" href="logout.php">Log out</a>
  </li> -->
