<?php
header('Content-type: text/html; charset=UTF-8');    
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
	
require("zoom_defines.php");

$CurrentConfig = $EngineExeDir . "/default.zcfg";
if(isset($_GET["config"]))
{
	$CurrentConfig = $_GET["config"];
}

?>
<html>
	<head>
		<link rel="stylesheet" href="zoom.css" type="text/css">
		
		<script>
			
			var isPaused = false;
			var refresh = 0;
			var sendReqTimer = 0;

			function InitButtons()
			{
				if(document.getElementById("pause"))
					document.getElementById("pause").disabled =  true;
					
				if(document.getElementById("start"))
					document.getElementById("start").disabled = false;
					
				if(document.getElementById("stop"))
					document.getElementById("stop").disabled = false;
				
				if(document.getElementById("configure"))
					document.getElementById("configure").disabled = false;
			}
				
				//set a timeout so status is updated evvery second
				//window.document.onload
				window.onload = InitButtons();


				function SendHttpRequest(param, synchronous)
				{
					
					//Set shared status to command value
					if(window.XMLHttpRequest)
					{
				 		//IE7_, FireFox, Chrome, OPera, Safari
				 		xmlhttp = new XMLHttpRequest();
				 	}
				 	else
				 	{
				  		//IE 6, 5
				  		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				 	}					
				 	//Try synchrosous repsonse to get possible error message
					xmlhttp.open("GET", param, synchronous); //true
				 	xmlhttp.send();				 
				 	
				 	if(synchronous == false)
				 	{
				 		var response = xmlhttp.responseText;
				 		if(response.indexOf("Error") != -1)
				 		{
				 			alert(response);
				 			return false;
				 		}
					}
					return true;
				}


				function launchEngine()
				{
					
					//Wait for a synchronous response from the launch command to read any error messages
					var config = document.getElementById("config_filename").value; 
					
					if(config.length < 1)
					{
						alert("No configuration file is selected, please enter a valid configuration file path");
						return false;
					}
					return SendHttpRequest("launch_engine.php?config=" + config, false);
				}
				
				function SendStartCommand()
				{
					//Set status to empty
					document.getElementById("IndexerStatus").innerHTML = "";
					
					SendHttpRequest("send_zoom_command.php?command="+<?PHP echo $__CUSTOM_BUILD_ZOOM_CMD_START; ?>, true);
					//clearTimeout(sendReqTimer);
					pageTimeout();
				}
				
				
				//Call the send command php module to send a command to the indexer
				//Disable/enable buttons as appropriate
				function sendCommand(buttonID)
				{					
					var zoomCmd;
					
					if(buttonID == "start")
					{
						//Disable start
							document.getElementById("start").disabled = true;
							
						//Disable configure
							document.getElementById("configure").disabled = true;
						

						//Need to launch engine if not already running or just start/unpause command if running and in paused state
						if(isPaused == false)
						{
								
							if(launchEngine() == true)
							{
								//Enable pause and stop and condifure
								document.getElementById("pause").disabled = false;
								document.getElementById("stop").disabled = false;
							
								//sendReqTimer = setTimeout(SendStartCommand, 1000, true);	
								SendStartCommand();
	
							}	
							else
							{
								//Enable start
								document.getElementById("start").disabled = false;
								//Enable configure
								document.getElementById("configure").disabled = false;
								
							}		
						}
						else
						{
							//Enable pause and stop
							document.getElementById("pause").disabled = false;
							document.getElementById("stop").disabled = false;
							
								
							zoomCmd = <?PHP echo $__CUSTOM_BUILD_ZOOM_CMD_RESUME; ?>;
							SendHttpRequest("send_zoom_command.php?command="+zoomCmd);
							pageTimeout();
						}		
						return;
					}
					else if(buttonID == "pause")
					{
						zoomCmd = <?PHP echo $__CUSTOM_BUILD_ZOOM_CMD_PAUSE; ?>;
						//Disbale pause
						document.getElementById("pause").disabled =  true;
						//Enable start and stop
						document.getElementById("start").disabled = false;
						document.getElementById("stop").disabled = false;
						isPaused = true;
					}
					else if(buttonID == "stop")
					{
						zoomCmd = <?PHP echo $__CUSTOM_BUILD_ZOOM_CMD_STOP; ?>;
						//Disbale stop and pause
						document.getElementById("pause").disabled =  true;
						document.getElementById("stop").disabled = true;
						//Enable start
						document.getElementById("start").disabled = false;
						//Enable configure
						document.getElementById("configure").disabled = false;
					}
					SendHttpRequest("send_zoom_command.php?command="+zoomCmd);
				}
				
				function pageTimeout()
				{
					//Using set timeout so we can reset it after the XMLHttpRequest request has finished
					//So we don't get multipler equests at the same time
				 //setInterval("showStatus()", 1000); //Setinterval, executes over and over at specififed interval
				 	setTimeout("showStatus()", 1000);  //Settimeout, executes once after spoecified interval
				 		setTimeout("showLog()", 2000); //Start log timeout
				 // pageTimeout(); //call this function 
				}
			
			function getNodeValue(parent, tagName) 
			{ 
				var node = parent.getElementsByTagName(tagName)[0]; 
				return (node && node.firstChild) ? node.firstChild.nodeValue : false; 
			}
			
			function SendQuitMessage()
			{
				<?PHP
				echo "var ZOOM_CMD_QUIT = $__CUSTOM_BUILD_ZOOM_CMD_QUIT;";	
				?>
	
					//Need to send a quit message
					SendHttpRequest("send_zoom_command.php?command="+ZOOM_CMD_QUIT);
									
					//Disable stop and pause
					document.getElementById("pause").disabled =  true;
					document.getElementById("stop").disabled = true;
					//Enable start
					document.getElementById("start").disabled = false;
					//Enable configure
					document.getElementById("configure").disabled = false;
								
					//Set status to finished
					document.getElementById("IndexerStatus").innerHTML = "Finished";
			}	
		
			function getXMLResponse(xmlhttp, callBackFunctionStr)
			{
				var responseXML;
				if (!xmlhttp.responseXML)
				{
					var parser = new DOMParser();
					responseXML = parser.parseFromString(xmlhttp.responseText, "application/xml");
					if (!responseXML)
					{
						setTimeout(callBackFunctionStr, 1000);
						return;
					}
				}
				else
					responseXML = xmlhttp.responseXML;
				return responseXML.documentElement;
			}
			
			function showStatus()
				{				
								
//					alert("Showstatus entry");
				 var xmlhttp;
				
				 if(window.XMLHttpRequest)
				 {
				  //IE7_, FireFox, Chrome, Opera, Safari
				  xmlhttp = new XMLHttpRequest();
				 // alert("new XMLHttpRequest");
				 }
				 else
				 {
				  //IE 6, 5
				  xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				 //  alert("new ActiveXObject");
				 }
				
				 xmlhttp.onreadystatechange = function()
				 {
				 //	alert("xmlhttp.onreadystatechange");
				 //	alert(xmlhttp.readyState);
				  if(xmlhttp.readyState==4 && xmlhttp.status==200)
				  {

				  	//var response = xmlhttp.responseXML.documentElement;				  	
						var response = getXMLResponse(xmlhttp, "showStatus()");
				  	
				    var statusvalues = response.getElementsByTagName('statusvalue');
				    				    	
				    	  			
				    for(var i=0; i < statusvalues.length; i++)
				    {
				    		 var target = getNodeValue(statusvalues[i], 'target'); 
				    		 var value = getNodeValue(statusvalues[i], 'value');
				    		
				    		 if(target && value) 
				    		 {
				    		 	var currentElement = 	document.getElementById(target);
				    		 	if(currentElement)
				    		 		currentElement.innerHTML = value;				    			
				    		}
				    }
 
				   		
						  //Need to enable/disable buttons depending on status 
						  var curStatus = document.getElementById("IndexerStatus").innerHTML;

							
						  switch(curStatus)
							{
								//Indexing has finished / stopped
								case "":
								case "N/A":
								case "Partial index complete (stopped by user)":
								case "Indexing completed":
								case "Out of memory":
								//Disable stop and pause
								document.getElementById("pause").disabled =  true;
								document.getElementById("stop").disabled = true;
								//Enable start
								document.getElementById("start").disabled = false;
								//Enable configure
								document.getElementById("configure").disabled = false;
								//	alert("Send quit message - other");
							 	//setTimeout("SendQuitMessage()", 2000);
							 	 
							 	 	setTimeout("showStatus()", 1000);
								break;
								
								//Index is paused
								case "Paused":

								//Disable pause 
								document.getElementById("pause").disabled =  true;
								//Enable start
								document.getElementById("start").disabled = false;
								document.getElementById("stop").disabled = false;
								//Reset timeout for next update
				   			setTimeout("showStatus()", 1000);
								break;
								
								//Waiting to quit
								case "Waiting to quit":
								//Read log before sending quit message
								showLog();
							 	setTimeout("SendQuitMessage()", 3000);
									
								break;
								
							default:
							//Reset timeout for next update
				   		 setTimeout("showStatus()", 1000);
				   		 //alert("reset timeout");
								break;
															
							}
				   
				  }

				 }
				 	// alert("send GET");
				 xmlhttp.open("GET", "get_zoom_status.php", true);
				 xmlhttp.send();
				 
				}
				
				function RegisterKey()
				{
					var config = document.getElementById("config_filename").value; 
					var address = "register.php?config_filename=" + config;
					window.location = address;
				}
				
				function ViewFullLog()
				{
					//Get current config, pass to view_log.php
					var config = document.getElementById("config_filename").value; 
					var address = "view_log.php?config=" + config;
					
					window.open(address, "_blank"); 
					
				}
				
				function showLog()
				{		
					
					<?php 
					
					echo "var MSG_INDEXED = $MSG_INDEXED;\n";	
					echo "var MSG_SKIPPED = $MSG_SKIPPED;\n";	
					echo "var MSG_INIT = $MSG_INIT;\n";	
					echo "var MSG_FILEIO = $MSG_FILEIO;\n";	
					echo "var MSG_DOWNLOAD = $MSG_DOWNLOAD;\n";	
					echo "var MSG_UPLOAD = $MSG_UPLOAD;\n";	
					echo "var MSG_PLUGIN = $MSG_PLUGIN;\n";	
					echo "var MSG_INFO = $MSG_INFO;\n";	
					echo "var MSG_ERROR = $MSG_ERROR;\n";	
					echo "var MSG_WARNING = $MSG_WARNING;\n";	
					echo "var MSG_STARTSTOP = $MSG_STARTSTOP;\n";	
					echo "var MSG_QUEUE = $MSG_QUEUE;\n";	
					echo "var MSG_SUMMARY = $MSG_SUMMARY;\n";	
					echo "var MSG_DEBUG = $MSG_DEBUG;\n";	
					echo "var MSG_THREAD = $MSG_THREAD;\n";	
					echo "var MSG_FILTERED = $MSG_FILTERED;\n";	
					echo "var MSG_BROKENLINKS = $MSG_BROKENLINKS;\n";	
					echo "var MSG_TYPECOUNT = $MSG_TYPECOUNT;\n";	
					?>
							
				 var xmlloghttp;
				
				 if(window.XMLHttpRequest)
				 {
				  //IE7_, FireFox, Chrome, OPera, Safari
				  xmlloghttp = new XMLHttpRequest();
				 }
				 else
				 {
				  //IE 6, 5
				  xmlloghttp = new ActiveXObject("Microsoft.XMLHTTP");
				 }
				
				 xmlloghttp.onreadystatechange = function()
				 {
				  if(xmlloghttp.readyState==4 && xmlhttp.status==200)
				  {

				  	//var response = xmlloghttp.responseXML.documentElement;
				  	var response = getXMLResponse(xmlloghttp, "showLog()");
				    var loglines = response.getElementsByTagName('logline');
    	
    				var LogDiv = 	document.getElementById("log_output");    	
				    var logStr = "";
				    		
				    for(var i=0; i < loglines.length; i++)
				    {
				    	
				    		 var type = parseInt(getNodeValue(loglines[i], 'type')); 
				    		 var timestr = getNodeValue(loglines[i], 'time');
				    		 var value = getNodeValue(loglines[i], 'value');
				    		
				    		 //var currentElement = 	document.getElementById(target);
				    		 //if(currentElement)
				    		 //	currentElement.innerHTML = value;		
				    		 
				    		switch(type)
				    		{
				    		case MSG_INDEXED:
				    			logStr += "<div class=\"MSG_INDEXED\">";
									break;			
								case MSG_DOWNLOAD:
									logStr += "<div class=\"MSG_DOWNLOAD\">";
								break;						
								case MSG_UPLOAD:
									logStr += "<div class=\"MSG_UPLOAD\">";
								break;
								case MSG_PLUGIN:
									logStr += "<div class=\"MSG_PLUGIN\">";
								break;
								case MSG_ERROR:
									logStr += "<div class=\"MSG_ERROR\">";
								break;
								case MSG_WARNING:				
									logStr += "<div class=\"MSG_WARNING\">";
								break;
								case MSG_STARTSTOP:
									logStr += "<div class=\"MSG_STARTSTOP\">";
								break;
								case MSG_INFO:
									logStr += "<div class=\"MSG_INFO\">";
								break;
								case MSG_DEBUG:
									logStr += "<div class=\"MSG_DEBUG\">";
								break;
								case MSG_SKIPPED:
									logStr += "<div class=\"MSG_SKIPPED\">";
								break;
								case MSG_FILTERED:	
									logStr += "<div class=\"MSG_FILTERED\">";						
								break;
								case MSG_INIT:				
									logStr += "<div class=\"MSG_INIT\">";
								break;
								case MSG_FILEIO:
									logStr += "<div class=\"MSG_FILEIO\">";
								break;
								case MSG_QUEUE:
									logStr += "<div class=\"MSG_QUEUE\">";
								break;
								case MSG_SUMMARY:
									logStr += "<div class=\"MSG_SUMMARY\">";
								break;
								case MSG_BROKENLINKS:
									logStr += "<div class=\"MSG_BROKENLINKS\">";
								break;
								
								default:
							
								break;
								
				    		}
				    		 	
								logStr += timestr + " " + value + "</div>\n";
				    }
					  if(loglines.length > 0)
						   LogDiv.innerHTML = logStr;
						   
			
						 //Need to estop if indexer has finished
						 var curStatus = document.getElementById("IndexerStatus").innerHTML;
								
						  switch(curStatus)
							{
								//Indexing has finished / stopped
								//case "":
								//case "N/A":
								//case "Partial index complete (stopped by user)":
								//case "Indexing completed":
								//case "Out of memory":
								case "Finished":
								break;
								
							default:
							//Reset timeout for next update
					  		 setTimeout("showLog()", 2000);
								break;
															
							}
					}
				   
				  }

				 xmlloghttp.open("GET", "get_zoom_log.php", true);
				 xmlloghttp.send();
				 
				}
				
			</script>
	</head>
	<body>
	<?php
	include("./header.php");
	?>
	<div id=main_content>
	<div id="config">
	<form action="config.php" method="post" >
	<table>
	<!-- If we use a file input field can't set a default, can't copy/paste -->
	<?php
	echo "<tr><td>Zoom config file: <input type=\"text\" size=35 id=\"config_filename\" name=\"config_filename\" value=\"$CurrentConfig\">";
	?>
	
	<input type="submit" id="configure" value="Configure" name="submit" >
	<input type="button" id="register" value="Register Key" name="register" onclick="RegisterKey()"></td></tr>
	</form>
	</table>
	</div>
	
		<!-- Start/Stop/Pause bttons -->	
	<div id="controls">
		<button id="start" onClick="sendCommand(this.id);">Start</button>
		<button id="pause" disabled onClick="sendCommand(this.id);">Pause</button>
		<button id="stop" disabled onClick="sendCommand(this.id);">Stop</button>
		<input type=button class="help_button" id="help" value="Help" onclick="window.open('./help/index.html');">
	</div>		
	
	
	<!-- Status information -->	
	<fieldset>
	<legend>Indexer Status</legend>	
	<div id="status">
			<table>
					<tr>
						<td class="status_col_header">Status</td>
						<td class="counter_col_header">Counter</td>
					</tr>
					<tr>
						<td class="status_col">Files indexed</td>
						<td class="counter_col" id="FilesIndexed">&nbsp;</td>
					</tr>
					<tr >
						<td class="status_col">Files skipped</td>
								<td class="counter_col" id="NumSkipped">&nbsp;</td>
					</tr>
					<tr >
						<td class="status_col">Files filtered</td>
								<td class="counter_col" id="NumFiltered">&nbsp;</td>
					</tr>		
					<tr >
						<td class="status_col">Files downloaded</td>
								<td class="counter_col" id="NumDownloaded">&nbsp;</td>
					</tr>			
					<tr >
						<td class="status_col">Unique words found</td>
					<td class="counter_col" id="NumUniqueWords">&nbsp;</td>
					</tr>			
						<tr >
						<td class="status_col">Variant words found</td>
						<td class="counter_col" id="NumVariants">&nbsp;</td>
						
					</tr>			
					<tr >
						<td class="status_col">Total words found</td>
						<td class="counter_col" id="NumTotalWords">&nbsp;</td>
					</tr>		
					<tr >
						<td class="counter_col" class="status_col">Avg. unique words per page</td>
						<td id="AvgUniqueWordsPerPage">&nbsp;</td>
					</tr>
					<tr >
						<td class="status_col">Avg. words per page</td>
						<td class="counter_col" id="AvgWordsPerPage">&nbsp;</td>
					</tr>
					<tr >
						<td class="status_col">Start index time</td>
						<td class="counter_col" id="StartTime">&nbsp;</td>
					</tr>
					<tr >
						<td class="status_col">Elapsed index time</td>
						<td class="counter_col" id="ElapsedTime">&nbsp;</td>
					</tr>
					<tr >
						<td class="status_col">URLs visited by spider</td>
							<td class="counter_col" id="VisitedURLs">&nbsp;</td>
					</tr>
						<tr >
						<td class="status_col">URLs in spider queue</td>
						<td class="counter_col" id="QueuedURLs">&nbsp;</td>
					</tr>
					<tr >
						<td class="status_col">Start points scanned</td>
						<td class="counter_col" id="StartPtTotal">&nbsp;</td>
					</tr>
					<tr >
						<td class="status_col">Total bytes scanned/downloaded (MB)</td> 
						<td class="counter_col" id="TotalBytes">&nbsp;</td>
					</tr>
			</table>
	</div> <!-- status -->
	
			
	
	<!-- Errors and warnings -->	
	<div id="errors_div">
		<table class="error_table">
				<tr class=table_name>
					<td colspan=2>Errors and Warnings</td>
				</tr>
					<tr >
						<td class="err_status_col">Status</td>
						<td class="err_counter_col">Counter</td>
						<td class="err_show_col"><!--show--></td>
					</tr>
					<tr>
						<td>Errors</td>
						<td id="Errors">&nbsp;</td>
					</tr>
					<tr >
						<td>Warnings</td>
						<td id="Warnings">&nbsp;</td>
					</tr>
					<tr>
						<td >Broken Links</td>
						<td id="BrokenLinks">&nbsp;</td>
					</tr>				
			</table>
	</div> <!-- errors -->
</fieldset>
	<!-- Thread status and information -->	
	<fieldset>
	<legend>Thread Status</legend>	
	<div id=thread_status>
				<table class=thread_table>
					<tr class=thread_table>
						<td class="table_header">Thread #</td>
						<td class="table_header">Status</td>
						<td class="table_header">Progress</td>
						<td class="table_header">URL</td>
					</tr>
					<tr class=thread_table>
						<td class="thread_id_col"> Indexer</td>
						<td class="thread_status_col" id="IndexerStatus"></td>
						<td class="thread_prog_col"></td>
						<td class="thread_url_col" id="CurrentFilePath"></td>
					</tr>
					<tr class=thread_table>
						<td class="thread_id_col">DL #1</td>
						<td class="thread_status_col" id="THREAD1_Status"></td>
						<td class="thread_prog_col" id="THREAD1_Progress"></td>
						<td class="thread_url_col" id="THREAD1_CurrentFilePath"></td>
					</tr>
					<tr class=thread_table>
						<td class="thread_id_col">DL #2</td>
						<td class="thread_status_col" id="THREAD2_Status"></td>
						<td class="thread_prog_col" id="THREAD2_Progress"></td>
						<td class="thread_url_col" id="THREAD2_CurrentFilePath"></td>
					</tr>
					<tr class=thread_table>
						<td class="thread_id_col">DL #3</td>
						<td class="thread_status_col" id="THREAD3_Status"></td>
						<td class="thread_prog_col" id="THREAD3_Progress"></td>
						<td class="thread_url_col" id="THREAD3_CurrentFilePath"></td>
					</tr>
					<tr class=thread_table>
						<td class="thread_id_col">DL #4</td>
						<td class="thread_status_col" id="THREAD4_Status"></td>
						<td class="thread_prog_col" id="THREAD4_Progress"></td>
						<td class="thread_url_col" id="THREAD4_CurrentFilePath"></td>
					</tr>
					<tr class=thread_table>
						<td class="thread_id_col">DL #5</td>
						<td class="thread_status_col" id="THREAD5_Status"></td>
						<td class="thread_prog_col" id="THREAD5_Progress"></td>
						<td class="thread_url_col" id="THREAD5_CurrentFilePath"></td>
					</tr>
					<tr class=thread_table>
						<td class="thread_id_col">DL #6</td>
						<td class="thread_status_col" id="THREAD6_Status"></td>
						<td class="thread_prog_col" id="THREAD6_Progress"></td>
						<td class="thread_url_col" id="THREAD6_CurrentFilePath"></td>
					</tr>
					<tr class=thread_table>
						<td class="thread_id_col">DL #7</td>
						<td class="thread_status_col" id="THREAD7_Status"></td>
						<td class="thread_prog_col" id="THREAD7_Progress"></td>
						<td class="thread_url_col" id="THREAD7_CurrentFilePath"></td>
					</tr>
					<tr class=thread_table>
						<td class="thread_id_col">DL #8</td>
						<td class="thread_status_col" id="THREAD8_Status"></td>
						<td class="thread_prog_col" id="THREAD8_Progress"></td>
						<td class="thread_url_col" id="THREAD8_CurrentFilePath"></td>
					</tr>
					<tr class=thread_table>
						<td class="thread_id_col">DL #9</td>
						<td class="thread_status_col" id="THREAD9_Status"></td>
						<td class="thread_prog_col" id="THREAD9_Progress"></td>
						<td class="thread_url_col" id="THREAD9_CurrentFilePath"></td>
					</tr>
					<tr class=thread_table>
						<td class="thread_id_col">DL #10</td>
						<td class="thread_status_col" id="THREAD10_Status"></td>
						<td class="thread_prog_col" id="THREAD10_Progress"></td>
						<td class="thread_url_col" id="THREAD10_CurrentFilePath"></td>
					</tr>
			</table>
	</div> <!-- thread_status -->
</fieldset>
	<fieldset>
	<legend>Log Snapshot</legend>	
	<div id=log_output>
	</div><!-- log_output -->
	<input type="button" size="75" value="View full log" onclick="ViewFullLog()" />
	</fieldset>
</div> <!-- main_content -->
</body>
</html>
