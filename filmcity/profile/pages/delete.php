<?php
  $postId = isset($_GET['post_id']) ? $_GET['post_id'] : '';

  if ( isset($_POST['delete_post']) ) {
    $post = getPostById($postId);
    if ($post['image']) {
      unlink(bloginfo('path').$post['image']);
    }
    if ($_POST['delete_post'] == 'yes') {
      if (deletePost($_POST['pid'])) {
        $url  = bloginfo('url');
        $extra = 'profile/?page=manage&msg=delete_success';
        header("Location: $url/$extra");
        exit();
      }
      else {
        $url  = bloginfo('url');
        $extra = 'profile/?page=manage&msg=delete_error';
        header("Location: $url/$extra");
        exit();
      }
    }
    else {
      $url  = bloginfo('url');
      $extra = 'profile/?page=manage';
      header("Location: $url/$extra");
      exit();
    }
  }
?>
<!DOCTYPE html>
<html lang="cs">

  <head>
    <title>Odstranit | <?php echo bloginfo('name'); ?></title>
    <?php include_head_assets(); ?>
    <?php global $userIdentity; ?>
  </head>

  <body class="profile-page delete-page blog-page">
    <?php include_header() ?>
    <div class="container">
      <?php if( isLoggedIn() && $postId && hasPost($userIdentity['id'], $postId) ) : ?>
      <div class="row heading">
        <div class="col-12">
          <h1>Odstranit</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <?php
            $post = getPostById($postId);
            echo '<p>Opravdu chcete smazat příspěvek: <strong>' . $post['title'] . '</strong> ?</p>';
          ?>
          <form method="post" action="<?php echo bloginfo('url') . '/profile/?page=delete&post_id=' . $post['id']; ?>">
            <input type="hidden" name="pid" value="<?php echo $post['id']; ?>" />
            <button type="submit" name="delete_post" value="yes">Ano</button>
            <button type="submit" name="delete_post" value="no">Ne</button>
          </form>
        </div>
      </div>
    <?php else : ?>

      <?php include(bloginfo('path') . '/core/templates/_permission-denied.php'); ?>

    <?php endif; ?>

    </div>

  <?php include_footer() ?>
