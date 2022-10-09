  <div class="container my-5">
    <h2 class="h3 text-dark">学習ログの登録</h2>
    <form action="create.php" method="POST">
      <?php if (count($errors)) : ?>
        <ul class="text-danger">
          <?php foreach ($errors as $error) : ?>
            <li><?= $error; ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
      <div class="form-group">
        <label for="date">日付</label>
        <input class="form-control" type="date" id="date" name="date" value="<?= $log['date']; ?>">
      </div>

      <div class="form-group">
        <label for="study_time">学習時間</label>
        <div class="form-row">
          <div class="col">
            <select class="form-control" id="study_time" name="study_time[]">
              <?php $i = 0; ?>
              <?php while ($i <= 12) : ?>
                <option value="<?= $i; ?>" <?php $i == $log['study_time'][0] ? print 'selected' : print ''; ?>><?= $i; ?>時間</option>
                <?php $i++; ?>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="col">
            <select class="form-control" id="study_time" name="study_time[]">
              <?php $i = 0; ?>
              <?php while ($i <= 59) : ?>
                <option value="<?= $i; ?>" <?php $i == $log['study_time'][1] ? print 'selected' : print ''; ?>><?= $i; ?>分</option>
                <?php $i++; ?>
              <?php endwhile; ?>
            </select>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label for="content">学習内容</label>
        <textarea class="form-control" id="content" name="content" placeholder="例) PHP レッスン6 ..."><?= $log['content'] ?></textarea>
      </div>

      <div class="form-group">
        <label for="rate">目標達成度</label>
        <select class="form-control" id="rate" name="rate">
          <?php $i = 0; ?>
          <?php while ($i <= 100) : ?>
            <option value="<?= $i; ?>" <?php $i == $log['rate'] ? print 'selected' : print ''; ?>><?= $i; ?>%</option>
            <?php $i++; ?>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="form-group">
        <label for="goal">明日の目標</label>
        <textarea class="form-control" id="goal" name="goal" placeholder="例) PHP 書籍を1章読む ..."><?= $log['goal']; ?></textarea>
      </div>
      <input class="btn btn-primary" type="submit" value="登録する">
    </form>
  </div>
