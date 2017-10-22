<?php
$error = false;
$page_title = "Sign in";

if($_SERVER['REQUEST_METHOD'] == "POST"){
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
				$error = "Fel lösenord för användaren '".$_POST['username']."', försök igen.";
			}
		} else {
			$error = "Användare '".$_POST['username']."' hittades inte.";
		}
	} else {
		$error = "Du måste fylla i alla fält.";
	}
}

