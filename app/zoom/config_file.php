<?php
include("./zoom_defines.php");

//Create main config structure and any other related structures
$UConfig = new USERCONFIG();
$UConfig->PluginConfig = new PLUGINCONFIG();

//Temp can remove 
function PrintDebug($message)
{
		return; //disbled until needed
		
		$fp_debug = fopen("/tmp/php-temp", "a");
		fprintf($fp_debug, "%d : %s\n", time(), $message);
		fclose($fp_debug);
}


class REC_ELEM
{
	var $word = "";	
	var $URL = "";	
	var $title = "";	
	var $desc = "";	
	var $imgURL = "";	
}

class CAT_ELEM
{
	var $name = "";
	var $description = "";
	var $pattern = "";
	var $IsExclusive = 0;

} 

class SYN_ELEM
{
	var $word = "";	
	var $synonyms = "";	
}

class METAFIELD_ELEM
{
	var $type = 0;
	var $method = 0;

	var $name = "";	
	var $showname = "";	
	var $formname = "";	
	
	//var $NoCurrentValue = 0;
	//var $CurrentTextValue = "";	
	//var $CurrentNumericValue = 0;

	//var $CurrentMultiValueCount = 0;
	//	var $CurrentMultiValues = 0;
		
	var $DropdownValues = "";	
}


class URL_ELEM
{
	var $urltype = 0;
	var $linktype = 0;
	var $url = "";
	var $keywords = "";
	var $baseURL = "";
	var $sourceURL = "";
	var $uselimit = 0;
	var $limit = 0;
	var $boost = 0;
}

Class EXTENSION_ITEM
{
	var $FileType = 0;
	var $Ext = "";
	var $ImageURL = "";
	var $UseThumbs = "";
	var $ThumbsPath = "";
	var $ThumbsFilenamePrefix = "";
	var $ThumbsFilenamePostfix = "";
	var $ThumbsExt = "";
}

class PLUGINCONFIG
{
	var $PdfUseMeta = 0;
	var $PdfUseDescFiles = 0;
	var $PdfUsePassword = 0;
	var $PdfToTextMethod = 0;
	var $PdfPassword = ""; 
	var $PdfHighlight = 0;

	var $DocUseMeta = 0;
	var $DocUseDescFiles = 0;

	var $XlsUseMeta = 0;
	var $XlsUseDescFiles = 0;

	var $PptUseMeta = 0;
	var $PptUseDescFiles = 0;

	var $WpdUseMeta = 0;
	var $WpdUseDescFiles = 0;

	var $SwfUseMeta = 0;
	var $SwfUseDescFiles = 0;

	var $RtfUseMeta = 0;
	var $RtfUseDescFiles = 0;

	var $DjvuUseMeta = 0;
	var $DjvuUseDescFiles = 0;

	var $Mp3UseMeta = 0;
	var $Mp3UseDescFiles = 0;
	var $Mp3UseTechnical = 0;
	
	var $DwfUseMeta = 0;
	var $DwfUseDescFiles = 0;
	var $DwfUseTechnical = 0;

	var $ImgUseMeta = 0;
	var $ImgUseDescFiles = 0;
	var $ImgUseTechnical = 0;
	var $ImgMinFilesize = 0;

	var $OfficeXmlUseMeta = 0;
	var $OfficeXmlUseDescFiles = 0;
	var $OfficeXmlTextOnly = 0;

	var $TorrentUseDescFiles = 0;

	var $MhtUseDescFiles = 0;

	var $ZipUseDescFiles = 0;
	var $ZipExtractFiles = 0;
};



//Based on UserConfig.h from Zoom

class USERCONFIG
{
	var $startdir = "";
	var $baseURL = "";
	var $outdir = "";
	
	//for spidering
	var $spiderURL = "";
	var $spiderURLtype = 0;	
	var $spiderURLUseLimit = 0;
	var $spiderURLLimit = 0;
	var $spiderURLBoost = 0;
	
	//for OSF
	var	$OSF_DeviceType = 0;
	var	$OSF_PartitionNum = 0;
	var	$OSF_MountPath = "";
	var	$OSF_MountName = "";
	
	// for additional start points
	var $starturl_list; //Array of URL_ELEM objects
	var $startdir_list; //Array of URL_ELEM objects
	
	var $NumDownloadThreads = 0;
	var $NoCache = 0;
	var $BeepOnFinish = 0;
	
	var $MAXWORDS= 0;
	var $MAXPAGES= 0;
	var $MAX_FILE_SIZE= 0;
	var $DESCLENGTH= 0;
	var $MAXPAGES_PER_STARTPT= 0;
	var $MAXWORDS_PER_PAGE= 0;	
	var $MAXTITLELENGTH= 0;
	var $MAXWORDLENGTH=0;
	var $MAXURLVISITS_PER_STARTPT = 0;
	
	var $LimitMaxWords = 0;
	var $LimitPerStartPt = 0;
	var $LimitWordsPerPage = 0;
	var $LimitURLsPerStartPt = 0;
	var $TruncateTitleLen = 0;
	var $TruncateShowURL = 0;	
	var $ShowURLLength = 0;
	
	
	//ExtensionItem Extensions[MAXEXTENSIONS+1];
	var $ExtensionList = array(""); //array of EXTENSION_ITEM items
		
	var $SkipWords = array("");
	var $SkipPages = array("");

	var $UseContentFilter = 0;	
	var $UsePositiveFilter = 0;	
	var $ContentFilterRules = array("");

	var $ScanNoExtension = 0;	
	var $ScanUnknownExtensions = 0;	
	var $ScanFileLinks = 0;	
	var $ParseJSLinks = 0;	
	var $ScanAllEmailAttachments = 0;	

	var $BinaryUseDescFiles = 0;	
	var $UseLocalDescPath = 0;	
	var $LocalDescPath = "";
	var $CheckThumbnailsExist = 0;		

	var $PluginConfig; //PLUGINCONFIG object
	
	var $IndexMetaDesc= 0;
	var $IndexTitle= 0;
	var $IndexContent= 0;
	var $IndexKeywords= 0;
	var $IndexFilename= 0;
	var $IndexAuthor= 0;
	var $IndexLinkText= 0;
	var $IndexAltText= 0;
	var $IndexDCMeta= 0;
	var $IndexParamTags= 0;
	var $IndexURLDomain= 0;
	var $IndexURLPath= 0;
	
	var $ResultNumber= 0;
	var $ResultTitle= 0;
	var $ResultMetaDesc= 0;
	var $ResultContext= 0;
	var $ResultTerms= 0;
	var $ResultScore= 0;
	var $ResultDate= 0;
	var $ResultURL= 0;
	var $ResultFilesize= 0;

	var $WeightTitle= 0;
	var $WeightDesc= 0;
	var $WeightKeywords= 0;
	var $WeightFilename= 0;
	var $WeightHeadings= 0;
	var $WeightDensity= 0;
	var $WeightLinktext= 0;
	var $WeightShortURLs= 0;
	var $WeightProximity= 0;
	var $WeightContent= 0;
	
	var $UseCats= 0;
	var $SearchMultiCats= 0;
	var $DisplayCatSummary= 0;
	var $UseDefCatName= 0;
	var $DefCatName = "";
	
	//CatsList* cats_list;
	
	var $cats_list;				//Array of CAT_ELEM objects
	var $syn_list;				//Array of SYN_ELEM objets
	var $RecommendedList; //Array of REC_ELEM objects
	var $metafield_list;  //Array of METAFIELD_ELEM objects
		
	var $RecommendedMax= 0;
	var $MetaMoneyCurrency= 0;
	var $MetaMoneyShowDecimals= 0;

	var $UseStemming= 0;
	var $StemmingLanguageIndex= 0;
	
	var $UseUTF8= 0;
	var $UseAuth= 0;
	var $UseCookies= 0;
	var $Login = "";
	var $Password = "";
		
	var $UseCookieLogin= 0;
	var $CookieLoginURL = "";
	var $CookieLoginName = "";
	var $CookieLoginValue = "";
	var $CookiePasswordName = "";
	var $CookiePasswordValue = "";
	var $CookieParams = "";

	var $OutputFormat= 0;
	var $OutputOS= 0;

	var $IsASPDotNet= 0;
	var $DotNetUseFormTags= 0;
	var $DotNetUsePostBacks= 0;

	var $UseDateTime= 0;
	var $UseZoomImage= 0;
	var $DefaultSort= 0;
	var $DateRangeSearch= 0;
	var $DateRangeFormat= 0;
	var $UseDomainDiversity= 0;

	var $UseUTCTime= 0;
	
	var $MapAccents= 0;	
	var $MapAccentChars= 0;
	var $MapUmlautChars= 0;
	var $MapLigatureChars= 0;
	var $MapAccentsToDigraphs= 0;
	var $MapLatinLigatureChars=0;
	
	var $MinWordLen= 0;
	var $SkipUnderscore= 0;
	var $SkipURLCase= 0;
	var $WordJoinChars = "";
	
	var $FormFormat= 0;
	var $Highlighting= 0;		
	var $GotoHighlight= 0;
	var $Logging= 0;
	var $LogFileName = "";
	var $Timing= 0;	
	var $ContextSize= 0;	
	var $LinkTarget = "";
	var $SearchAsSubstring= 0;
	var $DisableToLower= 0;
	var $StripDiacritics= 0;

	var $DefaultToAnd= 0;

	var $Spelling= 0;
	var $SpellingWhenLessThan= 0;
		
	var $Codepage = 0;	
	
	var $UseXML= 0;
	var $XMLTitle = "";
	var $XMLDescription = "";
	var $XMLLink = "";
	var $XMLOpenSearchDescURL = "";
	var $XMLStyleSheetURL = "";
	var $XMLHighlight= 0;
	
	var $LanguageFile = "";

	var $ZoomInfo= 0;
	var $AllowExactPhrase= 0;
	var $MaxContextSeeks= 0;
	var $MaxResultsPerQuery= 0;
	
	// for template source filepath
	var $UseSrcPaths= 0;
	var $SourceScriptPath = "";
	var $SourceTemplatePath = "";
	
	var $LinkBackURL = "";
	
		// report stats stuff
	var $ReportLogfile = "";
	var $ReportOutputDir = "";
	var $ReportAppendDatetime= 0;


	var $StatsTop10List= 0;
	var $StatsTopNRList= 0;
	var $StatsDayList= 0;
	var $StatsWeekList= 0;
	var $StatsMonthList= 0;
	var $StatsTop10Check= 0;
	var $StatsTopNRCheck= 0;	
	var $StatsDayCheck= 0;
	var $StatsWeekCheck= 0;
	var $StatsMonthCheck= 0;
	var $StatsListAll= 0;
	var $StatsListAllUpto= 0;
	var $StatsDayGraphType= 0;
	var $StatsWeekGraphType= 0;
	var $StatsMonthGraphType= 0;

	// FTP info
	var $FTPLogin = "";
	var $FTPPassword = "";
	var $FTPServer = "";
	var $FTPUploadPath = "";
	var $FTPPort= 0;
	var $FTPAuto= 0;
	var $FTPDontUploadTemplate= 0;
	var $FTPUsePASV= 0;
  var $FTPRenameTmp= 0;
	var $FTPSetFilePermissions= 0;

		// Logging options
	var $LogMode = 0;
	var $LogIndexed = 0;
	var $LogSkipped = 0;
	var $LogFiltered = 0;
	var $LogInit = 0;
	var $LogFileIO = 0;
	var $LogDownload = 0;
	var $LogUpload = 0;
	var $LogPlugin = 0;
	var $LogInfo = 0;
	var $LogError = 0;
	var $LogWarning = 0;	
	var $LogQueue = 0;
	var $LogSummary = 0;
	var $LogThread = 0;
	var $LogBrokenLinks = 0;
	var $LogWriteToFile = 0;
	var $LogDebugMode = 0;
	var $LogAppendDatetime = 0;
	var $LogSaveToFilename = "";
	var $LogHTMLErrors = 0;
	
	var $NoCharset = 0;
	var $EscapeURLsInUTF8 = 0;
	var $WizardUploadReqd = 0;
	var $OptimizeSetting = 0;
	
	
	var $CRC32 = 0;
	var $Verbose = 0;	
	var $RewriteLinks = 0;
	var $RewriteFind = "";
	var $RewriteWith = "";

	var $SitemapXML = 0;	
	var $SitemapTXT = 0;	
	var $SitemapUsePageBoost = 0;
	var $SitemapUpload = 0;
	var $SitemapUseBaseURL = 0;
	var $SitemapUploadPath = "";
	var $SitemapBaseURL = "";

	var $ThrottleDelay = 0;
	var $UseRobotsTxt = 0;	
	var $UserAgentStr = "";

	var $RetryWithMagic = 0;

	var $currentMode = 0;
	var $PluginOpenNewWindow = 0;
	var $PluginUseOverrideTimeout = 0;
	var $PluginOverrideTimeoutValue = 0;

	var $BinaryExtractStrings = 0;
	var $BinStringsAllowPunctuation = 0;
	var $BinStringsAllowNumbers = 0;	
	var $BinStringsMinStringLength = 0;
	var $BinStringsMaxStringLength = 0;
	var $BinStringsRepeatedCharLimit = 0;
	var $BinStringsCaseChangeLimit = 0;

	var $UseAutoComplete = 0;
	var $AutoCompleteRules = array("");
	var $UseAutoCompleteInclude = 0;	
	var $AutoCompleteIncludeTopNum = 0;
	var $AutoCompleteIncludeURL = "";
	var $AutoCompleteUsePageTitle = 0;
	var $AutoCompleteUseMetaDesc = 0;
	
	var $AppDataPath = "";
	var $TmpDataPath = "";
	
	var $UseProxyServer = 0;
	var $ProxyServer = "";

}


function GetConfigValue($buffer, $tag)
{
	$taglen = strlen($tag);	
	if (strncmp($buffer, $tag, $taglen) == 0)
	{				
		return rtrim(substr($buffer, $taglen)); //Trim whitespace (ie newline from each line)
	}
				
	return NULL;
}

//Read the EXTENSIONS section from the config into an array
function GetExtensionValues($fp_zcfg)
{
	global $MAX_CONFIG_LINELEN;
	global $MAXEXTENSIONS;
	global $MAX_FNAME;
	global $URLLENGTH;
	global $MAX_EXT;
		
	$numExtensions = 0;
		
	while(!feof($fp_zcfg) && $numExtensions < $MAXEXTENSIONS)
	{
		$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);	
				
		if(strncmp($nextLine, "#EXTENSIONS_END", strlen("#EXTENSIONS_END")) == 0)
		{
			return $extensionList;
		}
			
	//eg .htm|FILETYPE:0
	$extensionItem = new EXTENSION_ITEM();
	
	//split on | then :, stripping newlinechar
	$firstSplit = strpos($nextLine, "|");
	$extensionItem->Ext  = substr($nextLine, 0, $firstSplit);
	
	//Get type - located between first ":" and end of line or "|"
	$ftStart = strstr($nextLine, ":");
	$ftEnd = strpos($ftStart, "|");

	if($ftEnd === FALSE)
		$extensionItem->FileType = intval(substr($ftStart, 1));
	else
		$extensionItem->FileType = intval(substr($ftStart, 1, $ftEnd-1));	


	//Now parse rest of line after the first ":"
	//int extIndex;
	//extIndex = AddExtension(tmpExt);	
	$restOfLine = substr($nextLine, strpos($nextLine, ":")+2); //+2 to skip FileType
	
		
	$tok = strtok($restOfLine, "|| \n\r");
	while ($tok != false)
	{
		if (strcmp($tok, "USETHUMBS") == 0)
			$extensionItem->UseThumbs = true;
		else if (strstr($tok , "IMGURL:") == $tok  && strlen($tok) <= $URLLENGTH)
			 $extensionItem->ImageURL = substr($tok, strpos($tok, ":")+1);
		else if (strstr($tok , "THUMBSEXT:") == $tok && strlen($tok) <= $MAX_EXT)
		 $extensionItem->ThumbsExt = substr($tok, strpos($tok, ":")+1);
		else if (strstr($tok , "THUMBSPATH:") == $tok  && strlen($tok) <= $URLLENGTH)
		{
			 $extensionItem->ThumbsPath = substr($tok, strpos($tok, ":")+1);
			 //SwapToSlashes($UConfig->Extensions[extIndex].ThumbsPath);			
		}
		else if (strstr($tok , "THUMBSPREFIX:") == $tok  && strlen($tok) <= $MAX_FNAME)
			$extensionItem->ThumbsFilenamePrefix = substr($tok, strpos($tok, ":")+1);
		else if (strstr($tok , "THUMBSPOSTFIX:") == $tok  && strlen($tok) <= $MAX_FNAME)
			$extensionItem->ThumbsFilenamePostfix = substr($tok, strpos($tok, ":")+1);
		else if (strlen($tok ) <= $URLLENGTH)// default as image url
			$extensionItem->ImageURL = substr($tok, strpos($tok, ":")+1);
	
		$tok  = strtok("| \n\r");
	}

	$extensionList[$numExtensions] = $extensionItem;
	$numExtensions++;

	} 

	return $extensionList;	
}


function GetBaseURL($spiderURL, $new_baseURL, $bIsOfflinePath)
{	
	
	$len = 0;
	$slashChar = "";
	$filename = "";

	if ($bIsOfflinePath == true)
			$slashChar = '\\';
	else
			$slashChar = '/';

	$slashPtr = strrchr($spiderURL, $slashChar);

	if ($slashPtr != false)
	{
		
		$filename = $slashPtr;
					
		$len = strlen($spiderURL) - strlen($filename);
		if ($len < 8)
			$len = strlen($spiderURL);
	}
	else
		$len = strlen($spiderURL);

	$new_baseURL = substr($spiderURL, 0, $len);
		
	
	if ($new_baseURL[$len-1] != $slashChar)
	{
		$new_baseURL[$len] = $slashChar;
	}

	// Since this needs to match the spiderURL (which gets canonicalized at some point)
	// we might as well canonicalize our output	
	//DWORD dwLength = len;
	//::UrlCanonicalize(new_baseURL, new_baseURL, &dwLength, 0);
	/* Not canonicalizing as should be handled by the engine but if we have to here is some code from 
	the PHP help 
	function canonicalize($address)
	{
    $address = explode('/', $address);
    $keys = array_keys($address, '..');

    foreach($keys AS $keypos => $key)
    {
        array_splice($address, $key - ($keypos * 2 + 1), 2);
    }

    $address = implode('/', $address);
    $address = str_replace('./', '', $address);
	}
	*/




}


//Save config file
//Return false on failure
function SaveZCFGFile($zcfg_fpath)
{
	Global $UConfig;
	global $MAX_CONFIG_LINELEN;
	global $OUTPUT_PHP, $OUTPUT_ASP, $OUTPUT_CGI, $OUTPUT_JSFILE;
	global $TmpExtensions;
	global $AUTHXORKEY;
	global $MAXSKIPPAGES;
	global $MAXSKIPPAGELEN;
	global $MAXDESCLEN;
	global $TITLELEN;
	global $METAFIELD_MONEY_COUNT;
	global $MAXAUTOCOMPLETE;
	global $MAXFILTERRULES;
	global $METAFIELD_TYPE_COUNT;
	global $METAFIELD_TYPE_DROPDOWN;
	global $METAFIELD_TYPE_MULTI3;
	global $METAFIELD_DROPDOWN_MA;
	global $URLTYPE_INDEX_AND_FOLLOW;
	global $METAFIELD_TYPE_MULTI;
	global $MAXSKIPWORDS;
	global $METAFIELD_DROPDOWN_MAX;
	$i = 0;

	// open file for writing
	$fp_zcfg = fopen($zcfg_fpath, "wb");
	
	if(!$fp_zcfg)
	{
		/*
			ZoomMessageBox(main_hwnd, "Could not save configuration to disk in file\n")\
					"Check that the directory exists.\n")\
					"and that you have write access to this directory.\n"),
 					"Can't save to file",MB_ICONEXCLAMATION | MB_OK);
 		*/
		return false;
	}
	
	//Sanity check some values (eg so MaxResultsPerQuery is not 0)
	if($UConfig->MAXWORDS < 1)
		$UConfig->MAXWORDS = 30000;	
	if($UConfig->MAXPAGES < 1)
		$UConfig->MAXPAGES = 100;
	if($UConfig->MAX_FILE_SIZE < 1)
		$UConfig->MAX_FILE_SIZE = 1048576;	
	if($UConfig->DESCLENGTH < 1)
		$UConfig->DESCLENGTH = 150;
	if($UConfig->MaxResultsPerQuery < 1)
		$UConfig->MaxResultsPerQuery= 1000;
	if($UConfig->MaxContextSeeks < 1)
		$UConfig->MaxContextSeeks = 500;
		
	if($UConfig->MAXWORDLENGTH > 100)
		$UConfig->MAXWORDLENGTH  = 100;
	


	// Write the UTF-16 BOM so that we can easily open the ZCFG files in text editors
	// and they'll appear in Unicode mode.
	//fwrite(UTF16BOM, 1, sizeof(UTF16BOM), fp);

	
		PrintDebug("Saving: CurrentMode: " . $UConfig->currentMode);
	
	//Add BOM for UTF8
	$UTF8Bom = "\xEF\xBB\xBF";
	fwrite($fp_zcfg, $UTF8Bom);	 
	
		// Write new version header	
	fwrite($fp_zcfg, "__7_0_");	
//	if (StrPBrk(PROGRAM_BUILD, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ ")) == NULL)
//		fwrite($fp_zcfg, "%d",PROGRAM_BUILD);
//	else
//		fwrite($fp_zcfg, "%s",PROGRAM_BUILD);

	// Write out configuration	

	//Temp empty line to get around indexer skipping first config line
	//fwrite($fp_zcfg, "\r\n");
	
	/// Start writing
	fwrite($fp_zcfg, "\r\n#STARTDIR:$UConfig->startdir\r\n");
	fwrite($fp_zcfg, "#SPIDERURL:$UConfig->spiderURL\r\n");
	fwrite($fp_zcfg, "#BASEURL:$UConfig->baseURL\r\n");
	fwrite($fp_zcfg, "#OUTDIR:$UConfig->outdir\r\n");

	fwrite($fp_zcfg, "#SPIDERURLTYPE:$UConfig->spiderURLtype\r\n");
	fwrite($fp_zcfg, "#SPIDERURLUSELIMIT:$UConfig->spiderURLUseLimit\r\n");
	fwrite($fp_zcfg, "#SPIDERURLLIMIT:$UConfig->spiderURLLimit\r\n");
	fwrite($fp_zcfg, "#SPIDERURLBOOST:$UConfig->spiderURLBoost\r\n");
	
	fwrite($fp_zcfg, "#USE-CRC:$UConfig->CRC32\r\n");
	fwrite($fp_zcfg, "#CURRENTMODE:$UConfig->currentMode\r\n");

	
	fwrite($fp_zcfg, "#DLTHREADS:$UConfig->NumDownloadThreads\r\n");
	fwrite($fp_zcfg, "#NOCACHE:$UConfig->NoCache\r\n");	

	fwrite($fp_zcfg, "#BEEP-ON-FINISH:$UConfig->BeepOnFinish\r\n");

	fwrite($fp_zcfg, "#THROTTLEDELAY:$UConfig->ThrottleDelay\r\n");

	fwrite($fp_zcfg, "#OUTPUT:");
	if ($UConfig->OutputFormat == $OUTPUT_JSFILE)
		fwrite($fp_zcfg, "JSFILE\r\n");
	else if ($UConfig->OutputFormat == $OUTPUT_ASP)
		fwrite($fp_zcfg, "ASP\r\n");
	else if ($UConfig->OutputFormat == $OUTPUT_CGI)
		fwrite($fp_zcfg, "CGI\r\n");
	else
		fwrite($fp_zcfg, "PHP\r\n");

	fwrite($fp_zcfg, "#OUTPUT_OS:$UConfig->OutputOS\r\n");

	// note that the aspdotnet option needs to be AFTER OutputOS and OutputFormat
	// so it can override them if necessary
	fwrite($fp_zcfg, "#ISDOTNET:$UConfig->IsASPDotNet\r\n");
	fwrite($fp_zcfg, "#DOTNETUSEFORMTAGS:$UConfig->DotNetUseFormTags\r\n");
	fwrite($fp_zcfg, "#DOTNETUSEPOSTBACKS:$UConfig->DotNetUsePostBacks\r\n");

	// Output log options
	fwrite($fp_zcfg, "#VERBOSE:$UConfig->Verbose\r\n");

	fwrite($fp_zcfg, "#LOGMODE:$UConfig->LogMode\r\n");

	fwrite($fp_zcfg, "#LOGOPTIONS:");
	if ($UConfig->LogIndexed)
		fwrite($fp_zcfg, "INDEXED|");
	if ($UConfig->LogSkipped)
		fwrite($fp_zcfg, "SKIPPED|");
	if ($UConfig->LogFiltered)
		fwrite($fp_zcfg, "FILTERED|");
	if ($UConfig->LogInit)
		fwrite($fp_zcfg, "INIT|");
	if ($UConfig->LogDownload)
		fwrite($fp_zcfg, "DOWNLOAD|");
	if ($UConfig->LogUpload)
		fwrite($fp_zcfg, "UPLOAD|");
	if ($UConfig->LogFileIO)
		fwrite($fp_zcfg, "FILEIO|");
	if ($UConfig->LogPlugin)
		fwrite($fp_zcfg, "PLUGIN|");
	if ($UConfig->LogInfo)
		fwrite($fp_zcfg, "INFO|");
	if ($UConfig->LogError)
		fwrite($fp_zcfg, "ERROR|");
	if ($UConfig->LogWarning)
		fwrite($fp_zcfg, "WARNING|");
	if ($UConfig->LogQueue)
		fwrite($fp_zcfg, "QUEUE|");
	if ($UConfig->LogSummary)
		fwrite($fp_zcfg, "SUMMARY|");
	if ($UConfig->LogThread)
		fwrite($fp_zcfg, "THREAD|");
	if ($UConfig->LogBrokenLinks)
		fwrite($fp_zcfg, "BROKEN|");
	fwrite($fp_zcfg, "\r\n");
	
	fwrite($fp_zcfg, "#LOGWRITETOFILE:$UConfig->LogWriteToFile\r\n");
	fwrite($fp_zcfg, "#LOGWRITETOFILENAME:$UConfig->LogSaveToFilename\r\n");
	fwrite($fp_zcfg, "#LOGAPPENDDATETIME:$UConfig->LogAppendDatetime\r\n");
	fwrite($fp_zcfg, "#LOGDEBUGMODE:$UConfig->LogDebugMode\r\n");	
	fwrite($fp_zcfg, "#LOGHTMLERRORS:$UConfig->LogHTMLErrors\r\n");	

	if ($UConfig->UseSrcPaths)
	{
		fwrite($fp_zcfg, "#USESRCPATH_SCRIPT:$UConfig->SourceScriptPath\r\n");
	}				
	
	fwrite($fp_zcfg, "#SCAN_NOEXTENSION:$UConfig->ScanNoExtension\r\n");
	fwrite($fp_zcfg, "#SCAN_UNKNOWNEXTENSIONS:$UConfig->ScanUnknownExtensions\r\n");
	fwrite($fp_zcfg, "#SCAN_FILELINKS:$UConfig->ScanFileLinks\r\n");
	fwrite($fp_zcfg, "#SCAN_USELOCALDESCPATH:$UConfig->UseLocalDescPath\r\n");
	fwrite($fp_zcfg, "#SCAN_LOCALDESCPATH:$UConfig->LocalDescPath\r\n");
	fwrite($fp_zcfg, "#SCAN_ROBOTSTXT:$UConfig->UseRobotsTxt\r\n");
	fwrite($fp_zcfg, "#SCAN_CHECKTHUMBS:$UConfig->CheckThumbnailsExist\r\n");	
	fwrite($fp_zcfg, "#PARSEJSLINKS:$UConfig->ParseJSLinks\r\n");

	fwrite($fp_zcfg, "#SCAN_ALLEMAILATTACHMENTS:$UConfig->ScanAllEmailAttachments\r\n");

	//edition == ENTERPRISE_EDITION && 
	if (strlen($UConfig->UserAgentStr) > 0)
		fwrite($fp_zcfg, "#USERAGENTSTR:$UConfig->UserAgentStr\r\n");

	fwrite($fp_zcfg, "#REWRITELINKS:$UConfig->RewriteLinks\r\n");
	fwrite($fp_zcfg, "#REWRITEFIND:$UConfig->RewriteFind\r\n");
	fwrite($fp_zcfg, "#REWRITEWITH:$UConfig->RewriteWith\r\n");

	fwrite($fp_zcfg, "#INDEXOPTIONS:");
	if ($UConfig->IndexMetaDesc)
		fwrite($fp_zcfg, "METADESC|");
	if ($UConfig->IndexContent)
		fwrite($fp_zcfg, "CONTENT|");
	if ($UConfig->IndexTitle)
		fwrite($fp_zcfg, "TITLE|");
	if ($UConfig->IndexKeywords)
		fwrite($fp_zcfg, "KEYWORDS|");
	if ($UConfig->IndexFilename)
		fwrite($fp_zcfg, "FILENAME|");
	if ($UConfig->IndexAuthor)
		fwrite($fp_zcfg, "AUTHOR|");
	if ($UConfig->IndexLinkText)
		fwrite($fp_zcfg, "LINKTEXT|");
	if ($UConfig->IndexAltText)
		fwrite($fp_zcfg, "ALTTEXT|");
	if ($UConfig->IndexDCMeta)
		fwrite($fp_zcfg, "DCMETA|");
	if ($UConfig->IndexParamTags)
		fwrite($fp_zcfg, "PARAM|");
	if ($UConfig->IndexURLDomain)
		fwrite($fp_zcfg, "URLDOMAIN|");
	if ($UConfig->IndexURLPath)
		fwrite($fp_zcfg, "URLPATH|");
	fwrite($fp_zcfg, "\r\n");

	fwrite($fp_zcfg, "#RESULTOPTIONS:");
	if ($UConfig->ResultNumber)
		fwrite($fp_zcfg, "NUMBER|");
	if ($UConfig->ResultTitle)
		fwrite($fp_zcfg, "TITLE|");
	if ($UConfig->ResultMetaDesc)
		fwrite($fp_zcfg, "METADESC|");
	if ($UConfig->ResultContext)
		fwrite($fp_zcfg, "CONTEXT|");
	if ($UConfig->ResultTerms)
		fwrite($fp_zcfg, "TERMS|");
	if ($UConfig->ResultScore)
		fwrite($fp_zcfg, "SCORE|");
	if ($UConfig->ResultDate)
		fwrite($fp_zcfg, "DATE|");
	if ($UConfig->ResultURL)
		fwrite($fp_zcfg, "URL|");
	if ($UConfig->ResultFilesize)
		fwrite($fp_zcfg, "FILESIZE|");
	fwrite($fp_zcfg, "\r\n");
	
	fwrite($fp_zcfg, "#USE-UTF8:$UConfig->UseUTF8\r\n");
	fwrite($fp_zcfg, "#CODEPAGE:$UConfig->Codepage\r\n");
	fwrite($fp_zcfg, "#USESTEMMING:$UConfig->UseStemming\r\n");
	fwrite($fp_zcfg, "#STEMALGO:$UConfig->StemmingLanguageIndex\r\n");	

	if ($UConfig->MapAccents)
	{
		fwrite($fp_zcfg, "#MAPACCENTS:");
		if ($UConfig->MapAccentChars  === 0)
			fwrite($fp_zcfg, "NOACCENTS");
		if ($UConfig->MapUmlautChars === 0)
			fwrite($fp_zcfg, "NOUMLAUTS");
		if ($UConfig->MapLigatureChars === 0)
			fwrite($fp_zcfg, "NOLIGATURES");		
		fwrite($fp_zcfg, "\r\n");
	}
	
	fwrite($fp_zcfg, "#DIGRAPHS:$UConfig->MapAccentsToDigraphs\r\n");
	fwrite($fp_zcfg, "#MAPLATINLIGATURES:$UConfig->MapLatinLigatureChars\r\n");

	if (strlen($UConfig->LanguageFile) > 1)
		fwrite($fp_zcfg, "#ZLANGFILE:$UConfig->LanguageFile\r\n");
	
	fwrite($fp_zcfg, "#SKIPUNDERSCORE:$UConfig->SkipUnderscore\r\n");
	fwrite($fp_zcfg, "#SKIPURLCASE:$UConfig->SkipURLCase\r\n");

	// min word length
	fwrite($fp_zcfg, "#MINWORDLEN:$UConfig->MinWordLen\r\n");	

	fwrite($fp_zcfg, "#FORMFORMAT:$UConfig->FormFormat\r\n");	
	
	fwrite($fp_zcfg, "#HIGHLIGHTING:$UConfig->Highlighting\r\n");	
	fwrite($fp_zcfg, "#GOTOHIGHLIGHT:$UConfig->GotoHighlight\r\n");	

	fwrite($fp_zcfg, "#USEXML:$UConfig->UseXML\r\n");	
	fwrite($fp_zcfg, "#XMLTITLE:$UConfig->XMLTitle\r\n");	
	fwrite($fp_zcfg, "#XMLDESC:$UConfig->XMLDescription\r\n");	
	fwrite($fp_zcfg, "#XMLURL:$UConfig->XMLLink\r\n");
	fwrite($fp_zcfg, "#XMLXSLTURL:$UConfig->XMLStyleSheetURL\r\n");
	fwrite($fp_zcfg, "#XML_OPENSEARCH_DESCURL:$UConfig->XMLOpenSearchDescURL\r\n");
	fwrite($fp_zcfg, "#XMLHIGHLIGHT:$UConfig->XMLHighlight\r\n");	

	fwrite($fp_zcfg, "#LOGGING:$UConfig->Logging\r\n");	
	fwrite($fp_zcfg, "#LOGGING_FILE:$UConfig->LogFileName\r\n");	

	fwrite($fp_zcfg, "#TIMING:$UConfig->Timing\r\n");	
		
	fwrite($fp_zcfg, "#NOCHARSET:$UConfig->NoCharset\r\n");	
	fwrite($fp_zcfg, "#ESCAPEURLSUTF8:$UConfig->EscapeURLsInUTF8\r\n");	
	fwrite($fp_zcfg, "#USEUTCTIME:$UConfig->UseUTCTime\r\n");	

	if ($UConfig->DefaultToAnd)
		fwrite($fp_zcfg, "#DEFAULT_TO_AND:1\r\n");	
	else
		fwrite($fp_zcfg, "#DEFAULT_TO_AND:0\r\n");	

	fwrite($fp_zcfg, "#CONTEXTSIZE:$UConfig->ContextSize\r\n");
	fwrite($fp_zcfg, "#MAXRESULTSPERQUERY:$UConfig->MaxResultsPerQuery\r\n");
	
	if ($UConfig->AllowExactPhrase)
		fwrite($fp_zcfg, "#EXACTPHRASE:$UConfig->MaxContextSeeks\r\n");		
	else
		fwrite($fp_zcfg, "#EXACTPHRASE:0\r\n");		
	
	if (strlen($UConfig->LinkTarget) > 0)
		fwrite($fp_zcfg, "#LINKTARGET:$UConfig->LinkTarget\r\n");

	if ($UConfig->TruncateShowURL)
		fwrite($fp_zcfg, "#TRUNCATESHOWURL:$UConfig->ShowURLLength\r\n");
	
	fwrite($fp_zcfg, "#SEARCHASSUBSTRING:$UConfig->SearchAsSubstring\r\n");	
	fwrite($fp_zcfg, "#STRIPDIACRITICS:$UConfig->StripDiacritics\r\n");	
	fwrite($fp_zcfg, "#NO_TOLOWER:$UConfig->DisableToLower\r\n");	
	fwrite($fp_zcfg, "#ZOOMINFO:$UConfig->ZoomInfo\r\n");
	fwrite($fp_zcfg, "#USEDATETIME:$UConfig->UseDateTime\r\n");	
	fwrite($fp_zcfg, "#DATERANGESEARCH:$UConfig->DateRangeSearch\r\n");	
	fwrite($fp_zcfg, "#DATERANGEFORMAT:$UConfig->DateRangeFormat\r\n");	
	fwrite($fp_zcfg, "#DEFAULTSORT:$UConfig->DefaultSort\r\n");
	fwrite($fp_zcfg, "#WORDJOINCHARS:$UConfig->WordJoinChars\r\n");
	fwrite($fp_zcfg, "#ZOOMIMAGE:$UConfig->UseZoomImage\r\n");
	fwrite($fp_zcfg, "#USEDOMAINDIVERSITY:$UConfig->UseDomainDiversity\r\n");

	fwrite($fp_zcfg, "#SPELLING:$UConfig->Spelling\r\n");
	fwrite($fp_zcfg, "#SPELLINGWHENLESSTHAN:$UConfig->SpellingWhenLessThan\r\n");

	if ($UConfig->PluginOpenNewWindow)
		fwrite($fp_zcfg, "#PLUGINOPENNEWWINDOW:$UConfig->PluginOpenNewWindow\r\n");

	if ($UConfig->PluginUseOverrideTimeout)
	{
		fwrite($fp_zcfg, "#PLUGINUSETIMEOUT:$UConfig->PluginUseOverrideTimeout\r\n");
		fwrite($fp_zcfg, "#PLUGINTIMEOUTVALUE:$UConfig->PluginOverrideTimeoutValuer\n");
	}

	if (strlen($UConfig->LinkBackURL) > 0)
		fwrite($fp_zcfg, "#LINKBACKURL:$UConfig->LinkBackURL\r\n");

	fwrite($fp_zcfg, "#WIZARD_UPLOADREQD:$UConfig->WizardUploadReqd\r\n");

	if (strlen($UConfig->ReportLogfile) > 0)
		fwrite($fp_zcfg, "#REPORTLOGFILE:$UConfig->ReportLogfile\r\n");

	if (strlen($UConfig->ReportOutputDir) > 0)
		fwrite($fp_zcfg, "#REPORTOUTDIR:$UConfig->ReportOutputDir\r\n");
	
	fwrite($fp_zcfg, "#REPORTUSEDATES:$UConfig->ReportAppendDatetime\r\n");

	if ($UConfig->StatsTop10Check)
		fwrite($fp_zcfg, "#REPORT_TOP10:$UConfig->StatsTop10List\r\n");

	if ($UConfig->StatsTopNRCheck)
		fwrite($fp_zcfg, "#REPORT_TOPNR:$UConfig->StatsTopNRList\r\n");
	
	if ($UConfig->StatsDayCheck)
	{
		fwrite($fp_zcfg, "#REPORT_DAY:$UConfig->StatsDayList\r\n");
		fwrite($fp_zcfg, "#REPORT_DAY_TYPE:$UConfig->StatsDayGraphType\r\n");
	}

	if ($UConfig->StatsWeekCheck)
	{
		fwrite($fp_zcfg, "#REPORT_WEEK:$UConfig->StatsWeekList\r\n");
		fwrite($fp_zcfg, "#REPORT_WEEK_TYPE:$UConfig->StatsWeekGraphType\r\n");
	}

	if ($UConfig->StatsMonthCheck)
	{
		fwrite($fp_zcfg, "#REPORT_MONTH:$UConfig->StatsMonthList\r\n");
		fwrite($fp_zcfg, "#REPORT_MONTH_TYPE:$UConfig->StatsMonthGraphType\r\n");
	}
	
	if ($UConfig->StatsListAll)
	{		
		fwrite($fp_zcfg, "#REPORT_LISTALL:$UConfig->StatsListAllUpto\r\n");
	}

	fwrite($fp_zcfg, "#WORDWEIGHT_TITLE:$UConfig->WeightTitle\r\n");
	fwrite($fp_zcfg, "#WORDWEIGHT_DESC:$UConfig->WeightDesc\r\n");
	fwrite($fp_zcfg, "#WORDWEIGHT_KEYWORDS:$UConfig->WeightKeywords\r\n");	
	fwrite($fp_zcfg, "#WORDWEIGHT_FILENAME:$UConfig->WeightFilename\r\n");	
	fwrite($fp_zcfg, "#WORDWEIGHT_HEADINGS:$UConfig->WeightHeadings\r\n");	
	fwrite($fp_zcfg, "#WORDWEIGHT_LINKTEXT:$UConfig->WeightLinktext\r\n");	
	fwrite($fp_zcfg, "#WORDWEIGHT_CONTENT:$UConfig->WeightContent\r\n");	
	fwrite($fp_zcfg, "#WORDWEIGHT_DENSITY:$UConfig->WeightDensity\r\n");	
	fwrite($fp_zcfg, "#WORDWEIGHT_SHORTURLS:$UConfig->WeightShortURLs\r\n");	
	fwrite($fp_zcfg, "#WORDWEIGHT_PROXIMITY:$UConfig->WeightProximity\r\n");	

	if ($UConfig->UseSrcPaths)
	{
		fwrite($fp_zcfg, "#USESRCPATH_SCRIPT:$UConfig->SourceScriptPath\r\n");
	}				
	
	fwrite($fp_zcfg, "#USE-AUTH:$UConfig->UseAuth\r\n");
	fwrite($fp_zcfg, "#USE-COOKIES:$UConfig->UseCookies\r\n");

	if (strlen($UConfig->Login) > 0 && strlen($UConfig->Password) > 0)
	{
		fwrite($fp_zcfg, "#AUTHINFO:$UConfig->Login\r\n");		

		$encPassword = "";
		$length = strlen($UConfig->Password);
		for ($i = 0; $i < $length; $i++) 
		{
		   $tmp = $UConfig->Password[$i];
		   for ($j = 0; $j < strlen($AUTHXORKEY); $j++) 
		   {
		       $tmp = chr(ord($tmp) ^ ord($AUTHXORKEY[$j]));
		  	}
		   $encPassword .= $tmp;
		 }

		fwrite($fp_zcfg, $encPassword );
		fwrite($fp_zcfg, "\r\n");						
	}

	fwrite($fp_zcfg, "#USE-COOKIELOGIN:$UConfig->UseCookieLogin\r\n");	
	if ($UConfig->UseCookieLogin)
	{
		fwrite($fp_zcfg, "#COOKIELOGINURL:$UConfig->CookieLoginURL\r\n");
		fwrite($fp_zcfg, "#COOKIELOGINNAME:$UConfig->CookieLoginName\r\n");
		fwrite($fp_zcfg, "#COOKIELOGINVALUE:$UConfig->CookieLoginValue\r\n");
		fwrite($fp_zcfg, "#COOKIEPARAMS:$UConfig->CookieParams\r\n");
		fwrite($fp_zcfg, "#COOKIEPASSNAME:$UConfig->CookiePasswordName\r\n");		
		
		$encPassword = "";
		$length = strlen($UConfig->CookiePasswordValue);
		for ($i = 0; $i < $length; $i++) 
		{
		   $tmp = $UConfig->CookiePasswordValue[$i];
		   for ($j = 0; $j < strlen($AUTHXORKEY); $j++) 
		   {
		       $tmp = chr(ord($tmp) ^ ord($AUTHXORKEY[$j]));
		  	}
		   $encPassword .= $tmp;
		 }
		fwrite($fp_zcfg, $encPassword );
		fwrite($fp_zcfg, "\r\n");						
	}

	if (strlen($UConfig->FTPServer) > 0)
	{
		if ($UConfig->FTPPort < 1)
			$UConfig->FTPPort = 21;	// default back to port 21.

		fwrite($fp_zcfg, "#FTPINFO_START\r\n");				
		// servername and port
		fwrite($fp_zcfg, "$UConfig->FTPServer\r\n$UConfig->FTPPort\r\n");	
		
		// login and password
		fwrite($fp_zcfg, "$UConfig->FTPLogin\r\n");	
		/*
		for (UINT c = 0; c < _tcslen($UConfig->FTPPassword); c++)
			fwrite($fp_zcfg, "%c",(UINT) $UConfig->FTPPassword[c] ^ AUTHXORKEY);
		*/
		fwrite($fp_zcfg, "TMPNOPASSWORD");
		fwrite($fp_zcfg, "\r\n");		

		// upload path		
		fwrite($fp_zcfg, "$UConfig->FTPUploadPath\r\n");
		fwrite($fp_zcfg, "#FTPINFO_END\r\n");

		if ($UConfig->FTPAuto)
			fwrite($fp_zcfg, "#FTPAUTO:1\r\n");

		if ($UConfig->FTPDontUploadTemplate)
			fwrite($fp_zcfg, "#FTPNOTEMPLATE:1\r\n");

		if ($UConfig->FTPRenameTmp)
			fwrite($fp_zcfg, "#FTPRENAMETMP:1\r\n");

		// need to always print out (unlike above) to say if option has been disabled
		// (defaults to on if this line is missing - eg. old configs)
		fwrite($fp_zcfg, "#FTPSETPERMISSIONS:$UConfig->FTPSetFilePermissions\r\n");

		if ($UConfig->FTPUsePASV)
			fwrite($fp_zcfg, "#FTPUSEPASV:1\r\n");
	}

	fwrite($fp_zcfg, "#BINUSEDESC:$UConfig->BinaryUseDescFiles\r\n");

	fwrite($fp_zcfg, "#BINEXTRACTSTRINGS:$UConfig->BinaryExtractStrings\r\n");	

	//Plugin/Filetype options
	$tmpLine = "";
	if ($UConfig->PluginConfig->PdfUseDescFiles)
		$tmpLine  .= "PDF|";
	if ($UConfig->PluginConfig->DocUseDescFiles)
		$tmpLine  .= "DOC|";
	if ($UConfig->PluginConfig->PptUseDescFiles)
		$tmpLine  .= "PPT|";
	if ($UConfig->PluginConfig->RtfUseDescFiles)
		$tmpLine  .= "RTF|";
	if ($UConfig->PluginConfig->SwfUseDescFiles)
		$tmpLine  .= "SWF|";
	if ($UConfig->PluginConfig->WpdUseDescFiles)
		$tmpLine  .= "WPD|";
	if ($UConfig->PluginConfig->XlsUseDescFiles)
		$tmpLine  .= "XLS|";	
	if ($UConfig->PluginConfig->DjvuUseDescFiles)
		$tmpLine  .= "DJVU|";	
	if ($UConfig->PluginConfig->ImgUseDescFiles)
		$tmpLine  .= "IMAGE|";	
	if ($UConfig->PluginConfig->Mp3UseDescFiles)
		$tmpLine  .= "MP3|";	
	if ($UConfig->PluginConfig->DwfUseDescFiles)
		$tmpLine  .= "DWF|";	
	if ($UConfig->PluginConfig->OfficeXmlUseDescFiles)
		$tmpLine  .= "OFFICE|";	
	if ($UConfig->PluginConfig->TorrentUseDescFiles)
		$tmpLine  .= "BT|";
	if ($UConfig->PluginConfig->MhtUseDescFiles)
		$tmpLine  .= "MHT|";
	if ($UConfig->PluginConfig->ZipUseDescFiles)
		$tmpLine  .= "ZIP|";
	fwrite($fp_zcfg, "#PLUGIN_DESCFILES:$tmpLine\n");

	$tmpLine = "";
	if ($UConfig->PluginConfig->PdfUseMeta)
		$tmpLine  .= "PDF|";
	if ($UConfig->PluginConfig->DocUseMeta)
		$tmpLine  .= "DOC|";
	if ($UConfig->PluginConfig->PptUseMeta)
		$tmpLine  .= "PPT|";
	if ($UConfig->PluginConfig->RtfUseMeta)
	$tmpLine  .= "RTF|";
	if ($UConfig->PluginConfig->SwfUseMeta)
		$tmpLine  .= "SWF|";
	if ($UConfig->PluginConfig->WpdUseMeta)
		$tmpLine  .= "WPD|";
	if ($UConfig->PluginConfig->XlsUseMeta)
		$tmpLine  .= "XLS|";	
	if ($UConfig->PluginConfig->DjvuUseMeta)
		$tmpLine  .= "DJVU|";	
	if ($UConfig->PluginConfig->ImgUseMeta)
		$tmpLine  .= "IMAGE|";	
	if ($UConfig->PluginConfig->Mp3UseMeta)
		$tmpLine  .= "MP3|";	
	if ($UConfig->PluginConfig->DwfUseMeta)
		$tmpLine  .= "DWF|";	
	if ($UConfig->PluginConfig->OfficeXmlUseMeta)
		$tmpLine  .= "OFFICE|";	
	fwrite($fp_zcfg, "#PLUGIN_USEMETA:$tmpLine\n");

	$tmpLine = "";
	if ($UConfig->PluginConfig->Mp3UseTechnical)
			$tmpLine .= "MP3|";
	if ($UConfig->PluginConfig->ImgUseTechnical)
			$tmpLine .= "IMAGE|";	
	if ($UConfig->PluginConfig->DwfUseTechnical)
			$tmpLine .= "DWF|";	
	fwrite($fp_zcfg,  "#PLUGIN_USETECHNICAL:$tmpLine\n");

	$tmpLine = "";
	if ($UConfig->PluginConfig->OfficeXmlTextOnly)
		$tmpLine .= "OFFICE|";
	fwrite($fp_zcfg,  "#PLUGIN_TEXTONLY:$tmpLine\n");


	if ($UConfig->PluginConfig->PdfUsePassword && strlen($UConfig->PluginConfig->PdfPassword) > 0)		
	{
		fwrite($fp_zcfg, "#PLUGIN_PDF_USEPASSWORD:1\n");		
		
		$encPassword = "";
		$length = strlen($UConfig->PluginConfig->PdfPassword);
		for ($i = 0; $i < $length; $i++) 
		{
		   $tmp = $UConfig->PluginConfig->PdfPassword[$i];
		   for ($j = 0; $j < strlen($AUTHXORKEY); $j++) 
		   {
		       $tmp = chr(ord($tmp) ^ ord($AUTHXORKEY[$j]));
		  	}
		   $encPassword .= $tmp;
		 }

		fwrite($fp_zcfg, $encPassword );
		fwrite($fp_zcfg, "\r\n");				
	} 
	
	$tmpLine = $UConfig->PluginConfig->PdfToTextMethod;
	fwrite($fp_zcfg, "#PLUGIN_PDF_METHOD:$tmpLine\n");	
	$tmpLine = $UConfig->PluginConfig->PdfHighlight;
	fwrite($fp_zcfg, "#PLUGIN_PDF_HIGHLIGHT:$tmpLine\n");	
	$tmpLine = $UConfig->PluginConfig->ImgMinFilesize;	
	fwrite($fp_zcfg, "#PLUGIN_IMG_MINFILESIZE:$tmpLine\n");	
	$tmpLine = $UConfig->PluginConfig->ZipExtractFiles;	
	fwrite($fp_zcfg, "#PLUGIN_ZIP_EXTRACT:$tmpLine\n");	




#ifndef __CUSTOM_BUILD_NOPLUGINS
	// Options restricted to Standard or Pro editions
	//if (edition != FREE_EDITION)
	//{
	//	OnSavePluginConfig(fp);
	//}
#endif

	// Options restricted to Pro edition only - however currently enabled
	//if (edition == PRO_EDITION || edition == ENTERPRISE_EDITION)
	{
		fwrite($fp_zcfg, "#MAXPAGES_LIMIT:$UConfig->MAXPAGES\r\n");
		fwrite($fp_zcfg, "#MAXWORDS_LIMIT:$UConfig->MAXWORDS\r\n");
		if ($UConfig->LimitMaxWords)
			fwrite($fp_zcfg, "#USE_MAXWORDS_LIMIT:$UConfig->LimitMaxWords\r\n");
		fwrite($fp_zcfg, "#MAXFILESIZE_LIMIT:$UConfig->MAX_FILE_SIZE\r\n");
		fwrite($fp_zcfg, "#DESCLENGTH_LIMIT:$UConfig->DESCLENGTH\r\n");
		if ($UConfig->LimitPerStartPt)
			fwrite($fp_zcfg, "#MAXPAGES_PER_STARTPT_LIMIT:$UConfig->MAXPAGES_PER_STARTPT\r\n");
		if ($UConfig->LimitWordsPerPage)
			fwrite($fp_zcfg, "#MAXWORDS_PER_PAGE_LIMIT:$UConfig->MAXWORDS_PER_PAGE\r\n");
		if ($UConfig->LimitURLsPerStartPt)
			fwrite($fp_zcfg, "#MAXURLVISITS_PER_STARTPT_LIMIT:$UConfig->MAXURLVISITS_PER_STARTPT\r\n");
		if ($UConfig->TruncateTitleLen)
			fwrite($fp_zcfg, "#TRUNCATE_TITLELEN:$UConfig->MAXTITLELENGTH\r\n");
	}
	fwrite($fp_zcfg, "#MAXWORDLENGTH_LIMIT:$UConfig->MAXWORDLENGTH\r\n");
	
	fwrite($fp_zcfg, "#OPTIMIZE_SETTING:$UConfig->OptimizeSetting\r\n");

	// write out extensions
	fwrite($fp_zcfg, "#EXTENSIONS_START\r\n");
			
	foreach ($UConfig->ExtensionList as $nextExt)
	{
		fwrite($fp_zcfg, "$nextExt->Ext|FILETYPE:$nextExt->FileType");		

		if (strlen($nextExt->ImageURL) > 0)
			fwrite($fp_zcfg, "|IMGURL:$nextExt->ImageURL");
		if ($nextExt->UseThumbs != 0)
			fwrite($fp_zcfg, "|USETHUMBS");
		if (strlen($nextExt->ThumbsExt) > 0)
			fwrite($fp_zcfg, "|THUMBSEXT:$nextExt->ThumbsExt");
		if (strlen($nextExt->ThumbsPath) > 0)
			fwrite($fp_zcfg, "|THUMBSPATH:$nextExt->ThumbsPath");
		if (strlen($nextExt->ThumbsFilenamePrefix) > 0)
			fwrite($fp_zcfg, "|THUMBSPREFIX:$nextExt->ThumbsFilenamePrefix");
		if (strlen($nextExt->ThumbsFilenamePostfix) > 0)
			fwrite($fp_zcfg, "|THUMBSPOSTFIX:$nextExt->ThumbsFilenamePostfix");		
		fwrite($fp_zcfg, "\r\n");
	
	}
	
	fwrite($fp_zcfg, "#EXTENSIONS_END\r\n");

	// write out Additional Start points
	if ($UConfig->starturl_list)
	{
		fwrite($fp_zcfg, "#ADDSTARTURLS_START\r\n");
				
		foreach ($UConfig->starturl_list as $nextStartURL)
		{
			// Note that urltype, uselimit, and limit (and boost) is pipe separated on the same line.
			fwrite($fp_zcfg, "$nextStartURL->url\r\n$nextStartURL->urltype|$nextStartURL->uselimit|$nextStartURL->limit|$nextStartURL->boost\r\n$nextStartURL->baseURL\r\n");
		}
		fwrite($fp_zcfg, "#ADDSTARTURLS_END\r\n");
	}

	// write out offline additional starting dirs (offline mode)
	if ($UConfig->startdir_list)
	{
		fwrite($fp_zcfg, "#ADDSTARTDIRS_START\r\n");
		
		foreach ($UConfig->startdir_list as $nextStartDir)
		{
			fwrite($fp_zcfg, "$nextStartDir->url\r\n$nextStartDir->baseURL\r\n");
		}
		
		fwrite($fp_zcfg, "#ADDSTARTDIRS_END\r\n");
	}

	// write out skip pages
	fwrite($fp_zcfg, "#SKIPPAGES_START\r\n");

	foreach ($UConfig->SkipPages as $nextSkipPage)
	{
		fwrite($fp_zcfg, "$nextSkipPage\r\n");
	}
	
	fwrite($fp_zcfg, "#SKIPPAGES_END\r\n");

	// write out skip words
	fwrite($fp_zcfg, "#SKIPWORDS_START\r\n");
	
	foreach ($UConfig->SkipWords as $nextSkipWord)
	{
		fwrite($fp_zcfg, "$nextSkipWord\r\n");
	}
	
	fwrite($fp_zcfg, "#SKIPWORDS_END\r\n");

	fwrite($fp_zcfg, "#USECATS:$UConfig->UseCats\r\n");
	fwrite($fp_zcfg, "#USEDEFCATNAME:$UConfig->UseDefCatName\r\n");	
	fwrite($fp_zcfg, "#SEARCHMULTICATS:$UConfig->SearchMultiCats\r\n");
	fwrite($fp_zcfg, "#DISPLAYCATSUMMARY:$UConfig->DisplayCatSummary\r\n");

	if(empty($UConfig->cats_list) == false)
	{
		fwrite($fp_zcfg, "#CATEGORIES_START\r\n");
		
		// first line is default cat name
		if (strlen($UConfig->DefCatName) > 0)		
			fwrite($fp_zcfg, "$UConfig->DefCatName\r\n");
		else 
			fwrite($fp_zcfg, "Misc.\r\n");	// we write this out if it was blank
			
		foreach ($UConfig->cats_list as $nextCat)
		{
			fwrite($fp_zcfg, "$nextCat->name\r\n");
			fwrite($fp_zcfg, "$nextCat->pattern\r\n");
			
			// pipe is not permitted for description, as a last resort we'll truncate it if there is
			if (strstr($nextCat->description, "|") != false)	
			{
				$len = strlen($nextCat->description) - strpos($nextCat->description, "|");
				$nextCat->description = substr($nextCat->description, 0, $len);
			}

			fwrite($fp_zcfg, "$nextCat->description|$nextCat->IsExclusive|\r\n");	
		}
	
		fwrite($fp_zcfg, "#CATEGORIES_END\r\n");
	}


	if ($UConfig->syn_list != NULL) 
	{				
		fwrite($fp_zcfg, "#SYNONYMS_START:\r\n");			
		
		foreach ($UConfig->syn_list as $nextSyn)
		{
			fwrite($fp_zcfg, "$nextSyn->word,$nextSyn->synonyms\r\n");
		}

		fwrite($fp_zcfg, "#SYNONYMS_END\r\n");
	}

	if ($UConfig->RecommendedList != NULL) 
	{				
		fwrite($fp_zcfg, "#RECOMMENDED_START:\r\n");			
		
		foreach ($UConfig->RecommendedList as $nextRec)
		{			
			fwrite($fp_zcfg, "$nextRec->word\r\n$nextRec->URL\r\n$nextRec->title\r\n$nextRec->desc\r\n$nextRec->imgURL\r\n");			
		}
		
		fwrite($fp_zcfg, "#RECOMMENDED_END\r\n");
	}
	fwrite($fp_zcfg, "#RECOMMENDED_MAX:$UConfig->RecommendedMax\r\n");

	if ($UConfig->metafield_list != NULL) 
	{				
		fwrite($fp_zcfg, "#METAFIELD_START:\r\n");			
			
		foreach ($UConfig->metafield_list as $nextMeta)
		{
			fwrite($fp_zcfg, "$nextMeta->name\r\n$nextMeta->type\r\n$nextMeta->showname\r\n$nextMeta->formname\r\n$nextMeta->method\r\n");			
		
			if ($nextMeta->type == $METAFIELD_TYPE_DROPDOWN || $nextMeta->type == $METAFIELD_TYPE_MULTI)
			{
					// only write dropdown values IF it is dropdown type
				fwrite($fp_zcfg, "#DROPDOWN_START:\r\n");
				foreach ($nextMeta->DropdownValues as $nextDDValue)
				{
					fwrite($fp_zcfg, "$nextDDValue\r\n");
				}
				fwrite($fp_zcfg, "#DROPDOWN_END\r\n");
			}
		}
		
		fwrite($fp_zcfg, "#METAFIELD_END\r\n");
		fwrite($fp_zcfg, "#METAFIELD_MONEY_CURRENCY:$UConfig->MetaMoneyCurrency\r\n");
		fwrite($fp_zcfg, "#METAFIELD_MONEY_SHOWDEC:$UConfig->MetaMoneyShowDecimals\r\n");
	}	

	// write out content filter
	fwrite($fp_zcfg, "#USEFILTER:$UConfig->UseContentFilter\r\n");
	// write out skip words
	fwrite($fp_zcfg, "#FILTER_START\r\n");	
	foreach ($UConfig->ContentFilterRules as $filterName)
	{
		fwrite($fp_zcfg, "$filterName\r\n");
	}
	fwrite($fp_zcfg, "#FILTER_END\r\n");

	// write out auto complete rules
	fwrite($fp_zcfg, "#USEAUTOCOMPLETE:$UConfig->UseAutoComplete\r\n");	
	fwrite($fp_zcfg, "#AUTOCOMPLETE_START\r\n");
	foreach ($UConfig->AutoCompleteRules as $autoRule)
	{
		fwrite($fp_zcfg, "$autoRule\r\n");
	}
	fwrite($fp_zcfg, "#AUTOCOMPLETE_END\r\n");
	
	fwrite($fp_zcfg, "#USEAUTOCOMPLETE_IMPORT:$UConfig->UseAutoCompleteInclude\r\n");	
	fwrite($fp_zcfg, "#AUTOCOMPLETE_IMPORTNUM:$UConfig->AutoCompleteIncludeTopNum\r\n");	
	fwrite($fp_zcfg, "#AUTOCOMPLETE_IMPORTURL:$UConfig->AutoCompleteIncludeURL\r\n");	
	fwrite($fp_zcfg, "#AUTOCOMPLETE_USEPAGETITLE:$UConfig->AutoCompleteUsePageTitle\r\n");	
	fwrite($fp_zcfg, "#AUTOCOMPLETE_USEMETADESC:$UConfig->AutoCompleteUseMetaDesc\r\n");	

	fwrite($fp_zcfg, "#SITEMAP_TXT:$UConfig->SitemapTXT\r\n");
	fwrite($fp_zcfg, "#SITEMAP_XML:$UConfig->SitemapXML\r\n");
	fwrite($fp_zcfg, "#SITEMAP_UPLOAD:$UConfig->SitemapUpload\r\n");
	fwrite($fp_zcfg, "#SITEMAP_UPLOADPATH:$UConfig->SitemapUploadPath\r\n");
	fwrite($fp_zcfg, "#SITEMAP_USEPAGEBOOST:$UConfig->SitemapUsePageBoost\r\n");
	fwrite($fp_zcfg, "#SITEMAP_USEBASEURL:$UConfig->SitemapUseBaseURL\r\n");
	fwrite($fp_zcfg, "#SITEMAP_BASEURL:$UConfig->SitemapBaseURL\r\n");	

	fwrite($fp_zcfg, "#USEPROXYSERVER:$UConfig->UseProxyServer\r\n");
	fwrite($fp_zcfg, "#PROXYSERVER:$UConfig->ProxyServer\r\n");	


	fclose ($fp_zcfg);

	return true;
	
}


//Return false on failure
function LoadZCFGFile($zcfg_fpath, $createNew)
{
	PrintDebug("LoadZCFGFile start"); //temp debug
	
	$ZCFGVersionNumber = 7;
	
	global $UConfig;
	global $MAX_CONFIG_LINELEN;
	global $OUTPUT_PHP, $OUTPUT_ASP, $OUTPUT_CGI, $OUTPUT_JSFILE;
	global $OS_WINDOWS, $OS_LINUX, $OS_BSD, $OS_FLYINGANT;
	global $TmpExtensions;
	global $AUTHXORKEY;
	global $MAXSKIPPAGES;
	global $MAXSKIPPAGELEN;
	global $MAXDESCLEN;
	global $TITLELEN;
	global $METAFIELD_MONEY_COUNT;
	global $MAXAUTOCOMPLETE;
	global $MAXFILTERRULES;
	global $METAFIELD_TYPE_COUNT;
	global $METAFIELD_TYPE_DROPDOWN;
	global $METAFIELD_TYPE_MULTI3;
	global $METAFIELD_DROPDOWN_MA;
	global $URLTYPE_INDEX_AND_FOLLOW;
	global $METAFIELD_TYPE_MULTI;
	global $MAXSKIPWORDS;
	global $METAFIELD_DROPDOWN_MAX;
	global $NUM_THROTTLE_SETTINGS;
	global $THROTTLE_DELAY_VALUES;

	//Set some default values
	$UConfig->MAXWORDS = 30000;	
	$UConfig->MAXPAGES = 100;
	$UConfig->MAX_FILE_SIZE = 1048576;	
	$UConfig->DESCLENGTH = 150;
	$UConfig->MaxResultsPerQuery= 1000;
	$UConfig->MaxContextSeeks = 500;
	$UConfig->MAXWORDLENGTH = 35;
	$UConfig->AutoCompleteIncludeTopNum = 500;
	
	$fp_zcfg = "";

	if($createNew)
	{
		//Create empty file to check for write permissions
		$fp_zcfg = fopen($zcfg_fpath, "wb");
		if(!$fp_zcfg)
		{
			return false;
		}
		fclose($fp_zcfg);
		return true;
	}
	else
		$fp_zcfg = fopen($zcfg_fpath, "rb");
	
	if(!$fp_zcfg)
	{
		return false;
	}
			
	$IsUTF16 = false;
	
	//Read first line, check for version and BOM (eg UTF-16 configs from windows)
	//This need more work
   $bom = fread($fp_zcfg, 2);
   PrintDebug("Bom: " . $bom);
  
   if ($bom == b"\xFF\xFE")
   {
      $IsUTF16 =  true; 			//UTF-16 - need to convert to UTF8
      PrintDebug("Config file is UTF-16");
   }
   else if ($bom == b"\xEF\xBB") 	//UTF-8 //\xBF
   {
   		PrintDebug("Config file is UTF-8");
  	}
  	else
  	{
  			PrintDebug("Config file is single byte");
  		    rewind($fp_zcfg);
  	}
      
	$zcfgLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);	
	
	//If in UTF-16 (older windows format)  need to convert to UTF8
	if($IsUTF16) 	
		$zcfgLine = iconv("UTF-16LE", "UTF-8", $zcfgLine);
	
	//Due to PHP's retarded nature this function can return false on failure and 0 on success
	if(strpos($zcfgLine, "__7_0") !== false)
		$ZCFGVersionNumber = 7;	
	else if(strpos($zcfgLine, "__6_0")!== false)
		$ZCFGVersionNumber = 6;	
	else if(strpos($zcfgLine, "__5_0")!== false) //Below 5 can be single buyte, not sure how handled in PHP
		$ZCFGVersionNumber = 5;	
	else if(strpos($zcfgLine, "__4_0")!== false)
		$ZCFGVersionNumber = 4;	

	PrintDebug("ZCFGVersionNumber = " . $ZCFGVersionNumber);
		
		
while (!feof($fp_zcfg))
{
	//Get next line
	$zcfgLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);

	PrintDebug(	$zcfgLine);

	if($IsUTF16)
	$zcfgLine = iconv("UTF-16LE", "UTF-8", $zcfgLine);

	if ($zcfgValue = GetConfigValue($zcfgLine, "#STARTDIR:"))
	{
		$UConfig->startdir = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#SPIDERURL:"))
	{
		$UConfig->spiderURL = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#BASEURL:"))
	{
		$UConfig->baseURL = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#OUTDIR:"))
	{
		$UConfig->outdir = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#SPIDERURLTYPE:"))
	{
		$UConfig->spiderURLtype = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#SPIDERURLUSELIMIT:"))
	{
		$UConfig->spiderURLUseLimit = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#SPIDERURLLIMIT:"))
	{
		$UConfig->spiderURLLimit = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#SPIDERURLBOOST:"))
	{
		$UConfig->spiderURLBoost = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#USE-CRC:"))
	{
		$UConfig->CRC32 = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#CURRENTMODE:"))
	{
		$UConfig->currentMode = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#DLTHREADS:"))
	{
		$UConfig->NumDownloadThreads = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#NOCACHE:"))
	{
		$UConfig->NoCache = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#BEEP-ON-FINISH:"))
	{
		$UConfig->BeepOnFinish = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#THROTTLEDELAY:"))
	{
		$UConfig->ThrottleDelay = $zcfgValue;

		if($UConfig->ThrottleDelay < 0 || $UConfig->ThrottleDelay > $THROTTLE_DELAY_VALUES[$NUM_THROTTLE_SETTINGS-1]) 
			$UConfig->ThrottleDelay = 0;

		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#OUTPUT:"))
	{
		if ($zcfgValue == "JSFILE")
		$UConfig->OutputFormat = $OUTPUT_JSFILE;
		else if ($zcfgValue == "ASP")
		$UConfig->OutputFormat = $OUTPUT_ASP;
		else if ($zcfgValue == "CGI")
		$UConfig->OutputFormat = $OUTPUT_CGI;
		else
		$UConfig->OutputFormat = $OUTPUT_PHP;
		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#OUTPUT_OS:"))
	{
		$UConfig->OutputOS = $zcfgValue;
		if ($UConfig->OutputOS < $OS_WINDOWS || $UConfig->OutputOS > $OS_FLYINGANT)
		$UConfig->OutputOS = $OS_WINDOWS;	// defaults to Windows if invalid
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#ISDOTNET:"))
	{
		if ($zcfgValue == 1)
		{
			$UConfig->IsASPDotNet = 1;
			$UConfig->OutputOS = $OS_WINDOWS;
			$UConfig->OutputFormat = $OUTPUT_CGI;
			$UConfig->DotNetUseFormTags = true;
			$UConfig->DotNetUsePostBacks = false;
		}
		else
		$UConfig->IsASPDotNet = 0;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#VERBOSE:"))
	{
		if ($zcfgValue == 0)
		{
			$UConfig->Verbose = false;
		}
		else
		{
			$UConfig->Verbose = true;
		}
		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#APPDATAPATH:"))
	{
		$UConfig->AppDataPath  = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#OSF_TEMPDATAPATH:"))
	{
		$UConfig->TmpDataPath = $zcfgValue;
		continue;
	}


	if ($zcfgValue= GetConfigValue($zcfgLine, "#OSF_DEVICETYPE:"))
	{
		$UConfig->OSF_DeviceType = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#OSF_MOUNTNAME:"))
	{

		$UConfig->OSF_MountName = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#OSF_PARTITIONNUM:"))
	{
		$UConfig->OSF_PartitionNum = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#OSF_MOUNTPATH:"))
	{
		$UConfig->OSF_MountPath = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#LOGMODE:"))
	{
		if ( $zcfgValue < 3)
		{
			$UConfig->LogMode = $zcfgValue;
		}
		else
		$UConfig->LogMode = 0;

		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#LOGOPTIONS:"))
	{
		$UConfig->LogIndexed = false;
		$UConfig->LogSkipped = false;
		$UConfig->LogFiltered = false;
		$UConfig->LogInit = false;
		$UConfig->LogDownload = false;
		$UConfig->LogUpload = false;
		$UConfig->LogFileIO = false;
		$UConfig->LogPlugin = false;
		$UConfig->LogInfo = false;
		$UConfig->LogError = false;
		$UConfig->LogWarning = false;
		$UConfig->LogQueue = false;
		$UConfig->LogThread = false;
		$UConfig->LogSummary = false;
		$UConfig->LogBrokenLinks = false;

		if (strlen($zcfgValue) > 1)
		{
			if (strstr($zcfgValue,"SCANNED") != FALSE)
			$UConfig->LogIndexed = true;
			if (strstr($zcfgValue, "INDEXED") != FALSE)
			$UConfig->LogIndexed = true;
			if (strstr($zcfgValue, "SKIPPED") != FALSE)
			$UConfig->LogSkipped = true;
			if(strstr($zcfgValue, "FILTERED") != FALSE)
			$UConfig->LogFiltered = true;
			if(strstr($zcfgValue, "INIT") != FALSE)
			$UConfig->LogInit = true;
			if(strstr($zcfgValue, "DOWNLOAD") != FALSE)
			$UConfig->LogDownload = true;
			if(strstr($zcfgValue, "UPLOAD") != FALSE)
			$UConfig->LogUpload = true;
			if(strstr($zcfgValue, "FILEIO") != FALSE)
			$UConfig->LogFileIO = true;
			if(strstr($zcfgValue, "PLUGIN") != FALSE)
			$UConfig->LogPlugin = true;
			if(strstr($zcfgValue, "INFO") != FALSE)
			$UConfig->LogInfo = true;
			if(strstr($zcfgValue, "ERROR") != FALSE)
			$UConfig->LogError = true;
			if(strstr($zcfgValue, "WARNING") != FALSE)
			$UConfig->LogWarning = true;
			if(strstr($zcfgValue, "QUEUE") != FALSE)
			$UConfig->LogQueue = true;
			if(strstr($zcfgValue, "SUMMARY") != FALSE)
			$UConfig->LogSummary = true;
			if(strstr($zcfgValue, "THREAD") != FALSE)
			$UConfig->LogThread = true;
			if(strstr($zcfgValue, "BROKEN") != FALSE)
			$UConfig->LogBrokenLinks = true;
		}
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#LOGWRITETOFILE:"))
	{
		if ($zcfgValue == 0)
		$UConfig->LogWriteToFile = false;
		else
		$UConfig->LogWriteToFile = true;
		continue;
	}



	if ($zcfgValue = GetConfigValue($zcfgLine, "#LOGHTMLERRORS:"))
	{
		if ($zcfgValue == 0)
		$UConfig->LogHTMLErrors = false;
		else
		$UConfig->LogHTMLErrors = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#LOGWRITETOFILENAME:"))
	{
		$UConfig->LogSaveToFilename = $zcfgValue;

		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#LOGAPPENDDATETIME:"))
	{

		if ($zcfgValue == 0)
		$UConfig->LogAppendDatetime = false;
		else
		$UConfig->LogAppendDatetime = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#LOGDEBUGMODE:"))
	{

		if ($zcfgValue == 0)
		$UConfig->LogDebugMode = false;
		else
		$UConfig->LogDebugMode = true;
		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#SCAN_NOEXTENSION:"))
	{
		$UConfig->ScanNoExtension = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#SCAN_UNKNOWNEXTENSIONS:"))
	{
		if ($zcfgValue == 0)
		$UConfig->ScanUnknownExtensions = false;
		else
		$UConfig->ScanUnknownExtensions = true;
		continue;
	}
	if ($zcfgValue  = GetConfigValue($zcfgLine, "#SCAN_ALLEMAILATTACHMENTS:"))
	{
		if ($zcfgValue == 0)
		$UConfig->ScanAllEmailAttachments = false;
		else
		$UConfig->ScanAllEmailAttachments = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#SCAN_FILELINKS:"))
	{
		$UConfig->ScanFileLinks = $zcfgValue;
		continue;
	}


	if (strncmp($zcfgLine, "#EXTENSIONS_START", strlen("#EXTENSIONS_START")) == 0)
	{
		$UConfig->ExtensionList = GetExtensionValues($fp_zcfg);
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#PLUGINOPENNEWWINDOW:"))
	{
		$UConfig->PluginOpenNewWindow = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#SKIPUNDERSCORE:"))
	{
		$UConfig->SkipUnderscore = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#MINWORDLEN:"))
	{
		$UConfig->MinWordLen = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#SCAN_ROBOTSTXT:"))
	{
		$UConfig->UseRobotsTxt = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#SCAN_CHECKTHUMBS:"))
	{
		$UConfig->CheckThumbnailsExist = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#PARSEJSLINKS:"))
	{
		$UConfig->ParseJSLinks = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#SCAN_USELOCALDESCPATH:"))
	{							
		if ($zcfgValue == 0)
			$UConfig->UseLocalDescPath = false;
		else
			$UConfig->UseLocalDescPath = true;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#SCAN_LOCALDESCPATH:"))
	{
		$UConfig->LocalDescPath = $zcfgValue;
		continue;
	}
	

	if ($zcfgValue = GetConfigValue($zcfgLine, "#REWRITELINKS:"))
	{
		if ($zcfgValue == 0)
		$UConfig->RewriteLinks = false;
		else
		$UConfig->RewriteLinks = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#REWRITEFIND:"))
	{
		$UConfig->RewriteFind = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#REWRITEWITH:"))
	{
		$UConfig->RewriteWith = $zcfgValue;
		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#INDEXOPTIONS:"))
	{
		$UConfig->IndexMetaDesc = false;
		$UConfig->IndexContent = false;
		$UConfig->IndexTitle = false;
		$UConfig->IndexKeywords = false;
		$UConfig->IndexFilename = false;
		$UConfig->IndexAuthor = false;
		$UConfig->IndexLinkText = false;
		$UConfig->IndexAltText = false;
		$UConfig->IndexDCMeta = false;
		$UConfig->IndexParamTags = false;
		$UConfig->IndexURLDomain = false;
		$UConfig->IndexURLPath = false;
		if (strlen($zcfgValue) > 1)
		{
			if (strstr($zcfgValue, "TITLE") != NULL)
			$UConfig->IndexTitle = true;
			if (strstr($zcfgValue, "KEYWORDS") != NULL)
			$UConfig->IndexKeywords = true;
			if (strstr($zcfgValue, "METADESC") != NULL)
			$UConfig->IndexMetaDesc = true;
			if (strstr($zcfgValue, "CONTENT") != NULL)
			$UConfig->IndexContent = true;
			if (strstr($zcfgValue, "FILENAME") != NULL)
			$UConfig->IndexFilename = true;
			if (strstr($zcfgValue, "AUTHOR") != NULL)
			$UConfig->IndexAuthor = true;
			if (strstr($zcfgValue, "LINKTEXT") != NULL)
			$UConfig->IndexLinkText = true;
			if (strstr($zcfgValue, "ALTTEXT") != NULL)
			$UConfig->IndexAltText = true;
			if (strstr($zcfgValue, "DCMETA") != NULL)
			$UConfig->IndexDCMeta = true;
			if (strstr($zcfgValue, "PARAM") != NULL)
			$UConfig->IndexParamTags = true;
			if (strstr($zcfgValue, "URLPATH") != NULL)
			$UConfig->IndexURLPath = true;
			if (strstr($zcfgValue, "URLDOMAIN") != NULL)
			$UConfig->IndexURLDomain = true;
		}
		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#SKIPUNDERSCORE:"))
	{
		if ($zcfgValue == 0)
		$UConfig->SkipUnderscore = 0;
		else
		$UConfig->SkipUnderscore = 1;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#SKIPURLCASE:"))
	{
		if ($zcfgValue == 0)
		$UConfig->SkipURLCase = 0;
		else
		$UConfig->SkipURLCase = 1;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#MINWORDLEN:"))
	{
		$UConfig->MinWordLen = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#FORMFORMAT:"))
	{
		$UConfig->FormFormat = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#HIGHLIGHTING:"))
	{
		if ($zcfgValue == 0)
		$UConfig->Highlighting = 0;
		else
		$UConfig->Highlighting = 1;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#GOTOHIGHLIGHT:"))
	{
		if ($zcfgValue == 0)
		$UConfig->GotoHighlight = 0;
		else
		$UConfig->GotoHighlight = 1;
		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#RESULTOPTIONS:"))
	{
		$UConfig->ResultNumber = 0;
		$UConfig->ResultTitle = 0;
		$UConfig->ResultMetaDesc = 0;
		$UConfig->ResultContext = 0;
		$UConfig->ResultTerms = 0;
		$UConfig->ResultScore = 0;
		$UConfig->ResultDate = 0;
		$UConfig->ResultURL = 0;
		$UConfig->ResultFilesize = 0;
		if (strlen($zcfgValue) > 1)
		{
			if (strstr($zcfgValue, "NUMBER") != NULL)
			$UConfig->ResultNumber = 1;
			if (strstr($zcfgValue, "TITLE") != NULL)
			$UConfig->ResultTitle = 1;
			if (strstr($zcfgValue, "METADESC") != NULL)
			$UConfig->ResultMetaDesc = 1;
			if (strstr($zcfgValue, "CONTEXT") != NULL)
			$UConfig->ResultContext = 1;
			if (strstr($zcfgValue,"TERMS") != NULL)
			$UConfig->ResultTerms = 1;
			if (strstr($zcfgValue, "SCORE") != NULL)
			$UConfig->ResultScore = 1;
			if (strstr($zcfgValue, "DATE") != NULL)
			$UConfig->ResultDate = 1;
			if (strstr($zcfgValue, "URL") != NULL)
			$UConfig->ResultURL = 1;
			if (strstr($zcfgValue, "FILESIZE") != NULL)
			$UConfig->ResultFilesize = 1;
		}
		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#USE-UTF8:"))
	{
		if ($zcfgValue == 0)
		$UConfig->UseUTF8 = 0;
		else
		$UConfig->UseUTF8 = 1;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#CODEPAGE:"))
	{
		$UConfig->Codepage = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#USESTEMMING:"))
	{
		if ($zcfgValue == 0)
		$UConfig->UseStemming = false;
		else
		$UConfig->UseStemming = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#STEMALGO:"))
	{ 
		$UConfig->StemmingLanguageIndex = $zcfgValue;
		continue;
	}
	
	//Have to treat MAPACCENTS differently as it can be an emppty config value and doesn't work with GetConfigValue
	if(strpos($zcfgLine, "#MAPACCENTS:") !== FALSE)
	{
		PrintDebug("Reading MAPACCENTS");
		$UConfig->MapAccents = true;
		$UConfig->MapAccentChars = true;
		$UConfig->MapUmlautChars = true;
		$UConfig->MapLigatureChars = true;

		if (strlen($zcfgLine) > 1)
		{

				
			if (strpos($zcfgLine, "NOACCENTS") !== false)
				$UConfig->MapAccentChars = false;
			if (strpos($zcfgLine, "NOUMLAUTS") !== false)
				$UConfig->MapUmlautChars = false;
			if (strpos($zcfgLine, "NOLIGATURES") !== false)
				$UConfig->MapLigatureChars = false;
				
		PrintDebug("MapAccentChars " . $UConfig->MapAccentChars  . " MapUmlautChars " . $UConfig->MapUmlautChars . " MapLigatureChars " . $UConfig->MapLigatureChars );
		}
		
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#DIGRAPHS:"))
	{
		if ($zcfgValue == 0)
		$UConfig->MapAccentsToDigraphs = false;
		else
		$UConfig->MapAccentsToDigraphs = true;
		continue;
	}
	
	if ($zcfgValue = GetConfigValue($zcfgLine, "#MAPLATINLIGATURES:"))
	{
		if ($zcfgValue == 0)
		$UConfig->MapLatinLigatureChars = false;
		else
		$UConfig->MapLatinLigatureChars = true;
		continue;
	}
	
	
	if ($zcfgValue = GetConfigValue($zcfgLine, "#ZLANGFILE:"))
	{
		if (strlen($zcfgValue) > 1)
		{
			$UConfig->LanguageFile = $zcfgValue;
		}
		continue;
	}


	if ($zcfgValue = GetConfigValue($zcfgLine, "#USEXML:"))
	{

		if ($zcfgValue == 0)
		$UConfig->UseXML = false;
		else
		$UConfig->UseXML = true;
		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#XMLTITLE:"))
	{
		$UConfig->XMLTitle =  $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#XMLDESC:"))
	{
		$UConfig->XMLDescription = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#XMLURL:"))
	{
		$UConfig->XMLLink = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#XMLXSLTURL:"))
	{
		$UConfig->XMLStyleSheetURL = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#XML_OPENSEARCH_DESCURL:"))
	{
		$UConfig->XMLOpenSearchDescURL = $zcfgValue;
		continue;
	}


	if ($zcfgValue = GetConfigValue($zcfgLine, "#XMLHIGHLIGHT:"))
	{
		if ($zcfgValue == 0)
		$UConfig->XMLHighlight = false;
		else
		$UConfig->XMLHighlight = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#LOGGING:"))
	{
		if ($zcfgValue == 0)
		$UConfig->Logging = false;
		else
		$UConfig->Logging = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#LOGGING_FILE:"))
	{
		$UConfig->LogFileName = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#TIMING:"))
	{
		if ($zcfgValue == 0)
		$UConfig->Timing = false;
		else
		$UConfig->Timing = true;
		continue;
	}


	if ($zcfgValue = GetConfigValue($zcfgLine, "#NOCHARSET:"))
	{
		if ($zcfgValue == 0)
		$UConfig->NoCharset = false;
		else
		$UConfig->NoCharset = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#ESCAPEURLSUTF8:"))
	{
		if ($zcfgValue == 0)
		$UConfig->EscapeURLsInUTF8 = false;
		else
		$UConfig->EscapeURLsInUTF8 = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#USEUTCTIME:"))
	{
		if ($zcfgValue == 0)
		$UConfig->UseUTCTime = false;
		else
		$UConfig->UseUTCTime = true;
		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#DEFAULT_TO_AND:"))
	{
		if ($zcfgValue == 0)
		$UConfig->DefaultToAnd = false;
		else
		$UConfig->DefaultToAnd = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#CONTEXTSIZE:"))
	{
		$UConfig->ContextSize = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#MAXRESULTSPERQUERY:"))
	{
		$UConfig->MaxResultsPerQuery = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#EXACTPHRASE:"))
	{
		if ($zcfgValue == 0)
		$UConfig->AllowExactPhrase = false;
		else
		{
			$UConfig->AllowExactPhrase = true;
			$UConfig->MaxContextSeeks = $zcfgValue;
		}
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#LINKTARGET:"))
	{
		$UConfig->LinkTarget = $zcfgValue;
		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#TRUNCATESHOWURL:"))
	{
		if ($zcfgValue == 0)
		{
			$UConfig->TruncateShowURL = false;
			$UConfig->ShowURLLength = URLLENGTH;
		}
		else
		{
			$UConfig->TruncateShowURL = true;
			$UConfig->ShowURLLength = $zcfgValue;
		}
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#SEARCHASSUBSTRING:"))
	{
		if ($zcfgValue == 0)
		$UConfig->SearchAsSubstring = false;
		else
		$UConfig->SearchAsSubstring = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#STRIPDIACRITICS:"))
	{
		if ($zcfgValue == 0)
		$UConfig->StripDiacritics = false;
		else
		$UConfig->StripDiacritics = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#NO_TOLOWER:"))
	{
		if ($zcfgValue == 0)
		$UConfig->DisableToLower = false;
		else
		$UConfig->DisableToLower = true;
		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#ZOOMINFO:"))
	{
		if ($zcfgValue == 0)
		$UConfig->ZoomInfo = false;
		else
		$UConfig->ZoomInfo = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#BINUSEDESC:"))
	{
		if ($zcfgValue == 0)
		$UConfig->BinaryUseDescFiles = false;
		else
		$UConfig->BinaryUseDescFiles = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#BINEXTRACTSTRINGS:"))
	{
		if ($zcfgValue == 0)
		$UConfig->BinaryExtractStrings = false;
		else
		$UConfig->BinaryExtractStrings = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#BINSTRINGSPUNCT:"))
	{
		if ($zcfgValue == 0)
		$UConfig->BinStringsAllowPunctuation = false;
		else
		$UConfig->BinStringsAllowPunctuation = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#BINSTRINGSNUMBERS:"))
	{
		if ($zcfgValue == 0)
		$UConfig->BinStringsAllowNumbers = false;
		else
		$UConfig->BinStringsAllowNumbers = true;
		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#BINSTRINGSMINLEN:"))
	{
		$UConfig->BinStringsMinStringLength = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#BINSTRINGSMAXLEN:"))
	{
		$UConfig->BinStringsMaxStringLength = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#BINSTRINGSREPEATCHAR:"))
	{
		$UConfig->BinStringsRepeatedCharLimit = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#BINSTRINGSCASECHANGE:"))
	{
		$UConfig->BinStringsCaseChangeLimit = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#USEDATETIME:"))
	{
		if ($zcfgValue == 0)
		$UConfig->UseDateTime = false;
		else
		$UConfig->UseDateTime = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#DATERANGESEARCH:"))
	{
		if ($zcfgValue == 0)
		$UConfig->DateRangeSearch = false;
		else
		$UConfig->DateRangeSearch = true;
		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#USEDOMAINDIVERSITY:"))
	{
		if ($zcfgValue == 0)
		$UConfig->UseDomainDiversity = false;
		else
		$UConfig->UseDomainDiversity = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#DEFAULTSORT:"))
	{
		$UConfig->DefaultSort = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#DATERANGEFORMAT:"))
	{
		$UConfig->DateRangeFormat = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#DOTNETUSEFORMTAGS:"))
	{
		if ($zcfgValue == 0)
		$UConfig->DotNetUseFormTags = false;
		else
		$UConfig->DotNetUseFormTags = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#DOTNETUSEPOSTBACKS:"))
	{
		if ($zcfgValue == 0)
		$UConfig->DotNetUsePostBacks = false;
		else
		$UConfig->DotNetUsePostBacks = true;
		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#WORDJOINCHARS:"))
	{
		if (strlen($zcfgValue) > 0)
		{
			$UConfig->WordJoinChars = $zcfgValue;
		}
		else
		$UConfig->WordJoinChars = "";
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#SPELLING:"))
	{
		if ($zcfgValue == 0)
		$UConfig->Spelling = false;
		else
		$UConfig->Spelling = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#SPELLINGWHENLESSTHAN:"))
	{
		if ($zcfgValue < 1)
		$UConfig->SpellingWhenLessThan = 5;	// defaults to 5.
		else
		$UConfig->SpellingWhenLessThan = $zcfgValue;	// 1 or greater
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#PLUGINOPENNEWWINDOW:"))
	{
		if ($zcfgValue == 0)
		$UConfig->PluginOpenNewWindow = false;
		else
		$UConfig->PluginOpenNewWindow = true;
		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#PLUGINUSETIMEOUT:"))
	{
		if ($zcfgValue == 0)
		$UConfig->PluginUseOverrideTimeout = false;
		else
		$UConfig->PluginUseOverrideTimeout = true;
		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#PLUGINTIMEOUTVALUE:"))
	{

		if ($zcfgValue > 0 && $zcfgValue < 100000000)
		{
			$UConfig->PluginOverrideTimeoutValue = $zcfgValue;
		}
		else
		{
			$UConfig->PluginUseOverrideTimeout = false;
			// set to something here in case manually made zcfg file has #PLUGINUSETIMEOUT set after this
			$UConfig->PluginOverrideTimeoutValue = 60000;
		}
		continue;
	}
	
	//Filetype/Plugin options
	if ($zcfgValue = GetConfigValue($zcfgLine, "#PLUGIN_DESCFILES:"))
	{
		//	#PLUGIN_DESCFILES:PDF|PPT|SWF|XLS|OFFICE|ZIP|
		
		$UConfig->PluginConfig->PdfUseDescFiles = false;
		$UConfig->PluginConfig->DocUseDescFiles = false;
		$UConfig->PluginConfig->XlsUseDescFiles = false;
		$UConfig->PluginConfig->PptUseDescFiles = false;
		$UConfig->PluginConfig->RtfUseDescFiles = false;
		$UConfig->PluginConfig->DjvuUseDescFiles = false;
		$UConfig->PluginConfig->SwfUseDescFiles = false;
		$UConfig->PluginConfig->WpdUseDescFiles = false;
		$UConfig->PluginConfig->ImgUseDescFiles = false;
		$UConfig->PluginConfig->Mp3UseDescFiles = false;		
		$UConfig->PluginConfig->DwfUseDescFiles = false;
		$UConfig->PluginConfig->OfficeXmlUseDescFiles = false;
		$UConfig->PluginConfig->TorrentUseDescFiles = false;
		$UConfig->PluginConfig->MhtUseDescFiles = false;
		$UConfig->PluginConfig->ZipUseDescFiles = false;
		
		if (strpos($zcfgValue, "PDF") !== false)
			$UConfig->PluginConfig->PdfUseDescFiles = true;
		if (strpos($zcfgValue,"DOC") !== false)
			$UConfig->PluginConfig->DocUseDescFiles = true;
		if (strpos($zcfgValue, "PPT")!== false)
			$UConfig->PluginConfig->PptUseDescFiles = true;
		if (strpos($zcfgValue, "RTF") !== false)
			$UConfig->PluginConfig->RtfUseDescFiles = true;
		if (strpos($zcfgValue, "SWF") !== false)
			$UConfig->PluginConfig->SwfUseDescFiles = true;
		if (strpos($zcfgValue, "WPD") !== false)
			$UConfig->PluginConfig->WpdUseDescFiles = true;
		if (strpos($zcfgValue, "XLS")!== false)
			$UConfig->PluginConfig->XlsUseDescFiles = true;
		if (strpos($zcfgValue, "DJVU") !== false)
			$UConfig->PluginConfig->DjvuUseDescFiles = true;
		if (strpos($zcfgValue, "MP3") !== false)
			$UConfig->PluginConfig->Mp3UseDescFiles = true;
		if (strpos($zcfgValue, "IMAGE")!== false)
			$UConfig->PluginConfig->ImgUseDescFiles = true;
		if (strpos($zcfgValue, "DWF") !== false)
			$UConfig->PluginConfig->DwfUseDescFiles = true;
		if (strpos($zcfgValue, "OFFICE") != NULL)
			$UConfig->PluginConfig->OfficeXmlUseDescFiles = true;
		if (strpos($zcfgValue, "BT") !== false)
			$UConfig->PluginConfig->TorrentUseDescFiles = true;
		if (strpos($zcfgValue, "MHT") !== false)
			$UConfig->PluginConfig->MhtUseDescFiles = true;
		if (strpos($zcfgValue, "ZIP") !== false)
			$UConfig->PluginConfig->ZipUseDescFiles = true;
		
		continue;
	}
	
	if ($zcfgValue = GetConfigValue($zcfgLine, "#PLUGIN_USEMETA:"))
	{
		//#PLUGIN_USEMETA:PDF|DOC|PPT|RTF|SWF|WPD|DJVU|IMAGE|MP3|DWF|OFFICE|
		
		$UConfig->PluginConfig->PdfUseMeta = false;
		$UConfig->PluginConfig->DocUseMeta = false;
		$UConfig->PluginConfig->XlsUseMeta = false;
		$UConfig->PluginConfig->PptUseMeta = false;
		$UConfig->PluginConfig->RtfUseMeta = false;
		$UConfig->PluginConfig->DjvuUseMeta = false;
		$UConfig->PluginConfig->SwfUseMeta = false;
		$UConfig->PluginConfig->WpdUseMeta = false;
		$UConfig->PluginConfig->ImgUseMeta = false;
		$UConfig->PluginConfig->Mp3UseMeta = false;
		$UConfig->PluginConfig->DwfUseMeta = false;
		$UConfig->PluginConfig->OfficeXmlUseMeta = false;	
			
		if (strpos($zcfgValue, "PDF")!== false)
			$UConfig->PluginConfig->PdfUseMeta = true;
		if (strpos($zcfgValue, "DOC")!== false)
			$UConfig->PluginConfig->DocUseMeta = true;
		if (strpos($zcfgValue, "PPT")!== false)
			$UConfig->PluginConfig->PptUseMeta = true;
		if (strpos($zcfgValue, "RTF")!== false)
			$UConfig->PluginConfig->RtfUseMeta = true;
		if (strpos($zcfgValue, "SWF")!== false)
			$UConfig->PluginConfig->SwfUseMeta = true;
		if (strpos($zcfgValue, "WPD")!== false)
			$UConfig->PluginConfig->WpdUseMeta = true;
		if (strpos($zcfgValue, "XLS")!== false)
			$UConfig->PluginConfig->XlsUseMeta = true;
		if (strpos($zcfgValue, "DJVU")!== false)
			$UConfig->PluginConfig->DjvuUseMeta = true;
		if (strpos($zcfgValue, "MP3")!== false)
			$UConfig->PluginConfig->Mp3UseMeta = true;
		if (strpos($zcfgValue, "IMAGE")!== false)
			$UConfig->PluginConfig->ImgUseMeta = true;
		if (strpos($zcfgValue, "DWF")!== false)
			$UConfig->PluginConfig->DwfUseMeta = true;
		if (strpos($zcfgValue, "OFFICE")!== false)
			$UConfig->PluginConfig->OfficeXmlUseMeta = true;
		
			continue;
	}
	
	if ($zcfgValue = GetConfigValue($zcfgLine, "#PLUGIN_USETECHNICAL:"))
	{
		//#PLUGIN_USETECHNICAL:MP3|IMAGE|DWF|
		
		$UConfig->PluginConfig->Mp3UseTechnical = false;
		$UConfig->PluginConfig->ImgUseTechnical = false;
		$UConfig->PluginConfig->DwfUseTechnical = false;
		
		if (strpos($zcfgValue, "MP3") !== false)
			$UConfig->PluginConfig->Mp3UseTechnical = true;
		if (strpos($zcfgValue, "IMAGE") !== false)
			$UConfig->PluginConfig->ImgUseTechnical = true;
		if (strpos($zcfgValue, "DWF") !== false)
			$UConfig->PluginConfig->DwfUseTechnical = true;
		
		continue;
	}
	
	if ($zcfgValue = GetConfigValue($zcfgLine, "#PLUGIN_TEXTONLY:"))
	{
		//#PLUGIN_TEXTONLY:OFFICE|
		
		$UConfig->PluginConfig->OfficeXmlTextOnly = false;
		if (strpos($zcfgValue, "OFFICE") !== false)
			$UConfig->PluginConfig->OfficeXmlTextOnly = true;
			
		continue;
	}
	
		if ($zcfgValue = GetConfigValue($zcfgLine, "#PLUGIN_PDF_USEPASSWORD:"))
	{
		//#PLUGIN_PDF_USEPASSWORD:1
		//????
		
		if($zcfgValue == 0)
			$UConfig->PluginConfig->PdfUsePassword = false;
		else
		{
			$UConfig->PluginConfig->PdfUsePassword = true;		
			$encPassword = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);

			if(strlen($encPassword) > 0)
			{
				$UConfig->PluginConfig->PdfPassword = "";

				// de-crypt by xor				
				$length = strlen($encPassword);
				for ($i = 0; $i < $length; $i++) 
				{
			    $tmp = $encPassword[$i];
			
			    for ($j = 0; $j < strlen($AUTHXORKEY); $j++) 
			    {
			        $tmp = chr(ord($tmp) ^ ord($AUTHXORKEY[$j]));
		    	}
		
		  		 $UConfig->PluginConfig->PdfPassword .= $tmp;
			 }
			}
		}
				
		continue;
	}
	
	if ($zcfgValue = GetConfigValue($zcfgLine, "#PLUGIN_PDF_METHOD:"))
	{
		//#PLUGIN_PDF_METHOD:0	
		if ($zcfgValue > 0)				
			$UConfig->PluginConfig->PdfToTextMethod = $zcfgValue;
		else
			$UConfig->PluginConfig->PdfToTextMethod = 0;
		
		continue;
	}
	
	if ($zcfgValue = GetConfigValue($zcfgLine, "#PLUGIN_PDF_HIGHLIGHT:"))
	{
		//#PLUGIN_PDF_HIGHLIGHT:1
		if ($zcfgValue == 1)				
			$UConfig->PluginConfig->PdfHighlight = true;
		else
			$UConfig->PluginConfig->PdfHighlight = false;
		
		continue;
	}	
		
	if ($zcfgValue = GetConfigValue($zcfgLine, "#PLUGIN_IMG_MINFILESIZE:"))
	{
		//PLUGIN_IMG_MINFILESIZE:5
		if ($zcfgValue > 0)				
			$UConfig->PluginConfig->ImgMinFilesize = $zcfgValue;
		else
			$UConfig->PluginConfig->ImgMinFilesize = 0;
		continue;
	}	
	
	if ($zcfgValue = GetConfigValue($zcfgLine, "#PLUGIN_ZIP_EXTRACT:"))
	{
		#PLUGIN_ZIP_EXTRACT:1
		if ($zcfgValue > 0)				
			$UConfig->PluginConfig->ZipExtractFiles = $zcfgValue;
		else
			$UConfig->PluginConfig->ZipExtractFiles = 0;
		continue;
	}
	

	
	
	if ($zcfgValue = GetConfigValue($zcfgLine, "#ZOOMIMAGE:"))
	{
		if ($zcfgValue == 0)
		$UConfig->UseZoomImage = false;
		else
		$UConfig->UseZoomImage = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#LINKBACKURL:"))
	{
		if (strlen($zcfgValue) > 0)
		{
			$UConfig->LinkBackURL = $zcfgValue;
		}
		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#WIZARD_UPLOADREQD:"))
	{
		if ($zcfgValue == 0)
		$UConfig->WizardUploadReqd = false;
		else
		$UConfig->WizardUploadReqd = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#REPORTLOGFILE:"))
	{
		if (strlen($zcfgValue) > 0)
		{
			$UConfig->ReportLogfile = $zcfgValuel;
		}
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#REPORTOUTDIR:"))
	{
		if (strlen($zcfgValue) > 0)
		{
			$UConfig->ReportOutputDir = $zcfgValue;
		}
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#REPORTUSEDATES:"))
	{
		if ($zcfgValue == 0)
		$UConfig->ReportAppendDatetime = false;
		else
		$UConfig->ReportAppendDatetime = true;
		continue;
	}


	if ($zcfgValue = GetConfigValue($zcfgLine, "#REPORT_TOP10:"))
	{
		$UConfig->StatsTop10Check = true;
		$UConfig->StatsTop10List = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#REPORT_TOPNR:"))
	{
		$UConfig->StatsTopNRCheck = true;
		$UConfig->StatsTopNRList = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#REPORT_DAY:"))
	{
		$UConfig->StatsDayCheck = true;
		$UConfig->StatsDayList = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#REPORT_DAY_TYPE:"))
	{
		$UConfig->StatsDayGraphType = $zcfgValue;
		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#REPORT_WEEK:"))
	{
		$UConfig->StatsWeekCheck = true;
		$UConfig->StatsWeekList = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#REPORT_WEEK_TYPE:"))
	{
		$UConfig->StatsWeekGraphType = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#REPORT_MONTH:"))
	{
		$UConfig->StatsMonthCheck = true;
		$UConfig->StatsMonthList = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#REPORT_MONTH_TYPE:"))
	{
		$UConfig->StatsMonthGraphType =$zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#REPORT_LISTALL:"))
	{
		$UConfig->StatsListAll = true;

		if ($zcfgValue > 0 && $zcfgValue < 50000)
		$UConfig->StatsListAllUpto = $zcfgValue;
		else
		$UConfig->StatsListAllUpto = 100;//default is 100
		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#WORDWEIGHT_TITLE:"))
	{
		$UConfig->WeightTitle = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#WORDWEIGHT_DESC:"))
	{
		$UConfig->WeightDesc = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#WORDWEIGHT_KEYWORDS:"))
	{
		$UConfig->WeightKeywords = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#WORDWEIGHT_FILENAME:"))
	{
		$UConfig->WeightFilename = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#WORDWEIGHT_HEADINGS:"))
	{
		$UConfig->WeightHeadings = $zcfgValue;
		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#WORDWEIGHT_LINKTEXT:"))
	{
		$UConfig->WeightLinktext = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#WORDWEIGHT_CONTENT:"))
	{
		$UConfig->WeightContent = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#WORDWEIGHT_DENSITY:"))
	{
		$UConfig->WeightDensity = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#WORDWEIGHT_SHORTURLS:"))
	{
		$UConfig->WeightShortURLs = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#WORDWEIGHT_PROXIMITY:"))
	{
		$UConfig->WeightProximity = $zcfgValue;
		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#USESRCPATH_SCRIPT:"))
	{
		if (strlen($zcfgValue) > 1)
		{
			$UConfig->UseSrcPaths = true;
			$UConfig->SourceScriptPath = $zcfgValue;
		}
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#USESRCPATH_TEMPLATE:"))
	{
		if (strlen($zcfgValue) > 1)
		{
			$UConfig->UseSrcPaths = true;
			$UConfig->SourceTemplatePath = $zcfgValue;
		}
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#USE-AUTH:"))
	{
		if ($zcfgValue == 0)
		$UConfig->UseAuth = false;
		else
		$UConfig->UseAuth = true;
		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#USE-COOKIES:"))
	{
		if ($zcfgValue == 0)
		$UConfig->UseCookies = false;
		else
		$UConfig->UseCookies = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#AUTHINFO:"))
	{
		if (strlen($zcfgValue) > 1)
		{
			$UConfig->Login = $zcfgValue;

			$encPassword = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);

			if(strlen($encPassword) > 0)
			{
				$UConfig->Password = "";

				// de-crypt by xor				
				$length = strlen($encPassword);
				for ($i = 0; $i < $length; $i++) 
				{
			    $tmp = $encPassword[$i];
			
			    for ($j = 0; $j < strlen($AUTHXORKEY); $j++) 
			    {
			        $tmp = chr(ord($tmp) ^ ord($AUTHXORKEY[$j]));
		    	}
		
		  		 $UConfig->Password .= $tmp;
			 }
				
				
			}
		}
		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#USE-COOKIELOGIN:"))
	{
		if ($zcfgValue == 0)
		$UConfig->UseCookieLogin = false;
		else
		$UConfig->UseCookieLogin = true;
		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#COOKIELOGINURL:"))
	{
		$UConfig->CookieLoginURL = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#COOKIELOGINNAME:"))
	{
		$UConfig->CookieLoginName = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#COOKIELOGINVALUE:"))
	{
		$UConfig->CookieLoginValue = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#COOKIEPARAMS:"))
	{
		$UConfig->CookieParams = $zcfgValue;

		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#COOKIEPASSNAME:"))
	{
		if (strlen($zcfgValue) > 1)
		{
			$UConfig->CookiePasswordName = $zcfgValue;

			// Get encrypted password from following line.
			$encPassword = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);
			if ($encPassword  != false && strlen($encPassword ) > 0)
			{
				$length = strlen($encPassword);
				for ($i = 0; $i < $length; $i++) 
				{
			    $tmp = $encPassword[$i];
			
			    for ($j = 0; $j < strlen($AUTHXORKEY); $j++) 
			    {
			        $tmp = chr(ord($tmp) ^ ord($AUTHXORKEY[$j]));
		    	}
		
		  		 $UConfig->CookiePasswordValue .= $tmp;
				 }
			}
		}
		continue;
	}

	if (strncmp($zcfgLine, "#FTPINFO_START", strlen("#FTPINFO_START")) == 0)
	{

		$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);
		if ($nextLine != false)
		$UConfig->FTPServer = trim($nextLine);
		else
		continue;

		$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);
		if ($nextLine == false)
		continue;

		if ($nextLine > 0)
		$UConfig->FTPPort = trim($nextLine);
		else
		$UConfig->FTPPort = 21;

		$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);
		if ($nextLine != false)
		$UConfig->FTPLogin = trim($nextLine);
		else
		continue;

		$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);
		if ($nextLine != false)
		{
			$encPassword = $nextLine;
			// de-crypt by xor
			//Currently not working (need to support widechar/UTF8)
			/*
			$length = strlen($encPassword);
			for($i = 0; $i < $length; $i++)
			$UConfig->FTPPassword[$i]  = $encPassword[$i] ^ $AUTHXORKEY;
			*/

		}
		else
		continue;

		$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);
		if ($nextLine != false)
			$UConfig->FTPUploadPath = trim($nextLine);


		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#FTPAUTO:"))
	{
		if ($zcfgValue == 0)
		$UConfig->FTPAuto = false;
		else
		$UConfig->FTPAuto = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#FTPNOTEMPLATE:"))
	{
		if ($zcfgValue == 0)
		$UConfig->FTPDontUploadTemplate = false;
		else
		$UConfig->FTPDontUploadTemplate = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#FTPRENAMETMP:"))
	{
		if ($zcfgValue == 0)
		$UConfig->FTPRenameTmp = false;
		else
		$UConfig->FTPRenameTmp = true;
		continue;
	}


	if ($zcfgValue = GetConfigValue($zcfgLine, "#FTPSETPERMISSIONS:"))
	{
		if ($zcfgValue == 0)
		$UConfig->FTPSetFilePermissions = false;
		else
		$UConfig->FTPSetFilePermissions = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#FTPUSEPASV:"))
	{
		if ($zcfgValue == 0)
		$UConfig->FTPUsePASV = false;
		else
		$UConfig->FTPUsePASV = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#OPTIMIZE_SETTING:"))
	{
		if ($zcfgValue >= 0)
		$UConfig->OptimizeSetting = $zcfgValue;
		continue;
	}

	if (strncmp($zcfgLine, "#ADDSTARTURLS_START", strlen("#ADDSTARTURLS_START")) == 0)
	{

		// read in Additional Start points
		$i = 0;
		while(!feof($fp_zcfg) )
		{
			$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);

			if ($nextLine == false || strlen($nextLine) < 1)
			break;

			// quit if start of new section
			if(strncmp($nextLine, "#ADDSTARTURLS_END", strlen("#ADDSTARTURLS_END")) == 0)
			{
				break;
			}


			// create a temporary URL item with an empty base URL buffer so we can fill it in as we parse things in.
			$start_elem_item = new URL_ELEM();

			$start_elem_item->url = trim($nextLine);

			$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);
			if ($nextLine == false || strlen($zcfgLine) < 1)
			break; //"Invalid additional start URL section in config");

			// default options for this start point:
			$start_elem_item->urltype = $URLTYPE_INDEX_AND_FOLLOW;	// default url type
			$start_elem_item->uselimit = false;
			$start_elem_item->limit = 0;
			$start_elem_item->boost = 0;

			// now we look for pipe seperated values for these options
			// note that V4.x did not have extra values on this line after the urltype
			$tok = strtok($nextLine, "|\n\r");
			if(strlen($tok) > 0)
			{
				$start_elem_item->urltype = $tok;
				$tok = strtok("|\n\r");

				if(strlen($tok) > 0)
				{
					if($tok == 1)
					$start_elem_item->uselimit = true;

					$tok = strtok("|\n\r");

					if(strlen($tok) > 0)
					{
						$start_elem_item->limit = $tok;

						$tok = strtok("|\n\r");

						if(strlen($tok) > 0)
							$start_elem_item->boost = $tok;
					}
				}
			}

			//if ($start_elem_item->urltype >= URLTYPE_MAXCOUNT)
			//throw("Invalid additional start URL section in config (Invalid URL type specified)");

			$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);
			if ($nextLine == false || strlen($nextLine) < 1)
			break; //"Invalid additional start URL section in config");

			$start_elem_item->baseURL = trim($nextLine);

			GetBaseURL($start_elem_item->url, $start_elem_item->baseURL, false);

			$UConfig->starturl_list[$i] = $start_elem_item;
			$i++;

		}

		continue;
	}


	if (strncmp($zcfgLine, "#ADDSTARTDIRS_START", strlen("#ADDSTARTDIRS_START")) == 0)
	{
		$tmpurl = "";
		$tmpbaseURL = "";

		// read in Additional Start points
		$i = 0;
		while(!feof($fp_zcfg))
		{
			$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);
			if ($nextLine != false)
			{
				if(strncmp($nextLine, "#ADDSTARTDIRS_END", strlen("#ADDSTARTDIRS_END")) == 0)
					break;

				$startdir_elem_item = new URL_ELEM();
				$startdir_elem_item->url = trim($nextLine);

				$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);
				if ($nextLine == false || strlen($nextLine) < 1)
				break;	//Invalid additional start folders section in config"))

				$startdir_elem_item->baseURL = trim($nextLine);
				$UConfig->startdir_list[$i] = $startdir_elem_item;
				$i++;
			}
			else
			break; //Invalid additional start folders section in config file");

		}
		continue;
	}


	if (strncmp($zcfgLine, "#SKIPPAGES_START", strlen("#SKIPPAGES_START")) == 0)
	{
		$i = 0;
		while(!feof($fp_zcfg) && $i < $MAXSKIPPAGES)
		{
			$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);

			if ($nextLine != false)
			{
				if(strncmp($nextLine, "#SKIPPAGES_END", strlen("#SKIPPAGES_END")) == 0)
				{
					break;
				}
				$UConfig->SkipPages[$i] = trim($nextLine);
				$i++;
			}

		}
		continue;
	}

	if (strncmp($zcfgLine, "#SKIPWORDS_START", strlen("#SKIPWORDS_START")) == 0)
	{
		$i = 0;
		while(!feof($fp_zcfg) && $i < $MAXSKIPWORDS)
		{
			$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);

			if ($nextLine != false)
			{
				if(strncmp($nextLine, "#SKIPWORDS_END", strlen("#SKIPWORDS_END")) == 0)
				{
					break;
				}

				$UConfig->SkipWords[$i] = trim($nextLine);
				$i++;
			}

		}
		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#USECATS:"))
	{
		if ($zcfgValue == 0)
			$UConfig->UseCats = false;
		else
			$UConfig->UseCats = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#USEDEFCATNAME:"))
	{
		if ($zcfgValue == 0)
			$UConfig->UseDefCatName = false;
		else
			$UConfig->UseDefCatName = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#SEARCHMULTICATS:"))
	{
		if ($zcfgValue == 0)
		$UConfig->SearchMultiCats = false;
		else
		$UConfig->SearchMultiCats = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#DISPLAYCATSUMMARY:"))
	{
		if ($zcfgValue == 0)
		$UConfig->DisplayCatSummary = false;
		else
		$UConfig->DisplayCatSummary = true;
		continue;
	}

	if (strncmp($zcfgLine, "#CATEGORIES_START", strlen("#CATEGORIES_START")) == 0)
	{

		PrintDebug("CATEGORIES_START loop");
		// Read first line as default cat name
		$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);
		
		if($nextLine == false && strlen($nextLine) < 1)
			break; //("Invalid categories syntax in config file");

		$UConfig->DefCatName = trim($nextLine);

		// Now read in the actual categories
		$i = 0;
		while(!feof($fp_zcfg) )
		{
			$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);
			
			PrintDebug("CATEGORIES_START loop: ". $nextLine);
			
			if ($nextLine == false || strlen($nextLine) < 1)
				break;

			// quit if start of new section
			if(strncmp($nextLine, "#CATEGORIES_END", strlen("#CATEGORIES_END")) == 0)
			{
				PrintDebug("CATEGORIES_START broke on CATEGORIES_END");
				break;
			}

			$cat_elem_item = new CAT_ELEM();

			// Get Cat name
			$cat_elem_item->name = trim($nextLine);

			// Get Pattern
			$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);
			if ($nextLine == false || strlen($nextLine) < 1)
				break; //"Incorrect categories syntax (no category pattern specified)");

			$cat_elem_item->pattern = trim($nextLine);

			// Get description, exclusive options, etc. (on a pipe separated line)
			$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);
			if ($nextLine == false || strlen($nextLine) < 1)
				break; //("Incorrect categories syntax (missing category options line)");

			if ($nextLine[0] == '|')
			{
				// no description for category
				$cat_elem_item->description = "";
				$tok = strtok($nextLine,  "|\n\r");
			}
			else
			{
				// now we look for pipe seperated values for these options
				// note that V4.x did not have extra values on this line after the urltype
				$tok = strtok($nextLine,  "|\n\r");
				if (strlen($tok) > 0)
					$cat_elem_item->description = $tok;

				$tok = strtok("|\n\r");
			}

			//This may not be working in PHP, i believe calling strtok on a string like "|1" will return a 0 length string first call
			if ($tok != false)
				$cat_elem_item->IsExclusive = $tok;

			$UConfig->cats_list[$i] = $cat_elem_item;
			$i++;

		}

		continue;
	}


	// Pro only configs:
	if($zcfgValue = GetConfigValue($zcfgLine, "#MAXPAGES_LIMIT:"))
	{
		//Need to check again Pro edition settings?
		//(edition == PRO_EDITION || edition == ENTERPRISE_EDITION) &&

		if ($zcfgValue > 0)
		{
			$UConfig->MAXPAGES = $zcfgValue;

			/*
			if (edition == PRO_EDITION && $UConfig->MAXPAGES > 200000)
			{
			$UConfig->MAXPAGES = 200000;
			ZoomMessageBox(main_hwnd, "This ZCFG configuration file contains a maximum pages limit\n")\
			"which exceeds 200,000 pages.\n\n")\
			"You can not index more than 200,000 pages with the\n")\
			"Professional Edition of Zoom.\n\n")\
			"If you wish to index a larger site, please visit our website\n")\
			"for details on upgrading to the Enterprise Edition."),
			"Max pages limit for Pro Edition exceeded"),
			MB_ICONEXCLAMATION|MB_OK);
			}
			*/
		}
		continue;
	}


	//((edition == PRO_EDITION || edition == ENTERPRISE_EDITION) &&
	if  ($zcfgValue = GetConfigValue($zcfgLine, "#MAXWORDS_LIMIT:"))
	{
		PrintDebug("MAXWORDS_LIMIT: " . $zcfgValue);
		if ($zcfgValue > 0)
		{
			$UConfig->MAXWORDS = $zcfgValue;
			if ($UConfig->MAXWORDS > 500000) //edition == PRO_EDITION &&
			{
				$UConfig->MAXWORDS = 500000;
			}
		}
		continue;
	}

	//(edition == PRO_EDITION || edition == ENTERPRISE_EDITION) &&
	if ($zcfgValue = GetConfigValue($zcfgLine, "#MAXFILESIZE_LIMIT:"))
	{
		if ($zcfgValue > 0)
		$UConfig->MAX_FILE_SIZE = $zcfgValue;

		continue;
	}

	//(edition == PRO_EDITION || edition == ENTERPRISE_EDITION) &&
	if ($zcfgValue = GetConfigValue($zcfgLine, "#USE_MAXWORDS_LIMIT:"))
	{
		if ($zcfgValue > 0)
		$UConfig->LimitMaxWords = true;
		else
		$UConfig->LimitMaxWords = false;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#MAXWORDLENGTH_LIMIT:"))
	{
		$UConfig->MAXWORDLENGTH = intval($zcfgValue);
	}

	//((edition == PRO_EDITION || edition == ENTERPRISE_EDITION) &&
	if ($zcfgValue = GetConfigValue($zcfgLine, "#DESCLENGTH_LIMIT:"))
	{
		if ($zcfgValue >= 10 && $zcfgValue <= $MAXDESCLEN)
		$UConfig->DESCLENGTH = $zcfgValue;

		continue;
	}

	//((edition == PRO_EDITION || edition == ENTERPRISE_EDITION)
	if ($zcfgValue = GetConfigValue($zcfgLine, "#MAXPAGES_PER_STARTPT_LIMIT:"))
	{
		if ($zcfgValue > 0)
		{
			$UConfig->LimitPerStartPt = true;
			$UConfig->MAXPAGES_PER_STARTPT = $zcfgValue;
		}

		continue;
	}

	//((edition == PRO_EDITION || edition == ENTERPRISE_EDITION) &&
	if ($zcfgValue = GetConfigValue($zcfgLine, "#MAXWORDS_PER_PAGE_LIMIT:"))
	{
		if ($zcfgValue > 0)
		{
			$UConfig->LimitWordsPerPage = true;
			$UConfig->MAXWORDS_PER_PAGE = $zcfgValue;
		}

		continue;
	}
	
	if ($zcfgValue = GetConfigValue($zcfgLine, "#MAXURLVISITS_PER_STARTPT_LIMIT:"))
	{
		if ($zcfgValue > 0)
		{
			$UConfig->LimitURLsPerStartPt = true;
			$UConfig->MAXURLVISITS_PER_STARTPT = $zcfgValue;
		}

		continue;
	}


	//((edition == PRO_EDITION || edition == ENTERPRISE_EDITION) &&
	if ($zcfgValue = GetConfigValue($zcfgLine, "#TRUNCATE_TITLELEN:"))
	{
		if ($zcfgValue  > 0 && $zcfgValue  < $TITLELEN)
		{
			$UConfig->TruncateTitleLen = true;
			$UConfig->MAXTITLELENGTH = $zcfgValue;
		}
		continue;
	}

	//edition == ENTERPRISE_EDITION &&
	if($zcfgValue = GetConfigValue($zcfgLine, "#USERAGENTSTR:"))
	{
		if (strlen($zcfgValue) > 0)
		{
			$UConfig->UserAgentStr = $zcfgValue;
		}
		continue;
	}

	if (strncmp($zcfgLine, "#SYNONYMS_START", strlen("#SYNONYMS_START")) == 0)
	{
		// read in the synonyms
		$i = 0;
		while(!feof($fp_zcfg) )
		{
			$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);

			PrintDebug("Synonyms loop: ". $nextLine);


			if (strlen($nextLine) < 1)
			break;

			// quit if start of new section
			if(strncmp($nextLine, "#SYNONYMS_END", strlen("#SYNONYMS_END")) == 0)
				break;

			$nextSyn = explode (",", $nextLine, 2);
						
			//Strip newline
			$nextSyn[1] = trim($nextSyn[1]);
			
			if (strlen($nextSyn[0]) < 1 )
				continue;
					
			// ignore synonyms with space characters
			if (strchr($nextSyn[0], " ") != NULL)
				continue;

			$sys_elem_item  = new SYN_ELEM();
			
			// Get word
			$sys_elem_item->word = $nextSyn[0];
			
			if ( strlen($nextSyn[1]) < 1)
			{
				//SafeDelete(item);
				//throw("Incorrect synonyms syntax (no synonyms for word specified)");
				continue;
			}

			// ignore synonyms with space characters
			if (strchr($nextSyn[1], " ") != NULL)
			{
				continue;
			}
			else
			{
				$sys_elem_item->synonyms = $nextSyn[1];
				$UConfig->syn_list[$i] = $sys_elem_item;

				$i++;
			}			
		}
		
		PrintDebug("Synonyms loop done (". $i .")");
		continue;
	}

	if (strncmp($zcfgLine, "#RECOMMENDED_START", strlen("#RECOMMENDED_START")) == 0)
	{
		// read in the recommended links
		$i = 0;
		PrintDebug("RECOMMENDED_START loop");
		while(!feof($fp_zcfg) )
		{
			
			$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);

			if (strlen($nextLine) < 1)
				break;

			// quit if start of new section
			if(strncmp($nextLine, "#RECOMMENDED_END", strlen("#RECOMMENDED_END")) == 0)
				break;

			$rec_elem_item = new REC_ELEM();

			// Get word
			$rec_elem_item->word = trim($nextLine);

			// Get URL
			$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);
			if (strlen($nextLine) < 1)
			break; //("Incorrect recommended links syntax (no URL specified)");
			$rec_elem_item->URL =  trim($nextLine);

			// get title
			$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);
			if (strlen($nextLine) < 1)
			break; //"Incorrect recommended links syntax (no title specified)");
			$rec_elem_item->title =  trim($nextLine);

			// get description
			$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);
			if (strlen($nextLine) < 1)
			break; //"Incorrect recommended links syntax (no description specified)");
			$rec_elem_item->desc =  trim($nextLine);

			if ($ZCFGVersionNumber > 5)
			{
				// get image url
				$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);
				if (strlen($nextLine) < 1)
				break; //"Incorrect recommended links syntax (no image url specified)");

				$rec_elem_item->imgURL =  trim($nextLine);
			}

			$UConfig->RecommendedList[$i] = $rec_elem_item;
			$i++;

		}
		PrintDebug("RECOMMENDED_START loop finished (".$i.")");
		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#RECOMMENDED_MAX:"))
	{
		if ($zcfgValue > 0)
		$UConfig->RecommendedMax = $zcfgValue;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#METAFIELD_MONEY_CURRENCY:"))
	{

		if ($zcfgValue >= 0 && $zcfgValue < $METAFIELD_MONEY_COUNT)
		$UConfig->MetaMoneyCurrency = $zcfgValue;
		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#METAFIELD_MONEY_SHOWDEC:"))
	{
		if ($zcfgValue == 0)
		$UConfig->MetaMoneyShowDecimals = false;
		else
		$UConfig->MetaMoneyShowDecimals = true;
		continue;
	}

	if (strncmp($zcfgLine, "#METAFIELD_START", strlen("#METAFIELD_START")) == 0)
	{

		// read in the metafields settings
		$i = 0;
		while(!feof($fp_zcfg) )
		{
			$tmpMetaElem = new METAFIELD_ELEM();

			$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);

			PrintDebug("meta field loop: " . $nextLine);
			
			if (strlen($zcfgLine) < 1)
			break;

			if ($nextLine != false)
			{
				// quit if start of new section
				if(strncmp($nextLine, "#METAFIELD_END", strlen("#METAFIELD_END")) == 0)
					break;

				$UConfig->metafield_list;

				// Get name
				$tmpMetaElem->name = trim($nextLine);
				
				PrintDebug("name: " . $tmpMetaElem->name);

				// Get type
				$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);
				if ($nextLine == false)
				continue; //Incorrect metafields syntax (no type specified)

				if ($nextLine < $METAFIELD_TYPE_COUNT)
				$tmpMetaElem->type =  trim($nextLine); //strip newline
				else
				$tmpMetaElem->type = 0;

				// get show as
				$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);

				if ($nextLine == false)
				continue; //Incorrect metafields syntax (no show name specifiedd

				$tmpMetaElem->showname = trim($nextLine);


				$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);

				if ($nextLine == false)
					continue; //"Incorrect metafields syntax (no form name specified)");

				$tmpMetaElem->formname = trim($nextLine);

				// Get method
				$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);

				if ($nextLine == false)
					continue; //""Incorrect metafields syntax (no method specified)");

				$tmpMetaElem->method = trim($nextLine);

				// get dropdown values ONLY if this is dropdown type
				if ($tmpMetaElem->type == $METAFIELD_TYPE_DROPDOWN || $tmpMetaElem->type == $METAFIELD_TYPE_MULTI)
				{

					$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);
					if ($nextLine != false)
					{
						if (strncmp($nextLine, "#DROPDOWN_START", strlen("#DROPDOWN_START")) == 0)
						{
							PrintDebug("Meta loop got DROPDOWN_START ");
							
							// read in the dropdowns
							$dropdownCount = 0;
							while (!feof($fp_zcfg) && $dropdownCount < $METAFIELD_DROPDOWN_MAX)
							{

								$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);
									if (strlen($nextLine) < 1)
										break;

									// quit if start of new section
									if(strncmp($nextLine, "#DROPDOWN_END", strlen("#DROPDOWN_END")) == 0)
									{
										PrintDebug("Meta loop got DROPDOWN_END ");
										break;
									}

									// Get dropdown value
									$tmpMetaElem->DropdownValues[$dropdownCount] =  trim($nextLine);
									$dropdownCount++;
									
									PrintDebug("Meta drop down loop got " .  $nextLine);
								}
						}
					}
				}

				//Add $tmpMetaElem to array in config
				$UConfig->metafield_list[$i] = $tmpMetaElem;
				$i++;
			}
		}
		PrintDebug("Meta loop finished ");
		continue;
	}

	PrintDebug($zcfgLine);
	if ($zcfgValue = GetConfigValue($zcfgLine, "#USEFILTER:"))
	{
		PrintDebug("USEFILTER: " . $zcfgValue);
		if ($zcfgValue == 0)
		$UConfig->UseContentFilter = false;
		else
		$UConfig->UseContentFilter = true;
		continue;
	}

	if (strncmp($zcfgLine, "#FILTER_START", strlen("#FILTER_START")) == 0)
	{
		PrintDebug("FILTER_START");
		$i = 0;
		while(!feof($fp_zcfg) && $i < $MAXFILTERRULES)
		{
			PrintDebug("Filter Loop: " . $i);

			$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);
			if ($nextLine != false)
			{
				if(strncmp($nextLine, "#FILTER_END", strlen("#FILTER_END")) == 0)
					break;

				PrintDebug("Read Filter rule: " . $nextLine);

				$UConfig->ContentFilterRules[$i] = trim($nextLine);
				if(strlen($UConfig->ContentFilterRules[$i]) > 0)
				{
					// flag that we have positives in the list (if an entry is not negative signed)
					if (isset($UConfig->ContentFilterRules[$i]))
						if($UConfig->ContentFilterRules[$i][0] != '-')
							$UConfig->UsePositiveFilter = true;
					$i++;
				}

		
			}
		}
		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#USEAUTOCOMPLETE:"))
	{
		if ($zcfgValue == 0)
		$UConfig->UseAutoComplete = false;
		else
		$UConfig->UseAutoComplete = true;
		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#USEAUTOCOMPLETE_IMPORT:"))
	{
		if ($zcfgValue == 0)
		$UConfig->UseAutoCompleteInclude = false;
		else
		$UConfig->UseAutoCompleteInclude = true;
		continue;
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#AUTOCOMPLETE_IMPORTNUM:"))
	{
		$UConfig->AutoCompleteIncludeTopNum = intval($zcfgValue);
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#AUTOCOMPLETE_IMPORTURL:"))
	{
		if (strlen($zcfgValue) > 0)
		{
			$UConfig->AutoCompleteIncludeURL = $zcfgValue;
		}
		else
			$UConfig->AutoCompleteIncludeURL  = "";
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#AUTOCOMPLETE_USEPAGETITLE:"))
	{
		if ($zcfgValue == 0)
			$UConfig->AutoCompleteUsePageTitle = false;
		else
			$UConfig->AutoCompleteUsePageTitle  = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#AUTOCOMPLETE_USEMETADESC:"))
	{
		if ($zcfgValue == 0)
			$UConfig->AutoCompleteUseMetaDesc = false;
		else
			$UConfig->AutoCompleteUseMetaDesc  = true;
		continue;
	}		
	if (strncmp($zcfgLine, "#AUTOCOMPLETE_START", strlen("#AUTOCOMPLETE_START")) == 0)
	{
		$i = 0;
		while(!feof($fp_zcfg) && $i < $MAXAUTOCOMPLETE)
		{
			$nextLine = fgets($fp_zcfg, $MAX_CONFIG_LINELEN);
			if ($nextLine != false)
			{
				if(strncmp($nextLine, "#AUTOCOMPLETE_END", strlen("#AUTOCOMPLETE_END")) == 0)
					break;

				$UConfig->AutoCompleteRules[$i] = trim($nextLine);
				$i++;
			}
		}
	}

	if ($zcfgValue = GetConfigValue($zcfgLine, "#SITEMAP_TXT:"))
	{
		if ($zcfgValue == 0)
		$UConfig->SitemapTXT = false;
		else
		$UConfig->SitemapTXT = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#SITEMAP_XML:"))
	{
		if ($zcfgValue == 0)
		$UConfig->SitemapXML = false;
		else
		$UConfig->SitemapXML = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#SITEMAP_UPLOAD:"))
	{
		if ($zcfgValue == 0)
		$UConfig->SitemapUpload = false;
		else
		$UConfig->SitemapUpload = true;
		continue;
	}


	if ($zcfgValue = GetConfigValue($zcfgLine, "#SITEMAP_UPLOADPATH:"))
	{
		if (strlen($zcfgValue) > 0)
		{
			$UConfig->SitemapUploadPath = $zcfgValue;
		}
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#SITEMAP_USEPAGEBOOST:"))
	{
		if ($zcfgValue == 0)
		$UConfig->SitemapUsePageBoost = false;
		else
		$UConfig->SitemapUsePageBoost = true;
		continue;
	}
	if ($zcfgValue = GetConfigValue($zcfgLine, "#SITEMAP_USEBASEURL:"))
	{
		if ($zcfgValue == 0)
			$UConfig->SitemapUseBaseURL = false;
		else
			$UConfig->SitemapUseBaseURL = true;
		continue;
	}


	if ($zcfgValue = GetConfigValue($zcfgLine, "#SITEMAP_BASEURL:"))
	{
		if (strlen($zcfgValue) > 0)
		$UConfig->SitemapBaseURL = $zcfgValue;
		continue;
	}
	
		if ($zcfgValue = GetConfigValue($zcfgLine, "#USEPROXYSERVER:"))
	{
		if ($zcfgValue == 0)
			$UConfig->UseProxyServer = false;
		else
			$UConfig->UseProxyServer = true;
		continue;
	}
	
	if ($zcfgValue = GetConfigValue($zcfgLine, "#PROXYSERVER:"))
	{
		if (strlen($zcfgValue) > 0)
			$UConfig->ProxyServer = $zcfgValue;
		continue;
	}
	

	//if (edition != FREE_EDITION)
	//	OnLoadPluginConfig(fp, $zcfgLine);


}
	
	//Sanity check some values (eg so MaxResultsPerQuery is not 0)
	if($UConfig->MAXWORDS < 1)
		$UConfig->MAXWORDS = 30000;	
	if($UConfig->MAXPAGES < 1)
		$UConfig->MAXPAGES = 100;
	if($UConfig->MAX_FILE_SIZE < 1)
		$UConfig->MAX_FILE_SIZE = 1048576;	
	if($UConfig->DESCLENGTH < 1)
		$UConfig->DESCLENGTH = 150;
	if($UConfig->MaxResultsPerQuery < 1)
		$UConfig->MaxResultsPerQuery= 1000;
	if($UConfig->MaxContextSeeks < 1)
		$UConfig->MaxContextSeeks = 500;
	
	PrintDebug("LoadZCFGFile end"); //temp debug
	
	return true;
}



?>