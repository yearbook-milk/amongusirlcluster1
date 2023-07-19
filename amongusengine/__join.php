<?php 
require_once "datareader.php";
if (load(file_get_contents("gamedata.txt"))["game.status"] != "AWAITINGSTART") {
  echo "<fieldset style='color:red;border:1px solid red;'>NOTICE! The game on amongusirlcluster1.charleshu.repl.co has already started, you will not be able to join. Reload to check game status.</fieldset>";
}
?>

<h1>Join A Game</h1>
<form method="GET" action="/amongusengine/__find_player.php">
Enter a nickname (numbers and letters only, no space or special chars) <input name='nickname'>
<input type='submit'>
</form>

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
<hr><br><br>