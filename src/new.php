<?php

$review = [
  'title' => '',
  'author' => '',
  'status' => '',
  'score' => '',
  'summary' => '',
];

$errors = [];

$title = '読書ログの登録';
$content = __DIR__ . '/views/new.php';
include  __DIR__ . '/views/layout.php';
