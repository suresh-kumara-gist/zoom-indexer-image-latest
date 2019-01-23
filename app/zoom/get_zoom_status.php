<?PHP
//Turn off warnings otherwise response will not be properly formed if shmop_open fails (eg indexer has finished and closed shared memory block)
error_reporting(E_ERROR);
require("zoom_defines.php");

$shm_id = shmop_open($MappedStatusKey, "w", 0, 0);

//If we failed to open the mapped memory block indexer may have finished, set status to finish
if($shm_id == false)
{
		header('Content-Type: text/xml');     
		echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
		echo "<response>\n";
		AddValue("IndexerStatus", $INDEXER_STATUS_STRING[$INDEXER_STATUS_FINISHED]);
		AddValue("CurrentFilePath", "");
		echo "</response>\n";
		exit;
}


$shm_size = shmop_size($shm_id);

	   
function AddValue($fieldname, $value)
{
	//Added a trim to try and remove instances where the XML parising of the status fails due to null chars
	echo "<statusvalue><target>".$fieldname."</target><value>". trim($value) ."</value></statusvalue>\n";	
}
	   
$shm_contents = shmop_read($shm_id, 0, $shm_size);


/* 
	 Have to specify array lengths or PHP doesn't seem to unpack them as expected 
 	 These are specified in ZoomStatus.h 
 	 ExtCount is an array of integers, had trouble unpakcing an array of strings (did not work as expected)
*/

 $MAX_PATH = 261;
 $NUMCHAR_32BIT = 12;
 $NUMCHAR_64BIT = 22;
 $NUMCHAR_TIME = 32;
 $MAXEXTENTIONS = 100;
 $MAX_DOWNLOAD_THREADS = 10;
 $STATUS_URLLEN = $URLLENGTH+1;


 $unpack_struct_str = 
 'iLockFlag/'.
 'iLogBufferLockFlag/'.
 'iPageDataLockFlag/'.
 'iSeqNum/'.
 'iCommand/'.
 'iIsIncremental/'.
 'iIsIncrementalUpdate/'.
 'iIncrementalNumPages/'.
 'iIncrementalPageDataSize/'.
 'iIndexerStatus/'.
 'iLogBufferSizeWritten/'.
 'iLogBufferSizeRead/'.
  'a'. $STATUS_URLLEN.'CurrentFilePath/'.
  'iDLThreadNum/'.
  'I'.$MAX_DOWNLOAD_THREADS.'DLThreadFileBytesDownloaded/'.
  'I'.$MAX_DOWNLOAD_THREADS.'DLThreadFileTotalBytes/'.
  'i'.$MAX_DOWNLOAD_THREADS.'DLThreadStatus/'.
  'a'.$MAX_DOWNLOAD_THREADS*($STATUS_URLLEN).'DLThreadFilePathBuffer/'.
 	'a'.$NUMCHAR_32BIT.'FilesIndexed/'.  
	'a'.$NUMCHAR_32BIT.'Errors/'.
	'a'.$NUMCHAR_32BIT.'Warnings/'.
	'a'.$NUMCHAR_32BIT.'BrokenLinks/'.
	'a'.$NUMCHAR_32BIT.'VisitedURLs/'.
	'a'.$NUMCHAR_32BIT.'QueuedURLs/'.
	'a'.$NUMCHAR_64BIT.'TotalBytes/'.
  'i'.$MAXEXTENTIONS.'ExtCount/'.
	'a'.$NUMCHAR_32BIT.'NoExtCount/'.
	'a'.$NUMCHAR_32BIT.'EmailCount/'.
	'a'.$NUMCHAR_32BIT.'NumUniqueWords/'.
	'a'.$NUMCHAR_32BIT.'AvgUniqueWordsPerPage/'.
	'a'.$NUMCHAR_32BIT.'StartPtCounter/'.
	'a'.$NUMCHAR_32BIT.'StartPtTotal/'.
	'a'.$NUMCHAR_TIME.'StartTime/'.
	'a'.$NUMCHAR_TIME.'ElapsedTime/'.
	'a'.$NUMCHAR_64BIT.'PeakUsedPhys/'.
	'a'.$NUMCHAR_64BIT.'PeakUsedVirt/'.
	'a'.$NUMCHAR_32BIT.'NumKeywords/'.
	'a'.$NUMCHAR_32BIT.'NumPages/'.
	'a'.$NUMCHAR_32BIT.'NumVariants/'.
	'a'.$NUMCHAR_32BIT.'NumVariantWarnings/'.	
	'a'.$NUMCHAR_32BIT.'NumSkipped/'.			
	'a'.$NUMCHAR_32BIT.'NumFiltered/'.
	'a'.$NUMCHAR_32BIT.'NumDownloaded/'.
	'a'.$NUMCHAR_32BIT.'NumTotalWords/'.
	'a'.$NUMCHAR_32BIT.'NumTotalAndDummyWords/'.
	'a'.$NUMCHAR_32BIT.'AvgWordsPerPage/'.
	'a'.$NUMCHAR_32BIT.'NumDummyWords/'.
	'a'.$NUMCHAR_32BIT.'NumPagesCurrentStartPt/'.
	'a'.$NUMCHAR_32BIT.'NumWordsCurrentPage/'.
	'a'.$NUMCHAR_32BIT.'NumSpellings/'.
	'a'.$NUMCHAR_32BIT.'NumExtensions/'.
	'a'.$NUMCHAR_32BIT.'NumRecommended/'.
	'a'.$NUMCHAR_32BIT.'SkippedReason';


//Unpack data into PHP variables
$status = unpack($unpack_struct_str, $shm_contents);

//Format into an XML reponse
//Currently save each fields as 
//<statusvalue>
//	<target> FieldName
//	<value> Value	
//</statusvalue>

	//Ouptut XML response header
	header('Content-Type: text/xml; charset=UTF-8');     
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache");
	echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
	echo "<response>\n";

	//Check current indexing status and update status string
	AddValue("IndexerStatus", $INDEXER_STATUS_STRING[$status["IndexerStatus"]]);

	//If waiting to quit might need to start returning immediately here as sometimes zoomengine will corrupt the status values and it will never finish
				
	$currentFilePathArray = explode("\x00", $status["CurrentFilePath"]);
	if (is_array($currentFilePathArray) && count($currentFilePathArray) > 0)
		AddValue("CurrentFilePath", htmlentities($currentFilePathArray[0]));

	///Currently skippign EXTcount field
	//AddValue("CurrentFilePath", $status["CurrentFilePath"]);
 
  AddValue("SeqNum", $status["SeqNum"]);
	AddValue("FilesIndexed", $status["FilesIndexed"]);
	AddValue("Errors", $status["Errors"]);
	AddValue("Warnings", $status["Warnings"]);
	AddValue("BrokenLinks", $status["BrokenLinks"]);
	AddValue("VisitedURLs", $status["VisitedURLs"]);
	AddValue("QueuedURLs", $status["QueuedURLs"]);
	AddValue("TotalBytes", number_format((intval($status["TotalBytes"]) / 1048576), 2, '.', ','));
	AddValue("NoExtCount", $status["NoExtCount"]);
	AddValue("EmailCount", $status["EmailCount"]);
	AddValue("NumUniqueWords", $status["NumUniqueWords"]);
	AddValue("AvgUniqueWordsPerPage", $status["AvgUniqueWordsPerPage"]);
	AddValue("StartPtCounter", $status["StartPtCounter"]);
	AddValue("StartPtTotal", $status["StartPtTotal"]);
	AddValue("StartTime", $status["StartTime"]);
	AddValue("ElapsedTime", $status["ElapsedTime"]);
	AddValue("PeakUsedPhys", $status["PeakUsedPhys"]);
	AddValue("PeakUsedVirt", $status["PeakUsedVirt"]);
	AddValue("NumKeywords", $status["NumKeywords"]);
	AddValue("NumPages", $status["NumPages"]);
	AddValue("NumVariants", $status["NumVariants"]);
	AddValue("NumVariantWarnings", $status["NumVariantWarnings"]);
	AddValue("NumSkipped", $status["NumSkipped"]);
	AddValue("NumFiltered", $status["NumFiltered"]);
	AddValue("NumDownloaded", $status["NumDownloaded"]);
	AddValue("NumTotalWords", $status["NumTotalWords"]);
	AddValue("AvgWordsPerPage", $status["AvgWordsPerPage"]);
	AddValue("NumDummyWords", $status["NumDummyWords"]);
	AddValue("NumPagesCurrentStartPt", $status["NumPagesCurrentStartPt"]);
	// Removed due to possible invalid values, not currently used 
	//	AddValue("NumWordsCurrentPage", $status["NumWordsCurrentPage"]);
	AddValue("NumSpellings", $status["NumSpellings"]);
	AddValue("NumExtensions", $status["NumExtensions"]);
	AddValue("NumRecommended", $status["NumRecommended"]);
	AddValue("SkippedReason", $status["SkippedReason"]);

	$filepathArray = explode("\x00", $status["DLThreadFilePathBuffer"],  $MAX_DOWNLOAD_THREADS);
	$filepathArrayCount = count($filepathArray);

	if ($filepathArrayCount == 10)
	{
		// check for last field containing the rest of the string crammed into it
		$tmpNullPos = strpos($filepathArray[9], "\x00");
		if ($tmpNullPos >= 0)
			$filepathArray[9] = substr($filepathArray[9], 0, $tmpNullPos);
	}


	for ($i = 1; $i <= $MAX_DOWNLOAD_THREADS; $i++)
	{
	
		if ($i > $status["DLThreadNum"])
		{
			AddValue("THREAD".$i."_Status", $HTTPSESSION_STATUS_STRING[0]);
				AddValue("THREAD".$i."_Progress",  "");
			AddValue("THREAD".$i."_CurrentFilePath", "");
			
		}
		else
		{
			AddValue("THREAD".$i."_Status", $HTTPSESSION_STATUS_STRING[$status["DLThreadStatus".$i]]);
			$totalbytes = $status["DLThreadFileTotalBytes".$i];

			$bytesDownloaded = $status["DLThreadFileBytesDownloaded".$i];
			$bytesStr = "";
			if ($bytesDownloaded < 1000)
				$bytesStr = $bytesDownloaded . " bytes";
			else if ($bytesDownloaded < 1000000)
				$bytesStr = round($bytesDownloaded / 1024, 0) . " KB";
			else
				$bytesStr = round($bytesDownloaded / 1048576, 0) . " MB";
			
			AddValue("THREAD".$i."_Progress",  $bytesStr);
			
			AddValue("THREAD".$i."_CurrentFilePath", htmlentities($filepathArray[$i-1]));
		}
	}
	
	echo "</response>\n";
	//End response


?>


