<?php
  // Start the session
  session_start();
  require 'config/config.php';

  $error = $_GET['error'];

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

  $sql_dogs = "SELECT * FROM dogs WHERE users_id = " . $_SESSION["user_id"] . ";";
  $results_dogs = $mysqli->query($sql_dogs);
  if ( $results_dogs== false ) {
    echo $mysqli->error;
    exit();
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
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
      width: 100%;
    }
    h1 {
      font-family: 'Covered By Your Grace', cursive;
      font-size: 50px;
      color: #F886A8;

    }
    h2 {
      margin-top: 0;
      color: #28AFB0;
      font-family: 'Covered By Your Grace', cursive;
      font-size: 40px;

    }

    h4 {
      color: #fb8b3f;
    }

    p {
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
    .editbuttons {
      margin-right: 20px;
    }
    .pics {
      text-align: center;
      margin-top: 20px;
    }
    @media(min-width: 768px) {
      #logo {
        width: 13%;
      }
    }
    .progress {
      margin-top: 10px;
      margin-bottom: 10px;
    }
  </style>
  


</head>
<body>
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

      <div class="row">
        <div class="col-12 col-md-3">
          <h1 id="user"><?php echo $_SESSION["name"]?> </h1>
        </div>

        <div class="col-12 col-md-9">

          <ul class="nav nav-tabs" role="tablist">

            <?php $count = 0; ?>

            <?php while( $row = $results_dogs->fetch_assoc() ): ?>

              <?php if($count == 0 && !isset($error) && empty($error)) : ?>

                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#<?php echo $row['call_name'] . $row['id']; ?>"><i class="fas fa-dog"></i> <?php echo $row['call_name']; ?></a>
                  </li>

                <?php else: ?>

                  <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#<?php echo $row['call_name'] . $row['id']; ?>"><i class="fas fa-dog"></i> <?php echo $row['call_name']; ?></a>
                  </li>

                <?php endif; ?>


              <?php $count = $count + 1; ?>
              <?php endwhile; ?>

            
              <?php if($count == 0 || isset($error) || !empty($error)) : ?>


                <li class="nav-item">
              <a class="nav-link active" data-toggle="tab" href="#add"><i class="fas fa-plus-square"></i> Add a Dog</a>
            </li>

                <?php else: ?>
                  <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#add"><i class="fas fa-plus-square"></i> Add a Dog</a>
            </li>

                <?php endif; ?>


          </ul>


        </div> <!-- column -->
      </div> <!-- row -->

      <div class="tab-content">

        <?php $results_dogs->data_seek(0); ?>

        <?php $i = 0; ?>

        <?php while( $row = $results_dogs->fetch_assoc() ): ?>

          <?php if($i == 0 && !isset($error) && empty($error)) : ?>

          <div id="<?php echo $row['call_name'] . $row['id']; ?>" class="container tab-pane active">

          <?php else: ?>

            <div id="<?php echo $row['call_name'] . $row['id']; ?>" class="container tab-pane fade">

              <?php endif; ?>
              <?php $i = $i+ 1; ?>

            <br>
          <div class="row">
            <div class="col-12 col-md-4">
              <img src="<?php echo (substr($row['image'], 0, 11) . $row['id'] . substr($row['image'], 11))?>" alt="<?php echo $row['call_name']?>">
            </div>
            <div class="col-12 col-md-8">
              <h2><?php echo $row['name']?> <i class="fas fa-paw"></i></h2>
              <div class="row">
                <div class="col-12 col-sm-6">
                  <br>
                  <h4>About</h4>
                  <p><?php echo $row['sex']?></p>
                  <p><?php echo $row['breed']?></p>
                  <p>Born <?php echo $row['birthdate']?></p>
                </div>
                <div class="col-12 col-sm-6">
                  <br>
                  <h4>Sports</h4>
                  <?php $display_dog_id =  $row['id']; ?>
                  <?php $sql_dog_sports = "SELECT * FROM dogs_has_sports JOIN sports ON sports.id = dogs_has_sports.sports_id WHERE dogs_id =  " . $display_dog_id . ";";
                  $results_dog_sports = $mysqli->query($sql_dog_sports);
                  if ( $results_dog_sports== false ) {
                    echo $mysqli->error;
                    exit();
                  } ?>

                  <?php while( $row2 = $results_dog_sports->fetch_assoc() ): ?>
                    <p><?php echo $row2['name'] ?></p>

                  <?php endwhile; ?>

                </div>
              </div>
              <div class="row titles">
                <h4>Titles</h4>
              </div>
              <div class="row titles">
                <?php $sql_dog_titles = "SELECT * FROM dogs_has_titles JOIN titles ON titles.id = dogs_has_titles.titles_id WHERE dogs_id =  " . $display_dog_id . ";";
                  $results_dog_titles = $mysqli->query($sql_dog_titles);
                  if ( $results_dog_titles== false ) {
                    echo $mysqli->error;
                    exit();
                  } ?>

                  <?php while( $row3 = $results_dog_titles->fetch_assoc() ): ?>
                    <p><?php echo $row3['name']; ?></p>

                  <?php endwhile; ?>
              </div>
              <div class="row edit">
                <a href="edit_dog.php?dog_id=<?php echo $row['id'];?>" class="btn btn-info editbuttons">Edit <?php echo $row['call_name']; ?>'s Profile</a><br>
                <a href="delete_dog.php?dog_id=<?php echo $row['id'];?>" class="btn btn-warning">Delete <?php echo $row['call_name']; ?>'s Profile</a>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <h4>Tricks</h4>
          </div>
          <div class="row">
            <?php 
            $sql_tricks = "SELECT * FROM dogs_has_steps JOIN tricks ON dogs_has_steps.steps_tricks_id = tricks.id WHERE dogs_id = " . $display_dog_id . ";";
            $results_tricks = $mysqli->query($sql_tricks);
            if ( $results_tricks == false ) {
              echo $mysqli->error;
              exit();
            }
            $counted_tricks = array();
            $total_steps = array();
            $completed_steps = array();

            ?>
            <?php while( $row_trick = $results_tricks->fetch_assoc() ): ?>
              <?php if(!in_array($row_trick['name'], $counted_tricks)) : ?>
              
              <?php array_push($counted_tricks, $row_trick['name']); ?>
              <?php $row_trick["steps"] = 1; ?>
              <?php $completed_steps[$row_trick['name']] = $row_trick; ?>
              <?php else: ?>
                <?php $completed_steps[$row_trick['name']]["steps"] += 1; ?>
              <?php endif; ?>
            <?php endwhile; ?>

            <div class="row">

              <?php $anysteps = False; ?>

              <?php foreach($completed_steps as $trick=>$steps): ?>

                <?php 

                  $anysteps = True;
                  $sql_dog_steps = "SELECT * FROM steps WHERE tricks_id = " . $steps['steps_tricks_id'] . ";";
                  $results_dog_steps = $mysqli->query($sql_dog_steps);
                  if ( $results_dog_steps == false ) {
                    echo $mysqli->error;
                    exit();
                  }
                  $total_steps = mysqli_num_rows($results_dog_steps);

                  ?>

                  <div class="col-6 col-sm-4 col-md-3 pics">
                    <a href="trick_details.php?trick_id=<?php echo $steps['id']; ?>">
                      <img src="<?php echo (substr($steps['image'], 0, 13) . $steps['id'] . substr($steps['image'], 13))?>" class="image" alt="<?php echo $steps['name']; ?>"></a>
                      <div class="progress">
                        <div class="progress-bar bg-info" style="width: <?php echo ($steps["steps"]*100.0)/$total_steps;?>%;" role="progressbar" aria-valuenow="<?php echo ($steps["steps"]*100.0)/$total_steps;?>" aria-valuemin="0" aria-valuemax="100"></div></div>
                        <?php echo $steps['name']; ?>
                      </div>

                <?php endforeach; ?>

                <?php if(!$anysteps) : ?>
                  <p><?php echo $row['call_name']; ?> hasn't started any tricks yet.</p>
                <?php endif; ?>

        </div>



        </div>
      </div>

    <?php endwhile; ?>



    <?php if($i == 0 || isset($error) || !empty($error)) : ?>

          <div id="add" class="container tab-pane active">

          <?php else: ?>

            <div id="add" class="container tab-pane fade">

            <?php endif; ?>
        

        <br>
          <div class="form-group row">
          <div class="col-sm-1">
          </div>
          <div class="col-sm-11">
          <h2 id="add-text">Add a Dog <i class="fas fa-paw"></i></h2>
          <div id="error"><?php echo $error; ?></div>
        </div>
      </div>

          <form action="add_dog_confirmation.php" method="POST" enctype="multipart/form-data">
            <div class="form-group row">
              <div class="col-sm-1">
              </div>
              <div class="col-sm-11">
                <div class="custom-file mb-3">
                  <input type="file" class="custom-file-input" id="customFile" name="file">
                  <label class="custom-file-label" for="customFile">Add a picture of your dog</label>
                </div>
              </div>
            </div> <!-- .form-group -->
            <div class="form-group row">
              <label for="callname" class="col-sm-3 col-form-label text-sm-right">Call Name<span>*</span></label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="callname" name="callname">
              </div>
            </div> <!-- .form-group -->
            <div class="form-group row">
              <label for="name" class="col-sm-3 col-form-label text-sm-right">Registered Name<span>*</span></label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="name" name="name">
              </div>
            </div> <!-- .form-group -->
            <div class="form-group row">
              <label for="sex" class="col-sm-3 col-form-label text-sm-right">Sex<span>*</span></label>
              <div class="col-sm-9">
                <select name="sex" id="sex" class="form-control">
                  <option value="Female" selected>Female</option>
                  <option value="Male">Male</option>
                </select>
              </div>
            </div> <!-- .form-group -->
            <div class="form-group row">
              <label for="sex" class="col-sm-3 col-form-label text-sm-right">Birthdate<span>*</span></label>
              <div class="col-sm-9">
                <input type="date" class="form-control" id="birthdate" name="birthdate">
              </div>
            </div> <!-- .form-group -->
            <div class="form-group row">
              <label for="breed" class="col-sm-3 col-form-label text-sm-right">Breed<span>*</span></label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="breed" name="breed">
              </div>
            </div> <!-- .form-group -->
            <div class="form-group row">
              <label for="sport" class="col-sm-3 col-form-label text-sm-right">Sports</label>
              <div class="col-sm-9">
                <select data-placeholder="Start typing a sport name" multiple class="form-control form-control-chosen" name="sport[]" id="sport">
                  <?php while( $row = $results_sports->fetch_assoc() ): ?>

                    <option value="<?php echo $row['id']; ?>">
                      <?php echo $row['name']; ?>
                    </option>

                  <?php endwhile; ?>
                </select>
              </div>
            </div> <!-- .form-group -->
            <div class="form-group row">
              <label for="title" class="col-sm-3 col-form-label text-sm-right">Titles</label>
              <div class="col-sm-9">
                <select data-placeholder="Start typing a title" multiple class="form-control form-control-chosen" name="title[]" id="title">
                  <?php while( $row = $results_titles->fetch_assoc() ): ?>

                  <option value="<?php echo $row['id']; ?>">
                    <?php echo $row['name']; ?>
                  </option>

                <?php endwhile; ?>
              </select>
            </div>
          </div> <!-- .form-group -->
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
      </div>
    </div>
  </div>


    </div> <!-- container-fluid -->
  </div> <!-- bg color -->


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
      if ($("#customFile").val().trim().length == 0)
      {
        $("#error").html("Please fill out the required fields.");
        event.preventDefault();
      }
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