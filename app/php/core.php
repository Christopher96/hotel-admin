<?php
session_start();
require_once("connect.php");

function updateUserSession($user_id, $session_id){
    global $conn;
    $result = mysqli_query($conn, "UPDATE users SET last_session='{$session_id}' WHERE id='{$user_id}'") or die(mysql_error());
}

function getUserSession($user_id) {
	global $conn;
    $query = "SELECT id, name, username, role FROM users WHERE id=".$user_id." LIMIT 1";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

function redirect($page, $params = null) {
	$url = "Location: ?".$page;
	if(isset($params)) $url .= "&".$params;
	session_write_close();
	header($url);
	exit;
}

$priv = false;
$user = null;

if (isset($_SESSION['user_id'])) {
	$user = getUserSession($_SESSION['user_id']);
	if($user['role'] > 0) $priv = true;
}
