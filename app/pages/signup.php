<!--
  Created by: Christopher Gauffin
  Description: Error handeling and redirection for signing up
!-->
<div class="container sign">
	<div class="card">
		<h4 class="card-header">
			<span>Registrera</span><br/><br/>
			<small>Har du redan ett konto? <a href="?signin">Logga in</a></small>
		</h4>
		<div class="card-body">
			<form id="login_form" method="POST" action="?signup">
				<p>Namn</p>
				<div class="input-group">
					<input name="name" type="text" class="form-control" placeholder="Skriv in ditt namn" maxlength="30">
				</div><br/>
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
				<button type="submit" class="btn btn-primary float-right">Registrera</button>
			</form>
		</div>
	</div>
</div>