<?php
  // inlude functions and start session
  require_once(__DIR__.'/core/core.php');
  session_start();

?>
<!DOCTYPE html>
<html lang="cs">

  <head>
    <title>404 Stránka neexistuje</title>
    <?php include_head_assets(); ?>
  </head>

  <body class="logout-page">
    <?php include_header() ?>
    <div class="msg-wrapper">
      <div>
        <h1>Stránka neexistuje.</h1>
        <a href="<?php echo bloginfo('url'); ?>" title="Hlavní stránka">Přejít na hlavní stránku</a>
      </div>
    </div>
  </body>
</html>
