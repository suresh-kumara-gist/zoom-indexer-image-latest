<?php
header('Content-type: text/html; charset=UTF-8');
require("zoom_defines.php");
?>

<html>
	<head>
		<link rel="stylesheet" href="zoom.css" type="text/css">
				
		<title>Zoom Key Registration</title>
		
		<script type="text/javascript">
		
			function PreSubmit()
			{
				var keyStr = document.getElementById("entered_key").value;
				if(keyStr.length < 100)
				{
					alert("Key appears to be too short, please check you have copied and pasted the entire key");
					return false;
				}
				return true;
			}
			
		</script>
	</head>
	<body>
		
<?php
	include("./header.php");

	$registerSuccess = false;

	if (isset($_POST['submit']))
	{
		
		if(isset($_POST['entered_key']))
		{
			$newkey = $_POST['entered_key'];
			
			//Double check size seems ok
			if(strlen($newkey) < 100 || strlen($newkey) > 250)
			{
					//error
					echo "<P>Key is not the expected length";
			}
			else
			{
				$ketdatfilename = $EngineExeDir . "/.key.tmp";
				
				//Create key.dat file
				if(file_put_contents($ketdatfilename, $_POST['entered_key']) === FALSE)
				{
					//error
					echo "<P>Failed to create .key.tmp file";
				}
				 
				//Let file be created/closed (getting permission errors?)
				sleep(1);
				 				
				//From build V7.0 1002 try reading in all stdout from  zoom and then parsing looking for errors etc
				$zoomOutput = "";
				$execstr = "$EngineExePath  -k 2>&1";
				exec($execstr, $execOutput, $execRetVal);
				$zoomOutput = implode(" ", $execOutput);

				if (stripos($zoomOutput, "success registering") !== false)
				{
					$registerSuccess = true;
				}
				else if (strlen($zoomOutput) > 0)
				{
					$registerSuccess = false;
					//Error during key registration
					print("<p>An error occurred while registering the key:</p><p><i>".$zoomOutput."</i></p><p>Exit code: ".$execRetVal."</p><p>Please check you have copy/pasted it correctly.<br>For more information <a href=\"http://www.passmark.com/support/keyhelp.htm#multiline\">see here.</a></p>");
				
				}
				else
				{
					//No output detected, possible zoom crash during rego preocess
					$registerSuccess = false;
					//Error during key registration
					print("<p>An error occurred while registering the key, no output was detected from Zoom (Exit code: ".$execRetVal."). This could indicate Zoom cannot be executed properly (due to permissions or security restrictions) or has unexpectedly exited.</a></p>");	
					print("<p>Please also make sure to run the install.sh script to setup all proper permissions.</p>");
				}
							

				//No longer deleting file (as if folder does not have write permissions can't recreter)
				//So simply zero file 
				if(file_put_contents($ketdatfilename, "") === FALSE)
				{
					//error
				echo "<P>The $ketdatfilename file failed to be cleared, you should clear the contents this file manually";
					
				}
			}
		}
		else
		{
				echo "<P>No key entered";
		}
		  
	}


if($registerSuccess == true)
{
echo<<<SUCCESS
		<p>Key was registration was a success. <a href="ZoomIndexer.php">Click here</a> to return to the main indexer page.
SUCCESS;
}
else
{
			$CurrentConfigPath = "";
			 
			//Load config file passed from Zoomindexer.php, otherwsie use the default
			if(isset($_GET['config_filename']))
				$CurrentConfigPath  = $_GET['config_filename'];
			
			$path = "./ZoomIndexer.php";
			if(strlen($CurrentConfigPath) > 0)
				$path = $path . "?config=" . $CurrentConfigPath;
	
echo<<<SHOWFORM
		<p>Please paste your username and key into the text box below and then click the Register button.
		<form id="mainform" action="register.php" method="post" onsubmit="return PreSubmit();">
			<textarea style="min-width:250px" rows="10" cols="50" id="entered_key" name="entered_key"></textarea>
			<br>
			<input type="submit" value="Register Key" name="submit" >						
			<input type="button" value="Cancel" name="discard" onclick="window.location='$path'" >
		</form>
SHOWFORM;

}	

?>		
	</body>
</html>

