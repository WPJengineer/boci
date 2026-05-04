<?php

// start session to be able to work with session variables.
session_start();

if (!isset($_SESSION['customer_id'])) {
  header("Location: /student014/boci/backend/forms/form_login.php");
  exit();
}

// reset all session variables - missing cookies when we add them for guest sessions.
$_SESSION = [];

// end session to log out before we redirect.
session_destroy();

// redirection to homepage after logging out.
header("Location: /student014/boci/index.html");
exit();

?>