<?php
declare(strict_types=1);
require_once "../datareader.php";


function unassign_existing_task(array &$playerdata, array &$gamedata, string $playername, string $taskID, bool $markcomplete): void {
	//B. Update the player info by removing that task 
	foreach ($playerdata["$playername.tasks[]"] as $key => $value) {
		if (explode(":", $value)[0] == $taskID) {
			unset($playerdata["$playername.tasks[]"][$key]);
		}
	}
	file_put_contents("../$_GET[issuer].txt", write($playerdata));
	
	if ($markcomplete) {
	//C. Update the game data by ticking the task counter up 1
	$gamedata["game.tasksDone"] = (string)((int)$gamedata["game.tasksDone"] + 1);
	file_put_contents("../gamedata.txt", write($gamedata));
	}
}

function assign_new_task(array &$playerdata, array &$gamedata, string $playername, string $taskstring): void {
	$playerdata["$playername.tasks[]"][] = $taskstring;
	file_put_contents("../$_GET[issuer].txt", write($playerdata));
}



// when the game master goes to create task lists, it will call upon all task handlers and ask them for information
// such as "common task?", "is this task a task someone can start with, or should i not give this task at first?"
class TaskInfoStruct {
	public bool $long;
	//FALSE for short, TRUE for long
	public bool $common;
	//FALSE for not common, TRUE for common
	public bool $first_task;
	//FALSE for sequence task, TRUE for first task
	
	
	
	public string $taskID;

	public string $taskDetail;
}
// the game does so by requiring the file and calling return_information, which returns a struct with the required information

?>