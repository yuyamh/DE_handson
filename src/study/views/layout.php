<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- このファイル自体の呼び出し先であるstudy/new.phpからのパス指定となっている -->
  <link rel="stylesheet" href="stylesheets/css/app.css">
  <title><?= $title; ?></title>
</head>

<body>
    <h1>
        <a href="index.php" class="h2 text-dark d-block pl-4 pb-3 mt-3 shadow-sm">学習ログ</a>
    </h1>
    <div class="container">
        <?php include $content; ?>
    </div>
</body>

</html>
