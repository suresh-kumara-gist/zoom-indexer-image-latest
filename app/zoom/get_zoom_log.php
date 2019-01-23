<?PHP
//Turn off warnings otherwise response will not be properly formed if shmop_open fails (eg indexer has finished and closed shared memory block)
error_reporting(E_ERROR);
require("zoom_defines.php");

//Add an unpacked log line to a global array for later use
function AddLogLine(&$UnpackedLogLines, $type, $time, $value)
{
	//Convert time so we don't need to use javascript
	$line =  "<logline><type>" . $type . "</type><time>" . date("Y-m-d H:i:s", $time)  . "</time><value>". htmlentities($value) . "</value></logline>\n";
	array_push($UnpackedLogLines, $line);
}

$shm_id = shmop_open($MappedLogBufferKey, "w", 0, 0);

//If we failed to open the mapped memory block return
if($shm_id == false)
{
	header('Content-Type: text/xml');     
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache");  
	echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
	echo "<response> </response>\n";
	

	exit;
}

$shm_size = shmop_size($shm_id);  
$shm_contents = shmop_read($shm_id, 0, $shm_size);

//Close
shmop_close($shm_id);

$LogHeaderSize = 16;
$bytesRead = 0;

//Unpack entire buffer into array, only send back the last 25 lines 
$UnpackedLogLines = array();	

while(1)
{

	//Need to modify each unpack statement per line
	$unpack_struct_str = 
	 'x'.$bytesRead .'/'.
	  'iBufferLen/'.
	 'iType/'.
	 'iTime/'.
	 'iProgress/';

	
	//Need to unpack based on byte counts in header, first get next header
	$nextHeader = unpack($unpack_struct_str, $shm_contents); //+$bytesRead
	
	if($nextHeader["BufferLen"] == 0)
		break;
	
	$bytesRead += $LogHeaderSize;
	
	//Unpack actual log line now we have header
	$unpack_struct_str = 
	 'x'.$bytesRead .'/'.
	 'a'.$nextHeader["BufferLen"] .'Line/';
	
	$LogLine = unpack($unpack_struct_str, $shm_contents); //+$bytesRead
	
	//Format into an XML reponse
	//Currently save each log line as 
	//<logline><type>...</type><time>...</time><value>...</value</logline>
	
	$bytesRead += $nextHeader["BufferLen"];

	AddLogLine($UnpackedLogLines, $nextHeader["Type"], $nextHeader["Time"], $LogLine["Line"]);
		
	$numLines++;
}

//Send header of response
header('Content-Type: text/xml');   
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");    
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
echo "<response>";

//Print out the required lines
$numTotalLines = count($UnpackedLogLines);
$count = 0;

if($numTotalLines <= 25)
	$count = 0;	
else
	$count = $numTotalLines - 25;
		
		
while($count < $numTotalLines)
 	echo($UnpackedLogLines[$count++]);


//End response
echo "</response>\n";

?>


