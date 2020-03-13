<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>

<head>
  <title>ProjectName</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap CSS CDN -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

  <!-- Table -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="./dist/jquery.tabledit.min.js"></script>

  <!-- Custom stylesheet -->
  <link rel="stylesheet" href="./styles/layout.css">
  <link rel="stylesheet" href="./styles/slidebar.css">
  <link rel="stylesheet" href="./styles/btn_colors.css">
  <link rel="stylesheet" href="./styles/reload.css">

  <!-- Font Awesome JS -->
  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>

  <!-- FONT -->
  <link href="https://fonts.googleapis.com/css?family=Nunito&display=swap" rel="stylesheet">
</head>


<body style="font-family: 'Nunito';">
  <div id="loading" class="loader" style="display:none"></div>
  <div class="wrapper">
    <!-- Sidebar  -->
    <?php include 'templates/slidebar.html'; ?>

    <!-- Page Content  -->
    <div class=container id="content">
      <!-- <?php include 'templates/header.php'; ?> -->
      <?php
      $current_page = isset($_GET['page']) ? $_GET['page'] : null;

      switch ($current_page) {
        case ('dimension_one'):
          include 'templates/dimension_one.php';
          break;
        default:
          include 'templates/overview.php';
      }
      ?>
    </div>
  </div>

  <!-- jQuery CDN - Slim version (=without AJAX) -->
  <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
  <!-- Popper.JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
  <!-- Bootstrap JS -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

  <script type="text/javascript">
    $(document).ready(function() {

      $('#sidebarCollapse').on('click', function() {
        $('#sidebar').toggleClass('active');
        $('#sidebarIcon').toggleClass('fa-caret-square-left fa-caret-square-right')

      });
    });
  </script>
</body>

</html>