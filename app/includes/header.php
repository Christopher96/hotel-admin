<!--
  Created by: Christopher Gauffin
  Description: Header include for all main pages
!-->
<header id="header">
  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <a class="navbar-brand" href="#">Hotell Admin</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul id="menu" class="navbar-nav mr-auto">
        <?php if(isset($user)) { ?>
            <li class="nav-item active">
                <a class="nav-link" href="?home"><i class="fa fa-home"></i> Rum</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?users"><i class="fa fa-users"></i> Användare</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?signout"><i class="fa fa-sign-out"></i> Logga ut</a>
            </li>
        <?php } else { ?>
            <li class="nav-item">
                <a class="nav-link" href="?signin"><i class="fa fa-sign-in"></i> Logga in</a>
            </li>
        <?php } ?>
      </ul>
      </div>
  </nav>
</header>

