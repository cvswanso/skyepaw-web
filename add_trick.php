<?php
  // Start the session
	require 'config/config.php';

  session_start();
  $error = $_GET['error'];

  // DB Connection.
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  if ( $mysqli->connect_errno ) {
    echo $mysqli->connect_error;
    exit();
  }

  $mysqli->set_charset('utf8');

  $sql_tricks = "SELECT * FROM tricks;";
  $results_tricks = $mysqli->query($sql_tricks);
  if ( $results_tricks == false ) {
    echo $mysqli->error;
    exit();
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Skyepaw</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css2?family=Covered+By+Your+Grace&family=Quicksand:wght@500&display=swap" rel="stylesheet">
	<link href="bootstrap4c-chosen-master/bootstrap4c-chosen-master/dist/css/component-chosen.min.css" rel="stylesheet">
	<link rel="stylesheet" href="navigation.css">
	<style>
		.search-tricks {
			margin: 20px;
		}

		.top-col {
			margin-top: 15px;
		}

		.pics {
			margin: auto;
			text-align: center
		}

		.image {
			width: 90%;
		}

		.form-container {
			padding-top: 30px;
		}

		.add-minus-icon {
			font-size: 40px;
			margin-right: 10px;
			color: #28afb0;
		}

		.minus-icon {
			color: 	#B8B8B8;
		}
		#upload {
			opacity: 0;
		}

		#upload-label {
			position: absolute;
			top: 50%;
			left: 1rem;
			transform: translateY(-50%);
		}

		.image-area {
			border: 2px dashed rgba(255, 255, 255, 0.7);
			padding: 1rem;
			position: relative;
		}

		.image-area::before {
			content: 'Uploaded image result';
			color: #fff;
			font-weight: bold;
			text-transform: uppercase;
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			font-size: 0.8rem;
			z-index: 1;
		}

		.image-area img {
			z-index: 2;
			position: relative;
		}

		#skyetunnel {
			width: 90%;
			display: block;
			margin: auto;
			border-radius: 10px;
		}

		#skyehighfive {
			width: 90%;
			display: block;
			margin: auto;
			border-radius: 10px;
		}
		label {
	      color: #fb8b3f;
	    }
	    h2 {
	      margin-top: 0;
	      color: #F886A8;
	      font-family: 'Covered By Your Grace', cursive;
	      font-size: 40px;

	    }


	</style>


</head>
<body>



	<nav class="nav-bg navbar navbar-expand-md navbar-dark nav-fill w-100">
    <a href="browsetricks.php" id="logo"><img id="logo-pic" src="skyepaw_logo.png" alt="skyepaw logo"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item active">
          <a class="nav-link choice" href="browsetricks.php"><i class="fas fa-search"></i>&nbsp;&nbsp;Browse Tricks</a>
        </li>
        <li class="nav-item">
          <a class="nav-link choice" href="learn.php"><i class="fab fa-blogger-b"></i>&nbsp;&nbsp;Learn</a>
        </li>

        <?php if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) : ?>

        <li class="nav-item">
          <a class="nav-link choice" href="profile.php"><i class="fas fa-user"></i>&nbsp;&nbsp;<?php echo $_SESSION["name"]?><span class="sr-only">(current)</span></a>
        </li>

        <?php endif; ?>


        <li class="nav-item">

          <?php if(!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) : ?>

            <a class="nav-link choice" href="login.php"><i class="fas fa-sign-in-alt"></i>&nbsp;&nbsp;Log In/Register</a>

        <?php else: ?>

            <a class="nav-link choice" href="signout.php"><i class="fas fa-sign-out-alt"></i>&nbsp;&nbsp;Sign Out</a>
            
        <?php endif; ?>
        </li>

      </ul>
    </div>
  </nav>

	<div id="bg-color">
		


		<div class="container-fluid form-container">



			<form action="add_trick_confirmation.php" method="POST" enctype="multipart/form-data">

				<div class="form-group row">
          <div class="col-sm-1">
          </div>
          <div class="col-sm-11">
          <h2 id="add-text">Add a Trick <i class="fas fa-paw"></i></h2>
          <div id="error"><?php echo $error; ?></div>
        </div>
      </div>

				<div class="form-group row">
					<div class="col-sm-1">
					</div>
					<div class="col-sm-11">
						<div class="custom-file mb-3">
							<input type="file" class="custom-file-input required" id="customFile" name="file">
							<label class="custom-file-label" for="customFile">Add a picture of the trick</label>
						</div>
					</div>
				</div> <!-- .form-group -->

				<div class="form-group row">
					<label for="name" class="col-sm-3 col-form-label text-sm-right">Trick Name*</label>
					<div class="col-sm-9">
						<input type="text" class="form-control required" id="name" name="name">
					</div>
				</div> <!-- .form-group -->

				<div class="form-group row">
					<label for="level" class="col-sm-3 col-form-label text-sm-right">Difficulty Level<span>*</span></label>
					<div class="col-sm-9">
						<select name="level" id="level" class="form-control">
							<option value="Beginner" selected>Beginner</option>
							<option value="Intermediate">Intermediate</option>
							<option value="Advanced">Advanced</option>
						</select>
					</div>
				</div> <!-- .form-group -->

				<div class="form-group row">
					<label for="prereqs" class="col-sm-3 col-form-label text-sm-right">Prerequisites</label>
					<div class="col-sm-9">
						<select id="prereqs" data-placeholder="Start typing a trick name" multiple class="form-control form-control-chosen" name="prereqs[]">
							<?php while( $row = $results_tricks->fetch_assoc() ): ?>

								<option value="<?php echo $row['id']; ?>">
									<?php echo $row['name']; ?>
								</option>

							<?php endwhile; ?>
						</select>
					</div>
				</div> <!-- .form-group -->

				<div class="form-group row">
					<label for="recommended" class="col-sm-3 col-form-label text-sm-right">Recommended</label>
					<div class="col-sm-9">
						<textarea name="recommended" id="recommended" class="form-control" placeholder="Is there anything else your dog needs to know or be comfortable with beforehand?"></textarea>
					</div>
				</div> <!-- .form-group -->

				<div class="form-group row">
					<label for="challenges" class="col-sm-3 col-form-label text-sm-right">Challenges</label>
					<div class="col-sm-9">
						<textarea name="challenges" id="challenges" class="form-control" placeholder="Add some tips for challenging parts of this trick"></textarea>
					</div>
				</div> <!-- .form-group -->

				<div id="addsteps">
					<div class="form-group row">
						<label class="col-sm-3 col-form-label text-sm-right">Steps<span>*</span></label>
						<div class="col-sm-6">
							<input type="text" class="form-control required" id="step1" name="step1">
						</div>
						<div class="col-sm-3">
							<i class="fas fa-plus-square add-minus-icon add"></i>


						</div>
					</div>

				</div> <!-- .form-group -->

				<div class="form-group row">
					<div class="ml-auto col-sm-9">
						<input type="hidden" id="numsteps" name="numsteps" value="1">
						<span class="font-italic">* Required</span>
					</div>
				</div> <!-- .form-group -->

				<div class="form-group row">
					<div class="col-sm-3"></div>
					<div class="col-sm-9 mt-2">
						<button type="submit" class="btn btn-warning">Submit</button>
					</div>
				</div> <!-- .form-group -->

			</form>

		</div> <!-- .container -->

		<br>
		<br>
		<br>



	</div>


	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
	<script src="https://kit.fontawesome.com/49954d4722.js" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>

	<script>
		var numsteps = 1;
		$('.form-control-chosen').chosen();

		$('#addsteps').on('click', '.minus', function() {
			$(this).parent().parent().remove();
			numsteps--;
			document.getElementById("numsteps").value = numsteps;
			var i = 1;
			$('#addsteps').find('input').each(function(index) {
				$(this).attr('name', ('step' + i));
				i++;
			});
		});

		$('#addsteps').on('click', '.add', function() {
			numsteps++;
			document.getElementById("numsteps").value = numsteps;
			$(this).parent().parent().after('<div class="form-group row"><label for="steps" class="col-sm-3 col-form-label text-sm-right"></label><div class="col-sm-6"><input type="text" class="form-control" name="step' + numsteps + '"></div><div class="col-sm-3"><i class="fas fa-plus-square add-minus-icon add"></i><i class="fas fa-minus-square add-minus-icon minus-icon minus" ></i></div></div>');
			var i = 1;
			$('#addsteps').find('input').each(function(index) {
				$(this).attr('name', ('step' + i));
				i++;
			});

		});

		$(".custom-file-input").on("change", function() {
			var fileName = $(this).val().split("\\").pop();
			$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
		});

		$("form").on("submit", function() {
			if ($(".required").val().trim().length == 0)
			{
				$("#error").html("Please fill out the required fields.");
				event.preventDefault();
			}
			if ($("#name").val().trim().length == 0)
			{
				$("#error").html("Please fill out the required fields.");
				event.preventDefault();
			}
			if ($("#step1").val().trim().length == 0)
			{
				$("#error").html("Please fill out the required fields.");
				event.preventDefault();
			}
		});

	</script>

</body>
</html>