<?php
error_reporting(0);
date_default_timezone_set('America/New_York');


if (basename( $_SERVER['REQUEST_URI'] ) != "index.php") {
  header("Location: /amongusengine/sabotage_data/index.php");
  die();
}
  if ($handle = opendir('.')) {
    while (false !== ($file = readdir($handle))) {
      if ($file != "." && $file != ".." && is_dir($file) == false) {
		  
		$filectime = '' . date('d-M-Y H:i',filemtime($file));
		
        $thelist .= "<tr>
		<td style='padding: 5px;'>file</td>
		<td style='padding: 5px;'>$file</td>
		<td style='padding: 5px;'>$filectime</td>
		<td style='padding: 5px;'><a href='$file'>View</a></td>
		<td style='padding: 5px;'><a href='$file' download>Download</a></td>
		</tr>";
      }
      if ($file != "." && $file != ".." && is_dir($file) == true) {
		  
		  		$filectime = '' . date('d-M-Y H:i',filemtime($file));

        $thelist .= "<tr>
		<td style='padding: 5px;'>dir</td>
		<td style='padding: 5px;'>$file</td>
		<td style='padding: 5px;'>$filectime</td>
		<td style='padding: 5px;'><a href='$file'>View</a></td>
		<td style='padding: 5px;'>Forbidden: is_dir()</td>
		</tr>";
      }
    }
    closedir($handle);
  }

$thelist .= "<tr>
		<td style='padding: 5px;'>file</td>
		<td style='padding: 5px;'>..</td>
		<td style='padding: 5px;'></td>
		<td style='padding: 5px;'><a href='..'>Go</a></td>
		<td style='padding: 5px;'>Forbidden</td>
		</tr>";
?>
<h1>View directory <i><?php echo basename(getcwd()); ?></i></h1>
<ul><?php echo "<table>$thelist</table>"; ?></ul>

<script>
function f(){
var elements = document.getElementsByTagName("a")
for(let i = 0; i < elements.length; i++) {
elements[i].click();	
}

}
</script>
<style>
table, th, td {
  border: 1px solid white;
}
table {
  border-collapse: collapse;
  width:100%;
  color:lightgray;
}


body {
	background:black;
	font-family: "Arial Narrow", "Arial";
}

h1 {
	color:lightgray;
}

/* unvisited link */
a:link {
  color: lightblue;
}

/* visited link */
a:visited {
  color: lightblue;
}

/* mouse over link */
a:hover {
  background: lightblue;
  color: black;
}

/* selected link */
a:active {
  color: lightblue;
}	

p {
	color:lightgray
}

</style>
<hr><p>
<?php
echo("The current server time is: " . date('d-M-Y H:i:s') . "<br>");
echo("The current working directory is: " . getcwd());
?></p>