<?php

require_once __DIR__ . '/lib/library.php';
require_once __DIR__ . '/lib/mysqli.php';

function listStudyLogs($link)
{
    $logs = [];
    $sql = 'SELECT study_date, study_time, content, rate, goal FROM study_logs ORDER BY id DESC;';
    $results = mysqli_query($link, $sql);

    while ($log = mysqli_fetch_assoc($results)) {
        $logs[] = $log;
    }

    return $logs;
}

$link = dbConnect();
$logs = listStudyLogs($link);

$title = '学習ログ一覧';
$content = __DIR__ . '/views/index.php';
include __DIR__ . '/views/layout.php';
