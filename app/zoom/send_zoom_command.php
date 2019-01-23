<?php

include "zoom_defines.php";

$cmd = intval($_GET["command"]);


$test = ftok($EngineExePath, chr($ProcessID));

//$shm_id = shmop_open(0x1234, "w", 0, 0);
$shm_id = shmop_open($MappedStatusKey, "w", 0, 0);
if ($shm_id === FALSE)
	exit("Failed to open shared memory: " . $MappedStatusKey . " " . $test);

$shm_size = shmop_size($shm_id);
//$shm_contents = shmop_read($shm_id, 0, $shm_size);
 
//If this was called with a paramter, eg a command, get and lock memory segment

$status["LockFlag"] =  shmop_read($shm_id, 0, 4);
$lockFlag = intval($status["LockFlag"]);
var_dump($lockFlag);

$timeout = 0; 
while($lockFlag != 0 && $timeout < 100) //1 second of sleeps for timeout for 10 seconds
{
	usleep (1000);
	$status["LockFlag"] =  shmop_read($shm_id, 0, 4);
	$lockFlag = intval($status["LockFlag"]);
	$timeout++;
}

print ("<br>timeout: $timeout<br>before timeout attempt:<br>");

//if we could get the memory lock
if($timeout != 10 && $lockFlag == 0)
{
	print("setting command...<br>");
	//Lock flag
		$status["IndexerStatus"] = 1;
		$packed = pack("i", $status["LockFlag"] );
		$wrote = shmop_write($shm_id, $packed, $LOCKFLAG_OFFSET);
		
	//Send command
// TODO: fix this, write it properly not just 4 bytes in !
		$status["Command"] = $_GET["command"];
		$packed = pack("i", $status["Command"] );
		$wrote = shmop_write($shm_id, $packed, $COMMAND_OFFSET);
	
	//Unlock
		$status["IndexerStatus"] = 0;
		$packed = pack("i", $status["LockFlag"] );
		$wrote = shmop_write($shm_id, $packed, $LOCKFLAG_OFFSET);
}
echo "<br>WROTE: " . $wrote;


?>


