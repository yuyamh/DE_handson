<?php
require_once __DIR__ . '/lib/mysqli.php';

// 学習時間を◯時間◯分表示から分表示へ変換する
// function calculateToMinutes($time)
// {
//   $calculateToMinutes = $time[0] * 60 + $time[1];
//   return $calculateToMinutes;
// }

function createStudyLog($link, $log)
{
  $sql = <<<EOT
INSERT INTO study_logs (
    study_date,
    study_time,
    content,
    rate,
    goal
) VALUES (
    "{$log['date']}",
    "{$log['study_time']}",
    "{$log['content']}",
    "{$log['rate']}",
    "{$log['goal']}"
)
EOT;

  $result = mysqli_query($link, $sql);
  if (!$result) {
    error_log('Error: Fail to create study log');
    error_log('Debugging Error: ' . mysqli_error($link));
  }

  return $result;
}

function validate($log)
{
  $errors = [];

  // 日にちのチェック
  $dates = explode('-', $log['date']);
  if (!mb_strlen($log['date'])) {
    $errors['date'] = '日付を入力してください。';
  } elseif (count($dates) !== 3) {
    $errors['date'] = '日付を正しい形式で入力してください。';
  } elseif (!checkdate($dates[1], $dates[2], $dates[0])) {
    $errors['date'] = '正しい日付を入力してください。';
  }

  // 学習時間のチェック
  list($hours, $minutes) = $log['study_time'];
  if (!mb_strlen($hours) || !mb_strlen($minutes)) {
    $errors['study_time'] = '学習時間を入力してください。';
  } elseif (!is_numeric($hours) || !is_numeric($minutes)) {
    $errors['study_time'] = '学習時間は数字で入力してください。';
  } elseif (count(($log['study_time'])) !== 2) {
    $errors['study_time'] = '学習時間を正しく入力してください。';
  }

  // 学習内容のチェック
  if (mb_strlen($log['content']) > 200) {
    $errors['content'] = '学習内容は200文字以内で入力してください。';
  } elseif (!mb_strlen($log['content'])) {
    $errors['content'] = '学習内容を入力してください。';
  }

  // 目標達成度のチェック(0から100の間であるかチェックする)
  if (!is_numeric($log['rate'])) {
    $errors['rate'] = '目標達成度は0から100の整数で入力してください。';
  } elseif ($log['rate'] < 0 || $log['rate'] > 100) {
    $errors['rate'] = '目標達成度は0%から100%で入力してください。';
  }

  // 明日の目標のチェック
  if (mb_strlen($log['goal']) > 200) {
    $errors['goal'] = '明日の目標は200文字以内で入力してください。';
  } elseif (!mb_strlen($log['goal'])) {
    $errors['goal'] = '明日の目標を入力してください。';
  }

  return $errors;
}

// 学習時間を◯時間◯分表示から分表示へ変換する
function calculateToMinutes($arr_time)
{
  $calculatedToMinutes = $arr_time[0] * 60 + $arr_time[1];
  return $calculatedToMinutes;
}

// HTTPメソッドがPOSTだったら
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // POSTされた会社情報を変数に格納する
  $log = [
    'date' => $_POST['date'],
    // 'study_time' => calculateToMinutes($_POST['study_time']),
    'study_time' => $_POST['study_time'],
    'content' => $_POST['content'],
    'rate' => $_POST['rate'],
    'goal' => $_POST['goal']
  ];

  // バリデーションする
  $errors = validate($log);
  if (!count($errors)) {
    // バリデーションエラーがなければ
    $link = dbConnect();
    $log['study_time'] = calculateToMinutes($log['study_time']);
    createStudyLog($link, $log);
    mysqli_close($link);
    header("Location: index.php");
    // リダイレクト後は、下の処理は実行されない。
  }
  // バリデーションエラーがあれば
}


$title = '学習ログの登録';
$content = 'views/new.php';
include 'views/layout.php';
