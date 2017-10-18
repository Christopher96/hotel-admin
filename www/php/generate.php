<!--
  Created by: Christopher Gauffin
  Description: This file will generate a a complete HTML page of all the styles, scripts and includes passed to it.
!-->

<?php

$title = "Hotel";

?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="msapplication-tap-highlight" content="no" />
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width" />
    <!-- This is a wide open CSP declaration. To lock this down for production, see below. -->
    <meta http-equiv="Content-Security-Policy" content="style-src 'self' http://* 'unsafe-inline';" />
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="css/main.css" />
    <?php
      $delimiter = " - ";
    ?>
    <title><?= $title ?><?= !empty($page_title) ? $delimiter.$page_title : ""; ?></title>
  </head>
  <body>
    <?php
      if(!empty($includes)){
        foreach ($includes as $include) {
          include("includes/".$include.".php");
        }
      }
	    include("pages/".$page.".php");
    ?>
    <script>
      var active = '<?= $page ?>';
      var auth = {
        user_id: "<?= $_SESSION['user_id'] ?>",
        session_id: "<?= session_id() ?>",
      };
    </script>
  </body>


  <script src="scripts/vendor.js"></script>

  <script type="text/javascript" src="cordova.js"></script>
  <script type="text/javascript" src="js/main.js"></script>

  <!-- <script type="text/javascript" src="http://192.168.0.11:8080/target/target-script-min.js#anonymous"></script> -->

</html>
