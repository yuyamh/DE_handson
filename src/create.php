<?php

require_once __DIR__ . '/lib/mysqli.php';

function createReview($link, $review)
{
  $sql = <<<EOT
INSERT INTO reviews (
    title,
    author,
    status,
    score,
    summary
) VALUES (
    "{$review['title']}",
    "{$review['author']}",
    "{$review['status']}",
    "{$review['score']}",
    "{$review['summary']}"
)
EOT;

  $result = mysqli_query($link, $sql);
  if (!$result) {
    error_log('Error: fail to create review');
    error_log('Error Debugging: ' . mysqli_error($link));
  }
}

function validate($review)
{
  $errors = [];

  // 書籍名
  if (!strlen($review['title'])) {
      $errors['title'] = '書籍名を入力してください。';
  } elseif (strlen($review['title']) > 100) {
     $errors['title'] = '書籍名は100文字以内で入力してください。';
  }

  // 著者名
  if (!strlen($review['author'])) {
      $errors['author'] = '著者名を入力してください。';
  } elseif (strlen($review['author']) > 100) {
      $errors['author'] = '著者名は100文字以内で入力してください。';
  }

  // 読書状況
  if (!strlen($review['status'])) {
    $errors['status'] = '読書状況を入力してください。';
  } elseif (!in_array($review['status'], ['未読', '読んでいる', '読了'])) {
    $errors['status'] = '読書状況は「未読」、「読んでいる」、「読了」のいずれかを選択してください。';
  }

  // 評価
  if (!strlen($review['score'])) {
    $errors['score'] = '評価を入力してください。';
  } elseif ($review['score'] < 1 || $review['score'] > 5) {
    $errors['score'] = '評価は1~5の整数で入力してください。';
  }

  // 感想
  if (!strlen($review['summary'])) {
      $errors['summary'] = '感想を入力してください。';
  } elseif (strlen($review['summary']) > 1000) {
      $errors['summary'] = '感想は1000文字以内で入力してください。';
  }



  return $errors;
}


// POSTされた読書ログを変数に格納する
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $review = [
    'title' => $_POST['title'],
    'author' => $_POST['author'],
    'status' => $_POST['status'],
    'score' => $_POST['score'],
    'summary' => $_POST['summary'],
  ];

  // バリデーションする
  $errors = validate($review);

  // もしバリデーションがなければ
  if (!count($errors)) {
    // データベースに接続する
    $link = dbConnect();
    // データベースに読書ログを保存する
    createReview($link, $review);
    // データベースと切断する
    mysqli_close($link);
    header('Location: index.php');
  }
}
// バリデーションがあれば
$title = '読書ログの登録';
$content = __DIR__ . '/views/new.php';
include 'views/layout.php';
