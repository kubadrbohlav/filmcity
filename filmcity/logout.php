<?php
  // inlude functions and start session
  require_once(__DIR__.'/core/core.php');

  // destroy current session
  session_start();
  session_unset();
  session_destroy();

  // redirect to homepage
  $url  = bloginfo('url');
  header("Location: $url");
  exit();
?>
<!DOCTYPE html>
<html lang="cs">

  <head>
    <title>Odhlášení | <?php echo bloginfo('name'); ?></title>
    <?php include_head_assets(); ?>
  </head>

  <body class="logout-page">
    <?php include_header() ?>
    <div class="msg-wrapper">
      <div>
        <h3>Odhlašuji...</h3>
      </div>
    </div>
  </body>
</html>
