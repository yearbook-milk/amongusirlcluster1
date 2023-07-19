


<br>

<fieldset>
<a href='#' onclick='window.location = `amongusengine/__regenerate_game_image.php?playerno=${document.getElementById("quickgen").value}&impostor_index=${Math.floor(Math.random() * document.getElementById("quickgen").value)}`'>Generate and load a fresh new game image with these many players and 1 impostor: </a>
<input id='quickgen'>

  <p style='color: red'> ( !!! WARNING !!! ) This will erase the current game image and start the game from new.</p>
  <pre>To create a game, first generate a new image, then have everyone go to /amongusengine/ and join, then perform a connectivity test.
  First, press the emergency meeting button, and check if everyone's screen says "EMERGENCY MEETING".
  Then, press the button on to stop the emergency meeting. The game will be started then.
  </pre>
</fieldset>

<br>

<fieldset>
  <a href='amongusengine/event.php?event=emergencymeeting&issuer=__administrator&eventdata='>Call an emergency meeting</a>&nbsp;&nbsp;&nbsp;&nbsp;
  <a href='amongusengine/event.php?event=emergencymeetingfinish&issuer=__administrator&eventdata='>Stop the emergency meeting</a>
</fieldset>

<br>

<fieldset>
<a href='#' onclick='window.location = `amongusengine/event.php?event=killplayer&issuer=__administrator&eventdata=target=${document.getElementById("kill").value}`'>Kill this player: </a>
<input id='kill'>

</fieldset>


<br>

<fieldset>

  Current game objects: <br>
    <pre>

<?
chdir("amongusengine");
var_export(glob("*.txt"), FALSE);
?>
</fieldset>

<br>

      
<fieldset>

Vitals: <br>
<table>
<?
require_once "datareader.php";
foreach (glob("*.txt") as $key => $value) {
  $v1 = explode(".", $value)[0];
  if ($value != "gamedata.txt" && load(file_get_contents($value))["$v1.type"] == "PLAYER") {
    $ud = load(file_get_contents($value));
    echo "<tr><td>$v1</td><td>" . $ud["$v1.status"] . "</td></tr>";
  }
}
?>
</table>
</fieldset>


<br>

<fieldset>
  Game-wide Object: <pre>
<?
echo file_get_contents("gamedata.txt");
?></pre>
</fieldset>

<style>
body {
	background-color: #222;
	font-family: Courier New;
	color: white;
}

button {
	background-color: #0000;
}

a {
  color: #AA00AA;
}


input {
  background-color: #0000;
  border: 1px solid #FFF;
  color: red;
}
</style>