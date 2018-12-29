<?php
  // get user identity
  global $userIdentity;
  if (isLoggedIn()) {
    $userIdentity = getUserById($_SESSION['userid']);
  }
  else {
    $userIdentity = false;
  }
?>

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="<?php echo bloginfo('url') ?>/favicon.ico" />

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="<?php echo bloginfo('url'); ?>/assets/css/bootstrap-reboot.min.css" media="all" />
<link rel="stylesheet" href="<?php echo bloginfo('url'); ?>/assets/css/bootstrap.min.css" media="all" />

<!-- FontAwesome CDN -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

<?php if (isLoggedIn()): ?>
  <?php if($userIdentity['styles'] == 'red'): ?>
    <link rel="stylesheet" href="<?php echo bloginfo('url'); ?>/assets/css/red.css" media="all" />
  <?php else: ?>
    <link rel="stylesheet" href="<?php echo bloginfo('url'); ?>/assets/css/main.css" media="all" />
  <?php endif; ?>
<?php else: ?>
  <link rel="stylesheet" href="<?php echo bloginfo('url'); ?>/assets/css/main.css" media="all" />
<?php endif; ?>

<link rel="stylesheet" href="<?php echo bloginfo('url'); ?>/assets/css/print.css" media="print" />
