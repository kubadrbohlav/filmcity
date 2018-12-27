<?php
  require_once(__DIR__.'/core/core.php');
  session_start();
?>
<!DOCTYPE html>
<html lang="cs">

  <head>
    <title><?php echo bloginfo('name'); ?></title>
    <?php include_head_assets(); ?>
  </head>

  <body>
    <?php include_header() ?>

    <section id="intro">
      <div class="wrapper">
        <div class="content">
          <h1>Svět filmů</h1>
          <p>Vyberte si film na večer, sdílejte nebo hodnoťte!</p>
        </div>
      </div>
    </section>

    <section id="most-favourire">
      <div class="container">
        <h2>Nejoblíbenější</h2>
        <div class="row fav-list">
          <?php
            $topPosts = getTopPosts(8);
          ?>
          <?php if($topPosts) : ?>

            <?php foreach($topPosts as $post): ?>
              <div class="col-12 col-sm-6 col-md-3">
                <div class="cover">
                  <a href="<?php echo bloginfo('url').'/post.php?id='.$post['id']; ?>" title="<?php echo $post['title']; ?>"><img src="<?php if($post['image']) echo bloginfo('url').$post['image']; else echo bloginfo('url').'/assets/images/no-image.png'; ?>" alt="<?php echo $post['title']; ?>" /></a>
                </div>
                <div class="title">
                  <a href="<?php echo bloginfo('url').'/post.php?id='.$post['id']; ?>" title="<?php echo $post['title']; ?>"><?php echo $post['title']; ?></a>
                </div>
              </div>
            <?php endforeach; ?>

          <?php else: ?>

            <p>Nenalezeny žádné příspěvky pro tuto sekci.</p>

          <?php endif ?>
        </div>
      </div>
    </section>

    <section id="all-categories">
      <div class="container">
        <h2>Kategorie</h2>
        <div class="row">
          <?php
            $categories = getCategories();
          ?>
          <?php if($categories) : ?>

            <?php foreach($categories as $category) : ?>

              <div class="col-12 col-sm-6 col-md-4 category">
                <a href="<?php echo bloginfo('url').'/category.php?category='.$category['id']; ?>" title="<?php echo $category['name']; ?>"><img src="<?php echo bloginfo('url').$category['icon']; ?>" alt="<?php echo $category['name']; ?> filmy ikona" /></a>
                <h3><a href="<?php echo bloginfo('url').'/category.php?category='.$category['id']; ?>" title="<?php echo $category['name']; ?>"><?php echo $category['name']; ?></a></h3>
              </div>

            <?php endforeach; ?>

          <?php else: ?>

            <p>Nejsou vytvořeny žádné kategorie.</p>

          <?php endif; ?>
        </div>
      </div>
    </section>


<?php include_footer() ?>
