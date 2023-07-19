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

<fieldset>

Vitals: <br>
<table>
  <tr> 
  <td>ID</td>
  <td>Name</td>
  <td>Status</td>
  </tr>
<?
chdir("..");
require_once "datareader.php";
foreach (glob("*.txt") as $key => $value) {
  $v1 = explode(".", $value)[0];
  if ($value != "gamedata.txt" && load(file_get_contents($value))["$v1.type"] == "PLAYER") {
    $ud = load(file_get_contents($value));
    echo "<tr><td>$v1</td><td>" . $ud["$v1.nickname"] . "</td><td>" . $ud["$v1.status"] . "</td></tr>";
  }
}
?>
</table>
</fieldset>

<script>
  setTimeout(function(){window.location.reload()}, 3000);
</script>