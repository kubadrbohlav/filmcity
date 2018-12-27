<?php
  require_once( dirname( __FILE__ ) . '/' . '../core/core.php');
  session_start();

  $page = isset($_GET['page']) ? $_GET['page'] : '';

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
