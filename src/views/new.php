    <h2 class="h3 text-dark mb-3">読書ログ登録</h2>
    <form action="create.php" method="POST">
      <?php if (count($errors)) : ?>
        <ul class="text-danger">
          <?php foreach ($errors as $error) : ?>
            <li><?= $error; ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
      <div class="form-group">
        <label for="title">書籍名</label>
        <input type="text" id="title" name="title" class="form-control" value="<?= $review['title']; ?>" autocomplete="off">
      </div>
      <div class="form-group">
        <label for="author">著者名</label>
        <input type="text" id="author" name="author" class="form-control" value="<?= $review['author']; ?>" autocomplete="off">
      </div>

      <div class="form-group">
        <p class="mb-2">読書状況</p>
        <div>
          <div class="form-check-inline">
            <input type="radio" id="status1" name="status" class="form-check-input" value="未読" checked=<?= ($review['status'] === '未読') ? 'checked' : "" ?>>
            <label class="form-check-label" for="status1">未読</label>
          </div>
          <div class="form-check-inline">
            <input type="radio" id="status2" name="status" class="form-check-input" value="読んでいる" <?= ($review['status'] === '読んでいる') ? 'checked' : '' ?>>
            <label class="form-check-label" for="status2">読んでいる</label>
          </div>
          <div class="form-check-inline">
            <input type="radio" id="status3" name="status" class="form-check-input" value="読了" <?= ($review['status'] === '読了') ? 'checked' : '' ?>>
            <label class="form-check-label" for="status3">読了</label>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label for="score">評価(5点満点の整数)</label>
        <input type="number" min="1" max="5" id="score" name="score" class="form-control" value="<?= $review['score']; ?>" autocomplete="off">
      </div>
      <div class="form-group"><label for="summary">感想</label>
        <textarea type="textarea" id="summary" name="summary" class="form-control" rows="10"><?= $review['summary']; ?></textarea>
      </div>
      <button class="btn btn-primary" type="submit">登録する</button>
    </form>
