<?php
require_once "datareader.php";
$gamedata = load ( file_get_contents("gamedata.txt") );
if ($gamedata["game.status"] != "AWAITINGSTART") {
  die ("This game is currently underway.");
}
foreach (glob("*.txt") as $key => $value) {
  $v1 = explode(".", $value)[0];
  $userdata = load( file_get_contents($value) );
  if ($userdata["$v1.type"] == "PLAYER" && $userdata["$v1.hasAttachedUser"] == "FALSE") {
    echo "<p>Assignment successful! PID: $v1</p>";
    $userdata["$v1.hasAttachedUser"] = "TRUE";
    $userdata["$v1.nickname"] = $_GET['nickname'];
    file_put_contents($value, write($userdata));
    if ($userdata["$v1.role"] == "CREWMATE") {
      echo "<a href='/amongusengine/game/crewmate.php?playerID=$v1'>You are a crewmate! Click here to start.</a>";
    } else if ($userdata["$v1.role"] == "IMPOSTOR"){
      echo "<a href='/amongusengine/game/impostor.php?playerID=$v1'>You are an impostor! Click here to start.</a>";
    } else {
      echo ("This user object plays a role which is not supported by this version of Among Us IRL (1.0)");
    }
    die();
  } else {
    // pass this object up, either its not a player or it is already being occupied by someone
  }
}
die ("No available user objects were found.");

?>