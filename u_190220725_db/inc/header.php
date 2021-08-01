<?php
//header, attached to the login
//Will show both the login & register button if there is no user in the session(no user logged in) 
//Otherwise will show who is logged in, with a logout button for this user

	//start the session for the user
session_start();
?>

<html>
<body>
	<header>
		<nav>
			<div class="navbar">
				<!-- Welcome logged in user's to the website -->
				<?php
                //Check if the user has a saved session and is logged in
				if(isset($_SESSION['email'])) {
					?>
                    <!-- Show the user they are logged in. And have a "logout" button next to their name -->
					<p> Logged in as: <?php echo $_SESSION['forename']; ?> </p>
					<a href='inc/logout.php'>Click to log out</a>


				<?php } else { ?>
					<!-- If they aren't logged in, show them a login button -->
					<form action='inc\login.php'>
						<input type="submit" value="Login"/>
					</form>
                    <!-- Or the option of a Sign up button -->
					<form action='register.php'>
						<input type="submit" value="Sign Up"/>
					</form>
				<?php } ?>
			</div>
		</nav>
	</header>