<?php
include("../private/initialize.php");
$layout_files_to_load = load_layout("login");

include($layout_files_to_load["header"]);
include($layout_files_to_load["sidebar_left"]);

if ($session->is_logged_in() && $session->is_session_valid()) {
  redirect_to("index.php");
}
?>



<?php
$empty = "empty";
if (isset($_POST["login"])) {
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // check db if username/pwd exists
  $found_user = User::authenticate($username, $password);

  if ($found_user) {
    $session->login($found_user);
    // $logger->database_create_user_log($found_user);
    redirect_to("index.php");
  }
  else {
    $message = "Username/password incorrect";
  }

}
else {
  $username = "";
  $password = "";
  $message = $empty;
}
?>

<form class="form-signin" method="post">
  <h1 class="display-4 mb-3">
    <span class="text-nowrap">Title Manager</span>
  </h1>
  <img class="mb-4" src="images/site/site-logo.png" alt="" width="72" height="72">
  <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
  <label for="username" class="sr-only">Email address</label>
  <input type="text" name="username" class="form-control" value="<?php echo $username ?>" placeholder="Username" required autofocus>
  <label for="password" class="sr-only">Password</label>
  <input type="password" name="password" class="form-control" placeholder="Password" required>
  <!-- <div class="checkbox mb-3">
    <label>
      <input type="checkbox" value="remember-me"> Remember me
    </label>
  </div> -->
  <button class="btn btn-lg btn-primary btn-block" name="login" type="submit">Sign in</button>
    <?php $class = "container my-3 text-dark bg-warning";
    $message == $empty ? $class .= " invisible" : $class .= " visible"; ?>
  <div class="<?php echo $class?>">
    <div class="row">
      <div class="col-sm">
        <?php
          echo $message;
          echo $session->session_message();
        ?>
      </div>
    </div>
  </div>
</form>

<?php
include($layout_files_to_load["footer"]);
?>

<?php include(LIB_PATH.DS."deinitialize.php");?>
