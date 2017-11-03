<!--
  Created by: Christopher Gauffin
  Description: Error handeling and redirection for signing in
!-->
<div class="container sign">

	<div class="card">
		<div id="logo">
			<img src="images/logo.png" alt="main logo">
		</div>
		<div class="card-header">
			
			<span>Logga in</span>
		</div>
		<div class="card-body">
			<form id="login_form" method="POST" action="?signin">
				<p>Användarnamn</p>
				<div class="input-group">
					<input name="username" type="text" class="form-control" placeholder="Skriv in ditt användarnamn" maxlength="30">
				</div><br/>
				<p>Lösenord</p>
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
				<button type="submit" class="btn btn-primary float-right">Logga in</button>
			</form>
		</div>
	</div>
</div>


