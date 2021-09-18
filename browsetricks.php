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
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="navigation.css">
  <style>
    .search-tricks {
      margin: 20px;
    }

    .top-col {
      margin-top: 15px;
    }

    form > * {
      margin: 15px;
    }

    .pics {
      text-align: center;
      margin-top: 20px;
    }

    .image {
      max-height:100%;
      max-width:100%;
      height:auto;
      width:auto;
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
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">

          <form class="form-inline my-2 my-lg-0" action="" method="">
            <input class="form-control mr-sm-2 search-outline-warning search-tricks" type="search" placeholder="Search Tricks" aria-label="Search" id="search">
            <select name="label_id" id="level" class="form-control">
              <option value="All Levels">All Levels</option>
              <option value="Beginner">Beginner</option>
              <option value="Intermediate">Intermediate</option>
              <option value="Advanced">Advanced</option>

            </select>

            <button class="btn btn-warning my-2 my-sm-0" type="submit">Search</button>
          </form>
        </div>


      </div>

      <div class="row" id="search-results">

        <div class="col-6 col-sm-4 col-md-3 pics">
          <a href="add_trick.php">
            <img src="newtrick.png" class="image" alt="newtrick"></a>
            Add Your Own Trick
          </div>


        <?php while( $row = $results_tricks->fetch_assoc() ): ?>
          <div class="col-6 col-sm-4 col-md-3 pics">
            <a href="trick_details.php?trick_id=<?php echo $row['id']; ?>">
            <img src="<?php echo (substr($row['image'], 0, 13) . $row['id'] . substr($row['image'], 13))?>" class="image" alt="<?php echo $row['name']; ?>"></a>
            <?php echo $row['name']; ?>
          </div>

        <?php endwhile; ?>

        </div>

      </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/49954d4722.js" crossorigin="anonymous"></script>

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

    document.querySelector('form').onsubmit = function() {
      var searchTerm = document.querySelector('#search').value.trim();
      var level = document.querySelector('#level').value.trim();
      ajaxGet('search.php?term=' + searchTerm + '&level=' + level, function(results) {

        results = JSON.parse(results);

        // 29. Grab the list element
        var resultsList = document.querySelector('#search-results');

        // 30. Don't forget to clear all the previous elements upon every search. Now you can keep searching without leaving the page.
        while( resultsList.hasChildNodes()) {

          resultsList.removeChild(resultsList.lastChild);
        } 

        resultsList.innerHTML += '<div class="col-6 col-sm-4 col-md-3 pics"><a href="add_trick.php"><img src="newtrick.png" class="image" alt="newtrick"></a>Add Your Own Trick</div>';
        
        // 31. Run through the results and append them to resultsList.
        for( var i = 0; i < results.length; i++) {
          resultsList.innerHTML += '<div class="col-6 col-sm-4 col-md-3 pics"><a href="trick_details.php?trick_id=' + results[i].id + '"><img src="' + results[i].image.substring(0, 13) + results[i].id + results[i].image.substring(13) + '" class="image" alt="image"></a>' + results[i].name + '</div>'
        }
      })
      
      event.preventDefault();
      // return false;
    }





    </script>


  </body>
  </html>