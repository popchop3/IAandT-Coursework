<?php 
//include the DB connection configuration file and starts the session
include 'inc/dbconnect.php';
session_start();


?>
<head>
  <title>Event Details</title>
</head>
<section class="main-container">
  <div class="wrapper">
  </div>
</section>




<h2>Event Details</h2>

<?php


if (isset($_GET['event_id'])){
  $event_id=$_GET['event_id'];
//Retrive the event ID that was sent from the index.php page
  try{
    $rows = $db->query("SELECT * FROM events WHERE id = '$event_id'");
    //Set up the query used to get the data from the database where the eventID is equal to user input
  }
//Catch the error and print message to user
  catch(PDOException $exception) {
    echo("Error - Unable to get data from the database.<br>");
    echo($exception>getMessage());
    exit;
  }
}


if($rows->rowCount() > 0) {
  foreach($rows as $row) {
    $eventid=$row['id'];
    //check that there are rows of data. if so, loop through and grab the row results and outputs the to a form so that you can edit. This also includes the picture related to that event
    ?>


    <h3><?php echo $row['name']; ?></h3>

	<b> Category:</b> <?php echo $row['category']; ?><br>
    <b>Description:</b> <?php echo $row['description']; ?><br>
    <b>Date & time:</b> <?php echo $row['datetime']; ?><br>
    <b>People interested:</b> <?php
    $e_id = $row['id'];
    //Work out the popular an event is based on interest - I.e the number of people who have shown an interest in it
    try{
      $stmt = $db->query("SELECT COUNT(*) AS interest, E.`id` FROM `interest_in_event` EI
        INNER JOIN `events` E
        ON EI.`event_id` = E.`id`
        WHERE E.`id` = '$e_id'
        GROUP BY E.`id`");
    //create query to get the number of people interested in that specific eventID

      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      $interest = $data['interest'];
    }
    catch(PDOException $exception) {
      echo $exception->getMessage();
    }
    //catch exception and print message to user
    if (isset($interest)){
      echo $interest;
    } else {
      echo "0";
    }

    ?>

    <br> <b>Organiser:</b> <?php
    $o_id = $row['id'];
    try{
      $stmt = $db->query("SELECT `forename`,`surname`,`name`,`phoneNo`,`email`, `venue` FROM `events` E
        INNER JOIN `users` U
        ON E.`organiser_id` = U.`id`
        WHERE E.`id` = '$o_id'");
	//This query retrieves the organisers details and retruns them to the user
      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      $organiser_forename = $data['forename'];
      $organiser_surname = $data['surname'];
      $organiser_phoneNo = $data['phoneNo'];
      $organiser_email = $data['email'];
      $organiser_venue = $data['venue'];
    }
    catch(PDOException $exception) {
      echo $exception->getMessage();
    }

    echo $organiser_forename." ".$organiser_surname;
    echo '<br><b>Email: </b>'.$organiser_email;
    echo '<br><b>Phone Number:</b> +44'.$organiser_phoneNo;
    echo '<br><b>Venue: </b>'.$organiser_venue;
    ?>
    <br><br>
<!-- retrieve the picture filename, which is in a table column, and then grab the file from the "pictures" folder on the server. -->
    <img src="pictures/<?= $row['picture'] ?>" alt="<?= $row['name']?>" width="460" height="345"> 

    <br>
    <?php
  }
} 

//Check if the user is an organiser, and if they are then show an "Edit Event" button for them to update details of the event.
  if ($_SESSION['organiser']=='1' && $o_id==$_SESSION['id']){ 

?>

    <a href='updateevent.php?event_id=<?php echo $eventid ?>'>Edit Event</a>

  <?php }

 ?>


<br><br><form action='index.php'>
  <input type="submit" value="HOME"/>
</form>
<!-- Home button

Link to the page to send an email to the event organiser -->
   <a href='sendmail.php?event_id=<?php echo $eventid ?>'>Send mail to the event Organiser</a>


