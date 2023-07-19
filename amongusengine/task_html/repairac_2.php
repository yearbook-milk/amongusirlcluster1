<?php

if (empty($_GET['playerID']) || file_exists("../$_GET[playerID].txt") == FALSE ) {
	$_GET['playerID'] = '';
  die("INVALID OR MISSING PLAYER ID FIELD (HTTP GET, 'playerID'). This page must be loaded in the context of a player.");
}

?>
  <style>
    body {
      background-color: #111;
      color: white;
    }

    .green {
      color: lime;
    }

    .red {
      color: red;
    }

    .yellow {
      color: yellow;
    }

    .blue {
      color: cyan;
    }
  </style>
<pre id='console'>
Command Prompt
</pre>

<input style='color:white; border: 1px solid white; background-color: #0000; position: absolute; bottom: 10; width: 50%; font-size: 24px;' id='cmd' onchange='process()'>
<script>
  window.timesrun = 0;
  function process() {
    content = document.getElementById("cmd").value;
    contents = content.split(" ");
    cons = document.getElementById("console");
        cons.innerHTML += "<br><br>\n\n";

    if (contents[0] == 'clear') {
      cons.innerHTML = "";
    } 
    else if (contents[0] == 'connect') {
      cons.innerHTML += "Connecting to "+contents[1]+" on TCP port "+contents[2];
    }
    else if (contents[0] == 'status') {
      if (window.timesrun < 1) {
      cons.innerHTML += `
==== SYSTEM STATUS ====
Overall: <b class='red'>NOT RUNNING (ERROR)</b>
Desired Temperature: 68*F

INTAKE DAMPENERS --> INTAKE FAN --> FILTER 1 --> FILTER 2 --> HEATING SYSTEM --> COOLING SYSTEM --> OUTPUT FAN --> OUTPUT DAMPENERS
<b class='red'>[ERROR]</b>              <b class='green'>[OK, idle]</b>     <b class='red'>[ERROR]</b>      <b class='green'>[OK]</b>         <b class='green'>[OK, idle]</b>         <b class='green'>[OK, idle]</b>         <b class='red'>[ERROR]</b>        <b class='green'>[OK, opened]</b>

Error: Intake dampener power is disconnected.              
Error: Filter 1 needs replacement.
Error: Output fan power is disconnected.

(( If these errors have been fixed, use the "reset" command to reset and clear the errors. ))
      `;
    } else {
              cons.innerHTML += `
==== SYSTEM STATUS ====
Overall: <b class='cyan'>NOT RUNNING (IDLE)</b>
Desired Temperature: 68*F

INTAKE DAMPENERS --> INTAKE FAN --> FILTER 1 --> FILTER 2 --> HEATING SYSTEM --> COOLING SYSTEM --> OUTPUT FAN --> OUTPUT DAMPENERS
<b class='green'>[OK, opened]</b>         <b class='green'>[OK, idle]</b>     <b class='green'>[OK, idle]</b>      <b class='green'>[OK]</b>         <b class='green'>[OK, idle]</b>         <b class='green'>[OK, idle]</b>         <b class='green'>[OK, idle]</b>        <b class='green'>[OK, opened]</b>

The system is ready to start. Use the "startall" command to start the whole system.
      `;
    }
    }
    else if (contents[0] == 'reset') {
      window.timesrun += 1;
      cons.innerHTML += "All systems resetting...";
      setTimeout(function() {cons.innerHTML += "OK! Done. Try to start the AHU again usning 'startall'"}, 3000)
      
    }

    else if (contents[0] == 'startall' && window.timesrun < 1) {
      cons.innerHTML += "Attempting to start the air handler unit";
      setTimeout(function() {cons.innerHTML += "<b class='red'>ERROR! Unable to start the air handler unit due to remaining errors.</b>"}, 3000)
    }

    else if (contents[0] == 'startall' && window.timesrun > 0) {
      cons.innerHTML += "Attempting to start the air handler unit";
      setTimeout(function() {cons.innerHTML += "<b class='green'>Success! Task completed.</b>"}, 3000)

                  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && xhr.status == 200) {
      if (xhr.responseText.includes("OK")) {
        alert("OK! Task complete.")
      } else {
        alert("Error in sending complete task signal: "+xhr.responseText)
      }
    } else if (xhr.readyState == 4) {
      alert("HTTP error on sending complete task signal: "+xhr.responseText)
    }
  }
  xhr.open("GET", `/amongusengine/event.php?issuer=<?= $_GET['playerID']; ?>&eventdata=taskID=repairac_2&event=task`, true)
  xhr.send();

      
    }


  }
</script>