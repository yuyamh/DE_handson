<?php

echo '日にち(記入例：2022-04-26)：';
$param = trim(fgets(STDIN));

$result = preg_match('/^([1-9]){0,1}([0-9]{1})\:([0-5]{1})([0-9]{1})$/', $param);

if (!$result) {
  echo 'false';
} else {
  echo 'success';
}
