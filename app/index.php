<!--
  Created by: Christopher Gauffin
  Description: This is the initial file for the site which handles the different includes depending on the first paramater
!-->

<?php
include("php/core.php");

$public_pages = array("home", "signin", "signup", "users", "post");

if( $page = key($_GET) ){

	if(!isset($user) && !in_array($page, $public_pages)){
		header('Location: ?home');
	}

	$includes = array();

	array_push($includes, "header", "get_to_js");

	include("php/generate.php");


} else {
	header('Location: ?home');
}

?>
