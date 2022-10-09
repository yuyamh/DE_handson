<?php
function validate($memo)
{

  $errors = [];

    // タイトルが200字以内かどうかチェック
  if (mb_strlen($memo['title']) > 200) {
      $errors['title'] = 'タイトルは200文字以内で入力してください。';
  }

  // 内容が2000字以内かどうかチェック
  if (mb_strlen($memo['content']) > 1000) {
    $errors['content'] = 'メモ内容は1000字以内で入力してください。';
  }

  return $errors;
}

function dbConnect() {
  $link = mysqli_connect('db', 'book_log', 'pass', 'book_log');
  if (!$link) {
    echo 'Error: データベースへの接続に失敗しました。' . PHP_EOL;
    echo 'Error Debugging: ' . mysqli_connect_error() . PHP_EOL;
  } else {
    echo 'データベースに接続しました。' . PHP_EOL;
  }

  return $link;
}

$memo = [];

function createMemo($link) {
  echo 'メモを登録してください。' . PHP_EOL;
  echo 'タイトル：';
  $memo['title'] = rtrim(fgets(STDIN));
  echo '内容：';
  $memo['content'] = rtrim(fgets(STDIN));

  $validated = validate($memo);
  if (count($validated) > 0) {
    foreach ($validated as $error) {
      echo $error . PHP_EOL;
    }
    return;
  }


  $sql = <<<EOT
  INSERT INTO memos (
    title,
    content
  ) VALUES (
    "{$memo['title']}",
    "{$memo['content']}"
  )
  EOT;

  $result = mysqli_query($link, $sql);
  if (!$result) {
    echo 'Error: メモの追加に失敗しました。' . PHP_EOL;
    echo 'Error Debugging: ' . mysqli_error($link) . PHP_EOL;
  } else {
    echo 'メモの登録が完了しました。' . PHP_EOL . PHP_EOL;
  }


}


function listMemos($link) {
    $sql = 'SELECT title, content FROM memos';
    $results = mysqli_query($link, $sql);
    echo 'メモを表示します。' . PHP_EOL;
    echo '--------------------' . PHP_EOL;


    while ($memo = mysqli_fetch_assoc($results)) {
      echo 'タイトル：' . $memo['title'] . PHP_EOL;
      echo '内容：' . $memo['content'] . PHP_EOL;
      echo '--------------------' . PHP_EOL;
    }

    mysqli_free_result($results);
  }

$link = dbConnect();

while (true) {
  echo '1. メモを登録する' . PHP_EOL;
  echo '2. メモを表示する' . PHP_EOL;
  echo '9. アプリケーションを終了する' . PHP_EOL;
  echo '番号を選択してください：';
  $num = trim(fgets(STDIN));
  if ($num === '1') {
      // メモの登録
      createMemo($link);
  } elseif ($num === '2') {
      // メモの表示
      listMemos($link);
  } elseif ($num === '9') {
      // アプリを終了させる
      echo 'アプリケーションを終了します。' . PHP_EOL;
      mysqli_close($link);
      break;
  } else {
      echo '1, 2, 9の中から選択してください。' . PHP_EOL;
  }
}
