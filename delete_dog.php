<?php
  // Start the session
  require 'config/config.php';

  session_start();

  // DB Connection.
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  if ( $mysqli->connect_errno ) {
    echo $mysqli->connect_error;
    exit();
  }

  $mysqli->set_charset('utf8');

  $sql_titles = "DELETE FROM dogs_has_titles WHERE dogs_id=" . $_GET["dog_id"] . ";";
  $results_titles = $mysqli->query($sql_titles);
  if ( $results_titles == false ) {
    echo $mysqli->error;
    exit();
  }

  $sql_sports = "DELETE FROM dogs_has_sports WHERE dogs_id=" . $_GET["dog_id"] . ";";
  $results_sports = $mysqli->query($sql_sports);
  if ( $results_sports == false ) {
    echo $mysqli->error;
    exit();
  }

  $sql_steps = "DELETE FROM dogs_has_steps WHERE dogs_id=" . $_GET["dog_id"] . ";";
  $results_steps = $mysqli->query($sql_steps);
  if ( $results_steps == false ) {
    echo $mysqli->error;
    exit();
  }

  $sql_dogs = "DELETE FROM dogs WHERE id=" . $_GET["dog_id"] . ";";
  $results_dogs = $mysqli->query($sql_dogs);
  if ( $results_dogs == false ) {
    echo $mysqli->error;
    exit();
  }

  header( "Location: profile.php" );

?>