<?php
  require_once(__DIR__.'/core/core.php');
  session_start();

  if ( isset($_SESSION['userid']) ) {
    // redirect
    $url  = bloginfo('url');
    $extra = 'profile';
    header("Location: $url/$extra");
    exit();
  }

  $errors       = false;

  // Prihlaseni

  $email_e  = false;
  $passwd_e = false;

  $email  = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
  $passwd = isset($_POST['passwd']) ? htmlspecialchars($_POST['passwd']) : '';

  if ( isset($_POST['login_submit']) ) {

    if( isset($_POST['register_submit']) ) {
      unset($_POST['register_submit']);
    }

    if ( $email == '' ) {
      $email_e = true;
      $errors = true;
    }

    if ( $passwd == '' ) {
      $passwd_e = true;
      $errors = true;
    }

    if (!$errors) {
      $user = getUserByEmail($email);

      if ($user) {
        if (password_verify($passwd, $user['passwd'])) {
          $_SESSION['userid'] = $user['id'];
          $url  = bloginfo('url');
          $extra = 'profile';
          header("Location: $url/$extra");
          exit();
        }
        else {
          $loginError = 'Chyba přihlášení.';
        }
      }
      else {
        $loginError = 'Chyba přihlášení.';
      }
    }
  }

?>
<!DOCTYPE html>
<html lang="cs">

  <head>
    <title>Přihlášení | <?php echo bloginfo('name'); ?></title>
    <?php include_head_assets(); ?>
  </head>

  <body class="blog-login-page blog-page">
    <?php include_header() ?>
    <div class="container">
      <div class="row heading">
        <div class="col-12">
          <h1>Přihlášení</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <form id="login-form" name="login-form" action="<?php echo bloginfo('url'); ?>/login.php" method="post">
            <div class="form-element">
              <label for="email">Email: <span>*</span></label>
              <input type="email" id="email" name="email" value="<?php echo $email; ?>" <?php if($email_e): ?>class="error"<?php endif; ?> required />
              <?php if($email_e): ?><span class="error">Toto pole je povinné.</span><?php endif; ?>
            </div>
            <div class="form-element">
              <label for="passwd">Heslo: <span>*</span></label>
              <input type="password" id="passwd" name="passwd" value="" <?php if($passwd_e): ?>class="error"<?php endif; ?> required />
              <?php if($passwd_e): ?><span class="error">Toto pole je povinné.</span><?php endif; ?>
            </div>
            <div class="form-element">
              <input type="submit" name="login_submit" value="Přihlásit se" />
            </div>
            <div class="form-element required-fields"><span>Pole označená * jsou povinné.</span></div>
          </form>
          <?php if(isset($loginError)): ?>
            <div class="error">
              <?php echo $loginError; ?>
            </div>
          <?php endif; ?>
          <p>Ještě nemáte svůj účet? <a href="<?php echo bloginfo('url'); ?>/register.php" title="Registrujte se">Registrujte se</a>.</p>
        </div>
      </div>
    </div>

    <script src="<?php echo bloginfo('url') ?>/assets/js/validate-login.js"></script>

  <?php include_footer() ?>
