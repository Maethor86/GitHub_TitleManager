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
$results = "";
$title = "";
$message = "";

$status = "all";
$quality = "all";
$sortingid = 1;

if (isset($_GET["search"])) {
  if (isset($_GET["status"]) && isset($_GET["quality"]) && isset($_GET["sorting"])) {
    $status = $_GET["status"];
    $quality = $_GET["quality"];
    $sortingid = $_GET["sorting"];
  }
  else {
    $status = "all";
    $quality = "all";
    $sortingid = 1;
  }
  // $fields_required = array("search_term");
  // $errors = field_validation($_GET, $fields_required, $errors);
  if (empty($errors)) {
    $title = $_GET["search"];
    if (isset($_GET["page"])) {
      $page = $_GET["page"];
    }
    else {
      $page = 1;
    }
    $per_page = 10;
    $total_results = Movie::count_movie_set_by_title($title, $status, $quality);
    // $total_results = count($movies);
    $pagination = new Pagination($page, $per_page, $total_results);
    $movies = Movie::find_movie_set_by_title($title, $status, $quality, $sortingid, $pagination->get_perpage(),$pagination->offset());
    if ($movies) {
      $from = $pagination->offset() + 1;
      $to = $pagination->offset() + $pagination->get_perpage();
      if ($to > $pagination->get_totalcount()) {
        $to = $pagination->get_totalcount();
      }
      $results = "<div style=\"clear:both\">";
      $results .= "Showing results " . $from . " - " . $to . " of " . $pagination->get_totalcount() . " (" . $pagination->total_pages() . " pages) <br />" ;
      if ($pagination->total_pages() > 1) {
        if ($pagination->has_previous_page()) {
          $results .= "<a href=\"browse_movies.php?search=" . $_GET["search"] . "&status=" . $_GET["status"] . "&quality=" . $_GET["quality"] . "&sorting=" . $_GET["sorting"] . "&options=" . $_GET["options"] . "&page=1";
          $results .= "\">&laquo;&laquo;First</a> &nbsp;";

          $results .= "<a href=\"browse_movies.php?search=" . $_GET["search"] . "&status=" . $_GET["status"] . "&quality=" . $_GET["quality"] . "&sorting=" . $_GET["sorting"] . "&options=" . $_GET["options"] . "&page=";
          $results .= $pagination->previous_page();
          $results .= "\">&laquo;Previous</a>";
        }
        for ($i = 1; $i <= $pagination->total_pages(); $i++) {
          if ($i == $pagination->get_currentpage()) {
            $results .= $pagination->get_currentpage();
          }
          elseif ($i < $pagination->get_currentpage()+5 && $i > $pagination->get_currentpage()-5) {
            $results .= " <a href=\"browse_movies.php?search=" . $_GET["search"] . "&status=" . $_GET["status"] . "&quality=" . $_GET["quality"] . "&sorting=" . $_GET["sorting"] . "&options=" . $_GET["options"] . "&page=" . $i . "\">" . $i . "</a> ";
          }
          elseif ($i == $pagination->get_currentpage()+5 || $i == $pagination->get_currentpage()-5) {
            $results .= " ... ";
          }
        }
        if ($pagination->has_next_page()) {
          $results .= "<a href=\"browse_movies.php?search=" . $_GET["search"] . "&status=" . $_GET["status"] . "&quality=" . $_GET["quality"] . "&sorting=" . $_GET["sorting"] . "&options=" . $_GET["options"] . "&page=";
          $results .= $pagination->next_page();
          $results .= "\">Next&raquo;</a> &nbsp;";

          $results .= "<a href=\"browse_movies.php?search=" . $_GET["search"] . "&status=" . $_GET["status"] . "&quality=" . $_GET["quality"] . "&sorting=" . $_GET["sorting"] . "&options=" . $_GET["options"] . "&page=";
          $results .= $pagination->total_pages();
          $results .= "\">Last&raquo;&raquo;</a>";
        }
      }
      $results .= "</div><br />";
      foreach ($movies as $movie) {

        $no_poster_encoded = Poster::encode_poster();
        $caption = $movie->get_title() . " (" . $movie->get_releasedyear() . ")";
        $new_caption = wordwrap($caption, 20, "<br />\n"); // ***

        $moviequality = $movie->get_moviequalityid();
        switch ($moviequality) {
          case '1':
            $icon = "blu_ray-logo";
            $icon_type = "jpg";
            break;
          case '2':
            $icon = "dvd-logo";
            $icon_type = "jpg";
            break;

          default:
            $icon = "unknown_quality-logo";
            $icon_type = "jpg";
            break;
        }
        $results .= "<div style=\"text-align:center; display:inline-block; vertical-align:top; padding:10px\">";
        $results .= "<a href=\"movie_info.php?movieID=" . $movie->get_movieid() . "\">"; // ***
        $results .=     "<img src=\"data:image/jpeg;base64," . Poster::encode_poster($movie->get_posterfilename(),"jpg") ."\" class=\"poster_thumbnail\" onerror=\"this.src='data:image/jpg;base64," . $no_poster_encoded . "'\" height=\"150\">";  // ***
        $results .=     "<img src=\"data:image/jpeg;base64," . Poster::encode_icon($icon,$icon_type) ."\" height=\"30px\" class=\"smallicon\">";  // ***
        $results .=     "<p>" . $new_caption . "</p>";
        $results .= "</a></div>";
      }
      $results .= "<div style=\"clear:both\"><br /><br />";
      $results .= "Showing results " . $from . " - " . $to . " of " . $pagination->get_totalcount() . " (" . $pagination->total_pages() . " pages) <br />" ;
      if ($pagination->total_pages() > 1) {
        if ($pagination->has_previous_page()) {
          $results .= "<a href=\"browse_movies.php?search=" . $_GET["search"] . "&status=" . $_GET["status"] . "&quality=" . $_GET["quality"] . "&sorting=" . $_GET["sorting"] . "&options=" . $_GET["options"] . "&page=1";
          $results .= "\">&laquo;&laquo;First</a> &nbsp;";

          $results .= "<a href=\"browse_movies.php?search=" . $_GET["search"] . "&status=" . $_GET["status"] . "&quality=" . $_GET["quality"] . "&sorting=" . $_GET["sorting"] . "&options=" . $_GET["options"] . "&page=";
          $results .= $pagination->previous_page();
          $results .= "\">&laquo;Previous</a>";
        }
        for ($i = 1; $i <= $pagination->total_pages(); $i++) {
          if ($i == $pagination->get_currentpage()) {
            $results .= $pagination->get_currentpage();
          }
          elseif ($i < $pagination->get_currentpage()+5 && $i > $pagination->get_currentpage()-5) {
            $results .= " <a href=\"browse_movies.php?search=" . $_GET["search"] . "&status=" . $_GET["status"] . "&quality=" . $_GET["quality"] . "&sorting=" . $_GET["sorting"] . "&options=" . $_GET["options"] . "&page=" . $i . "\">" . $i . "</a> ";
          }
          elseif ($i == $pagination->get_currentpage()+5 || $i == $pagination->get_currentpage()-5) {
            $results .= " ... ";
          }
        }
        if ($pagination->has_next_page()) {
          $results .= "<a href=\"browse_movies.php?search=" . $_GET["search"] . "&status=" . $_GET["status"] . "&quality=" . $_GET["quality"] . "&sorting=" . $_GET["sorting"] . "&options=" . $_GET["options"] . "&page=";
          $results .= $pagination->next_page();
          $results .= "\">Next&raquo;</a> &nbsp;";

          $results .= "<a href=\"browse_movies.php?search=" . $_GET["search"] . "&status=" . $_GET["status"] . "&quality=" . $_GET["quality"] . "&sorting=" . $_GET["sorting"] . "&options=" . $_GET["options"] . "&page=";
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
else {
  $title = "";
  $message = "Search results will appear here.";
}


$search_form  = "<form action=\"browse_movies.php\" method=\"get\" style=\"display: inline-block; vertical-align:bottom;\">";
$search_form .= "<ul class=\"form\">";
$search_form .= "<li class=\"form\">";
$search_form .= "<div>";
$search_form .= "Title";
$search_form .= "</div>";
$search_form .= "<div>";
$search_form .= "Status";
$search_form .= "</div>";
$search_form .= "<div>";
$search_form .= "Quality";
$search_form .= "</div>";
$search_form .= "<div>";
$search_form .= "Sort by";
$search_form .= "</div>";
$search_form .= "<div>";
$search_form .= "Options";
$search_form .= "</div>";
$search_form .= "<div>";
$search_form .= "";
$search_form .= "</div>";
$search_form .= "</li>";
$search_form .= "<li class=\"form\">";
$search_form .= "<div><input type=\"text\" name=\"search\" placeholder=\"Search " . Movie::count_movies() . " movies...\" style=\"font-style:italic\" value=\"".$title."\"></div>";
$search_form .= "<div><select name=\"status\"/>";
$search_form .= "<option value=\"all\">All</option>";
$moviestati = Moviestatus::find_all();
foreach ($moviestati as $moviestatus) {
  $search_form .= "<option value=\"" . $moviestatus->get_moviestatusid() . "\"";
  if ($moviestatus->get_moviestatusid() == $status) {
    $search_form .=  "selected=\"selected\"";
  }
  $search_form .= ">" . $moviestatus->get_description() . "</option>";
}
$search_form .= "</select>";
$search_form .= "</div>";
$search_form .= "<div><select name=\"quality\"/>";
$search_form .= "<option value=\"all\">All</option>";
$moviequalities = Moviequality::find_all();
foreach ($moviequalities as $moviequality) {
  $search_form .= "<option value=\"" . $moviequality->get_moviequalityid() . "\"";
  if ($moviequality->get_moviequalityid() == $quality) {
    $search_form .=  "selected=\"selected\"";
  }
  $search_form .= ">" . $moviequality->get_description() . "</option>";
}
$search_form .= "</select>";
$search_form .= "</div>";
$search_form .= "<div><select name=\"sorting\"/>";
$moviesortings = Moviesorting::find_all();
foreach ($moviesortings as $moviesorting) {
  $search_form .= "<option value=\"" . $moviesorting->get_moviesortingid() . "\"";
  if ($moviesorting->get_moviesortingid() == $sortingid) {
    $search_form .=  "selected=\"selected\"";
  }
  $search_form .= ">" . $moviesorting->get_description() . "</option>";
}
$search_form .= "</select>";
$search_form .= "</div>";
// $search_form .= "<div><select name=\"sorting\"/>";
// $search_form .= "<option value=\"alphabetical_az\">Alphabetical (A-Z)</option>";
// $search_form .= "<option value=\"alphabetical_za\">Alphabetical (Z-A)</option>";
// $search_form .= "<option value=\"imdbscore\">IMDB Score</option>";
// $search_form .= "<option value=\"imdbvotes\">IMDB Votes</option>";
// $search_form .= "</select>";
// $search_form .= "</div>";
$search_form .= "<div><select name=\"options\"/>";
$search_form .= "<option value=\"option_1\">some option</option>";
$search_form .= "<option value=\"option_2\">some other option</option>";
$search_form .= "<option value=\"option_3\">a third option</option>";
$search_form .= "</select>";
$search_form .= "</div>";
$search_form .= "<div><input type=\"submit\" value=\"Search\" /></div>";
// $search_form .= "<div>|</div>";
// $search_form .= "<div><input type=\"submit\" name=\"browse_all\" value=\"Browse all\" /></div>";
$search_form .= "</li>";
$search_form .= "</ul>";
$search_form .= "</form>";
//
// $browse_form  = "<form action=\"browse_movies.php?search=\" method=\"post\" style=\"display: inline-block; vertical-align:bottom;\">";
// $browse_form .= "<ul class=\"form\">";
// $browse_form .= "<li class=\"form\">";
// $browse_form .= "<div>|</div>";
// $browse_form .= "<div><input type=\"submit\" name=\"browse_all\" value=\"Browse all\" /></div>";
// $browse_form .= "</li>";
// $browse_form .= "</ul>";
// $browse_form .= "</form>";


?>

<?php
echo make_page_title("Browse Movies");
echo $session->session_message();
echo form_errors($errors);

echo $search_form;
// echo $browse_form;
echo "<hr />";
echo $message;
echo $results;

// echo "<hr />";
// echo "<hr />";
// echo $output;


?>


<?php
include($layout_files_to_load["sidebar_right"]);
include($layout_files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
