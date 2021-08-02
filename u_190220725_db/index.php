<?php include_once 'inc/header.php';
include 'inc/dbconnect.php';
// includes the header file which contains the sign up and register part. Also need to include the dbconnect file so that we have the DB configs and can run our queries 

?>



<head><title>Events Homepage</title>
  <style type="text/css">
  table { border: 3px solid black; }
  th, td { padding: 10px; 
    border-bottom: 2px solid #ddd;}
    tr:hover {background-color:##e0e0e0;}
    th {
      background-color:#165929;
      color: white;
    }

  </style>
</head>
<section class="main-container">
  <div class="wrapper">
    <h1>Aston Events</h1>
  </div>
</section>


<h3>Upcoming Events</h3>


<?php
if (isset($_GET['orderby'])){ 
  $orderby = $_GET['orderby'];
  //sets the filter/sort by option depending on what the user has selected
  ?>

  <form id="order_select" method="get" action="index.php?search=order_id">
    <!-- This is a select menu for filtering/sorting events from the list
      Selects the selected value depending on what sort is already selected -->
      <select name="orderby" id="order_id">
        <option value="category" <?php if ($orderby=='category'){ ?> selected=selected <?php } ?> >Category</option>
        <option value="name" <?php if ($orderby=='name'){ ?> selected=selected <?php } ?> >Name</option>
      	<option value="datetime" <?php if ($orderby=='date'){ ?> selected=selected <?php } ?> >Date</option>
        <option value="interest" <?php if ($orderby=='interest'){ ?> selected=selected <?php } ?> >Interest</option>
        <option value="otheronly" <?php if ($orderby=='otheronly'){ ?> selected=selected <?php } ?> >Other events only</option>
        <option value="sportonly" <?php if ($orderby=='sportonly'){ ?> selected=selected <?php } ?> >Sport events only</option>
        <option value="cultureonly" <?php if ($orderby=='cultureonly'){ ?> selected=selected <?php } ?> >Culture events only</option>
        
      </select>
      <input type="submit" value="Search" >
    </form>
  <?php } else { ?>
    <!-- Selection list for the first lot of main page events. Allows user to sort/filter from here -->
    <form id="order_select" method="get" action="index.php?search=order_id">
      <select name="orderby" id="order_id">
        <option value="name" >Name</option>
      	<option value="datetime" selected=selected >Date</option>
        <option value="category" >Category</option>
        <option value="interest" >Interest</option>
        <option value="cultureonly" >Culture events only</option>
        <option value="sportonly" >Sport events only</option>
        <option value="otheronly" >Other events only</option>
      </select>
      <input type="submit" value="Search" >
    </form>
    <?php
  }
  ?>



  <table class="data-table" id="table">
    <thead> <!-- Main table for the list of events.  -->
      <tr>
      	<th>Event name</th>
      	<th>Description</th>
        <th>Date</th>
      	<th>Category</th>
        <th>Interest</th>
        <th>More info</th>
        <th>Registered</th>
      </tr>
    </thead>
    <tbody>

      <?php
	//executes the db query depending on what filter was selected in menu
      try {
        if (isset($_GET['orderby'])){
          $orderby=$_GET['orderby'];
          if ($_GET['orderby']=="interest") {
            $rows = $db->query("SELECT E.`id`,E.`name`,E.`category`, E.`venue`,E.`description`,E.`datetime`, COUNT(*) AS interest
              FROM `events` E 
              LEFT OUTER JOIN `interest_in_event` EI 
              ON EI.`event_id` = E.`id` 
              GROUP BY E.`id`
              ORDER BY `interest` desc
              ");
        //Works out how popular an event is by counting the number of times the eventID is in the event interest table, and then sorts by this value.

          } elseif ($_GET['orderby']=="name") {
            $rows = $db->query("SELECT * FROM events ORDER BY name ASC");
        //Sort the output by in alphabetical order
        
         } elseif ($_GET['orderby']=="cultureonly") {
            $rows = $db->query("SELECT * FROM events WHERE `category` = 'Culture'");
        //Filter the events into their different cultures
			
          } elseif ($_GET['orderby']=="datetime") {
            $rows = $db->query("SELECT * FROM events ORDER BY `datetime` ASC");
        //Sort the rrsults in descending date order

          } elseif ($_GET['orderby']=="sportonly") {
            $rows = $db->query("SELECT * FROM events WHERE `category` = 'Sport'");
        //Filter events into their different sports 


          } elseif ($_GET['orderby']=="otheronly") {
            $rows = $db->query("SELECT * FROM events WHERE `category` = 'Other'");
        //Filter the events into other only
        

          }else {
            $rows = $db->query("SELECT * FROM events ORDER BY category ASC");
        } //if the orderby isn't set, order them by category
      }
      else {
        $rows = $db->query("SELECT * FROM events");
        //if the orderby isn't set, output all of the events
      }



	//checks every result row of the event and sets them into a variable from the result
      if($rows->rowCount() > 0) {
        foreach($rows as $row) {
          $eventid=$row['id'];


          ?>

          <tr>
            <td><?= $row['name'] ?></td> 
            <td><?= $row['description'] ?></td>
            <td><?= $row['datetime'] ?></td>
            <td><?= $row['category'] ?></td>

            <td>
              <?php
              $e_id = $row['id'];
              try{
                $stmt = $db->query("SELECT COUNT(*) AS interest, E.`id` FROM `interest_in_event` EI
                  INNER JOIN `events` E
                  ON EI.`event_id` = E.`id`
                  WHERE E.`id` = '$e_id'
                  GROUP BY E.`id`");

                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                $interest = $data['interest'];
                //retrieve the interest for the current user
              }
              catch(PDOException $exception) {
                echo $exception->getMessage();
                //Ouptut error message to the user if the DB query errors
              }
              if (isset($interest)){
                echo $interest;
              } else {
                echo "0";
                //default the count to 0 if it returns null
              }
              
              ?>

            </td>

              <td>
                <a href='eventdetails.php?event_id=<?php echo $eventid ?>'>Show details</a>
              </td>
              <td>

                <?php
               //if the user is logged in, allow them to register and unregister to an event
              //The register option will show if they aren't found in the event interest for that ID
              // otherwise it will show unregister if they are already there

                $u_id = $_SESSION['id'];

                try{
                  $stmt = $db->query("SELECT E.`name`,E.`id` 
                    FROM `events` E 
                    RIGHT OUTER JOIN interest_in_event EI 
                    ON E.`id`=EI.`event_id` 
                    RIGHT OUTER JOIN users U 
                    ON EI.`user_id` = U.`id` 
                    WHERE user_id='$u_id' 
                    AND E.`id` = '$e_id'
                    ");

                  $data = $stmt->fetch(PDO::FETCH_ASSOC);
                }
                catch(PDOException $exception) {
                  echo $exception->getMessage();
                }
                if (isset($data['name'])) {

                  ?>
                  <a href='unregisterinterest.php?event_id=<?php echo $eventid ?>'>Unregister interest from the event</a>
                  <?php
                } else {
                  ?>
                  <a href='registerinterest.php?event_id=<?php echo $eventid ?>'>Register interest in event</a>
                <?php } ?>


              </td>
              <?php

            ?>

          </tr>

          <?php
        }
      } else {
        echo("<tr><td colspan=5>No results were found in the DB.</td></tr>");
      //return error if nothing is found in the DB
      }
    } catch(PDOException $exception) {
      echo("Failed to get data from database.<br>");
      echo($exception->getMessage());
      exit;
    //Error printed out to user from DB
    }

    ?>

  </tbody>
</table>
<br><br>


<?php

//check if the user is an organiser. if they are, reveal a bbutton to take them to a "myevents" page. this will show events they have organised only
if (isset($_SESSION['email'])){


  if ($_SESSION['organiser']=='1'){ 
    $o_id=$_SESSION['id'];
    //If the user is logged in and they have organised an event, reveal a link to their events
    ?>
    <form action='myevents.php'>
      <input type="submit" value="My Events"/>
    </form>
    <!--  Button to see their events -->
  <?php }

}

?>