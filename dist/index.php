<?php
include("php/core.php");

$scripts = array("signout", "signin", "signup");
$public_pages = array("home", "signout", "signin", "signup", "users", "post");

if( $page = key($_GET) ){

	if(in_array($page, $scripts)) {
		include("php/".$page.".php");
	}
	
	if(!isset($user) && !in_array($page, $public_pages)){
		redirect("home");
	}
	
	$includes = array("header", "get_to_js", "auth");
	include("php/generate.php");
} else {
	redirect("home");
}