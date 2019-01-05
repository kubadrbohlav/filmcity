<?php
  // get page for view
  $page = isset($_GET['page']) ? $_GET['page'] : '';
?>

<div class="profile-links col-12 col-md-3">
  <h2>Uživatelské odkazy</h2>
  <nav aria-labelledby="user-links-navigation">
    <ul>
      <li><a <?php if($page == '') : ?>class="active"<?php endif; ?> href="<?php echo bloginfo('url'); ?>/profile" title="Můj účet"><span class="far fa-address-card"></span> Můj účet</a></li>
      <li><a <?php if($page == 'detail') : ?>class="active"<?php endif; ?> href="<?php echo bloginfo('url'); ?>/profile?page=detail" title="Osobní údaje"><span class="fas fa-user-edit"></span> Osobní údaje</a></li>
      <li><a <?php if($page == 'manage') : ?>class="active"<?php endif; ?> href="<?php echo bloginfo('url'); ?>/profile?page=manage" title="Moje filmy"><span class="fas fa-film"></span> Moje filmy</a></li>
      <li><a <?php if($page == 'style') : ?>class="active"<?php endif; ?> href="<?php echo bloginfo('url'); ?>/profile?page=style" title="Vzhled"><span class="fas fa-palette"></span> Vzhled</a></li>
      <li><a href="<?php echo bloginfo('url'); ?>/logout.php" title=""><span class="fas fa-sign-out-alt"></span> Odhlášení</a></li>
    </ul>
  </nav>
</div>
