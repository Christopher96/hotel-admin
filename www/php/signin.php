<?php

require_once("core.php");

if( isset($_SESSION['user_id']) ){
	redirect("profile");
} else {
	
	if( !empty($_POST['username']) && !empty($_POST['password']) ){
		$username = mysqli_real_escape_string($conn, $_POST['username']);
		$password = mysqli_real_escape_string($conn, $_POST['password']);

		$query = "SELECT * FROM users WHERE username='".$username."'";
		$result = mysqli_query($conn, $query);

		if( mysqli_num_rows($result) == 1 ){

			$user = mysqli_fetch_assoc($result);

			if( password_verify($password, $user['pw_hash']) ){
				$_SESSION['user_id'] = $user['id'];

				updateUserSession($user['id'], session_id());

				redirect("profile");
			} else {
				redirect("signin", "password=true&username=".$username);
			}
		} else {
			redirect("signin", "username=".$username);
		}
	} else {
		redirect("signin", "fields=true");
	}
}

