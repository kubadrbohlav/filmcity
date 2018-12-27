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
          <h1>Moje filmy</h1>
          <a href="<?php echo bloginfo('url') . '/profile/?page=add' ?>" title="Přidat nový">Přidat nový <span class="fas fa-plus"></span></a>
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-md-9">
          <?php $posts = getUserPosts($userIdentity['id']); ?>
          <?php if($posts): ?>
            <?php foreach($posts as $post): ?>
              <div class="post">
                <h2><?php echo $post['title']; ?></h2>
                <span class="total-ratings"><?php echo countRatings($post['id']); ?> Hodnocení</span>
                <span class="created"><?php echo date('G:i, j.n.Y', strtotime($post['created'])); ?></span>
                <div class="btns">
                  <a href="<?php echo bloginfo('url') . '/post.php?id=' . $post['id'] ?>" title="Zobrazit">Zobrazit</a>
                  <a href="<?php echo bloginfo('url') . '/profile/?page=edit&post_id=' . $post['id'] ?>" title="Upravit">Upravit</a>
                  <a href="<?php echo bloginfo('url') . '/profile/?page=delete&post_id=' . $post['id'] ?>" title="Smazat">Smazat</a>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p>Zatím zde nemáte žádné své recenze filmů.</p>
          <?php endif; ?>
        </div>

        <?php include(bloginfo('path') . '/core/templates/_user-menu.php'); ?>

      </div>
    <?php else : ?>

      <?php include(bloginfo('path') . '/core/templates/_permission-denied.php'); ?>

    <?php endif; ?>

    </div>

  <?php include_footer() ?>
