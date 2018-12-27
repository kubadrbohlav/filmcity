<?php
  if (isLoggedIn()) {
    $userId = $_SESSION['userid'];
  }
  else {
    $userId = false;
  }
  if (!$userId) {
    // redirect
    $url  = bloginfo('url');
    $extra = 'profile';
    header("Location: $url/$extra");
    exit();
  }

  $user = getUserById($userId);

  $style = isset( $_POST['styles'] ) ? htmlspecialchars($_POST['styles']) : htmlspecialchars($user['styles']);

  if ( isset($_POST['set_styles']) ) {
    if ($style != 'default' && $style != 'red') {
      $style = 'default';
    }

    if( updateStyles($style, $user['id']) ) {
      // redirect
      $url  = bloginfo('url');
      $extra = 'profile?msg=styles_updated';
      header("Location: $url/$extra");
      exit();
    }
    else {
      $error = 'Chyba databáze.';
    }

  }
?>
<!DOCTYPE html>
<html lang="cs">

  <head>
    <title>Vzhled | <?php echo bloginfo('name'); ?></title>
    <?php include_head_assets(); ?>
    <?php global $userIdentity; ?>
  </head>

  <body class="profile-page delete-page blog-page">
    <?php include_header() ?>
    <div class="container">
      <?php if( isLoggedIn() ) : ?>
      <div class="row heading">
        <div class="col-12">
          <h1>Vzhled</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-md-9">
          <form method="post" action="<?php echo bloginfo('url') . '/profile/?page=style'; ?>">
            <div class="form-element">
              <label for="style1">Modrá (výchozí)</label>
              <input type="radio" id="style1" name="styles" value="default" <?php if($style == 'default'): ?>checked<?php endif; ?> required />
              <label for="style2">Červená</label>
              <input type="radio" id="style2" name="styles" value="red" <?php if($style == 'red'): ?>checked<?php endif; ?> />
            </div>
            <div class="form-element">
              <input type="submit" name="set_styles" value="Použít" />
              <?php if(isset($error)) : ?><span class="error"><?php echo $error ?></span><?php endif; ?>
            </div>
          </form>
        </div>

        <?php include(bloginfo('path') . '/core/templates/_user-menu.php'); ?>

      </div>
    <?php else : ?>

      <?php include(bloginfo('path') . '/core/templates/_permission-denied.php'); ?>

    <?php endif; ?>

    </div>

  <?php include_footer() ?>
