<?php
// Created by: Christopher Gauffin
// Description: Index page where pages and scripts are inluded depending on fist $_GET param and disallowed users are redirected.

include("php/core.php");

$private = array("users");
$scripts = array("signout", "signin", "signup");

if( $page = key($_GET) ){

	if(in_array($page, $scripts)) {
		include("php/".$page.".php");
	}
	
	if(!isset($user)){
		if($page != "signin")
			redirect("signin");
	} else {
		if($page == "signin")
			redirect("rooms");

		if(in_array($page, $private) && !$priv)
			redirect("rooms");
	}
	
	$page_title = $page;
	$includes = array("header", "get_to_js", "auth");
	include("php/generate.php");
} else {
	redirect("signin");
}