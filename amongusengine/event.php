<?php 
declare(strict_types=1); 
require_once "datareader.php";
ini_set('max_execution_time', '300');



//1. Check if all HTTP parameters are present
if (!isset($_GET["event"]) || !isset($_GET["eventdata"]) || !isset($_GET["issuer"]) ) {
	die("response.status=MISSING HTTP PARAMETERS;");
}

$event = $_GET['event'];
$eventdata = load($_GET['eventdata']);
$issuer = $_GET['issuer'];
$gamedata = load(file_get_contents("gamedata.txt"));



// this needs to be checked for before the live check, otherwise you won't be able to stop any emergency meetings
if ($event == "emergencymeetingfinish") {
	//Call the meeting immediately, as currently there is no limit to how many times a person can call meeting nor is there a timeout
	$gamedata["game.emergencyMeetingState"] = "FALSE";
		$gamedata["game.status"] = "LIVE";
	$gamedata["game.emergencyMeetingCaller"] = "None";
	file_put_contents("gamedata.txt", write($gamedata));
	//Start the emergency meeting handler, which will bring the meeting to a close after a designated amount of time
	//(or wait for the host to end the meeting, if the meeting time limit is set to <= 0)
  die("response.status=OK HANDLEDBY EVENT_PRE_LIVE_CHECK");
}
else if ($event == "emergencymeeting") {
	//Call the meeting immediately, as currently there is no limit to how many times a person can call meeting nor is there a timeout
	$gamedata["game.emergencyMeetingState"] = "TRUE";
	$gamedata["game.status"] = "EMERGENCYMEETING";
	$gamedata["game.emergencyMeetingCaller"] = $_GET["issuer"];
	file_put_contents("gamedata.txt", write($gamedata));
	//Start the emergency meeting handler, which will bring the meeting to a close after a designated amount of time
	//(or wait for the host to end the meeting, if the meeting time limit is set to <= 0)
  die("response.status=OK HANDLEDBY EVENT_PRE_LIVE_CHECK");
}


//2. Check if the game is active
if ($gamedata['game.status'] != "LIVE") {
	die("response.status=GAME ISNT LIVE;");
}





//3. Check if the player exists and that they're alive
if (!file_exists($_GET["issuer"] . ".txt")) {
	die("response.status=PLAYER DOESNT EXIST");
}
$playerdata = load(file_get_contents($_GET["issuer"] . ".txt"));



	



//4. Check which operation the user has requested
if ($event == "killplayer") {

	//A. Check that the target exists, is alive,  and that the kill cooldown has expired since the last kill
  if ((int)$playerdata["$_GET[issuer].lastKill"] + (int)$gamedata["game.killCoolDown"] >= time()) {
		die("response.status=KILLER TIMED OUT");
	}
  
  if (!file_exists("$eventdata[target].txt")) {
		die("response.status=TARGET DOESNT EXIST");
	}
	
  $targetedUserData = load(file_get_contents("$eventdata[target].txt"));

	if ($targetedUserData["$eventdata[target].status"] != "ALIVE") {
		die("response.status=TARGET ISNT ALIVE");
	}
	
	
	//B. Change the target's information
	$targetedUserData["$eventdata[target].status"] = "DEAD";
	file_put_contents("$eventdata[target].txt", write($targetedUserData));
	
	//C. Update the dead player ticker
	if ($targetedUserData["$eventdata[target].role"] == "CREWMATE") {
		$gamedata["game.crewmateNumber"] = (string)((int)$gamedata["game.crewmateNumber"] - 1);
	} else if ($targetedUserData["$eventdata[target].role"] == "IMPOSTOR") {
		$gamedata["game.impostorNumber"] = (string)((int)$gamedata["game.impostorNumber"] - 1);
	}
	file_put_contents("gamedata.txt", write($gamedata));
	
	//D. Reset this player's kill cooldown
	//The killer and the victim cannot be the same person, or else the victim won't actually die - they'll be overwritten as alive with this fn
	$playerdata["$_GET[issuer].lastKill"] = (string)time();
	file_put_contents("$_GET[issuer].txt", write($playerdata));


}

else if ($event == "sabotagestart") {
	//check that there isn't already a sabotage - in the real among us only one sab can go at the same time
	if ($gamedata["game.sabotageState"] != "FALSE") {
		die("response.status=SABOTAGE STATE !=FALSE");
	} 
	
	chdir("sabotage_data");
	//look for a relevant .php file to handle the sabotage (each one will have a different time and resolution system)
	if (!file_exists("$eventdata[sabotageID].php")) {
		die("response.status=SABOTAGE HANDLERFILE MISSING");
	}
	//check that the method exists to start the sabotage
	require "$eventdata[sabotageID].php";
	if (!function_exists("sabotage_main")) {
		die("response.status=SABOTAGE SABOTAGE_MAIN() MISSING");
	}
	//run the handler. the function accepts an array which will contain the info required to start a sabotage
	//this file is also responsible for modifying gamedata.txt as required
	sabotage_main($gamedata);
	chdir("..");
}

else if ($event == "sabotagestop") {
	//check that there was a sabotage to start with
	if ($gamedata["game.sabotageState"] == "FALSE") {
		die("response.status=SABOTAGE STATE ==FALSE");
	} 
	//reset everything back to normal
	$gamedata["game.sabotageState"] = "FALSE";
	$gamedata["game.sabotageName"] = "None";
	$gamedata["game.sabotageStartTime"] = (string)0;
	file_put_contents("gamedata.txt", write($gamedata));
	
	
}




else if ($event == "task") {
	//A. Check that the player has this task (impostors are issued no tasks and thus cannot interact with task menus)
	$taskID = $eventdata["taskID"];
	$hasTask = False;
	foreach ($playerdata["$_GET[issuer].tasks[]"] as $value) {
		if (explode(":", $value)[0] == $taskID) {
			$hasTask = True;
		}
	}
	
	if (!$hasTask) {
		die("response.status=PLAYER LACKS TASK#$taskID");
	}

	//B. Check if there is any handler for the task
	chdir("task_data");
	//look for a relevant .php file to handle the sabotage (each one will have a different time and resolution system)
	if (!file_exists("$eventdata[taskID].php")) {
		die("response.status=TASK HANDLERFILE MISSING");
	}
	//check that the method exists to handle the task
	require "$eventdata[taskID].php";
	if (!function_exists("task_main")) {
		die("response.status=TASK TASK_MAIN() MISSING");
	}
	//run the handler. the function accepts an array which will contain the info required to start a sabotage
	//this file is also responsible for modifying gamedata.txt as required
	task_main($playerdata, $gamedata);
	chdir("..");
}



else {
	die("response.status=NO HANDLER EXISTS");
}


//run a conditions check
if (!isset($_GET['norefresh'])) {
require "conditions_refresh.php";
}

die("response.status=OK HANDLEDBY EVENT");
?>