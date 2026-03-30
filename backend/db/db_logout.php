<?php

// start session to be able to work with session variables.
session_start();

// reset all session variables - missing cookies when we add them for guest sessions.
$_SESSION = [];

// end session to log out before we redirect.
session_destroy();

// redirection to homepage after logging out.
header("Location: /boci/index.html");
exit();

?>