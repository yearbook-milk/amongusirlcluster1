<?php

if (empty($_GET['playerID']) || file_exists("../$_GET[playerID].txt") == FALSE ) {
	$_GET['playerID'] = '';
  die("INVALID OR MISSING PLAYER ID FIELD (HTTP GET, 'playerID'). This page must be loaded in the context of a player.");
}

?>

This task is not implemented yet, and should not be issued to any users. If you have been issued this task, contact your game's administator immediately to rectify this issue.