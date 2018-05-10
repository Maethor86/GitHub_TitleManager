<?php
include("../private/initialize.php");
$layout_files_to_load = load_layout("standard");

// ***
$_SESSION["subject_id"] = 3;
$_SESSION["page_id"] = 5;

include($layout_files_to_load["header"]);
include($layout_files_to_load["sidebar_left"]);
include($layout_files_to_load["sidebar_right"]);

if (!($session->is_logged_in() && $session->is_session_valid())) {
  redirect_to("login.php");
}
?>


<?php
$results = "";
$title = "";
$message = "";
$pagination_out  = "";

$status = "all";
$quality = "all";
$sortingid = 1;
$options = "option_1";

if (isset($_GET["search"])) {
  $status = (isset($_GET["status"]) ? $_GET["status"] : "all");
  $quality = (isset($_GET["quality"]) ? $_GET["quality"] : "all");
  $sortingid = (isset($_GET["sorting"]) ? $_GET["sorting"] : 1);

  if (empty($errors)) {
    $title = $_GET["search"];
    if (isset($_GET["page"])) {
      $page = $_GET["page"];
    }
    else {
      $page = 1;
    }
    $per_page = 12;
    $total_results = Movie::count_movie_set_by_title($title, $status, $quality);
    $pagination = new Pagination($page, $per_page, $total_results);

    $movies = Movie::find_movie_set_by_title($title, $status, $quality, $sortingid, $pagination->get_perpage(),$pagination->offset());
    if ($movies) {
      $results .= "<div class=\"container-fluid\">";
      $results .= "<div class=\"row\">";

      foreach ($movies as $movie) {

        $no_poster_encoded = Poster::encode_poster();
        $caption = $movie->get_title() . " (" . $movie->get_releasedyear() . ")";
        $new_caption = wordwrap($caption, 20, "<br />\n"); // ***

        $moviequality = $movie->get_moviequalityid();
        switch ($moviequality) {
          case '1':
            $icon = "blu_ray-logo-transparent";
            $icon_type = "png";
            break;
          case '2':
            $icon = "dvd-logo-transparent";
            $icon_type = "png";
            break;

          default:
            $icon = "unknown_quality-logo";
            $icon_type = "jpg";
            break;
        }

        $results .= "<div class=\"col-sm-4 my-1\">";

        $results .= "<a class=\"text-dark\" style=\"text-decoration: none\" href=\"movie_info.php?movieID=" . $movie->get_movieid() . "\">"; // ***
        $results .= "<div class=\"card h-100\">";
        $results .= "<div class=\"card-header\">";
        $results .= "<h5 class=\"card-title text-center\">".$movie->get_title()."</h5>";
        $results .= "</div>";
        $results .= "<img class=\"card-img-top\" src=\"data:image/jpeg;base64,".Poster::encode_poster($movie->get_posterfilename(),"jpg") ."\" alt=\"Generic placeholder image\">";
        $results .= "<div class=\"card-body\">";
        $results .= "<h6 class=\"card-subtitle mb-2 text-muted\">".$movie->get_releasedyear();
        $results .= "<img class=\"float-right\" src=\"data:image/jpeg;base64,".Poster::encode_icon($icon,$icon_type)."\" style=\"width: 15%;\">";
        $results .= "<small class=\"font-italic\">";
        $results .= "<br />Last updated ".generate_datetime_diff(new TMDateTime($movie->get_datetimelastmodified()));
        $results .= "</small>";
        $results .= "</h6>";
        $results .= "<p class=\"card-text\">";
        $results .= $movie->get_plotsummary();
        $results .= "</p>";
        $results .= "</div>";
        $results .= "<div class=\"card-footer text-muted text-center\">";
        $results .= Moviestatus::find_by_id($movie->get_moviestatusid())->get_description();
        $results .= "</div>";

        $results .= "</div>";
        $results .= "</a>";

        $results .= "</div>";



        // $results .= "<div style=\"text-align:center; display:inline-block; vertical-align:top; padding:10px\">";
        // $results .= "<li class=\"media\">";
        // $results .= "<img class=\"img-fluid img-thumbnail mr-3\" src=\"data:image/jpeg;base64," . Poster::encode_poster($movie->get_posterfilename(),"jpg") ."\" alt=\"Generic placeholder image\">";
        // $results .= "<div class=\"media-body\">";
        // $results .= "<h5 class=\"mt-0 mb-1\">List-based media object</h5>";
        // $results .= "Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.";
        // $results .= "</div>";
        // $results .= "</div>";
        // $results .= "</li>";

        // $results .= "<a href=\"movie_info.php?movieID=" . $movie->get_movieid() . "\">"; // ***
        // $results .=     "<img src=\"data:image/jpeg;base64," . Poster::encode_poster($movie->get_posterfilename(),"jpg") ."\" class=\"poster_thumbnail\" onerror=\"this.src='data:image/jpg;base64," . $no_poster_encoded . "'\" height=\"150\">";  // ***
        // $results .=     "<img src=\"data:image/jpeg;base64," . Poster::encode_icon($icon,$icon_type) ."\" height=\"30px\" class=\"smallicon\">";  // ***
        // $results .=     "<p>" . $new_caption . "</p>";
        // $results .= "</a>";
      }

      // $results .= "</ul>";
      $results .= "</div>";
      $results .= "</div>";
      // $results .= "</div>";

      // pagination
      $from = $pagination->offset() + 1;
      $to = $pagination->offset() + $pagination->get_perpage();
      if ($to > $pagination->get_totalcount()) {
        $to = $pagination->get_totalcount();
      }
      $max_pages = 3;
      // $pagination_out .= "<div class=\"container-fluid text-center\">";
      // $pagination_out .= "Showing results " . $from . " - " . $to . " of " . $pagination->get_totalcount() . " (" . $pagination->total_pages() . " pages) <br />" ;
      // $pagination_out .= "</div>";
      $pagination_out .= "<div class=\"container-fluid\">";
      $pagination_out .= "<nav aria-label=\"Page navigation\">";
      $pagination_out .= "<ul class=\"pagination justify-content-center\">";
      if ($pagination->total_pages() > 1) {

        $pagination_first = ($pagination->has_previous_page() ? "" : " disabled");
        $pagination_out .= "<li class=\"page-item".$pagination_first."\">";
        $pagination_out .= "<a class=\"page-link\" href=\"browse_movies.php?search=" . $_GET["search"] . "&status=" . $status . "&quality=" . $quality . "&sorting=" . $sortingid . "&page=1";
        $pagination_out .= "\" tabindex=\"-1\">First</a>";
        $pagination_out .= "</li>";

        $pagination_previous = ($pagination->has_previous_page() ? "" : " disabled");
        $pagination_out .= "<li class=\"page-item".$pagination_previous."\">";
        $pagination_out .= "<a class=\"page-link\" href=\"browse_movies.php?search=" . $_GET["search"] . "&status=" . $status . "&quality=" . $quality . "&sorting=" . $sortingid . "&page=";
        $pagination_out .= $pagination->previous_page();
        $pagination_out .= "\" tabindex=\"-1\">&laquo;</a>";
        $pagination_out .= "</li>";

        for ($i = 1; $i <= $pagination->total_pages(); $i++) {
          if ($i == $pagination->get_currentpage()) {
            $pagination_out .= "<li class=\"page-item disabled\">";
            $pagination_out .= "<a class=\"page-link\" tabindex=\"-1\" href=\"browse_movies.php?search=" . $_GET["search"] . "&status=" . $status . "&quality=" . $quality . "&sorting=" . $sortingid . "&page=" . $i . "\">".$i."</a>";
            $pagination_out .= "</li>";
          }
          elseif ($pagination->get_currentpage() <= $max_pages+1 && $i <= 2*$max_pages || $pagination->get_currentpage() >= $pagination->total_pages()-($max_pages) && $i >= $pagination->total_pages()-(2*$max_pages-1)) {
            $pagination_out .= "<li class=\"page-item\">";
            $pagination_out .= "<a class=\"page-link\" href=\"browse_movies.php?search=" . $_GET["search"] . "&status=" . $status . "&quality=" . $quality . "&sorting=" . $sortingid . "&page=" . $i . "\">".$i."</a>";
            $pagination_out .= "</li>";
          }
          elseif ($pagination->get_currentpage() <= $max_pages+1 && $i == 2*$max_pages+1 || $pagination->get_currentpage() >= $pagination->total_pages()-($max_pages) && $i == $pagination->total_pages()-(2*$max_pages)) {
            $pagination_out .= "<li class=\"page-item disabled\">";
            $pagination_out .= "<a class=\"page-link\" tabindex=\"-1\" href=\"\">&middot;&middot;&middot;</a>";
            $pagination_out .= "</li>";
          }
          elseif ($i < $pagination->get_currentpage()+$max_pages && $i > $pagination->get_currentpage()-$max_pages) {
            $pagination_out .= "<li class=\"page-item\">";
            $pagination_out .= "<a class=\"page-link\" href=\"browse_movies.php?search=" . $_GET["search"] . "&status=" . $status . "&quality=" . $quality . "&sorting=" . $sortingid . "&page=" . $i . "\">".$i."</a>";
            $pagination_out .= "</li>";
          }
          elseif ($i == $pagination->get_currentpage()+$max_pages || $i == $pagination->get_currentpage()-$max_pages) {
            $pagination_out .= "<li class=\"page-item disabled\">";
            $pagination_out .= "<a class=\"page-link\" tabindex=\"-1\" href=\"\">&middot;&middot;&middot;</a>";
            $pagination_out .= "</li>";
          }
        }

        $pagination_next = ($pagination->has_next_page() ? "" : " disabled");
        $pagination_out .= "<li class=\"page-item".$pagination_next."\">";
        $pagination_out .= "<a class=\"page-link\" href=\"browse_movies.php?search=" . $_GET["search"] . "&status=" . $status . "&quality=" . $quality . "&sorting=" . $sortingid . "&page=";
        $pagination_out .= $pagination->next_page();
        $pagination_out .= "\" tabindex=\"-1\">&raquo;</a>";
        $pagination_out .= "</li>";

        $pagination_last = ($pagination->has_next_page() ? "" : " disabled");
        $pagination_out .= "<li class=\"page-item".$pagination_last."\">";
        $pagination_out .= "<a class=\"page-link\" href=\"browse_movies.php?search=" . $_GET["search"] . "&status=" . $status . "&quality=" . $quality . "&sorting=" . $sortingid . "&page=";
        $pagination_out .= $pagination->total_pages();
        $pagination_out .= "\" tabindex=\"-1\">Last</a>";
        $pagination_out .= "</li>";
      }
      $pagination_out .= "</ul>";
      $pagination_out .= "</nav>";
      $pagination_out .= "</div>";

    }
    else {
      $message = "Couldn't find any movies containing '" . $title . "'.";
    }
  }
}
else {
  $title = "";
  $message = "Search results will appear here.";
}

$search_form  = "";
$search_form .= "<form class=\"\" action=\"browse_movies.php\" method=\"get\">";
$search_form .= "<div class=\"form-row\">";
$search_form .= "<div class=\"form-group col-md-10\">";
$search_form .= "<label for=\"inputSearch\">Search</label>";
$search_form .= "<input id=\"inputSearch\" name=\"search\" class=\"form-control\" type=\"text\" value=\"".$search_term."\" placeholder=\"Browse ".Movie::count_movies()." movies...\" style=\"font-style:italic\">";
$search_form .= "</div>";
$search_form .= "<div class=\"form-group col-md-2 align-self-end\">";
$search_form .= "<input id=\"submitButton\" class=\"btn btn-primary\" type=\"submit\" value=\"Search\">";
$search_form .= "</div>";
$search_form .= "</div>";
// $search_form .= "<div class=\"form-row\">";
// $search_form .= "</div>";
$search_form .= "<div class=\"form-row\">";
$search_form .= "<div class=\"form-group col-md-10\">";
$search_form .= "<button class=\"btn btn-sm btn-secondary\" type=\"button\" data-toggle=\"collapse\" data-target=\"#advancedOptions\" aria-expanded=\"false\" aria-controls=\"advancedOptions\">";
$search_form .= "Show/hide advanced options";
$search_form .= "</button>";
$search_form .= "</div>";
$search_form .= "</div>";
$search_form .= "<div class=\"collapse\" id=\"advancedOptions\">";
$search_form .= "<div class=\"form-row\">";
$search_form .= "<div class=\"form-group col-md-4\">";
$search_form .= "<label for=\"inputMoviesorting\">Sort by</label>";
$search_form .= "<select id=\"inputMoviesorting\" class=\"form-control float-right\" name=\"sorting\">";
$moviesortings = Moviesorting::find_all();
foreach ($moviesortings as $moviesorting) {
  $search_form .= "<option value=\"".$moviesorting->get_moviesortingid()."\"";
  // "<input class=\"btn btn-sm btn-secondary\" type=\"button\" name=\"sorting\">".$moviesorting->get_description()."</button>";
  if ($moviesorting->get_moviesortingid() == $sortingid) {
    $search_form .=  "selected=\"selected\"";
  }
  $search_form .= ">" . $moviesorting->get_description() . "</option>";
}
$search_form .= "</select>";
$search_form .= "</div>";
$search_form .= "<div class=\"form-group col-md-4\">";
$search_form .= "<label for=\"inputMoviestatus\">Moviestatus</label>";
$search_form .= "<select id=\"inputMoviestatus\" class=\"form-control\" name=\"status\">";
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
$search_form .= "<div class=\"form-group col-md-4\">";
$search_form .= "<label for=\"inputMoviequality\">Moviequality</label>";
$search_form .= "<select id=\"inputMoviequality\" class=\"form-control\"  name=\"quality\">";
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
$search_form .= "</div>";
$search_form .= "</div>";
$search_form .= "</form>";

?>

<?php
echo make_page_title("Browse Movies");
echo $session->session_message();
echo form_errors($errors);

echo $search_form;
echo "<hr />";
echo $message;
echo $pagination_out;
echo $results;
echo $pagination_out;

// echo "<hr />";
// echo "<hr />";
// echo $output;


?>


<?php
include($layout_files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
