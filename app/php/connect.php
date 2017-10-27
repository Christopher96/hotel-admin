<?php

ini_set("mysql.connect_timeout", 300);
ini_set("default_socket_timeout", 300);

$local = true;

if( $local ){
  $dbhost = "localhost";
  $dbuser = "root";
  $dbpass = "root";
  $db = "hotel";
} else {
  $dbhost = "localhost";
  $dbuser = "id726486_root";
  $dbpass = "rootpassword";
  $db = "id726486_hotel";
}

$conn = mysqli_connect( $dbhost, $dbuser, $dbpass, $db ) or die("Error connecting to mysql.");

?>
