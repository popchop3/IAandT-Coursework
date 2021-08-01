<?php
//Code for sending an email to the eevnt organiser
//includes DB config file so we can query DB to get organiser contact details
include 'inc/dbconnect.php';
//starts session and grabs ID from URL
session_start();
if (isset($_GET['event_id'])){
    $e_id=$_GET['event_id'];

    $u_id  = $_SESSION['id'];
    $u_email = $_SESSION['email'];
    $u_forename = $_SESSION['forename'];
    $u_surname = $_SESSION['surname'];
//Sets user variables using session data

}

//SQL query to retrieve the email of the event organiser from the DB. 
try{
    $rows = $db->query("SELECT `email`, E.`name`, E.`id` FROM `events` E
        INNER JOIN `interest_in_event` EI
        ON E.`id`=EI.`event_id`
        INNER JOIN `users` U
        ON U.`id` = EI.`user_id`
        WHERE E.`id` = $e_id
        ");
}

catch(PDOException $exception) {
    echo("Unable to get email from the database.<br>");
    echo($exception->getMessage());
    exit;
}

//set the organiser email and event name from the DB query
if($rows->rowCount() > 0) {
  foreach($rows as $row) {
    $o_email = $row['email'];
    $e_name = $row['name'];

}
}



if(isset($_POST['sendmail'])) {
//if the form is submitted successfully, run the below PHP code
    //Send email to organiser
    //Set the subject to - name + "enquiry about event"
    $email_to = $o_email;
    $email_subject = $e_name." - enquiry about event";


    //function for errors in the email form
    function died($error) {
        // error message
        echo "There was an error with your message. See below: ";
        echo "(Error: ".$error.")<br /><br />";
        echo "<a href 'sendmail.php'>Please resubmit and try again. </a>";
        die();
    }


    // validation for data in the form
    if(!isset($_POST['comments'])) {
        died('Please add message here');       
}

    $comments = $_POST['comments']; 

    $error_message = "";

//Check that the message contains some detail
    if(strlen($comments) < 1) {
        $error_message .= 'Please enter a valid message';
    }

    if(strlen($error_message) > 0) {
        died($error_message);
    }

    //Message that organiser will get
    $email_message = "Please see the form details below\n\n";


    //Use of the clean function to remove any content in the message that may be a script etc intended for hacking
    function clean_string($string) {
      $badcontent = array("content-type","bcc:","to:","cc:","href");
      return str_replace($badcontent,"",$string);
  }

  $email_message .= "Forename: ".$u_forename."\n";
  $email_message .= "Surname: ".$u_surname."\n";
  $email_message .= "Email: ".$u_email."\n";
  $email_message .= "Comments: ".clean_string($comments)."\n";
// cleaning the message of potential hacks

// Build email headers and send the email
  $headers = 'From: '.$u_email."\r\n".
  'Reply-To: '.$u_email."\r\n" .
  'X-Mailer: PHP/' . phpversion();
  @mail($o_email, $email_subject, $email_message, $headers);  
  ?>

  <!-- Email sent success message-->
  Your email has been sent to the organiser of this event

  <?php

}
?>


<h1>Email the event organiser</h1>
<!-- Form for the user to input their comments to send to the event organiser. The rest of the data is received from the session variables. -->
<form name="contactform" method="post" action="">
    <table width="460px">
        <tr>
         <td valign="top">
          <label for="comments">Comments: </label>
      </td>
      <td valign="top">
          <textarea  name="comments" maxlength="800" cols="20" rows="8"></textarea>
      </td>
  </tr>
  <tr>
     <td colspan="2" style="text-align:center">
        <input type="submit" name="sendmail" value="Send Email" class="submit">
        <!-- Send email button. This triggers the above PHP code -->
    </td>
</tr>
</table>
</form>

<!-- Home button-->
    <form action='index.php'>
      <input type="submit" value="Home"/>
    </form>
