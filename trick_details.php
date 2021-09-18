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

$sql_prereqs = "SELECT * FROM tricks_has_prereqs JOIN tricks ON tricks.id = tricks_has_prereqs.prereqs_id WHERE tricks_has_prereqs.tricks_id = " . $_GET["trick_id"] . ";";

$results_prereqs = $mysqli->query($sql_prereqs);
if ( $results_prereqs == false ) {
  echo $mysqli->error;
  exit();
}
$sql_dogs = "";
if (!empty($_SESSION["user_id"])) {
$sql_dogs = "SELECT * FROM dogs WHERE users_id = " . $_SESSION["user_id"] . ";";
  $results_dogs = $mysqli->query($sql_dogs);
  if ( $results_dogs== false ) {
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

    .skye h4 {
      color: #fb8b3f;
    }

    .skye p {
      font-family: Quicksand;
    }

    p {
      margin-left: 15px;
    }
    .edit {
      margin-left: 40px;
      margin-top: 17px;
    }
    label, span {
      color: #fb8b3f;
    }
    .rating {
      margin-top: 0;
      font-family: 'Covered By Your Grace', cursive;
      font-size: 30px;
      color: #065465;
      margin-right: 10px;
    }
    a {
      margin-right: 20px;
    }
    .progress-bar {
      width: 0%;
    }
    .progress {
      margin-top: 10px;
    }
    .select-box {
      margin-right: 10px;
    }
    .prep-prereq-col {
      margin-left: -40px;
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

        <br>

        <div class="row justify-content-between w-100">
          <div class="col-12 col-md-auto">
            <h2><?php echo $row['name']?> <i class="fas fa-paw"></i></h2>
          </div>
          <div class="col-12 col-md-auto">
            <h4 class="rating"><?php echo $row['rating']?> </h4>
          </div>
        </div>
        <div class="row">
          <div class="col-12 col-md-6 col-lg-4 order-first">
            <img src="<?php echo (substr($row['image'], 0, 13) . $row['id'] . substr($row['image'], 13))?>" alt="<?php echo $row['name']?>">
          </div>
          <div class="col-12 col-md-6 col-lg-8 prep-prereq-col">
            <div class="row skye">
              <div class="col-12 order-md-2">
                <br>
                <h4>Recommended Preparation</h4>
                <p><?php if (!empty($row['recommended'])) echo $row['recommended']?></p>
                <p><?php if (empty($row['recommended'])) echo "There is no recommended preparation for this trick.";?></p>
              </div>
              <div class="col-12 order-md-1">
               <br>
               <h4>Prerequisites</h4>
               <?php $prereqs_counter = 0; ?>
               <?php while( $row3 = $results_prereqs->fetch_assoc() ): ?>
                <a href="trick_details.php?trick_id=<?php echo $row3['id']; ?>"><p><?php echo $row3['name']; ?></p></a>
                <?php $prereqs_counter++; ?>
              <?php endwhile; ?>
              <p><?php if ($prereqs_counter == 0) echo "This trick has no prerequisites.";?></p>
            </div>
          </div>
        </div>
      </div>
      <br>
      <div class="skye row justify-content-between w-100">
        <div class="col-auto"><h4>Steps</h4></div>
        <?php if (!empty($_SESSION["user_id"])) : ?>
        <div class="col"><div class="progress">
          <div class="progress-bar bg-info" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div></div></div>
          <div class="col-auto select-box order-md-2"><select name="dog" id="dog-dropdown" class="form-control">
            <?php while( $row = $results_dogs->fetch_assoc() ): ?>
              <option value="<?php echo $row['id']; ?>">
                  <?php echo $row['call_name']; ?>
                </option>
            <?php endwhile; ?>
          </select></div>
        <?php endif; ?>
        </div>

        <div class="row skye">
          <div class="col-12 step-check-wrapper">

            <?php $sql_steps = "SELECT * FROM steps WHERE tricks_id =  " . $_GET['trick_id'] . " ORDER BY step_number;";
            $results_steps = $mysqli->query($sql_steps);
            if ( $results_steps == false ) {
              echo $mysqli->error;
              exit();
            } ?>

            <script> var numsteps = 0; </script>

            <?php while( $row2 = $results_steps->fetch_assoc() ): ?>

              <script> numsteps = numsteps + 1; </script>
              <p>
                <?php if (!empty($_SESSION["user_id"])) : ?>
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="<?php echo $row2['id'] ?>" class="form-check-input step-check" type="checkbox" value="">
                <?php endif; ?>
                <?php echo $row2['step'] ?></p>

              <?php endwhile; ?>
            </div>
          </div>
          <div class="row skye">
            <div class="col-12"><br>
              <h4>Challenges</h4>
              <p><?php if (!empty($row['challenges'])) echo $row['challenges']?></p>
              <p><?php if (empty($row['challenges'])) echo "There are no challenges when teaching this trick!
              If you are teaching you dog and you encounter any, feel free to add them!";?></p>
            </div>
          </div>

          <div class="row edit">
            <a href="edit_trick.php?trick_id=<?php echo $_GET['trick_id'];?>" class="btn btn-info">Edit <?php echo $row['name']?></a>
            <a href="delete_trick.php?trick_id=<?php echo $_GET['trick_id'];?>" class="btn btn-warning">Delete <?php echo $row['name']?></a>
          </div>



        </div> <!-- container-fluid -->
      </div> <!-- bg color -->

      <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
      <script src="https://kit.fontawesome.com/49954d4722.js" crossorigin="anonymous"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>


      <script>

        function ajaxGet(endpointUrl, returnFunction){
          var xhr = new XMLHttpRequest();
          xhr.open('GET', endpointUrl, true);
          xhr.onreadystatechange = function(){
            if (xhr.readyState == XMLHttpRequest.DONE) {
              if (xhr.status == 200) {
                returnFunction( xhr.responseText );
              } else {
                alert('AJAX Error.');
              }
            }
          }
          xhr.send();
        };


      ajaxGet('updatesteps.php?mode=initialize&trick_id=<?php echo $_GET["trick_id"];?>&dog_id=' + $("#dog-dropdown").val(), function(results) {

        results = JSON.parse(results);

        for( var i = 0; i < results.length; i++) {
           document.getElementById(results[i].steps_id).checked = true;
        }
        calcPercent();
      })

      $("#dog-dropdown").on("change", function() {
        console.log('updatesteps.php?mode=initialize&trick_id=<?php echo $_GET["trick_id"];?>&dog_id=' + $("#dog-dropdown").val());
        ajaxGet('updatesteps.php?mode=initialize&trick_id=<?php echo $_GET["trick_id"];?>&dog_id=' + $("#dog-dropdown").val(), function(results) {

        results = JSON.parse(results);

        $(".step-check-wrapper").find("input").prop( "checked", false);
        for( var i = 0; i < results.length; i++) {
           document.getElementById(results[i].steps_id).checked = true;
        }
        calcPercent();
      })
      });




        $('.form-control-chosen').chosen();
        $(".custom-file-input").on("change", function() {
          var fileName = $(this).val().split("\\").pop();
          $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });


        var calcPercent = function() {
          var checked = $( "input:checked" ).length;
          var percent = checked*100.0/numsteps;
          percent = Math.ceil(percent).toString();
          percent += "%";
          $('.progress-bar').width(percent);
        };

        $(".step-check-wrapper").find("input").change(function(){
          if (this.checked) {
            ajaxGet('updatesteps.php?mode=add&trick_id=<?php echo $_GET["trick_id"];?>&dog_id=' + $("#dog-dropdown").val() + "&step_id=" + $(this).attr('id'), function(results) {

              results = JSON.parse(results);

              $(".step-check-wrapper").find("input").prop( "checked", false);
              for( var i = 0; i < results.length; i++) {
               document.getElementById(results[i].steps_id).checked = true;
             }
             calcPercent();
           })
          }
          else {
            ajaxGet('updatesteps.php?mode=delete&trick_id=<?php echo $_GET["trick_id"];?>&dog_id=' + $("#dog-dropdown").val() + "&step_id=" + $(this).attr('id'), function(results) {

              results = JSON.parse(results);

              $(".step-check-wrapper").find("input").prop( "checked", false);
              for( var i = 0; i < results.length; i++) {
               document.getElementById(results[i].steps_id).checked = true;
             }
             calcPercent();
           })
          }
        });


  </script>


</body>
</html>