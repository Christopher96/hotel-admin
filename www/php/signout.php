<!--
  Created by: Christopher Gauffin
  Description: Session destroy for logging out a user
!-->

<?php

session_start();
session_destroy();

header('Location: index.php?signin');
