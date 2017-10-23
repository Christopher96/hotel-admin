<?php
include("php/core.php");

$scripts = array("signout", "signin", "signup");

if( $page = key($_GET) ){

	if(in_array($page, $scripts)) {
		include("php/".$page.".php");
	}
	
	if(!isset($user) && $page != "signin"){
		redirect("signin");
	}
	
	$includes = array("header", "get_to_js", "auth");
	include("php/generate.php");
} else {
	redirect("signin");
}