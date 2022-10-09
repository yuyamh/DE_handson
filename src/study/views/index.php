<?php
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>学習ログの一覧</title>
</head>

<body>
  <h1 class="h2 my-4">学習ログ一覧</h1>
  <a href="new.php" class="btn btn-primary mb-4">学習ログを登録する</a>
  <main>
    <?php if (count($logs) > 0): ?>
    <h1 class="h4 mb-3">学習時間合計:&nbsp;<?= calculateToHour(showTotalStudyTime($link)); ?></h1>
        <?php foreach ($logs as $log): ?>
            <section class="card shadow-sm mb-4">
                <div class="card-body">
                  <h1 class="card-title h3"><?= h(convertStudyTime($log['study_date'])); ?></h1>
                  <h2 class="card-text h4"><?= h(calculateToHour($log['study_time'])); ?></h2>
                  <div>
                      学習内容 : <?= h($log['content']); ?>&nbsp;/&nbsp;
                      目標達成度 : <?= h($log['rate']); ?>%&nbsp;/&nbsp;
                      明日の目標 : <?= h($log['goal']); ?>
                  </div>
                </div>
            </section>
        <?php endforeach; ?>
      <?php else: ?>
          <p>まだ学習ログが登録されていません。</p>
    <?php endif; ?>
  </main>
</body>
</html>
