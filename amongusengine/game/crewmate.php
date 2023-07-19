<script src="https://unpkg.com/html5-qrcode"></script>
<script src="/amongusengine/game/datareader.js"></script>


<?php
require_once "../datareader.php";

if (empty($_GET['playerID']) || file_exists("../$_GET[playerID].txt") == FALSE ) {
	$_GET['playerID'] = '';
  die("INVALID OR MISSING PLAYER ID FIELD (HTTP GET, 'playerID')");
}

  $udata = load(file_get_contents("../$_GET[playerID].txt"));
if ($udata["$_GET[playerID].role"] != "CREWMATE") {
  die("THIS USER IS NOT A CREWMATE (ROLE: ".$udata["$_GET[playerID].role"].")");
}

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

<h1 hidden id='id'><?= $_GET['playerID'] ?></h1>

<div style='text-align:center'>
<button onclick="divSelect('tasklist')" style='border: 1px solid lightblue; font-size: 30px; font-family: monospace; color: lightblue'>Tasklist</button>
<button onclick="divSelect('interface')" style='border: 1px solid lightgreen; font-size: 30px; font-family: monospace; color: lightgreen'>Use</button>
</div>
<br>
<br>

<div hidden id='emergency_meeting' style='width:100%; height:100%; background-color: #222; position: absolute; left:0 ; top:0; color: red'>
  <h1>!!! EMERGENCY MEETING !!!</h1><hr>
  Issued by <b id='emergency_meeting_issued_by'>[[ waiting for player and game data to be returned to client ]]</b><br>
</div>

<div hidden id='dead' style='width:100%; height:100%; background-color: #222; position: absolute; left:0 ; top:0; color: red'>
  <h1>You have been killed.</h1><hr>All your tasks have been completed automatically.
</div>

<div hidden id='crewmate_w' style='width:100%; height:100%; background-color: #222; position: absolute; left:0 ; top:0; color: cyan'>
  <h1>Victory</h1>
</div>

<div hidden id='impostor_w' style='width:100%; height:100%; background-color: #222; position: absolute; left:0 ; top:0; color: red'>
  <h1>Defeat</h1>
</div>

<div hidden id='inactive' style='width:100%; height:100%; background-color: #222; position: absolute; left:0 ; top:0; color: light_gray'>
  <h1>Game Inactive</h1><hr>
  The game is not yet started, is suspended or is not running at all.
</div>

<div hidden id='sab' style='width:50%; height:50%; background-color: #222; color: orange; margin: auto'>
  <h1>!!! SABOTAGE WARNING !!!</h1>
  <hr>
  Type: <b id='sab_type'>[[ waiting for player and game data to be returned to client ]]</b><br>
  Instructions: <br><pre id='sab_instructions'>[[ waiting for player and game data to be returned to client ]]</pre>
</div>


<div id='tasklist' style='display: none'>

<div>
<h2 style='color: lightblue;'>Total Tasks Completed:</h2><br>
<b style='color: lightblue' id='tasks-done'>[[ waiting for player and game data to be returned to client ]]</b>
<b style='color: lightblue'> out of </b>
<b style='color: lightblue' id='tasks-total'>[[ waiting for player and game data to be returned to client ]]</b>

<br><br>

<h2 style='color: lightblue;'>My Tasks:</h2><br>
<pre style='color: lightblue' id='listoftasks'>
[[ waiting for player and game data to be returned to client ]]
</pre>

<button hidden id='do_all_tasks' style='border: 1px solid lightblue; font-size: 30px; font-family: monospace; color: lightblue' onclick='doAllTasks()' style='color:green'>Finish All Tasks As A Dead Person</button>
  
<pre id='listoftasks_consoleoutput'>
</pre>

</div>
</div>




<div id='interface' style='display:flex; justify-content: center; gap: 15px;'>

	<div id='qr-module' style='border: 1px solid lightgreen; padding: 5px'>
	<h2 style='text-align: center; color: lightgreen'>Camera</h2>
	<div id="qr-reader" style=''></div>
	<div id="qr-options">
	<select id='camselect'>
	</select>
	<br>
	<br>
	<button onclick='startScanning()' style='color:white'> [ Intialize ] </button>
	</div>
	<p style='color:lightgreen;'>
	TO INITIALIZE YOUR SCANNER<br>
	1. Choose a device from the drop-down menu. Choose correctly, as the chosen camera cannot be changed later.<br>
	2. Click [ Intialize ], then wait for Javascript to start the camera.<br>
	<br><br>
	TO INTERACT WITH IN-GAME OBJECTS<br>
	Hold your camera up such that the QR code is clearly visible and takes up the majority of the frame. Keep making minor adjustments until the scanner is able to read the code.<br>
	</p>
	</div>
	
	
	<div style='border: 1px solid lightgreen; padding: 5px;text-align: center;'>
		<h2 style='text-align: center; color: lightgreen'>Interface</h2>
		<br>
		<br>
		<iframe style='height:100vh; width: 85vh;' id='iframe0' src=''></iframe>
	</div>

</div>

<script>

window.lastGameState = ""
function refreshGameAndPlayerData() {
  // we first load the game data to see if anything has happened
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      gameinfo = read(xhttp.responseText);
      console.log(gameinfo);
      // first, we check the state of the game and bring up the relevant screen
      if (gameinfo["game.status"] == "CREWMATEVICTORY") { divSelect("crewmate_w") }
      if (gameinfo["game.status"] == "IMPOSTORVICTORY") { divSelect("impostor_w") }
      if (gameinfo["game.status"] == "EMERGENCYMEETING") { 
        divSelect("emergency_meeting") 
        if (gameinfo["game.taskBarUpdates"] == "ALWAYS" || gameinfo["game.taskBarUpdates"] == "AFTERMEETINGONLY") {
          document.getElementById("tasks-done").innerHTML = gameinfo["tasksDone"]
          document.getElementById("tasks-total").innerHTML = gameinfo["totalTasks"]
        }
        if (window.lastGameState != "EMERGENCYMEETING") {
        document.getElementById("sfx").src = "/amongusengine/assets/e_meeting_beep.wav";
        document.getElementById("sfx").play();
        }
        window.lastGameState = "EMERGENCYMEETING";
      }
      if (gameinfo["game.status"] == "LIVE") { 
        if (window.lastGameState != "LIVE") { divSelect("tasklist") }
      }
      if (gameinfo["game.status"] == "AWAITINGSTART") { divSelect("inactive") }
      window.lastGameState = gameinfo["game.status"]
      // second, we update the task bar, if such setting is applicable
      if (gameinfo["game.taskBarUpdates"] == "ALWAYS") {
        document.getElementById("tasks-done").innerHTML = gameinfo["game.tasksDone"]
        document.getElementById("tasks-total").innerHTML = gameinfo["game.totalTasks"]
      }
      // third, we update the user's task list 
      window.currentasks = []
      tasks = gameinfo[`${document.getElementById("id").innerHTML}.tasks[]`]
      document.getElementById("listoftasks").innerHTML = ""
      for (i of tasks) {
        document.getElementById("listoftasks").innerHTML += "[" + i.split(":")[0] + "] ";
        window.currentasks.push( i.split(":")[0] )
        if (i.split(":").length > 1) {
          document.getElementById("listoftasks").innerHTML += i.split(":")[1];
        }
        document.getElementById("listoftasks").innerHTML += "\n";
      }
      // fourth, we check if a sabotage has been started
      console.log(gameinfo["game.sabotageState"])
      if (gameinfo["game.sabotageState"] != "FALSE") {
        document.getElementById("sab").style.display = "block";
        document.getElementById("sab_type").innerHTML = gameinfo["game.sabotageName"]
        document.getElementById("sab_instructions").innerHTML = gameinfo["game.sabotageInstructions"]
        document.getElementById("sab").style.color = "#F00";
        setTimeout(function(){document.getElementById("sab").style.color = "orange"}, 250)
        document.getElementById("sfx").src = "/amongusengine/assets/sabalarm.ogg";
        document.getElementById("sfx").play();
      } else {
        document.getElementById("sab").style.display = "none";
      }

      // fifth, we check if the current user is dead, and if yes, delete their kill button
      console.log(gameinfo[`${document.getElementById("id").innerHTML}.status`])
      if (gameinfo[`${document.getElementById("id").innerHTML}.status`] == "DEAD" && window.lastplayerstate == "ALIVE") {
        document.getElementById("do_all_tasks").style.display = "inline";
        divSelect("tasklist");
        alert("You are dead. You are able unable to kill anyone; you can choose to either keep playing as a 'ghost' or sit out and mark all your tasks as done automatically.")
      }
      if (gameinfo[`${document.getElementById("id").innerHTML}.status`] == "ALIVE" && window.lastplayerstate == "DEAD") {
        document.getElementById("do_all_tasks").style.display = "none";
        divSelect("tasklist");
        alert("You have been brought back to life.") 
      }
      window.lastplayerstate = gameinfo[`${document.getElementById("id").innerHTML}.status`]

    }
  };
xhttp.open("GET", "/amongusengine/status.php", true);
xhttp.send();
}

function doAllTasks() {
  document.getElementById("listoftasks_consoleoutput").innerHTML = `Initializing subroutine to mark all tasks as complete...`;
  for (i of window.currentasks) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readystate = 4) {
        document.getElementById("listoftasks_consoleoutput").innerHTML += xhr.responseText;
        document.getElementById("listoftasks_consoleoutput").innerHTML += "\n";
      }
    }
    xhr.open("GET", `/amongusengine/event.php?event=task&issuer=${document.getElementById("id").innerHTML}&eventdata=taskID=${i}`, true);
    xhr.send();
  }
}
  
function divSelect(divid) {
	menus = ["tasklist", "interface", "emergency_meeting", "crewmate_w", "impostor_w", "dead", "inactive"];
	for (i = 0; i < menus.length; i++) {
		document.getElementById(menus[i]).style.display = "none";
	}
  if (divid == "interface") {
	document.getElementById(divid).style.display = "flex";
  } else {
    	document.getElementById(divid).style.display = "block";

  }
}

function onDecode(txt, result) {
	document.getElementById("iframe0").src = txt + "?playerID=" + document.getElementById("id").innerHTML;
}

Html5Qrcode.getCameras().then(devices => {
  if (devices && devices.length) {
    for (i = 0; i < devices.length; i++) {
		document.getElementById("camselect").innerHTML += `<option value=${devices[i].id}>${i}: ${devices[i].label}</option>`;
	}
  }
}).catch(err => {
  // handle err
  alert("Error in Html5QrCode.getCameras()")
});

const reader = new Html5Qrcode("qr-reader");
function startScanning() {
document.getElementById("qr-options").hidden = true;
reader.start(document.getElementById("camselect").value, {qrbox: { width: 250, height: 250 }},  (text, result) => {onDecode(text,result)}, (error) => {}).catch((error) => {alert("Error in reader.start() caught by .catch(): "+error)})
}

setInterval(refreshGameAndPlayerData, 3000)
refreshGameAndPlayerData()
</script>


<audio hidden controls id='sfx' src=''></audio>