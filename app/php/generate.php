<?php
$title = "Hotell Admin";
$delimiter = " - ";
?>
<!--
  Created by: Christopher Gauffin
  Description: This file will generate a a complete HTML page of all the styles, scripts and includes passed to it.
!-->

<!DOCTYPE html>
<html>
  <head>
    <meta name="msapplication-tap-highlight" content="no" />
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width" />
    <!-- This is a wide open CSP declaration. To lock this down for production, see below. -->
    <meta http-equiv="Content-Security-Policy" content="style-src 'self' http://* 'unsafe-inline';" />
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="css/main.css" />
    <title><?= $title ?><?= !empty($page_title) ? $delimiter.$page_title : ""; ?></title>
  </head>
  <body>
    <?php
      if(!empty($includes)){
        foreach ($includes as $include) {
          include("includes/".$include.".php");
        }
      }
    ?>
    <div id="content">
      <?php include("pages/".$page.".php"); ?>
    </div>
  </body>

 
  <!-- build:js js/vendor.js -->
  <!-- bower:js -->
  <!-- endbower -->
  <script src="../node_modules/jquery/dist/jquery.js"></script>
  <script src="../node_modules/popper.js/dist/umd/popper.js"></script>
  <script src="../node_modules/lightbox2/dist/js/lightbox.min.js"></script>
  <script src="../node_modules/bootstrap/dist/js/bootstrap.js"></script>
  <!-- endbuild -->

  <!-- build:js js/main.js -->
  <script src="js/main.js"></script>
    <!-- endbuild -->

</html>
