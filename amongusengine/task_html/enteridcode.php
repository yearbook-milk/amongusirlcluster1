<?php

if (empty($_GET['playerID']) || file_exists("../$_GET[playerID].txt") == FALSE ) {
	$_GET['playerID'] = '';
  die("INVALID OR MISSING PLAYER ID FIELD (HTTP GET, 'playerID'). This page must be loaded in the context of a player.");
}

?>

<script src="https://amongusirlcluster1.charleshu.repl.co/amongusengine/game/datareader.js"></script>


<h2>Enter your ID here: <b id='idin'></b></h2> 
<hr>
<table>
  <tr>
    <td> <button>1</button> </td>
    <td> <button>2</button> </td>
    <td> <button>3</button> </td>
    <td> <button>4</button> </td>
    <td> <button>5</button> </td>
    <td> <button>6</button> </td>
    <td> <button>7</button> </td>
    <td> <button>8</button> </td>
    <td> <button>9</button> </td>
    <td> <button>0</button> </td>    
  </tr>

  <tr>
    <td> <button>Q</button> </td>
    <td> <button>W</button> </td>
    <td> <button>E</button> </td>
    <td> <button>R</button> </td>
    <td> <button>T</button> </td>
    <td> <button>Y</button> </td>
    <td> <button>U</button> </td>
    <td> <button>I</button> </td>
    <td> <button>O</button> </td>
    <td> <button>P</button> </td>    
  </tr>

    <tr>
    <td> <button>A</button> </td>
    <td> <button>S</button> </td>
    <td> <button>D</button> </td>
    <td> <button>F</button> </td>
    <td> <button>G</button> </td>
    <td> <button>H</button> </td>
    <td> <button>J</button> </td>
    <td> <button>K</button> </td>
    <td> <button>L</button> </td>
  </tr>


    <tr>
    <td> <button>Z</button> </td>
    <td> <button>X</button> </td>
    <td> <button>C</button> </td>
    <td> <button>V</button> </td>
    <td> <button>B</button> </td>
    <td> <button>N</button> </td>
    <td> <button>M</button> </td>  
      <td>      <button>_</button> </td>
<td><button>.</button></td>


  </tr>
</table>
<br><br><hr>
<button id='rel' style='color:red'>↺</button>
<button id='ok' style='color:green'>OK</button>



<style>
body {
	background-color: #333;
	font-family: Courier New;
	color: white;
}

button {
	background-color: black;
  width: 45px;
  height: 45px;
  color: cyan;
  font-size: 24;
  font-family: monospace;
}

button:active {
  background-color: #333;
}



</style><br><br><br>
this is a MIRA Corporation application<br>
(c) 1992 all rights reserved

<script>
  var buttons = document.getElementsByTagName('button');
for (let i = 0; i < buttons.length; i++) {
    let button = buttons[i];
    if (button.style.display.color != "red") {
    button.onclick = function() {
      document.getElementById("idin").innerHTML += button.innerHTML;
    }
    }
}

document.getElementById("rel").onclick = function() {
  window.location.reload();
}


document.getElementById("ok").onclick = function() {
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
  xhr.open("GET", `/amongusengine/event.php?issuer=<?= $_GET['playerID']; ?>&eventdata=taskID=enteridcode&event=task`, true)
  xhr.send();
}



</script>