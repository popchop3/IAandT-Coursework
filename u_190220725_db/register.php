<?php
require 'inc/dbconnect.php';
//Require the DB config file so we have query database

//Retrieve the data from the "register" form once submitted
if(isset($_POST['register'])) {
	$errMsg = '';
	$forename = $_POST['forename'];
	$surname = $_POST['surname'];
	$email = $_POST['email'];
    //Sets a binary value for the organiser and students. I.e if the user is a student, we will set it to 1 (true) and organiser to 0(false). Then vice versa for organisers
	if ($_POST['user_type']=="student"){
		$student="1";
		$organiser="0";
	} else{
		$student="0";
		$organiser="1";
	}
	$password = $_POST['password'];
	$password_ecrypted = crypt($_POST['password'],'$1$somethin$');
	//encrypt the password so it matches what we have in the database

	//Check for empty fields in the form and send message back to user if there are
	if($forename == '')
		$errMsg = 'Please enter your forename';
	if($surname == '')
		$errMsg = 'Please enter your surname';
	if($email == '')
		$errMsg = 'Please enter your email address';
	if($password == '')
		$errMsg = 'Please enter your password';
	if(!isset($student))
		$errMsg = 'Please select your user type';


	if($errMsg == ''){
		//if there is no error, then allow the user to continue to register
		try{
			//query the database and insert the user to register them
			$sth=$db->prepare("INSERT INTO `users` 
				(`email`,
				`password`,
				`forename`,
				`surname`,
				`student`,
				`organiser`) 
				VALUES (
				:email,
				:password,
				:forename,
				:surname,
				:student,
				:organiser) 
				");
			$sth->bindParam(':email', $email, PDO::PARAM_STR, 60);
			$sth->bindParam(':password', $password_ecrypted, PDO::PARAM_STR, 50);
			$sth->bindParam(':forename', $forename, PDO::PARAM_STR, 40);
			$sth->bindParam(':surname', $surname, PDO::PARAM_STR, 40);
			$sth->bindParam(':student', $student, PDO::PARAM_INT);
			$sth->bindParam(':organiser', $organiser, PDO::PARAM_INT);
			$sth->execute();


			//Output the success message to the user. Then allow them to go back to the home page(index.php) and login
			?>
			<p>You have been registered successfully! </p>
			<a href='/u_190220725_db/index.php'>Click to go home/login</a>
			<?php
		} catch (PDOException $exception) {
        //catches the DB exception when a user is trying to register
			?>
			<p>Error with the database. please try again.</p>

			<p>(Error message: <?= $exception->getMessage() ?>)</p>
			<a href='/u_190220725_db/inc/signup.php'>Click here to sign up again</a>
			<?php
		}
	}
}
?>


<!-- Below is the HTML to register the form -->
<html>
<head><title>Register new user</title></head>
<style>
html, body {
	margin: 2px;
	border: 0;
}
</style>
<body>
	<div align="center">
		<div style=" border: solid 1px #006D9C; " align="left">
			<?php
			if(isset($errMsg)){
				echo '<div style="color:#FF0000;text-align:center;font-size:17px;">'.$errMsg.'</div>';
			}
			?>
			<div style="background-color:#006D9C; color:#FFFFFF; padding:11px;"><b>Register here</b></div>
			<div style="margin: 16px">
				<form action="" method="post">
					<!-- Have kept placeholder so that the user knows what to type-->
					<input type="text" name="forename" placeholder="Forename" value="<?php if(isset($_POST['forename'])) echo $_POST['forename'] ?>" autocomplete="off" class="box"/><br /><br />
					<input type="text" name="surname" placeholder="Surname" value="<?php if(isset($_POST['surname'])) echo $_POST['surname'] ?>" autocomplete="off" class="box"/><br /><br />

					<!-- Email address is checked against the Regex pattern so it matches @aston.ac.uk fromatting  -->
					<input type="text" name="email" placeholder="Aston University email" 
					pattern="[a-z0-9._%+-]+@aston.ac.uk$"
					value="<?php if(isset($_POST['email'])) echo $_POST['email'] ?>" autocomplete="off" class="box"/><br /><br />

					<!-- Use of radio button so that they can only choose 1 of the 2 options -->
					Which user are you?: <br>
					Student <input type="radio" value="student" name="user_type" required />

					Organiser <input type="radio" value="organiser" name="user_type" /><br /><br />


					<!-- Kept type as password so that it is hashed for the user. More secure -->
					<input type="password" name="password" placeholder="Password" value="<?php if(isset($_POST['password'])) echo $_POST['password'] ?>" class="box" /><br/><br />
					<input type="submit" name='register' value="Register" class='submit'/><br />
					<!-- Submit button to run PHP above-->
				</form>


			</div>
		</div>
		<br><br><form action='index.php'>
			<input type="submit" value="Home"/>
		</form>
		<!-- Home button -->
	</div>
</body>
</html>