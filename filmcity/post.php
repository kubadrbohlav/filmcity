<?php
  // inlude functions and start session
  require_once(__DIR__.'/core/core.php');
  session_start();

  // get post ID
  $postId = isset($_GET['id']) ? $_GET['id'] : '';

  // get post
  $post = getPostById($postId);

  // if post does not exists, redirect to 404 page
  if(($postId && !$post) || !$postId ) {
    $url  = bloginfo('url');
    $extra = '404.php';
    header("Location: $url/$extra");
    exit();
  }

  // Rating form

  $rating_e = false;
  $rating   = isset( $_POST['rating'] ) ? htmlspecialchars($_POST['rating']) : 2;

  // if rating form is submitted
  if( isset($_POST['rate-post']) ) {
    // if rating value is empty
    if($rating == '') {
      $rating_e = true;
    }

    else {
      // add rating to database
      if(addRating($post['id'], $_POST['uid'], $rating)) {
        // reload page if rating added successfully
        $url  = bloginfo('url');
        $extra = 'post.php?id='.$post['id'];
        header("Location: $url/$extra");
        exit();
      }
      else {
        $dbError = 'Chyba databáze.';
      }
    }
  }
?>
<!DOCTYPE html>
<html lang="cs">

  <head>
    <title><?php echo $post['title']; ?> | <?php echo bloginfo('name'); ?></title>
    <?php include_head_assets(); ?>
    <?php global $userIdentity; ?>
  </head>

  <body class="post-page blog-page">
    <?php include_header() ?>

    <div class="container">
      <div class="category-header row">
        <div class="col-12">
          <h1><?php echo $post['title']; ?></h1>
        </div>
      </div>

      <div class="row">
        <?php
          // get author and category of the post
          $author = getUserById($post['author']);
          $cat    = getCategory($post['category']);
        ?>

        <!-- Post -->
        <article class="post col-12 col-md-9 cf">
          <h2><?php echo $post['title']; ?></h2>
          <div class="img-wrapper">
              <img src="<?php if($post['image']) echo bloginfo('url').$post['image']; else echo bloginfo('url').'/assets/images/no-image.png'; ?>" alt="<?php echo $post['title']; ?>" />
          </div>
          <div class="content">
            <div class="metadata">
              <dl>
                <dt>Publikováno</dt>
                <dd><?php echo date('G:i, j.n.Y', strtotime($post['created'])); ?></dd>
                <dt>Autor</dt>
                <dd><?php echo $author['name'] . ' ' . $author['surname'] ?></dd>
                <dt>Kategorie</dt>
                <dd><a href="<?php echo bloginfo('url') . '/category.php?category=' . $cat['id']; ?>" title="<?php echo $cat['name'] ?>"><?php echo $cat['name']; ?></a></dd>
              </dl>
              <div class="rating">
                <div class="note"><?php echo avgRating($post['id']); ?></div>
                <div class="count"><?php echo countRatings($post['id']); ?> Hodnocení</div>
              </div>
            </div>
            <p class="description"><?php echo $post['description']; ?></p>
          </div>
        </article>

        <!-- Sidebar -->
        <div id="sidebar" class="col-12 col-md-3">
          <?php // if current user can rate post, display rating form ?>
          <?php if(isLoggedIn()) : ?>
            <?php if( canRate($post['id'], $userIdentity['id']) && !hasPost($userIdentity['id'], $post['id']) ): ?>
              <h2>Vaše hodnocení</h2>
              <form id="rate-form" action="<?php echo bloginfo('url').'/post.php?id='.$post['id']; ?>" method="post" data-path="<?php echo bloginfo('url').'/core/rate.php'; ?>">
                <div class="form-element star-rating">
                  <label for="rating-1" title="1.0">1</label>
                  <input type="radio" id="rating-1" name="rating" value="1" <?php if($rating == 1): ?>checked<?php endif; ?> />
                  <label for="rating-2" title="2.0">2</label>
                  <input type="radio" id="rating-2" name="rating" value="2" <?php if($rating == 2): ?>checked<?php endif; ?> />
                  <label for="rating-3" title="3.0">3</label>
                  <input type="radio" id="rating-3" name="rating" value="3" <?php if($rating == 3): ?>checked<?php endif; ?> />
                  <label for="rating-4" title="4.0">4</label>
                  <input type="radio" id="rating-4" name="rating" value="4" <?php if($rating == 4): ?>checked<?php endif; ?> />
                  <label for="rating-5" title="5.0">5</label>
                  <input type="radio" id="rating-5" name="rating" value="5" <?php if($rating == 5): ?>checked<?php endif; ?> />
                  <?php if($rating_e): ?><span class="error">Zvolte známku.</span><?php endif; ?>
                </div>
                <input type="hidden" name="pid" value="<?php echo $post['id']; ?>" />
                <input type="hidden" name="uid" value="<?php echo $userIdentity['id']; ?>" />
                <input type="submit" id="rate-post" name="rate-post" value="Hodnotit film" />
                <?php if(isset($dbError)): ?><span class="error"><?php echo $dbError; ?></span><?php endif; ?>
              </form>

            <?php // if current user already rated post ?>
            <?php else: ?>
              <div class="already-voted">
                <p>Tento film jste již hodnotil/a.</p>
              </div>
            <?php endif; ?>
          <?php endif; ?>

          <nav id="sidebar-categories">
            <h2>Kategorie</h2>
            <ul>
              <?php
                // get all categories
                $categories = getCategories();
                foreach($categories as $category) {
                  echo '<li><a href="'.bloginfo('url').'/category.php?category='.$category['id'].'" title="'.$category['name'].'">'.$category['name'].'</a></li>';
                }
              ?>
            </ul>
          </nav>
        </div>
      </div>
    </div>


  <?php include_footer() ?>
