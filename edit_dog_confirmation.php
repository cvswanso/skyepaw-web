<?php

require 'config/config.php';

session_start();

if ( !isset($_POST['callname']) || empty($_POST['callname']) )
{
	$error = "Please fill out all required fields.";
	$url = 'edit_dog.php?dog_id=' . $_POST['dog_id'] . '&error=' . $error;
	header( "Location: $url" );
}
else if ( !isset($_POST['name']) || empty($_POST['name']) )
{
	$error = "Please fill out all required fields.";
	$url = 'edit_dog.php?dog_id=' . $_POST['dog_id'] . '&error=' . $error;
	header( "Location: $url" );
}
else if ( !isset($_POST['sex']) || empty($_POST['sex']) )
{
	$error = "Please fill out all required fields.";
	$url = 'edit_dog.php?dog_id=' . $_POST['dog_id'] . '&error=' . $error;
	header( "Location: $url" );
}
else if ( !isset($_POST['birthdate']) || empty($_POST['birthdate']) )
{
	$error = "Please fill out all required fields.";
	$url = 'edit_dog.php?dog_id=' . $_POST['dog_id'] . '&error=' . $error;
	header( "Location: $url" );
}
else if ( !isset($_POST['breed']) || empty($_POST['breed']) )
{
	$error = "Please fill out all required fields.";
	$url = 'edit_dog.php?dog_id=' . $_POST['dog_id'] . '&error=' . $error;
	header( "Location: $url" );
}
else {

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


	if (isset($_FILES["file"]["name"]) && !empty($_FILES["file"]["name"]))
	{

		$targetDir = "dog_images/";

		$fileName = $_POST['dog_id'] . basename($_FILES["file"]["name"]);
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
            $url = 'edit_dog.php?dog_id=' . $_POST['dog_id'] . '&error=' . $error;
			header("Location: $url");
			exit();
			}
		}else{
			$error = "Please submit a PNG or JPG file.";
    		$url = 'edit_dog.php?dog_id=' . $_POST['dog_id'] . '&error=' . $error;
			header("Location: $url");
			exit();
		}

		$statement = $mysqli->prepare("UPDATE dogs SET call_name=?, name=?, sex=?, birthdate=?, breed=?, image=? WHERE id=?");

		$image_path ="dog_images/" . $_FILES["file"]["name"];

		$statement->bind_param("ssssssi", $_POST['callname'], $_POST['name'], $_POST['sex'], $_POST['birthdate'], $_POST['breed'], $image_path, $_POST['dog_id']);

		$executed = $statement->execute();

		$dog_id = $mysqli->insert_id;

		$sql_titles = "DELETE FROM dogs_has_titles WHERE dogs_id=" . $_POST["dog_id"] . ";";
		$results_titles = $mysqli->query($sql_titles);
		if ( $results_titles == false ) {
			echo $mysqli->error;
			exit();
		}

		$sql_sports = "DELETE FROM dogs_has_sports WHERE dogs_id=" . $_POST["dog_id"] . ";";
		$results_sports = $mysqli->query($sql_sports);
		if ( $results_sports == false ) {
			echo $mysqli->error;
			exit();
		}

		foreach ($_POST['sport'] as $selectedOption)
		{
			$sport_statement = $mysqli->prepare("INSERT INTO dogs_has_sports (dogs_id, sports_id) VALUES (?, ?)");

			$sport_statement->bind_param("ii", $_POST['dog_id'], $selectedOption);

			$sport_executed = $sport_statement->execute();
		}

		foreach ($_POST['title'] as $selectedOption)
		{
			$title_statement = $mysqli->prepare("INSERT INTO dogs_has_titles (dogs_id, dogs_users_id, titles_id) VALUES (?, ?, ?)");

			$title_statement->bind_param("iii", $_POST['dog_id'], $_SESSION["user_id"], $selectedOption);

			$title_executed = $title_statement->execute();
		}

		if(!$executed) {
			echo $mysqli->error;
		}

		$mysqli->close();
	}
	else {

		$statement = $mysqli->prepare("UPDATE dogs SET call_name= ?, name= ?, sex= ?, birthdate= ?, breed= ? WHERE id= ?");

		$statement->bind_param("sssssi", $_POST['callname'], $_POST['name'], $_POST['sex'], $_POST['birthdate'], $_POST['breed'], $_POST['dog_id']);


		$executed = $statement->execute();

		$dog_id = $mysqli->insert_id;

		$sql_titles = "DELETE FROM dogs_has_titles WHERE dogs_id=" . $_POST["dog_id"] . ";";
		$results_titles = $mysqli->query($sql_titles);
		if ( $results_titles == false ) {
			echo $mysqli->error;
			exit();
		}

		$sql_sports = "DELETE FROM dogs_has_sports WHERE dogs_id=" . $_POST["dog_id"] . ";";
		$results_sports = $mysqli->query($sql_sports);
		if ( $results_sports == false ) {
			echo $mysqli->error;
			exit();
		}

		foreach ($_POST['sport'] as $selectedOption)
		{
			$sport_statement = $mysqli->prepare("INSERT INTO dogs_has_sports (dogs_id, sports_id) VALUES (?, ?)");

			$sport_statement->bind_param("ii", $_POST['dog_id'], $selectedOption);

			$sport_executed = $sport_statement->execute();
		}

		foreach ($_POST['title'] as $selectedOption)
		{
			$title_statement = $mysqli->prepare("INSERT INTO dogs_has_titles (dogs_id, dogs_users_id, titles_id) VALUES (?, ?, ?)");

			$title_statement->bind_param("iii", $_POST['dog_id'], $_SESSION["user_id"], $selectedOption);

			$title_executed = $title_statement->execute();
		}

		if(!$executed) {
			echo $mysqli->error;
		}

		$statement->close();
	}

	$url = 'profile.php';
	header( "Location: $url" );

}

?>