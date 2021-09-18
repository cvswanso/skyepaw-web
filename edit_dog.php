<?php
require 'config/config.php';

session_start();

$error = $_GET['error'];

if( !isset($_GET["dog_id"]) || empty($_GET["dog_id"])) {

  $error =  "Invalid dog ID";
  echo $error;
  exit();

}
else {

  // DB Connection.
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  if ( $mysqli->connect_errno ) {
    echo $mysqli->connect_error;
    exit();
  }

  $mysqli->set_charset('utf8');

  $sql_sports = "SELECT * FROM sports;";
  $results_sports = $mysqli->query($sql_sports);
  if ( $results_sports == false ) {
    echo $mysqli->error;
    exit();
  }

  $sql_titles = "SELECT * FROM titles;";
  $results_titles = $mysqli->query($sql_titles);
  if ( $results_titles == false ) {
    echo $mysqli->error;
    exit();
  }

  $sql_dogs = "SELECT * FROM dogs WHERE id = " . $_GET["dog_id"] . ";";
  $results_dogs = $mysqli->query($sql_dogs);
  if ( $results_dogs == false ) {
    echo $mysqli->error;
    exit();
  }
  while( $row = $results_dogs->fetch_assoc()) {
    $user_dog_id = $row["users_id"];
    $name = $row["name"];
    $call_name = $row["call_name"];
    $sex = $row["sex"];
    $birthdate = $row["birthdate"];
    $breed = $row["breed"];
    $image = $row["image"];
  }
  if ($user_dog_id != $_SESSION["user_id"])
  {
    $error =  "This is not your dog.";
    echo $error;
    exit();
  }

  $sql_dog_sports = "SELECT * FROM dogs_has_sports JOIN sports ON dogs_has_sports.sports_id = sports.id WHERE dogs_id = " . $_GET["dog_id"] . ";";
  $results_dog_sports = $mysqli->query($sql_dog_sports);
  if ( $results_dog_sports == false ) {
    echo $mysqli->error;
    exit();
  }

  $sql_dog_titles = "SELECT * FROM dogs_has_titles JOIN titles ON dogs_has_titles.titles_id = titles.id WHERE dogs_id = " . $_GET["dog_id"] . ";";
  $results_dog_titles = $mysqli->query($sql_dog_titles);
  if ( $results_dog_titles == false ) {
    echo $mysqli->error;
    exit();
  }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Skyepaw</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css2?family=Covered+By+Your+Grace&family=Quicksand:wght@500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link href="bootstrap4c-chosen-master/bootstrap4c-chosen-master/dist/css/component-chosen.min.css" rel="stylesheet">
  <link rel="stylesheet" href="navigation.css">

  <style>
    form > * {
      margin: 15px;
    }

    .row {
      margin-left: 25px;
      margin-top: 0px;
    }

    #user {
      margin-top: 10px;
    }

    img {
      width: 90%;
    }
    h1 {
      font-family: 'Covered By Your Grace', cursive;
      font-size: 50px;
      color: #F886A8;

    }
    h2 {
      margin-top: 0;
      color: #F886A8;
      font-family: 'Covered By Your Grace', cursive;
      font-size: 40px;

    }

    #skye h4 {
      color: #fb8b3f;
    }

    #skye p {
      font-family: Quicksand;
    }

    .titles {
      margin-left: 35px;
    }
    p {
      margin-left: 15px;
    }
    .edit {
      margin-left: 40px;
      margin-top: 17px;
    }
    label {
      color: #fb8b3f;
    }
  </style>
</head>
<body>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
  


  <nav class="nav-bg navbar navbar-expand-md navbar-dark nav-fill w-100">
    <a href="browsetricks.php" id="logo"><img id="logo-pic" src="skyepaw_logo.png" alt="skyepaw logo"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link choice" href="browsetricks.php"><i class="fas fa-search"></i>&nbsp;&nbsp;Browse Tricks</a>
        </li>
        <li class="nav-item">
          <a class="nav-link choice" href="learn.php"><i class="fab fa-blogger-b"></i>&nbsp;&nbsp;Learn</a>
        </li>

        <?php if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) : ?>

        <li class="nav-item active">
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
    <br>

    <div class="container-fluid">

        <div id="add"><br>
          <div class="form-group row">
          <div class="col-sm-1">
          </div>
          <div class="col-sm-11">
          <h2 id="add-text">Edit <?php echo $call_name ?>'s Profile <i class="fas fa-paw"></i></h2>
          <div id="error"><?php echo $error?></div>
        </div>
      </div>

          <form action="edit_dog_confirmation.php" method="POST" enctype="multipart/form-data">
            <div class="form-group row">
              <div class="col-sm-1">
              </div>
              <div class="col-sm-11">
                <div class="custom-file mb-3">
                  <input type="file" class="custom-file-input" id="customFile" name="file">
                  <label class="custom-file-label" for="customFile"><?php echo substr($image, 11) ?></label>
                </div>
              </div>
            </div> <!-- .form-group -->
            <div class="form-group row">
              <label for="callname" class="col-sm-3 col-form-label text-sm-right">Call Name<span>*</span></label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="callname" name="callname" value="<?php echo $call_name ?>">
              </div>
            </div> <!-- .form-group -->
            <div class="form-group row">
              <label for="name" class="col-sm-3 col-form-label text-sm-right">Registered Name<span>*</span></label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $name ?>">
              </div>
            </div> <!-- .form-group -->
            <div class="form-group row">
              <label for="sex" class="col-sm-3 col-form-label text-sm-right">Sex<span>*</span></label>
              <div class="col-sm-9">
                <select name="sex" id="sex" class="form-control">
                  <?php echo $sex ?>


                  <?php if($sex == "Female") : ?>

                      <option value="Female" selected>Female</option>
                      <option value="Male">Male</option>

                  <?php else: ?>

                      <option value="Female">Female</option>
                      <option value="Male" selected>Male</option>
                      
                  <?php endif; ?>

                </select>
              </div>
            </div> <!-- .form-group -->
            <div class="form-group row">
              <label for="sex" class="col-sm-3 col-form-label text-sm-right">Birthdate<span>*</span></label>
              <div class="col-sm-9">
                <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?php echo $birthdate ?>">

                <input type="hidden" name="dog_id" value="<?php echo $_GET["dog_id"] ?>">
              </div>
            </div> <!-- .form-group -->
            <div class="form-group row">
              <label for="breed" class="col-sm-3 col-form-label text-sm-right">Breed<span>*</span></label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="breed" name="breed" value="<?php echo $breed ?>">
              </div>
            </div> <!-- .form-group -->
            <div class="form-group row">
              <label for="sport" class="col-sm-3 col-form-label text-sm-right">Sports</label>
              <div class="col-sm-9">
                <select id="sport" data-placeholder="Start typing a sport name" multiple class="form-control form-control-chosen" name="sport[]">
                  <?php while( $row = $results_sports->fetch_assoc() ): ?>
                    <?php $selected = False; ?>
                    <?php while( $row2 = $results_dog_sports->fetch_assoc() ): ?>

                      <?php echo  $row2['id'];?>
                      <?php if ($row['id'] == $row2['id']) : ?>

                        <?php $selected = True; ?>

                      <?php endif; ?>

                    <?php endwhile; ?>

                    <?php if ($selected) : ?>

                      <option selected value="<?php echo $row['id']; ?>">
                        <?php echo $row['name']; ?>
                      </option>

                      <?php else: ?>
                        <option value="<?php echo $row['id']; ?>">
                          <?php echo $row['name']; ?>
                        </option>

                      <?php endif; ?>
                      <?php $results_dog_sports->data_seek(0); ?>



                    <?php endwhile; ?>
                  </select>
                </div>
              </div> <!-- .form-group -->
            <div class="form-group row">
              <label for="title" class="col-sm-3 col-form-label text-sm-right">Titles</label>
              <div class="col-sm-9">
                <select id="title" data-placeholder="Start typing a title" multiple class="form-control form-control-chosen" name="title[]">
                  <?php while( $row = $results_titles->fetch_assoc() ): ?>
                    <?php $selected = False; ?>
                    <?php while( $row2 = $results_dog_titles->fetch_assoc() ): ?>

                      <?php echo  $row2['id'];?>
                      <?php if ($row['id'] == $row2['id']) : ?>

                        <?php $selected = True; ?>

                      <?php endif; ?>

                    <?php endwhile; ?>

                    <?php if ($selected) : ?>

                      <option selected value="<?php echo $row['id']; ?>">
                        <?php echo $row['name']; ?>
                      </option>

                      <?php else: ?>
                        <option value="<?php echo $row['id']; ?>">
                          <?php echo $row['name']; ?>
                        </option>

                      <?php endif; ?>
                      <?php $results_dog_titles->data_seek(0); ?>



                    <?php endwhile; ?>
                </select>
              </div>
            </div> <!-- .form-group -->
            <input type="hidden" class="form-control" id="dog_id" name="dog_id" value="<?php echo $_GET["dog_id"] ?>">
            <div class="form-group row">
              <div class="ml-auto col-sm-9">
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
        </div>


    </div> <!-- container-fluid -->
  </div> <!-- bg color -->

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/49954d4722.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>

  
  <script>
    $('.form-control-chosen').chosen();
    $(".custom-file-input").on("change", function() {
      var fileName = $(this).val().split("\\").pop();
      $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

    $("form").on("submit", function() {
      if ($("#name").val().trim().length == 0)
      {
        $("#callname").html("Please fill out the required fields.");
        event.preventDefault();
      }
      if ($("#birthdate").val().trim().length == 0)
      {
        $("#error").html("Please fill out the required fields.");
        event.preventDefault();
      }
      if ($("#breed").val().trim().length == 0)
      {
        $("#error").html("Please fill out the required fields.");
        event.preventDefault();
      }
    });
  </script>


</body>
</html>