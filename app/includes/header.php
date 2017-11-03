<!--
  Created by: Christopher Gauffin
  Description: Header include for all main pages
!-->
<header id="header">
  <nav class="navbar justify-content-center navbar-expand-md navbar-dark fixed-top bg-dark">
      <div class="container <?= ($page == 'signin') ? 'sign': '' ?>">
        <div class="navbar-header">
            <a class="navbar-brand" href="?rooms">
                <span>Hotel Admin</span>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul id="menu" class="navbar-nav mr-auto">
            <?php if(isset($user)) { ?>
                <li class="nav-item active">
                    <a class="nav-link" href="?rooms"><i class="fa fa-home"></i> Rum</a>
                </li>
                <?php if($priv) { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="?users"><i class="fa fa-users"></i> Användare</a>
                    </li>
                <?php } ?>               
            <?php } else { ?>
                <li class="nav-item">
                    <a class="nav-link" href="?signin"><i class="fa fa-sign-in"></i> Logga in</a>
                </li>
            <?php } ?>
        </ul>
        <ul class="log-nav navbar-nav">
            <?php if(isset($user)) { ?>
                <li class="nav-item">
                    <span class="nav-link">Inloggad som <?= $user['name'] ?></span>
                </li> 
                <li class="nav-item">
                    <a class="nav-link" href="?signout"><i class="fa fa-sign-in"></i> Logga ut</a>
                </li>             
            <?php } ?>
        </ul>
        </div>
      </div>
  </nav>
</header>

