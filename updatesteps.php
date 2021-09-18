<?php
  // Start the session
  session_start();
require 'config/config.php';

  // DB Connection.
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  if ( $mysqli->connect_errno ) {
    echo $mysqli->connect_error;
    exit();
  }

  $mysqli->set_charset('utf8');

  if ($_GET["mode"] == "add") {
    $add_statement = $mysqli->prepare("INSERT INTO dogs_has_steps (dogs_id, dogs_users_id, steps_id, steps_tricks_id) VALUES (?, ?, ?, ?)");

     $add_statement->bind_param("iiii", $_GET['dog_id'], $_SESSION['user_id'], $_GET["step_id"], $_GET["trick_id"]);
     $executed = $add_statement->execute();
  }
  if ($_GET["mode"] == "delete") {
    $delete_statement = $mysqli->prepare("DELETE FROM dogs_has_steps WHERE dogs_id=? AND dogs_users_id=? AND steps_id=? AND steps_tricks_id=?");

     $delete_statement->bind_param("iiii", $_GET['dog_id'], $_SESSION['user_id'], $_GET["step_id"], $_GET["trick_id"]);
     $executed = $delete_statement->execute();
  }

  $sql = "SELECT * FROM dogs_has_steps WHERE dogs_id = " . $_GET["dog_id"] . " AND steps_tricks_id = " . $_GET["trick_id"] . ";";

  $results = $mysqli->query($sql);
  if ( $results == false ) {
    echo $mysqli->error;
    exit();
  }

  $mysqli->close();

  $results_array = [];

  while( $row = $results->fetch_assoc() ) {
    array_push( $results_array, $row );
  } 
  echo json_encode( $results_array );

?>