<?php
require 'config/config.php';
session_start();

if ( !isset($_POST['name']) || empty($_POST['name']) )
{
	$error = "Please fill out all required fields.";
	$url = 'edit_trick.php?error=' . $error;
	header( "Location: $url" );
}
else if ( !isset($_POST['level']) || empty($_POST['level']) )
{
	$error = "Please fill out all required fields.";
	$url = 'edit_trick.php?error=' . $error;
	header( "Location: $url" );
}
else if ( !isset($_POST['step1']) || empty($_POST['step1']) )
{
	$error = "Please fill out all required fields.";
	$url = 'edit_trick.php?error=' . $error;
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
	if (isset($_FILES["file"]["name"]) && !empty($_FILES["file"]["name"]))
	{

		$targetDir = "trick_images/";

		$fileName = $_POST['trick_id'] . basename($_FILES["file"]["name"]);
		$targetFilePath = $targetDir . $fileName;

		$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
		$allowTypes = array('jpg','png','jpeg','gif','pdf', 'JPG', 'JPEG');
		if(in_array($fileType, $allowTypes)){
        // Upload file to server
			if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
            // Insert image file name into database
				$statusMsg = "The file ". $fileName . " has been uploaded successfully.";
			}
			else{
				$error = "Please choose a smaller file.";
            	$url = 'edit_trick.php?dog_id=' . $_POST['trick_id'] . '&error=' . $error;
				header("Location: $url");
				exit();
			}
		}else{
			$error = "Please submit a PNG or JPG file.";
    		$url = 'edit_trick.php?dog_id=' . $_POST['trick_id'] . '&error=' . $error;
			header("Location: $url");
			exit();
		}

		$statement = $mysqli->prepare("UPDATE tricks SET name=?, rating=?, recommended=?, challenges=?, image=? WHERE id=?");

		$image_path ="trick_images/" . $_FILES["file"]["name"];

		$statement->bind_param("sssssi", $_POST['name'], $_POST['level'], $_POST['recommended'], $_POST['challenges'], $image_path, $_POST['trick_id']);

		$executed = $statement->execute();

		$trick_id = $mysqli->insert_id;

		$sql_prereqs = "DELETE FROM tricks_has_prereqs WHERE tricks_id=" . $_POST["trick_id"] . ";";
		$results_prereqs = $mysqli->query($sql_prereqs);
		if ( $results_prereqs == false ) {
			echo $mysqli->error;
			exit();
		}

		$sql_steps2 = "SELECT * FROM steps WHERE tricks_id=" . $_POST["trick_id"] . ";";
		$results_steps2 = $mysqli->query($sql_steps2);
		if ( $results_steps2 == false ) {
			echo $mysqli->error;
			exit();
		}

		while($steps_row = $results_steps2->fetch_assoc() )
		{
			 $flag = False;
			for ($x = 1; $x <= $_POST['numsteps']; $x++) {
				if ($steps_row["step"] == $_POST['step' . $x])
				{
					$flag = True;
				}
			}
			if ($flag == False) {
				$sql_dogsteps = "DELETE FROM dogs_has_steps WHERE steps_id=" . $steps_row["id"] . ";";
				$results_dogsteps = $mysqli->query($sql_dogsteps);
				if ( $results_dogsteps == false ) {
					echo $mysqli->error;
					exit();
				}

				$sql_steps = $mysqli->prepare("DELETE FROM steps WHERE tricks_id=? AND step=?;");
				$sql_steps->bind_param("is", $_POST["trick_id"], $steps_row["step"]);
				$executed_steps = $sql_steps->execute();
			}
				
		}


		foreach ($_POST['prereqs'] as $selectedOption)
		{
			$prereq_statement = $mysqli->prepare("INSERT INTO tricks_has_prereqs (tricks_id, prereqs_id) VALUES (?, ?)");

			$prereq_statement->bind_param("ii", $_POST['trick_id'], $selectedOption);

			$prereq_executed = $prereq_statement->execute();
		}

		$results_steps2->data_seek(0);
		for ($x = 1; $x <= $_POST['numsteps']; $x++) {
			$flag = False;
			while($steps_row = $results_steps2->fetch_assoc() ) {
				if ($steps_row["step"] == $_POST['step' . $x])
				{
					$flag = True;
				}
			}
			if ($flag == False) {
			$steps_statement2 = $mysqli->prepare("INSERT INTO steps (step, tricks_id) VALUES (?, ?)");

				$steps_statement2->bind_param("si", $_POST['step' . $x], $_POST['trick_id']);

				$steps_executed2 = $steps_statement2->execute();
			}
			$results_steps2->data_seek(0);
		}

		for ($x = 1; $x <= $_POST['numsteps']; $x++) {
			$order_statement = $mysqli->prepare("UPDATE steps SET step_number=? WHERE step=? AND tricks_id=?");
			echo $_POST['step' . $x];

			$order_statement->bind_param("isi", $x, $_POST['step' . $x], $_POST['trick_id']);

			$order_executed = $order_statement->execute();
		}

		if(!$executed) {
			echo $mysqli->error;
		}

		$mysqli->close();
	}
	else {
		$statement = $mysqli->prepare("UPDATE tricks SET name=?, rating=?, recommended=?, challenges=? WHERE id=?");

		$statement->bind_param("ssssi", $_POST['name'], $_POST['level'], $_POST['recommended'], $_POST['challenges'], $_POST['trick_id']);

		$executed = $statement->execute();

		$trick_id = $mysqli->insert_id;

		$sql_prereqs = "DELETE FROM tricks_has_prereqs WHERE tricks_id=" . $_POST["trick_id"] . ";";
		$results_prereqs = $mysqli->query($sql_prereqs);
		if ( $results_prereqs == false ) {
			echo $mysqli->error;
			exit();
		}

		$sql_steps2 = "SELECT * FROM steps WHERE tricks_id=" . $_POST["trick_id"] . ";";
		$results_steps2 = $mysqli->query($sql_steps2);
		if ( $results_steps2 == false ) {
			echo $mysqli->error;
			exit();
		}

		while($steps_row = $results_steps2->fetch_assoc() )
		{
			 $flag = False;
			for ($x = 1; $x <= $_POST['numsteps']; $x++) {
				if ($steps_row["step"] == $_POST['step' . $x])
				{
					$flag = True;
				}
			}
			if ($flag == False) {
				$sql_dogsteps = "DELETE FROM dogs_has_steps WHERE steps_id=" . $steps_row["id"] . ";";
				$results_dogsteps = $mysqli->query($sql_dogsteps);
				if ( $results_dogsteps == false ) {
					echo $mysqli->error;
					exit();
				}

				$sql_steps = $mysqli->prepare("DELETE FROM steps WHERE tricks_id=? AND step=?;");
				$sql_steps->bind_param("is", $_POST["trick_id"], $steps_row["step"]);
				$executed_steps = $sql_steps->execute();
			}
				
		}


		foreach ($_POST['prereqs'] as $selectedOption)
		{
			$prereq_statement = $mysqli->prepare("INSERT INTO tricks_has_prereqs (tricks_id, prereqs_id) VALUES (?, ?)");

			$prereq_statement->bind_param("ii", $_POST['trick_id'], $selectedOption);

			$prereq_executed = $prereq_statement->execute();
		}

		$results_steps2->data_seek(0);
		for ($x = 1; $x <= $_POST['numsteps']; $x++) {
			$flag = False;
			while($steps_row = $results_steps2->fetch_assoc() ) {
				if ($steps_row["step"] == $_POST['step' . $x])
				{
					$flag = True;
				}
			}
			if ($flag == False) {
			$steps_statement2 = $mysqli->prepare("INSERT INTO steps (step, tricks_id) VALUES (?, ?)");

				$steps_statement2->bind_param("si", $_POST['step' . $x], $_POST['trick_id']);

				$steps_executed2 = $steps_statement2->execute();
			}
			$results_steps2->data_seek(0);
		}

		for ($x = 1; $x <= $_POST['numsteps']; $x++) {
			$order_statement = $mysqli->prepare("UPDATE steps SET step_number=? WHERE step=? AND tricks_id=?");
			echo $_POST['step' . $x];

			$order_statement->bind_param("isi", $x, $_POST['step' . $x], $_POST['trick_id']);

			$order_executed = $order_statement->execute();
		}

		if(!$executed) {
			echo $mysqli->error;
		}

		$mysqli->close();
	}

	$url = 'trick_details.php?trick_id=' . $_POST['trick_id'];
	header( "Location: $url" );

}
?>