<!--
  Created by: Christopher Gauffin
  Description: Header include for all main pages
!-->
<header id="header">
  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <a class="navbar-brand" href="#">Hotel Administration</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav mr-auto">
          <li data-page="home" class="nav-item active">
              <a href="?home" class="nav-link" href="#"><i class="fa fa-home"></i> Hotelrooms</a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="#"><i class="fa fa-users"></i> Users</a>
          </li>
          
          <?php if(isset($user)) { ?>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-user"></i> Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-sign-out"></i> Sign out</a>
            </li>
          <?php } ?>
      </ul>
      <?php if(isset($user)) { ?>
        <div class="profile_login bg-primary">
          <p>Inloggad som: <i><?= $user['username'] ?></i></p>
          <a href="?signoff" class="btn btn-danger">Logga ut</a>
        </div>
      <?php } ?>
      </div>
  </nav>
</header>

