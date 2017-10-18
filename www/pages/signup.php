<!--
  Created by: Christopher Gauffin
  Description: Error handeling and redirection for signing up
!-->

<?php

$error = false;

if( isset($_GET['username']) ){
	$error = "Användaren ".$_GET['username']." existerar redan, välj ett annat användarnamn.";
}

if( isset($_GET['fields']) ){
	$error = "Du måste fylla i alla fält";
}

$page_title = "Sign up";

?>
<div class="container sign">
	<div class="card">
		<h4 class="card-header">
			<span>Sign up</span><br/><br/>
			<small>Already have an account? <a href="?signin">Sign in</a></small>
		</h4>
		<div class="card-body">
			<form id="login_form" method="POST" action="php/signup.php">
				<p>Name</p>
				<div class="input-group">
					<input name="name" type="text" class="form-control" placeholder="Skriv in ditt namn" maxlength="30">
				</div><br/>
				<p>Username</p>
				<div class="input-group">
					<input name="username" type="text" class="form-control" placeholder="Skriv in ditt användarnamn" maxlength="30">
				</div><br/>
				<p>Password</p>
				<div class="input-group">
					<input name="password" type="password" class="form-control" placeholder="Skriv in ditt lösenord" maxlength="30">
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