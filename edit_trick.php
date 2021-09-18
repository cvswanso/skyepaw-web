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

  $sql_tricks = "SELECT * FROM tricks WHERE id = " . $_GET["trick_id"] . ";";
  $results_tricks = $mysqli->query($sql_tricks);
  if ( $results_tricks == false ) {
    echo $mysqli->error;
    exit();
  }

  $row = $results_tricks->fetch_assoc();

  $sql_prereqs = "SELECT * FROM tricks;";
  $results_prereqs = $mysqli->query($sql_prereqs);
  if ( $results_prereqs == false ) {
    echo $mysqli->error;
    exit();
  }

  $sql_tricks_prereqs = "SELECT * FROM tricks_has_prereqs JOIN tricks ON tricks.id = tricks_has_prereqs.prereqs_id WHERE tricks_has_prereqs.tricks_id = " . $_GET["trick_id"] . ";";

  $results_tricks_prereqs = $mysqli->query($sql_tricks_prereqs);
  if ( $results_tricks_prereqs == false ) {
    echo $mysqli->error;
    exit();
  }

  $sql_steps = "SELECT * FROM steps WHERE tricks_id=" . $_GET["trick_id"] . " ORDER BY step_number;";
  $results_steps = $mysqli->query($sql_steps);
  if ( $results_steps == false ) {
    echo $mysqli->error;
    exit();
  }

  $steps_row = $results_steps->fetch_assoc();

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


    .add-minus-icon {
      font-size: 40px;
      margin-right: 10px;
      color: #28afb0;
    }

    .minus-icon {
      color:  #B8B8B8;
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
    <br>

    <div class="container-fluid">
          <form action="edit_trick_confirmation.php" method="POST" enctype="multipart/form-data">

        <div class="form-group row">
          <div class="col-sm-1">
          </div>
          <div class="col-sm-11">
          <h2 id="add-text">Edit <?php echo $row['name']?> <i class="fas fa-paw"></i></h2>
        </div>
      </div>

        <div class="form-group row">
          <div class="col-sm-1">
          </div>
          <div class="col-sm-11">
            <div class="custom-file mb-3">
              <input type="file" class="custom-file-input" id="customFile" name="file">
              <label class="custom-file-label" for="customFile"><?php echo substr($row['image'], 13)?></label>
            </div>
          </div>
        </div> <!-- .form-group -->

        <div class="form-group row">
          <label for="name" class="col-sm-3 col-form-label text-sm-right">Trick Name<span>*</span></label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $row['name']?>">
          </div>
        </div> <!-- .form-group -->

        <div class="form-group row">
          <label for="level" class="col-sm-3 col-form-label text-sm-right">Difficulty Level<span>*</span></label>
          <div class="col-sm-9">
            <select name="level" id="level" class="form-control">
              <?php if($row['rating'] == "Beginner") : ?>

                      <option value="Beginner" selected>Beginner</option>
                      <option value="Intermediate">Intermediate</option>
                      <option value="Advanced">Advanced</option>

                  <? elseif ($row['rating'] == "Intermediate"): ?>

                    <option value="Beginner">Beginner</option>
                      <option value="Intermediate" selected>Intermediate</option>
                      <option value="Advanced">Advanced</option>

                  <?php else: ?>

                      <option value="Beginner">Beginner</option>
                      <option value="Intermediate">Intermediate</option>
                      <option value="Advanced" selected>Advanced</option>
                      
                  <?php endif; ?>
            </select>
          </div>
        </div> <!-- .form-group -->

        <div class="form-group row">
          <label for="prereqs" class="col-sm-3 col-form-label text-sm-right">Prerequisites</label>
          <div class="col-sm-9">
            <select id="prereqs" data-placeholder="Start typing a trick name" multiple class="form-control form-control-chosen" name="prereqs[]">
              <?php while( $row3 = $results_prereqs->fetch_assoc() ): ?>
                    <?php $selected = False; ?>
                    <?php while( $row2 = $results_tricks_prereqs->fetch_assoc() ): ?>

                      <?php if ($row3['id'] == $row2['prereqs_id']) : ?>

                        <?php $selected = True; ?>

                      <?php endif; ?>

                    <?php endwhile; ?>

                    <?php if ($selected) : ?>

                      <option selected value="<?php echo $row3['id']; ?>">
                        <?php echo $row3['name']; ?>
                      </option>

                      <?php else: ?>
                        <option value="<?php echo $row3['id']; ?>">
                          <?php echo $row3['name']; ?>
                        </option>

                      <?php endif; ?>
                      <?php $results_tricks_prereqs->data_seek(0); ?>


                    <?php endwhile; ?>
            </select>
          </div>
        </div> <!-- .form-group -->

        <div class="form-group row">
          <label for="recommended" class="col-sm-3 col-form-label text-sm-right">Recommended</label>
          <div class="col-sm-9">
            <textarea name="recommended" id="recommended" class="form-control" placeholder="Is there anything else your dog needs to know or be comfortable with beforehand?"><?php echo $row['recommended']?></textarea>
          </div>
        </div> <!-- .form-group -->

        <div class="form-group row">
          <label for="challenges" class="col-sm-3 col-form-label text-sm-right">Challenges</label>
          <div class="col-sm-9">
            <textarea name="challenges" id="challenges" class="form-control" placeholder="Add some tips for challenging parts of this trick"><?php echo $row['challenges']?></textarea>
          </div>
        </div> <!-- .form-group -->

        <div id="addsteps">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label text-sm-right">Steps<span>*</span></label>
            <div class="col-sm-6">
              <input type="text" class="form-control" id="step1" name="step1" value="<?php echo htmlentities($steps_row['step']);?>">

            </div>
            <div class="col-sm-3">
              <i class="fas fa-plus-square add-minus-icon add"></i>
            </div>
          </div>
          <script>
            var numsteps = 1;
          </script>
          <?php $numsteps = 1; ?>
          <?php while( $row_step = $results_steps->fetch_assoc() ): ?>

            <script>
                  numsteps++;
                </script>
                <?php $numsteps = $numsteps + 1; ?>

                <div class="form-group row"><label for="steps" class="col-sm-3 col-form-label text-sm-right"></label><div class="col-sm-6"><input type="text" value="<?php echo htmlentities($row_step['step']);?>" class="form-control" name="step<?php echo $numsteps;?>"></div><div class="col-sm-3"><i class="fas fa-plus-square add-minus-icon add"></i><i class="fas fa-minus-square add-minus-icon minus-icon minus" ></i></div></div>


              <?php endwhile; ?>

        </div> <!-- .form-group -->

        <input type="hidden" class="form-control" id="trick_id" name="trick_id" value="<?php echo $_GET["trick_id"] ?>">

        <div class="form-group row">
          <div class="ml-auto col-sm-9">
            <input type="hidden" id="numsteps" name="numsteps" value="1">
            <script>
              document.getElementById("numsteps").value = numsteps;
            </script>
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


    </div> <!-- container-fluid -->
  </div> <!-- bg color -->

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/49954d4722.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>

  
  <script>
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
      console.log(numsteps);
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