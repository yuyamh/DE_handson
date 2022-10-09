<?php

// require_once __DIR__ . 'mysqli.php';

// $link =

// エスケープ処理
function h($string)
{
     return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// 日付の整形
function convertStudyTime($time)
{
    // 曜日を日本語表示で取得する
    $day = [' (日)', ' (月)', ' (火)', ' (水)', ' (木)', ' (金)', ' (土)'];
    $today = date('w', strtotime($time));


    //「-」を「/」に変える
    $time = str_replace('-', '/', $time);

    return $time . $day[$today];
}

// 学習時間を◯時間◯分表示へ変換するメソッドを書く
function calculateToHour($param)
{
  $params[0] = floor($param / 60);
  $params[1] = $param % 60;

  $calculateToHour = implode('時間', $params) . '分';

  return $calculateToHour;
}

// 学習時間の合計を表示する
function showTotalStudyTime($link)
{
    $total = 0;
    // DBからカラム名'study_time'の列を取り出す
    $sql = 'SELECT study_time FROM study_logs';
    $result = mysqli_query($link, $sql);

    // 取り出した値をループで足していく
    while ($study_times = mysqli_fetch_assoc($result)) {
      foreach ($study_times as $study_time) {
        $total += $study_time;
      }
    }
    
    mysqli_free_result($result);
    return $total;
  }
