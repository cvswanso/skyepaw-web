<?php
session_start();
require 'config/config.php';

if ( !isset($_FILES["file"]["name"]) || empty($_FILES["file"]["name"]) )
{
	// Missing required fields.
	$error = "Please fill out all required fields.";
	$url = 'add_trick.php?error=' . $error;
	header( "Location: $url" );
}
else if ( !isset($_POST['name']) || empty($_POST['name']) )
{
	$error = "Please fill out all required fields.";
	$url = 'add_trick.php?error=' . $error;
	header( "Location: $url" );
}
else if ( !isset($_POST['level']) || empty($_POST['level']) )
{
	$error = "Please fill out all required fields.";
	$url = 'add_trick.php?error=' . $error;
	header( "Location: $url" );
}
else if ( !isset($_POST['step1']) || empty($_POST['step1']) )
{
	$error = "Please fill out all required fields.";
	$url = 'add_trick.php?error=' . $error;
	header( "Location: $url" );
}
else {
	// All required fields provided.

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if ( $mysqli->errno ) {
		echo $mysqli->error;
		exit();
	}

	if ( isset($_POST['recommended']) && !empty($_POST['recommended']) ) {
		// User selected bytes value.
		$recommended = $_POST['recommended'];
	} else {
		// User did not select bytes value.
		$recommended = NULL;
	}

	if ( isset($_POST['challenges']) && !empty($_POST['challenges']) ) {
		// User selected bytes value.
		$challenges = $_POST['challenges'];
	} else {
		// User did not select bytes value.
		$challenges = NULL;
	}

	$statement = $mysqli->prepare("INSERT INTO tricks (name, rating, recommended, challenges, image) VALUES (?, ?, ?, ?, ?)");

	$image_path ="trick_images/" . $_FILES["file"]["name"];

	$statement->bind_param("sssss", $_POST['name'], $_POST['level'], $recommended, $challenges, $image_path);
	
	$executed = $statement->execute();

	$trick_id = $mysqli->insert_id;

	$targetDir = "trick_images/";
	$fileName = $trick_id . basename($_FILES["file"]["name"]);
	$targetFilePath = $targetDir . $fileName;
 	$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
	$allowTypes = array('jpg','png','jpeg','gif','pdf', 'JPG', 'JPEG', 'PNG');

	if(in_array($fileType, $allowTypes)) {
        // Upload file to server
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
            // Insert image file name into database
            $statusMsg = "The file ". $fileName . " has been uploaded successfully.";
        }
        else {
            $error = "Please choose a smaller file.";
            $sql = "DELETE FROM tricks WHERE id = " . $trick_id . ";";
      		$results = $mysqli->query($sql);
            $url = 'add_trick.php?error=' . $error;
			header("Location: $url");
			exit();
        }
    }
    else {
    		$error = "Please submit a PNG or JPG file.";
    		$sql = "DELETE FROM tricks WHERE id = " . $trick_id . ";";
      		$results = $mysqli->query($sql);
    		$url = 'add_trick.php?error=' . $error;
			header("Location: $url");
			exit();
    }



	foreach ($_POST['prereqs'] as $selectedOption)
	{
		$prereqs_statement = $mysqli->prepare("INSERT INTO tricks_has_prereqs (tricks_id, prereqs_id) VALUES (?, ?)");

		$prereqs_statement->bind_param("ii", $trick_id, $selectedOption);
		
		$prereqs_executed = $prereqs_statement->execute();
	}

	for ($x = 1; $x <= $_POST['numsteps']; $x++) {
		$steps_statement = $mysqli->prepare("INSERT INTO steps (step, step_number, tricks_id) VALUES (?, ?, ?)");

		$steps_statement->bind_param("sii", $_POST['step' . $x], $x, $trick_id);
		
		$steps_executed = $steps_statement->execute();
	}


	if(!$executed) {
		echo $mysqli->error;
	}






	$statement->close();

	$mysqli->close();

    $url = 'trick_details.php?trick_id=' . $trick_id;
	header("Location: $url");

}
?>