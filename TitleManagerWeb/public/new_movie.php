<?php
include("../private/initialize.php");
$layout_files_to_load = load_layout("standard");

include($layout_files_to_load["header"]);
include($layout_files_to_load["sidebar_left"]);
include($layout_files_to_load["sidebar_right"]);

if (!($session->is_logged_in() && $session->is_session_valid())) {
  redirect_to("login.php");
}
// echo $session->session_message();
// echo make_page_title("Add New Movie");
?>

<?php

$title = "";
$results = "";
$message = "Search results will appear here.";
if (isset($_POST["create_movie"])) {

  $fields_required = array("title");
  $errors = field_validation($_POST, $fields_required, $errors);
  if (empty($errors)) {

    $title = $_POST["title"];
    $created_movie = Movie::create($title);
    if ($created_movie) {
      $_SESSION["message"] .= "Movie created.<br />";
    }
    else {
      $_SESSION["message"] .= "Movie not created.<br />";
    }
   redirect_to("new_movie.php");
  }
}

if (isset($_GET["search_term"])) {
  $fields_required = array("search_term");
  $errors = field_validation($_GET, $fields_required, $errors);
  if (empty($errors)) {
    $title = $_GET["search_term"];

    if (isset($_GET["page"])) {
      $page = $_GET["page"];
    }
    else {
      $page = 1;
    }
    $movies = Movie::search_extdb_title($title, $page);
    if ($movies) {
      $message = "";
      // $results  = "<table border=1>";
      $per_page = 10;
      $total_results = $movies["totalResults"];
      $pagination = new Pagination($page, $per_page, $total_results);

      $from = $pagination->offset() + 1;
      $to = $pagination->offset() + $pagination->get_perpage();
      if ($to > $pagination->get_totalcount()) {
        $to = $pagination->get_totalcount();
      }
      $results = "<div style=\"clear:both\">";
      $results .= "Showing results " . $from . " - " . $to . " of " . $pagination->get_totalcount() . " (" . $pagination->total_pages() . " pages) <br />" ;
      if ($pagination->total_pages() > 1) {
        if ($pagination->has_previous_page()) {
          $results .= "<a href=\"new_movie.php?search_term=" . $_GET["search_term"] . "&page=1";
          $results .= "\">&laquo;&laquo;First</a> &nbsp;";

          $results .= "<a href=\"new_movie.php?search_term=" . $_GET["search_term"] . "&page=";
          $results .= $pagination->previous_page();
          $results .= "\">&laquo;Previous</a>";
        }
        for ($i = 1; $i <= $pagination->total_pages(); $i++) {
          if ($i == $pagination->get_currentpage()) {
            $results .= $pagination->get_currentpage();
          }
          elseif ($i < $pagination->get_currentpage()+5 && $i > $pagination->get_currentpage()-5) {
            $results .= " <a href=\"new_movie.php?search_term=" . $_GET["search_term"] . "&page=" . $i . "\">" . $i . "</a> ";
          }
          elseif ($i == $pagination->get_currentpage()+5 || $i == $pagination->get_currentpage()-5) {
            $results .= "... ";
          }
        }
        if ($pagination->has_next_page()) {
          $results .= "<a href=\"new_movie.php?search_term=" . $_GET["search_term"] . "&page=";
          $results .= $pagination->next_page();
          $results .= "\">Next&raquo;</a> &nbsp;";

          $results .= "<a href=\"new_movie.php?search_term=" . $_GET["search_term"] . "&page=";
          $results .= $pagination->total_pages();
          $results .= "\">Last&raquo;&raquo;</a>";
        }
      }
      $results .= "</div><br />";
      foreach ($movies["Search"] as $movie) {

        $no_poster_encoded = Poster::encode_poster();
        $caption = $movie["Title"] . " (" . $movie["Year"] . ")";
        $new_caption = wordwrap($caption, 20, "<br />\n");
        $results .= "<div style=\"text-align:center; display:inline-block; vertical-align:top; padding:10px\">";
        $results .= "<a href=\"more_movie_info.php?imdbID=" . $movie["imdbID"] . "\">";
        $results .=     "<img src=\"data:image/jpeg;base64," . Movie::url_to_image($movie["Poster"]) ."\" onerror=\"this.src='data:image/jpg;base64," . $no_poster_encoded . "'\" height=\"150\">";
        $results .=     "<p>" . $new_caption . "</p>";
        $results .= "</a></div>";
      }
      $results .= "<div style=\"clear:both\"><br /><br />";
      $results .= "Showing results " . $from . " - " . $to . " of " . $pagination->get_totalcount() . " (" . $pagination->total_pages() . " pages) <br />" ;
      if ($pagination->total_pages() > 1) {
        if ($pagination->has_previous_page()) {
          $results .= "<a href=\"new_movie.php?search_term=" . $_GET["search_term"] . "&page=1";
          $results .= "\">&laquo;&laquo;First</a> &nbsp;";

          $results .= "<a href=\"new_movie.php?search_term=" . $_GET["search_term"] . "&page=";
          $results .= $pagination->previous_page();
          $results .= "\">&laquo;Previous</a>";
        }
        for ($i = 1; $i <= $pagination->total_pages(); $i++) {
          if ($i == $pagination->get_currentpage()) {
            $results .= $pagination->get_currentpage();
          }
          elseif ($i < $pagination->get_currentpage()+5 && $i > $pagination->get_currentpage()-5) {
            $results .= " <a href=\"new_movie.php?search_term=" . $_GET["search_term"] . "&page=" . $i . "\">" . $i . "</a> ";
          }
          elseif ($i == $pagination->get_currentpage()+5 || $i == $pagination->get_currentpage()-5) {
            $results .= "... ";
          }
        }
        if ($pagination->has_next_page()) {
          $results .= "<a href=\"new_movie.php?search_term=" . $_GET["search_term"] . "&page=";
          $results .= $pagination->next_page();
          $results .= "\">Next&raquo;</a> &nbsp;";

          $results .= "<a href=\"new_movie.php?search_term=" . $_GET["search_term"] . "&page=";
          $results .= $pagination->total_pages();
          $results .= "\">Last&raquo;&raquo;</a>";
        }
      }
      $results .= "</div><br />";
    }
    else {
      $message = "Couldn't find any movies containing '" . $title . "'.";
    }
   // redirect_to("new_movie.php");
  }
}




?>

<?php
echo form_errors($errors);

$output  = "<form action=\"new_movie.php\" method=\"get\">";
$output .= "<ul class=\"form\">";
$output .= "Title:";
$output .= "<li class=\"form\">";
$output .= "<div><input type=\"text\" name=\"search_term\" placeholder=\"Enter title...\" style=\"font-style:italic\" value=\"".$title."\"></div>";
$output .= "<div><input type=\"submit\" value=\"Search DB\" /></div></li>";
$output .= "</ul></form>";

echo make_page_title("Add New Movie");
echo $session->session_message();
echo $output;
echo "<hr />";
echo $message;
echo $results;
// echo $results;

// $output  = "<form action=\"new_movie.php\" method=\"post\">";
// $output .= "<ul class=\"form\">";
// $output .= "Title:";
// $output .= "<li class=\"form\">";
// $output .= "<div><input type=\"text\" name=\"title\" placeholder=\"Enter title...\" style=\"font-style:italic\" value=$title></div>";
// $output .= "<div><input type=\"submit\" name=\"create_movie\" value=\"Add\" /></div></li>";
// $output .= "</ul></form>";
// echo $output;


?>

<?php
include($layout_files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
