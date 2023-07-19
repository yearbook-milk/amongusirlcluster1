<?php

if (empty($_GET['playerID']) || file_exists("../$_GET[playerID].txt") == FALSE ) {
	$_GET['playerID'] = '';
  die("INVALID OR MISSING PLAYER ID FIELD (HTTP GET, 'playerID'). This page must be loaded in the context of a player.");
}

?>


<h1>Default Task Handler</h1>

<pre>
Task ID: <?=explode(".", explode("?", basename($_SERVER['REQUEST_URI']))[0])[0]?>

Player ID: <?=$_GET['playerID']?>

</pre>

<p>
</p>

<?php
$basename = explode(".", explode("?", basename($_SERVER['REQUEST_URI']))[0])[0];
echo "\n\nTo complete the task: click <a href='/amongusengine/event.php?issuer=$_GET[playerID]&event=task&eventdata=taskID=$basename'>here</a>";
?>