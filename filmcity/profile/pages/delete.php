<?php
  // get post ID and start session
  $postId = isset($_GET['post_id']) ? $_GET['post_id'] : '';
  session_start();

  // if form is submitted
  if ( isset($_POST['delete_post']) ) {
    // get post
    $post = getPostById($postId);

    // if delete post
    if ($_POST['delete_post'] == 'yes') {
      // if post has image, delete that image
      if ($post['image']) {
        unlink(bloginfo('path').$post['image']);
      }

      // delete post and then redirect to manage page
      if (deletePost($_POST['pid'])) {
        $url  = bloginfo('url');
        $extra = 'profile/?page=manage&msg=delete_success';
        header("Location: $url/$extra");
        exit();
      }

      // if error during deleting, redirect to manage page with error message
      else {
        $url  = bloginfo('url');
        $extra = 'profile/?page=manage&msg=delete_error';
        header("Location: $url/$extra");
        exit();
      }
    }

    // if do not delete post, redirect to manage page
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
      <?php // if current user can delete post with specified ID ?>
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

    <?php // if current user cannot delete post with specified ID ?>
    <?php else : ?>

      <?php include(bloginfo('path') . '/core/templates/_permission-denied.php'); ?>

    <?php endif; ?>

    </div>

  <?php include_footer() ?>
