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
  $dbuser = "id1125381_root";
  $dbpass = "rootpassword";
  $db = "id1125381_blog";
}

$conn = mysqli_connect( $dbhost, $dbuser, $dbpass, $db ) or die("Error connecting to mysql.");

?>
