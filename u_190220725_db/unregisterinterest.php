<?php
include 'inc/dbconnect.php';
//Inlcude DB config file so we can query datadbase to remove user's interest
session_start();
$user_id = $_SESSION['id'];
//starts session and sets user's id to that in session varable

if (isset($_GET['event_id'])){
  $event_id=$_GET['event_id'];
  //retrieve event ID from URL
}

try{
//Prepare query to remove the userID who is logged in from the event interest. 
  $sth=$db->prepare("DELETE 
    FROM `interest_in_event` 
    WHERE `event_id` = :event_id 
    AND `user_id` = :user_id
    ");
  $sth->bindParam(':event_id', $event_id, PDO::PARAM_INT);
  $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
  $sth->execute();

  header("Location: index.php");
//return user to home page to abstract away from code
} catch (PDOException $exception) {
//catches error from DB
  ?>
  <p>Error with the database. please try again.</p>

  <p>(Error details: <?= $exception->getMessage() ?>)</p>
  <a href='/astonevents/index.php'>Go back home</a>
  <!-- prints error message and a link(href) to go back to the home page-->
  <?php
}

?>