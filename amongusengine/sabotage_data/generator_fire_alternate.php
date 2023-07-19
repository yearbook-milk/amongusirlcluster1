<?php 
require_once "../datareader.php";

function sabotage_main(array &$gamedata): void {
	//change sab state
	$gamedata["game.sabotageState"] = "SEVERE TIMED:30";
	$gamedata["game.sabotageName"] = "Generator Fire";
	$gamedata["game.sabotageStartTime"] = (string)time();
  $gamedata["game.sabotageInstructions"] = "GENERATORS......... SHUT DOWN (DO WITHIN 30 SECONDS)<br>PEOPLE REQUIRED.... 2";
	file_put_contents("../gamedata.txt", write($gamedata));
}