<?php
  // inlude functions and start session
  require_once(__DIR__.'/core/core.php');
  session_start();

  // if user is already logged in, redirect to profile page
  if ( isset($_SESSION['userid']) ) {
    // redirect
    $url  = bloginfo('url');
    $extra = 'profile';
    header("Location: $url/$extra");
    exit();
  }

  // Registrace

  // errors
  $errors       = false;
  $forename_e   = false;
  $surename_e   = false;
  $emailReg_e   = 0;
  $passwdReg_e  = false;
  $passwdAReg_e = false;
  $duplicatedEmail = false;
  $otherError = false;

  // get input data
  $forename   = isset($_POST['forename']) ? htmlspecialchars($_POST['forename']) : '';
  $surename   = isset($_POST['surename']) ? htmlspecialchars($_POST['surename']) : '';
  $emailReg   = isset($_POST['emailSignup']) ? htmlspecialchars($_POST['emailSignup']) : '';
  $passwdReg  = isset($_POST['passwdSignup']) ? htmlspecialchars($_POST['passwdSignup']) : '';
  $passwdAReg = isset($_POST['passwdAgain']) ? htmlspecialchars($_POST['passwdAgain']) : '';


  // if register form is submitted
  if( isset($_POST['register_submit']) ) {

    // if name is empty
    if ( $forename == '' ) {
      $forename_e = true;
      $errors = true;
    }

    // if surname is empty
    if ( $surename == '' ) {
      $surename_e = true;
      $errors = true;
    }

    // if email is empty
    if ( $emailReg == '' ) {
      $emailReg_e = 1;
      $errors = true;
    }
    // if email does not match pattern
    else if ( !preg_match('/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/', $emailReg) ) {
      $emailReg_e = 2;
      $errors = 1;
    }
    else { /* code... */ }

    // if password does not match pattern
    if ( !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/', $passwdReg) ) {
      $passwdReg_e = true;
      $errors = true;
    }
    else {
      // if passwords does not match
      if ( $passwdReg != $passwdAReg ) {
        $passwdAReg_e = true;
        $errors = true;
      }
    }

    // if no input errors
    if (!$errors) {
      // insert new user into database
      $newUser = registerUser($forename, $surename, $emailReg, $passwdReg);
      switch ($newUser) {
        // if user successfully inserted
        case 1:
          // get user
          $user = getUserByEmail($emailReg);

          // if got user, set new SESSION and redirect to profile page
          if ($user) {
            $_SESSION['userid'] = $user['id'];
            $url  = bloginfo('url');
            $extra = 'profile';
            header("Location: $url/$extra");
            exit();
          }
          else {
            $otherError = true;
          }
          break;

        // if email already exists in database
        case -1:
          $duplicatedEmail = true;
          break;

        // if other error during db conection occured
        case -2:
          $otherError = true;
          break;
        default:
          $otherError = true;
          break;
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

  <body class="blog-register-page blog-page">
    <?php include_header() ?>

    <div class="container">
      <div class="row heading">
        <div class="col-12">
          <h1>Registrace</h1>
        </div>
      </div>

      <!-- Register form -->
      <div class="row">
        <div class="col-12">
            <form id="signup-form" name="signup-form" action="<?php echo bloginfo('url'); ?>/register.php" method="post">
              <div class="form-element">
                <label for="forename">Jméno: <span>*</span></label>
                <input type="text" id="forename" name="forename" value="<?php echo $forename; ?>" <?php if($forename_e): ?>class="error"<?php endif; ?> required />
                <?php if($forename_e): ?><span class="error">Toto pole je povinné.</span><?php endif; ?>
              </div>
              <div class="form-element">
                <label for="surename">Přijmení: <span>*</span></label>
                <input type="text" id="surename" name="surename" value="<?php echo $surename; ?>" <?php if($surename_e): ?>class="error"<?php endif; ?> required />
                <?php if($surename_e): ?><span class="error">Toto pole je povinné.</span><?php endif; ?>
              </div>
              <div class="form-element">
                <label for="emailSignup">Email: <span>*</span></label>
                <input type="email" id="emailSignup" name="emailSignup" value="<?php echo $emailReg; ?>" <?php if($emailReg_e != 0 || $duplicatedEmail): ?>class="error"<?php endif; ?> pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" required />
                <?php if($emailReg_e == 1): ?><span class="error">Toto pole je povinné.</span><?php endif; ?>
                <?php if($emailReg_e == 2): ?><span class="error">Zadejte platný email.</span><?php endif; ?>
                <?php if($duplicatedEmail): ?><span class="error">Uživatel s tímto emailem již existuje.</span><?php endif; ?>
              </div>
              <div class="form-element">
                <label for="passwdSignup">Heslo: <span>*</span></label>
                <input type="password" id="passwdSignup" name="passwdSignup" value="" <?php if($passwdReg_e): ?>class="error"<?php endif; ?> />
                <?php if($passwdReg_e): ?>
                <div class="error">
                  <span>Heslo musí obsahovat:</span>
                  <ul>
                    <li>Alespoň 6 znaků</li>
                    <li>Alespoň jeden znak [a-z]</li>
                    <li>Alespoň jeden znak [A-Z]</li>
                    <li>Alespoň jeden znak [0-9]</li>
                  </ul>
                </div>
                <?php endif; ?>
              </div>
              <div class="form-element">
                <label for="passwdAgain">Heslo znovu: <span>*</span></label>
                <input type="password" id="passwdAgain" name="passwdAgain" value="" <?php if($passwdAReg_e): ?>class="error"<?php endif; ?> />
                <?php if($passwdAReg_e): ?><span class="error">Hesla se neshodují.</span><?php endif; ?>
              </div>
              <div class="form-element">
                <input type="submit" name="register_submit" value="Registrovat se" />
                <?php if($otherError): ?><span class="error">Při odesílání došlo k chybě. Zkuste to prosím za chvíli.</span><?php endif; ?>
              </div>
              <div class="form-element required-fields"><span>Pole označená * jsou povinné.</span></div>
            </form>
        </div>
      </div>
    </div>

    <!-- Form validation -->
    <script src="<?php echo bloginfo('url') ?>/assets/js/validate-register.js"></script>

  <?php include_footer() ?>
