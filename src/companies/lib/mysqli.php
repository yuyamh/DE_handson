<?php
// '__DIR__'は現在のディレクトリを示す
require __DIR__ . '/../../vendor/autoload.php';

function dbConnect()
{
  // データベース「book_log」と接続する。
  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
  $dotenv->load();

  $dbHost = $_ENV['DB_HOST'];
  $dbUsername = $_ENV['DB_USERNAME'];
  $dbPassword = $_ENV['DB_PASSWORD'];
  $dbDatabase = $_ENV['DB_DATABASE'];

  $link = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbDatabase);
  if (!$link) {
    echo 'データベースに接続できません。' . PHP_EOL;
    echo 'Error: ' . mysqli_connect_error() . PHP_EOL;
    exit;
  }

  return $link;
}
