<?php
$time_pre = microtime(true);

require_once "datareader.php";

class TaskInfoStruct {
	public string $long;
	//FALSE for short, TRUE for long
	public string $common;
	//FALSE for not common, TRUE for common
	public string $first_task;
	//FALSE for sequence task, TRUE for first task
	
	public string $taskID;

	public string $taskDetail;

  function __construct($taskID) {
    $taskData = load(file_get_contents($taskID . ".txt"));
    $this->long = $taskData["$taskID.long"];
    $this->common = $taskData["$taskID.common"];
    $this->first_task = $taskData["$taskID.first_task"];
    $this->taskID = $taskID;
    $this->taskDetail = $taskData["$taskID.taskDetail"];
  }

}

echo "
<h1>Automatic Game Image Generation</h1>
playernumber(playerno)=$_GET[playerno], tobeimpostor(impostor_index)=$_GET[impostor_index]
";


// 0. Load all the tasks
$potentialTasks = array();
chdir("task_data");
foreach (glob("*.txt") as $key => $value) {
  $potentialTasks[] = new TaskInfoStruct(explode(".", $value)[0]);
}
chdir("..");

function give_random_first_task($potentialTasks, $longshort, $common, $dontpick) {
  $longshort = $longshort == "LONG" ? "TRUE" : "FALSE"; 
  $common = $common == "COMMON" ? "TRUE" : "FALSE";
  $latch = TRUE;
  while ($latch) {
    $picked_task = $potentialTasks[ array_rand($potentialTasks) ];
    if ($picked_task->long == $longshort && $picked_task->common == $common && $picked_task->first_task == "TRUE" && !in_array($picked_task->taskID, $dontpick)) {
      return $picked_task;
    }
  }
}


echo "<h1>Task List</h1><hr>
<table>
<tr>
  <td>TASK ID</td>
  <td>LONG?</td>
  <td>COMMON?</td>
  <td>FIRST TASK?</td>
  </tr>
";
foreach ($potentialTasks as $key=>$value) {
  echo "<tr>
  <td>$value->taskID</td>
  <td>$value->long</td>
  <td>$value->common</td>
  <td>$value->first_task</td>
  </tr>";
}
echo "</table>";



// 1. Erase all the players that are currently exist, and also load some game creation data
echo("<h1>PLAYER object removal</h1><hr>");
foreach (glob("*.txt") as $key => $value) {
  $v1 = explode(".", $value)[0];
  if ($value != "gamedata.txt" && load(file_get_contents($value))["$v1.type"] == "PLAYER") {
    unlink($value);
    echo("$value<br>");
  }
}

$gcdata = load(file_get_contents("game_creation_data.txt"));
$commons = array();
for ($i = 0; $i < (int)$gcdata["game_creation_data.numberCommonTasks"]; $i++) {
  $alreadyplucked = array();
  $taskObj = give_random_first_task($potentialTasks, "SHORT", "COMMON", $alreadyplucked);
  $commons[] = "$taskObj->taskID:$taskObj->taskDetail";
  $alreadyplucked[] = $taskObj->taskID;
}

echo "<h1>Common Tasks for this Generation</h1><hr><pre>";
echo var_export($commons, TRUE);
echo "</pre>";

// 2. Create a specified number of people
$number_of_tasks = 0;
echo "<h1>Players</h1><hr>";

for ($i = 0; $i < (int)$_GET["playerno"]; $i++) {
  $playerData = array();
  $playerData["player_$i.type"] = "PLAYER";
  $playerData["player_$i.status"] = "ALIVE";
  $playerData["player_$i.nickname"] = "(No Nickname)";
  $playerData["player_$i.lastKill"] = "1";
  $playerData["player_$i.hasAttachedUser"] = "FALSE";
  if ( (int)$_GET["impostor_index"] == (int)$i ) {
    $playerData["player_$i.role"] = "IMPOSTOR";
    $playerData["player_$i.tasks[]"] = array();
  } else {
    $playerData["player_$i.role"] = "CREWMATE";
    $playerData["player_$i.tasks[]"] = $commons;
    for ($j = 0; $j < (int)$gcdata["game_creation_data.numberShortTasks"]; $j++) {
      $alreadyplucked = array();
      $taskObj = give_random_first_task($potentialTasks, "SHORT", "NONCOMMON", $alreadyplucked);
      $playerData["player_$i.tasks[]"][] = "$taskObj->taskID:$taskObj->taskDetail";
      $alreadyplucked[] = $taskObj->taskID;
    }
    for ($j = 0; $j < (int)$gcdata["game_creation_data.numberLongTasks"]; $j++) {
      $alreadyplucked = array();
      $taskObj = give_random_first_task($potentialTasks, "LONG", "NONCOMMON", $alreadyplucked);
      $playerData["player_$i.tasks[]"][] = "$taskObj->taskID:$taskObj->taskDetail";
      $alreadyplucked[] = $taskObj->taskID;
    }
  }
  echo "<pre>" . var_export($playerData, TRUE) . "</pre><br><br>";
  file_put_contents("player_$i.txt", write($playerData));
}




// 3. Set up gamedata.txt to include all the players, the updated task count, current settings, emergency meeting and sab off, and set to LIVE
$numCrewmates = (int)$_GET['playerno'] - 1;
$tbu = $gcdata["game_creation_data.taskBarUpdates"];
$kcd = $gcdata["game_creation_data.killCoolDown"];
$playerArrayString = "";
for ($i = 0; $i < (int)$_GET["playerno"]; $i++) {
  $playerArrayString .= "player_$i,";
}
$playerArrayString = substr($playerArrayString, 0, -1);

$totalTasks = ( (int)$gcdata["game_creation_data.numberLongTasks"] * ((int)$_GET["playerno"] - 1) ) + ( (int)$gcdata["game_creation_data.numberCommonTasks"] * ((int)$_GET["playerno"] - 1) ) + ( (int)$gcdata["game_creation_data.numberShortTasks"] * ((int)$_GET["playerno"] - 1) );

$gdata = "game.type=LIVEGAMEDATA;
game.status=AWAITINGSTART;
game.sabotageState=FALSE;
game.sabotageName=None;
game.sabotageStartTime=1;
game.sabotageInstructions=None;
game.tasksDone=0;
game.totalTasks=$totalTasks;
game.taskBarUpdates=$tbu;
game.crewmateNumber=$numCrewmates;
game.impostorNumber=1;
game.playerArray[]=$playerArrayString;
game.killCoolDown=$kcd;
game.emergencyMeetingState=FALSE;
game.emergencyMeetingCaller=None;";

echo "<h1>gamedata</h1><hr><pre>$gdata</pre>";
file_put_contents("gamedata.txt", $gdata);
echo "<h1>game_creation_data</h1><hr><pre>" . var_export($gcdata, TRUE) . "</pre>";


$time_post = microtime(true);
echo "Generation complete in " . (1000 * ($time_post - $time_pre)) . " ms" 
  ?>


<style>
body {
	background-color: #333;
	font-family: Courier New;
	color: white;
}

button {
	background-color: #0000;
}

</style>
