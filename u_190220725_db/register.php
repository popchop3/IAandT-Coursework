<?php
require 'inc/dbconnect.php';
//Require the DB config file so we have query database

//Retrieve the data from the "register" form once submitted
if(isset($_POST['register'])) {
	$errMsg = '';
	$forename = $_POST['forename'];
	$surname = $_POST['surname'];
	$email = $_POST['email'];
	$phoneNo = $_POST['phoneNo'];
    
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
	if($phoneNo == '')
		$errMsg = 'Please enter your phone number';
	if($password == '')
		$errMsg = 'Please enter your password';


	if($errMsg == ''){
		//if there is no error, then allow the user to continue to register
		try{
			//query the database and insert the user to register them
			$sth=$db->prepare("INSERT INTO `users` 
				(`email`,
                `phoneNo`,
				`password`,
				`forename`,
				`surname`) 
				VALUES (
				:email,
                :phoneNo,
				:password,
				:forename,
				:surname) 
				");
			$sth->bindParam(':email', $email, PDO::PARAM_STR, 60);
        	$sth->bindParam(':phoneNo', $phoneNo, PDO::PARAM_STR, 60);
			$sth->bindParam(':password', $password_ecrypted, PDO::PARAM_STR, 50);
			$sth->bindParam(':forename', $forename, PDO::PARAM_STR, 40);
			$sth->bindParam(':surname', $surname, PDO::PARAM_STR, 40);
			$sth->execute();


			//Output the success message to the user. Then allow them to go back to the home page(index.php) and login
			?>
			<p>You have been registered successfully! </p>
			<a href='/index.php'>Click to go home/login</a>
			<?php
		} catch (PDOException $exception) {
        //catches the DB exception when a user is trying to register
			?>
			<p>Error with the database. please try again.</p>

			<p>(Error message: <?= $exception->getMessage() ?>)</p>
			<a href='/register.php'>Click here to register again</a>
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
		<div style=" border: solid 1px #165929; " align="left">
			<?php
			if(isset($errMsg)){
				echo '<div style="color:#FF0000;text-align:center;font-size:17px;">'.$errMsg.'</div>';
			}
			?>
			<div style="background-color:#165929; color:#FFFFFF; padding:11px;"><b>Register here</b></div>
			<div style="margin: 16px">
				<form action="" method="post">
					<!-- Have kept placeholder so that the user knows what to type-->
					<input type="text" name="forename" placeholder="Forename" value="<?php if(isset($_POST['forename'])) echo $_POST['forename'] ?>" autocomplete="off" class="box"/><br /><br />
					<input type="text" name="surname" placeholder="Surname" value="<?php if(isset($_POST['surname'])) echo $_POST['surname'] ?>" autocomplete="off" class="box"/><br /><br />

					<!-- Email address is checked against the Regex pattern so it matches @aston.ac.uk fromatting  -->
					<input type="text" name="email" placeholder="Aston University email" 
					pattern="[a-z0-9._%+-]+@aston.ac.uk$"
					value="<?php if(isset($_POST['email'])) echo $_POST['email'] ?>" autocomplete="off" class="box"/><br /><br />

                	<input type="text" name="phoneNo" placeholder="Phone Number" value="<?php if(isset($_POST['phoneNo'])) echo $_POST['phoneNo'] ?>" autocomplete="off" class="box"/><br /><br />
                	
					


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