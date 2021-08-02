<?php 

include 'inc/dbconnect.php';
//include DB config file so we can query DB and add 1 to the interest number of an event - this decides 

//start session and grab session data for ID of user from index.php page
session_start();
$user_id = $_SESSION['id'];

if (isset($_GET['event_id'])){
  $event_id=$_GET['event_id'];
}


try{
// prepares SQL query to insert interest into the event ID
      $sth=$db->prepare("INSERT INTO `interest_in_event` (
      	`event_id`, 
      	`user_id`) 
      	VALUES (
      	:event_id,
      	:user_id
        )");
      $sth->bindParam(':event_id', $event_id, PDO::PARAM_INT);
      $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
      $sth->execute();

      header("Location: index.php");
      //Sends user back to home page

    } catch (PDOException $exception) {
    //Catches exception in database
      ?>
      <p>Error with the database. please try again..</p>
<!-- Prints error message on failure. Also provide a home button -->
      <p>(Error message: <?= $exception->getMessage() ?>)</p>
      <a href='/index.php'>Go back home</a>

      <?php
    }




?>

