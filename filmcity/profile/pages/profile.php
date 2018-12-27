<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="cs">

  <head>
    <title>Profil | <?php echo bloginfo('name'); ?></title>
    <?php include_head_assets(); ?>
    <?php global $userIdentity; ?>
  </head>

  <body class="profile-page blog-page">
    <?php include_header() ?>
    <div class="container">
      <?php if( isLoggedIn() ) : ?>
      <div class="row heading">
        <div class="col-12">
          <h1>Můj účet</h1>
        </div>
      </div>
      <div class="row">
        <div class="overview col-12 col-md-9">
          <section id="user-info">
            <h2>Uživatel</h2>
            <div class="info-table">
              <div id="portrait">
                <img src="<?php echo bloginfo('url') . '/assets/images/no-profile.png' ?>" alt="<?php echo $userIdentity['name'] . ' ' . $userIdentity['surname'] ?> foto" />
              </div>
              <div>
                <dl>
                  <dt>Jméno</dt>
                  <dd><?php echo $userIdentity['name'] . ' ' . $userIdentity['surname'] ?></dd>
                  <dt>Email</dt>
                  <dd class="email"><?php echo $userIdentity['email'] ?></dd>
                </dl>
              </div>
            </div>
          </section>
        </div>

        <?php include(bloginfo('path') . '/core/templates/_user-menu.php'); ?>

      </div>
    <?php else : ?>

      <?php include(bloginfo('path') . '/core/templates/_permission-denied.php'); ?>

    <?php endif; ?>

    </div>

  <?php include_footer() ?>
