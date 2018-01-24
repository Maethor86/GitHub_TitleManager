<?php
include("../private/initialize.php");
$layout_files_to_load = load_layout("standard");

include($layout_files_to_load["header"]);
include($layout_files_to_load["sidebar_left_back_browse"]);

if (!($session->is_logged_in() && $session->is_session_valid())) {
  redirect_to("login.php");
}
echo $session->session_message();
?>


<?php


  $movie_id = $_GET["movieID"];
  $movie = Movie::find_by_id($movie_id);
  $createddate = new MyDateTime($movie->get_datetimecreated());

  if ($movie) {
    $output  = "<table border=0>";
    $output .= "<tr>";
    $output .= "<th align=\"left\" width=100px>Movie info</th>";
    $output .= "</tr>";
    $output .= "<tr>";
    $output .= "<td>Title</td>";
    $output .= "<td>".$movie->get_title()." (".$movie->get_releasedyear().")</td>";
    $output .= "</tr>";
    $output .= "<tr>";
    $output .= "<td>Added by</td>";
    $output .= "<td>".User::find_by_id($movie->get_createdbyuser())->get_username().", on ".$createddate->get_presentable_datetime()."</td>";
    $output .= "</tr>";
    $output .= "<tr>";
    $output .= "<td>Runtime</td>";
    $output .= "<td>".$movie->get_runningtime()." min (".$movie->get_runningtime_hours()[0]."h ".$movie->get_runningtime_hours()[1]."min)</td>";
    $output .= "</tr>";
    $output .= "<tr>";
    $output .= "<td>IMDB Rating</td>";
    $output .= "<td>".$movie->get_imdbrating()."/10 (with ".$movie->get_imdbvotes()." votes)</td>";
    $output .= "</tr>";
    $output .= "<tr>";
    $output .= "<td valign=\"top\">Plot</td>";
    $output .= "<td>".$movie->get_plot()."</td>";
    $output .= "</tr>";
    $output .= "</table>";

    $output .= "<ul class=\"form\">";
    $output .= "<li class=\"form\">";
    $output .= "<div>";
    $output .= "<a href=\"edit_movie.php?movieID=";
    $output .= $movie->get_movieid();
    $output .= "\">";
    $output .= "Edit";
    $output .= "</a>";
    $output .= "</div>";
    $output .= "</li>";
    $output .= "<li class=\"form\">";
    $output .= "<div>";
    $output .= "<a href=\"delete_movie.php?movieID=";
    $output .= $movie->get_movieid();
    $output .= "\">";
    $output .= "Delete";
    $output .= "</a>";
    $output .= "</div>";
    $output .= "</li>";
    $output .= "</ul>";
    echo $output;
  }


?>


<?php
include($layout_files_to_load["sidebar_right"]);
include($layout_files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
