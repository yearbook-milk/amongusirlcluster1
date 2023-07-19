<?php declare(strict_types=1);

function strip(string $string): string {
	return str_replace( array("\r", "\n", '\n', '\r'), '', $string );
}

function write(array $input): string {
	$toWrite = "";
	foreach($input as $key => $value) {
		//if this statement assigns a normal string or array
		if (substr($key, -2) == "[]") {
			$toWrite .= $key . "=";
			foreach($value as $key2 => $value2) {
				$toWrite .= $value2;
				$toWrite .= ",";
			}
			// if $value was a blank array, doing this will actually remove the equal sign off the end, which will cause a read error
			// so we only strip one char off the end when there was content in the array
			if (count($value) > 0) {
			$toWrite = substr($toWrite, 0, -1);
			}
			$toWrite .= ";\n";
		} else {
			$toWrite .= $key . "=" . $value . ";\n";
		}
	}
	
	//var_dump($toWrite);
	return $toWrite;
}

function load(string $string): array {
	//strip newlines
	$string = strip($string);
	
	//create a associative array to hold everything
	$return = array();
	
	//read $string
	foreach (explode(";", $string) as $key => $value) {
						
		$statement = explode("=", $value);

		//check if line is commented or empty
		if (strlen($value) == 0) {
			//do nothing
			$n = 4;
		} else {
			//read the "X = Y" statement

			//if the statement assigns a normal string
			if (substr($statement[0], -2) != "[]") {
				$return[ $statement[0] ] = $statement[1];
			}

			//if the statement assigns an array
			else {
				$toAppend = array(); 
				foreach (explode(",", $statement[1]) as $value2) {
					$toAppend[] = $value2;
				}
				$return[ $statement[0] ] = $toAppend;
			}
		}
	}
	
	return $return;
}

//var_dump(load(file_get_contents("gamedata.txt")))
?>