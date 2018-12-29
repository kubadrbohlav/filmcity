<?php
  // include core functions
  require_once('core.php');

  // add rating to database
  addRating($_POST['pid'], $_POST['uid'], $_POST['rating']);
