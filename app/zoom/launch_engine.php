<?php

header('Content-Type: text/xml; charset=UTF-8');     
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

include "zoom_defines.php";

$config = "";

if(isset($_GET["config"]))
	$config = $_GET["config"];
else
	print("Error: No config file specified\r\n");	

print("Launching engine...\r\n");

$output = "";
$retVar = ""; 

$config = escapeshellcmd($config);


//Check we can access /tmp (safer than using the temp folder where zoom is installed)
//If for some reason we can't then fall back to local temp folder
$tmpFileLocation = "/tmp";

if (is_writable($tmpFileLocation) == FALSE )
	$tmpFileLocation = $EngineExeDir . "/temp/zoomoutput";
else
	$tmpFileLocation .= "/zoomoutput";

$ret = exec($EngineExePath . " -autorun " . $config . " -p " . $ProcessID . " > " . $tmpFileLocation ." 2>&1 &");

//Short sleep to wait for engine to start/fail
sleep(1);

//Read tmp file
if(filesize($tmpFileLocation) > 0)
{
		$fp_zoomoutput = fopen($tmpFileLocation, "r");
		
		$line = fgets($fp_zoomoutput);

		if(stripos($line, "not found") !== false)
		{
			//exec couldn't find the zoom exectuable
			print("Error: Couldn't find Zoom executable at $EngineExePath");
		}
		else if(stripos($line, "permission") !== false)
		{
			print("Error: Unable to launch Zoom, invalid permissions");
		}
		else if(stripos($line, "shared libraries") !== false)
		{
			print("Error: Unable to launch Zoom, missing libraries were detected. You will need to install these libraries before Zoom can run.\n");
			print($line);
		}
		else if (stripos($line, "error") !== false)
		{
		//Error during launch, missing required file, memory segment already exists etc
			print($line);
		}
		else
			print("Success");
		fclose($fp_zoomoutput);
}
else
{
	print("Success");
}


?>
