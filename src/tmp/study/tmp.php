<?php

// function calculateToMinutes($param) {
//   $params = array_map('intval', explode(':', $param));

//   $calculateToMinutes = $params[0] * 60 + $params[1];

//   return $calculateToMinutes;
// }

// echo '文字を入力：';
// $param = fgets(STDIN);
// echo calculateToMinutes($param);

// DB接続
$link = mysqli_connect('db', 'book_log', 'pass', 'book_log');
if (!$link) {
    echo 'ERROR: DB接続に失敗しました。' . PHP_EOL;
    echo 'Error debugging: ' . mysqli_connect_error() . PHP_EOL;
    exit;
} else {
  echo 'DB接続に成功しました。' . PHP_EOL;
}

$sql = <<<EOT
INSERT INTO study_logs (
    study_date,
    study_time,
    content,
    rate,
    goal
) VALUES (
    '2022-08-31',
    '1:40',
    'テキスト版Webアプリの作成',
    '100',
    'Webアプリの作成'
)
EOT;

// バリデーション
// if (preg_match()) {

// }


$result = mysqli_query($link, $sql);
if (!$result) {
    echo 'Error: 学習ログの登録に失敗しました。' . PHP_EOL;
    echo 'Debugging Error: ' . mysqli_error($link) . PHP_EOL;
} else {
  echo '学習ログを登録しました。' . PHP_EOL;
  // mysqli_free_result($result);
}


$sql = 'SELECT study_date, study_time, content, rate, goal FROM study_logs';
$results = mysqli_query($link, $sql);

while ($study_log = mysqli_fetch_assoc($results)) {
  echo '--------------------' . PHP_EOL;
  echo '日にち: ' . $study_log['study_date'] . PHP_EOL;
  echo '学習時間: ' . $study_log['study_time'] . PHP_EOL;
  echo '学習内容: ' . $study_log['content'] . PHP_EOL;
  echo '目標達成度: ' . $study_log['rate']  . '%' . PHP_EOL;
  echo '明日の目標: ' . $study_log['goal'] . PHP_EOL;
}

mysqli_free_result($results);
mysqli_close($link);
