<?php
declare(strict_types=1);

require_once "task_library.php";
require_once "../datareader.php";



	function task_main(array &$playerdata, array &$gamedata): void {
	//delete the task that was assigned
	unassign_existing_task($playerdata, $gamedata, $_GET['issuer'], "enteridcode", TRUE);
	}

?>