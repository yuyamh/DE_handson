<?php
$log = [
  'date' => '',
  'study_time' => ['', ''],
  'content' => '',
  'rate' => '',
  'goal' => '',
];
$errors = [];

$title = '学習ログの登録';
$content = __DIR__ . '/views/new.php';
include __DIR__ . '/views/layout.php';
