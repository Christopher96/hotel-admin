<!--
  Created by: Christopher Gauffin
  Description: Error handeling and redirection for signing in
!-->

<?php

$error = false;

if( isset($_GET['username']) ){
	if( isset($_GET['password'])){
		$error = "Fel lösenord för användaren '".$_GET['username']."', försök igen.";
	} else {
		$error = "User '".$_GET['username']."' was not found, <a href='?signup'>Sign up</a>";
	}

}

if( isset($_GET['fields']) ){
	$error = "Du måste fylla i alla fält.";
}

$page_title = "Sign in";

?>
<div class="container sign">
	<div class="card">
		<h4 class="card-header">
			<span>Sign in</span><br/><br/>
			<small>Need an account? <a href="?signup">Sign up</a></small>
		</h4>
		<div class="card-body">
			<form id="login_form" method="POST" action="php/signin.php">
				<p>Username</p>
				<div class="input-group">
					<input name="username" type="text" class="form-control" placeholder="Enter your username" maxlength="30">
				</div><br/>
				<p>Password</p>
				<div class="input-group">
						<input name="password" type="password" class="form-control" placeholder="Enter your password" maxlength="30">
				</div><br/>
				<?php if (!empty($error)) { ?>
				<div class="alert alert-danger" role="alert" >
						<i class="fa fa-exclamation-circle"></i>
						<span class="sr-only">Error:</span>
						<?= $error ?>
				</div>
				<?php } ?>
				<button type="submit" class="btn btn-primary float-right">Sign in</button>
			</form>
		</div>
	</div>
</div>

