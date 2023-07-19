<?php
require_once "../datareader.php";

	$gamedata2 = load(file_get_contents("../gamedata.txt"));
	
	if ($gamedata2["game.sabotageState"] != "FALSE") {
		$gamedata2["game.status"] = "IMPOSTORVICTORY";
		file_put_contents("../gamedata.txt", write($gamedata2));
	}
	die("response=OK HANDLEDBY GENERATOR_FIRE_ALTERNATE_CHECK");
?>