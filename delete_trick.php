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

  $sql_prereqs = "DELETE FROM tricks_has_prereqs WHERE tricks_id=" . $_GET["trick_id"] . " OR prereqs_id=" . $_GET["trick_id"] . ";";
  $results_prereqs = $mysqli->query($sql_prereqs);
  if ( $results_prereqs == false ) {
    echo $mysqli->error;
    exit();
  }

  $sql_steps = "DELETE FROM steps WHERE tricks_id=" . $_GET["trick_id"] . ";";
  $results_steps = $mysqli->query($sql_steps);
  if ( $results_steps == false ) {
    echo $mysqli->error;
    exit();
  }

  $sql_tricks = "DELETE FROM tricks WHERE id=" . $_GET["trick_id"] . ";";
  $results_tricks = $mysqli->query($sql_tricks);
  if ( $results_tricks == false ) {
    echo $mysqli->error;
    exit();
  }

  header( "Location: browsetricks.php" );

?>