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

  $sql = "SELECT * FROM tricks WHERE name LIKE '%" . $_GET['term'] . "%';";
  if ($_GET['level'] == "Beginner")
  {
    $sql = "SELECT * FROM tricks WHERE rating = 'Beginner' AND name LIKE '%" . $_GET['term'] . "%';";
  }
  if ($_GET['level'] == "Intermediate")
  {
    $sql = "SELECT * FROM tricks WHERE rating = 'Intermediate';";
  }
  if ($_GET['level'] == "Advanced")
  {
    $sql = "SELECT * FROM tricks WHERE rating = 'Advanced';";
  }
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