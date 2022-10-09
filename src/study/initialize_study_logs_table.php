<?php

require_once __DIR__ . '/lib/mysqli.php';

function dropTable($link)
{
    $dropTableSql = 'DROP TABLE IF EXISTS study_logs';
    $result = mysqli_query($link, $dropTableSql);
    if (!$result) {
        echo 'テーブルの削除に失敗しました。' . PHP_EOL;
        echo 'Debugging Error: ' . mysqli_error($link) . PHP_EOL;
    } else {
      echo 'テーブルを削除しました。' . PHP_EOL;
    }
}

function createTable($link)
{
    $createTableSql = <<<EOT
CREATE TABLE study_logs (
    id INTEGER AUTO_INCREMENT NOT NULL PRIMARY KEY,
    study_date VARCHAR(10) NOT NULL,
    study_time VARCHAR(5) NOT NULL,
    content VARCHAR(200),
    rate INTEGER,
    goal VARCHAR(200),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) DEFAULT CHARACTER SET=utf8mb4;
EOT;

    $result = mysqli_query($link, $createTableSql);
    if (!$result) {
      echo 'テーブルの作成に失敗しました。' . PHP_EOL;
      echo 'Debugging Error: ' . mysqli_error($link) . PHP_EOL;
    } else {
      echo 'テーブルを作成しました。' . PHP_EOL;
    }
}

$link = dbConnect();
dropTable($link);
createTable($link);

mysqli_close($link);
