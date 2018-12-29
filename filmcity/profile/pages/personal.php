<?php
  // start session
  session_start();

  // get user ID
  if (isLoggedIn()) {
    $userId = $_SESSION['userid'];
  }
  else {
    $userId = false;
  }

  // if user is not logged in, redirect to profile page
  if (!$userId) {
    // redirect
    $url  = bloginfo('url');
    $extra = 'profile';
    header("Location: $url/$extra");
    exit();
  }

  // get user
  $user = getUserById($userId);

  // get inputs
  $name    = isset( $_POST['name'] ) ? htmlspecialchars($_POST['name']) : htmlspecialchars($user['name']);
  $surname = isset( $_POST['surname'] ) ? htmlspecialchars($_POST['surname']) : htmlspecialchars($user['surname']);


  // errors
  $errors = false;
  $name_e = false;
  $surname_e = false;

  // if form is submitted
  if ( isset($_POST['update_user']) ) {

    // if name is empty
    if ($name == '') {
      $errors = true;
      $name_e = true;
    }

    // if surname is empty
    if ($surname == '') {
      $errors = true;
      $surname_e = true;
    }

    // if no input errors
    if(!$errors ) {

      // update user data
      if ( updateUser($user['id'], $name, $surname) ) {

        // if success, redirect to profile page
        $url  = bloginfo('url');
        $extra = 'profile?msg=detail_updated';
        header("Location: $url/$extra");
        exit();
      }
      else {
        $error = 'Chyba databáze.';
      }
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
          <h1>Osobní údaje</h1>
        </div>
      </div>

      <!-- Update form -->
      <div class="row">
        <div class="col-12 col-md-9">
          <form id="update-user-form" method="post" action="<?php echo bloginfo('url') . '/profile/?page=detail'; ?>">
            <div class="form-element">
              <label for="name">Jméno <span>*</span></label>
              <input type="text" id="name" name="name" value="<?php echo $name; ?>" <?php if($name_e): ?>class="error"<?php endif; ?> required />
              <?php if($name_e): ?><span class="error">Toto pole je povinné.</span><?php endif; ?>
            </div>
            <div class="form-element">
              <label for="surname">Přijmení <span>*</span></label>
              <input type="text" id="surname" name="surname" value="<?php echo $surname; ?>" <?php if($surname_e): ?>class="error"<?php endif; ?> required />
              <?php if($surname_e): ?><span class="error">Toto pole je povinné.</span><?php endif; ?>
            </div>
            <div class="form-element">
              <input type="submit" name="update_user" value="Upravit" />
              <?php if(isset($error)) : ?><span class="error"><?php echo $error ?></span><?php endif; ?>
            </div>
            <div class="form-element required-fields"><span>Pole označená * jsou povinné.</span></div>
          </form>
        </div>

        <!-- User menu -->
        <?php include(bloginfo('path') . '/core/templates/_user-menu.php'); ?>
      </div>

      <!-- Form validation -->
      <script src="<?php echo bloginfo('url').'/assets/js/validate-updateuser.js' ?>"></script>
      
    <?php else : ?>

      <?php include(bloginfo('path') . '/core/templates/_permission-denied.php'); ?>

    <?php endif; ?>

    </div>

  <?php include_footer() ?>
