<?php
  // inlude functions and start session
  require_once( dirname( __FILE__ ) . '/' . '../core/core.php');
  session_start();

  // get page for view
  $page = isset($_GET['page']) ? $_GET['page'] : '';

  // include page for view
  switch ($page) {
    case 'add':
      include('pages/add.php');
      break;
    case 'manage':
      include('pages/manage.php');
      break;
    case 'delete':
      include('pages/delete.php');
      break;
    case 'edit':
      include('pages/edit.php');
      break;
    case 'style':
      include('pages/styles.php');
      break;
    case 'detail':
      include('pages/personal.php');
      break;
    default:
      include('pages/profile.php');
      break;
  }
