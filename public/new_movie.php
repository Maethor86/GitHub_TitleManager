<?php
include("../private/initialize.php");
$layout_files_to_load = load_layout("standard");

include($layout_files_to_load["header"]);
include($layout_files_to_load["sidebar_left"]);

if (!($session->is_logged_in() && $session->is_session_valid())) {
  redirect_to("login.php");
}
echo $session->session_message();
echo make_page_title("Add New Movie");
?>

<?php

$title = "";
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



?>

<?php
echo form_errors($errors);

$output  = "<form action=\"new_movie.php\" method=\"post\">";
$output .= "<ul class=\"form\">";
$output .= "Title:";
$output .= "<li class=\"form\">";
$output .= "<div><input type=\"text\" name=\"title\" placeholder=\"Enter title...\" style=\"font-style:italic\" value=$title></div>";
$output .= "<div><input type=\"submit\" name=\"create_movie\" value=\"Add\" /></div></li>";
$output .= "</ul></form>";
// $output .= "<a href=\"browse_movies.php\">Cancel</a>";
echo $output;

/*

function create_movie($post) {
  // must remember to check if title is unique

  $title = $post["title"];

  $date_time_created = generate_datetime_for_sql();

  $safe_title = sql_stringprep($title);

  $query  = "INSERT INTO Movies (DateTimeCreated, Title)";
  $query .= " VALUES (?, ?)";

  $params = array($date_time_created, $safe_title);

  try {
    $created_movie = sql_request_query($query, $params);
  }
  catch (exception $e) {
    sql_log_errors($e, sqlsrv_errors());
    if ($e->getCode() == EXCEPTION_CODE_SQL_CONFIRM_QUERY) {
      $_SESSION["message"] .= "A movie with that title already exists.<br />";
    }
    else {
      $_SESSION["error"] .= make_exception_message_to_user($e);
    }
  }
  return $created_movie;

}
*/

/*
function edit_movie($post) {
  $movie_id = $_GET["movieID"];
  $movie = find_movie_title_by_movie_id($movie_id);
  if ($movie) {
    $safe_movie_id = sql_stringprep($movie_id);
    $safe_title = sql_stringprep($post["new_title"]);

    $query  = "UPDATE Movies";
    $query .= " SET Title = ?";
    $query .= " WHERE MovieID = ?";

    $params = array($safe_title, $safe_movie_id);

    $edited_movie = sql_request_query($query, $params);
    return $edited_movie;
  }
  else {
    // i dont think this can ever happen
    $_SESSION["message"] .= "Couldn't find movie.<br />";
    return FALSE;
  }
}

function delete_movie($get) {

  $movie_id = $get["movieID"];
  $movie = find_movie_by_movie_id($movie_id);
  if ($movie) {

    // if ($user["UserRoleID"] == 1) {
    //   $_SESSION["message"] .= "Can't delete admin.<br />";
    //   return FALSE;
    // }

    // elseif ($user_id == $_SESSION["user_id"]) {
    //   $_SESSION["message"] .= "Can't delete the user that is logged in.<br />";
    //   return FALSE;
    // }


    $safe_movie_id = sql_stringprep($movie_id);

    $query  = "DELETE FROM Movies";
    $query .= " WHERE MovieID = ?";

    $params = array($safe_movie_id);

    $deleted_movie = sql_request_query($query, $params);
    return $deleted_movie;

  }
  else {
    // i dont think this can ever happen
    $_SESSION["message"] .= "Couldn't find movie.<br />";
    return FALSE;
  }


*/
?>

<?php
include($layout_files_to_load["sidebar_right"]);
include($layout_files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
