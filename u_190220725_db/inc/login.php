<?php
session_start();
require_once 'dbconnect.php';
//Starts the session when the user is about to login and grabs the DB configuration file with it's properties


if(isset($_POST['login'])) {
	// if the form has been completed and submitted, run the PHP code below
	$errMsg = '';
	//set the error message to empty
		// Retrieve the data from form. if it's empty, fill the error message with correct error.
	$email = $_POST['email'];
	$password = $_POST['password'];
	if($email == '')
		$errMsg = 'Enter email';
	if($password == ''){
		$errMsg = 'Enter password';
	} else {
		$password = crypt($_POST['password'],'$1$somethin$');
		//The above line encrypts the password, so that it matches the encrypted password which has been inputted into the database.
	}

	if($errMsg == '') {
		//checks that there still aren't any errors in the HTML form
		try {
			$stmt = $db->prepare('SELECT id, phoneNo, forename, email, password, student, organiser FROM users WHERE email = :email');
			//Prepares the query for the database for the required data when the email matches
			$stmt->execute(array(
				':email' => $email
			));

			$data = $stmt->fetch(PDO::FETCH_ASSOC);
			//Collects these results into the "data" variable. This is able to hold even an array of results. 
			if($data == false){
				$errMsg = "$email was not found in the database.";
				//If the user's inputted email isn't found in the database, fill the error message varaible with this detail
			}
			else {
				if($password == $data['password']) {
					//if the user's password matches the one that is saved in the DB
					$_SESSION['forename'] = $data['forename'];
					$_SESSION['surname'] = $data['surname'];
					$_SESSION['email'] = $data['email'];
					$_SESSION['id'] = $data['id'];
					$_SESSION['student'] = $data['student'];
					$_SESSION['organiser'] = $data['organiser'];
					header('Location: /u_190220725_db/index.php');
					exit;
                    //Grab the data from the database(if the email matches) and then save to session variables so that we can use later in the website.
				}
				else
					$errMsg = 'The password not match in the database.';
				//If the password doesn't match the one in the DB, output the error message
			}
		}
		catch(PDOException $exception) {
			$errMsg = $exception->getMessage();
			//if our DB query does not work, output the exception
		}
	}
}


?>


<!-- Below is the HTML form used for logging in... -->
<html>
<head><title>Login</title></head>
<style>
html, body {
	margin: 1px;
	border: 0;
}
</style>
<body>
	<div align="center">
		<div style=" border: solid 1px #006D9C; " align="left">
			<?php
			if(isset($errMsg)){
				echo '<div style="color:#FF0000;text-align:center;font-size:17px;">'.$errMsg.'</div>';
				//Format the error message if there is one
			}
			?>
			<div style="background-color:#006D9C; color:#FFFFFF; padding:10px;"><b>Login</b></div>
			<div style="margin: 15px">
				<form action="" method="post">
					<input type="text" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email'] ?>" 
					autocomplete="off" class="box"/><br /><br />
					<!-- If an error occurs whilst filling in the form, the values will remain so that they can modify their existing form and not fill all the details out again -->
					<input type="password" name="password" value="<?php if(isset($_POST['password'])) echo $_POST['password'] ?>" autocomplete="off" class="box" /><br/><br />
					<!-- Hides the password when it is being typed in -->
					<input type="submit" name='login' value="Login" class='submit'/><br />
					<!-- This submit button sends the data and initialises the PHP script above -->
				</form>
			</div>
		</div>
		<br><br><form action='/u_190220725_db/index.php'>
			<input type="submit" value="Home"/>
		</form>
		<!-- Home button -->
	</div>
</body>
</html>

