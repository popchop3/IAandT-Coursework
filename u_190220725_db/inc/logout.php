<?php
//Starts the session
session_start();
//Immediately ends the session, so that session data is lost and the user is logged out. This will then enable another user to login
session_destroy();
echo 'You have been logged out. <a href="/u_190220725_db/index.php">Go back</a>';o
?>