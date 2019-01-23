<?php
require("zoom_defines.php");

$extraTxt = "";


//Get the current username and zoom version
$version = $FREE_EDITION;
$registeredNameRaw = "";
$execstr = "$EngineExePath -checkkeyonly  -p " . $ProcessID ;
$regInfoFP = popen($execstr, "r");
$ret = TRUE;
while($ret !== FALSE)
{
	$ret = fgets($regInfoFP, 128);
	
	if($ret !== FALSE)
		$registeredNameRaw .= $ret;
}
pclose($regInfoFP);

$versionStr = strstr($registeredNameRaw, "Version ");
if (strlen($versionStr) > 0)
{
	$versionDetails = explode("\n", $versionStr);
	if (!empty($versionDetails));
		$ZOOM_VERSION_STRING = $versionDetails[0];
}

$username = strstr($registeredNameRaw, "User: ");
if(strlen($username ) > 0)
{
	$registeredDetails = explode("\n", $username);
	if(!empty($registeredDetails))
	{
		
		if($registeredDetails[1] == $FREE_EDITION	)
		{
			$registeredDetails[0] = "";
			$registeredDetails[1] = "Free Edition";
		}
		else
		{
			$registeredDetails[0] = substr($registeredDetails[0], 6);
			
			if($registeredDetails[1] == $STANDARD_EDITION)
			{
				$registeredDetails[1] = "Standard Edition";
				$version = $PRO_EDITION;
			}
			else if($registeredDetails[1] == $PRO_EDITION)
			{
				$registeredDetails[1] = "Professional Edition";
				$version = "pro";
			}
			else if($registeredDetails[1] == $ENTERPRISE_EDITION)
			{
				$registeredDetails[1] = "Enterprise Edition";
				$version = $ENTERPRISE_EDITION;
			}
		}
	}
}
else
{
			$registeredDetails[0] = "";
			$registeredDetails[1] = "Free Edition";
}


if(!empty($registeredDetails))
{
	if(strlen($registeredDetails[0]) > 0)
		$extraTxt  = "$registeredDetails[1] licensed to: $registeredDetails[0]";
		else
			$extraTxt  = "$registeredDetails[1]";
}
if(isset($CurrentConfigPath))
		$extraTxt .= "<br> Zoom config file: $CurrentConfigPath";
		
	//Check that we are actually on a linux system (warning if otherwise)
	if(stripos(php_uname("s"), "Linux") === FALSE)
	{
		$extraTxt .= "<br><div class=error_msg>Warning: It appears you are trying to use the Linux version of Zoom on a non-Linux system (".php_uname("s") . "), indexer will not run.</div>";
	}

echo<<<HEADER
<div id="header">
	<div id="header_img">
		<img src="./images/zoom_main.png" alt="" />
	</div>
	<div id="header_text">
		<span id="heading">$ZOOM_HEADING_STRING</span><br>
		$ZOOM_VERSION_STRING<br>
		$extraTxt
	</div>
	<input type="hidden" value=$version id="zoom_version">
</div>
HEADER;
?>