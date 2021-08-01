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
    <h1>Aston Events</h1>
  </div>
</section>




<h2>Details</h2>

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

    Category : <?php echo $row['category']; ?><br>
    Description : <?php echo $row['description']; ?><br>
    Datetime of event : <?php echo $row['datetime']; ?><br>
    Popularity : <?php
    $e_id = $row['id'];
    //Work out the popularity of the event - I.e the number of people who have shown an interest in it
    try{
      $stmt = $db->query("SELECT COUNT(*) AS popularity, E.`id` FROM `interest_in_event` EI
        INNER JOIN `events` E
        ON EI.`event_id` = E.`id`
        WHERE E.`id` = '$e_id'
        GROUP BY E.`id`");
    //create query to get the number of people interested in that specific eventID

      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      $popularity = $data['popularity'];
    }
    catch(PDOException $exception) {
      echo $exception->getMessage();
    }
    //catch exception and print message to user
    if (isset($popularity)){
      echo $popularity;
    } else {
      echo "0";
    }

    ?>

    <br> Organiser : <?php
    $o_id = $row['id'];
    try{
      $stmt = $db->query("SELECT `forename`,`surname`,`name` FROM `events` E
        INNER JOIN `users` U
        ON E.`organiser_id` = U.`id`
        WHERE E.`id` = '$o_id'");
//Query to retrieve the organisers full name and print it to the user
      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      $organiser_forename = $data['forename'];
      $organiser_surname = $data['surname'];
    }
    catch(PDOException $exception) {
      echo $exception->getMessage();
    }

    echo $organiser_forename." ".$organiser_surname;
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


