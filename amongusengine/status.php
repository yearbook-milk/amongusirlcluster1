<?php
header("Content-Type: text/plain");
foreach (glob("*.txt") as $key => $value) {
  echo file_get_contents($value);
  echo "\n\n\n";
}
?>