<?php
if (strlen($_GET['code']) > 6) {
  die("Code is too long");
}
sleep(2);
file_put_contents("code", $_GET['code']);
?>