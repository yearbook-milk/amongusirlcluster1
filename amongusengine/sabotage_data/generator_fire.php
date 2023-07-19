<?php 
require_once "../datareader.php";

function sabotage_main(array &$gamedata): void {
	//change sab state
	$gamedata["game.sabotageState"] = "SEVERE TIMED:30";
	$gamedata["game.sabotageName"] = "Generator Fire";
	$gamedata["game.sabotageStartTime"] = (string)time();
  $gamedata["game.sabotageInstructions"] = "GENERATORS......... SHUT DOWN (DO WITHIN 30 SECONDS)<br>PEOPLE REQUIRED.... 2";
	file_put_contents("../gamedata.txt", write($gamedata));
	
	//count down (we will now rely on clients to display the timer)
	for ($i = 30; $i >= 0; $i--) {
		sleep(1);
	} 
	
	//if the sabotage hasn't been fixed yet send the imp win message
	
	//load new gamedata and discard the one passsed to it at the start via ref (this is a case where passing by reference doesn't do anything -
    //it needs to reread the file after 30 seconds - 
	//and the $gamedata given to sabotage_main by event was from 30 seconds ago, not now)
	$gamedata2 = load(file_get_contents("../gamedata.txt"));
	
	if ($gamedata2["game.sabotageState"] != "FALSE") {
		$gamedata2["game.status"] = "IMPOSTORVICTORY";
		file_put_contents("../gamedata.txt", write($gamedata2));
	}
	die("response=OK HANDLEDBY GENERATOR_FIRE");
}