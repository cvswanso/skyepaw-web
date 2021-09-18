<?php
require 'config/config.php';

session_start();

// Server-side input validation

if ( !isset($_POST['email']) || empty($_POST['email'])
	|| !isset($_POST['name']) || empty($_POST['name'])
	|| !isset($_POST['password']) || empty($_POST['password']) ) {
	$error = "Please fill out all required fields.";
	$url = 'login.php?error=' . $error;
	header( "Location: $url" );
}
else {
	// Store this user into the database!
	// connect to db
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit();
	}

	// Check if username or email address is already taken (aka exists in the users table)
	$sql_registered = "SELECT * FROM users 
	WHERE email = '" . $_POST["email"] . "';";

	$results_registered = $mysqli->query($sql_registered);
	if(!$results_registered) {
		echo $mysqli->error;
		exit();
	}

	// num_rows is the # of matches
	if($results_registered->num_rows > 0) {
		$error = "This email already has an account associated with it.";
		$url = 'login.php?error=' . $error;
		header( "Location: $url" );
	}
	else {

		$password = hash("sha256", $_POST["password"]);

		$statement = $mysqli->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");

		$statement->bind_param("sss", $_POST['name'], $_POST['email'], $password);
	
		$executed = $statement->execute();

		$user_id = $mysqli->insert_id;

		if($statement->affected_rows != 1) {
			echo $mysqli->error;
			exit();
		}
		else {
			$_SESSION["logged_in"] = true;
			$_SESSION["user_id"] = $user_id;
			$_SESSION["name"] = $_POST["name"];
			$url = 'profile.php';
			header( "Location: $url" );
		}

	}

	$mysqli->close();


}

?>