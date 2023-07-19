<?php
declare(strict_types=1); 

require_once "datareader.php";
//assume that datareader.php has already been imported by the parent

// SERVES 2 FUNCTIONS: CYCLE THE "CLOCK" ANd RUN VARIOUS CHECKS, AS WELL AS RETURN A STATE FOR THE GAME/PARTICULAR PLAYER ON REQUEST
// THE "CLOCK" is cycled every time a player does something or attempts to pull new data from the server

//1. Check for a win condition
$gamedata = load(file_get_contents("gamedata.txt"));

//if the impostors have won by killing enough crewmates
if ((int)$gamedata['game.crewmateNumber'] <= (int)$gamedata['game.impostorNumber']) {
	$gamedata['game.status'] = "IMPOSTORVICTORY";
	file_put_contents("gamedata.txt", write($gamedata));
}
	

//if the crewmates have won by ejecting the impostors
if ((int)$gamedata['game.impostorNumber'] <= 0) {
	$gamedata['game.status'] = "CREWMATEVICTORY";
	file_put_contents("gamedata.txt", write($gamedata));
}

//if the crewmates have won by completing all their tasks AND no sabotage is active 
	if ( 
    (int)$gamedata['game.tasksDone'] >= (int)$gamedata['game.totalTasks'] &&
    $gamedata['game.sabotageState'] == "FALSE"
  ) {
	$gamedata['game.status'] = "CREWMATEVICTORY";
	file_put_contents("gamedata.txt", write($gamedata));
}

    var_dump($gamedata);

?>