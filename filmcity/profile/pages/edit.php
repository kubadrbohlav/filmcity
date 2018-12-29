<?php
  // get post ID
  $postId = isset($_GET['post_id']) ? $_GET['post_id'] : '';

  // extend class for processing images and start session
  include(bloginfo('path') . '/core/class.upload.php');
  session_start();

  // get post
  $post = getPostById($postId);

  // errors

  $errors = false;
  $title_e      = false;
  $cat_e        = false;
  $image_e      = 0;
  $desc_e       = false;
  $upload_e     = 0;
  $submit_e     = false;

  // get inputs
  $title        = isset( $_POST['title'] ) ? htmlspecialchars($_POST['title']) : htmlspecialchars($post['title']);
  $cat          = isset( $_POST['category'] ) ? htmlspecialchars($_POST['category']) : htmlspecialchars($post['category']);
  $desc         = isset( $_POST['description'] ) ? htmlspecialchars($_POST['description']) : htmlspecialchars($post['description']);
  $rawPath      = htmlspecialchars($post['image']);

  // if form is submitted
  if( isset($_POST['update-post']) ) {

    // if title is empty
    if ( $title == '' ) {
      $title_e = true;
      $errors = true;
    }

    // if category is empty
    if ( $cat == '' ) {
      $cat_e = true;
      $errors = true;
    }

    // if image is submitted
    if( !empty($_FILES['image']) && $_FILES['image']['size'] > 0 ) {
      $target_dir = '/uploads/';
      $target_file = time() . '_' . basename(preg_replace('/[^A-Za-z0-9\_\-\.]/', '', $_FILES['image']['name']));
      $rawPath = $target_dir.$target_file;
      $imageType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

      // if file already exists
      if (file_exists($target_file)) {
        $upload_e = 1;
        $errors = true;
      }

      // if file is too large
      if ($_FILES['image']['size'] > 10000000) {
        $upload_e = 2;
        $errors = true;
      }

      // if file type is not allowed
      if($imageType != "jpg" && $imageType != "png" && $imageType != "jpeg" && $imageType != "gif" ) {
        $upload_e = 3;
        $errors = true;
      }

      // if no image errors
      if (!$errors) {
        // create new upload class
        $handle = new upload($_FILES['image']);

        // try uploaded image
        if ($handle->uploaded) {
          // if success

          // set file name
          $handle->file_new_name_body = substr($target_file, 0 , (strrpos($target_file, "."))); // remove file suffix

          // resize image
          $handle->image_resize = true;
          $handle->image_x = 300;
          $handle->image_ratio_y = true;

          // try to process image
          $handle->process(bloginfo('path').'/uploads/');
          if ($handle->processed) {
            // if success, clean memory and delete old image if existed
            $handle->clean();
            if ($post['image']) {
              unlink(bloginfo('path').$post['image']);
            }
          }
          else {
            $upload_e = 4;
            $errors = true;
          }
        }
        else {
          $upload_e = 4;
          $errors = true;
        }
      }

    }

    // if length of description is less than 10 characters
    if ( strlen($desc) < 10 ) {
      $desc_e = true;
      $errors = true;
    }

    // if no input errors
    if (!$errors) {

      // update post data
      if( updatePost($title, $desc, $rawPath, $cat, $post['id']) ) {
        // redirect if success
        $url  = bloginfo('url');
        $extra = 'profile/?page=manage&msg=updated';
        header("Location: $url/$extra");
        exit();
      }
      else {
        $submit_e = true;
      }
    }
    else {
      // code...
    }

  }
?>
<!DOCTYPE html>
<html lang="cs">

  <head>
    <title>Přidat nový | <?php echo bloginfo('name'); ?></title>
    <?php include_head_assets(); ?>
    <?php global $userIdentity; ?>
  </head>

  <body class="profile-page edit-post-page blog-page">
    <?php include_header() ?>
    <div class="container">

      <?php // if current user can update post ?>
      <?php if( isLoggedIn() && $postId && hasPost($userIdentity['id'], $postId) ) : ?>
      <div class="row heading">
        <div class="col-12">
          <h1>Upravit: <?php echo $post['title']; ?></h1>
          <a href="<?php echo bloginfo('url').'/profile/?page=manage'; ?>" title="Zpět">Zpět</a>
        </div>
      </div>

      <!-- Update form -->
      <div class="row">
        <div class="col-12 col-md-9">
          <form id="update-form" name="update-form" action="<?php echo bloginfo('url') . '/profile/?page=edit&post_id=' . $post['id']; ?>" method="post" enctype="multipart/form-data">
            <div class="form-element">
              <label for="title">Titulek <span>*</span></label>
              <input type="text" id="title" name="title" value="<?php echo $title; ?>" <?php if($title_e): ?>class="error"<?php endif; ?> required />
              <?php if($title_e): ?><span class="error">Toto pole je povinné.</span><?php endif; ?>
            </div>
            <div class="form-element">
              <label for="category">Kategorie <span>*</span></label>
              <select id="category" name="category" <?php if($cat_e): ?>class="error"<?php endif; ?> required>
                <option value="">--- Vyberte kategorii ---</option>
                <?php getCategoriesOptions($cat); ?>
              <select>
              <?php if($cat_e): ?><span class="error">Toto pole je povinné.</span><?php endif; ?>
            </div>
            <div class="form-element">
              <label for="image">Ilustrační obrázek/plakát</label>
              <input type="file" id="image" name="image" value="" />
              <?php if($post['image']) : ?>
                <div>
                  <span>Aktuální obrázek <a href="<?php echo bloginfo('url').$post['image']; ?>" target="_blank" title="Ilustrační obrázek">zde</a>.</span>
                </div>
              <?php else: ?>
                <div>
                  <span>Příspěvek nemá ilustrační obrázek.</span>
                </div>
              <?php endif; ?>
              <?php if($upload_e == 1): ?><span class="error">Tento soubor již existuje.</span><?php endif; ?>
              <?php if($upload_e == 2): ?><span class="error">Soubor je příliš velký.</span><?php endif; ?>
              <?php if($upload_e == 3): ?><span class="error">Soubor musí být typu: JPG, JPEG, PNG nebo GIF</span><?php endif; ?>
              <?php if($upload_e == 4): ?><span class="error">Při nahrávání souboru došlo k chybě.</span><?php endif; ?>
            </div>
            <div class="form-element">
              <label for="description">Popis <span>*</span></label>
              <textarea id="description" name="description" <?php if($desc_e): ?>class="error"<?php endif; ?> required><?php echo $desc; ?></textarea>
              <?php if($desc_e): ?><span class="error">Popis musí obsahovat alespoň 10 znaků.</span><?php endif; ?>
            </div>
            <div class="form-element">
              <input type="submit" id="update-post" name="update-post" value="Upravit" />
              <?php if($submit_e): ?><span class="error">Při zápisu do databáze došlo k chybě.</span><?php endif; ?>
            </div>
            <div class="form-element required-fields"><span>Pole označená * jsou povinné.</span></div>
          </form>
        </div>

        <!-- User menu -->
        <?php include(bloginfo('path') . '/core/templates/_user-menu.php'); ?>
      </div>

      <!-- Form validation -->
      <script src="<?php echo bloginfo('url').'/assets/js/validate-edit.js' ?>"></script>
      
    <?php else : ?>

      <?php include(bloginfo('path') . '/core/templates/_permission-denied.php'); ?>

    <?php endif; ?>

    </div>

  <?php include_footer() ?>
