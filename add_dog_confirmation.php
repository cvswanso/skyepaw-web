<?php
require 'config/config.php';

session_start();

if ( !isset($_FILES["file"]["name"]) || empty($_FILES["file"]["name"]) )
{
	// Missing required fields.
	$error = "Please fill out all required fields.";
	$url = 'profile.php?error=' . $error;
	header( "Location: $url" );
}
else if ( !isset($_POST['callname']) || empty($_POST['callname']) )
{
	$error = "Please fill out all required fields.";
	$url = 'profile.php?error=' . $error;
	header( "Location: $url" );
}
else if ( !isset($_POST['name']) || empty($_POST['name']) )
{
	$error = "Please fill out all required fields.";
	$url = 'profile.php?error=' . $error;
	header( "Location: $url" );
}
else if ( !isset($_POST['sex']) || empty($_POST['sex']) )
{
	$error = "Please fill out all required fields.";
	$url = 'profile.php?error=' . $error;
	header( "Location: $url" );
}
else if ( !isset($_POST['birthdate']) || empty($_POST['birthdate']) )
{
	$error = "Please fill out all required fields.";
	$url = 'profile.php?error=' . $error;
	header( "Location: $url" );
}
else if ( !isset($_POST['breed']) || empty($_POST['breed']) )
{
	$error = "Please fill out all required fields.";
	$url = 'profile.php?error=' . $error;
	header( "Location: $url" );
}
 else {
	// All required fields provided.

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if ( $mysqli->errno ) {
		echo $mysqli->error;
		exit();
	}

	if ( isset($_POST['sport']) && !empty($_POST['sport']) ) {
		// User selected album value.
		$sport = $_POST['sport'];
	} else {
		// User did not select album value.
		$sport = NULL;
	}

	if ( isset($_POST['title']) && !empty($_POST['title']) ) {
		// User selected bytes value.
		$title = $_POST['title'];
	} else {
		// User did not select bytes value.
		$title = NULL;
	}

	$statement = $mysqli->prepare("INSERT INTO dogs (call_name, name, sex, birthdate, breed, users_id, image) VALUES (?, ?, ?, ?, ?, ?, ?)");

	$image_path ="dog_images/" . $_FILES["file"]["name"];

	$statement->bind_param("sssssis", $_POST['callname'], $_POST['name'], $_POST['sex'], $_POST['birthdate'], $_POST['breed'], $_SESSION["user_id"], $image_path);
	
	$executed = $statement->execute();

	$dog_id = $mysqli->insert_id;

	$targetDir = "dog_images/";

	$fileName = $dog_id . basename($_FILES["file"]["name"]);
	$targetFilePath = $targetDir . $fileName;

	$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
	$allowTypes = array('jpg','png','jpeg','gif','pdf', 'JPG', 'JPEG');
	if(in_array($fileType, $allowTypes)){
        // Upload file to server
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
            // Insert image file name into database
            $statusMsg = "The file ".$fileName. " has been uploaded successfully.";
        }
        else{
            $error = "Please choose a smaller file.";
            $sql = "DELETE FROM dogs WHERE id = " . $dog_id . ";";
      		$results = $mysqli->query($sql);
            $url = 'profile.php?error=' . $error;
			header("Location: $url");
			exit();
        }
    }else{
        $error = "Please submit a PNG or JPG file.";
    		$sql = "DELETE FROM dogs WHERE id = " . $dog_id . ";";
      		$results = $mysqli->query($sql);
    		$url = 'profile.php?error=' . $error;
			header("Location: $url");
			exit();
    }

    foreach ($_POST['sport'] as $selectedOption)
	{
		$sport_statement = $mysqli->prepare("INSERT INTO dogs_has_sports (dogs_id, sports_id) VALUES (?, ?)");

		$sport_statement->bind_param("ii", $dog_id, $selectedOption);
		
		$sport_executed = $sport_statement->execute();
	}

	foreach ($_POST['title'] as $selectedOption)
	{
		$title_statement = $mysqli->prepare("INSERT INTO dogs_has_titles (dogs_id, dogs_users_id, titles_id) VALUES (?, ?, ?)");

		$title_statement->bind_param("iii", $dog_id, $_SESSION["user_id"], $selectedOption);
	
		$title_executed = $title_statement->execute();
	}

	$statement->close();

	$mysqli->close();

    $url = 'profile.php';
	header( "Location: $url" );

}

if ( isset($error) || !empty($error) )
{
	$url = 'add_dog_error.php/' . $error;
	header( "Location: $url" );
}

?>