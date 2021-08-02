<?php
require 'inc/dbconnect.php';
//Must require the DB config file so that we can post data about a new event to the DB
session_start();
//start the session and grab the ID
$o_id=$_SESSION['id'];




if(isset($_POST['addevent'])) {
//Check if the form has been submitted. if so, run the follow code



//Below code uploads the user's photo to the event
  echo "Event picture is currently being uploaded to the pictures directory...<br>";
  $target_dir = "pictures/";
  $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
  $uploaderror = 0;
  $pictureFileType = strtolower(pathinfo($targetfile,PATHINFO_EXTENSION));

//check to see if the file already exists
  if (file_exists($targetfile)) {
    echo "This file already exists in the database.<br>";
    $uploaderror = 0;
  }
    //check that the file size is not too large
  if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Your file is too large to upload.";
    $uploaderror = 0;
  }
    // Check the file is in the correct format
  if($pictureFileType != "jpg" && $pictureFileType != "png" && $pictureFileType != "jpeg"
    && $pictureFileType != "gif" ) {
    echo "Only the following file formats are allowed: JPEG, PNG, JPG, GIF<br>";
  $uploaderror = 0;
}
// If there is an errro with uploading. The upload error will be set to 0. Check for this, and if it is then print to the user that file was not able to be uploaded
if ($uploaderror == 0) {
  echo "Your file was not able to be uploaded."; ?>
  <a href='newevent.php'>Click here to try uploading again</a>

  <?php
    // If there is no error, upload the file
} else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetfile)) {
    //print that the file has been uploaded successfully
    //header("Location: newevent.php");
  } else {
    echo "An error occured while uploading your file. Please try again";
  }
}


$errMsg = '';
//set error message to empty
// retrieve the new event data from the form
$event_name = $_POST['event_name'];
$description = $_POST['description'];
$eventdate = $_POST['datetime'];
$venue = $_POST['venue'];


//set the category based on the category specified in the form
if ($_POST['category']=="sport"){
  $category="Sport";
} elseif ($_POST['category']=="other"){
  $category="Other";
} elseif ($_POST['category']=="culture"){
  $category="Culture";

//do null checks on the different fields and ask the user to enter again if so
  if($event_name == '')
    $errMsg = 'Please enter the name of the event';
  if($description == '')
    $errMsg = 'Please enter the description of the event';
  if($venue == '')
    $errMsg = 'Please enter the venue that the event is hosted in';
  if(!isset($category))
    $errMsg = 'Please enter the event category';
  if($eventdate == '')
    $errMsg = 'Please enter the event timings';
}



if($errMsg == ''){
  try{
//Check the the form contains no errors. If so, input this data into the database
    $sth=$db->prepare("INSERT INTO `events`(
      `category`, 
      `name`, 
      `datetime`, 
      `description`, 
      `organiser_id`, 
      `venue`,
      `picture`)
      VALUES (
      :category,
      :name,
      :eventdate,
      :description,
      :o_id,
      :venue,
      :picture) 
      ");
    $sth->bindParam(':category', $category, PDO::PARAM_STR, 40);
    $sth->bindParam(':name', $event_name, PDO::PARAM_STR, 40);
    $sth->bindParam(':eventdate', $eventdate, PDO::PARAM_STR, 40);
    $sth->bindParam(':description', $description, PDO::PARAM_STR, 225);
    $sth->bindParam(':o_id', $o_id, PDO::PARAM_INT);
    $sth->bindParam(':venue', $venue, PDO::PARAM_STR,40);
    $sth->bindParam(':picture', $target_file, PDO::PARAM_STR,40);
    $sth->execute();

    //print success message once the event is added. Also add link (href) to the event page. 
    ?>
    <p>Your event was created successfully </p>
    <a href='index.php'>View all events</a>

    <?php

  } catch (PDOException $exception) {
    //catches the exception and prints error message 
    ?>
    <p>Error with the database. please try again.</p>

    <p>(Error message: <?= $exception->getMessage() ?>)</p>
    <a href='/u_190220725_db/newevent.php'>Add another new event</a>

    <?php
  }


}
}

?>

<html>
<head><title>Add Event</title></head>
<style>
html, body {
  margin: 2px;
  border: 0;
}
</style>
<body>
  <div align="center">
    <div style=" border: solid 2px #165929; " align="left">
      <?php
      if(isset($errMsg)){
        echo '<div style="color:#FF0000;text-align:center;font-size:17px;">'.$errMsg.'</div>';
      }
      ?>
      <div style="background-color:#165929; color:#FFFFFF; padding:10px;"><b>Add a new event</b></div>
      <div style="margin: 15px">
        <form action="" method="post">
          <!-- Below is the form for adding a new event -->
          <div class="header">   
            <h3>Enter the details of the event below</h3>
          </div>  
          <div class="sep"></div>
          <div class="inputs">

          <b>Event Name:</b> <input type="text" name="event_name" required /><br />
          	<b>Description: </b> <input type="text" name="description" required /><br />
          <b>Venue: 	</b> <input type="text" name="venue" required /><br /><br />

            <b>Date & time:</b> <input type="datetime-local" name="datetime" required/><br />

            

          	<b> Category:</b> <br>
            Culture <input type="radio" value="culture" name="category" required />
            Sport <input type="radio" value="sport" name="category" required />
            Other <input type="radio" value="other" name="category" required /><br /><br />
            

          <b> Organiser ID:</b> <?= $o_id ?> <!-- sets organiser id off session varaible -->
            
            <br />



            <!-- Form for uploading the event picture-->
            <form action="" method="post" enctype="multipart/form-data">
            <b>Choose Picture:</b>
              <input type="file" name="fileToUpload" id="fileToUpload">
              <input type="submit" value="Upload Picture" name="upload">
            </form>
            <br><br>

            <!-- Submit button to start running PHP above -->
            <input type="submit" name='addevent' value="Add Event" class='submit'/><br />

          </div>

        </form>


      </div>
    </div>

    <a href='myevents.php'>Return to my events</a>
    <!-- Link(href) to return to my events. Also a home button below-->
    <br><br><form action='/index.php'>
      <input type="submit" value="Home"/>
    </form>
  </div>
</body>
</html>