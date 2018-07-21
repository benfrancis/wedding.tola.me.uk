<!DOCTYPE html>

<html>
  <head>
    <meta charset="utf-8">
    <title>Ben & Siobhan's Wedding - RSVP</title>
    <link rel="stylesheet" type="text/css" href="../css/site.css" />
    <link rel="stylesheet" type="text/css" href="rsvp.css" />
  </head>
  <body>
    <h1>Ben & Siobhan's Wedding</h1>
    <nav>
      <ul>
        <li><a href="/">Home</a></li>
        <li><a href="/venue">Venue</a></li>
        <li><a href="/schedule">Schedule</a></li>
        <li><a href="/accommodation">Accommodation</a></li>
        <li class="selected"><a href="/rsvp">RSVP</a></li>
        <li><a href="/wedding_party">Wedding Party</a></li>
      </ul>
    </nav>
    <section id="rsvp">
<?php
  if(isset($_POST['submit'])){
  
    // Connect to database
    $user="wedding";
    $password="OBFUSCATED";
    $database="wedding";
    mysql_connect('localhost', $user, $password);
    @mysql_select_db($database) or die( "There was a problem connecting to the database, please <a href='/contact'>contact us</a>.");

    // Make strings safe
    $names = mysql_real_escape_string($_POST['names']);
    $response = mysql_real_escape_string($_POST['response']);
    $dietary_requirements = mysql_real_escape_string($_POST['dietary_requirements']);
    $other_dietary_requirements = mysql_real_escape_string($_POST['other_dietary_requirements']);
    $leaving_early = $_POST['leaving_early'];
    $comments = mysql_real_escape_string($_POST['comments']);
    $leaving_early_boolean = "false";
    if ($leaving_early) {
      $leaving_early_boolean = "true";
    }
    
    // Try to save in database
    $query = "INSERT INTO responses VALUES ('" . $names . "','" . $response . "','" .
      $dietary_requirements . "','" . $other_dietary_requirements . "'," . $leaving_early_boolean . ",'" .
      $comments . "')";
    //echo $query;
    $save_success = mysql_query($query);
    if (!$save_success) {
      die("There was a problem saving your response, please <a href='/contact'>contact us</a>.");
    }
    mysql_close();
  
    // Send email 
    $to = "ben@tola.me.uk";
    $subject = "RSVP form submission";
    $message = "\nNames: " . $names;
    if ($response) {
      $message = $message . "\nResponse: " . $response;
    }
    if ($dietary_requirements) {
      $message = $message . "\nDietary Requirements: " . $dietary_requirements;
    }
    if ($other_dietary_requirements) {
      $message = $message . "\nOther Dietary Requirements: " . $other_dietary_requirements;
    }
    if ($leaving_early) {
      $message = $message . "\nLeaving Early: yes";
    } else {
      $message = $message . "\nLeaving Early: no";
    }
    if ($comments) {
      $message = $message . "\nComments: " . $comments;
    }

    $headers = "From:" . $to;
    $send_success = mail($to,$subject,$message,$headers);
    
    if($send_success) {
      echo "Thank you " . $names . ", your response has been received.";
      echo "\n<br><br>" . nl2br($message);
    } else {
      echo "There was a problem sending your response, please <a href='/contact'>contact us</a>.";
    }
  }
?>
    </section>
    <footer>
      <img id="hearts" src="../images/hearts.png" />
      <nav>
        <ul>
          <li><a href="/contact">Contact Us</a></li>
          <li><a href="/venue">Photos</a></li>
          <li><a href="/schedule">Honeymoon</a></li>
        </ul>
      </nav>
    </footer>
  </body>
  
</html>
