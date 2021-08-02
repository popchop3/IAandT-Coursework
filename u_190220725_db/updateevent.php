<?php
//requires the db config file to run this file
require 'inc/dbconnect.php';
if (isset($_GET['event_id'])){
  $event_id=$_GET['event_id'];
  //Retrieve the event id from the last page using a GET request 
}
if(isset($_POST['update'])) {
  //if the form has been submitted
  $errMsg = '';
  //set error message to empty
    // Retrieve the data from the form
  $name = $_POST['name'];
  $description = $_POST['description'];
  $venue = $_POST['venue'];
  $eventdate = $_POST['eventdatetime'];
  if ($_POST['category']=="sport"){
    $category="Sport";
  } elseif ($_POST['category']=="culture"){
    $category="Culture";
  } elseif ($_POST['category']=="other"){
    $category="Other";
    //Above shows case sensitivity being ignored for the event types

    if($name == '')
      $errMsg = 'Enter your event name';
    if($eventdate == '')
      $errMsg = 'Enter the event date and time';
    if($venue == '')
      $errMsg = 'Enter the venue';
    if($description == '')
      $errMsg = 'Enter a description for the event';
    if(!isset($category))
      $errMsg = 'Please select event type';
    //Null checks on the fields - If null, sets the error message to help the user fill in properly

  }


  if($errMsg == ''){
    try{
// if there are no errors, then prepare the SQL query for updating the event
      $sth=$db->prepare("UPDATE `events` 
        SET
        `category`=:category,
        `name`=:name,
        `datetime`=:eventdate,
        `description`=:description,
        `venue`=:venue 
        WHERE `id` = '$event_id'
        ");
      $sth->bindParam(':category', $category, PDO::PARAM_STR, 64);
      $sth->bindParam(':name', $name, PDO::PARAM_STR, 50);
      $sth->bindParam(':eventdate', $eventdate, PDO::PARAM_STR, 50);
      $sth->bindParam(':description', $description, PDO::PARAM_STR, 100);
      $sth->bindParam(':venue', $venue, PDO::PARAM_INT);
      $sth->execute();
//Execute this query for the specified eventID
      ?>
      <p>Your event has been successfully updated </p>
      <a href='/index.php'>Click to go home</a>

      <?php
// Post success message and a link to go back to the home page
    } catch (PDOException $exception) {
//Catches thrown exception
      ?>
      <p>There was an error in the database. Please retry</p>

      <p>(Error message: <?= $exception->getMessage() ?>)</p>
      <a href='/index.php'>Click to go home</a>

      <?php
      //link to go back to the home page
    }


  }
}




if(isset($_GET['action']) && $_GET['action'] == 'joined') {
  $errMsg = 'Your event has been updated. Go <a href="/u_190220725_db/index.php">home</a> to see all events';
}
//This is the success message when the form is completed correctly. It also contains a link to the home page



if (isset($_GET['event_id'])){
  $event_id=$_GET['event_id'];
//get the event details of the eventID that was sent to teh page
  try{
     //prepare the query to get the event details and store in a variable
    $rows = $db->query("SELECT * FROM events WHERE id = '$event_id'");
  }

  catch(PDOException $exception) {
    echo("Error - unable to retrieve data from the database.<br>");
    echo($exception->getMessage());
    exit;
  }//catch the exception and print error message to the user
}



?>

<!-- Set the title of the page -->
<html>
<head><title>Edit Event</title></head>
<style>
html, body {
  margin: 3px;
  border: 0;
}
</style>



<?php




//check that there are rows of data. if so, loop through and grab the row results and outputs the to a form so that you can edit. 
if($rows->rowCount() > 0) {
  foreach($rows as $row) {
    $eventid=$row['id'];
    ?>



<!-- Below code sets up the HTML page for the user to edit an event. i.e by adding buttons for which category the event is, adding the name/date and time etc-->
    <body>
      <div align="center">
        <div style=" border: solid 1px #006D9C; " align="left">
          <?php
          if(isset($errMsg)){
            echo '<div style="color:#FF0000;text-align:center;font-size:17px;">'.$errMsg.'</div>';
          }
          ?>
          <div style="background-color:#006D9C; color:#FFFFFF; padding:10px;"><b>Edit <?= $row['name'] ?></b></div>
          <div style="margin: 15px">
            <form action="" method="post">
              Event Name <br>
              <input type="text" name="name" value="<?= $row['name'] ?>" autocomplete="off" class="box"/><br /><br />
              Description <br>
              <input type="text" name="description" value="<?= $row['description'] ?>" autocomplete="off" class="box"/><br /><br />

              Please Choose Category: <br>
              Sport <input type="radio" value="sport" name="category" <?php if ($row['category']=='Sport'){ ?> checked <?php } ?> required />

              Culture <input type="radio" value="culture" name="category" <?php if ($row['category']=='Culture'){ ?> checked <?php } ?> />

              Other <input type="radio" value="other" name="category" <?php if ($row['category']=='Other'){ ?> checked <?php } ?> /><br /><br />

              Venue: <br>
              <input type="text" name="venue" value="<?= $row['venue'] ?>" autocomplete="off" class="box"/><br /><br />
              Date and time: <br>
              <input type="datetime-local" name="eventdatetime" value="<?= $row['datetime'] ?>" autocomplete="off" class="box"/><br /><br />

              <input type="submit" name='update' value="Update" class='submit'/><br />
              <!-- Button for the user to submit their edited event form -->
            </form>


          </div>
        </div>
        <br><br><form action='/index.php'>
          <!-- Button back to home page -->
          <input type="submit" value="Home"/>
        </form>
      </div>
    </body>
    </html>


    <?php
  }
}
?>