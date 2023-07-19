<script src="https://unpkg.com/html5-qrcode"></script>
<script src="/amongusengine/game/datareader.js"></script>

<?php
require_once "../datareader.php";
if (empty($_GET['playerID']) || file_exists("../$_GET[playerID].txt") == FALSE ) {
	$_GET['playerID'] = '';
  die("INVALID OR MISSING PLAYER ID FIELD (HTTP GET, 'playerID')");
}

  $udata = load(file_get_contents("../$_GET[playerID].txt"));
if ($udata["$_GET[playerID].role"] != "IMPOSTOR") {
  die("THIS USER IS NOT A IMPOSTOR (ROLE: ".$udata["$_GET[playerID].role"].")");
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
<button onclick="divSelect('tasklist')" style='border: 1px solid lightblue; font-size: 30px; font-family: monospace; color: lightblue'>Taskbar</button>
<button id='kill_button' onclick="divSelect('interface')" style='border: 1px solid crimson; font-size: 30px; font-family: monospace; color: crimson'>Kill</button>
<button onclick="divSelect('sabotage')" style='border: 1px solid orange; font-size: 30px; font-family: monospace; color: orange'>Sabotage</button>
</div>
<br>
<br>


<div hidden id='emergency_meeting' style='width:100%; height:100%; background-color: #222; position: absolute; left:0 ; top:0; color: red'>
  <h1>!!! EMERGENCY MEETING !!!</h1><hr>
  Issued by <b id='emergency_meeting_issued_by'>[[ waiting for player and game data to be returned to client ]]</b><br>
</div>

<div hidden id='dead' style='width:100%; height:100%; background-color: #222; position: absolute; left:0 ; top:0; color: red'>
  <h1>You have been killed.</h1><hr>Unlike the real Among Us, you can't sabotage as a dead impostor.
</div>

<div hidden id='crewmate_w' style='width:100%; height:100%; background-color: #222; position: absolute; left:0 ; top:0; color: cyan'>
  <h1>Defeat</h1>
</div>

<div hidden id='impostor_w' style='width:100%; height:100%; background-color: #222; position: absolute; left:0 ; top:0; color: red'>
  <h1>Victory</h1>
</div>

<div hidden id='inactive' style='width:100%; height:100%; background-color: #222; position: absolute; left:0 ; top:0; color: light_gray'>
  <h1>Game Inactive</h1><hr>
  The game is not yet started, is suspended or is not running at all.
</div>

<div hidden id='sab' style='width:50%; height:50%; background-color: #222; color: orange; margin: auto'>
  <h1>!!! SABOTAGE WARNING !!!</h1>
  <hr>
  Type: <b id='sab_type'>[[ waiting for player and game data to be returned to client ]]</b><br>
  Instructions: <br><b id='sab_instructions'>[[ waiting for player and game data to be returned to client ]]</b>
</div>



<div id='tasklist' style='display: none'>

<div>
<h2 style='color: lightblue;'>Total Tasks Completed:</h2><br>
<b style='color: lightblue' id='tasks-done'>[[ waiting for player and game data to be returned to client ]]</b>
<b style='color: lightblue'> out of </b>
<b style='color: lightblue' id='tasks-total'>[[ waiting for player and game data to be returned to client ]]</b>

<br><br>

<!--h2 style='color: lightblue;'>My Fake Tasks:</h2><br>
<pre style='color: lightblue'>
Boiler Room: Download Data
</pre-->

</div>
</div>




<div id='interface' style='display:flex; justify-content: center; gap: 15px;'>

	<div id='qr-module' style='border: 1px solid crimson; padding: 5px; text-align:center;'>
	<h2 style='text-align: center; color: crimson'>Mark Player as Dead</h2>
	<div id="qr-reader" style='width:50%;'></div>
	<div id="qr-options">
	<select id='camselect'>
	</select>
	<br>
	<br>
	<button onclick='startScanning()' style='color:crimson; border: 1px solid crimson; font-family: monospace; font-size: 24px;'> Intialize </button>
	</div>
	<p style='color:crimson;'>
	TO INITIALIZE YOUR SCANNER<br>
	1. Choose a device from the drop-down menu. Choose correctly, as the selected camera cannot be changed later.<br>
	2. Click [ Intialize ], then wait for Javascript to start the camera.<br>
	<br><br>
	TO MARK A PLAYER AS DEAD<br>
	Once your victim has been incapacitated, locate their Crewmate Identification Card and scan the QR code. Remember - if you don't scan the code, it doesn't count.
  </p>
	<p style='color: crimson;'>Your kill cooldown is currently <b id='kill-cooldown' style='color:green'>inactive.</b></p>
	</div>
	
	


</div>




<div style='display: none; color: orange' id='sabotage'>
<div>
<h2>Critical Sabotages</h2>
<hr>
<button onclick='sendInSabotage("generator_fire_alternate")' style='color:crimson; border: 1px solid crimson; font-family: monospace; font-size: 24px;'>Generator Fire</button>


<br>
<br>
<br>

<h2>Non-Critical Sabotages</h2>
<hr>
None available yet.
</div>
</div>

<script>
function sendInSabotage(sabid) {
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function() {

    if (this.readyState == 4) {
      if (this.responseText.includes("OK") == false) {
        alert("An error occured in attempting to sabotage: "+this.responseText);
      } else {
        alert("Sabotage complete.")
        setTimeout(function() {
          var xhr2 = new XMLHttpRequest();
          xhr2.open("GET", "/amongusengine/sabotage_data/"+sabid+"_check.php", true);
          xhr2.send();
        }, 30000)
      }
    }
    
  }
  xhr.open("GET", `/amongusengine/event.php?issuer=${document.getElementById("id").innerHTML}&event=sabotagestart&eventdata=sabotageID=${sabid}`, true);
  xhr.send();
  alert("Sabotage sent...")
  
}
function divSelect(divid) {
	menus = ["tasklist", "interface", "sabotage", "emergency_meeting", "crewmate_w", "impostor_w", "dead", "inactive"];
	for (i = 0; i < menus.length; i++) {
		document.getElementById(menus[i]).style.display = "none";
	}
	document.getElementById(divid).style.display = "flex";
}

function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

async function onDecode(txt, result) {
	//kill functionality later
	var xhr = new XMLHttpRequest()
  xhr.onreadystatechange = function() {
	//cooldown get functinality later
  if (xhr.readyState == 4 && xhr.responseText.includes("OK")) {
  	cooldown = 15;
  	document.getElementById("kill-cooldown").innerHTML = "active."
  	document.getElementById("kill-cooldown").style.color = "red"
    alert("OK! Player marked as dead.")
  	
  	setTimeout(function(){document.getElementById("kill-cooldown").innerHTML = "inactive."; document.getElementById("kill-cooldown").style.color = "green"}, cooldown * 1000);
  } else if (xhr.readyState == 4) {
    alert("An error occurred while attempting to mark a player as dead: "+this.responseText)
  }
  }
  xhr.open("GET", `/amongusengine/event.php?issuer=${document.getElementById("id").innerHTML}&event=killplayer&eventdata=target=${txt}`, true);
  xhr.send()
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
      // (since impostors are not issued tasks, this segment is commented out)
      /*
      tasks = gameinfo[`${document.getElementById("id").innerHTML}.tasks[]`]
      document.getElementById("listoftasks").innerHTML = ""
      for (i of tasks) {
        document.getElementById("listoftasks").innerHTML += i;
        document.getElementById("listoftasks").innerHTML += "\n";
      }
      */

      // fourth, we check if any sab has been started
      console.log(gameinfo["game.sabotageState"])
      if (gameinfo["game.sabotageState"] != "FALSE") {
        document.getElementById("sab").style.display = "block";
        document.getElementById("sab_type").innerHTML = gameinfo["game.sabotageName"]
        document.getElementById("sab_instructions").innerHTML = gameinfo["game.sabotageInstructions"]
                document.getElementById("sfx").src = "/amongusengine/assets/sabalarm.ogg";
        document.getElementById("sfx").play();
      } else {
        document.getElementById("sab").style.display = "none";
      }

      // fifth, we check if the current user is dead, and if yes, delete their kill button
      console.log(gameinfo[`${document.getElementById("id").innerHTML}.status`])
      if (gameinfo[`${document.getElementById("id").innerHTML}.status`] == "DEAD" && window.lastplayerstate == "ALIVE") {
        document.getElementById("kill_button").style.display = "none";
        divSelect("tasklist");
        alert("You are dead. You are able unable to kill anyone, however, you are still able to sabotage.")
      }
      if (gameinfo[`${document.getElementById("id").innerHTML}.status`] == "ALIVE" && window.lastplayerstate == "DEAD") {
        document.getElementById("kill_button").style.display = "inline";
        divSelect("tasklist");
        alert("You have been brought back to life.") 
      }
      window.lastplayerstate = gameinfo[`${document.getElementById("id").innerHTML}.status`]

    }
  };

xhttp.open("GET", "/amongusengine/status.php", true);
xhttp.send();
}

setInterval(refreshGameAndPlayerData, 3000)

  refreshGameAndPlayerData()
</script>

<audio hidden controls id='sfx' src=''></audio>