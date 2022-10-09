<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- hrefは呼び込み先からのパスの指定になっている（create.php, new.php） -->
  <link rel="stylesheet" href="stylesheets/css/app.css">
  <title><?= $title ?></title>
</head>

<body>
    <div class="container">
        <?php include $content; ?>
    </div>
</body>
