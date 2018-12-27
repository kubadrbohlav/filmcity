<?php
  require_once(__DIR__.'/core/core.php');
  session_start();

  $catId = isset($_GET['category']) ? $_GET['category'] : '';
  $category = getCategory($catId);

  if($catId && !$category) {
    $url  = bloginfo('url');
    $extra = 'category.php';
    header("Location: $url/$extra");
    exit();
  }

  $paged = isset($_GET['paged']) ? $_GET['paged'] : '';
  if ($paged == '' || $paged < 0) {
    $paged = 0;
  }
  if (!is_numeric($paged) || ( (int)(ceil(countPosts($catId)/POST_PER_PAGE)) < $paged ) ) {
    $url = bloginfo('url');
    $extra = 'category.php';
    if ($catId) {
      $extra .= '?category=' . $catId;
    }
    header("Location: $url/$extra");
    exit();
  }

  $order_by = isset($_GET['order_by']) ? $_GET['order_by'] : '';
  if ($order_by && $order_by != 'newest' && $order_by != 'rating') {
    $url = bloginfo('url');
    $extra = 'category.php';
    if ($catId) {
      $extra .= '?category=' . $catId;
    }
    header("Location: $url/$extra");
    exit();
  }

  $posts = getPosts($catId, $paged, $order_by);

?>
<!DOCTYPE html>
<html lang="cs">

  <head>
    <title><?php if($category) echo $category['name']; else echo 'Všechny filmy'; ?> | <?php echo bloginfo('name'); ?></title>
    <?php include_head_assets(); ?>
  </head>

  <body class="category-page blog-page">
    <?php include_header() ?>

    <div class="container">
      <div class="category-header row">
        <div class="col-12">
          <h1><?php if($category) echo $category['name']; else echo 'Všechny filmy'; ?></h1>
          <div class="sorting">
            <form action="<?php echo bloginfo('url') . '/category.php' ?>" method="get">
              <?php if($catId) : ?>
                <input type="hidden" name="category" value="<?php echo $catId; ?>" />
              <?php endif ?>
              <label for="order_by">Seřadit podle:</label>
              <select id="order_by" name="order_by">
                <option>--- Vybrat ---</option>
                <option value="newest" <?php if($order_by == 'newest') echo 'selected'; ?>>Nejnovější</option>
                <option value="rating" <?php if($order_by == 'rating') echo 'selected'; ?>>Nejoblíbenější</option>
              </select>
              <input type="submit" value="Seřadit" />
            </form>
          </div>
        </div>
      </div>

      <div class="row">
        <div id="posts" class="col-12 col-md-9">
          <?php if($posts): ?>

            <?php pagination($catId, $paged, $order_by); ?>

            <?php foreach( $posts as $post ) : ?>

              <?php
                $cat    = getCategory($post['category']);
              ?>

              <article class="post cf">
                <div class="img-wrapper">
                    <a href="<?php echo bloginfo('url') . '/post.php?id=' . $post['id']; ?>" title="<?php echo $post['title']; ?>"><img src="<?php if($post['image']) echo bloginfo('url').$post['image']; else echo bloginfo('url').'/assets/images/no-image.png'; ?>" alt="<?php echo $post['title']; ?>" /></a>
                </div>
                <div class="content">
                  <h2><a href="<?php echo bloginfo('url') . '/post.php?id=' . $post['id']; ?>" title="<?php echo $post['title']; ?>"><?php echo $post['title']; ?></a></h2>
                  <div class="metadata">
                    <dl>
                      <dt>Publikováno</dt>
                      <dd><?php echo date('j.n.Y', strtotime($post['created'])); ?></dd>
                      <dt>Kategorie</dt>
                      <dd><a href="<?php echo bloginfo('url').'/category.php?category='.$cat['id']; ?>" title="<?php echo $cat['name']; ?>"><?php echo $cat['name']; ?></a></dd>
                    </dl>
                    <div class="rating">
                      <div class="note"><?php echo avgRating($post['id']); ?></div>
                      <div class="count"><?php echo countRatings($post['id']); ?> Hodnocení</div>
                    </div>
                  </div>
                  <p class="description"><?php echo $post['description']; ?></p>
                  <a class="more-link" href="<?php echo bloginfo('url') . '/post.php?id=' . $post['id']; ?>" title="Čtěte více">Čtěte více</a>
                </div>
              </article>

            <?php endforeach; ?>

            <?php pagination($catId, $paged, $order_by); ?>

          <?php else: ?>

            <p>Nejsou zde zatím žádné příspěvky.</p>

          <?php endif; ?>

        </div>

        <div id="sidebar" class="col-12 col-md-3">
          <nav id="sidebar-categories">
            <h2>Kategorie</h2>
            <ul>
              <?php
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
