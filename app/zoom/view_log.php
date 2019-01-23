<?php

//Supress all warnings
error_reporting(E_ERROR);

require("./zoom_defines.php");
include("./config_file.php");

global $UConfig;

//Need to open config file, get log location
$CurrentConfigPath = $EngineExeDir . "/default.zcfg";
if(isset($_GET["config"]))
{
	$CurrentConfigPath = $_GET["config"];
}


$cfgLoaded = LoadZCFGFile($CurrentConfigPath, false);
?>

<html>
	<head>
		<link rel="stylesheet" href="zoom.css" type="text/css">
		<style>
			#filters {
				width: 85%;
				margin:auto;
				margin-top: 20px;
				margin-bottom: 20px;
			}

			#filters table {
				margin:0px; padding:0px;
				width: 100%;
				border: 1px solid #000000;
				box-shadow: 10px 10px 5px #888888;				
			}

			#filter_header {
				background-color: #cccccc;
			}

			#backtotop {
				position: fixed;
				z-index: 1000;
				right: 0px;
				bottom: 0px;
			}
		</style>

		<script type="text/javascript">

			function filter( szClass, bShow )
			{
				var tempElements = document.querySelectorAll( "div." + szClass );
				for( var i = 0; i < tempElements.length; i++ )
				{
					if( bShow )
						tempElements[i].style.display = "";
					else
						tempElements[i].style.display = "none";
				}
			} 

			function filterShowAll( bShow )
			{
				var inputs = document.getElementsByTagName( "input" );
			
				for( var i = 0; i < inputs.length; i++ )
				{
					if( inputs[i].type == "checkbox" )
					{
						inputs[i].checked = bShow;
						activateEvent( inputs[i], "change" );
					}
				}						
			}

			function filterShowDefault()
			{
				var inputs = document.getElementsByTagName( "input" );
				var cbs = new Array( "indexed", "download", "error", "warning", "info", "init", "brokenlinks", "fileio", "upload", "filtered", "summary", "plugin" );

				for( var i = 0; i < inputs.length; i++ )
				{
					var bIsDefault = false;
					for( j = 0; j < cbs.length; j++ )
					{
						if( cbs[j] == inputs[i].id )
						{
							bIsDefault = true;
							break;
						}
					}
					if( bIsDefault )
						inputs[i].checked = true;
					else
						inputs[i].checked = false;
					
					activateEvent( inputs[i], "change" );
				}					
			}

			function activateEvent( item, actionShortName )
			{
				if( document.createEventObject )
				{
					item.fireEvent( "on" + actionShowName );
				}
				else
				{
					var evt = document.createEvent("HTMLEvents" );
					evt.initEvent( actionShortName, true, true );
					item.dispatchEvent(evt );
				}
			}

		</script>
	</head>
	<body onload="filterShowDefault()">
	<a name="top"></a>
	<div id="filters">
	<table>
		<tr>
			<td id="filter_header" colspan="6">Show message options</td>
		</tr>
		<tr>
			<td><button onclick="filterShowAll( true )">Show All</button></td>
			<td><input id="indexed" type="checkbox" onchange="filter( 'MSG_INDEXED', this.checked )" onchange="filter( 'MSG_INDEXED', this.checked )">Indexed</td>
			<td><input id="download" type="checkbox" onchange="filter( 'MSG_DOWNLOAD', this.checked )">Download</td>
			<td><input id="error" type="checkbox" onchange="filter( 'MSG_ERROR', this.checked )">Error</td>
			<td><input id="init" type="checkbox" onchange="filter( 'MSG_INIT', this.checked )">Initalization</td>
			<td><input id="brokenlinks" type="checkbox" onchange="filter( 'MSG_BROKENLINKS', this.checked )">Broken links</td>
		</tr>
		<tr>
			<td><button onclick="filterShowDefault()">Reset to default</button></td>
			<td><input id="skipped" type="checkbox" onchange="filter( 'MSG_SKIPPED', this.checked )">Skipped</td>
			<td><input id="queue" type="checkbox" onchange="filter( 'MSG_QUEUE', this.checked )">Queued</td>
			<td><input id="warning" type="checkbox" onchange="filter( 'MSG_WARNING', this.checked )">Warning</td>
			<td><input id="fileio" type="checkbox" onchange="filter( 'MSG_FILEIO', this.checked )">File I/O</td>
			<td><input id="upload" type="checkbox" onchange="filter( 'MSG_UPLOAD', this.checked )">Upload</td>
		</tr>
		<tr>
			<td><button onclick="filterShowAll(false)">Hide All</button></td>
			<td><input id="filtered" type="checkbox" onchange="filter( 'MSG_FILTERED', this.checked )">Filtered</td>
			<td><input id="startstop" type="checkbox" onchange="filter( 'MSG_STARTSTOP', this.checked )">Start/Stop</td>
			<td><input id="info" type="checkbox" onchange="filter( 'MSG_INFO', this.checked )">Information</td>
			<td><input id="summary" type="checkbox" onchange="filter( 'MSG_SUMMARY', this.checked )">Summary</td>
			<td><input id="plugin" type="checkbox" onchange="filter( 'MSG_PLUGIN', this.checked )">Plugin</td>
		</tr>
		<tr>		
			<td></td>
			<td><input id="debug" type="checkbox" onchange="filter( 'MSG_DEBUG', this.checked )">Debug</td>
		</tr>
</table>

		
		
			
		
	</div>

<?php

if($cfgLoaded == true) 
{ 
	//Check that a log file is being created
	if($UConfig->LogWriteToFile == 0)
	{
		echo "<div class=\"error_msg\"><b>No log file is selected to be created in this configuration</b></div>";
		return;	
	}
		
	
	$fp_log = fopen($UConfig->LogSaveToFilename, "r");
	
	//Check that file open succedded 
	if(!$fp_log)
	{
		echo "<div class=\"error_msg\"><b>Unable to load log file $UConfig->LogSaveToFilename (file not found or insufficient permissions)</b></div>";
		return;
	}
	
	while (!feof($fp_log))
	{
		$outputStr = "";
		$NextLine = fgets($fp_log, $MAX_CONFIG_LINELEN);	

		if(	$NextLine == FALSE)
			break;
		
		//Split line into Type | Time | Message
		$curline = explode( "|", $NextLine, 3);

		switch($curline[0])
		{
			case $MSG_INDEXED:
				$outputStr = "<div class=\"MSG_INDEXED\">";
			break;			
			case $MSG_DOWNLOAD:
				$outputStr = "<div class=\"MSG_DOWNLOAD\">";
			break;						
			case $MSG_UPLOAD:
				$outputStr = "<div class=\"MSG_UPLOAD\">";
			break;
			case $MSG_PLUGIN:
				$outputStr = "<div class=\"MSG_PLUGIN\">";
			break;
			case $MSG_ERROR:
				$outputStr = "<div class=\"MSG_ERROR\">";
			break;
			case $MSG_WARNING:				
				$outputStr = "<div class=\"MSG_WARNING\">";
			break;
			case $MSG_STARTSTOP:
				$outputStr = "<div class=\"MSG_STARTSTOP\">";
			break;
			case $MSG_INFO:
				$outputStr = "<div class=\"MSG_INFO\">";
			break;
			case $MSG_DEBUG:
				$outputStr = "<div class=\"MSG_DEBUG\">";
			break;
			case $MSG_SKIPPED:
				$outputStr = "<div class=\"MSG_SKIPPED\">";
			break;
			case $MSG_FILTERED:	
				$outputStr = "<div class=\"MSG_FILTERED\">";						
			break;
			case $MSG_INIT:				
				$outputStr = "<div class=\"MSG_INIT\">";
			break;
			case $MSG_FILEIO:
				$outputStr = "<div class=\"MSG_FILEIO\">";
			break;
			case $MSG_QUEUE:
				$outputStr = "<div class=\"MSG_QUEUE\">";
			break;
			case $MSG_SUMMARY:
				$outputStr = "<div class=\"MSG_SUMMARY\">";
			break;
			case $MSG_BROKENLINKS:
				$outputStr = "<div class=\"MSG_BROKENLINKS\">";
			break;
			default:
				$outputStr = "<div>";
			break;
				
			}
			
			$outputStr .= $curline[1] . " " . $curline[2];
			$outputStr .= "</div>\n";
			echo $outputStr;
		 
	}
	
	fclose($fp_log);
	
}
else
{
	echo "<div class=\"error_msg\"><b>Unable to load config file $CurrentConfigPath</b></div>";
}
				

?>

<div style="width:160px; vertical-align:middle">
	<a id="backtotop" href="#top">Back to Top<img src="images/skip_24.png"></a>
</div>
	
</body>
</html>
