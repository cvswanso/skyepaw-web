<?php

require 'config/config.php';

session_start();

$error = $_GET['error'];

// If no user is logged in, do the usual things. Otherwise, redirect user out of this page.
if( !isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {

  // Check if user has entered in username/password
  if (isset($_POST['password']) && isset($_POST['email'])) {
    
    // User did not enter username/password, it's blank
    if (empty($_POST['password'] || empty($_POST['email'])) ) {

      $loginerror = "Please enter first name, email, and password.";

    }
    else {
      // User did enter username/password but need to check if the username/pw combination is correct
      $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

      if($mysqli->connect_errno) {
        echo $mysqli->connect_error;
        exit();
      }

      // Hash whatever user typed in for password, then compare this to the hashed password in the DB
      $passwordInput = hash("sha256", $_POST["password"]);

      $sql = "SELECT * FROM users
            WHERE email = '" . $_POST['email'] . "' AND password = '" . $passwordInput . "';";
      
      $results = $mysqli->query($sql);

      if(!$results) {
        echo $mysqli->error;
        exit();
      }

      // If we get 1 result back, means username/pw combination is correct.
      if($results->num_rows > 0) {
        // Set sesssion variables to remember this user
        while ($row = mysqli_fetch_assoc($results)) {
          $user_id = $row['id'];
          $name = $row['name'];
        }

        $_SESSION["logged_in"] = true;
        $_SESSION["user_id"] = $user_id;
        $_SESSION["name"] = $name;

        // Success! Redirect user to the home page
        header("Location: profile.php");

      }
      else {
        $loginerror = "Invalid username or password.";
      }
    } 
  }
}
// Redirect logged in user to home
else {
  header("Location: browsetricks.php");
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
      margin-left: 0px;
      padding-left: 0px;
    }


    .add-minus-icon {
      font-size: 40px;
      margin-right: 10px;
      color: #28afb0;
    }

    .minus-icon {
      color:  #B8B8B8;
    }
    .accountheading {
      color:  #28afb0;
    }
    .welcome-image {
      width: 60%;
      margin: auto;
    }
    .email-heading {
      padding-left: 0px;
      margin-left: 0px;
    }

    .row {
      margin-left: 0px;
    }
    .register-btn {
      margin-bottom: 25px;
    }
    .login-col {
      margin-bottom: 40px;
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

        <li class="nav-item">
          <a class="nav-link choice" href="profile.php"><i class="fas fa-user"></i>&nbsp;&nbsp;<?php echo $_SESSION["name"]?><span class="sr-only">(current)</span></a>
        </li>

        <?php endif; ?>


        <li class="nav-item active">

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


            <div class="form-group row">
          <div class="col-sm-12">
            <h1>Welcome to </h1>
          </div>
        </div>

        <div class="form-group row">
          <img class="welcome-image" src="skyepaw_logo.png" alt="skyepaw logo">
        </div>


        <div class="form-group row">
          <div class="col-12 order-2 order-lg-1 col-lg-6">
            <h1 class="accountheading">Make an Account </h1>
            <div id="makeerror"><?php echo $error; ?></div><br>
            <form action="register_confirmation.php" method="POST" id="make">
              <div class="form-group row">
                
              <div class="col-sm-2 email-heading">
              <label for="name" class="col-sm-3 col-form-label text-sm-right email-heading">First Name<span>*</span></label>
            </div>
              <div class="col-sm-10">
                <input type="text" class="form-control make" id="name" name="name">
              </div>
            </div> <!-- .form-group -->
              <div class="form-group row">

              <div class="col-sm-2 email-heading">
              <label for="email" class="col-sm-3 col-form-label text-sm-right email-heading">Email<span>*</span></label>
            </div>
              <div class="col-sm-10">
                <input type="email" class="form-control make" id="email" name="email">
              </div>
            </div> <!-- .form-group -->
            <div class="form-group row">
              <div class="col-sm-2 email-heading">
              <label for="password" class="col-sm-3 col-form-label text-sm-right email-heading">Password<span>*</span></label>
            </div>
              <div class="col-sm-10">
                <input type="password" class="form-control make" id="password" name="password">
              </div>
            </div> <!-- .form-group -->
            <button class="btn btn-warning my-2 my-sm-0 register-btn" type="submit">Register</button>
            </form>
          </div>

          <div class="col-12 order-1 order-lg-2 col-lg-6 login-col">
            <h1 class="accountheading">Log In</h1>
            <div id="loginerror"><?php echo $loginerror; ?></div><br>
            <form action="login.php" method="POST" id="login">
            <div class="form-group row">
              <div class="col-sm-2 email-heading">
              <label for="email-login" class="col-form-label text-sm-right email-heading">Email<span>*</span></label>
            </div>
              <div class="col-sm-10">
                <input type="email" class="form-control email login" id="email-login" name="email">
              </div>
            </div> <!-- .form-group -->
            <div class="form-group row">
              <div class="col-sm-2 email-heading">
              <label for="password-login" class="col-12 col-form-label text-sm-right email-heading">Password<span>*</span></label>
            </div>
              <div class="col-sm-10">
                <input type="password" class="form-control password login" id="password-login" name="password">
              </div>
            </div> <!-- .form-group -->

            <button class="btn btn-warning my-2 my-sm-0" type="submit">Log In</button>
          </form>
          </div>
        </div>
        </div>


    </div> <!-- container-fluid -->
  </div> <!-- bg color -->

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/49954d4722.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>

  
  <script>
    var numsteps = 1;
    $('.form-control-chosen').chosen();

    

    $("#make").on("submit", function() {
      if ($(".make").val().trim().length == 0)
      {
        $("#makeerror").html("Please fill out the required fields.");
        event.preventDefault();
      }
      if ($("#password").val().trim().length < 8)
      {
        $("#makeerror").html("Password must be at least 8 characters.");
        event.preventDefault();
      }
    });

    $("#login").on("submit", function() {
      if ($(".login").val().trim().length == 0)
      {
        $("#loginerror").html("Please fill out the required fields.");
        event.preventDefault();
      }
    });

  </script>


</body>
</html>