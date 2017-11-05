<?php
// Created by: Christopher Gauffin
// Description: Index page where pages and scripts are inluded depending on fist $_GET param and disallowed users are redirected.

include("php/core.php");

$scripts = array("signout", "signin", "signup");

if( $page = key($_GET) ){

	if(in_array($page, $scripts)) {
		include("php/".$page.".php");
	}
	
	if(!isset($user) && $page != "signin"){
		redirect("signin");
	}
	
	$page_title = $page;
	$includes = array("header", "get_to_js", "auth");
	include("php/generate.php");
} else {
	redirect("signin");
}