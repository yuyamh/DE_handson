<?php

// DB接続
function dbConnect() {

    $link = mysqli_connect('db', 'book_log', 'pass', 'book_log');
    if (!$link) {
        echo 'Error: DB接続に失敗しました。' . PHP_EOL;
        echo 'Error debugging: ' . mysqli_connect_error() . PHP_EOL;
        exit;
    }

  return $link;
}


// 全角スペースを半角スペースに置換する
function mbtrim($str) {
   return preg_replace("/(^\s+)|(\s+$)/u", "", $str);
}

// 学習時間を◯時間◯分表示を分表示へ変換する
function calculateToMinutes($param)
{
    $params = array_map('intval', explode(':', $param));

    $calculateToMinutes = $params[0] * 60 + $params[1];

    return $calculateToMinutes;
}

// 学習時間を◯時間◯分表示へ変換するメソッドを書く
function calculateToHour ($param) {
    $params[0] = floor($param / 60);
    $params[1] = $param % 60;

    $calculateToHour = implode('時間', $params) . '分';

    return $calculateToHour;
}

function createLog($link) {
    echo '学習ログを入力してください。' . PHP_EOL;

    $log = [];

    // 日にち
    echo '日にち(記入例: 2022-04-26) : ';
    $log['date'] = trim(mbtrim(fgets(STDIN)));

    // 学習時間、「◯:◯」で入力、分表示のために後でバリデーション処理
    echo '学習時間：';
    $log['studyTime'] = trim(mbtrim(fgets(STDIN)));

    // 学習内容
    echo '学習内容：';
    $log['content'] = trim(mbtrim(fgets(STDIN)));

    // 目標達成度
    echo '目標達成度(0〜100) : ';
    $log['rate'] = trim(mbtrim(fgets(STDIN)));

    // 明日の目標
    echo '明日の目標：';
    $log['goal'] = trim(mbtrim(fgets(STDIN)));

    // バリデーション
    $validated = validate($log);
    if (count($validated) > 0) {
      foreach ($validated as $error) {
        echo $error;
      }
      return;
    }

    // バリデーション後に分表示に直す
    $calculated = calculateToMinutes($log['studyTime']);


    // DBへ保存する
    $sql = <<<EOT
INSERT INTO study_logs (
    study_date,
    study_time,
    content,
    rate,
    goal
) VALUES (
    "{$log['date']}",
    "{$calculated}",
    "{$log['content']}",
    "{$log['rate']}",
    "{$log['goal']}"
)
EOT;

    $result = mysqli_query($link, $sql);
    if (!$result) {
      echo 'Error: 学習ログの登録に失敗しました。' . PHP_EOL;
      echo 'Debugging Error: ' . mysqli_error($link) . PHP_EOL;
    } else {
      echo '学習ログを登録しました。' . PHP_EOL;
    }

    return $result;
}

function listLogs($link) {
    $sql = 'SELECT id, study_date, study_time, content, rate, goal FROM study_logs';
    $results = mysqli_query($link, $sql);

    while ($study_log = mysqli_fetch_assoc($results)) {
      echo '--------------------' . PHP_EOL;
      echo 'ID: ' . $study_log['id'] . PHP_EOL;
      echo '日にち: ' . $study_log['study_date'] . PHP_EOL;
      echo '学習時間: ' . calculateToHour($study_log['study_time']) . PHP_EOL;
      echo '学習内容: ' . $study_log['content'] . PHP_EOL;
      echo '目標達成度: ' . $study_log['rate']  . '%' . PHP_EOL;
      echo '明日の目標: ' . $study_log['goal'] . PHP_EOL;
    }

    mysqli_free_result($results);
}

// 学習時間の合計を表示する
function showTotalStudyTime($link){
    $total = 0;
    // DBからカラム名'study_time'の列をSUMで取り出す
    $sql = 'SELECT study_time FROM study_logs';
    // 取り出した結果を表示する
    $result = mysqli_query($link, $sql);

    while ($study_times = mysqli_fetch_assoc($result)) {
        foreach ($study_times as $study_time) {
            $total += $study_time;
        }
    }

    echo "\n";
    echo '学習時間 合計: ' . calculateToHour($total) . PHP_EOL;
    echo '--------------------' . PHP_EOL;

    mysqli_free_result($result);
}

// 日付チェック(validate()で使用)
function dateCheck($param) {
    list($year, $month, $date) = explode('-', $param);
    return checkdate($month, $date, $year);
}

// バリデーション
function validate($log) {
    $errors = [];

    // 日にちのチェック
    if (!mb_strlen($log['date'])) {
        $errors['date'] = 'Error: 日付を入力してください。'  . PHP_EOL;
    } elseif (!preg_match('/^(20[0-9]{2})\-(0[1-9]{1}|1[0-2]{1})\-(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/', $log['date'])) {
        $errors['date'] = 'Error: 日付を正しく入力してください。' . PHP_EOL;
    } elseif (!dateCheck($log['date'])) {
        $errors['date'] = 'Error: 存在しない日付です。' . PHP_EOL;
    }

    // 学習時間のチェック
    if (!mb_strlen($log['studyTime'])) {
        $errors['studyTime'] = 'Error: 学習時間を入力してください。' .  PHP_EOL;
    } elseif (!preg_match('/^([1-9]){0,1}([0-9]{1})\:([0-5]{1})([0-9]{1})$/', $log['studyTime'])) {
        $errors['studyTime'] = 'Error: 学習時間は「時間:分」の形式で入力してください(例：1:40)。' . PHP_EOL;
    }

    // 学習内容のチェック
    if (mb_strlen($log['content']) > 200) {
        $errors['content'] = 'Error: 学習内容は200文字以内で入力してください。' . PHP_EOL;
    } elseif (!mb_strlen($log['content'])) {
        $errors['content'] = 'Error: 学習内容を入力してください。' . PHP_EOL;
    }

  // 目標達成度のチェック(0から100の間であるかチェックする)
    if (empty($log['rate']) && $log['rate'] !== '0') {
        $errors['rate'] = 'Error: 目標達成度を入力してください。' . PHP_EOL;
    } elseif (!is_numeric($log['rate'])) {
       $errors['rate'] = 'Error: 目標達成度は0から100の整数で入力してください。' . PHP_EOL;
    } elseif ($log['rate'] < 0 || $log['rate'] > 100) {
        $errors['rate'] = 'Error: 目標達成度は0から100の整数で入力してください。' . PHP_EOL;
    }

  // 明日の目標のチェック
    if (mb_strlen($log['goal']) > 200) {
      $errors['goal'] = 'Error: 明日の目標は200文字以内で入力してください。' . PHP_EOL;
    } elseif (!mb_strlen($log['goal'])) {
      $errors['goal'] = 'Error: 明日の目標を入力してください。' . PHP_EOL;
    }

    return $errors;
}

function updateLog($link) {
    // 変更したいログのIDを入力してもらう
    echo '編集する学習ログのIDを入力してください : ';
    $update_id = trim(mbtrim(fgets(STDIN)));
    // バリデーション
    if (!mb_strlen($update_id)) {
        echo 'Error: 編集する学習ログのIDを入力してください。'  . PHP_EOL;
        return;
    } elseif (!is_numeric($update_id)) {
        echo 'Error: IDは半角数字で入力してください。' . PHP_EOL;
        return;
    }

    // 変更するデータを入力してもらう
    echo '変更用の学習ログを入力してください。' . PHP_EOL;

    $log = [];

    // 日にち
    echo '日にち(記入例: 2022-04-26) : ';
    $log['date'] = trim(mbtrim(fgets(STDIN)));

    // 学習時間、「◯:◯」で入力、後で分表示のためにバリデーション
    echo '学習時間：';
    $log['studyTime'] = trim(mbtrim(fgets(STDIN)));

    // 学習内容
    echo '学習内容：';
    $log['content'] = trim(mbtrim(fgets(STDIN)));

    // 目標達成度
    echo '目標達成度(0〜100) : ';
    $log['rate'] = trim(mbtrim(fgets(STDIN)));

    // 明日の目標
    echo '明日の目標：';
    $log['goal'] = trim(mbtrim(fgets(STDIN)));

    // バリデーション
    $validated = validate($log);
    if (count($validated) > 0) {
      foreach ($validated as $error) {
        echo $error;
      }
      return;
    }


    // バリデーション後に分表示に直す
    $calculated = calculateToMinutes($log['studyTime']);


    // 日時を照らし合わせてUpdate文実行
    // DBへ保存する
    $sql = <<<EOT
UPDATE study_logs
SET study_date = "{$log['date']}",
study_time = "{$calculated}",
content = "{$log['content']}",
rate = "{$log['rate']}",
goal = "{$log['goal']}"
WHERE id = {$update_id}
EOT;

    $result = mysqli_query($link, $sql);
    if (!$result) {
      echo 'Error: 学習ログの編集に失敗しました。' . PHP_EOL;
      echo 'Debugging Error: ' . mysqli_error($link) . PHP_EOL;
    } else {
      echo '学習ログを編集しました。' . PHP_EOL;
    }
}

function deleteLog($link) {
    // 削除する学習ログのIDを入力してもらう
    echo '削除する学習ログのIDを入力してください : ';
    $delete_id = trim(mbtrim(fgets(STDIN)));

    // バリデーション
    if (!mb_strlen($delete_id)) {
      echo 'Error: 削除する学習ログのIDを入力してください。' . PHP_EOL;
      return;
    } elseif (!is_numeric($delete_id)) {
      echo 'Error: IDは半角数字で入力してください。' . PHP_EOL;
      return;
    }
    // delete文を実行する
    $sql = "DELETE FROM study_logs WHERE id = {$delete_id}";
    // DBに保存する
    $result = mysqli_query($link, $sql);
    if (!$result) {
      echo 'Error: 学習ログの削除に失敗しました。' . PHP_EOL;
      echo 'Debugging Error: ' . mysqli_error($link) . PHP_EOL;
    } else {
      echo '学習ログを削除しました。' . PHP_EOL;
    }
}

$link = dbConnect();

while (true) {
    echo '1. 学習ログを登録する' . PHP_EOL;
    echo '2. 学習ログを表示する' . PHP_EOL;
    echo '3. 学習ログを編集する' . PHP_EOL;
    echo '4. 学習ログを削除する' . PHP_EOL;
    echo '9. アプリケーションを終了する' . PHP_EOL;
    echo '番号を入力してください : ';
    $num = trim(mbtrim(fgets(STDIN)));

      if ($num === '1')
      {
          $result = createLog($link);
      } elseif ($num === '2')
      {
          listLogs($link);
          showTotalStudyTime($link);
      } elseif ($num === '3')
      {
          updateLog($link);
      } elseif ($num === '4')
      {
          deleteLog($link);
      }
      elseif ($num === '9')
      {
          echo '学習記録アプリを終了します。' . PHP_EOL;
          mysqli_close($link);
          break;
      } else {
          echo '「1」「2」「3」「4」「9」のいずれかの番号を入力してください。' . PHP_EOL . PHP_EOL;
      }

}
