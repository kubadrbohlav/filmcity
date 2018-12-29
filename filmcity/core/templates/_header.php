<?php
  global $userIdentity;
?>
<header class="no-js">
  <div class="container cf">
    <a href="<?php echo bloginfo('url'); ?>" title="<?php echo bloginfo('name'); ?>"><img class="logo" src="<?php echo bloginfo('url'); ?>/assets/images/filmcity-logo.svg" alt="FilmCity logo" /></a>
    <nav id="main-menu">
      <ul class="cf">
        <li><a href="<?php echo bloginfo('url'); ?>" title="Úvod">Úvod</a></li>
        <li>
          <a href="<?php echo bloginfo('url'); ?>/category.php" title="Filmy">Filmy</a>
          <ul>
            <?php
              $categories = getCategories();
              foreach($categories as $category) {
                echo '<li><a href="'.bloginfo('url').'/category.php?category='.$category['id'].'" title="'.$category['name'].'">'.$category['name'].'</a></li>';
              }
            ?>
          </ul>
        </li>
      </ul>
    </nav>
    <?php if( isLoggedIn() ): ?>
      <div id="user-links">
        <ul>
          <li>
            <a href="<?php echo bloginfo('url') ?>/profile" title="<?php echo $userIdentity['name'] . ' ' . $userIdentity['surname'] ?>"><span class="fas fa-user"></span><?php echo $userIdentity['name'] . ' ' . $userIdentity['surname'] ?></a>
            <ul>
              <li><a href="<?php echo bloginfo('url'); ?>/profile" title="Můj účet">Můj účet</a></li>
              <li><a href="<?php echo bloginfo('url'); ?>/logout.php" title="Odhlásit se">Odhlásit se</a></li>
            </ul>
          </li>
        </ul>
      </div>
    <?php else: ?>
      <div id="user-links">
        <a class="login-or-signup" href="<?php echo bloginfo('url'); ?>/login.php" title="Přihlásit se"><span class="fas fa-sign-in-alt"></span> Přihlášení</a>
      </div>
    <?php endif; ?>
    <div id="mobile-header" class="no-js">
      <div id="toggleMenu" class="no-js"><span class="fas fa-bars"></span></div>
      <nav id="mobile-menu" class="no-js">
        <ul>
          <li><a href="<?php echo bloginfo('url'); ?>" title="Úvod">Úvod</a></li>
          <li><a href="<?php echo bloginfo('url'); ?>/category.php" title="Filmy">Filmy</a></li>
          <?php if( isLoggedIn() ): ?>
            <li><a href="<?php echo bloginfo('url'); ?>/profile" title="Můj účet">Můj účet</a></li>
            <li><a href="<?php echo bloginfo('url'); ?>/logout.php" title="Odhlásit se">Odhlásit se</a></li>
          <?php else: ?>
            <li><a href="<?php echo bloginfo('url'); ?>/login.php" title="Přihlásit">Přihlásit</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </div>
</header>
