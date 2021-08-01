<?php 
session_start();
include 'inc/dbconnect.php';

//Include the DB config file so that we can connect to it and run queries. Also start the session
$o_id=$_SESSION['id'];
?>
<head>
  <title>Events - My Events</title>
  <style type="text/css">
  table { border: 2px solid black; }
  th, td { padding: 10px; 
    border-bottom: 2px solid #ddd;}
    tr:hover {background-color:#f5f5f5;}
    th {
      background-color:#006D9C;
      color: white;

    }

  </style>
</head>
<section class="main-container">
  <div class="wrapper">
    <h1>My Events</h1>
  </div>
</section>

<?php
if (isset($_GET['orderby'])){ 
  $orderby = $_GET['orderby'];
  //Set the filter/sort to what is selected and submitted 
  ?>

  <form id="order_select" method="get" action="myevents.php?search=order_id">
    <!-- Drop down menu for user to select what is filtered and sorted in the list of events. Then below code then selects this option that is chosen. -->
    <select name="orderby" id="order_id">
      <option value="category" <?php if ($orderby=='category'){ ?> selected=selected <?php } ?> >Category</option>
      <option value="name" <?php if ($orderby=='name'){ ?> selected=selected <?php } ?> >Name</option>
      <option value="cultureonly" <?php if ($orderby=='cultureonly'){ ?> selected=selected <?php } ?> >Culture events only</option>
      <option value="popularity" <?php if ($orderby=='popularity'){ ?> selected=selected <?php } ?> >Popularity</option>
      <option value="datetime" <?php if ($orderby=='datetime'){ ?> selected=selected <?php } ?> >Date</option>
      <option value="sportonly" <?php if ($orderby=='sportonly'){ ?> selected=selected <?php } ?> >Sport events only</option>
      <option value="otheronly" <?php if ($orderby=='otheronly'){ ?> selected=selected <?php } ?> >Other events only</option>
    </select>
    <input type="submit" value="Search" >
    <!-- Submit button for the search --!>
  </form>
<?php } else { ?>
  <!-- Menu for the main page to select sort/filter through main page of events  -->
  <form id="order_select" method="get" action="myevents.php?search=order_id">
   <select name="orderby" id="order_id">
      <option value="category" selected=selected >Category</option>
      <option value="name" >Name</option>
      <option value="datetime" >Date</option>
      <option value="cultureonly" >Culture events only</option>
      <option value="popularity" >Popularity</option>
      <option value="otheronly" >Other events only</option>
      <option value="sportonly" >Sport events only</option>
    </select>
    <input type="submit" value="Search" >
    <!--Submit search button for form -->
  </form>
  <?php
}
?>





<!--Table headings of results -->
<table class="data-table" id="table">
  <thead>
    <tr>
      <th>Event name</th>
      <th>Description</th>
      <th>Date</th>
      <th>Category</th>
      <th>Popularity</th>
      <th>Edit</th>
    </tr>
  </thead>
  <tbody>

    <?php
    //Set the order to the value the user selected in the menu
    if (isset($_GET['orderby'])){
      $orderby=$_GET['orderby'];
    } else {
        //by default set to category if none selected
      $orderby="category";
    }

    try {
    //Run the correct DB query based on the order chosen by the the user
      if ($orderby=="category") {
        $rows = $db->query("SELECT * FROM events WHERE organiser_id = '$o_id' ORDER BY category DESC");
        
      } elseif ($orderby=="popularity") {
        //This query counts the popularity of the events using the event interest page and then sorts by this popularity 
        $rows = $db->query("SELECT E.`id`,E.`name`,E.`category`, E.`venue`,E.`description`,E.`datetime`, COUNT(*) AS popularity
          FROM `events` E 
          LEFT OUTER JOIN `interest_in_event` EI 
          ON EI.`event_id` = E.`id` 
          WHERE E.`organiser_id` = '$o_id'
          GROUP BY E.`id`
          ORDER BY `popularity` desc
          ");

      } elseif ($orderby=="name") {
        $rows = $db->query("SELECT * FROM events WHERE organiser_id = '$o_id' ORDER BY name ASC");
        
        
      } elseif ($orderby=="datetime") {
        $rows = $db->query("SELECT * FROM events WHERE organiser_id = '$o_id' ORDER BY `datetime` ASC");
        
      
      }  elseif ($_GET['orderby']=="sportonly") {
        $rows = $db->query("SELECT * FROM events WHERE `category` = 'Sport' AND organiser_id = '$o_id'");
        //Filter events by sport 

      } elseif ($_GET['orderby']=="cultureonly") {
        $rows = $db->query("SELECT * FROM events WHERE `category` = 'Culture' AND organiser_id = '$o_id'");
        //Filter events by culture

      } elseif ($_GET['orderby']=="otheronly") {
        $rows = $db->query("SELECT * FROM events WHERE `category` = 'Other' AND organiser_id = '$o_id'");
        //Filter events to other
      }



      if($rows->rowCount() > 0) {
        foreach($rows as $row) {
        //loop through each row of results and print the values to a table in the format below 
          $eventid=$row['id'];
          ?>

          <tr>
            <td><?= $row['name'] ?></td>
            <td><?= $row['description'] ?></td>
            <td><?= $row['category'] ?></td>
            <td><?= $row['datetime'] ?></td>

            <td>
              <?php
              $e_id = $row['id'];
              try{
                $stmt = $db->query("SELECT COUNT(*) AS popularity, E.`id` FROM `interest_in_event` EI
                  INNER JOIN `events` E
                  ON EI.`event_id` = E.`id`
                  WHERE E.`id` = '$e_id'
                  GROUP BY E.`id`");
            //Works out popularity for each event 
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                $popularity = $data['popularity'];
              }
              catch(PDOException $exception) {
                echo $exception->getMessage();
              }
              if (isset($popularity)){ ?>
                <a href='eventpopularity.php?event_id=<?php echo $eventid ?>'> <?php echo $popularity; ?></a>
              <?php 
            //The popularity - given as a number then becomes a link (href) to the eventpopularity.php page. This then allows the user to see who is signed up for their event 
            } else {
                echo "0";
                //print 0 if no popularity
              }

              ?>


            </td>
            <td>
              <a href='updateevent.php?event_id=<?php echo $eventid ?>'>Update details of event</a>
              <!-- Link(href) to update the event. This will then take the user to the updateevent.php page. When taking the user here, it will send over the eventID also so we can load of the correct event -->
            </td>
          </tr>

          <?php
        }
      } else {
        echo("<tr><td colspan=5>There were no events organised by your user. Please create an event to view one.</td></tr>");
        //if nothing was found in the DB, then output message to user saying so
      }
    } catch(PDOException $exception) {
      echo("Error - unable to get data from the database.<br>");
      echo($exception->getMessage());
      exit;
    }

    ?>

  </tbody>
</table>





<!-- Buttons to go to add a new event, and to go home -->
<br><br><form action='newevent.php'>
  <input type="submit" value="Add New Event"/>
</form>


<br><br><form action='index.php'>
  <input type="submit" value="Home"/>
</form>


