<?php

require_once __DIR__ . '/lib/mysqli.php';

function dropTable($link)
{
    $dropTableSql = 'DROP TABLE IF EXISTS companies';
    $result = mysqli_query($link, $dropTableSql);
    if ($result) {
      echo 'テーブルを削除しました' . PHP_EOL;
    } else {
      echo 'Error: テーブルの削除に失敗しました' . PHP_EOL;
      echo 'Debugging Error: ' . mysqli_error($link) . PHP_EOL;
    };
}

function createTable($link)
{
    $createTableSql = <<<EOT
    CREATE TABLE companies (
      id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(255),
      establishment_date DATE,
      founder VARCHAR(255),
      created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) DEFAULT CHARACTER SET=utf8mb4;
EOT;
    $result = mysqli_query($link, $createTableSql);
    if ($result) {
      echo 'テーブルを作成しました' . PHP_EOL;
    } else {
      echo 'Error: テーブルの作成に失敗しました' . PHP_EOL;
      echo 'Debugging Error: ' . mysqli_error($link) . PHP_EOL;
    };
}

$link = dbConnect();
dropTable($link);
createTable($link);
mysqli_close($link);
