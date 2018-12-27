<?php

// Application constants

define("POST_PER_PAGE", 10);


// Application database ------------------------------------------

$dsn = 'mysql:dbname=f112371;host=c219um.forpsi.com';
$db_user = 'f112371';
$db_passwd = '9dPsDRch';
$db_options = [
  PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
  PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
];

try {
    $db = new PDO($dsn, $db_user, $db_passwd, $db_options);
} catch (PDOException $e) {
    exit('<strong>DB - chyba spojeni:</strong> ' . $e->getMessage());
}

// -------------------------------------------------------------

function include_head_assets() {
  require_once __DIR__ . '/templates/head_assets.php';
}

function include_header() {
  require_once __DIR__ . '/templates/header.php';
}

function include_footer() {
  require_once __DIR__ . '/templates/footer.php';
}


function bloginfo( $param ) {
  global $db;
  $sql = 'SELECT * FROM options WHERE option_id=1';
  $options = $db->query($sql)->fetch();
  switch ($param) {
    case 'name':
      return htmlspecialchars($options['blog_name']);
      break;
    case 'url':
      return htmlspecialchars($options['blog_url']);
      break;
    case 'path':
      return htmlspecialchars($options['root_path']);
      break;
    default:
      return false;
      break;
  }
}


function isLoggedIn() {
  return isset($_SESSION['userid']) ? true : false;
}


function getUserByEmail($email) {
  global $db;
  $sql = 'SELECT * FROM users WHERE email=:email';
  $stmt = $db->prepare($sql);
  $stmt->bindValue(':email', $email, PDO::PARAM_STR);

  if ( $stmt->execute() ) {
    $user = $stmt->fetch();
    return $user;
  }
  else {
    return false;
  }
}


function getUserById($uid) {
  global $db;
  $sql = 'SELECT * FROM users WHERE id=:uid';
  $stmt = $db->prepare($sql);
  $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);

  if ( $stmt->execute() ) {
    $user = $stmt->fetch();
    return $user;
  }
  else {
    return false;
  }
}


function registerUser($name, $surname, $email, $passwd) {
  global $db;
  $db->beginTransaction();
  $sql = 'INSERT INTO users (name, surname, email, passwd) VALUES (:name, :surname, :email, :passwd)';
  $stmt = $db->prepare($sql);

  $stmt->bindValue(':name', $name, PDO::PARAM_STR);
  $stmt->bindValue(':surname', $surname, PDO::PARAM_STR);
  $stmt->bindValue(':email', $email, PDO::PARAM_STR);
  $stmt->bindValue(':passwd', password_hash($passwd, PASSWORD_BCRYPT), PDO::PARAM_STR);

  try {
     $stmt->execute();
     $db->commit();

     return 1;
  }
  catch (PDOException $e) {
     if ($e->errorInfo[1] == 1062) {
        // return -2 if duplicated email
        return -1;
     } else {
        return -2;
     }
  }
}


function getCategories() {
  global $db;
  $sql = 'SELECT * FROM categories ORDER BY name';
  $stmt = $db->prepare($sql);

  if ( $stmt->execute() ) {
    $categories = $stmt->fetchAll();
    return $categories;
  }
  else {
    return false;
  }
}


function getCategoriesOptions($selectedCat) {
  global $db;
  $sql = 'SELECT * FROM categories ORDER BY name';
  $stmt = $db->prepare($sql);

  if ( $stmt->execute() ) {
    $categories = $stmt->fetchAll();

    foreach ($categories as $category) {
      $selected = ($category['id'] == $selectedCat) ? ' selected' : '';
      echo '<option value="'.$category['id'].'"'.$selected.'>'.$category['name'].'</option>';
    }
  }
  else {
    console.log('Nastala chyba při načítání kategorií');
  }
}


function getUserPosts($uid) {
  global $db;
  $sql = 'SELECT * FROM posts WHERE author=:uid ORDER BY created DESC';
  $stmt = $db->prepare($sql);
  $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);

  if ( $stmt->execute() ) {
    $posts = $stmt->fetchAll();
    return $posts;
  }
  else {
    return false;
  }
}


function getPostById($pid) {
  global $db;
  $sql = 'SELECT * FROM posts WHERE id=:pid';
  $stmt = $db->prepare($sql);
  $stmt->bindValue(':pid', $pid, PDO::PARAM_INT);

  if ( $stmt->execute() ) {
    $posts = $stmt->fetch();
    return $posts;
  }
  else {
    return false;
  }
}


function getPosts($cid = '', $page, $order_by) {
  global $db;
  $sql = 'SELECT * FROM posts';

  if ( $order_by == 'rating' ) {
    $sql = 'SELECT p.id, title, category, image, description, created, AVG(value) AS AVGRating FROM posts AS p JOIN ratings AS r ON p.id = r.post';

    if($cid) {
      $sql .= ' WHERE category=:cid';
    }

    $sql .= ' GROUP BY p.id ORDER BY AVGRating DESC';
  }
  else {
    if($cid) {
      $sql .= ' WHERE category=:cid';
    }
    if ( $order_by == 'newest' ) {
      $sql .= ' ORDER BY created DESC';
    }
  }

  if ( $page == 0 || $page == 1 ) {
    $sql .= ' LIMIT ' . POST_PER_PAGE;
  }
  else {
    $from = (int)(($page-1) * POST_PER_PAGE);
    $sql .= ' LIMIT ' . $from . ', ' . POST_PER_PAGE;
  }

  $stmt = $db->prepare($sql);
  if ($cid) {
    $stmt->bindValue(':cid', $cid, PDO::PARAM_INT);
  }

  if ( $stmt->execute() ) {
    $posts = $stmt->fetchAll();
    return $posts;
  }
  else {
    return false;
  }
}


function countPosts($cid = '') {
  global $db;
  if($cid) {
    $sql = 'SELECT * FROM posts WHERE category=:cid';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':cid', $cid, PDO::PARAM_INT);
  }
  else {
    $sql = 'SELECT * FROM posts';
    $stmt = $db->prepare($sql);
  }

  if ( $stmt->execute() ) {
    return $stmt->rowCount();
  }
  else {
    return false;
  }
}


function getTopPosts($num) {
  global $db;
  $sql = 'SELECT p.id, title, image, AVG(value) AS AVGRating FROM posts AS p JOIN ratings AS r ON p.id = r.post  GROUP BY p.id ORDER BY AVGRating DESC LIMIT :num';
  $stmt = $db->prepare($sql);
  $stmt->bindValue(':num', $num, PDO::PARAM_INT);

  if ( $stmt->execute() ) {
    $posts = $stmt->fetchAll();
    return $posts;
  }
  else {
    return false;
  }
}


function getCategory($cid) {
  global $db;
  if(!$cid) {
    return false;
  }

  $sql = 'SELECT * FROM categories WHERE id=:cid';
  $stmt = $db->prepare($sql);
  $stmt->bindValue(':cid', $cid, PDO::PARAM_INT);

  if ( $stmt->execute() ) {
    $category = $stmt->fetch();
    return $category;
  }
  else {
    return false;
  }
}


function linkParams($cid, $order_by) {
    $url = '';
    if ($cid) {
      $url .= '&category=' . $cid;
    }
    if ($order_by) {
      $url .= '&order_by=' . $order_by;
    }
    return $url;
}


function pagination($cid, $currentPage, $order_by) {
  $totalPosts = countPosts($cid);
  if ( $totalPosts > POST_PER_PAGE ) {

    if ($currentPage == 0) {
      $currentPage = 1;
    }

    $totalPages = (int)(ceil($totalPosts/POST_PER_PAGE));
    $params = $_SERVER['QUERY_STRING'];

    echo '<div class="pagination">';
    if ($currentPage != 1) {
      echo '<a class="prev-btn" href="' . bloginfo('url') . '/category.php?paged=' . ($currentPage-1) . linkParams($cid, $order_by) . '" title="Předchozí"><i class="fas fa-angle-left"></i> Předchozí</a>';
    }

    echo '<ol>';
    for($i = 1; $i <= $totalPages; $i++) {
      if( $i == $currentPage ) {
        echo '<li><span>'.$i.'</span></li>';
      }
      else {
        echo '<li><a href="' . bloginfo('url') . '/category.php?paged=' . $i . linkParams($cid, $order_by) . '" title="Strana '.$i.'">'.$i.'</a></li>';
      }
    }
    echo '</ol>';

    if ($currentPage != $totalPages) {
      echo '<a class="next-btn" href="' . bloginfo('url') . '/category.php?paged=' . ($currentPage+1) . linkParams($cid, $order_by) . '" title="Další">Další <i class="fas fa-angle-right"></i></a>';
    }
    echo '</div>';

  }
}


function addRating($pid, $uid, $value) {
  global $db;
  $db->beginTransaction();
  $sql = 'INSERT INTO ratings (post, author, value) VALUES (:post, :author, :value)';
  $stmt = $db->prepare($sql);

  $stmt->bindValue(':post', $pid, PDO::PARAM_INT);
  $stmt->bindValue(':author', $uid, PDO::PARAM_INT);
  $stmt->bindValue(':value', $value, PDO::PARAM_INT);

  if ( $stmt->execute() ) {
    $db->commit();
  }

  $user = getUserById($uid);
  if ($user['voted']) {
    $voted_array = unserialize($user['voted']);
    array_push($voted_array, $pid);
  }
  else {
    $voted_array = array($pid);
  }
  $voted_array = serialize($voted_array);

  $sql = 'UPDATE users SET voted=:voted WHERE id=:uid';
  $stmt = $db->prepare($sql);
  $stmt->bindValue(':voted', $voted_array, PDO::PARAM_STR);
  $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);

  if ( $stmt->execute() ) {
    return true;
  }
  else {
    return false;
  }
}


function countRatings($pid) {
  global $db;
  $sql = 'SELECT * FROM ratings WHERE post=:pid';
  $stmt = $db->prepare($sql);
  $stmt->bindValue(':pid', $pid, PDO::PARAM_INT);

  if ( $stmt->execute() ) {
    return $stmt->rowCount();
  }
  else {
    return -1;
  }
}


function avgRating($pid) {
  global $db;
  $sql = 'SELECT AVG(value) FROM ratings WHERE post=:pid';
  $stmt = $db->prepare($sql);
  $stmt->bindValue(':pid', $pid, PDO::PARAM_INT);

  if ( $stmt->execute() ) {
    $row = $stmt->fetch();
    $avg = $row["AVG(value)"];
    return number_format((float)$avg, 1);
  }
  else {
    return -1;
  }
}


function savePost($title, $desc, $target_file, $author, $cat, $rating) {
  global $db;
  $db->beginTransaction();
  $sql = 'INSERT INTO posts (title, description, image, author, category) VALUES (:title, :description, :image, :author, :category)';
  $stmt = $db->prepare($sql);

  $stmt->bindValue(':title', $title, PDO::PARAM_STR);
  $stmt->bindValue(':description', $desc, PDO::PARAM_STR);
  $stmt->bindValue(':image', $target_file, PDO::PARAM_STR);
  $stmt->bindValue(':author', $author, PDO::PARAM_INT);
  $stmt->bindValue(':category', $cat, PDO::PARAM_INT);

  try {
     // try to execute sql query
     $stmt->execute();
     $postId = $db->lastInsertId();
     $db->commit();

     if ( addRating( $postId, $author, $rating ) ) {
       return true;
     }
     else {
       return false;
     }
  }
  catch (PDOException $e) {
    //$db->rollback();
    return false;
  }
}


function updatePost($title, $desc, $target_file, $cat, $postId) {
  global $db;
  $db->beginTransaction();
  $sql = 'UPDATE posts SET title=:title, description=:description, image=:image, category=:category WHERE id=:pid';
  $stmt = $db->prepare($sql);

  $stmt->bindValue(':title', $title, PDO::PARAM_STR);
  $stmt->bindValue(':description', $desc, PDO::PARAM_STR);
  $stmt->bindValue(':image', $target_file, PDO::PARAM_STR);
  $stmt->bindValue(':category', $cat, PDO::PARAM_STR);
  $stmt->bindValue(':pid', $postId, PDO::PARAM_INT);

  try {
     // try to execute sql query
     $stmt->execute();
     $db->commit();

     return true;
  }
  catch (PDOException $e) {
     return false;
  }
}


function deletePost($pid) {
  global $db;
  $sql = 'DELETE FROM posts WHERE id=:pid';
  $stmt = $db->prepare($sql);
  $stmt->bindValue(':pid', $pid, PDO::PARAM_INT);

  if ( $stmt->execute() ) {
    return true;
  }
  else {
    return false;
  }
}


function hasPost($userId, $postId) {
  global $db;
  $sql = 'SELECT * FROM posts WHERE id=:pid AND author=:uid';
  $stmt = $db->prepare($sql);
  $stmt->bindValue(':pid', $postId, PDO::PARAM_INT);
  $stmt->bindValue(':uid', $userId, PDO::PARAM_INT);

  if ( $stmt->execute() ) {
    $post = $stmt->fetch();
    return $post;
  }
  else {
    return false;
  }
}


function canRate($pid, $uid) {
  $user = getUserById($uid);
  $voted = unserialize($user['voted']);
  if(!$voted) {
    return true;
  }
  if (in_array($pid, $voted)) {
    return false;
  }
  else {
    return true;
  }
}


function updateStyles($style, $uid) {
  global $db;
  $sql = 'UPDATE users SET styles=:style WHERE id=:uid';
  $stmt = $db->prepare($sql);
  $stmt->bindValue(':style', $style, PDO::PARAM_STR);
  $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);

  if ( $stmt->execute() ) {
    return true;
  }
  else {
    return false;
  }
}


function updateUser($uid, $name, $surname) {
  global $db;
  $sql = 'UPDATE users SET name=:name, surname=:surname WHERE id=:uid';
  $stmt = $db->prepare($sql);
  $stmt->bindValue(':name', $name, PDO::PARAM_STR);
  $stmt->bindValue(':surname', $surname, PDO::PARAM_STR);
  $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);

  if ( $stmt->execute() ) {
    return true;
  }
  else {
    return false;
  }
}
