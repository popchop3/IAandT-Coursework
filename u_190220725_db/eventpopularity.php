<?php 
//This page will show the list of interested students in an event hosted by the current user
include 'inc/dbconnect.php';
//Grabs the db config file for SQL queries

?>
<head>
  <title> Event Details</title>
</head>
<section class="main-container">
  <div class="wrapper">
    <h1>Aston Events</h1>
  </div>
</section>


<h2>Details</h2>

<?php

//Retrieves the event ID from the URL which was passed through the myevents page
if (isset($_GET['event_id'])){
  $event_id=$_GET['event_id'];
//Simple query statement to grab all events with that ID
  try{
    $stmt = $db->query("SELECT * FROM events WHERE id = '$event_id'");
  }
//catches and prints error to user
  catch(PDOException $exception) {
    echo("Error - unable to get data from the database.<br>");
    echo($exception->getMessage());
    exit;
  }

  $data = $stmt->fetch(PDO::FETCH_ASSOC);
  $eventname = $data['name'];
//pritns the event name for the user that is interested
  ?>
  <h3><?php echo $eventname; ?></h3>
  <h4>Students that are ineterested:</h4>
  <?php
  
//grabs all the interested students. Does this by joining to the interest_in_event table and selecting all of them with a row for this event ID in it
  try{
    $rows = $db->query("SELECT `EI`.`event_id`, `forename`,`surname` FROM `users` U INNER JOIN `interest_in_event` EI ON U.`id`=EI.`user_id` WHERE EI.`event_id` = '$event_id'");
  }
  //catches excpetion and prints to user
  catch(PDOException $exception) {
    echo $exception->getMessage();
  }


}

?>


<br><br><form action='myevents.php'>
  <input type="submit" value="Go Back"/>
</form>
<!-- Back button -->



