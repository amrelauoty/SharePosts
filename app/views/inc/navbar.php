<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 sticky-top">
  <div class="container">
    <a class="navbar-brand" href="<?php echo URLROOT;?>"><?php echo SITENAME;?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a class="nav-link" href="<?php echo URLROOT;?>">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo URLROOT;?>/pages/about">About</a>
            </li>
          </ul>
          <ul class="navbar-nav ml-auto">

          <?php if(isset($_COOKIE['login']) || isset($_SESSION['user_name'])) : ?>
            <li class="nav-item">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php
                if(isset($_SESSION['user_name']))
                {
                  echo 'Welcome, '. $_SESSION['user_name'];
                } 
                else
                {
                  echo 'Welcome'. $_COOKIE['login'];
                }
                
                    
                ?>
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="<?php echo URLROOT;?>/users/logout">Logout</a>
              </div>
          <?php else : ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo URLROOT;?>/users/register">Register</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo URLROOT;?>/users/login">Login</a>
            </li>
          </ul>
        </div>
         <?php endif; ?>

  </div>
      
</nav>