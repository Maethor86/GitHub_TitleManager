<?php
include("../private/initialize.php");
$layout_files_to_load = load_layout("standard");

include($layout_files_to_load["header"]);
include($layout_files_to_load["sidebar_left_back"]);

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


    // foreach ($users as $user) {
    //   $output .= "<tr><td>";
    //   $output .= $user->get_username();
    //   $output .= "</td><td><a href=\"edit_user.php?userID=".$user->get_userid()."\">Edit</a>";
    //   $output .= " <a href=\"delete_user.php?userID=".$user->get_userid()."\">Delete</a></td>";
    //   $output .= "</tr>";
    // }
    // $output .= "</table>";
    // $output .= "<br />";
    // $output .= "<a href=\"new_user.php\">Add new user</a>";
    // $output .= "</table>";

    // $output .= "Movie Info: <br />";
    // $output .= "<ul class=\"form\">";
    // $output .= "<li class=\"form\">";
    // $output .= "<div>Title:</div><div>".$movie->get_title()." (".$movie->get_releasedyear().")</div>";
    // $output .= "</li>";
    // $output .= "<li class=\"form\">";
    // $output .= "<div>Added to TitleManager:</div><div>".$movie->get_datetimecreated()."</div>";
    // $output .= "</li>";
    // $output .= "<li class=\"form\">";
    // $output .= "<div>Added by:</div><div>".User::find_by_id($movie->get_createdbyuser())->get_username()." on ".$movie->get_datetimecreated()."</div>";
    // $output .= "</li>";
    // $output .= "<li class=\"form\">";
    // $output .= "<div>Runtime:</div><div>".$movie->get_runningtime()." min</div>";
    // $output .= "</li>";
    // $output .= "<li class=\"form\">";
    // $output .= "<div>IMDB Rating:</div><div>".$movie->get_imdbrating()."/10 (with ".$movie->get_imdbvotes()." votes)</div>";
    // $output .= "</li>";
    // $output .= "<li class=\"form\">";
    // $output .= "<div>Plot:</div><div>".$movie->get_plot()."</div>";
    // $output .= "</li>";

    // $output .= "<li class=\"form\">";
    // $output .= "<div>IMDB Rating:</div><div><span class=\"imdbRatingPlugin\" data-user=\"\" data-title=".$movie->get_imdbid()." data-style=\"p4\">
    //     </a></span><script>(function(d,s,id){var js,stags=d.getElementsByTagName(s)[0];if(d.getElementById(id)){return;}js=d.createElement(s);js.id=id;js.src=\"http://g-ec2.images-amazon.com/images/G/01/imdb/plugins/rating/js/rating.min.js\";stags.parentNode.insertBefore(js,stags);})(document,'script','imdb-rating-api');</script></div>";
    // $output .= "</li>";
    // $output .= "</ul>";
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
