<?php

$user = null;

function updateUserSession($user_id, $session_id){
    global $conn;
    $result = mysqli_query($conn, "UPDATE users SET last_session='{$session_id}' WHERE id='{$user_id}'") or die(mysql_error());
}

function getUserSession($session_id) {
	global $user;
    $query = "SELECT name, username FROM users WHERE id=".$_SESSION['user_id']." LIMIT 1";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
}

function redirect($page, $params = null) {
	if (session_status() == PHP_SESSION_ACTIVE) {
		session_destroy();
	}
	$url = "Location: /?".$page."&";
	if(isset($params)) $url .= $params;
	header($url);
}

if (session_status() != PHP_SESSION_ACTIVE) {
	session_start();
	getUserSession($_COOKIE["PHPSESSID"]);
}

require_once("connect.php");
