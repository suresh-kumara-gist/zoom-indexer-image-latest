<?php
header('Content-type: text/html; charset=UTF-8');

include("./zoom_defines.php");
include("./config_file.php");
include("./codepages.php");

$CurrentConfigPath = "";

global $UConfig;

//Copy values from submitted form into the global config var
//Check maximum length won't be exceeded 
function UpdateConfig()
{	
	Global $UConfig;
	Global $MAX_CONFIG_LINELEN;
	Global $MAXEXTENSIONS;
	Global $MAX_FNAME;
	Global $URLLENGTH;
	Global $MAX_EXT;
	Global $MAX_PATH;
	Global $MAX_DOWNLOAD_THREADS;
	Global $WORDWEIGHT_NORMAL;
	Global $MAXFILTERRULES;
	Global $CAT_NAME_LEN;
	Global $TITLELEN;
	Global $MAXDESCLEN;
	Global $FILETYPE_STRING;
	Global $METAFIELD_STRINGS;
	Global $METAFIELD_SEARCH_STRINGS;
	Global $PLUGIN_PASSWORD_LEN;
	Global $MAXSKIPPAGELEN;
	Global $MAXSKIPPAGES;
	Global $MAXSKIPWORDS;
	Global $WORDLENGTH;
	Global $HTTP_MAX_USERAGENT_LEN;
	Global $MODE_OFFLINE;
	Global $OUTPUT_ASPNET;
  Global $MAXAUTOCOMPLETE;
  Global $AUTOCOMPLETE_LEN;

	//Start options

	if(isset($_POST["config_mode"]))
		$UConfig->currentMode = intval($_POST["config_mode"]);
		
	if($UConfig->currentMode == $MODE_OFFLINE)
	{
		if(isset($_POST["config_starturl"]))
			$UConfig->startdir = substr($_POST["config_starturl"], 0, $MAX_PATH);
	}
	else 
	{
			if(isset($_POST["config_starturl"]))
				$UConfig->spiderURL = substr($_POST["config_starturl"], 0, $URLLENGTH);	
	}
		
	if(isset($_POST["config_baseurl"]))
		$UConfig->baseURL =  substr($_POST["config_baseurl"], 0, $URLLENGTH);
	
	if(isset($_POST["config_outdir"]))
		$UConfig->outdir =  substr($_POST["config_outdir"], 0, $URLLENGTH);
	
		
	if(isset($_POST["config_cgi_platform"])) 
	{
		$UConfig->OutputOS = intval($_POST["config_cgi_platform"]); 
	}
		
	if(isset($_POST["config_platform"]))
	{
		$tmpFormat = intval($_POST["config_platform"]); 
		
		if($tmpFormat == $OUTPUT_ASPNET)
		{
			$UConfig->IsASPDotNet = 1;
			$UConfig->OutputOS = $OS_WINDOWS;
			$UConfig->OutputFormat = $OUTPUT_CGI;
			$UConfig->DotNetUseFormTags = 1;
			$UConfig->DotNetUsePostBacks = 0;
		}
		else
		{
				$UConfig->IsASPDotNet = 0;
				$UConfig->OutputFormat = intval($_POST["config_platform"]); 
		}
	}

	//start points - use hidden fields not displayed list (as can switch between spider and offline) 
	//Ensure array is empty
	$UConfig->startdir_list = array();
			
	if(isset($_POST["start_points_off"]))
	{
		//Save both offline and spider lists
		//if in spider mode use all options, if in offline mode only start point and base URL
			PrintDebug("Start points - offline\n");
				
			$spEntries = $_POST["start_points_off"]; 
			$baseEntries = $_POST["starturl_base_off"]; 
			$count = 0;

			$firstItem = true;
			foreach($spEntries as $nextEntry)
			{			
					$startdir_elem_item = new URL_ELEM();
					$startdir_elem_item->url = trim($nextEntry);
					$startdir_elem_item->baseURL = trim($baseEntries[$startdir_elem_item->url]);
					$UConfig->startdir_list[$count-1] = $startdir_elem_item;
					
					PrintDebug($count . " : " . $startdir_elem_item->url . " : "  . $startdir_elem_item->baseURL . "\n");
					
				$count++;
			}
	}
	
	//Ensure array is empty
	$UConfig->starturl_list = array();
	if(isset($_POST["start_points"]))
	{
			PrintDebug("Start points - spider\n");			
			$start_elem_item = new URL_ELEM();
			$spEntries = $_POST["start_points"]; 
			$baseEntries = $_POST["starturl_base"]; 
			$type = $_POST["starturl_type"]; 
			$useLimit = $_POST["starturl_uselimit"]; 
			$limit = $_POST["starturl_limit"]; 
			$boost = $_POST["starturl_boost"]; 
			 			 
			$count = 0;
			
			PrintDebug(serialize($spEntries) . "\n");
			PrintDebug(serialize($baseEntries) . "\n");
			PrintDebug(serialize($type) . "\n");
			PrintDebug(serialize($useLimit) . "\n");
			PrintDebug(serialize($limit) . "\n");
			PrintDebug(serialize($boost) . "\n");
			
	//First list item 0 is the value entered on the main indexer tab, uses different variables to rest of list
		//$UConfig->spiderURL = "";
		$UConfig->spiderURLtype = $type[0];	
		$UConfig->spiderURLUseLimit =  $useLimit[0];
		$UConfig->spiderURLLimit = $limit[0];
		$UConfig->spiderURLBoost = $boost[0];	
		
			foreach($spEntries as $nextEntry)
			{
					$start_elem_item = new URL_ELEM();
					$start_elem_item->url = trim($nextEntry);
					$start_elem_item->baseURL = trim($baseEntries[$start_elem_item->url]);
		
					$start_elem_item->urltype = $type[$start_elem_item->url];	// default url type
					$start_elem_item->uselimit = $useLimit[$start_elem_item->url];
					$start_elem_item->limit =  $limit[$start_elem_item->url];
					$start_elem_item->boost =  $boost[$start_elem_item->url];
					
					$UConfig->starturl_list[$count-1] = $start_elem_item;			
					
					PrintDebug($count . " : " . $start_elem_item->url . " : "  . $start_elem_item->baseURL . " : "  . $start_elem_item->uselimit . "\n");
			
				$count++;
			}	
	}
		
	//Scan options
	//Process scan extensions select field
	if(isset($_POST["config_scan_exts"]))
	{
		
		$extEntries = $_POST["config_scan_exts"]; 
		$i = 0;
					
		$imgURLArr = $_POST["ImageURL"];
		$useThumbArr = $_POST["UseThumbs"];
		$thumbsPathArr = $_POST["ThumbsPath"];
		$thumbsPrefixArr = $_POST["ThumbsFilenamePrefix"];
		$thumbsPostfixArr = $_POST["ThumbsFilenamePostfix"];
		$thumbsExtArr = $_POST["ThumbsExt"];
										
		//Ensure array is empty
		$UConfig->ExtensionList = array();		
					
					
		foreach($extEntries as $nextEntry)
		{	
				$item =  explode("|", $nextEntry); 			
				$ext_elem_item = new EXTENSION_ITEM();
	
				// Get extension 		
				$ext_elem_item->Ext =  trim($item[0]); 				
				
				// Get filetype - match to index in $FILETYPE_STRING array			
				$ext_elem_item->FileType = array_search(trim($item[1]), $FILETYPE_STRING); 
															
				//For each extensions check for the thumbnail/image options							
				if(isset($useThumbArr))
				{
				
					$ext_elem_item->UseThumbs = intval($useThumbArr[$ext_elem_item->Ext]);

					if($ext_elem_item->UseThumbs == 1)
					{																
							$ext_elem_item->UseThumbs = 0; //Only saved as true or false
							if(isset($imgURLArr[$ext_elem_item->Ext]))
								$ext_elem_item->ImageURL = substr($imgURLArr[$ext_elem_item->Ext], 0, $URLLENGTH);
								
					}
					
					if($ext_elem_item->UseThumbs == 2)
					{					
							$ext_elem_item->UseThumbs = true;
							//Get thumbnail options
							if(isset($thumbsPathArr[$ext_elem_item->Ext]))
								$ext_elem_item->ThumbsPath = substr($thumbsPathArr[$ext_elem_item->Ext], 0, $URLLENGTH);
								
							if(isset($thumbsPrefixArr[$ext_elem_item->Ext]))
								$ext_elem_item->ThumbsFilenamePrefix = substr($thumbsPrefixArr[$ext_elem_item->Ext], 0, $MAX_FNAME);
								
							if(isset($thumbsPostfixArr[$ext_elem_item->Ext]))
								$ext_elem_item->ThumbsFilenamePostfix = substr($thumbsPostfixArr[$ext_elem_item->Ext], 0, $MAX_FNAME);
								
							if(isset($thumbsExtArr[$ext_elem_item->Ext]))
								$ext_elem_item->ThumbsExt = substr($thumbsExtArr[$ext_elem_item->Ext], 0, $MAX_EXT);							
					}
				}
					
				$UConfig->ExtensionList[$i] = $ext_elem_item;
				$i++;		
		} 
	}
	

	$UConfig->ScanNoExtension = intval(isset($_POST["config_scan_noext"]), 2);
	$UConfig->ScanUnknownExtensions = intval(isset($_POST["config_scan_unknown"]), 2);
	$UConfig->PluginOpenNewWindow = intval(isset($_POST["config_scan_newwindow"]), 2);
	$UConfig->CRC32 = intval(isset($_POST["config_scan_duplicate"]), 2);
	
	//Skip options
	if(isset($_POST["config_skip_minwordlen"]))
		$UConfig->MinWordLen = intval($_POST["config_skip_minwordlen"]);
	$UConfig->SkipUnderscore = intval(isset($_POST["config_skip_underscore"]), 2); 
	
	//Wordlists - treat each line as a new word, remove entries that are too long / invalid, should display an error message
	//Reset skiplist and word list arraya
	$UConfig->SkipWords = array();
	$UConfig->SkipPages = array();
	
	if(isset($_POST["config_skip_wordlist"]))
	{
		$skipWordList = explode("\r\n", $_POST["config_skip_wordlist"]); 
		$numWords = 0;
		foreach ($skipWordList as $nextWord)
		{
			if($numWords > $MAXSKIPWORDS) //should flag an error messsage
				break;
				
			if(strlen($nextWord) > $WORDLENGTH)
				continue; //flag an error message
			
			//Trim before checking length or end up with empty strings at end
			trim($nextWord);
			if(strlen($nextWord) > 0)
			{
				$UConfig->SkipWords[$numWords] = $nextWord;		
				$numWords++;
			}
		}
	}
	
	if(isset($_POST["config_skip_pagelist"]))
	{
		$skipPageList = explode("\r\n", $_POST["config_skip_pagelist"]); 
		$numPages = 0;
		foreach ($skipPageList as $nextPage)
		{
			if($numPages > $MAXSKIPPAGES) //should flag an error messsage
				break;
				
			if(strlen($nextPage) > $MAXSKIPPAGELEN)
				continue; //flag an error message
			
			$nextPage = trim($nextPage);
			if(strlen($nextPage) > 0)
			{
				$UConfig->SkipPages[$numPages] = $nextPage;		
				$numPages++;
			}
		}
	}
										

	//Spider options	
	$UConfig->UseRobotsTxt = intval(isset($_POST["config_userobotstxt"]), 2); 
	$UConfig->ParseJSLinks = intval(isset($_POST["config_parsejslinks"]), 2); 
	$UConfig->ScanFileLinks = intval(isset($_POST["config_scanfilelinks"]), 2); 
	$UConfig->CheckThumbnailsExist = intval(isset($_POST["config_checkthumbs"]), 2); 
	$UConfig->UseLocalDescPath = intval(isset($_POST["config_usedescfolder"]), 2); 
	
	if(isset($_POST["config_descfolder"]))
		$UConfig->LocalDescPath = substr($_POST["config_descfolder"], 0, $MAX_PATH);
	
	if(isset($_POST["config_throttledelay"]))
		$UConfig->ThrottleDelay = intval($_POST["config_throttledelay"]);

	$UConfig->NoCache = intval(isset($_POST["config_dontusecache"]), 2);

if(isset($_POST["config_downloadthreads"]))
{
	if($_POST["config_downloadthreads"] == "single")
		$UConfig->NumDownloadThreads = 1;
	else
	{
		$numThreads = 1;
		
		if(isset($_POST["config_numdlthreads"]))
			$numThreads = intval($_POST["config_numdlthreads"]);
		
		if($numThreads > 0 && $numThreads <= $MAX_DOWNLOAD_THREADS)
			$UConfig->NumDownloadThreads = $numThreads;
		else
			$UConfig->NumDownloadThreads = 1; 
	}
}

	//proxy settings
		$UConfig->UseProxyServer = intval(isset($_POST["config_useproxy"]), 2); 
		if(isset($_POST["config_proxyserver"]))
				$UConfig->ProxyServer = substr($_POST["config_proxyserver"], 0, $URLLENGTH);	
				
	//Search page
	if(isset($_POST["config_searchformappearance"]))
		$UConfig->FormFormat = intval($_POST["config_searchformappearance"]);
	
	if(isset($_POST["config_resultslinking"]))
	{
	if($_POST["config_resultslinking"] == "current")
		$UConfig->LinkTarget = 0;
	else if($_POST["config_resultslinking"] == "new")
		$UConfig->LinkTarget = "_blank";
	else 
		{	
			if(isset($_POST["config_resultsname"]))
				$UConfig->LinkTarget = substr($_POST["config_resultsname"], 0,  $URLLENGTH);
		}
	}
	
	$UConfig->DefaultToAnd = intval(isset($_POST["config_defaultmatchall"]), 2); 
	$UConfig->ZoomInfo = intval(isset($_POST["config_showzoominfo"]), 2); 
	$UConfig->Timing = intval(isset($_POST["config_showsearchtime"]), 2); 	
	$UConfig->AllowExactPhrase = intval(isset($_POST["config_allowexactphrase"]), 2); 
	$UConfig->UseDateTime  = intval(isset($_POST["config_sortbydate"]), 2); 
	
	if(isset($_POST["config_sortdatedef"]))
		$UConfig->DefaultSort  = intval($_POST["config_sortdatedef"]); 
	$UConfig->UseDomainDiversity  = intval(isset($_POST["config_domaindiversity"]), 2); 
	
	$UConfig->DateRangeSearch  = intval(isset($_POST["config_daterangesearch"]), 2); 
	if(isset($_POST["config_daterangeformat"]))
		$UConfig->DateRangeFormat  = intval($_POST["config_daterangeformat"]); 

	$UConfig->Spelling = intval(isset($_POST["config_spellingsuggestion"]), 2); 
	
	if(isset($_POST["config_spellingresults"]))
		$UConfig->SpellingWhenLessThan = intval($_POST["config_spellingresults"]);

	//Results layout
	$UConfig->ResultNumber = intval(isset($_POST["config_res_number"]), 2);  
	$UConfig->ResultTitle = intval(isset($_POST["config_res_title"]), 2);  
	$UConfig->ResultMetaDesc = intval(isset($_POST["config_res_metadesciption"]), 2); 
	$UConfig->ResultContext = intval(isset($_POST["config_res_context"]), 2);  
	$UConfig->ResultTerms = intval(isset($_POST["config_res_terms"]), 2);  
	$UConfig->ResultScore = intval(isset($_POST["config_res_score"]), 2);  
	$UConfig->ResultDate = intval(isset($_POST["config_res_date"]), 2);  
	$UConfig->ResultURL = intval(isset($_POST["config_res_url"]), 2);  
	$UConfig->ResultFilesize = intval(isset($_POST["config_res_filesize"]), 2);  
	$UConfig->Highlighting = intval(isset($_POST["config_highlight_matched"]), 2); 
	$UConfig->GotoHighlight = intval(isset($_POST["config_highlight_jumpto"]), 2);  
	$UConfig->UseZoomImage = intval(isset($_POST["config_res_image"]), 2);
	if(isset($_POST["config_res_contextsize"]))
		$UConfig->ContextSize= intval($_POST["config_res_contextsize"]); 
	
	//Indexing optons
	$UConfig->IndexMetaDesc = intval(isset($_POST["config_index_metadescription"]), 2); 
	$UConfig->IndexTitle = intval(isset($_POST["config_index_title"]), 2); 
	$UConfig->IndexContent = intval(isset($_POST["config_index_contexnt"]), 2); 
	$UConfig->IndexKeywords = intval(isset($_POST["config_index_metakeywords"]), 2); 
	$UConfig->IndexFilename = intval(isset($_POST["config_index_filename"]), 2); 				
	$UConfig->IndexAuthor = intval(isset($_POST["config_index_metaauthor"]), 2);
	$UConfig->IndexLinkText = intval(isset($_POST["config_index_linktext"]), 2);
	$UConfig->IndexAltText = intval(isset($_POST["config_index_alttext"]), 2);
	$UConfig->IndexDCMeta = intval(isset($_POST["config_index_dublincore"]), 2);
	$UConfig->IndexParamTags = intval(isset($_POST["config_index_paramtag"]), 2);
	//$UConfig->IndexURLDomain = intval(isset($_POST["config_res_url"]), 2); //** might not be used?
	//$UConfig->IndexURLPath = intval(isset($_POST["config_res_url"]), 2);  //** might not be used?
	$UConfig->RewriteLinks = intval(isset($_POST["config_rewriteurls"]), 2); 
	
	if(isset($_POST["config_rewrite_find"]))
		$UConfig->RewriteFind = substr($_POST["config_rewrite_find"], 0,  $URLLENGTH);
	if(isset($_POST["config_rewrite_replace"]))
	$UConfig->RewriteWith = substr($_POST["config_rewrite_replace"], 0,  $URLLENGTH);
	
	$UConfig->WordJoinChars = "";
	if(isset($_POST["config_join_dot"]))
		$UConfig->WordJoinChars .= ".";
	if(isset($_POST["config_join_hyphen"]))
		$UConfig->WordJoinChars .= "-";
	if(isset($_POST["config_join_underscore"]))
		$UConfig->WordJoinChars .= "_";	
	if(isset($_POST["config_join_apostrophe"]))
		$UConfig->WordJoinChars .= "'";
	if(isset($_POST["config_join_hash"]))
		$UConfig->WordJoinChars .= "#";
	if(isset($_POST["config_join_dollar"]))
		$UConfig->WordJoinChars .= "$";
	if(isset($_POST["config_join_comma"]))
		$UConfig->WordJoinChars .= ",";
	if(isset($_POST["config_join_colon"]))
		$UConfig->WordJoinChars .= ":";
	if(isset($_POST["config_join_ampersand"]))
		$UConfig->WordJoinChars .= "&";
	if(isset($_POST["config_join_slash"]))
		$UConfig->WordJoinChars .= "\\";


	//Limits
	//Currently allowing editing as no distinction made between professional and normal
	
	$UConfig->TruncateShowURL  = intval(isset($_POST["config_limits_truncate_URLS"]), 2);
	$UConfig->TruncateTitleLen  =intval(isset($_POST["config_limits_truncate_titles"]), 2);
	$UConfig->LimitWordsPerPage  = intval(isset($_POST["config_limits_words_per_file"]), 2);
	$UConfig->LimitPerStartPt   = intval(isset($_POST["config_limits_files_per_sp"]), 2);
	$UConfig->LimitMaxWords   = intval(isset($_POST["config_limits_max_unique"]), 2);
	$UConfig->LimitURLsPerStartPt   = intval(isset($_POST["config_limits_URLs_per_sp"]), 2);
	


	
	
	if(isset($_POST["config_limits_maxfiles"]))
		$UConfig->MAXPAGES  = intval($_POST["config_limits_maxfiles"]);
	if(isset($_POST["config_limits_unique"]))
		$UConfig->MAXWORDS = intval($_POST["config_limits_unique"]);
	if(isset($_POST["config_limits_maxsize"]))
	{
		$UConfig->MAX_FILE_SIZE = intval($_POST["config_limits_maxsize"]);
		$UConfig->MAX_FILE_SIZE = 	$UConfig->MAX_FILE_SIZE * 1024;
	}
	if(isset($_POST["config_limits_maxdescription"]))
		$UConfig->DESCLENGTH = intval($_POST["config_limits_maxdescription"]);
		
	if(isset($_POST["config_limits_maxquery"]))
		$UConfig->MaxResultsPerQuery = intval($_POST["config_limits_maxquery"]);
			
	if(isset($_POST["config_limits_per_sp_val"]))
		$UConfig->MAXPAGES_PER_STARTPT = intval($_POST["config_limits_per_sp_val"]);
	if(isset($_POST["config_limits_per_file_val"]))
		$UConfig->MAXWORDS_PER_PAGE = intval($_POST["config_limits_per_file_val"]);
	if(isset($_POST["config_limits_truncate_titles_val"]))
		$UConfig->MAXTITLELENGTH = intval($_POST["config_limits_truncate_titles_val"]);
	if(isset($_POST["config_limits_truncate_URLS_val"]))
		$UConfig->ShowURLLength = intval($_POST["config_limits_truncate_URLS_val"]);
	
	if(isset($_POST["config_limits_URLs_per_sp_val"]))
		$UConfig->MAXURLVISITS_PER_STARTPT = intval($_POST["config_limits_URLs_per_sp_val"]);
		
		if(isset($_POST["config_limits_maxwordlength"]))
		$UConfig->MAXWORDLENGTH  = intval($_POST["config_limits_maxwordlength"]);
	
	if(isset($_POST["config_optimization"]))
		$UConfig->OptimizeSetting  = intval($_POST["config_optimization"]);

	//Authentication
	$UConfig->UseCookieLogin = intval(isset($_POST["config_cookies_autologin"]), 2);
	$UConfig->UseCookies = intval(isset($_POST["config_cookies_fromie"]), 2); //This might be redundant / not required in linux
	$UConfig->UseAuth = intval(isset($_POST["config_http_authenticate"]), 2);
	if(isset($_POST["config_http_login"]))
	$UConfig->Login = $_POST["config_http_login"];
		if(isset($_POST["config_http_pwd"]))
	$UConfig->Password = $_POST["config_http_pwd"];
	
	if(isset($_POST["config_cookies_loginpage"]))
		$UConfig->CookieLoginURL = $_POST["config_cookies_loginpage"];
	if(isset($_POST["config_cookies_loginvariable"]))
		$UConfig->CookieLoginName = $_POST["config_cookies_loginvariable"];
	if(isset($_POST["config_cookies_loginname"]))
		$UConfig->CookieLoginValue = $_POST["config_cookies_loginname"];
	if(isset($_POST["config_cookies_passwordvariable"]))
		$UConfig->CookiePasswordName = $_POST["config_cookies_passwordvariable"];
	if(isset($_POST["config_cookies_password"]))
		$UConfig->CookiePasswordValue = $_POST["config_cookies_password"];

	//Autocomplete
	
 	$UConfig->UseAutoComplete = intval(isset($_POST["config_autocomplete_use"]), 2);
  $UConfig->UseAutoCompleteInclude = intval(isset($_POST["config_autocomplete_usetop"]), 2);
  
  if(isset($_POST["config_autocomplete_url"]))
		$UConfig->AutoCompleteIncludeURL = trim($_POST["config_autocomplete_url"]);
  	
 	if(isset($_POST["config_autocomplete_topnum"]))
		$UConfig->AutoCompleteIncludeTopNum  = intval($_POST["config_autocomplete_topnum"]);
		
	
	$UConfig->AutoCompleteUsePageTitle  = intval(isset($_POST["config_autocomplete_usepagetitle"]));				
	$UConfig->AutoCompleteUseMetaDesc  = intval(isset($_POST["config_autocomplete_usemetadesc"]));				

	//Ensure array is empty
	$UConfig->AutoCompleteRules = array();
 	if(isset($_POST["config_autocomplete_list"]))
	{
		$autocompleteList = explode("\r\n", $_POST["config_autocomplete_list"]); 
		$numRules = 0;
		foreach ($autocompleteList as $nextRule)
		{
			if($numRules > $MAXAUTOCOMPLETE) //should flag an error messsage or warning
				break;
				
			if(strlen($nextRule) > $AUTOCOMPLETE_LEN)
				continue; //flag an error message or warning
			
			$nextRule = trim($nextRule);
			if(strlen($nextRule) > 0)
			{
				$UConfig->AutoCompleteRules[$numRules] = $nextRule;		
				$numRules++;
			}
		}
	}
		


	//Languages
	$UConfig->MapAccents = intval(isset($_POST["config_language_acdc"]), 2);
	$UConfig->MapAccentChars = intval(isset($_POST["config_language_accents"]), 2);
	$UConfig->MapLigatureChars = intval(isset($_POST["config_language_ligatures"]), 2);
	$UConfig->MapUmlautChars = intval(isset($_POST["config_language_umlauts"]), 2);
	$UConfig->MapAccentsToDigraphs = intval(isset($_POST["config_language_digraphs"]), 2);
	$UConfig->StripDiacritics = intval(isset($_POST["config_language_striparabic"]), 2);
	$UConfig->SearchAsSubstring = intval(isset($_POST["config_language_substringall"]), 2);
	$UConfig->DisableToLower = intval(isset($_POST["config_language_singlecase"]), 2);
	$UConfig->UseStemming = intval(isset($_POST["config_language_stemming"]), 2);
	$UConfig->MapLatinLigatureChars = intval(isset($_POST["config_language_latinlig"]), 2);
	
	if(isset($_POST["config_languages_encoding"]))
		$UConfig->UseUTF8 = intval($_POST["config_languages_encoding"]);
		
	if($UConfig->UseUTF8 == 0 ) 
		$UConfig->Codepage = $_POST["config_languages_encoding_picker"];

	if(isset($_POST["config_languages_zlang"]))
		$UConfig->LanguageFile = substr($_POST["config_languages_zlang"], 0,  $MAX_PATH);

	if(isset($_POST["config_languages_stem_lang"]))
		$UConfig->StemmingLanguageIndex = intval($_POST["config_languages_stem_lang"]);

	
	//Weightings	
	if(isset($_POST["config_weighting_title"]))
		$UConfig->WeightTitle = intval($_POST["config_weighting_title"]) - $WORDWEIGHT_NORMAL;
	if(isset($_POST["config_weighting_description"]))
		$UConfig->WeightDesc = intval($_POST["config_weighting_description"])- $WORDWEIGHT_NORMAL;
	if(isset($_POST["config_weighting_keywords"]))
		$UConfig->WeightKeywords = intval($_POST["config_weighting_keywords"])- $WORDWEIGHT_NORMAL;
	if(isset($_POST["config_weighting_filename"]))
		$UConfig->WeightFilename = intval($_POST["config_weighting_filename"])- $WORDWEIGHT_NORMAL;
	if(isset($_POST["config_weighting_heading"]))
		$UConfig->WeightHeadings = intval($_POST["config_weighting_heading"])- $WORDWEIGHT_NORMAL;
	if(isset($_POST["config_weighting_linkalt"]))
		$UConfig->WeightLinktext = intval($_POST["config_weighting_linkalt"])- $WORDWEIGHT_NORMAL;
	if(isset($_POST["config_weighting_body"]))
		$UConfig->WeightContent = intval($_POST["config_weighting_body"])- $WORDWEIGHT_NORMAL;
	
	if(isset($_POST["config_weighting_wordpos"]))
		$UConfig->WeightProximity = intval($_POST["config_weighting_wordpos"]);
	if(isset($_POST["config_weighting_density"]))
		$UConfig->WeightDensity = intval($_POST["config_weighting_density"]);
	if(isset($_POST["config_weighting_urllength"]))
		$UConfig->WeightShortURLs = intval($_POST["config_weighting_urllength"]);

	//Filtering
	$UConfig->UseContentFilter = intval(isset($_POST["config_filtering_enable"]), 2);
	if(isset($_POST["filtering_rules"]))
		$UConfig->ContentFilterRules = explode("\n", $_POST["filtering_rules"], $MAXFILTERRULES);

	//Categories
	$UConfig->UseCats = intval(isset($_POST["config_categories_enable"]), 2);
		
	//Split category on known char "-"
	$UConfig->cats_list = array();
	if(isset($_POST["category_rules"]))
	{
		
		$tempcats = $_POST["category_rules"];
		$numCats = 0;
			
		foreach($tempcats as $cat)
		{
			PrintDebug("NextCAt: " . $cat);
			
			$cat_elem_item = new CAT_ELEM();

			$nextCat = explode("|", $cat, 4);
			
			$cat_elem_item->name = trim($nextCat[0]);
			$cat_elem_item->pattern = trim($nextCat[1]);
			if(isset($nextCat[2]))
				$cat_elem_item->description = trim($nextCat[2]);
			if(isset($nextCat[3]))
				$cat_elem_item->IsExclusive = intval(isset($nextCat[3]), 2);
				
			$UConfig->cats_list[$numCats] = $cat_elem_item;		
			
						PrintDebug("NextCAt: " . serialize(	$UConfig->cats_list[$numCats]));	
			$numCats++;	
		}
	
	}

	
	$UConfig->UseDefCatName = intval(isset($_POST["config_categories_catchfiles"]), 2);  
	$UConfig->SearchMultiCats = intval(isset($_POST["config_categories_mutil"]), 2); 
	$UConfig->DisplayCatSummary  = intval(isset($_POST["config_categories_breakdown"]), 2);
	if(isset($_POST["config_categories_catchcategory"]))
		$UConfig->DefCatName = substr($_POST["config_categories_catchcategory"], 0,  $CAT_NAME_LEN);
	
	//Sitemaps		
	$UConfig->SitemapXML = intval(isset($_POST["config_sitemap_xml"]), 2); 
	$UConfig->SitemapTXT = intval(isset($_POST["config_sitemap_txt"]), 2); 
	if(isset($_POST["config_sitemap_include"]))
		$UConfig->SitemapUseBaseURL = intval($_POST["config_sitemap_include"]); 
	if(isset($_POST["config_sitemap_baseurl"]))
		$UConfig->SitemapBaseURL = substr($_POST["config_sitemap_baseurl"], 0,  $URLLENGTH);
	$UConfig->SitemapUpload  = intval(isset($_POST["config_sitemap_upload"]), 2); 
	if(isset($_POST["config_sitemap_uploadpath"]))
		$UConfig->SitemapUploadPath = substr($_POST["config_sitemap_uploadpath"], 0,  $MAX_PATH);
	$UConfig->SitemapUsePageBoost = intval(isset($_POST["config_sitemap_pageboost"]), 2); 
	
	
	//Synonyms
	$UConfig->syn_list = array();
	if(isset($_POST["synonym_rules"]))
	{
		$synEntries = $_POST["synonym_rules"]; 
		$i = 0;
			
		foreach($synEntries as $nextEntry)
		{	
				$item =  explode("|", $nextEntry); 			
				$syn_elem_item = new SYN_ELEM();
	
				// Get word
				$syn_elem_item->word = trim($item[0]);
				// Get synonyms
				$syn_elem_item->synonyms = trim($item[1]);
				
				$UConfig->syn_list[$i] = $syn_elem_item;
				$i++;		
		} 
	}

	//Recommended
	if(isset($_POST["config_recommended_max_links"]))
	 	$UConfig->RecommendedMax = intval($_POST["config_recommended_max_links"]); 
	
	$UConfig->RecommendedList = array();
	if(isset($_POST["recommended_rules"]))
	{
		$recEntries = $_POST["recommended_rules"];
		$i = 0;
			
		foreach($recEntries as $nextEntry)
		{	
				$item =  explode("|", $nextEntry); 			
				$rec_elem_item = new REC_ELEM();
	
				// Get word
				$rec_elem_item->word = trim($item[0]);
				// Get URL
				$rec_elem_item->URL = trim($item[1]);
				// get title
				$rec_elem_item->title = trim($item[2]);
				// get description
				$rec_elem_item->desc = trim($item[3]);
				// get image url
				$rec_elem_item->imgURL = trim($item[4]);
				
				$UConfig->RecommendedList[$i] = $rec_elem_item;
				$i++;		
		} 
	}
	
	//Custom meta
	$UConfig->metafield_list = array();
	if(isset($_POST["meta_rules"]))
	{
		$metaEntries = $_POST["meta_rules"];
		$i = 0;
			
		foreach($metaEntries as $nextEntry)
		{	
				$item =  explode("|", $nextEntry); 			
				$meta_elem_item = new METAFIELD_ELEM();
	
				// get meta name
				$meta_elem_item->name = trim($item[0]);
	
				// Get type - match to type array
				$meta_elem_item->type = array_search(trim($item[1]), $METAFIELD_STRINGS); 
				
				// get show search as
				$meta_elem_item->showname = trim($item[2]);
				
				// get criteria name
				$meta_elem_item->formname = trim($item[3]);
				
				// Get URL - match to method array
				$meta_elem_item->method = array_search(trim($item[4]), $METAFIELD_SEARCH_STRINGS); 
		
				//Drop down values - can be empty or seperated by ",'
				if(isset($item[5]))
					$meta_elem_item->DropdownValues =  array_map('trim', explode(",", $item[5])); 														
				
				$UConfig->metafield_list[$i] = $meta_elem_item;
							
				$i++;		
		} 
	}
	
	//Index log
	if(isset($_POST["config_indexlog_detail"]))
		$UConfig->LogMode = intval(($_POST["config_indexlog_detail"]));  
		

	$UConfig->LogHTMLErrors = intval(isset($_POST["config_indexlog_warnings"]), 2); 
	$UConfig->LogDebugMode = intval(isset($_POST["config_indexlog_debugmode"]), 2);  
	$UConfig->LogAppendDatetime = intval(isset($_POST["config_indexlog_appenddate"]), 2);  
	$UConfig->LogWriteToFile = intval(isset($_POST["config_indexlog_save"]), 2);
	if(isset($_POST["config_indexlog_path"]))   
		$UConfig->LogSaveToFilename = substr($_POST["config_indexlog_path"], 0,  $MAX_PATH);
	
	//Advanced
	$UConfig->UseSrcPaths = intval(isset($_POST["config_advanced_specifyscript"]), 2); 
	$UConfig->WizardUploadReqd = intval(isset($_POST["config_advanced_wizard"]), 2);  
	$UConfig->BeepOnFinish = intval(isset($_POST["config_advanced_beep"]), 2);  
	$UConfig->Logging = intval(isset($_POST["config_advanced_logsearches"]), 2); 
	$UConfig->NoCharset = intval(isset($_POST["config_advanced_disablechar"]), 2);  
	$UConfig->UseXML = intval(isset($_POST["config_advanced_usexml"]), 2);  
	$UConfig->XMLHighlight = intval(isset($_POST["config_advanced_hl_xml"]), 2); 
	 
	if(isset($_POST["config_advanced_scriptpath"]))  
		$UConfig->SourceScriptPath  = substr($_POST["config_advanced_scriptpath"], 0,  $MAX_PATH);
	if(isset($_POST["config_advanced_logpath"]))   
 		$UConfig->LogFileName = substr($_POST["config_advanced_logpath"], 0,  $MAX_PATH);
 	if(isset($_POST["config_advanced_linkback"]))  
 		$UConfig->LinkBackURL = substr($_POST["config_advanced_linkback"], 0, $URLLENGTH);
	if(isset($_POST["config_advanced_ci_title"]))  
		$UConfig->XMLTitle  = substr($_POST["config_advanced_ci_title"], 0,  $TITLELEN);
	if(isset($_POST["config_advanced_ci_desc"])) 
		$UConfig->XMLDescription  = substr($_POST["config_advanced_ci_desc"], 0,  $MAXDESCLEN); 
	if(isset($_POST["config_advanced_ci_url"]))  
		$UConfig->XMLLink = substr($_POST["config_advanced_ci_url"], 0,  $URLLENGTH); 
	if(isset($_POST["config_advanced_ci_xlst"]))  
		$UConfig->XMLStyleSheetURL = substr($_POST["config_advanced_ci_xlst"], 0,  $URLLENGTH);
	if(isset($_POST["config_advanced_os_descURL"]))  
		$UConfig->XMLOpenSearchDescURL = substr($_POST["config_advanced_os_descURL"], 0,  $URLLENGTH);
	if(isset($_POST["config_advanced_useragent"]))  
		$UConfig->UserAgentStr = substr($_POST["config_advanced_useragent"], 0,  $HTTP_MAX_USERAGENT_LEN);

	//Filetype/plugin 
	if(isset($_POST["PdfUseMeta"]))
		$UConfig->PluginConfig->PdfUseMeta  = intval($_POST["PdfUseMeta"]); 
	if(isset($_POST["PdfUseDescFiles"]))
		$UConfig->PluginConfig->PdfUseDescFiles  = intval($_POST["PdfUseDescFiles"]); 
	if(isset($_POST["PdfUsePassword"]))
		$UConfig->PluginConfig->PdfUsePassword  = intval($_POST["PdfUsePassword"]); 
	if(isset($_POST["PdfToTextMethod"]))
		$UConfig->PluginConfig->PdfToTextMethod  = intval($_POST["PdfToTextMethod"]); 
	if(isset($_POST["PdfToTextMethod"]))
		$UConfig->PluginConfig->PdfPassword = substr($_POST["PdfPassword"], 0,  $PLUGIN_PASSWORD_LEN); 
	if(isset($_POST["PdfHighlight"]))
		$UConfig->PluginConfig->PdfHighlight  = intval($_POST["PdfHighlight"]);
	 
	 
	if(isset($_POST["DocUseMeta"]))
		$UConfig->PluginConfig->DocUseMeta   = intval($_POST["DocUseMeta"]); 
	if(isset($_POST["DocUseDescFiles"]))
		$UConfig->PluginConfig->DocUseDescFiles  = intval($_POST["DocUseDescFiles"]); 

	if(isset($_POST["XlsUseMeta"]))
		$UConfig->PluginConfig->XlsUseMeta   = intval($_POST["XlsUseMeta"]); 
	if(isset($_POST["XlsUseDescFiles"]))
		$UConfig->PluginConfig->XlsUseDescFiles  = intval($_POST["XlsUseDescFiles"]); 

	if(isset($_POST["PptUseMeta"]))
		$UConfig->PluginConfig->PptUseMeta  = intval($_POST["PptUseMeta"]); 
	if(isset($_POST["PptUseDescFiles"]))
		$UConfig->PluginConfig->PptUseDescFiles  = intval($_POST["PptUseDescFiles"]); 

	if(isset($_POST["WpdUseMeta"]))
		$UConfig->PluginConfig->WpdUseMeta   = intval($_POST["WpdUseMeta"]); 
	if(isset($_POST["WpdUseDescFiles"]))
		$UConfig->PluginConfig->WpdUseDescFiles  = intval($_POST["WpdUseDescFiles"]); 

	if(isset($_POST["SwfUseMeta"]))
		$UConfig->PluginConfig->SwfUseMeta  = intval($_POST["SwfUseMeta"]); 
	if(isset($_POST["SwfUseDescFiles"]))
		$UConfig->PluginConfig->SwfUseDescFiles  = intval($_POST["SwfUseDescFiles"]); 

	if(isset($_POST["RtfUseMeta"]))
		$UConfig->PluginConfig->RtfUseMeta  = intval($_POST["RtfUseMeta"]); 
	if(isset($_POST["RtfUseDescFiles"]))
		$UConfig->PluginConfig->RtfUseDescFiles   = intval($_POST["RtfUseDescFiles"]); 

	if(isset($_POST["DjvuUseMeta"]))
		$UConfig->PluginConfig->DjvuUseMeta   = intval($_POST["DjvuUseMeta"]); 
	if(isset($_POST["DjvuUseDescFiles"]))
		$UConfig->PluginConfig->DjvuUseDescFiles  = intval($_POST["DjvuUseDescFiles"]); 

	if(isset($_POST["Mp3UseMeta"]))
		$UConfig->PluginConfig->Mp3UseMeta   = intval($_POST["Mp3UseMeta"]);
	if(isset($_POST["Mp3UseDescFiles"])) 
		$UConfig->PluginConfig->Mp3UseDescFiles   = intval($_POST["Mp3UseDescFiles"]); 
	if(isset($_POST["Mp3UseTechnical"]))
		$UConfig->PluginConfig->Mp3UseTechnical  = intval($_POST["Mp3UseTechnical"]); 
	
	if(isset($_POST["DwfUseMeta"]))
		$UConfig->PluginConfig->DwfUseMeta   = intval($_POST["DwfUseMeta"]); 
	if(isset($_POST["DwfUseDescFiles"]))
		$UConfig->PluginConfig->DwfUseDescFiles  = intval($_POST["DwfUseDescFiles"]); 
	if(isset($_POST["DwfUseTechnical"]))
		$UConfig->PluginConfig->DwfUseTechnical   = intval($_POST["DwfUseTechnical"]); 

	if(isset($_POST["ImgUseMeta"]))
		$UConfig->PluginConfig->ImgUseMeta  = intval($_POST["ImgUseMeta"]); 
	if(isset($_POST["ImgUseDescFiles"]))
		$UConfig->PluginConfig->ImgUseDescFiles   = intval($_POST["ImgUseDescFiles"]); 
	if(isset($_POST["ImgUseTechnical"]))
		$UConfig->PluginConfig->ImgUseTechnical   = intval($_POST["ImgUseTechnical"]); 
		
	if(isset($_POST["ImgMinFilesize"]))
		$UConfig->PluginConfig->ImgMinFilesize   = intval($_POST["ImgMinFilesize"]); 

	if(isset($_POST["OfficeXmlUseMeta"]))
		$UConfig->PluginConfig->OfficeXmlUseMeta   = intval($_POST["OfficeXmlUseMeta"]); 
	if(isset($_POST["OfficeXmlUseDescFiles"]))
		$UConfig->PluginConfig->OfficeXmlUseDescFiles   = intval($_POST["OfficeXmlUseDescFiles"]); 
	if(isset($_POST["OfficeXmlTextOnly"]))
		$UConfig->PluginConfig->OfficeXmlTextOnly   = intval($_POST["OfficeXmlTextOnly"]); 

	if(isset($_POST["TorrentUseDescFiles"]))
		$UConfig->PluginConfig->TorrentUseDescFiles   = intval($_POST["TorrentUseDescFiles"]); 

	if(isset($_POST["MhtUseDescFiles"]))
		$UConfig->PluginConfig->MhtUseDescFiles   = intval($_POST["MhtUseDescFiles"]); 

	if(isset($_POST["ZipUseDescFiles"]))
		$UConfig->PluginConfig->ZipUseDescFiles   = intval($_POST["ZipUseDescFiles"]); 
	if(isset($_POST["ZipExtractFiles"]))
		$UConfig->PluginConfig->ZipExtractFiles   = intval($_POST["ZipExtractFiles"]); 

	if(isset($_POST["binary_use_desc"]))
		$UConfig->BinaryUseDescFiles   = intval($_POST["binary_use_desc"]); 
	if(isset($_POST["binary_extract_strings"]))
		$UConfig->BinaryExtractStrings   = intval($_POST["binary_extract_strings"]); 	
		

}


$SelfURL =  $_SERVER['PHP_SELF'];

//Load config file passed from Zoomindexer.php, otherwsie use the default
if(isset($_POST['config_filename']))
	$CurrentConfigPath  = $_POST['config_filename'];


$createNewFile = false;

if(isset($_POST['create_new']))
	$createNewFile = true;

$cfgRedirectFailed = false;
$cfgSaveFailed = false;
$cfgLoaded = false;

if(strlen($CurrentConfigPath) > 0)
	$cfgLoaded = LoadZCFGFile($CurrentConfigPath, $createNewFile);


//Check that file open succedded 
if($cfgLoaded == true) 
{
			
	//Check if form has been submitted / save config
	//There is also a $_POST['action'] code fragment,  we were thinking of having an apply function each time they close a section
	if (isset($_POST['submit']) && $_POST['submit'] == "Save Changes")
	{  
	
		//Update config struct with values from form submission
		UpdateConfig();
		
		//Save config file
		$cfgsaved =  SaveZCFGFile($CurrentConfigPath); 
				
		//Redirect back to zoom indexer page if everything saved ok, 
		//else stay here and display an error mesasge if required
		if($cfgsaved == true)
		{
			$host  = $_SERVER['HTTP_HOST'];
			$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			header("Location: http://$host$uri/ZoomIndexer.php?config=$CurrentConfigPath");
			
			$cfgRedirectFailed = true;
			
			
		}
		else
			$cfgSaveFailed = true;

	}
	else 
	{
		function zlangfiles($file)
			{ 
					PrintDebug("Zlang filter: " . $file);
																	 
					if(strpos($file, ".zlang") === FALSE)
						return FALSE;
					else
						return TRUE;
			}
																
		//scan for zlang files
		//Scan "lang" directory where zoom executable is and add to an array for use in a dropdown list	
		//Results from scandir inclused directories and any other files, so filter them to only include .zlang files
		$ZlangFiles = array_filter(scandir( $EngineExeDir . "/lang/", 0), "zlangfiles");
		
	}
		
}


?>
<!DOCTYPE html>
<html>
<head>
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
	<META HTTP-EQUIV="Expires" CONTENT="-1">
	
	<title>Zoom Indexer Configuration</title>
<script type="text/javascript">
				
	var LastElement = null;			

		function showHide(shID) 
		{
			if (document.getElementById(shID)) 
			{
				if (document.getElementById(shID).style.display == "none")
				{
					document.getElementById(shID).style.display	= "";					
				}
				else
				{
					document.getElementById(shID).style.display = "none";					
				}
			}
		}
		function doSelectRow(cbID, rowID) 
		{			
			if (document.getElementById(cbID)) 
			{				
				if (!document.getElementById(cbID).checked) 
					document.getElementById(cbID).checked = true;		
							
				if (document.getElementById(rowID))
				{					
					document.getElementById(rowID).style.backgroundColor = "#CCFFAA";
				}
			}			
		}		
		function onChangeRow(cbID, rowID) 
		{			
			if (document.getElementById(cbID)) 
			{		
				if (document.getElementById(cbID).checked == 1)
				{
					if (document.getElementById(rowID))
						document.getElementById(rowID).style.backgroundColor = "#CCFFAA";
				}
				else
				{
					if (document.getElementById(rowID))
					{
						document.getElementById(rowID).style.backgroundColor = "#FFFFFF";
					}
				}
			}			
		}				
	
	function SetFreeLimits()
	{
		<?php
		echo "var FREE = $FREE_EDITION;\n";
		echo "var FREE_EDITION_MAXPAGES = $FREE_EDITION_MAXPAGES;\n";
		echo "var FREE_EDITION_MAXWORDS = $FREE_EDITION_MAXWORDS;\n";
		echo "var FREE_EDITION_MAXFILESIZE = $FREE_EDITION_MAXFILESIZE / 1024;\n";
		echo "var FREE_EDITION_DESCLENGH = $FREE_EDITION_DESCLENGH;\n";
		echo "var FREE_EDITION_MAXQUERY = $FREE_EDITION_MAXQUERY;\n";
		echo "var FREE_EDITION_WORDLEN = $FREE_EDITION_WORDLEN;\n";
		echo "var TITLELEN = $TITLELEN;\n";
		echo "var URLLENGTH = $URLLENGTH;\n";
	
		?>
		
		var edition = document.getElementById("zoom_version").value;
		
		if(edition == FREE)
		{	
			//set free edition limits
			document.getElementsByName("config_limits_maxfiles")[0].value = FREE_EDITION_MAXPAGES;		
			document.getElementsByName("config_limits_maxsize")[0].value = FREE_EDITION_MAXFILESIZE;	
			document.getElementsByName("config_limits_maxdescription")[0].value = FREE_EDITION_DESCLENGH;	
			document.getElementsByName("config_limits_maxquery")[0].value = FREE_EDITION_MAXQUERY;	
			document.getElementsByName("config_limits_maxwordlength")[0].value = FREE_EDITION_WORDLEN;	
			document.getElementsByName("config_limits_unique")[0].value = FREE_EDITION_MAXWORDS;	
			document.getElementsByName("config_limits_per_sp_val")[0].value = FREE_EDITION_MAXPAGES;	
			document.getElementsByName("config_limits_per_file_val")[0].value = FREE_EDITION_MAXWORDS;	
			document.getElementsByName("config_limits_truncate_titles_val")[0].value = TITLELEN;	
			document.getElementsByName("config_limits_truncate_URLS_val")[0].value = URLLENGTH;	
						
			//Disabled the controls
			document.getElementsByName("config_limits_maxfiles")[0].disabled = true;
			document.getElementsByName("config_limits_maxsize")[0].disabled = true;
			document.getElementsByName("config_limits_maxdescription")[0].disabled = true;
			document.getElementsByName("config_limits_maxquery")[0].disabled = true;
			document.getElementsByName("config_limits_maxwordlength")[0].disabled = true;			
			document.getElementsByName("config_limits_max_unique")[0].disabled = true;
			document.getElementsByName("config_limits_unique")[0].disabled = true;
			document.getElementsByName("config_limits_files_per_sp")[0].disabled = true;
			document.getElementsByName("config_limits_per_sp_val")[0].disabled = true;
			document.getElementsByName("config_limits_words_per_file")[0].disabled = true;
			document.getElementsByName("config_limits_per_file_val")[0].disabled = true;
			document.getElementsByName("config_limits_truncate_titles")[0].disabled = true;
			document.getElementsByName("config_limits_truncate_titles_val")[0].disabled = true;
			document.getElementsByName("config_limits_truncate_URLS")[0].disabled = true;
			document.getElementsByName("config_limits_truncate_URLS_val")[0].disabled = true;
																						
			
		}
		
	}
	
	function EnableHTTPAuth()
	{
		if(document.getElementsByName("config_http_authenticate")[0].checked == true)
		{
			document.getElementsByName("config_http_login")[0].disabled = false;
			document.getElementsByName("config_http_pwd")[0].disabled = false;	
		}
		else
		{
			document.getElementsByName("config_http_login")[0].disabled = true;
			document.getElementsByName("config_http_pwd")[0].disabled = true;	
		}
	
	}
	
	function EnableCookieAuth()
	{	
		if(document.getElementsByName("config_cookies_autologin")[0].checked == true)
		{
			document.getElementsByName("config_cookies_loginpage")[0].disabled = false;
			document.getElementsByName("config_cookies_loginvariable")[0].disabled = false;
			document.getElementsByName("config_cookies_loginname")[0].disabled = false;		
			document.getElementsByName("config_cookies_passwordvariable")[0].disabled = false;
			document.getElementsByName("config_cookies_password")[0].disabled = false;	
		}
		else
		{
			document.getElementsByName("config_cookies_loginpage")[0].disabled = true;
			document.getElementsByName("config_cookies_loginvariable")[0].disabled = true;	
			document.getElementsByName("config_cookies_loginname")[0].disabled = true;	
			document.getElementsByName("config_cookies_passwordvariable")[0].disabled = true;
			document.getElementsByName("config_cookies_password")[0].disabled = true;	
		}
	
	}
	
	//Return 0 on failure (prevent user fomr navigating away from section)
	function OnLeaveLimits()
	{
		<?PHP
		echo "var FREE = $FREE_EDITION;\n";
		echo "var STAND = $STANDARD_EDITION;\n";
		echo "var PRO = $PRO_EDITION;\n";
		echo "var ENT = $ENTERPRISE_EDITION	;\n";
		echo "var OUTPUT_PHP = $OUTPUT_PHP;\n";
		echo "var OUTPUT_ASP = $OUTPUT_ASP;\n";
		echo "var OUTPUT_JSFILE = $OUTPUT_JSFILE;\n";
		echo "var OUTPUT_CGI = $OUTPUT_CGI;\n";	
		echo "var OUTPUT_ASPNET = $OUTPUT_ASPNET;\n";	
		echo "var FREE_EDITION_MAXPAGES = $FREE_EDITION_MAXPAGES;\n";
		echo "var STANDARD_EDITION_MAXPAGES = $STANDARD_EDITION_MAXPAGES;\n";
		echo "var PRO_EDITION_MAXPAGES = $PRO_EDITION_MAXPAGES;\n";
		echo "var MAX_THEORETICAL_BASEWORDS = $MAX_THEORETICAL_BASEWORDS;\n";
		echo "var MAXDESCLEN = $MAXDESCLEN;\n";
		echo "var MAXWORDLENGTH_LIMIT = $MAXWORDLENGTH_LIMIT;\n";
		echo "var TITLELEN = $TITLELEN;\n";
		echo "var URLLENGTH = $URLLENGTH;\n"; 
		echo "var MAX_THEORETICAL_PAGES = $MAX_THEORETICAL_PAGES;\n"; 
		echo "var WORDLENGTH = $WORDLENGTH;\n"; 
		
		if(php_uname("m") == "i386")
			echo "var using64bit = false";
		else
			echo "var using64bit = true";
			
		?>
	
		
		var OutputFormat = 0;
		for (i = 0; i < document.getElementsByName('config_platform').length; i++) 
		{
           if (document.getElementsByName('config_platform')[i].checked) 
           {
           	OutputFormat = document.getElementsByName('config_platform')[i].value;
           }
    }
		
		var edition = document.getElementById("zoom_version").value;
		
		if(edition == PRO || edition == ENT)
		{
						
			var tmpMaxPages = document.getElementsByName("config_limits_maxfiles")[0].value;
			if(OutputFormat == OUTPUT_PHP && tmpMaxPages > 65500)
			{
				alert("Invalid number for max files limit entered.\n\nThere is a technical limit of 65,500 files with the search platform\n" 
				 	+ "selected. Please specify a lower limit and try again.\n\nIf you need to index over this limit, you must select the CGI platform.");
				return 0;
			}
			
			if (edition == PRO && tmpMaxPages > PRO_EDITION_MAXPAGES)
			{
	
				if(confirm("Max pages limit for Pro Edition exceeded.\nYou will need the Enterprise Edition to index more than\n" + PRO_EDITION_MAXPAGES + "pages.\n\nPlease lower your max pages limit to the actual number of\n" +
							"files you need to index, or visit our website for details\non upgrading to Enterprise Edition.\n\nWould you like to visit our website now?") == true)
						{
							window.open("http://www.wrensoft.com/zoom/editions.html");
						}
					
					return 0;
					
			}
			
			var check_state = document.getElementsByName('config_limits_max_unique')[0].checked;		
			if (check_state == true)
			{
				var tmpMaxWords = document.getElementsByName("config_limits_unique")[0].value;
				
				if (OutputFormat != OUTPUT_CGI && tmpMaxWords > 500000)
				{
					alert("Invalid number for max words entered.\nThere is a technical limit of 500,000 unique words with the search\n" +
								"platform selected. While it is technically feasible to index this many\nwords, the load and server requirements would be an issue. Please\n" +
								"specify a lower limit.\n\nIf you need to index a site over this limit, you must select the CGI platform.");
					return 0;
				}
	
				if (tmpMaxWords < 50)
				{
					alert("Invalid unique words limit entered.\nThe number of unique words you have entered is too low.\n" +
						"You should have a limit of at least 50 unique words or greater.");
					return 0;
				}
	
	
				if (tmpMaxWords >= MAX_THEORETICAL_BASEWORDS)
				{
					alert("The number of unique words specified exceeds the practical\nlimitations of your hardware.\n\n" +
						"Note that you should set the limits to a reasonable number that reflects\nthe content you intend to index.");					
					return 0;
				}
			}

				var tmpMaxFileSize = document.getElementsByName("config_limits_maxsize")[0].value;
				if ( tmpMaxFileSize < 1 || (using64bit == false && tmpMaxFileSize*1024 > 2147483647))
				{
					alert("The max file size you have entered is not valid.\nZoom will not be able to allocate the required amount\n" +
						"of RAM for this filesize.\n\nPlease note that this value is specified in KB (kilobytes),\n1 KB = 1024 bytes and, 1024 KB = 1 MB (megabyte)\n");
						return 0;
				}
				
						
				tmpDescLength  = document.getElementsByName("config_limits_maxdescription")[0].value;
				if (tmpDescLength > MAXDESCLEN || tmpDescLength < 10)
				{
					alert("Invalid number for description length.\nThe number of characters you have entered for the description entries is invalid.\n" +
					"Please specify a value between 10 and " + MAXDESCLEN + ".");
					return 0;
				}
			
			check_state = document.getElementsByName("config_limits_maxquery")[0].value;
			
			if (check_state < 10)
			{			
				alert("Invalid number for max. results per query.\nThe number of results per search query is invalid.\n" +
					"Please specify a value between 10 and the maximum pages indexed.");
				return 0;
			}
			
			check_state = document.getElementsByName("config_limits_maxwordlength")[0].value;
			if ( check_state < WORDLENGTH || check_state > MAXWORDLENGTH_LIMIT)
			{			
				
				alert("The max word length is invalid.\nPlease specify a value between " + WORDLENGTH +" and " + MAXWORDLENGTH_LIMIT);
				return 0;
			}
			
		
		check_state = document.getElementsByName("config_limits_files_per_sp")[0].checked;
		if (check_state == true)
		{
			var tmpMaxPagesPerStartPt = document.getElementsByName("config_limits_per_sp_val")[0].value; 
			
			if (tmpMaxPagesPerStartPt > tmpMaxPages || tmpMaxPagesPerStartPt == 0)
			{
				alert("Invalid number for max files per start point entered.\nThe max number of files per start point can not exceed the max number\n" +
					"of pages to index. Please change your values and try again.");						
				return 0;
			}
		}
		
		check_state = document.getElementsByName("config_limits_words_per_file")[0].checked;
		if (check_state == true)
		{
			var tmpMaxWordsPerPage = document.getElementsByName("config_limits_per_file_val")[0].value; 
			if (tmpMaxWordsPerPage > tmpMaxWords || tmpMaxWordsPerPage == 0)
			{
				alert("Invalid number for max words per page entered.\nThe max number of words per page can not exceed the max total\n" +
					"number of words to index. Please change your values and try again.");						
				return 0;
			}
		}
				
		check_state = document.getElementsByName("config_limits_truncate_titles")[0].checked;
		if (check_state == true)
		{
			var tmpTitleLen = document.getElementsByName("config_limits_truncate_titles_val")[0].value;  
			if (tmpTitleLen > TITLELEN || tmpTitleLen == 0)
			{
				alert("Invalid title length.\nThe title length you have specified is greater than the maximum allowed (" + TITLELEN + ").\n");					
				return 0;
			}
		}	
			
			
		check_state = document.getElementsByName("config_limits_truncate_URLS")[0].checked;
		if (check_state == true)
		{
			var tmpShowURLLen = document.getElementsByName("config_limits_truncate_URLS_val")[0].value;
			if (tmpShowURLLen > URLLENGTH || tmpShowURLLen == 0)
			{
				alert("Invalid URL length.\nThe URL length you have specified is greater than the maximum allowed (" + URLLENGTH + ").\n");					
				return 0;
			}
		}	
		
		if (tmpMaxPages == 0 || tmpMaxWords == 0 || tmpMaxFileSize == 0 || tmpDescLength == 0)
		{
			alert("Invalid number for limit entered\nYou have entered zero for one or more of the limit configurations.\n" +
				"The indexer will not be able to scan any files with this limit.");
			return 0;
		}	 	
			
		if (OutputFormat == OUTPUT_JSFILE)
		{
			if (tmpMaxPages > 200000 || tmpMaxWords > 200000)
			{				
				
			alert("JavaScript is not the best platform for searching a large number of files (as indicated by the Limits you have specified).\n\n" +				
			"Depending on the how close you reach these limits, you may find your search page failing to load in some browsers. IE6 and IE7 in particular are known to be very restrictive and slow in executing intensive JavaScripts.\n\n" +
			"If you proceed with this option, you should test the search page created with these limits in different browsers to ensure there are no problems.\n\n" +
			"You should also seriously consider alternatives such as using PHP or CGI for better performance and greater capacity.\n\n" +
			"There are more details on our website here: http://www.wrensoft.com/zoom/support/faq_CD_search.html");
			}
		}	
		
		
		if (tmpMaxPages >= MAX_THEORETICAL_PAGES)
		{
			alert("The number of pages specified exceeds the practical\nlimitations of your hardware.\n\n" +
					"Note that you should set the limits to a reasonable number that reflects\nthe content you intend to index.");					
			return 0;
		}

		
		if(using64bit == false)
		{
			
			if (tmpMaxPages > 2000000)
			{
				alert("Depending on the sizes of the pages indexed, it is extremely likely that\nyou will exceed the 32-bit address space limitations of your system,\n" +
					"rendering the resultant index files incomplete.\n\nWe would suggest lowering the limits specified or using the 64-bit\n" +
					"version of Zoom on a 64-bit system. See the Help file for more information.");
			}
			else if (tmpMaxPages > 1000000)
			{
				alert("Depending on the content of the pages you are indexing, it is possible\nthat you may exceed the 32-bit address space limitations of your\n" +
					"system, rendering the resultant index files incomplete.\n\nWe would suggest lowering the limits specified or using the 64-bit\n" +
					"version of Zoom on a 64-bit system. See the Help file for more information.");			
			}			
		}
		else
		{
			if (tmpMaxPages > 20000000)
			{
				alert("Depending on the sizes of the pages indexed, it is possible that\nyou may exceed the practical limitations of your hardware. This may\n" +
					"render the resultant index files incomplete.\n\nNote that you should set the limits to a reasonable number that reflects\nthe content you intend to index.");
			}	
		}
	
		}	
		
		return 1;		
}
	
	function OpenHelp(section)
	{
		var address = "./help/index.html";
		
		if(section == "config_start")
		{
			address += "?zoom_search_engine_indexer.htm";
		}
		else if (section == "config_startpoints")
		{	
			address += "?start_spider_url.htm";	
		}
		else if (section == "config_scanoptions")
		{	
			address += "?scan_options.htm";	
		}
		else if (section == "config_skipptions")
		{	
			address += "?skip_options.htm";	
		}
		else if (section == "config_spiderptions")
		{	
			address += "?spider_options.htm";	
		}
		else if (section == "config_searchpage")
		{	
			address += "?search_page.htm";	
		}
		else if (section == "results_layout")
		{	
			address += "?results_layout.htm";	
		}
		else if (section == "indexing_options")
		{	
			address += "?indexing_options.htm";	
		}
		else if (section == "limits")
		{	
			address += "?limits.htm";	
		}	
		else if (section == "authentication")
		{	
			address += "?authentication.htm";	
		}	
		else if (section == "autocomplete")
		{	
			address += "?autocomplete.htm";	
		}	
		else if (section == "languages")
		{	
			address += "?languages.htm";	
		}	
		else if (section == "weightings")
		{	
			address += "?weightings.htm";	
		}	
		else if (section == "filtering")
		{	
			address += "?content_filtering.htm";	
		}	
		else if (section == "categories")
		{	
			address += "?categories.htm";	
		}	
		else if (section == "sitemaps")
		{	
			address += "?sitemaps.htm";	
		}	
		else if (section == "synonyms")
		{	
			address += "?synonyms.htm";	
		}	
		else if (section == "recommended")
		{	
			address += "?recommended_links.htm";	
		}	
		else if (section == "custommeta")
		{	
			address += "?custom_meta_search_fields.htm";	
		}
				else if (section == "indexlog")
		{	
			address += "?index_log.htm";	
		}
				else if (section == "advanced")
		{	
			address += "?advanced.htm";	
		}
			else if (section == "filetypes")
		{	
			address += "?configuring_a_plugin.htm";	
		}


		window.open(address, "_blank"); 						
	}
	
function GetBaseURL(spiderURL, bIsOfflinePath)
{	
	var len = 0;
	var slashChar = "";

	if (bIsOfflinePath == true)
			slashChar = '\\';
	else
			slashChar = '/';

	var slashPos =   spiderURL.lastIndexOf(slashChar); //strrchr(spiderURL, slashChar);

	if (slashPos != -1)
	{
		len = slashPos + 1;
	
		if (len < 8)
			len = spiderURL.length
	}
	else
		len = spiderURL.length;

	new_baseURL = spiderURL.substr(0, len);
	
	if (new_baseURL.charAt(new_baseURL.length-1) != slashChar)
	{
		new_baseURL += slashChar;
	}

	
	return new_baseURL;
}
	
	//Start point has changed, so udpate base field
	//Also update advanced start point list
	function UpdateBase()
	{
		var startURL = document.getElementsByName("config_starturl")[0].value;
		var baseURL = "";
		baseURL = GetBaseURL(startURL, false);
		document.getElementsByName("config_baseurl")[0].value = baseURL;
		
	//Update first item in advanced start point display list
		var displayList =	document.getElementsByName("start_points_display[]")[0];
		displayList.options[0].text = startURL;
		displayList.options[0].value = startURL;
		
		//Update hidden field
		if(document.getElementById("config_mode_spider").checked )
			document.getElementsByName("start_point[0]")[0].value  = startURL;	
		else
			document.getElementsByName("start_point_off[0]")[0].value  = startURL;	
	
	}			
	
	function UpdateSPBase()
	{
		var startURL = document.getElementsByName("config_sp_start")[0].value;
		var baseURL = "";
		baseURL = GetBaseURL(startURL, false);

		document.getElementsByName("config_sp_base")[0].value = baseURL;
	}			
	
		
	//Before form submission make sure all <select> options are selected so they are saved correctly
	function PreSubmit()
	{
		
		//Check limits 
		if(OnLeaveLimits() == 0)
			return 0;
			
		
		//Check last scan extension and file type updates are saved
		UpdateExtConfigure(true);
		UpdateFiletypeCfg(true);

	
		//Scan extensions
		var allItems = document.getElementsByName("config_scan_exts[]")[0];
		for(count = 0; count < allItems.length; count++)
		{
			allItems.options[count].selected = true;
		}
		
		//Categories
		
		allItems = document.getElementsByName("category_rules[]")[0];
		for(count = 0; count < allItems.length; count++)
		{
			allItems.options[count].selected = true;
		}
		
		//Synonyms
		allItems = document.getElementsByName("synonym_rules[]")[0];
		for(count = 0; count < allItems.length; count++)
		{
			allItems.options[count].selected = true;
		}
		
		//Recommended links
		allItems = document.getElementsByName("recommended_rules[]")[0];
		for(count = 0; count < allItems.length; count++)
		{
			allItems.options[count].selected = true;
		}
		
		//Custom meta fields
		allItems = document.getElementsByName("meta_rules[]")[0];
		for(count = 0; count < allItems.length; count++)
		{
			allItems.options[count].selected = true;
		}
		
		return 1;

	}
	
function isUrl(urlStr) 
{
	var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
	return regexp.test(urlStr);
}


function DetermineThumbnailURL()
{
	var tmpPathURL = "";

	var destURL = "";
	var bIsRootRelative = false;
	var thumbfilename = "";
	var thumbPathURL = 	document.getElementsByName("config_ext_conf_thumbfolder")[0].value;
	var thumbExt = 	document.getElementsByName("config_ext_conf_thumbext")[0].value;
	var prefix = document.getElementsByName("config_ext_conf_thumbbefore")[0].value; 
	var postfix = document.getElementsByName("config_ext_conf_thumbafter")[0].value;
	
	//Get currently selected extension
	var extItems = document.getElementsByName("config_scan_exts[]")[0];
	var selStr = extItems.options[extItems.selectedIndex].text;
	var tmpExt = selStr.split("|", 2); 
	var SelectedExt = tmpExt[0].trim();
				
	var exampleURL = "http://mysite.com/test/myfile" + SelectedExt;		
	var tmpBaseURL = "http://mysite.com/test/";		
		
	if (thumbPathURL.length == 0)
		tmpPathURL =  "./";	
	else	
		tmpPathURL = 	thumbPathURL;
		
	if (tmpPathURL.charAt(0) == "/")
		bIsRootRelative = true;
		
	//Ensure trailing slash
	if (tmpPathURL.charAt(tmpPathURL.length-1)  != "/")
		tmpPathURL = tmpPathURL + "/" 
	
	//if no thumb extenstion default to .jpg
	if(thumbExt.length < 1)
		thumbExt = ".jpg";
	
	var fname = exampleURL.slice(exampleURL.lastIndexOf("/") + 1, exampleURL.lastIndexOf("."));
	thumbfilename = prefix + fname + postfix + thumbExt;
		
	if(isUrl(tmpPathURL))
	{
			destURL = "";	

			if (tmpPathURL.charAt(tmpPathURL.length-1) != "/")
				tmpPathURL = tmpPathURL + "/";
			
			destURL = tmpPathURL + thumbfilename;
		
	}
	else if (bIsRootRelative)
	{
		// path URL starts with slash, so we will output final URL in the same form as a "relative" path
		// to the root directory (without http:// or base URL)
		destURL = tmpPathURL + thumbfilename;
	}
	else
	{								
		// if the base URL is a valid URL such as http://mysite.com/blah/ 
		if(isUrl(tmpBaseURL))
		{
			//Remove "." char at start, combine with base URL
			if(tmpPathURL.substr(0, 2) == "./")
				tmpPathURL=  tmpPathURL.slice(2);
			destURL = tmpBaseURL + tmpPathURL + thumbfilename; 
	
		}
		else
		{
			// tmpBaseURL is not a valid URL and is most likely a relative URL 
			// (eg. "../myCDROM/")	
			destURL = "";	
			if(tmpBaseURL.length > 0)
			{
				if (tmpBaseURL.charAt(tmpBaseURL.length-1) != "/")
					tmpBaseURL = tmpBaseURL + "/";
			
				destURL = destURL  + tmpBaseURL;
			}
			tmpPathURL = tmpPathURL + thumbfilename; 
			destURL = destURL + tmpPathURL;		
		}
	}			
	
	document.getElementsByName("config_ext_conf_thumbexurl")[0].value = exampleURL;		
	document.getElementsByName("config_ext_conf_thumbexloc")[0].value = destURL;			
		
}
		
	//Update filesize on limits page
	var LastFiletypeSelected = "";
	function UpdateFileSize(forceSave)
	{
		var max_file_size = parseInt(document.getElementsByName("config_limits_maxsize")[0].value);
		var EstFileMB = max_file_size / 1024;		
		document.getElementById("file_size_mb").innerHTML =  EstFileMB.toFixed(2) + " MB";
	}
	
	function UpdateFiletypeCfg(forceSave)
	{				
		<?PHP
		
		$FileTypeStrs = "";
		
		foreach ($FILETYPE_STRING as $nextType)
		{			
			$FileTypeStrs .= "\"$nextType\", ";
		}
		
			$FileTypeStrs .= "\"\"";
		
		echo "var FILETYPE_STRINGS = new Array($FileTypeStrs);"	
		?>
	
		//Need to change the the HTML displayed for the configure section depending on extension seelcted
		//Also need to save the previous selection and get values for the newley selected item
		var SelectedType = "";
	
		//Get currently selected file type - only first selected option when multi[ple are selected it used
		var CuItems = document.getElementsByName("config_filetypes[]")[0];		
		
		if(CuItems.selectedIndex < 0)
			return;
		
		SelectedType = CuItems.options[CuItems.selectedIndex].text;
		
		if(SelectedType.length == 0)
			return;
		
		//Check if they have changed selection, if so update hidden form elements so changes will be saved
		//or if forceSave flag is true (eg saving config)
		if((forceSave == true && LastFiletypeSelected.length > 0) || (LastFiletypeSelected.length > 0 && LastFiletypeSelected != SelectedType))
		{
				
			if(LastFiletypeSelected.search("Binary") != -1) //wouldn't work with full string, javascript issue? (FILETYPE_STRINGS[3]
			{
				
				if(document.getElementsByName("conf_binary_use_desc")[0].checked == true)
					document.getElementsByName("binary_use_desc")[0].value = 1;
				else
					document.getElementsByName("binary_use_desc")[0].value = 0;	
						
				if(document.getElementsByName("conf_binary_extract_strings")[0].checked == true)
					document.getElementsByName("binary_extract_strings")[0].value = 1;
				else
					document.getElementsByName("binary_extract_strings")[0].value = 0;			
													
			}
			else if(LastFiletypeSelected.search(FILETYPE_STRINGS[4]) != -1)
			{
				if(document.getElementsByName("config_ext_conf_doc_meta")[0].checked == true)
					document.getElementsByName("DocUseMeta")[0].value = 1;
				else
					document.getElementsByName("DocUseMeta")[0].value = 0;	
						
				if(document.getElementsByName("config_ext_conf_doc_desc")[0].checked == true)
					document.getElementsByName("DocUseDescFiles")[0].value = 1;
				else
					document.getElementsByName("DocUseDescFiles")[0].value = 0;			
			}
			else if(LastFiletypeSelected.search(FILETYPE_STRINGS[5]) != -1)
			{
				if(document.getElementsByName("config_ext_conf_pdf_meta")[0].checked == true)
					document.getElementsByName("PdfUseMeta")[0].value = 1;
				else
					document.getElementsByName("PdfUseMeta")[0].value = 0;	
						
				if(document.getElementsByName("config_ext_conf_pdf_desc")[0].checked == true)
					document.getElementsByName("PdfUseDescFiles")[0].value = 1;
				else
					document.getElementsByName("PdfUseDescFiles")[0].value = 0;			
									
				if(document.getElementsByName("config_ext_conf_pdf_highlight")[0].checked == true)
					document.getElementsByName("PdfHighlight")[0].value = 1;
				else
					document.getElementsByName("PdfHighlight")[0].value = 0;						
														
				if(document.getElementsByName("config_ext_conf_pdf_usepasswd")[0].checked == true)
					document.getElementsByName("PdfUsePassword")[0].value = 1;
				else
					document.getElementsByName("PdfUsePassword")[0].value = 0;	
				
				if(document.getElementsByName("config_ext_pdf_scan")[0].checked == true)
					document.getElementsByName("PdfToTextMethod")[0].value = 0;
				else if(document.getElementsByName("config_ext_pdf_scan")[1].checked == true)
						document.getElementsByName("PdfToTextMethod")[0].value = 1;
				else
						document.getElementsByName("PdfToTextMethod")[0].value = 2;
					
				document.getElementsByName("PdfPassword")[0].value = document.getElementsByName("config_ext_conf_pdf_passwd")[0].value;	
							
			}
			else if(LastFiletypeSelected.search(FILETYPE_STRINGS[6]) != -1)
			{
				if(document.getElementsByName("config_ext_conf_ppt_meta")[0].checked == true)
					document.getElementsByName("PptUseMeta")[0].value = 1;
				else
					document.getElementsByName("PptUseMeta")[0].value = 0;	
						
				if(document.getElementsByName("config_ext_conf_ppt_desc")[0].checked == true)
					document.getElementsByName("PptUseDescFiles")[0].value = 1;
				else
					document.getElementsByName("PptUseDescFiles")[0].value = 0;					
			}
			else if(LastFiletypeSelected.search(FILETYPE_STRINGS[7]) != -1)
			{
				if(document.getElementsByName("config_ext_conf_xls_desc")[0].checked == true)
					document.getElementsByName("XlsUseDescFiles")[0].value = 1;
				else
					document.getElementsByName("XlsUseDescFiles")[0].value = 0;	
			}
			else if(LastFiletypeSelected.search(FILETYPE_STRINGS[8]) != -1)
			{
				if(document.getElementsByName("config_ext_conf_wpd_desc")[0].checked == true)
					document.getElementsByName("WpdUseDescFiles")[0].value = 1;
				else
					document.getElementsByName("WpdUseDescFiles")[0].value = 0;	
			}
			else if(LastFiletypeSelected.search(FILETYPE_STRINGS[9]) != -1)
			{
				if(document.getElementsByName("config_ext_conf_swf_desc")[0].checked == true)
					document.getElementsByName("SwfUseDescFiles")[0].value = 1;
				else
					document.getElementsByName("SwfUseDescFiles")[0].value = 0;
			}
			else if(LastFiletypeSelected.search(FILETYPE_STRINGS[10]) != -1)
			{
				if(document.getElementsByName("config_ext_conf_rtf_desc")[0].checked == true)
					document.getElementsByName("RtfUseDescFiles")[0].value = 1;
				else
					document.getElementsByName("RtfUseDescFiles")[0].value = 0;		
			}	
			else if(LastFiletypeSelected.search(FILETYPE_STRINGS[11]) != -1)
			{
				if(document.getElementsByName("config_ext_conf_djvu_desc")[0].checked == true)
					document.getElementsByName("DjvuUseDescFiles")[0].value = 1;
				else
					document.getElementsByName("DjvuUseDescFiles")[0].value = 0;			
			}
			else if(LastFiletypeSelected.search(FILETYPE_STRINGS[12]) != -1)
			{
				if(document.getElementsByName("config_ext_conf_img_meta")[0].checked == true)
					document.getElementsByName("ImgUseMeta")[0].value = 1;
				else
					document.getElementsByName("ImgUseMeta")[0].value = 0;		
					
				if(document.getElementsByName("config_ext_conf_img_desc")[0].checked == true)
					document.getElementsByName("ImgUseDescFiles")[0].value = 1;
				else
					document.getElementsByName("ImgUseDescFiles")[0].value = 0;		
					
				if(document.getElementsByName("config_ext_conf_img_tech")[0].checked == true)
					document.getElementsByName("ImgUseTechnical")[0].value = 1;
				else
					document.getElementsByName("ImgUseTechnical")[0].value = 0;			
				
				document.getElementsByName("ImgMinFilesize")[0].value = document.getElementsByName("config_ext_conf_img_size")[0].value;
		}
			else if(LastFiletypeSelected.search(FILETYPE_STRINGS[13]) != -1)
			{
				if(document.getElementsByName("config_ext_conf_mp3_meta")[0].checked == true)
					document.getElementsByName("Mp3UseMeta")[0].value = 1;
				else
					document.getElementsByName("Mp3UseMeta")[0].value = 0;		
					
				if(document.getElementsByName("config_ext_conf_mp3_desc")[0].checked == true)
					document.getElementsByName("Mp3UseDescFiles")[0].value = 1;
				else
					document.getElementsByName("Mp3UseDescFiles")[0].value = 0;		
					
				if(document.getElementsByName("config_ext_conf_mp3_tech")[0].checked == true)
					document.getElementsByName("Mp3UseTechnical")[0].value = 1;
				else
					document.getElementsByName("Mp3UseTechnical")[0].value = 0;			
	
			}
			else if(LastFiletypeSelected.search(FILETYPE_STRINGS[14]) != -1)
			{				
				if(document.getElementsByName("config_ext_conf_dwf_meta")[0].checked == true)
					document.getElementsByName("DwfUseMeta")[0].value = 1;
				else
					document.getElementsByName("DwfUseMeta")[0].value = 0;		
					
				if(document.getElementsByName("config_ext_conf_dwf_desc")[0].checked == true)
					document.getElementsByName("DwfUseDescFiles")[0].value = 1;
				else
					document.getElementsByName("DwfUseDescFiles")[0].value = 0;		
					
				if(document.getElementsByName("config_ext_conf_dwf_tech")[0].checked == true)
					document.getElementsByName("DwfUseTechnical")[0].value = 1;
				else
					document.getElementsByName("DwfUseTechnical")[0].value = 0;			
			}
			else if(LastFiletypeSelected.search(FILETYPE_STRINGS[15]) != -1)
			{
				if(document.getElementsByName("config_ext_conf_doc_meta")[0].checked == true)
					document.getElementsByName("OfficeXmlUseMeta")[0].value = 1;
				else
					document.getElementsByName("OfficeXmlUseMeta")[0].value = 0;		
					
				if(document.getElementsByName("config_ext_conf_doc_desc")[0].checked == true)
					document.getElementsByName("OfficeXmlUseDescFiles")[0].value = 1;
				else
					document.getElementsByName("OfficeXmlUseDescFiles")[0].value = 0;		
					
				if(document.getElementsByName("config_ext_conf_doc_xls")[0].checked == true)
					document.getElementsByName("OfficeXmlTextOnly")[0].value = 1;
				else
					document.getElementsByName("OfficeXmlTextOnly")[0].value = 0;		
	
			}
			else if(LastFiletypeSelected.search(FILETYPE_STRINGS[16]) != -1)
			{
				if(document.getElementsByName("config_ext_conf_tor_desc")[0].checked == true)
					document.getElementsByName("TorrentUseDescFiles")[0].value = 1;
				else
					document.getElementsByName("TorrentUseDescFiles")[0].value = 0;		
			}
			else if(LastFiletypeSelected.search(FILETYPE_STRINGS[17]) != -1)
			{		
				if(document.getElementsByName("config_ext_conf_mht_desc")[0].checked == true)
					document.getElementsByName("MhtUseDescFiles")[0].value = 1;
				else
					document.getElementsByName("MhtUseDescFiles")[0].value = 0;		
			}
			else if(LastFiletypeSelected.search(FILETYPE_STRINGS[18]) != -1)
			{	
	
				if(document.getElementsByName("config_ext_conf_zip_desc")[0].checked == true)
					document.getElementsByName("ZipUseDescFiles")[0].value = 1;
				else
					document.getElementsByName("ZipUseDescFiles")[0].value = 0;
					
				if(document.getElementsByName("config_ext_zip_index")[0].checked == true)
						document.getElementsByName("ZipExtractFiles")[0].value = 1;
					else
						document.getElementsByName("ZipExtractFiles")[0].value = 0;
			}	
			
			
		}	
		
		LastFiletypeSelected = SelectedType;

		var ConfigureStr ="<fieldset><legend>" + SelectedType + "</legend>";

		//General (HTML, Plain Text, Unknown Text) - DLG_EXT_CONFIG_GENERAL 
		if(SelectedType.search(FILETYPE_STRINGS[0]) != -1 || SelectedType.search(FILETYPE_STRINGS[1]) != -1 || SelectedType.search(FILETYPE_STRINGS[2]) != -1)
		{
			ConfigureStr += "This file will be treated as a HTML or text file.\n\nYou can configure other indexing options for this file type via the main Configuration window.";
		}
		else if(SelectedType.search("Binary") != -1) //wouldn't work with full string, javascript issue? (FILETYPE_STRINGS[3]
		{
			//Doc indexing options - DLG_EXT_CONFIG_DOC
			var BinUseDesc = "";
			if(document.getElementsByName("binary_use_desc")[0].value != 0)
				BinUseDesc = "checked=\"checked\"";
			var BinExtract = "";
			if(document.getElementsByName("binary_extract_strings")[0].value != 0)
				BinExtract = "checked=\"checked\"";
								
			ConfigureStr += "<table><tr><td><input type=\"checkbox\" name=\"conf_binary_use_desc\" value=\"1\" "+ BinUseDesc + "> Use description (.desc) files</td></tr>";
			ConfigureStr += "<tr><td><input type=\"checkbox\" name=\"conf_binary_extract_strings\" value=\"1\" "+ BinExtract + "> Extract recognizable text from binary file</td></tr></table>";
		
		}
		else if(SelectedType.search(FILETYPE_STRINGS[4]) != -1)
		{
			//Doc indexing options - DLG_EXT_CONFIG_DOC
			var DocUseMeta = "";
			if(document.getElementsByName("DocUseMeta")[0].value != 0)
				DocUseMeta = "checked=\"checked\"";
			var DocUseDescFiles = "";
			if(document.getElementsByName("DocUseDescFiles")[0].value != 0)
				DocUseDescFiles = "checked=\"checked\"";
				
			ConfigureStr += "<table><tr><td><input type=\"checkbox\" name=\"config_ext_conf_doc_meta\" value=\"1\" "+ DocUseMeta + "> Retrieve internal meta information</td></tr>";
			ConfigureStr += "<tr><td><input type=\"checkbox\" name=\"config_ext_conf_doc_desc\" value=\"1\" "+ DocUseDescFiles + "> Use description (.desc) files</td></tr></table>";
		}
		else if(SelectedType.search(FILETYPE_STRINGS[5]) != -1)
		{		
			var PdfUseMeta = "";
			if(document.getElementsByName("PdfUseMeta")[0].value != 0)
				PdfUseMeta = "checked=\"checked\"";
			var PdfUseDescFiles = "";
			if(document.getElementsByName("PdfUseDescFiles")[0].value != 0)
				PdfUseDescFiles = "checked=\"checked\"";
			var PdfUsePassword = "";	
			if(document.getElementsByName("PdfUsePassword")[0].value != 0)
				PdfUsePassword = "checked=\"checked\"";
			
			var scanPresent = ""
			var scanRaw = "";
			var scanLayer = "";
			
			if(document.getElementsByName("PdfToTextMethod")[0].value == 2)
				scanLayer = "checked=\"checked\"";
			else 	if(document.getElementsByName("PdfToTextMethod")[0].value == 1)
				scanRaw = "checked=\"checked\"";
			else
				scanPresent = "checked=\"checked\"";		

			var PdfPassword = document.getElementsByName("PdfPassword")[0].value; 
			
			var PdfHighlight = "" ;
			if(document.getElementsByName("PdfHighlight")[0].value != 0)
				PdfHighlight = "checked=\"checked\"";	
		
		
			//PDF indexing options - DLG_EXT_CONFIG_PDF
			ConfigureStr += "<input type=\"checkbox\" name=\"config_ext_conf_pdf_meta\" value=\"1\" " + PdfUseMeta + ">Retrieve internal meta information<br>\n";
			ConfigureStr += "<input type=\"checkbox\" name=\"config_ext_conf_pdf_desc\" value=\"1\" "+ PdfUseDescFiles + ">Use description (.desc) files<br>\n";
			ConfigureStr += "<fieldset><legend>Scan Method</legend><input type=\"radio\" name=\"config_ext_pdf_scan\" value=\"0\" " + scanPresent + " />Scan text by presentation layout (default)<br><input type=\"radio\" name=\"config_ext_pdf_scan\" value=\"1\" " + scanRaw + " />Scan text in raw formatting order<br><input type=\"radio\" name=\"config_ext_pdf_scan\" value=\"2\" " + scanLayer + " />Scan text by text layer<br></fieldset>\n";
			ConfigureStr += "<fieldset><legend>Highlighting</legend><input type=\"checkbox\" name=\"config_ext_conf_pdf_highlight\" value=\"1\" "+ PdfHighlight + ">Highlight and locate matched words within PDF document viewer (Acrobat Reader 7.0 or later only)</fieldset>\n";
			ConfigureStr += "<fieldset><legend>Security Options</legend><input type=\"checkbox\" name=\"config_ext_conf_pdf_usepasswd\" value=\"1\" " + PdfUsePassword + ">Use following password to decrypt and index protected PDF files:<br><input type=\"text\" name=\"config_ext_conf_pdf_passwd\" value=\""+ PdfPassword + "\" /></fieldset>\n";

		}
		else if(SelectedType.search(FILETYPE_STRINGS[6]) != -1)
		{				
			//PPT indexing options  - DLG_EXT_CONFIG_PPT
			var PptUseMeta = "";
			if(document.getElementsByName("PptUseMeta")[0].value != 0)
				PptUseMeta = "checked=\"checked\"";
			var PptUseDescFiles = "";
			if(document.getElementsByName("PptUseDescFiles")[0].value != 0)
				PptUseDescFiles = "checked=\"checked\"";

			ConfigureStr += "<input type=\"checkbox\" name=\"config_ext_conf_ppt_meta\" value=\"1\" " + PptUseMeta + ">Retrieve internal meta information<br>\n";
			ConfigureStr += "<input type=\"checkbox\" name=\"config_ext_conf_ppt_desc\" value=\"1\" " + PptUseDescFiles + ">Use description (.desc) files";
		}
		else if(SelectedType.search(FILETYPE_STRINGS[7]) != -1)
		{	
			//XLS indexing options - DLG_EXT_CONFIG_XLS		
			//	var $XlsUseMeta = 0; - No XLS meta info?
			var XlsUseDescFiles = "";
			if(document.getElementsByName("XlsUseDescFiles")[0].value != 0)
				XlsUseDescFiles = "checked=\"checked\"";
			
			ConfigureStr += "<input type=\"checkbox\" name=\"config_ext_conf_xls_desc\" value=\"1\" " + XlsUseDescFiles + ">Use description (.desc) files\n";
		}
		else if(SelectedType.search(FILETYPE_STRINGS[8]) != -1)
		{
			//WPD indexing options - DLG_EXT_CONFIG_WPD
			//var $WpdUseMeta = 0; - now WPD meta?
			var WpdUseDescFiles = "";
			if(document.getElementsByName("WpdUseDescFiles")[0].value != 0)
				WpdUseDescFiles = "checked=\"checked\"";
	
			ConfigureStr += "<input type=\"checkbox\" name=\"config_ext_conf_wpd_desc\" value=\"1\" "+ WpdUseDescFiles + ">Use description (.desc) files\n";
		}
		else if(SelectedType.search(FILETYPE_STRINGS[9]) != -1)
		{
			//SWF indexing options - DLG_EXT_CONFIG_SWF
			//var $SwfUseMeta = 0;
			var SwfUseDescFiles = "";
			if(document.getElementsByName("SwfUseDescFiles")[0].value != 0)
				SwfUseDescFiles = "checked=\"checked\"";
	
			ConfigureStr += "<input type=\"checkbox\" name=\"config_ext_conf_swf_desc\" value=\"1\" " + SwfUseDescFiles + ">Use description (.desc) files\n";
		}
		else if(SelectedType.search(FILETYPE_STRINGS[10]) != -1)
		{
			//RTF indexing options - DLG_EXT_CONFIG_RTF
			//var $RtfUseMeta = 0;
			var RtfUseDescFiles = "";
			if(document.getElementsByName("RtfUseDescFiles")[0].value != 0)
				RtfUseDescFiles = "checked=\"checked\"";
	
			ConfigureStr += "<input type=\"checkbox\" name=\"config_ext_conf_rtf_desc\" value=\"1\" " + RtfUseDescFiles + ">Use description (.desc) files\n";
		}
		else if(SelectedType.search(FILETYPE_STRINGS[11]) != -1)
		{
			//DjVu indexing options - 
			//	var $DjvuUseMeta = 0;
			var DjvuUseDescFiles = "";
			if(document.getElementsByName("DjvuUseDescFiles")[0].value != 0)
				DjvuUseDescFiles = "checked=\"checked\"";
	
			ConfigureStr += "<input type=\"checkbox\" name=\"config_ext_conf_djvu_desc\" value=\"1\" " + DjvuUseDescFiles + ">Use description (.desc) files\n";
		}
		else if(SelectedType.search(FILETYPE_STRINGS[12]) != -1)
		{
			//Image indexing options - DLG_EXT_CONFIG_IMAGE
			var ImgUseMeta = "";
			if(document.getElementsByName("ImgUseMeta")[0].value != 0)
				ImgUseMeta = "checked=\"checked\"";
				
			var ImgUseDescFiles = "";
			if(document.getElementsByName("ImgUseDescFiles")[0].value != 0)
				ImgUseDescFiles = "checked=\"checked\"";
					
			var ImgUseTechnical = "";
			if(document.getElementsByName("ImgUseTechnical")[0].value != 0)
				ImgUseTechnical = "checked=\"checked\"";
						
			var ImgMinFilesize = document.getElementsByName("ImgMinFilesize")[0].value;	
			
			ConfigureStr += "<table><tr><td><input type=\"checkbox\" name=\"config_ext_conf_img_meta\" value=\"1\"" + ImgUseMeta + ">Retrieve internal meta information<br>\n";
			ConfigureStr += "<input type=\"checkbox\" name=\"config_ext_conf_img_desc\" value=\"1\"" + ImgUseDescFiles + ">Use description (.desc) files<br>\n";
			ConfigureStr += "<input type=\"checkbox\" name=\"config_ext_conf_img_tech\" value=\"1\"" + ImgUseTechnical + ">Retrieve technical data (camera type, etc.) when available<br>\n";
			ConfigureStr += "Only index images larger than <input type=\"text\" name=\"config_ext_conf_img_size\" size=\"5\" value=\"" + ImgMinFilesize + "\" /> kilobytes\n";	
		}
		else if(SelectedType.search(FILETYPE_STRINGS[13]) != -1)
		{	
			//Mp3 indexing options - DLG_EXT_CONFIG_MP3
			var Mp3UseMeta = "";
			if(document.getElementsByName("Mp3UseMeta")[0].value != 0)
				Mp3UseMeta = "checked=\"checked\"";
				
			var Mp3UseDescFiles = "";
			if(document.getElementsByName("Mp3UseDescFiles")[0].value != 0)
				Mp3UseDescFiles = "checked=\"checked\"";
					
			var Mp3UseTechnical = "";
			if(document.getElementsByName("Mp3UseTechnical")[0].value != 0)
				Mp3UseTechnical = "checked=\"checked\"";
			
			ConfigureStr += "<input type=\"checkbox\" name=\"config_ext_conf_mp3_meta\" value=\"1\"" + Mp3UseMeta + ">Retrieve internal meta information (artist, etc.)<br>\n";
			ConfigureStr += "<input type=\"checkbox\" name=\"config_ext_conf_mp3_desc\" value=\"1\"" + Mp3UseDescFiles + ">Use description (.desc) files<br>\n";
			ConfigureStr += "<input type=\"checkbox\" name=\"config_ext_conf_mp3_tech\" value=\"1\"" + Mp3UseTechnical + ">Retrieve technical data (bitrate, duration, etc.) when available<br>\n";
		}
		else if(SelectedType.search(FILETYPE_STRINGS[14]) != -1)
		{	
			//DWF indexing options - DLG_EXT_CONFIG_DWF
			var DwfUseMeta = "";
			if(document.getElementsByName("DwfUseMeta")[0].value != 0)
				DwfUseMeta = "checked=\"checked\"";
 			var DwfUseDescFiles = "";
			if(document.getElementsByName("DwfUseDescFiles")[0].value != 0)
				DwfUseDescFiles = "checked=\"checked\"";
			var DwfUseTechnical = "";
			if(document.getElementsByName("DwfUseTechnical")[0].value != 0)
				DwfUseTechnical = "checked=\"checked\"";
		
			ConfigureStr += "<input type=\"checkbox\" name=\"config_ext_conf_dwf_meta\" value=\"1\" " + DwfUseMeta + ">Retrieve internal meta information (title, author, etc.)<br>\n";
		
			ConfigureStr += "<input type=\"checkbox\" name=\"config_ext_conf_dwf_desc\" value=\"1\" " + DwfUseDescFiles + ">Use description (.desc) files<br>\n";

			ConfigureStr += "<input type=\"checkbox\" name=\"config_ext_conf_dwf_tech\" value=\"1\" " + DwfUseDescFiles + ">Retrieve technical data (sheet attributes, dimensions, etc.) when available<br>\n";
		}
		else if(SelectedType.search(FILETYPE_STRINGS[15]) != -1)
		{	
			//Office 2007 Indexing options - DLG_EXT_CONFIG_OFFICEXML
			var OfficeXmlUseMeta = "";
			if(document.getElementsByName("OfficeXmlUseMeta")[0].value != 0)
				OfficeXmlUseMeta = "checked=\"checked\"";
			var OfficeXmlUseDescFiles = "";
			if(document.getElementsByName("OfficeXmlUseDescFiles")[0].value != 0)
				OfficeXmlUseDescFiles = "checked=\"checked\"";
			var OfficeXmlTextOnly = "";
			if(document.getElementsByName("OfficeXmlTextOnly")[0].value != 0)
				OfficeXmlTextOnly = "checked=\"checked\"";
				
			ConfigureStr += "<input type=\"checkbox\" name=\"config_ext_conf_doc_meta\" value=\"1\" " + OfficeXmlUseMeta + ">Retrieve internal meta information<br>\n";
			ConfigureStr += "<input type=\"checkbox\" name=\"config_ext_conf_doc_desc\" value=\"1\" "+ OfficeXmlUseDescFiles + ">Use description (.desc) files<br>\n";
			ConfigureStr += "<input type=\"checkbox\" name=\"config_ext_conf_doc_xls\" value=\"1\" "+ OfficeXmlTextOnly + ">Index text only from Excel spreadsheets when available<br>\n";
		}	
		else if(SelectedType.search(FILETYPE_STRINGS[16]) != -1)
		{
			//Torrent indexing options  -DLG_EXT_CONFIG_TORRENT
			var TorrentUseDescFiles = "";
			if(document.getElementsByName("TorrentUseDescFiles")[0].value != 0)
				TorrentUseDescFiles = "checked=\"checked\"";
			ConfigureStr += "<input type=\"checkbox\" name=\"config_ext_conf_tor_desc\" value=\"1\" " + TorrentUseDescFiles + ">Use description (.desc) files<br>\n";
		}
		else if(SelectedType.search(FILETYPE_STRINGS[17]) != -1)
		{
			//MHT indexing options - DLG_EXT_CONFIG_MHT
			var MhtUseDescFiles = "";
			if(document.getElementsByName("MhtUseDescFiles")[0].value != 0)
				MhtUseDescFiles = "checked=\"checked\"";
			ConfigureStr += "<input type=\"checkbox\" name=\"config_ext_conf_mht_desc\" value=\"1\" "+ MhtUseDescFiles + ">Use description (.desc) files<br>\n";
		}
		else if(SelectedType.search(FILETYPE_STRINGS[18]) != -1)
		{		
			
			//ZIP file options - DLG_EXT_CONFIG_ZIP
			var ZipUseDescFiles = "";
			if(document.getElementsByName("ZipUseDescFiles")[0].value != 0)
				ZipUseDescFiles = "checked=\"checked\"";
			var ZipExtractFiles = "";
			var IndexNameOnly = "";
			if(document.getElementsByName("ZipExtractFiles")[0].value != 0)
				ZipExtractFiles = "checked=\"checked\"";
			else
				IndexNameOnly = "checked=\"checked\"";
	
			ConfigureStr += "<input type=\"checkbox\" name=\"config_ext_conf_zip_desc\" value=\"1\" " + ZipUseDescFiles + ">Use description (.desc) files<br>\n";	
			ConfigureStr += "<fieldset><legend>Index Method</legend><input type=\"radio\" name=\"config_ext_zip_index\" value=\"0\" " + ZipExtractFiles + "/>Extract and index all files inside ZIP archive<br>\n";	
			ConfigureStr += "<input type=\"radio\" name=\"config_ext_zip_index\" value=\"1\" " + IndexNameOnly + "/>Only index filename of ZIP archive</fieldset>\n";
		}
						
		ConfigureStr	+= "</fieldset>"; 
		document.getElementById("filetype_configure").innerHTML	= ConfigureStr;	
		
	}
	
	
	var LastExtSelected = "";
	function UpdateExtConfigure(forceSave)
	{				
		<?PHP
		
		$FileTypeStrs = "";
		
		foreach ($FILETYPE_STRING as $nextType)
		{			
			$FileTypeStrs .= "\"$nextType\", ";
		}
		
			$FileTypeStrs .= "\"\"";
		
		echo "var FILETYPE_STRINGS = new Array($FileTypeStrs);"	
		?>
		
			
		//Need to change the the HTML displayed for the configure section depending on extension seelcted
		var ConfigureStr="";
		var SelectedType = "";
		var SelectedExt= "";
		var name = "";
		
		//Get currently selected file type - only first selected option when multi[ple are selected it used
		//(multi select used for delete)
		var CuItems = document.getElementsByName("config_scan_exts[]")[0];
		for(count = 0; count < CuItems.length; count++)
		{
			if(CuItems.options[count].selected)
			{
				var selStr = CuItems.options[count].text;
				var curExt = selStr.split("|", 2); 
				SelectedExt = curExt[0].trim();
				SelectedType = curExt[1].trim();
				break;
			}
		}
			
		if(SelectedExt.length == 0)
			return;
		
		//Check if they have changed selection, if so update hidden form elements so changes will be saved
		//or if forceSave flag is true (eg saving config)
		if((forceSave == true &&  LastExtSelected.length > 0) || (LastExtSelected.length > 0 && LastExtSelected != SelectedExt))
		{
			name = "ImageURL[" + LastExtSelected + "]";		
	 		document.getElementsByName(name)[0].value = 	document.getElementsByName("config_ext_conf_iconurl")[0].value;
				
			name = "UseThumbs["  + LastExtSelected + "]";
			if(	document.getElementsByName("config_ext_conf_imageopt")[0].checked == true) 
			 document.getElementsByName(name)[0].value = 0;		
			else if(document.getElementsByName("config_ext_conf_imageopt")[1].checked == true) 	
				document.getElementsByName(name)[0].value = 1;		
			else
			 	document.getElementsByName(name)[0].value = 2;		
				
			name = "ThumbsPath["  + LastExtSelected + "]";
			document.getElementsByName(name)[0].value = document.getElementsByName("config_ext_conf_thumbfolder")[0].value;
						
			name = "ThumbsFilenamePrefix["  + LastExtSelected + "]";
			document.getElementsByName(name)[0].value = document.getElementsByName("config_ext_conf_thumbbefore")[0].value;
					
			name = "ThumbsFilenamePostfix["  + LastExtSelected + "]";
			document.getElementsByName(name)[0].value = document.getElementsByName("config_ext_conf_thumbafter")[0].value;
	
			name = "ThumbsExt["  + LastExtSelected + "]";
			document.getElementsByName(name)[0].value = document.getElementsByName("config_ext_conf_thumbext")[0].value;
		}
		
		//Load thumbnail/image info for file extension
		name = "ImageURL[" + SelectedExt + "]";		
		document.getElementsByName("config_ext_conf_iconurl")[0].value  = document.getElementsByName(name)[0].value;
				
		name = "UseThumbs["  + SelectedExt + "]";
		var imgOption = document.getElementsByName(name)[0].value;
		
		//Make sure disabled/enabled elements are enforced
		ScanExtImageOptChange(imgOption);
		
		if(imgOption == 0) 	
			document.getElementsByName("config_ext_conf_imageopt")[0].checked = true;
		else if(imgOption == 1) 	
			document.getElementsByName("config_ext_conf_imageopt")[1].checked = true;
		else
			document.getElementsByName("config_ext_conf_imageopt")[2].checked = true;
			
		name = "ThumbsPath["  + SelectedExt + "]";
		document.getElementsByName("config_ext_conf_thumbfolder")[0].value  = document.getElementsByName(name)[0].value;

		name = "ThumbsFilenamePrefix["  + SelectedExt + "]";
		document.getElementsByName("config_ext_conf_thumbbefore")[0].value  = document.getElementsByName(name)[0].value;
				
		name = "ThumbsFilenamePostfix["  + SelectedExt + "]";
		document.getElementsByName("config_ext_conf_thumbafter")[0].value  = document.getElementsByName(name)[0].value;

		name = "ThumbsExt["  + SelectedExt + "]";
		document.getElementsByName("config_ext_conf_thumbext")[0].value  = document.getElementsByName(name)[0].value;
	

		//Update last selected
		LastExtSelected = SelectedExt;

	}
	
	
	//Update Estimate RAM usages
	function UpdateRAMUse()
	{
		//Use PHP defines for values
			<?PHP

		echo "var MAXSKIPPAGES = $MAXSKIPPAGES;\n";
		echo "var WORDLENGTH = $WORDLENGTH;\n";
		echo "var MAXSKIPWORDS = $MAXSKIPWORDS;\n";
		echo "var DICT_ELEM_SIZE = $DICT_ELEM_SIZE;\n";
		echo "var MAXSKIPPAGES = $MAXSKIPPAGES;\n";
		echo "var MAX_PATH = $MAX_PATH;\n";
		echo "var DICT_ELEM_HEADER_SIZE = $DICT_ELEM_HEADER_SIZE;\n";
		echo "var DICTIONARY_AVG_WORDLENGTH = $DICTIONARY_AVG_WORDLENGTH;\n";
		echo "var VARIANT_ELEM_HEADER_SIZE = $VARIANT_ELEM_HEADER_SIZE;\n";
		echo "var PAGES_ARRAY_SIZE = $PAGES_ARRAY_SIZE;\n";
		echo "var WORDMAP_ROW_SIZE = $WORDMAP_ROW_SIZE;\n";
		echo "var WORDMAP_HEADER_SIZE = $WORDMAP_HEADER_SIZE;\n";
		echo "var TITLELEN = $TITLELEN;\n";
		echo "var LOGLINELENGTH = $LOGLINELENGTH;\n";
		echo "var LOG_NUM_LINES = $LOG_NUM_LINES;\n";
		echo "var ROBOTS_TXT_MAX_FILE_SIZE = $ROBOTS_TXT_MAX_FILE_SIZE;\n";
		echo "var URL_ELEM = $URL_ELEM;\n";
		echo "var HASHHISTORY_TYPE_SIZE = $HASHHISTORY_TYPE_SIZE;\n";
		echo "var HASHHISTORY_OVERALLOCATE = $HASHHISTORY_OVERALLOCATE;\n";
		echo "var FLUSH_PER_PAGELIMIT = $FLUSH_PER_PAGELIMIT;\n";
		echo "var OUTPUT_CGI = $OUTPUT_CGI;\n";
		echo "var EST_AVG_WORDMAP_BYTES_PER_PAGE = $EST_AVG_WORDMAP_BYTES_PER_PAGE;\n";
		echo "var EST_AVG_BIG_WORDMAP_BYTES_PER_PAGE = $EST_AVG_BIG_WORDMAP_BYTES_PER_PAGE;\n";	
		echo "var MAX_ACCENT_CHAR_LENGTH = $MAX_ACCENT_CHAR_LENGTH;\n";	
		echo "var NUM_TOTAL_ACCENT_CHARS = $NUM_TOTAL_ACCENT_CHARS;\n";
		echo "var EST_AVG_BIG_WORDMAP_BYTES_PER_PAGE_AFTERFLUSH = $EST_AVG_BIG_WORDMAP_BYTES_PER_PAGE_AFTERFLUSH;\n";
	
			?>
						
		var max_pages = parseInt(document.getElementsByName("config_limits_maxfiles")[0].value);
		var max_words =  parseInt(document.getElementsByName("config_limits_unique")[0].value);
		var max_file_size = parseInt(document.getElementsByName("config_limits_maxsize")[0].value) * 1024; 
		var max_desc_length = parseInt(document.getElementsByName("config_limits_maxdescription")[0].value); 
		var EstMemoryStr = "";
		
		var EstMemory = (MAXSKIPPAGES * MAX_PATH) * 2;
		EstMemory += (WORDLENGTH * MAXSKIPWORDS) * 2;
		EstMemory += DICT_ELEM_SIZE * ((1.5 * max_words));
		EstMemory += DICT_ELEM_SIZE * ((1.5 * max_words));
		EstMemory += max_words * (DICT_ELEM_HEADER_SIZE + ((DICTIONARY_AVG_WORDLENGTH+1) * 2));
		EstMemory += max_words * (VARIANT_ELEM_HEADER_SIZE + ((DICTIONARY_AVG_WORDLENGTH+1) * 2)) + PAGES_ARRAY_SIZE;	
		EstMemory += ((WORDMAP_HEADER_SIZE+WORDMAP_ROW_SIZE) * max_words) +	((TITLELEN+1) * 2);
		EstMemory += ((max_desc_length+1) * 2);
		EstMemory += max_file_size  +	(max_file_size * 2);
		EstMemory += (LOGLINELENGTH * LOG_NUM_LINES);
			
		// for the robotsTxtBuffer: we switch behaviour depending on how big the max file size is
		if (max_file_size > ROBOTS_TXT_MAX_FILE_SIZE)
			EstMemory += ROBOTS_TXT_MAX_FILE_SIZE;
		else
			EstMemory += max_file_size;
					
		
		if (document.getElementById("config_mode_spider").checked == true)
		{
			// truncate url_list to an average (since it is a queue now and items get pushed as they're index)
			EstMemory += URL_ELEM * (max_pages / 2);	// maximum size of url_list[]		
			
			
			var numThreads = parseInt(document.getElementsByName("config_numdlthreads")[0].value);
			if(numThreads < 1)
				numThreads = 1;
			if(numThreads > 10)
				numThreads = 10;
			
			// Memory used for Buffers in the BufferQueue		
			EstMemory += (max_file_size+1) * (numThreads + 2);	// max_file_size is specified in bytes, not number of TCHARs.
	
			// URL History
			EstMemory +=  HASHHISTORY_TYPE_SIZE * (max_pages * HASHHISTORY_OVERALLOCATE);
		}
				
		// Estimate memory used for wordmap
		// This is difficult to estimate accurately, since the wordmap can be realloc'ed as we index
		var BeforeFlushRatio = 0;
		
		var OutputFormat = 0;
				
		for (i = 0; i < document.getElementsByName('config_platform').length; i++) 
		{
          if (document.getElementsByName('config_platform')[i].checked) 
          {
          	OutputFormat = document.getElementsByName('config_platform')[i].value;
          }
     }
		
		
		if (max_pages > FLUSH_PER_PAGELIMIT)
			BeforeFlushRatio = (max_words / FLUSH_PER_PAGELIMIT);
		else
			BeforeFlushRatio = (max_words / max_pages);			
		BeforeFlushRatio = Math.log(BeforeFlushRatio) / Math.log(2);
		BeforeFlushRatio = Math.max(BeforeFlushRatio, 1);
	
		if (OutputFormat == OUTPUT_CGI)
			BeforeFlushRatio *= EST_AVG_BIG_WORDMAP_BYTES_PER_PAGE;
		else
			BeforeFlushRatio *= EST_AVG_WORDMAP_BYTES_PER_PAGE;
			
		// Calculate the est. memory for the portion before the flush and portion after flush
		if (max_pages > FLUSH_PER_PAGELIMIT)
		{
			var AfterFlushRatio = 0;
			AfterFlushRatio = (max_words / (max_pages-FLUSH_PER_PAGELIMIT));
			AfterFlushRatio = Math.log(AfterFlushRatio) / Math.log(2);
			AfterFlushRatio = Math.max(AfterFlushRatio, 1);		
			AfterFlushRatio *= EST_AVG_BIG_WORDMAP_BYTES_PER_PAGE_AFTERFLUSH;
	
			EstMemory +=  (BeforeFlushRatio * FLUSH_PER_PAGELIMIT);
			EstMemory +=  (AfterFlushRatio * (max_pages-FLUSH_PER_PAGELIMIT));
		}
		else
			EstMemory +=  (BeforeFlushRatio * max_pages);
			
		EstMemory += 4 * 256;	// CRC lookup table
		
		var CRC32 = parseInt(document.getElementsByName("config_scan_duplicate")[0].value);
		if (CRC32)
		{		
			EstMemory += (HASHHISTORY_TYPE_SIZE * (max_pages * HASHHISTORY_OVERALLOCATE));
		}
		
		var UseCats = parseInt(document.getElementsByName("config_categories_enable")[0].value);
		if (UseCats)
		{
			EstMemory += 4 * max_pages;		
		}
	
		var MapAccents = parseInt(document.getElementsByName("config_language_acdc")[0].value);
		if (MapAccents)
		{
			EstMemory +=	2 * (2 * MAX_ACCENT_CHAR_LENGTH * NUM_TOTAL_ACCENT_CHARS);		// AccentChars + NormalChars
		}
		
		//Ouput the calculation
		EstMemory /= 1024;	// Estimated memory in kilobytes
		var EstMemoryMB = EstMemory / 1024;
		
		if (EstMemoryMB < 1024) // less than 1 GB
		{
			EstMemoryStr = "<p>Estimated RAM required: " + EstMemory.toFixed(0) + " KB (" + EstMemoryMB.toFixed(2) + " MB)";
		}
		else
		{
			// convert EstMemoryMB to GB
			EstMemoryMB = EstMemoryMB / 1024;
			EstMemoryStr = "<p>Estimated RAM required: " + EstMemoryMB.toFixed(2) + " GB";
		}
	
		document.getElementById("optimization_ramest").innerHTML	= EstMemoryStr;	
		
	}
	
	//If removeAll is true prompt with a confirmation message then remove all
	function RemoveMeta(removeAll)
	{
		if(removeAll == true)
		{
			if(confirm("Really remove all custom meta entries?") == false)
			{
				return;
			}
		}
		
		var selectedItems = document.getElementsByName("meta_rules[]")[0];
		for(count = selectedItems.length-1; count > -1; count--)
		{
			if(selectedItems.options[count].selected || removeAll == true)
			{				
					selectedItems.remove(count);
			}
		}
		
	}
	
	
		//If removeAll is true prompt with a confirmation message then remove all
	function RemoveRecommended(removeAll)
	{
		if(removeAll == true)
		{
			if(confirm("Really remove all recommended entries?") == false)
			{
				return;
			}
		}
		
		var selectedItems = document.getElementsByName("recommended_rules[]")[0];
		for(count = selectedItems.length-1; count > -1; count--)
		{
			if(selectedItems.options[count].selected || removeAll == true)
			{				
					selectedItems.remove(count);
			}
		}
		
	}
	
	//If removeAll is true prompt with a confirmation message then remove all
	function RemoveSynonyms(removeAll)
	{
		if(removeAll == true)
		{
			if(confirm("Really remove all Synonym entries?") == false)
			{
				return;
			}
		}

		var selectedItems = document.getElementsByName("synonym_rules[]")[0];
		for(count = selectedItems.length-1; count > -1; count--)
		{
			if(selectedItems.options[count].selected || removeAll == true)
			{
					selectedItems.remove(count);
			}
		}
		
		
	}
	
		//If removeAll is true prompt with a confirmation message then remove all
	function RemoveStartPoints(removeAll)
	{
		
		if(removeAll == true)
		{
			if(confirm("Really remove all start point entries?") == false)
			{
				return;
			}
						
		}

		var selectedItems = document.getElementsByName("start_points_display[]")[0];
		//Stop at 0, can't remove original (default) start point
		for(count = selectedItems.length-1; count > 0; count--)
		{
			if(selectedItems.options[count].selected || removeAll == true)
			{
					//Also need to delete hidden fields 
					var itemName ="";
					if (document.getElementById("config_mode_spider").checked == true)
						itemName = "start_points[" + selectedItems.options[count].value + "]";
					else
						itemName = "start_points_off[" + selectedItems.options[count].value + "]";	
		
					document.getElementById("mainform").removeChild(document.getElementsByName(itemName)[0]);
					
					//Also remove hidden list entry					
					if (document.getElementById("config_mode_spider").checked == true)
					{
						var hiddenList = document.getElementsByName("start_points_list");
						for(entry = 0; entry < hiddenList.length; entry++)
							if(hiddenList[entry].value == selectedItems.options[count].value)
									document.getElementById("mainform").removeChild(hiddenList[entry]);
					}
					else
					{
						var hiddenList = document.getElementsByName("start_points_off_list");
							for(entry = 0; entry < hiddenList.length; entry++)
								if(hiddenList[entry].value == selectedItems.options[count].value)
									document.getElementById("mainform").removeChild(hiddenList[entry]);
					}
				
					selectedItems.remove(count);		
			}
		}
		
		if(removeAll != true && selectedItems.options[0].selected)
			alert("Cannot remove main start point");
		
	}
	
	
		//If removeAll is true prompt with a confirmation message then remove all
	function RemoveCats(removeAll)
	{
		if(removeAll == true)
		{
			if(confirm("Really remove all category entries?") == false)
			{
				return;
			}
		}

		var selectedItems = document.getElementsByName("category_rules[]")[0];
		for(count = selectedItems.length-1; count > -1; count--)
		{
			if(selectedItems.options[count].selected || removeAll == true)
			{
					selectedItems.remove(count);
			}
		}
		
		
	}
	
	//Enable or disable the dropdown values field for cusotm met field edit
	function CheckIfDDVEnabled()
	{
		if(document.getElementsByName("config_meta_type")[0].value == 2)
		{
			document.getElementsByName("config_meta_ddvalues")[0].disabled = false;
		}
		else
		{
				document.getElementsByName("config_meta_ddvalues")[0].disabled = true;
		}
				
	}
	
	//Handle Add and Update buttons for synonyms, if update is true then update currently selected
	function AddEditMeta(update)
	{		
		<?PHP
		
		$MetaTypeStrs = "";
		$MetaSearchStrs = "";
		
		foreach ($METAFIELD_STRINGS as $nextType)
		{			
			$MetaTypeStrs .= "\"$nextType\", ";
		}
		
		foreach ($METAFIELD_SEARCH_STRINGS as $nextSearch)
		{			
			$MetaSearchStrs .= "\"$nextSearch\", ";
		}
		
		$MetaTypeStrs .= "\"\"";
		$MetaSearchStrs.= "\"\"";
		
		echo "var METAFIELD_STRINGS = new Array($MetaTypeStrs);";	
		echo "var METAFIELD_SEARCH_STRINGS = new Array($MetaSearchStrs);";
		echo "var METAFIELD_TYPE_DROPDOWN = $METAFIELD_TYPE_DROPDOWN;"; 
		?>
		
			var newName = document.getElementsByName("config_meta_name")[0].value;
			var newDDValues = document.getElementsByName("config_meta_ddvalues")[0].value;
			var newShow = document.getElementsByName("config_meta_show")[0].value;
			var newCriteriaName = document.getElementsByName("config_meta_criterianame")[0].value;
			var newCriteriaMethod = document.getElementsByName("config_meta_criteriamethod")[0].selectedIndex;
			var newType = document.getElementsByName("config_meta_type")[0].selectedIndex;
		
			if(newName.length < 1)
			{
				alert("No meta name specified.\nPlease enter a name and try again.");
				return;
			}
		
			if(newShow.length < 1)
			{
				alert("No meta show name specified.");
				return;
			}
			
			if(newCriteriaName.length < 1)
			{
				alert("No meta form name specifed.");
				return;
			}
			
			//check through list for exising meta fields
			var curList = document.getElementsByName("meta_rules[]")[0]; 
			var selIndex = -1;
			for(count = 0; count < curList.length; count++)
			{
				var nextItem = curList.options[count].text;	
				nextItem = nextItem.substr(0, newName.length);
				
				if(update == false) //Adding a new entry
				{
					if(nextItem == newName)
					{
						alert("A meta field entry for this name already exists.");
						return;
					}
				}
				else
				{
						if(curList.options[count].selected)
							selIndex = count;
				}											
			}
																
			var newEntryStr = newName + " | " + METAFIELD_STRINGS[newType] + " | " + newShow + " | " + newCriteriaName + " | " + METAFIELD_SEARCH_STRINGS[newCriteriaMethod] + " | ";
			
			//Get new drop down values if required
			if(newType == METAFIELD_TYPE_DROPDOWN)
			{
				newDDValues = newDDValues.trim();
				var newDDArray = newDDValues.split("\n");
				
				if(newDDArray.length > 0)
				{				
					for (var i = 0; i < newDDArray.length - 1; i++)
						newEntryStr += newDDArray[i] +",";
					
					newEntryStr += newDDArray[newDDArray.length-1];		
				}
			}
			newEntryStr += "\n";
			
			if(update == false)
			{
				//Create new option to add
				var newOpt = document.createElement("option");
				newOpt.text = newEntryStr;	
				newOpt.value = newEntryStr;
				
				//Add to list
				var curList = document.getElementsByName("meta_rules[]")[0];
				curList.add(newOpt, null);	
			}	
			else
			{
				if(selIndex == -1)
				{
						alert("No entry in the list was selected to update");	
						return;
				}
	
				curList.options[selIndex].text = newEntryStr;
				curList.options[selIndex].value = newEntryStr;	
			}					
	}
	
		//Reload the start points list based on spider/offline mode
	function ReloadStartPointDisplay()
	{
		var displayList =	document.getElementsByName("start_points_display[]")[0];
		displayList.options.length = 0;	 
		
		//Add current start point		
		var newOpt = document.createElement("option");
		newOpt.text = 	document.getElementsByName("config_starturl")[0].value	
		newOpt.value = 	document.getElementsByName("config_starturl")[0].value;
		displayList.add(newOpt, null);	
		
		if (document.getElementById("config_mode_spider").checked == true)
		{	
			//Build display list from hidden start points list
			var curStartPoints = document.getElementsByName("start_points_list");
			
			for(count = 0; count < curStartPoints.length; count++)
			{
				var newOpt = document.createElement("option");
				newOpt.text = curStartPoints[count].value;	
				newOpt.value = curStartPoints[count].value;
				displayList.add(newOpt, null);	
			}
				
		}
		else
		{
		
			//offline mode
			var curStartPoints = document.getElementsByName("start_points_off_list");
			//Build display list from hidden start points list
			for(count = 0; count < curStartPoints.length; count++)
			{
				var newOpt = document.createElement("option");
				newOpt.text = curStartPoints[count].value;	
				newOpt.value = curStartPoints[count].value;
				displayList.add(newOpt, null);	
			}
	
		}
		
	}
	
	//Handle Add and Update buttons for start points, if update is true then update currently selected
	function AddEditStartPoint(update)
	{
			var newLimit = 0;
			var newUseLimit = 0;
			var newStart = document.getElementsByName("config_sp_start")[0].value;
			var newBase = document.getElementsByName("config_sp_base")[0].value;
			var newType = document.getElementsByName("config_sp_spideropts")[0].selectedIndex;
			newLimit = parseInt(document.getElementsByName("config_sp_limit")[0].value);
			var newWeight = document.getElementsByName("config_sp_weight")[0].selectedIndex;	

			if(document.getElementsByName("config_sp_uselimit")[0].checked == true)
				newUseLimit = 1;
			
			//sanity check that newLimit is at least 0
			if(newLimit < 0 || isNaN(newLimit))
				newLimit = 0
		
			if(newStart.length < 1)
			{
				alert("No start point has been entered!");
				return;
			}
		
			if(newBase.length < 1)
			{
				alert("No base URL have been entered for start point!");
				return;
			}
		
		
			//check through list for exising start point
			var curList = document.getElementsByName("start_points_display[]")[0]; 
			var selIndex = -1;
			for(count = 0; count < curList.length; count++)
			{
				var nextItem = curList.options[count].text;	
				nextItem = nextItem.substr(0, newStart.length);
				
				if(update == false) //Adding a new entry
				{
					if(nextItem == newStart)
					{
						alert("An entry for this start point already exists.");
						return;
					}
				}
				else
				{
						if(curList.options[count].selected)
							selIndex = count;
				}											
			}
		
			var newEntryStr = newStart;
			
			if(update == false)
			{
				//Create new option to add
				var newOpt = document.createElement("option");
				newOpt.text = newEntryStr;	
				newOpt.value = newEntryStr;	
				
				//Add to list
				var curList = document.getElementsByName("start_points_display[]")[0];
				curList.add(newOpt, null);	
				
				selIndex = curList.length - 1;
			}	
			else
			{
				if(selIndex == -1)
				{
						alert("No entry in the list was selected to update");	
						return;
				}
	
				curList.options[selIndex].text = newEntryStr;
				curList.options[selIndex].value = newEntryStr;	
			}			
		
		
		//if first item is selected then need to process differently (using [0] reference as they might change the URL on the other page)			
		//and  then need to udpate the current fields on the start options page
		if(selIndex == 0 && update == true)
		{
			if (document.getElementById("config_mode_spider").checked == true)
			{
			  document.getElementsByName("starturl_type[0]")[0].value = newType;
			  document.getElementsByName("starturl_uselimit[0]")[0].value = newUseLimit; 
			  document.getElementsByName("starturl_limit[0]")[0].value = newLimit;
			  document.getElementsByName("starturl_boost[0]")[0].value = newWeight;
			  document.getElementsByName("start_point[0]")[0].value  = newStart;	
			}
			
			else
			{
				document.getElementsByName("start_point_off[0]")[0].value  = newStart;	
			}
			
			
			document.getElementsByName("config_starturl")[0].value  = newStart;
			document.getElementsByName("config_baseurl")[0].value  = newBase;			
			

			 
			return;
		}
	
		//Also need to update hidden fields
		if (document.getElementById("config_mode_spider").checked == true)
		{
			
			
			var newElement;
			var newName = "start_points[" + newEntryStr + "]";
			if(update == false)
			{
				newElement = document.createElement("input");
				newElement.setAttribute("type", "hidden");
				newElement.setAttribute("name", newName);
				newElement.setAttribute("value", newEntryStr);
				document.getElementById("mainform").appendChild(newElement);
				
				//Add new list field too			
				newElement = document.createElement("input");
				newElement.setAttribute("type", "hidden");
				newElement.setAttribute("name", "start_points_list");
				newElement.setAttribute("value", newEntryStr);
				document.getElementById("mainform").appendChild(newElement);

			}
			else
				newElement = document.getElementsByName(newName)[0].value = newEntryStr;
			
			
			var newElement;
			var newName = "starturl_base[" + newEntryStr + "]";
			if(update == false)
			{
				newElement = document.createElement("input");
				newElement.setAttribute("type", "hidden");
				newElement.setAttribute("name", newName);
				newElement.setAttribute("value", newBase);
				document.getElementById("mainform").appendChild(newElement);
			}
			else
				newElement = document.getElementsByName(newName)[0].value = newBase;
				
			newName = "starturl_type[" + newEntryStr + "]";
			if(update == false)
			{
				newElement = document.createElement("input");
				newElement.setAttribute("type", "hidden");
				newElement.setAttribute("name", newName);
				newElement.setAttribute("value", newType);
				document.getElementById("mainform").appendChild(newElement);
			}
			else
				newElement = document.getElementsByName(newName)[0].value = newType;	
	
			newName = "starturl_uselimit[" + newEntryStr + "]";
			if(update == false)
			{
				newElement = document.createElement("input");
				newElement.setAttribute("type", "hidden");
				newElement.setAttribute("name", newName);
				newElement.setAttribute("value", newUseLimit);
				document.getElementById("mainform").appendChild(newElement);
			}
			else
				newElement = document.getElementsByName(newName)[0].value = newUseLimit;	
		
			newName = "starturl_limit[" + newEntryStr + "]";
			if(update == false)
			{
				newElement = document.createElement("input");
				newElement.setAttribute("type", "hidden");
				newElement.setAttribute("name", newName);
				newElement.setAttribute("value", newLimit);
				document.getElementById("mainform").appendChild(newElement);
			}
			else
				newElement = document.getElementsByName(newName)[0].value = newLimit;	
			
			newName = "starturl_boost[" + newEntryStr + "]";
			if(update == false)
			{
				newElement = document.createElement("input");
				newElement.setAttribute("type", "hidden");
				newElement.setAttribute("name", newName);
				newElement.setAttribute("value", newWeight);
				document.getElementById("mainform").appendChild(newElement);
			}
			else
				newElement = document.getElementsByName(newName)[0].value = newWeight;	
		}
		else
		{
			//if in offline mode only use startpoint and base URL
			
			var newElement;
			var newName = "start_points_off[" + newEntryStr + "]";
			if(update == false)
			{
				newElement = document.createElement("input");
				newElement.setAttribute("type", "hidden");
				newElement.setAttribute("name", newName);
				newElement.setAttribute("value", newEntryStr);
				document.getElementById("mainform").appendChild(newElement);
				
				//Add new list field too			
				newElement = document.createElement("input");
				newElement.setAttribute("type", "hidden");
				newElement.setAttribute("name", "start_points_off_list");
				newElement.setAttribute("value", newEntryStr);
				document.getElementById("mainform").appendChild(newElement);
				
			}
			else
				newElement = document.getElementsByName(newName)[0].value = newEntryStr;
				

			newName = "starturl_base_off[" + newEntryStr + "]";
			if(update == false)
			{
				newElement = document.createElement("input");
				newElement.setAttribute("type", "hidden");
				newElement.setAttribute("name", newName);
				newElement.setAttribute("value", newBase);
				document.getElementById("mainform").appendChild(newElement);
			}
			else
				newElement = document.getElementsByName(newName)[0].value = newBase;
				

			
		}
		


	}
	
	//Handle Add and Update buttons for reccomendations, if update is true then update currently selected
	function AddEditRecLink(update)
	{
		
			var newWord = document.getElementsByName("config_rec_keyword")[0].value;
			var newURL = document.getElementsByName("config_rec_url")[0].value;
			var newTitle = document.getElementsByName("config_rec_title")[0].value;
			var newDesc = document.getElementsByName("config_rec_desc")[0].value;
			var newImg = document.getElementsByName("config_rec_img")[0].value;
		
			if(newWord.length < 1)
			{
				alert("Each recommended link must be associated with a keyword or search phrase.\nPlease enter a key word and try again.");
				return;
			}
		
			if(newURL.length < 1)
			{
				alert("no URL entered for recommended link.");
				return;
			}
			
			if(newTitle.length < 1)
			{
				alert("no title entered for recommended link.");
				return;
			}
			
			//check through list for exising recommended
			var curList = document.getElementsByName("recommended_rules[]")[0]; 
			var selIndex = -1;
			for(count = 0; count < curList.length; count++)
			{
				var nextItem = curList.options[count].text;	
				nextItem = nextItem.substr(0, newWord.length);
				
				if(update == false) //Adding a new entry
				{
					if(nextItem == newWord)
					{
						alert("A recommended entry for this word already exists.");
						return;
					}
				}
				else
				{
						if(curList.options[count].selected)
							selIndex = count;
				}											
			}
						
			var newEntryStr = newWord + " | " + newURL + " | " + newTitle + " | " + newDesc  + " | " + newImg;
			
			if(update == false)
			{
				//Create new option to add
				var newOpt = document.createElement("option");
				newOpt.text = newEntryStr;	
				newOpt.value = newEntryStr;	
				
				//Add to list
				var curList = document.getElementsByName("recommended_rules[]")[0];
				curList.add(newOpt, null);	
			}	
			else
			{
				if(selIndex == -1)
				{
						alert("No entry in the list was selected to update");	
						return;
				}
	
				curList.options[selIndex].text = newEntryStr;
				curList.options[selIndex].value = newEntryStr;	
			}					
	}
	

	
	//Handle Add and Update buttons for synonyms
	function AddEditSynonym(update)
	{
		<?PHP
		echo "var MAX_SYNONYM_LENGTH = $MAX_SYNONYM_LENGTH;\n";
		?>

			var newWord = document.getElementsByName("config_syn_word")[0].value;
			var newSynonym =document.getElementsByName("config_syn_synonyms")[0].value;
			
			if(newWord.length < 1)
			{
				alert("An indexed word is required for each synonym entry.\nPlease enter a word to map the synonyms to and try again.");
				return;
			}
			
			if(newWord.search(" ") != -1)
			{
				alert("The synonym word can not contain a space character.\nNote that you can not specify a phrase as a synonym.\n\nPlease remove the space character from your\nword entry and try again.");
				return;	
			}
			
			if(newSynonym.length < 1)
			{
				alert("A list of synonyms is required for each entry.\nThis is a list of words which will be considered equivalent to the indexed word\nplease enter a synonym and try again.");
				return;
			}
			
			if(newSynonym.length > MAX_SYNONYM_LENGTH)
			{
				alert("You have exceeded the maximum length for synonyms (100 characters).\nPlease enter a shorter synonym to continue.");
				return;
			}
			
			if(newSynonym.search(" ") != -1)
			{
				alert("The synonym list can not contain a space character.\nNote that you can not specify a phrase as a synonym.\n\nPlease remove the space character from your\nsynonyms entry and try again.");
				return;	
			}
			
	
			//check through list for exising synonym
			var curList = document.getElementsByName("synonym_rules[]")[0]; 
			var selIndex = -1;
			for(count = 0; count < curList.length; count++)
			{
				var nextItem = curList.options[count].text;	
				nextItem = nextItem.substr(0, newWord.length);
				
				if(update == false) //Adding a new entry
				{
					if(nextItem == newWord)
					{
						alert("A synonym entry for this word already exists.");
						return;
					}
				}
				else
				{
						if(curList.options[count].selected)
							selIndex = count;
				}											
			}
				
			var newEntryStr = newWord + " | " + newSynonym + "\n"
			
			if(update == false)
			{
				//Create new option to add
				var newOpt = document.createElement("option");
				newOpt.text = newEntryStr;	
				newOpt.value = newEntryStr;	
				
				//Add to list
				var curList = document.getElementsByName("synonym_rules[]")[0];
				curList.add(newOpt, null);	
			}	
			else
			{
				if(selIndex == -1)
				{
						alert("No entry in the list was selected to update");	
						return;
				}
	
				curList.options[selIndex].text = newEntryStr;
				curList.options[selIndex].value = newEntryStr;	
			}					
	}
	
	//Handle Add and Update buttons for categories
	function AddEditCategory(update)
	{
		
		<?PHP
		echo "var CAT_NAME_LEN = $CAT_NAME_LEN;\n";
		echo "var CAT_PATTERN_LEN = $CAT_PATTERN_LEN;\n";
		echo "var CAT_DESC_LEN = $CAT_DESC_LEN;\n";
		?>

			var newName = document.getElementsByName("config_cat_name")[0].value;
			var newMatch =document.getElementsByName("config_cat_match")[0].value;
			var newDesc =document.getElementsByName("config_cat_desc")[0].value;
			var newExclusive =document.getElementsByName("config_cat_exclusive")[0].checked;
			
			if(newName.length < 1)
			{ 
				alert("An unique name is required for each category.\nPlease enter a category name and try again.");
				return;
			}
					
			if(newName.length > CAT_NAME_LEN)
			{
				alert("The category name you have entered is too long.\nPlease enter a shorter category name to continue.");
				return;
			}
			
			if(newName.toLowerCase() == "all")
			{
				alert("You do not need to create a category for \"All\".\n\nAn \"All\" category is automatically created and is always implied\nto be available with the categories feature.\n\nFor this reason, you can not create a manual category named\n\"All\". Please rename your category.");
				return;	
			}
			
			if(newMatch.length == 0)
			{
				alert("You have not specified a pattern for this category.\n\nThis means that no files will be selected for this category\nunless they contain a meta ZOOMCATEGORY tag.");
				return;	
			}
			
			if(newMatch.length > CAT_PATTERN_LEN)
			{
				alert("The category pattern you have entered is too long.\nPlease enter a shorter pattern to continue.");
				return;
			}
		
			if(newDesc.length > CAT_DESC_LEN)
			{
				alert("The category description you have entered is too long.\nPlease enter a shorter description to continue.");
				return;
			}
			
			if(newDesc.length > 0 && newDesc.indexOf("|") != -1)
			{
				alert("The pipe character is not allowed in the category description.");
				return;
			}
	
			//check through list for exising category
			var curList = document.getElementsByName("category_rules[]")[0]; 
			var selIndex = -1;
			for(count = 0; count < curList.length; count++)
			{
				var nextItem = curList.options[count].text;	
				nextItem = nextItem.substr(0, newName.length);
				
				if(update == false) //Adding a new entry
				{
					if(nextItem == newName)
					{
						alert("A category entry with that name already exists.");
						return;
					}
				}
				else
				{
						if(curList.options[count].selected)
							selIndex = count;
				}											
			}
				
			var newEntryStr = newName + " | " + newMatch  + " | " + newDesc;
			
			if(newExclusive != false)
				newEntryStr += " | Exclusive";
			
			if(update == false)
			{
				//Create new option to add
				var newOpt = document.createElement("option");
				newOpt.text = newEntryStr;	
				newOpt.value = newEntryStr;	
				
				//Add to list
				var curList = document.getElementsByName("category_rules[]")[0];
				curList.add(newOpt, null);	
			}	
			else
			{
				if(selIndex == -1)
				{
						alert("No entry in the list was selected to update");	
						return;
				}
	
				curList.options[selIndex].text = newEntryStr;
				curList.options[selIndex].value = newEntryStr;
			}					
	}

	function MetaChange()
	{
		<?PHP
		
		$MetaTypeStrs = "";
		$MetaSearchStrs = "";
		
		foreach ($METAFIELD_STRINGS as $nextType)
		{			
			$MetaTypeStrs .= "\"$nextType\", ";
		}
		
		foreach ($METAFIELD_SEARCH_STRINGS as $nextSearch)
		{			
			$MetaSearchStrs .= "\"$nextSearch\", ";
		}
		
		$MetaTypeStrs .= "\"\"";
		$MetaSearchStrs.= "\"\"";
		
		echo "var METAFIELD_STRINGS = new Array($MetaTypeStrs);";	
		echo "var METAFIELD_SEARCH_STRINGS = new Array($MetaSearchStrs);";
		?>	
		
		//Get currently selected meta field - only first selected option when multiple are selected it used
		//(multi select used for delete)
		var SelectedMeta = "";
		var CurItems = document.getElementsByName("meta_rules[]")[0];
		for(count = 0; count < CurItems.length; count++)
		{
			if(CurItems.options[count].selected)
			{
				SelectedMeta = CurItems.options[count].text;
				break;
			}
		}
		
		if(SelectedMeta.length != 0)
		{			
			var metaField = SelectedMeta.split("|", 6); 
			document.getElementsByName("config_meta_name")[0].value = metaField[0].trim();
			document.getElementsByName("config_meta_show")[0].value = metaField[2].trim();
			document.getElementsByName("config_meta_criterianame")[0].value = metaField[3].trim();
			
			//Have to treat type, search criteria and ddvalues differently (requires more processing)
			metaField[1] = metaField[1].trim();
	
			for(i = 0; i < METAFIELD_STRINGS.length; i++)
			{
				if(metaField[1] == METAFIELD_STRINGS[i])
					document.getElementsByName("config_meta_type")[0].selectedIndex = i;		
			}
			
			metaField[4] = metaField[4].trim();
			for(i = 0; i < METAFIELD_SEARCH_STRINGS.length; i++)
			{
				if(metaField[4] == METAFIELD_SEARCH_STRINGS[i])
					document.getElementsByName("config_meta_criteriamethod")[0].selectedIndex = i;		
			}
			
			//Split DD values on , and replace with "\n"
			var ddvalueStr = "";
			//If no values don't need to process and also avoids a javascript "not defined" error
			if(metaField.length > 5)
			{
				var ddValues = metaField[5].split(","); 
				for(i = 0; i < ddValues.length; i++)
				{
					ddvalueStr += ddValues[i].trim() +"\n";
				}
			}
			document.getElementsByName("config_meta_ddvalues")[0].value = ddvalueStr;
		}
			
	}
	
	function RecChange()
	{
		//Get currently selected recommended link - only first selected option when multiple are selected it used
		//(multi select used for delete)
		var SelectedRec = "";
		var CurItems = document.getElementsByName("recommended_rules[]")[0];
		for(count = 0; count < CurItems.length; count++)
		{
			if(CurItems.options[count].selected)
			{
				SelectedRec = CurItems.options[count].text;
				break;
			}
		}
		if(SelectedRec.length != 0)
		{
			var recLink = SelectedRec.split("|", 5); 
			document.getElementsByName("config_rec_keyword")[0].value = recLink[0].trim();
			document.getElementsByName("config_rec_url")[0].value = recLink[1].trim();
			document.getElementsByName("config_rec_title")[0].value = recLink[2].trim();
			document.getElementsByName("config_rec_desc")[0].value = recLink[3].trim();
			document.getElementsByName("config_rec_img")[0].value = recLink[4].trim();
		}
			
	}
	
	function StartPointChange()
	{
			//Get currently selected start point - only first selected option when multiple are selected it used
		//(multi select used for delete)
		var SelectedSP = "";
		var count = 0;
			
		var CurItems = document.getElementsByName("start_points_display[]")[0];
		for(count = 0; count < CurItems.length; count++)
		{
			if(CurItems.options[count].selected)
			{
				SelectedSP = CurItems.options[count].text;
				break;
			}
		}
		
	
		//Different fields for default start point
		if(count == 0)
		{
			if (document.getElementById("config_mode_spider").checked == true)	
			{
				document.getElementsByName("config_sp_start")[0].value = SelectedSP;
				document.getElementsByName("config_sp_base")[0].value =  document.getElementsByName("config_baseurl")[0].value;			
				document.getElementsByName("config_sp_spideropts")[0].selectedIndex = document.getElementsByName("starturl_type[0]")[0].value;
		
				if(document.getElementsByName("starturl_uselimit[0]")[0].value == 0)			
						document.getElementsByName("config_sp_uselimit")[0].checked = false;
				else
						document.getElementsByName("config_sp_uselimit")[0].checked = true;
				
				document.getElementsByName("config_sp_limit")[0].value = document.getElementsByName("starturl_limit[0]")[0].value;
				document.getElementsByName("config_sp_weight")[0].selectedIndex = document.getElementsByName("starturl_boost[0]")[0].value;
			}
			else
			{
				document.getElementsByName("config_sp_start")[0].value = SelectedSP;
				document.getElementsByName("config_sp_base")[0].value = document.getElementsByName("config_baseurl")[0].value;		
				document.getElementsByName("config_sp_spideropts")[0].selectedIndex = 0;
				document.getElementsByName("config_sp_uselimit")[0].checked = false;
				document.getElementsByName("config_sp_limit")[0].value = "0";
				document.getElementsByName("config_sp_weight")[0].selectedIndex = 0;
			
			}	
		}
		else
		{	
			 if(SelectedSP.length != 0)
			{
				if (document.getElementById("config_mode_spider").checked == true)	
				{
					document.getElementsByName("config_sp_start")[0].value = SelectedSP;
					document.getElementsByName("config_sp_base")[0].value = document.getElementsByName("starturl_base[" + SelectedSP +"]" )[0].value;		
					if(document.getElementsByName("starturl_uselimit[" + SelectedSP +"]")[0].value == 0)			
						document.getElementsByName("config_sp_uselimit")[0].checked = false;
					else
						document.getElementsByName("config_sp_uselimit")[0].checked = true;
					
					document.getElementsByName("config_sp_spideropts")[0].selectedIndex = document.getElementsByName("starturl_type[" + SelectedSP +"]")[0].value;
					document.getElementsByName("config_sp_limit")[0].value = document.getElementsByName("starturl_limit[" + SelectedSP +"]")[0].value;	
					document.getElementsByName("config_sp_weight")[0].selectedIndex = document.getElementsByName("starturl_boost[" + SelectedSP +"]")[0].value;
			
				}
				else
				{
					document.getElementsByName("config_sp_start")[0].value = SelectedSP;
					document.getElementsByName("config_sp_base")[0].value = document.getElementsByName("starturl_base_off[" + SelectedSP +"]" )[0].value;		
					document.getElementsByName("config_sp_spideropts")[0].selectedIndex = 0;
					document.getElementsByName("config_sp_uselimit")[0].checked = false;
					document.getElementsByName("config_sp_limit")[0].value = "0";
					document.getElementsByName("config_sp_weight")[0].selectedIndex = 0;
				
				}	
			
			}
		}
		//If in offline mode disable N/A fields		
		if (document.getElementById("config_mode_spider").checked == false)
		{
			document.getElementsByName("config_sp_spideropts")[0].disabled = true; 
			document.getElementsByName("config_sp_uselimit")[0].disabled = true; 
			document.getElementsByName("config_sp_limit")[0].disabled = true; 
			document.getElementsByName("config_sp_weight")[0].disabled = true; 
		}
		else
		{
				document.getElementsByName("config_sp_spideropts")[0].disabled = false; 
			document.getElementsByName("config_sp_uselimit")[0].disabled = false; 
			document.getElementsByName("config_sp_limit")[0].disabled = false; 
			document.getElementsByName("config_sp_weight")[0].disabled = false;
		}

	}
	
	function SynonymChange()
	{
		//Get currently selected synonym - only first selected option when multiple are selected it used
		//(multi select used for delete)
		var SelectedSyn = "";
		var CurItems = document.getElementsByName("synonym_rules[]")[0];
		for(count = 0; count < CurItems.length; count++)
		{
			if(CurItems.options[count].selected)
			{
				SelectedSyn = CurItems.options[count].text;
				break;
			}
		}
		if(SelectedSyn.length != 0)
		{
			var synonym = SelectedSyn.split("|", 2); 
			document.getElementsByName("config_syn_word")[0].value = synonym[0].trim();
			document.getElementsByName("config_syn_synonyms")[0].value = synonym[1].trim();
		}
	}
	
	function CategoryChange()
	{
		//Get currently selected category - only first selected option when multiple are selected it used
		//(multi select used for delete)
		var SelectedCat = "";
		var CurItems = document.getElementsByName("category_rules[]")[0];
		for(count = 0; count < CurItems.length; count++)
		{
			if(CurItems.options[count].selected)
			{
				SelectedCat = CurItems.options[count].text;
				break;
			}
		}
		if(SelectedCat.length != 0)
		{
			var category = SelectedCat.split("|", 4); 
			document.getElementsByName("config_cat_name")[0].value = category[0].trim();
			document.getElementsByName("config_cat_match")[0].value = category[1].trim();
			document.getElementsByName("config_cat_desc")[0].value = category[2].trim();
			
			if(category.length > 3)
			{
				document.getElementsByName("config_cat_exclusive")[0].checked = true;
			}
			else
				document.getElementsByName("config_cat_exclusive")[0].checked = false;
			
		}
	}
	
	//Run functions that require config options to be updated on the page before 
	//calculting/displaying/disabling certain actions
	function PageLoad()
	{
		UpdateRAMUse();
		GeneratePreview();
		UpdateFileSize();
		UpdateOpt();
		EnableHTTPAuth();
		EnableCookieAuth();
		ChangeIndexMethod(1);
		RewriteLinkOptChange();
		StemmingOptChange();
		ACDCOptChange();
		ContentOptChange();
		CategoryOptChange();
		SitemapOptChange();
		IndexLogOptChange();	
		AdvancedOptChange();
		SetFreeLimits();
		
		//Display start options by default
		hideshow(document.getElementById('config_startoptions'), true);
	}
	
	//Update the optimization text display
	function UpdateOpt()
	{		
		//Use PHP defines for values
		<?PHP
	
		$MaxMatchesStr = "";
		$ContextStr = "";
		$TimeStr = "";
		
		for($count = 0; $count < $NUM_OPTIMIZE_SETTINGS; $count++)
		{
			$MaxMatchesStr .= "\"$OPTIMIZE_MAXMATCHES[$count]\"";
			$ContextStr .= "\"$OPTIMIZE_CONTEXTSEEKS[$count]\"";
			$TimeStr .= "\"$OPTIMIZE_MAXSEARCHTIME[$count]\"";
			
			if($count < $NUM_OPTIMIZE_SETTINGS-1)
			{
				$MaxMatchesStr .= ", ";
				$ContextStr .= ", ";
				$TimeStr .= ", ";
			}
		}
		
		
		echo "var OPTIMIZE_MAXMATCHES = new Array($MaxMatchesStr);\n";
		echo "var OPTIMIZE_CONTEXTSEEKS = new Array($ContextStr);\n";
		echo "var OPTIMIZE_MAXSEARCHTIME = new Array($TimeStr);\n";
		
		?>
			
		var selectedItem = document.getElementsByName("config_optimization")[0].selectedIndex;
		var OptString = "Max matches = " + OPTIMIZE_MAXMATCHES[selectedItem] + "\nMax context seeks = " + OPTIMIZE_CONTEXTSEEKS[selectedItem] + "\nMax search time = "+ OPTIMIZE_MAXSEARCHTIME[selectedItem] +" sec.";
			
		document.getElementsByName("optimization_settings")[0].value = OptString;

	}

	function ScanExtImageOptChange(newvalue)
	{
		//Default to disable
		var isDisabled = true;
		
		//Enable thumbnail options
		if(newvalue == 2)
		{
			isDisabled = false
		}
		
		document.getElementsByName("config_ext_conf_thumbfolder")[0].disabled = isDisabled;
		document.getElementsByName("config_ext_conf_thumbbefore")[0].disabled = isDisabled;
		document.getElementsByName("config_ext_conf_thumbafter")[0].disabled = isDisabled;
		document.getElementsByName("config_ext_conf_thumbext")[0].disabled = isDisabled;
		document.getElementsByName("config_ext_conf_thumbexurl")[0].disabled = isDisabled;
		document.getElementsByName("config_ext_conf_thumbexloc")[0].disabled = isDisabled;						
		
	}
	
	
function RewriteLinkOptChange()
	{
		
		if(document.getElementsByName("config_rewriteurls")[0].checked == true)
		{
			document.getElementsByName("config_rewrite_find")[0].disabled = false;
			document.getElementsByName("config_rewrite_replace")[0].disabled  = false;
		}
		else
		{
			document.getElementsByName("config_rewrite_find")[0].disabled = true;
			document.getElementsByName("config_rewrite_replace")[0].disabled  = true;
		}	
		
	}
	
	function ACDCOptChange()
	{
				
		if(document.getElementsByName("config_language_acdc")[0].checked == true)
		{
			document.getElementsByName("config_language_accents")[0].disabled = false;
			document.getElementsByName("config_language_umlauts")[0].disabled  = false;
			document.getElementsByName("config_language_ligatures")[0].disabled  = false;
			document.getElementsByName("config_language_digraphs")[0].disabled  = false;
		}
		else
		{
			document.getElementsByName("config_language_accents")[0].disabled = true;
			document.getElementsByName("config_language_umlauts")[0].disabled  = true;
			document.getElementsByName("config_language_ligatures")[0].disabled  = true;
			document.getElementsByName("config_language_digraphs")[0].disabled  = true;
		}	
		
	}
	
	function StemmingOptChange()
	{
								
		if(document.getElementsByName("config_language_stemming")[0].checked == true)
		{
			document.getElementsByName("config_languages_stem_lang")[0].disabled = false;
		}
		else
		{
			document.getElementsByName("config_languages_stem_lang")[0].disabled = true;
		}	
		
	}
	
	function ContentOptChange()
	{
		if(document.getElementsByName("config_filtering_enable")[0].checked == true)
		{
			document.getElementsByName("filtering_rules")[0].disabled = false;
		}
		else
		{
			document.getElementsByName("filtering_rules")[0].disabled = true;
		}	
		

	}
	
	function CategoryOptChange()
	{
		
		if(document.getElementsByName("config_categories_enable")[0].checked == true)
		{
			document.getElementsByName("category_rules[]")[0].disabled = false;
			document.getElementsByName("config_categories_catchfiles")[0].disabled = false;
			document.getElementsByName("config_categories_mutil")[0].disabled = false;
			document.getElementsByName("config_categories_breakdown")[0].disabled = false;
			document.getElementsByName("config_categories_remove")[0].disabled = false;
			document.getElementsByName("config_categories_removeall")[0].disabled = false;
			document.getElementsByName("config_cat_name")[0].disabled = false;
			document.getElementsByName("config_cat_match")[0].disabled = false;
			document.getElementsByName("config_cat_exclusive")[0].disabled = false;
			document.getElementsByName("config_cat_desc")[0].disabled = false;
		}
		else
		{
			document.getElementsByName("category_rules[]")[0].disabled = true;
			document.getElementsByName("config_categories_catchfiles")[0].disabled = true;
			document.getElementsByName("config_categories_mutil")[0].disabled = true;
			document.getElementsByName("config_categories_breakdown")[0].disabled = true;
			document.getElementsByName("config_categories_remove")[0].disabled = true;
			document.getElementsByName("config_categories_removeall")[0].disabled = true;
			document.getElementsByName("config_cat_name")[0].disabled = true;
			document.getElementsByName("config_cat_match")[0].disabled = true;
			document.getElementsByName("config_cat_exclusive")[0].disabled = true;
			document.getElementsByName("config_cat_desc")[0].disabled = true;
		}	
		
	}
	
	function SitemapOptChange()
	{			
		if(document.getElementsByName("config_sitemap_txt")[0].checked == true)
		{		
			document.getElementsByName("config_sitemap_upload")[0].disabled = false;
			document.getElementsByName("config_sitemap_uploadpath")[0].disabled = false;
		}
		else
		{
			document.getElementsByName("config_sitemap_upload")[0].disabled = true;
			document.getElementsByName("config_sitemap_uploadpath")[0].disabled = true;	
		}
			
		if(document.getElementsByName("config_sitemap_xml")[0].checked == true)
		{		
			document.getElementsByName("config_sitemap_baseurl")[0].disabled = false;
			document.getElementsByName("config_sitemap_include")[0].disabled = false;
			document.getElementsByName("config_sitemap_include")[1].disabled = false;
			document.getElementsByName("config_sitemap_upload")[0].disabled = false;
			document.getElementsByName("config_sitemap_uploadpath")[0].disabled = false;
			document.getElementsByName("config_sitemap_pageboost")[0].disabled = false;		
		}
		else
		{
			document.getElementsByName("config_sitemap_baseurl")[0].disabled = true;
			document.getElementsByName("config_sitemap_include")[0].disabled = true;
			document.getElementsByName("config_sitemap_include")[1].disabled = true;
			document.getElementsByName("config_sitemap_pageboost")[0].disabled = true;			
		}
		
	}
	
	function IndexLogOptChange()
	{
		if(document.getElementsByName("config_indexlog_save")[0].checked == true)
		{		
			document.getElementsByName("config_indexlog_appenddate")[0].disabled = false;
			document.getElementsByName("config_indexlog_debugmode")[0].disabled = false;
			document.getElementsByName("config_indexlog_path")[0].disabled = false;			
		}
		else
		{
			document.getElementsByName("config_indexlog_appenddate")[0].disabled = true;
			document.getElementsByName("config_indexlog_debugmode")[0].disabled = true;	
			document.getElementsByName("config_indexlog_path")[0].disabled = true;
		}			
	}
	
	function AdvancedOptChange()
	{
		
		//Custom script
		if(document.getElementsByName("config_advanced_specifyscript")[0].checked == true)
		{		
			document.getElementsByName("config_advanced_scriptpath")[0].disabled = false;
		}
		else
		{
			document.getElementsByName("config_advanced_scriptpath")[0].disabled = true;
		}				
		
		//Log searches
		if(document.getElementsByName("config_advanced_logsearches")[0].checked == true)
		{		
			document.getElementsByName("config_advanced_logpath")[0].disabled = false;
		}
		else
		{
			document.getElementsByName("config_advanced_logpath")[0].disabled = true;
		}		
		
		//XML/RSS
		if(document.getElementsByName("config_advanced_usexml")[0].checked == true)
		{		
			document.getElementsByName("config_advanced_ci_title")[0].disabled = false;
			document.getElementsByName("config_advanced_ci_desc")[0].disabled = false;
			document.getElementsByName("config_advanced_ci_url")[0].disabled = false;
			document.getElementsByName("config_advanced_ci_xlst")[0].disabled = false;
		}
		else
		{
			document.getElementsByName("config_advanced_ci_title")[0].disabled = true;
			document.getElementsByName("config_advanced_ci_desc")[0].disabled = true;
			document.getElementsByName("config_advanced_ci_url")[0].disabled = true;
			document.getElementsByName("config_advanced_ci_xlst")[0].disabled = true;
		}		

	}
	
	
	//Remove a scan extension from the list
	function DelScanExt()
	{
		var selectedItems = document.getElementsByName("config_scan_exts[]")[0];
		for(count = selectedItems.length-1; count > -1 ; count--)
		{
			if(selectedItems.options[count].selected)
					selectedItems.remove(count);
		}
		
	}
	
	//Check if the current value of a field is > 0. 
	//If not display an error message and set to a default value
	function CheckisNumGreaterThan0(field, value, name, defvalue)
	{

		if(isNaN(value) ||  value < 1 )
		{
			alert("You have specified an invalid value for the "+ name +" field.\nThe value has been reset to its default.");
			field.value = defvalue;
			
		}
		
	}
	
	function ValidateDLThreads()
	{
		var maxThreads = 10;
		
		<?PHP
		echo("var maxThreads = $MAX_DOWNLOAD_THREADS;");
		?>
		
		var numThreads = Number(document.getElementsByName("config_numdlthreads")[0].value);

		if(isNaN(numThreads) ||  numThreads < 1 || numThreads > maxThreads)
		{
			alert("You have specified an invalid number of download threads.\nPlease specify a number between 1 and 10.");
			document.getElementsByName("config_numdlthreads")[0].value = 1;
		}
		
		
	}
	
	//Add a scan extension to the list
	function AddScanExt()
	{
		var MAX_EXT = 100;
		
		<?PHP
		echo "MAX_EXT = $MAX_EXT";
		?>		
				
		var extStr = document.getElementsByName("config_scan_add_ext")[0].value;
				
		if(extStr == null)
		{
				alert("No file extension entered.");
				return;								 
		}
		
		if(extStr[0] != ".")
		{
				alert("The file extension must begin with a dot.\neg. \".html\", \".txt\", \".php\", etc.");
				return;								 
		}
		
		if(extStr.length > MAX_EXT )
		{
				alert("You have exceeded the maximum length for a file extension.\nPlease enter a shorter extension to continue.");
				return;								 
		}
		
		
		//append - so we can compare to list
		 extStr = extStr + " | ";
		 
		//check through list for exising ext
		var curEntries = document.getElementsByName("config_scan_exts[]")[0]; 
		for(count = 0; count < curEntries.length; count++)
		{
			var nextItem = curEntries.options[count].text;	
			nextItem = nextItem.substr(0, extStr.length);
			
			if(nextItem == extStr)
			{
				alert("This extension is already in the list of file extensions to scan.");
				return;
			}
		}		
		
		var typeStr = document.getElementsByName("config_scan_add_exttype")[0].value;
		var newEntryStr = extStr + typeStr + "\n"
								
		//Create new option to add
		var newOpt = document.createElement("option");
		newOpt.text = newEntryStr;
		newOpt.value = newEntryStr;
		
		//Add to list
		var curList = document.getElementsByName("config_scan_exts[]")[0];
		curList.add(newOpt, null);
		
		
		//Also have to create thumbnail/image options elements
		extStr = document.getElementsByName("config_scan_add_ext")[0].value;
		
		var newName = "ImageURL[" + extStr + "]";
		var newElement = document.createElement("input");
		newElement.setAttribute("type", "hidden");
		newElement.setAttribute("name", newName);
		document.getElementById("mainform").appendChild(newElement);
		
		newName = "UseThumbs[" + extStr + "]";
		newElement = document.createElement("input");
		newElement.setAttribute("type", "hidden");
		newElement.setAttribute("name", newName);
		document.getElementById("mainform").appendChild(newElement);
		
		newName = "ThumbsPath[" + extStr + "]";
		newElement = document.createElement("input");
		newElement.setAttribute("type", "hidden");
		newElement.setAttribute("name", newName);
		document.getElementById("mainform").appendChild(newElement);
		
		newName = "ThumbsFilenamePrefix[" + extStr + "]";
		newElement = document.createElement("input");
		newElement.setAttribute("type", "hidden");
		newElement.setAttribute("name", newName);
		document.getElementById("mainform").appendChild(newElement);
		
		newName = "ThumbsFilenamePostfix[" + extStr + "]";
		newElement = document.createElement("input");
		newElement.setAttribute("type", "hidden");
		newElement.setAttribute("name", newName);
		document.getElementById("mainform").appendChild(newElement);
		
		newName = "ThumbsExt[" + extStr + "]";
		newElement = document.createElement("input");
		newElement.setAttribute("type", "hidden");
		newElement.setAttribute("name", newName);
		document.getElementById("mainform").appendChild(newElement);
		
		//Clear text entry
		document.getElementsByName("config_scan_add_ext")[0].value = "";
			
	}
	
	//Generate the HTML preview of results based on current selections
	function GeneratePreview()
	{

		var previewText = "";
		var tmpLine = "";
		
		//if (TempResultZoomImage)
		if(document.getElementsByName("config_res_image")[0].checked == true)
		{
			previewText = "<div id=\"previewimage\" style=\"float:left; margin-bottom:20px; margin-right:5px; \"><img src=\"./images/image_48.png\"></div>";
		}
	
		
		previewText +=	"<div id=\"previewtext\" >";
		//if (TempResultNumber)
		if(document.getElementsByName("config_res_number")[0].checked == true)
		{
			previewText += "<b>1.</b> ";
		}
		
		//if (TempResultTitle)
		if(document.getElementsByName("config_res_title")[0].checked == true)
		{
			previewText += "<a style=\"color:blue\" href=\"\">Title of page</a><br>";
		}
		else
		{
			previewText += "<a style=\"color:blue\" href=\"\">http://www.mysite.com/page.html</a><br>";
		}

		//if (TempResultMetaDesc)
		if(document.getElementsByName("config_res_metadesciption")[0].checked == true)
		{
			previewText += "<span style=\"color: rgb(0, 120, 0);\">This is the page's meta description</span><br>";
		}

		//if (TempResultContext && UConfig.OutputFormat != OUTPUT_JSFILE)
		if(document.getElementsByName("config_res_context")[0].checked == true &&
				document.getElementsByName("config_platform")[0].nodeValue != "js")
		{		
			//if (TempResultHighlight)
			if(	document.getElementsByName("config_highlight_matched").checked == true )
				previewText += "... context description. Actual <span style=\"color: rgb(255, 255, 40);\">content</span>from the page where the searched<br>";									
			else
			  previewText += "... context description. Actual content from the page where the searched<br>";									
			
			previewText += "word was found ... more context from the page near the words found ...<br>";
		}
		
		//if (TempResultTerms)
		if(document.getElementsByName("config_res_terms")[0].checked == true)
			tmpLine += "Terms matched: 2";
			
		//if (TempResultScore)
		if(document.getElementsByName("config_res_score")[0].checked == true)
		{
			if(tmpLine.length > 0)
					tmpLine += " - ";
			tmpLine += "Score: 1345";
		}
		
		//if (TempResultDate)
		if(document.getElementsByName("config_res_date")[0].checked == true)
		{
			if(tmpLine.length > 0)
					tmpLine += " - ";
			tmpLine += "1 Aug 2013";
		}
		
		//if (TempResultFilesize)
		if(document.getElementsByName("config_res_filesize")[0].checked == true)
		{
			if(tmpLine.length > 0)
					tmpLine += " - ";
			tmpLine += "5k";
		}	
		
		//if (TempResultURL)
		if(document.getElementsByName("config_res_url")[0].checked == true)
		{
			if(tmpLine.length > 0)
					tmpLine += " - ";
			tmpLine += "URL: http://www.mywebsite.com/page.html";
		}
		
		previewText += "<span id=smalltext style=\"font-size: 10px\">";
		previewText += tmpLine;
		previewText += "</span></div>";
			
		//Update preview div
		document.getElementById("preview").innerHTML = previewText;

	}
	
	//use to enable/diable value fields linked to a checkbox
	function ToggleChecboxValue(cb, cb_val)
	{
		if(document.getElementsByName(cb)[0].checked)
		{
			document.getElementsByName(cb_val)[0].disabled = false;
		}
		else
		{	
			document.getElementsByName(cb_val)[0].disabled  = true;	
		}
		
	}
	
	//Used to validate some only integer fields (can use type="number" but it's HTMl5 only)
function onlyNumbers(e)
{
	var keynum;
	var keychar;
	var numcheck;
	if(window.event) // IE
  	{
  		keynum = e.keyCode;
  	}
		else if(e.which) // Netscape/Firefox/Opera
  	{
  		keynum = e.which;
  	}
  
  //Check if keychar is NULL, probably a control char like DEL or arrow keys
  if(keynum == null)
   return true;
  	
	keychar = String.fromCharCode(keynum);
	numcheck = /\d/;
	
	if(numcheck.test(keychar) == true)
	 return true;
	 
	numcheck = /[\b]/; //Have to check for backspace or can't delete in firefox
	
	if(numcheck.test(keychar) == true)
	 return true;
	 
	 //Chec
	 
	 return false;
}

//Selects the file tyope based on the extension entered
function PickDefaultExtensionByFileType()
{

	var curExt = document.getElementsByName("config_scan_add_ext")[0].value;

		if (curExt.search(".html") != -1|| 
		curExt.search(".htm") != -1 ||
		curExt.search(".shtml") != -1 ||
		curExt.search(".shtm") != -1 ||
		curExt.search(".xml") != -1 ||
		curExt.search(".xhtml") != -1 ||
		curExt.search(".php") != -1 ||
		curExt.search(".php3") != -1 ||
		curExt.search(".asp") != -1 ||
		curExt.search(".aspx") != -1 ||
		curExt.search(".cfm") != -1 ||
		curExt.search(".pl") != -1 ||
		curExt.search(".cgi") != -1)	
		{
			document.getElementsByName("config_scan_add_exttype")[0].selectedIndex = 0;
			return;
		}

	if (curExt.search(".txt") != -1 || 
		curExt.search(".text") != -1  ||
		curExt.search(".nfo") != -1 ||
		curExt.search(".js") != -1  ||
		curExt.search(".dat") != -1 )
	{
			document.getElementsByName("config_scan_add_exttype")[0].selectedIndex = 1;
			return;
		}

	if (curExt.search(".docx") != -1  ||
		curExt.search(".pptx") != -1 ||
		curExt.search(".ppsx") != -1 ||
		curExt.search(".dotx") != -1 ||
		curExt.search(".xlsx") != -1 )
	{
			document.getElementsByName("config_scan_add_exttype")[0].selectedIndex = 15;
			return;
		}

	if (curExt.search(".doc") != -1  || curExt.search(".dot") != -1)
	{
			document.getElementsByName("config_scan_add_exttype")[0].selectedIndex = 4;
			return;
		}

	if (curExt.search(".pdf") != -1  )
	{
			document.getElementsByName("config_scan_add_exttype")[0].selectedIndex = 5;
			return;
		}

	if (curExt.search(".ppt") != -1 ||
		curExt.search(".pps") != -1  ||
		curExt.search(".pot") != -1 )
	{
			document.getElementsByName("config_scan_add_exttype")[0].selectedIndex = 6;
			return;
		}


	if (curExt.search(".xls") != -1 || curExt.search(".xlt") != -1 )
	{
			document.getElementsByName("config_scan_add_exttype")[0].selectedIndex = 7;
			return;
		}

	if (curExt.search(".wpd") != -1 )
	{
			document.getElementsByName("config_scan_add_exttype")[0].selectedIndex = 8;
			return;
		}

	if (curExt.search(".swf") != -1 )
	{
			document.getElementsByName("config_scan_add_exttype")[0].selectedIndex = 9;
			return;
		}

	if (curExt.search(".rtf") != -1 )
	{
			document.getElementsByName("config_scan_add_exttype")[0].selectedIndex = 10;
			return;
		}		

	if (curExt.search(".djvu") != -1 )
	{
			document.getElementsByName("config_scan_add_exttype")[0].selectedIndex = 11;
			return;
		}		

	if (curExt.search(".jpg") != -1 ||
		curExt.search(".jpeg") != -1  ||
		curExt.search(".jpe") != -1  ||
		curExt.search(".gif") != -1  ||
		curExt.search(".tiff") != -1 ||
		curExt.search(".tif") != -1  ||
		curExt.search(".png") != -1 )
	{
			document.getElementsByName("config_scan_add_exttype")[0].selectedIndex = 12;
			return;
	}		

	if (curExt.search(".mp3") != -1 )
		{
			document.getElementsByName("config_scan_add_exttype")[0].selectedIndex = 13 ;
			return;
		}

	if (curExt.search(".dwf") != -1)
	{
			document.getElementsByName("config_scan_add_exttype")[0].selectedIndex = 14;
			return;
		}	

	if (curExt.search(".torrent") != -1)
		{
			document.getElementsByName("config_scan_add_exttype")[0].selectedIndex = 16;
			return;
		}

	if (curExt.search(".mht") != -1)
	{
			document.getElementsByName("config_scan_add_exttype")[0].selectedIndex = 17;
			return;
		}

	if (curExt.search(".zip") != -1 )
		{
			document.getElementsByName("config_scan_add_exttype")[0].selectedIndex = 18;
			return;
		}
		
	if (curExt.search(".tgz") != -1  ||
		curExt.search(".taz") != -1  ||
		curExt.search(".tar.gz") != -1  ||
		curExt.search(".tar") != -1 )
		{
			document.getElementsByName("config_scan_add_exttype")[0].selectedIndex = 25;
			return;
		}

	if (curExt.search(".gz") != -1)
		{
			document.getElementsByName("config_scan_add_exttype")[0].selectedIndex = 26;
			return;
		}
		
	if (curExt.search(".pst") != -1  ||
		curExt.search(".ost") != -1)
		{
			document.getElementsByName("config_scan_add_exttype")[0].selectedIndex = 20;
			return;
		}

	if (curExt.search(".msg") != -1)
		{
			document.getElementsByName("config_scan_add_exttype")[0].selectedIndex = 27;
			return;
		}

	if (curExt.search(".mbox") != -1  ||
			curExt.search(".mbx") != -1  ||
			curExt.search(".eml") != -1)
		{
			document.getElementsByName("config_scan_add_exttype")[0].selectedIndex = 21;
			return;
		}

	if (curExt.search(".msf") != -1)
		{
			document.getElementsByName("config_scan_add_exttype")[0].selectedIndex = 23;
			return;
		}

	if (curExt.search(".dbx") != -1)
		{
			document.getElementsByName("config_scan_add_exttype")[0].selectedIndex = 22;
			return;
		}
		
	if (curExt.search(".avi") != -1 ||
		curExt.search(".wmv") != -1 ||		
		curExt.search(".mpg") != -1 ||
		curExt.search(".mpeg") != -1 ||
		curExt.search(".rmv") != -1 ||
		curExt.search(".rmvb") != -1 ||
		curExt.search(".flv") != -1 ||
		curExt.search(".mov") != -1 ||		
		curExt.search(".qt") != -1 ||
		curExt.search(".wav") != -1 ||
		curExt.search(".3fr") != -1 ||
		curExt.search(".acr") != -1 ||
		curExt.search(".afm") != -1 ||
		curExt.search(".acfm") != -1 ||
		curExt.search(".amfm") != -1 ||
		curExt.search(".ai") != -1 ||
		curExt.search(".ait") != -1 ||
		curExt.search(".aiff") != -1 ||
		curExt.search(".aif") != -1 ||
		curExt.search(".aifc") != -1 ||
		curExt.search(".ape") != -1 ||
		curExt.search(".arw") != -1 ||
		curExt.search(".asf") != -1 ||
		curExt.search(".dib") != -1 ||
		curExt.search(".btf") != -1 ||
		curExt.search(".cos") != -1 ||
		curExt.search(".dcp") != -1 ||
		curExt.search(".dcr") != -1 ||
		curExt.search(".divx") != -1 ||
		curExt.search(".dng") != -1 ||
		curExt.search(".fla") != -1 ||
		curExt.search(".f4a") != -1 ||
		curExt.search(".f4b") != -1 ||
		curExt.search(".f4p") != -1 ||
		curExt.search(".f4v") != -1 ||
		curExt.search(".fla") != -1 ||
		curExt.search(".flac") != -1 ||
		curExt.search(".fpx") != -1 ||
		curExt.search(".hdp") != -1 ||
		curExt.search(".wdp") != -1 ||
		curExt.search(".itc") != -1 ||
		curExt.search(".m4ts") != -1 ||
		curExt.search(".mts") != -1 ||
		curExt.search(".m2t") != -1 ||
		curExt.search(".m4a") != -1 ||
		curExt.search(".m4b") != -1 ||
		curExt.search(".m4p") != -1 ||
		curExt.search(".m4v") != -1 ||
		curExt.search(".mef") != -1 ||
		curExt.search(".mie") != -1 ||
		curExt.search(".miff") != -1 ||
		curExt.search(".mif") != -1 ||
		curExt.search(".mka") != -1 ||
		curExt.search(".mkv") != -1 ||
		curExt.search(".mks") != -1 ||
		curExt.search(".mpc") != -1 ||
		curExt.search(".ogg") != -1 ||
		curExt.search(".psd") != -1 ||
		curExt.search(".psb") != -1 ||
		curExt.search(".psp") != -1 ||
		curExt.search(".pspimage") != -1 ||
		curExt.search(".ra") != -1 ||
		curExt.search(".vob") != -1 ||
		curExt.search(".xcf") != -1 ||
		curExt.search(".bmp") != -1)
	{
		document.getElementsByName("config_scan_add_exttype")[0].selectedIndex = 19;
		return;
	}
		
	if (curExt.search(".exe") != -1  ||
		curExt.search(".dgn") != -1  ||
		curExt.search(".wma") != -1  ||
		curExt.search(".cab") != -1  ||
		curExt.search(".rar") != -1  ||		
		curExt.search(".bz2") != -1  ||		
		curExt.search(".jar") != -1 ||		
		curExt.search(".lzo") != -1 ||		
		curExt.search(".cat") != -1 ||
		curExt.search(".chm") != -1 ||
		curExt.search(".msi") != -1 ||
		curExt.search(".pub") != -1 ||
		curExt.search(".hlp") != -1  )
		{
			document.getElementsByName("config_scan_add_exttype")[0].selectedIndex = 3;
			return;
		}
		
		
	document.getElementsByName("config_scan_add_exttype")[0].selectedIndex = 2;	
	return;
}

	
	//Update the images and text relating to index method
	function ChangeIndexMethod(section)
	{
		<?PHP
			echo "var OUTPUT_PHP = $OUTPUT_PHP;\n";
			echo "var OUTPUT_ASP = $OUTPUT_ASP;\n";
			echo "var OUTPUT_JSFILE = $OUTPUT_JSFILE;\n";
			echo "var OUTPUT_CGI = $OUTPUT_CGI;\n";	
			echo "var OUTPUT_ASPNET = $OUTPUT_ASPNET;\n";	
		?>
		var hintMsg = "";
		//Section will vary based on item group that was last clicked
		switch(section)
		{
		
			//Indexing mode
			case 1:
			{
				if(document.getElementById("config_mode_spider").checked )
				{
						document.getElementById("indexing_image").src = "./images/spider_48.png";
						
						hintMsg = 
						"Spider mode indexes a remote copy of your website already uploaded and hosted on a web server.<br><br>It does this via the use of a 'spider'," +
						"which starts from a given start page, and follow the links it finds on each page. This allows the indexer to thoroughly index a website" +
						"containing both static content (.htm and .html files which do not change) and dynamically generated content (such as websites with a PHP" +
						"or ASP driven backend, message boards, etc.).<br><br>It requires an established Internet connection";			
				
				
					//Udpate start point to match spider or offline
					document.getElementsByName("config_starturl")[0].value = 	document.getElementsByName("start_point[0]")[0].value ;	
						
				}
				else
				{
					document.getElementById("indexing_image").src = "./images/hard_drive_48.png";
					
					hintMsg = 
					"Offline mode indexes a local copy of your website, stored on your hard disk.<br><br>" +
					"This is effective for static web pages and allows the user to index a website without uploading it to a web server, maximizing indexing speed "+
					"and convenience. It can also be used for web pages that will be published on a disk or CD-ROM, where a web server will not be available and does not require an Internet connection.";
				
					//Udpate start point to match spider or offline
					document.getElementsByName("config_starturl")[0].value = 	document.getElementsByName("start_point_off[0]")[0].value ;	
			
				}
			
				
				//clear and reload advanced start points list
				ReloadStartPointDisplay();
				break;
			}
			//Start url
			case 2:
			{
				if(document.getElementById("config_mode_spider").checked )
				{
					hintMsg = 
					"In spider mode, you are required to specify the URL from which the indexer will start the spider scanning from." +
					"<br><br>Typically, you would point this to the entrance page of your website, (such as index.html) so that it will be able to find links to other pages on your" +
					"website by following the links it finds on each page (as a visitor would).";
				}
				else
				{					
					hintMsg = 
					"The start directory specifies the local directory for the offline scanning to begin in. All sub-directories under the start directory"+
					" will also be scanned. In other words, you should point this at the folder in which you have created the files for your website.";			
				}
					break;
			}
			//Base url
			case 3:
			{
				
				if(document.getElementById("config_mode_spider").checked )
				{
					hintMsg = 
							"In spider mode, this is automatically determined for you based on the URL of the start page specified but you can override it if necessary (click on the 'More' button and select 'Edit')." +
							"<br><br>This is the URL where your website will be published and uploaded to. For example, if your website will be published at http://abc.com/~me/index.html, then http://abc.com/~me/ will" +
							" be the base URL of your website. This is used to determine the base location of each file on your website, so do not specify the filename of the main page (i.e. index.html, home.htm, etc.).";
				}
				else
				{					
					hintMsg = 
					"This is the URL where your website will be published and uploaded to. For example, if your website will be published at http://abc.com/~me/index.html, then http://abc.com/~me/ will" +
					" be the base URL of your website. This is used to determine the base location of each file on your website, so do not specify the filename of the main page (i.e. index.html, home.htm, etc.).";
				}
					break;
			}
			//Output directory
			case 4:
			{
					hintMsg = 
					"This is the directory in which the index files generated will be saved. Usually you would put this in the same directory as your website"+
					" files so that they can be uploaded together.";
						break;
			}
			//Platform
			case 5:
			{
				var OutputFormat = 0;
				
				for (i = 0; i < document.getElementsByName('config_platform').length; i++) 
				{
            if (document.getElementsByName('config_platform')[i].checked) 
            {
            	OutputFormat = document.getElementsByName('config_platform')[i].value;
            }
        }
				
				
				if(OutputFormat == OUTPUT_PHP ) 
				{
						hintMsg = 
						"PHP (PHP: Hypertext Preprocessor) is most commonly found on web servers running Apache (as it is now built-in by default), but it can be installed on IIS servers also." +
						"<br><br>It is a popular software package installed on web servers to provide scripting capabilities. Since March 2004, there are a reported 15,528,732 website domains which use PHP (source: Netcraft Survey)." +
						"<br><br>If you are not sure, check with your web host to find out if PHP is available on your server.";
				}
				else if(OutputFormat == OUTPUT_ASP )
				{
						hintMsg = 
						"ASP (Active Server Pages) is the Microsoft equivalent to PHP, and comes packaged with most default IIS (Internet Information Services) web servers. Chances are if you have a Windows-based web server,"+
						" you will have ASP available to you. It can also be available on some non-IIS servers, but they are less common."+
						"<br><br>Note that ASP is not the same as ASP.NET (Microsoft's new server-side platform), and they are not compatible."+
						"<br><br>Check with your web host to find out if ASP is available on your hosting account.";
				}
				else if(OutputFormat == OUTPUT_JSFILE )
				{
					hintMsg = 
					"Javascript is for offline distributions (CD-ROMs, DVDs) without a web server or where any of the other server-side options are not supported."+
					"<br><br>Javascript is a scripting language that allows a web page to tell the browser what to do. It is a client-side language, meaning that it"+
					" is interpreted and processed on the computer viewing the web site - not on the server. This means that it is usually very limited in what it can"+
					" do, but can be convenient since it does not rely on any special requirements on the server-side, and can also run off a CD with no web server present.";
					
				}
				else if(OutputFormat == OUTPUT_CGI ) 
				{
					hintMsg = 
					"CGI (Common Gateway Interface) is the high performance option, designed for sites of 60,000+ pages and advanced users."+
					"<br><br>CGI is a method of running programs on a server over the web. This is different to PHP and ASP in that it does not have to load and interpret a \"script\", and" +
					" is not limited by the technical capabilities of a scripting platform. In fact, the scripting engines for PHP and ASP are CGI applications themselves. As such, CGI" +
					" provides a way to run web applications requiring maximum performance and efficiency, and you will find it used on most enterprise-scale sites such as popular sites like eBay, Google, and Yahoo."+
					"<br><br>Due to the less restrictive nature of CGI applications, some web hosts (especially those offering cheaper packages) do not provide CGI support for security reasons." +
					" In addition to this, setting up and installing CGI applications can be more complex, especially if you have never installed one before.";
				
					//Enable platform choice
					document.getElementsByName("config_cgi_platform")[0].disabled = false;
					document.getElementsByName("config_cgi_platform")[1].disabled = false;
					document.getElementsByName("config_cgi_platform")[2].disabled = false;
					document.getElementsByName("config_cgi_platform")[3].disabled = false;
				
				}
				else if(OutputFormat == OUTPUT_ASPNET ) 
				{
						hintMsg = "ASP.NET is a web application framework from Microsoft. It is NOT the same as ASP (which is also referred to as \"Classic ASP\") and have very different requirements."+
						"<br><br>This option is generally only recommended for advanced web developers who are working on existing .NET websites and need to integrate the search engine into their .NET web application."+
						"<br><br>This option also requires a Zoom ASP.NET Native Control to be installed directly onto the web server. Please see Help or the Users Guide for more information.";
				
						alert("The ASP.NET option requires an ASP.NET native control to be installed on the web server first.\n\n" +
						      "Once the server-side control is installed, you will only need to upload the index files when you update your site and re-index. You do not need to re-install the native control until a new build is released.\n\n" +
  				         "You can download the ASP.NET native control from our website here: http://www.wrensoft.com/zoom/aspdotnet.html");
				
				}
				
				if(OutputFormat != OUTPUT_CGI)
				{
							//Disable platform choice
					document.getElementsByName("config_cgi_platform")[0].disabled = true;
					document.getElementsByName("config_cgi_platform")[1].disabled = true;
					document.getElementsByName("config_cgi_platform")[2].disabled = true;
					document.getElementsByName("config_cgi_platform")[3].disabled = true
				}
				
					break;
			}
		
		}

				document.getElementById("hints_and_tips_content").innerHTML = hintMsg;
	}
	
	//Hide last clicked section and show new one, if trackItem is true, track last change 
	//(used for menu items)
	function hideshow(element, trackItem)
	{

		//If displaying limits do some javascript checing of values and prevent navigation if limits exceeded
		if(LastElement != null && LastElement.id == "limits")
			if(OnLeaveLimits() == 0)
				return;
					
		//Hide last clicked section 
		if(trackItem == true && LastElement != null && LastElement != element)
		{
				if(LastElement.style.display=="block")
					LastElement.style.display="none"	
		}
			
		if (element.style.display=="block")
			element.style.display="none"
		else
			element.style.display="block"
			
			if(trackItem == true)
				LastElement = element;
			
		//If displaying the start point options, disable fields based on current indexing mode
		if(element.id == "config_startpoints")
		{
			if(document.getElementById("config_mode_spider").checked == false)
			{
				document.getElementsByName("config_sp_spideropts")[0].disabled = true; 
				document.getElementsByName("config_sp_uselimit")[0].disabled = true; 
				document.getElementsByName("config_sp_limit")[0].disabled = true; 
				document.getElementsByName("config_sp_weight")[0].disabled = true; 
			}
			else
			{
				document.getElementsByName("config_sp_spideropts")[0].disabled = false; 
				document.getElementsByName("config_sp_uselimit")[0].disabled = false; 
				document.getElementsByName("config_sp_limit")[0].disabled = false; 
				document.getElementsByName("config_sp_weight")[0].disabled = false; 	
			}
		}
		
	
	}

</script>
<style type="text/css">	
	.sectionImage
	{
	float: left;
	}	
</style>
<link rel="stylesheet" href="zoom.css" type="text/css">
</head>
<body onload="javascript:PageLoad()">

<?php	
//make sure $CurrentConfigPath is set to config for display 
include("./header.php");
?>


<?php

	
	//Check config file is not empty (eg reloading the page or going to config.php directly)
	if($cfgRedirectFailed)
	{
			echo "<div class=\"error_msg\">";	
			echo "<b>Unable to redirect, please use this link to return to the <a href=ZoomIndexer.php>Zoom user interface</a>. </b>"; 
			echo "</div>";	
			exit;	
	}

//If file wasn't loaded correcrtly display an error message (eg file not found)
//and stop loading other menu items and elements
if($cfgLoaded == false) 
{

	
	//Check config file is not empty (eg reloading the page or going to config.php directly)
	if(strlen($CurrentConfigPath) == 0)
	{
			echo "<div class=\"error_msg\">";	
			echo "<b>Error: no config file specified, please return to the <a href=ZoomIndexer.php>Zoom user interface</a>, enter a config file and use the 'Configure' button </b>"; 
			echo "</div>";		
	}
	else if(file_exists($CurrentConfigPath)) //Check if file exists, if yes likely a permissions error, if no 
	{
			echo "<div class=\"error_msg\">";	
			echo "<b>Error: Could not open configuration file please check permissions will allow reading and writing</b>"; 
			echo "</div>";		
	}
	else
	{
			echo "<div class=\"error_msg\">";	
			echo "<form id=\"mainform\" action=\"$SelfURL\" method=\"post\">";
			echo "<input type=\"hidden\" name=\"config_filename\" value=\"$CurrentConfigPath\" />";
			echo "<input type=\"hidden\" name=\"create_new\" value=\"1\" />";
			echo "<b>Error: Config file $CurrentConfigPath was not found.<br>Do you wish to create it?</b></br>"; 
			echo "<input type=\"submit\" value=\"Create new config\" name=\"submit\" >"; 
			echo "<input type=\"button\" value=\"Cancel\" name=\"discard\" onclick=\"window.location='ZoomIndexer.php?config=$CurrentConfigPath'\" >"; 
			echo "</div>";	
	}
	exit;
} 

if($cfgSaveFailed == true)
{
	echo "<div class=\"error_msg\">";	
	echo "<b>Error: Could not save configuration file please check it permissions are correct</b>"; 
	echo "</div>";		
}

?>

<div class="config_menu">	
	<table>
	<tr><td><a href="javascript:hideshow(document.getElementById('config_startoptions'), true)"><img src="./images/start_24.png"  class="sectionImage" alt="Start options">Start options</a></td></tr>
	<tr><td><a href="javascript:hideshow(document.getElementById('config_scanoptions'), true)"><img src="./images/scan_24.png" class="sectionImage" alt="Scan options">Scan options</a></td></tr>
	<tr><td><a href="javascript:hideshow(document.getElementById('config_skipptions'), true)"><img src="./images/skip_24.png" class="sectionImage"  alt="Skip options">Skip options</a></td></tr>
	<tr><td><a href="javascript:hideshow(document.getElementById('config_spiderptions'), true)"><img src="./images/spider_24.png" class="sectionImage"  alt="Spider option">Spider options</a></td></tr>
	<tr><td><a href="javascript:hideshow(document.getElementById('config_searchpage'), true)"><img src="./images/search_24.png" class="sectionImage"  alt="Search page">Search page</a></td></tr>
	<tr><td><a href="javascript:hideshow(document.getElementById('results_layout'), true)"><img src="./images/results_24.png" class="sectionImage"  alt="Results layout">Results layout</a></td></tr>
	<tr><td><a href="javascript:hideshow(document.getElementById('indexing_options'), true)"><img src="./images/index_preferences_24.png" class="sectionImage"  alt="Indexing options">Indexing options</a></td></tr>
	<tr><td><a href="javascript:hideshow(document.getElementById('limits'), true)"><img src="./images/limits_24.png" class="sectionImage"  alt="Limits">Limits</a></td></tr>
	<tr><td><a href="javascript:hideshow(document.getElementById('authentication'), true)"><img src="./images/authentication_24.png" class="sectionImage"  alt="Authentication">Authentication</a></td></tr>
	<tr><td><a href="javascript:hideshow(document.getElementById('autocomplete'), true)"><img src="./images/keyboard_key_24.png" class="sectionImage"  alt="Autocomplete">Autocomplete</a></td></tr>

	<!-- removed FTP section
	<tr><td><a href="javascript:hideshow(document.getElementById('ftp'))"><img src="./images/ftp_24.png" class="sectionImage"  alt="FTP">FTP</a></td></tr>
  -->
  <tr><td><a href="javascript:hideshow(document.getElementById('languages'), true)"><img src="./images/languages_24.png" class="sectionImage"  alt="Languages">Languages</a></td></tr>
  <tr><td><a href="javascript:hideshow(document.getElementById('weightings'), true)"><img src="./images/weightings_24.png" class="sectionImage"  alt="Weightings">Weightings</a></td></tr>
  <tr><td><a href="javascript:hideshow(document.getElementById('filtering'), true)"><img src="./images/filtering_24.png" class="sectionImage"  alt="Filtering">Filtering</a></td></tr>
  <tr><td><a href="javascript:hideshow(document.getElementById('categories'), true)"><img src="./images/categories_24.png" class="sectionImage"  alt="Categories">Categories</a></td></tr>
  <tr><td><a href="javascript:hideshow(document.getElementById('sitemaps'), true)"><img src="./images/sitemap_24.png" class="sectionImage"  alt="Sitemaps">Sitemaps</a></td></tr> 
  <tr><td><a href="javascript:hideshow(document.getElementById('synonyms'), true)"><img src="./images/synonyms_24.png" class="sectionImage"  alt="Synonyms">Synonyms</a></td></tr>
	<tr><td><a href="javascript:hideshow(document.getElementById('recommended'), true)"><img src="./images/recommended_24.png" class="sectionImage"  alt="Recommended">Recommended</a></td></tr> 
  <tr><td><a href="javascript:hideshow(document.getElementById('custommeta'), true)"><img src="./images/metafields_24.png" class="sectionImage"  alt="Custom meta fields">Custom meta fields</a></td></tr> 
  <tr><td><a href="javascript:hideshow(document.getElementById('indexlog'), true)"><img src="./images/log_24.png" class="sectionImage"  alt="Index log">Index log</a></td></tr> 
  <tr><td><a href="javascript:hideshow(document.getElementById('advanced'), true)"><img src="./images/advanced_24.png" class="sectionImage"  alt="Advanced">Advanced</a></td></tr>
  <!--<tr><td><a href="javascript:hideshow(document.getElementById('filetypes'), true)"><img src="./images/filetype_24.png" class="sectionImage"  alt="Filetype Options">Filetype Options</a></td></tr>-->
  </table> 
</div>

<?php

$RADIOBUTTON_CHECKEDSTR = "checked=\"checked\""; 
	$spiderModeStr = "";
	$offlineModeStr = "";
		
	if ($UConfig->currentMode == $MODE_SPIDER)
	{
		$spiderModeStr = $RADIOBUTTON_CHECKEDSTR;
		$StartLocation = $UConfig->spiderURL;
	}
	else
	{
		$offlineModeStr = $RADIOBUTTON_CHECKEDSTR;
		$StartLocation = $UConfig->startdir;
	}
	
	$scanNoExtsStr = "";
	$pluginsNewWindowStr = "";
	$crcSkipIdenticalStr = "";
	$skipUnderscoreStr = "";	
	$scanUnknownStr = "";
	
	if($UConfig->ScanNoExtension == 1)
		$scanNoExtsStr = $RADIOBUTTON_CHECKEDSTR;
	if($UConfig->ScanUnknownExtensions == 1)
		$scanUnknownStr = $RADIOBUTTON_CHECKEDSTR;
		
	if($UConfig->PluginOpenNewWindow == 1)
		$pluginsNewWindowStr = $RADIOBUTTON_CHECKEDSTR;
		
	if($UConfig->CRC32 == 1)
		$crcSkipIdenticalStr = $RADIOBUTTON_CHECKEDSTR;
						
	if($UConfig->SkipUnderscore == 1)
		$skipUnderscoreStr = $RADIOBUTTON_CHECKEDSTR;
			

	$phpStr = "";
	$aspStr = "";
	$cgiStr = "";
	$jsStr = "";
	$aspdotnetStr = "";
	$isDisabled = "disabled";
		
	if ($UConfig->OutputFormat == $OUTPUT_JSFILE)
		$jsStr = $RADIOBUTTON_CHECKEDSTR;
	else if ($UConfig->OutputFormat == $OUTPUT_CGI)
	{
	$cgiStr = $RADIOBUTTON_CHECKEDSTR;
		$isDisabled = "";
	}
	else if ($UConfig->OutputFormat == $OUTPUT_ASP)
		$aspStr = $RADIOBUTTON_CHECKEDSTR;
	else
		$phpStr = $RADIOBUTTON_CHECKEDSTR;
		
	$singleThreadStr = "";
	$multiThreadStr = "";
	$reloadCacheStr = "";

	if ($UConfig->NumDownloadThreads < 2)
		$singleThreadStr = $RADIOBUTTON_CHECKEDSTR;
	else
		$multiThreadStr = $RADIOBUTTON_CHECKEDSTR;
	if ($UConfig->NoCache == 1)
		$reloadCacheStr = $RADIOBUTTON_CHECKEDSTR;
	
	$aspnetStr = "";
	$winStr = "";
	$linStr = "";
	$bsdStr = "";
	$antStr = "";
	
	if($UConfig->IsASPDotNet == 1)
	{
		$aspnetStr = $RADIOBUTTON_CHECKEDSTR;
	}
	else
	{	
		
		if ($UConfig->OutputOS == $OS_WINDOWS)
			$winStr = $RADIOBUTTON_CHECKEDSTR;
		else if ($UConfig->OutputOS == $OS_LINUX)
			$linStr = $RADIOBUTTON_CHECKEDSTR;
		else if ($UConfig->OutputOS == $OS_BSD)
			$bsdStr = $RADIOBUTTON_CHECKEDSTR;
		else
			$antStr = $RADIOBUTTON_CHECKEDSTR; 
		
	}
		

//Start options output
echo<<<CONFIG_STARTOPTIONS
	<form id="mainform" action="$SelfURL" method="post" onsubmit="if(PreSubmit() == 0) return false;">

	<input type="hidden" name="config_filename" value="$CurrentConfigPath" />

<div id="config_startoptions" class="config_section">
<fieldset>
<legend><img src="./images/start_24.png" class="sectionImage" alt="Start options">Start options</legend>	
<a id="#config_start"></a>
		<div id="start_opts_main">
		<fieldset>
			<legend>Indexing mode</legend>	
			<div style="position:relative;">
			<img src="./images/spider_48.png" id="indexing_image" class="floatLeft">
			<input type="radio"  id="config_mode_spider"  name="config_mode" value="$MODE_SPIDER" $spiderModeStr onclick="ChangeIndexMethod(1);" />Spider mode<br>
			<input type="radio"  id="config_mode_offline"  name="config_mode" value="$MODE_OFFLINE" $offlineModeStr onclick="ChangeIndexMethod(1);"/>Offline mode<br>
			</div>
		</fieldset>
		<fieldset>
			<legend>Start spider from this URL:</legend>	
			<input type="text" style="width:100%;" name="config_starturl" value="$StartLocation"  onclick="ChangeIndexMethod(2);" oninput="UpdateBase()"/><br><input type="button" size="75" name="More" value="More" onclick="hideshow(document.getElementById('config_startpoints'), true)";/>		
		</fieldset>
		<fieldset>
			<legend>Base URL:</legend>	
			<input type="text" style="width:100%;" name="config_baseurl" value="$UConfig->baseURL" onclick="ChangeIndexMethod(3);" />
		</fieldset>
		<fieldset>
			<legend>Output directory:</legend>	
			<input type="text" style="width:100%;" name="config_outdir" value="$UConfig->outdir" onclick="ChangeIndexMethod(4);" />
		</fieldset>
		<fieldset>
			<legend>Platform</legend>	
			<input type="radio" name="config_platform" value="$OUTPUT_PHP" $phpStr onclick="ChangeIndexMethod(5);" />PHP<br>
			<input type="radio" name="config_platform" value="$OUTPUT_ASP" $aspStr onclick="ChangeIndexMethod(5);" />ASP<br>
			<input type="radio" name="config_platform" value="$OUTPUT_CGI" $cgiStr onclick="ChangeIndexMethod(5);" />CGI --> 
					<input type="radio" name="config_cgi_platform" value="$OS_WINDOWS" $winStr $isDisabled  /> Windows
					<input type="radio" name="config_cgi_platform" value="$OS_LINUX" $linStr $isDisabled  /> Linux
					<input type="radio" name="config_cgi_platform" value="$OS_BSD" $bsdStr $isDisabled /> BSD (Unix, FreeBSD)
					<input type="radio" name="config_cgi_platform" value="$OS_FLYINGANT" $antStr $isDisabled /> FlyingAnt Server
			<br>			
			<input type="radio" name="config_platform" value="$OUTPUT_JSFILE" $jsStr onclick="ChangeIndexMethod(5);" />JavaScript<br>
			<input type="radio" name="config_platform" value="$OUTPUT_ASPNET" $aspnetStr onclick="ChangeIndexMethod(5);" />ASP.NET<br>
		</fieldset>	
		<input type="button" class="help_button"  value="Help"/ onclick="OpenHelp('config_start')">
		</div>
		<div id="hints_and_tips" >
			<fieldset>
				<legend>Hints and Tips</legend>	
				<span id="hints_and_tips_content" ></span>
			</fieldset>	
		</div>
	</fieldset>

</div>
CONFIG_STARTOPTIONS;

echo<<<CONFIG_STARTPOINTS
<div id="config_startpoints" class="config_section">
<fieldset>
	<legend><img src="./images/start_point_add.png" class="sectionImage" alt="Advanced start point options">Advanced Start Point Options</legend>	
	<table>
<tr><td>Add more start points to index parts of your site which can not be reached by following links from the main URL.</td></tr>	

<tr><td colspan=5 ><select style="min-width:250px" multiple size=10  name="start_points_display[]" onchange="StartPointChange()">
CONFIG_STARTPOINTS;

//Need to check if in spider mode (starturl_list) or offline mode (startdir_list)
if ($UConfig->currentMode == $MODE_SPIDER)
{	
		//Also need to include the current default start point
	$url =  $UConfig->baseURL;						 	
	echo "<option VALUE=\"$url\">$url</option>\n";
	
foreach ($UConfig->starturl_list as $nextURL)
				{			
					$url =  $nextURL->url;						 	
					echo "<option VALUE=\"$url\">$url</option>\n";
				}
}
else
{
	
		//Also need to include the current default start point
	$url =  $UConfig->startdir;						 	
	echo "<option VALUE=\"$url\">$url</option>\n";
					
	foreach ($UConfig->startdir_list as $nextURL)
				{			
					$url =  $nextURL->url;
					echo "<option VALUE=\"$url\">$url</option>\n";
				}
}
echo<<<CONFIG_STARTPOINTS
</select></td></tr>
<tr><td> <input type="button" name="config_startpoints_remove" value="Remove" onClick="RemoveStartPoints(false)" />  <input type="button" name="config_startpoints_remove" value="Remove All" onClick="RemoveStartPoints(true)"/>
</table>
<fieldset>
<legend>Add/Edit Start Points</legend>	
<table>
<tr><td>Spider URL / Start Folder </td><td><input type="text" size="15" name="config_sp_start" oninput="UpdateSPBase()"></td></tr> 
<tr><td>Base URL </td><td><input type="text" size="15" name="config_sp_base"></td></tr> 
<tr><td>Spidering options </td><td><select name="config_sp_spideropts">
<option VALUE=$URLTYPE_INDEX_AND_FOLLOW	>Index page and follow internal* links (Default)</option>
<option VALUE=$URLTYPE_INDEX_AND_FOLLOW_ALL>Index page and follow internal* and external links</option>
<option VALUE=$URLTYPE_INDEX_ONLY>Index single page only</option>
<option VALUE=$URLTYPE_FOLLOW_ONLY>Follow links only</option>
<option VALUE=$URLTYPE_FOLLOW_ALL>Follow all links on this page only</option>
</select>
<tr><td><input type="checkbox" name="config_sp_uselimit" value="1"  />Limit files for this start point:</td><td> <input type="numberNew" name="config_sp_limit" value=""  size="5"  onkeypress="return onlyNumbers(event)" /></td></tr>
<tr><td>Weighting for this start point: </td><td><select name=config_sp_weight>
	<option value="0">-5 Deboost</option>
	<option value="1">-4 Deboost</option>
	<option value="2">-3 Deboost</option>
	<option value="3">-2 Deboost</option>
	<option value="4">-1 Deboost</option>
	<option value="5">Normal</option>
	<option value="6">+1 Boost</option>
	<option value="7">+2 Boost</option>
	<option value="8">+3 Boost</option>
	<option value="9">+4 Boost</option>
	<option value="10">+5 Boost</option>
</select>
</table>
<input type="button" value="Add" name="config_sp_add" onclick="AddEditStartPoint(false)" />
<input type="button" name="config_sp_edit" value="Update" onclick="AddEditStartPoint(true)" />
</fieldset>
<input type="button" size="75" value="Back to start options" onclick="hideshow(document.getElementById('config_startoptions'), true);" />
<input type="button" class="help_button"  value="Help" onclick="OpenHelp('config_startpoints')">
</fieldset>
</div>
CONFIG_STARTPOINTS;

	//Hidden fields for start points - load both offline and spider lists
	//Also need to include  the current default start point
		//Use 0 as index, otherwsie if they change the base URL becomes very difficult to keep updated
		
		echo "<input type=\"hidden\" name=\"start_point[0]\" value=\"$UConfig->spiderURL\">\n";
		echo "<input type=\"hidden\" name=\"start_point_off[0]\" value=\"$UConfig->startdir\">\n";
			
		echo "<input type=\"hidden\" name=\"starturl_uselimit[0]\" value=\"$UConfig->spiderURLUseLimit\">\n";
		echo "<input type=\"hidden\" name=\"starturl_limit[0]\" value=\"$UConfig->spiderURLLimit\">\n";
		echo "<input type=\"hidden\" name=\"starturl_boost[0]\" value=\"$UConfig->spiderURLBoost\">\n";
		echo "<input type=\"hidden\" name=\"starturl_type[0]\" value=\"$UConfig->spiderURLtype\">\n";
					
		
	if(!empty($UConfig->starturl_list))
	{
				foreach ($UConfig->starturl_list as $nextSP)
				{
					echo "<input type=\"hidden\" name=\"start_points_list\" value=\"$nextSP->url\">\n";
					echo "<input type=\"hidden\" name=\"start_points[$nextSP->url]\" value=\"$nextSP->url\">\n";
					echo "<input type=\"hidden\" name=\"starturl_base[$nextSP->url]\" value=\"$nextSP->baseURL\">\n";
					echo "<input type=\"hidden\" name=\"starturl_type[$nextSP->url]\" value=\"$nextSP->urltype\">\n";
					echo "<input type=\"hidden\" name=\"starturl_uselimit[$nextSP->url]\" value=\"$nextSP->uselimit\">\n";
					echo "<input type=\"hidden\" name=\"starturl_limit[$nextSP->url]\" value=\"$nextSP->limit\">\n";
					echo "<input type=\"hidden\" name=\"starturl_boost[$nextSP->url]\" value=\"$nextSP->boost\">\n";
					
				}
	}

	if(!empty($UConfig->startdir_list))
	{
				foreach ($UConfig->startdir_list as $nextSP)
				{
					
					echo "<input type=\"hidden\" name=\"start_points_off_list\" value=\"$nextSP->url\">\n";
					echo "<input type=\"hidden\" name=\"start_points_off[$nextSP->url]\" value=\"$nextSP->url\">\n";
					echo "<input type=\"hidden\" name=\"starturl_base_off[$nextSP->url]\" value=\"$nextSP->baseURL\">\n";

				}
	}

//Scan options output
echo<<<CONFIG_SCANOPTIONS
<div id="config_scanoptions" class="config_section">
<fieldset>
<legend><img src="./images/scan_24.png" class="sectionImage" alt="Scan options">Scan options</legend>	
<a id="#config_scan"></a>
		<input type="hidden" name="action" value="config_apply_scanoptions" />
		<fieldset>
			<legend>Duplicate Page Detection</legend>	
			<input type="checkbox" name="config_scan_duplicate" value="1" $crcSkipIdenticalStr >Use CRC to skip files with identical content<br>
		</fieldset>
				<fieldset>
			<legend>Plugin Formats</legend>	
			<input type="checkbox" name="config_scan_newwindow" value="1" $pluginsNewWindowStr >Open all plugin file formats (eg. PDF, DOC, etc) in a new window<br>
		</fieldset>
		<fieldset>
			<legend>Scan Extensions</legend>	
		<div id="extlist" style="float:left;">
		<table>
		<tr><td colspan=3>
		<select style="min-width:250px" size=10 name="config_scan_exts[]" multiple onchange="UpdateExtConfigure(false);" >
CONFIG_SCANOPTIONS;

		if(!empty($UConfig->ExtensionList))
		{
			foreach ($UConfig->ExtensionList as $nextExt)
			{
				$extStr = $nextExt->Ext ." | " . $FILETYPE_STRING[$nextExt->FileType];
				 echo "<option VALUE=\"$extStr\">$extStr</option>\n";
			}
		}
		echo "</select>";
		
		//Store thumbnail/image options
		
		if(!empty($UConfig->ExtensionList))
		{
			foreach ($UConfig->ExtensionList as $nextExt)
			{
				$value = "";
				$name = "ImageURL[" . $nextExt->Ext . "]";
				if(isset($nextExt->ImageURL))
					$value = $nextExt->ImageURL;
				else
					$value = "";
				echo "<input type=\"hidden\" name=\"$name\" value=\"$value\">";
				
				//Only saved as true or false so need to manipulate for radio buttons
				$name = "UseThumbs[" . $nextExt->Ext . "]";
				$value = "0";
				
				if(strlen($nextExt->ImageURL) > 1)
						$value = "1";
				else if(isset($nextExt->UseThumbs))
				{
					 if ($nextExt->UseThumbs)
						$value = "2";
				}
			
				echo "<input type=\"hidden\" name=\"$name\" value=\"$value\">";
				
				$name = "ThumbsPath[" . $nextExt->Ext . "]";
				if(isset($nextExt->ThumbsPath))
					$value = $nextExt->ThumbsPath;
				else
					$value = "";
					
				echo "<input type=\"hidden\" name=\"$name\" value=\"$value\">";
				
				$name = "ThumbsFilenamePrefix[" . $nextExt->Ext . "]";
				if(isset($nextExt->ThumbsFilenamePrefix))
					$value = $nextExt->ThumbsFilenamePrefix;
				else
					$value = "";
				echo "<input type=\"hidden\" name=\"$name\" value=\"$value\">";
				
				$name = "ThumbsFilenamePostfix[" . $nextExt->Ext . "]";
					if(isset($nextExt->ThumbsFilenamePostfix))
					$value = $nextExt->ThumbsFilenamePostfix;
				else
					$value = "";
				echo "<input type=\"hidden\" name=\"$name\" value=\"$value\">";
				
				$name = "ThumbsExt[" . $nextExt->Ext . "]";
				if(isset($nextExt->ThumbsExt))
					$value = $nextExt->ThumbsExt;
				else
					$value = "";
				echo "<input type=\"hidden\" name=\"$name\" value=\"$value\">\n";
				
			}
		}
		
echo<<<CONFIG_SCANOPTIONS

		</td></tr>
		<tr>
		<td colspan=3><input type="checkbox" name="config_scan_noext" value="1" $scanNoExtsStr>Scan files with no extension</td>		
		</tr>
		<tr>
		<td colspan=3><input type="checkbox" name="config_scan_unknown" value="1" $scanUnknownStr>Scan files with unknown extensions</td>		
		</tr>
		<tr>
		<td><input type="button" value="Add" name="add"  onclick="showHide('addext');"></td>
		<td><input type="button" value="Remove" name="remove"  onclick="DelScanExt()"></td>
		<td><input type="button" value="Configure file types" name="configure"  onclick="javascript:hideshow(document.getElementById('filetypes'), true)">
		<td><input type="button" value="Thumbnails" name="configthumbs"  onclick="showHide('conf_thumbs');">
	</tr>
	</table>
	</div>
	<div id="addext" style="display: none;">
		<fieldset>
		<legend>Add extension</legend>	
		<table>
		<tr><td>Add extension to scan: <input type="text" size="5" name="config_scan_add_ext" onblur="PickDefaultExtensionByFileType()"></td></tr>
		<tr><td>File type:
		<select style="min-width:50px" name="config_scan_add_exttype" > 
CONFIG_SCANOPTIONS;
			foreach ($FILETYPE_STRING as $nextType)
			{
				 echo "<option VALUE=\"$nextType\">$nextType</option>\n";
			}
echo<<<CONFIG_SCANOPTIONS
		</select></td></tr>
		<tr><td colspan=2>Note that filetype will be pre-selected for any recognized file extensions</td></tr>
		</table>
		<input type="button" value="Add" name="add" onclick="AddScanExt()">
		</fieldset>
	</div>
	<div id="conf_thumbs" style="clear: left; display: none;">
	<fieldset>
	<legend>Image and Thumbnail Configuration (select a scan extension above)</legend>	
	<div id="ext_configure">
	Here you can configure images to be displayed alongside the search results for files of this extension.
	You will need to enable 'Images' on the Results Layout tab for these images to appear in your search results.<br>
	Note that Zoom does not automatically generate thumbnails or icons. It assumes they have been pre-generated and will be located at the URLs determined based on the settings in this window.<br>
	<fieldset><legend>Image Option</legend><input type="radio" name="config_ext_conf_imageopt" value="0" onclick="ScanExtImageOptChange(this.value);" />No Image displayed next to result<br>	
	<input type="radio" name="config_ext_conf_imageopt" value="1" onclick="ScanExtImageOptChange(this.value)" />Display same icon for all files: Icon URL: <input type="text" size="15" name="config_ext_conf_iconurl" value="" /><br>
	<input type="radio" name="config_ext_conf_imageopt" value="2" onclick="ScanExtImageOptChange(this.value)" />Display different thumbnails for file (see "Thumbnail Options")</fieldset>
	<fieldset><legend>Thumbnail Options</legend>
	Associate files with thumbnail found at the following URL folder:<br>
	<input type="text" name="config_ext_conf_thumbfolder" value="" disabled="disabled" onchange="DetermineThumbnailURL();" /><br>
	Look for thumbnails with the same filename as the original file but with:<br>
	<input type="text" name="config_ext_conf_thumbbefore" size="5" value="" disabled="disabled"  onchange="DetermineThumbnailURL();" /> before the name and/or
	<input type="text" name="config_ext_conf_thumbafter" size="5" value="" disabled="disabled" onchange="DetermineThumbnailURL();" /> after the name.<br>
	Thumbnails will have the following file extension<input type="text" name="config_ext_conf_thumbext" size="5" value="" disabled="disabled" onchange="DetermineThumbnailURL();" /><br>
	Based on the above settings, when Zoom indexes a file at the following URL:<br>
	<input type="text" name="config_ext_conf_thumbexurl" size="25 value="" disabled="disabled" /><br>
	It will look for the thumbnail here:<br>
	<input type="text" name="config_ext_conf_thumbexloc" size="25" value="" disabled="disabled" />
	</fieldset>
	</div>
	</fieldset>
	</div>
	</fieldset>
	<input type="button" class="help_button"  value="Help"/ onclick="OpenHelp('config_scanoptions')">
	</fieldset>
</div>
CONFIG_SCANOPTIONS;

//Skip options output
echo<<<CONFIG_SKIPOPTIONS
<div id="config_skipptions" class="config_section">
<fieldset>
	<legend><img src="./images/skip_24.png" class="sectionImage" alt="Skip options">Skip options</legend>	
	<a id="#config_skip"></a>
	<div id="start_opts_main">
	<div id="page_skip"> 
		<fieldset>
			<legend>Page and folder skiplist</legend>	
			Pages and folders containing any of the following words<br>will not be scanned.<br>
			Each word must be on a new line
			<br><textarea style="min-width:250px" rows="20" cols="20"  name="config_skip_pagelist">
CONFIG_SKIPOPTIONS;
	foreach ($UConfig->SkipPages as $nextPage)
		{
			 echo "$nextPage\n";
		} 
echo<<<CONFIG_SKIPOPTIONS
			 </textarea>
			 <br><input type="checkbox" name="config_skip_underscore" value="1" $skipUnderscoreStr>Skip files or directories that begin with an underscore <br>(eg. "_notes") when indexing offline
		</fieldset>
		</div>
		<div id="word_skip"> 
		<fieldset>
		<legend>Word skiplist</legend>	
		The following words will be excluded from the index
		<Br>Each word must be on a new line
		<br><textarea style="min-width:250px" rows="20" cols="15" name="config_skip_wordlist">
CONFIG_SKIPOPTIONS;
		foreach ($UConfig->SkipWords as $nextWord)
		{
			 echo "$nextWord\n";
			 PrintDebug("Added: " . $nextWord);
		}
echo<<<CONFIG_SKIPOPTIONS
</textarea>
		<br>Skip words less than <input type="text" size="5" name="config_skip_minwordlen" value="$UConfig->MinWordLen" onkeypress="return onlyNumbers(event)"> characters
		</fieldset>
				<input type="button" class="help_button"  value="Help"/ onclick="OpenHelp('config_skipptions')">

		</div>
	</div>
	</fieldset>
	</div>
CONFIG_SKIPOPTIONS;

//Spider options
$useRobotsTxtStr = "";	
$parseJSLinksStr = "";
$scanFileLinksStr = "";
$checkThumbnailsExistStr = "";
$useLocalDescPathStr = "";
$useProxyStr = "";

if($UConfig->UseRobotsTxt == 1)
		$useRobotsTxtStr = $RADIOBUTTON_CHECKEDSTR;
if($UConfig->ParseJSLinks == 1)
		$parseJSLinksStr = $RADIOBUTTON_CHECKEDSTR;
if($UConfig->ScanFileLinks == 1)
		$scanFileLinksStr = $RADIOBUTTON_CHECKEDSTR;
if($UConfig->CheckThumbnailsExist == 1)
		$checkThumbnailsExistStr = $RADIOBUTTON_CHECKEDSTR;			
if($UConfig->UseLocalDescPath == 1)
		$useLocalDescPathStr = $RADIOBUTTON_CHECKEDSTR;			

if($UConfig->UseProxyServer == 1)
		$useProxyStr = $RADIOBUTTON_CHECKEDSTR;			

$ThrottleOpt1 = "";
$ThrottleOpt2 = "";
$ThrottleOpt3 = "";
$ThrottleOpt4 = "";
$ThrottleOpt5 = "";
$ThrottleOpt6 = "";
		
		
		
if($UConfig->ThrottleDelay == $THROTTLE_DELAY_VALUES[0])
	$ThrottleOpt1 = "selected=\"selected\"";
else if($UConfig->ThrottleDelay == $THROTTLE_DELAY_VALUES[1])
	$ThrottleOpt2 = "selected=\"selected\"";
else if($UConfig->ThrottleDelay == $THROTTLE_DELAY_VALUES[2])
	$ThrottleOpt3 = "selected=\"selected\"";
else if($UConfig->ThrottleDelay == $THROTTLE_DELAY_VALUES[3])
	$ThrottleOpt4 = "selected=\"selected\"";
else if($UConfig->ThrottleDelay == $THROTTLE_DELAY_VALUES[4])
	$ThrottleOpt5 = "selected=\"selected\"";
else if($UConfig->ThrottleDelay == $THROTTLE_DELAY_VALUES[5])
	$ThrottleOpt6 = "selected=\"selected\"";


echo<<<CONFIG_SPIDEROPTIONS
<div id="config_spiderptions" style="width: 800px; display: none;">
<fieldset>
<legend><img src="./images/spider_24.png" class="sectionImage" alt="Spider options">Spider options</legend>	
<a id="#config_spider"></a>
	<fieldset>
	<legend>Spider downloading options</legend>	
	<table>
	<tr><td><input type="radio" name="config_downloadthreads" value="single" $singleThreadStr />Single-threaded downloading</td></tr>
		<tr><td>This is slower but makes it easier to determine if the spider is finding all the files you need, and if not, why.</td></tr>
		<tr><td><input type="radio" name="config_downloadthreads" value="mutiple" $multiThreadStr />Mutiple threads <input type="number" name="config_numdlthreads" value="$UConfig->NumDownloadThreads" onkeypress="return onlyNumbers(event)" onblur="ValidateDLThreads()"> </td></tr>
		<tr><td>Increases the speed of the spider indexing by downloading multiple files at the same time.</td></tr>
		<tr><td><input type="checkbox" name="config_dontusecache" value="1" $reloadCacheStr />Reload all files (do not use cache)</td></tr>
	</table>
	</fieldset>
	
	<fieldset>
	<legend>Advanced spider mode options</legend>	
	<input type="checkbox" name="config_userobotstxt" value="1"  $useRobotsTxtStr >Enable "Robots.txt" support<br>
	<input type="checkbox" name="config_parsejslinks" value="1"  $parseJSLinksStr >Parse for links in JavaScript code (see Help)<br>
	<input type="checkbox" name="config_scanfilelinks" value="1"  $scanFileLinksStr >Scan files linked via "file://" URLs in spider mode<br>
	<input type="checkbox" name="config_checkthumbs" value="1"  $checkThumbnailsExistStr >Check thumbnails exists on website before using URL<br>
	<input type="checkbox" name="config_usedescfolder" value="1" 	$useLocalDescPathStr >Use this offline folder for all plugin .desc files<br>
	<input type="text" name="config_descfolder"  value="$UConfig->LocalDescPath">
	</fieldset>
	
	
	<fieldset>
	<legend>Spider throttling</legend>	
	 <select name="config_throttledelay">
   <option value="$THROTTLE_DELAY_VALUES[0]" $ThrottleOpt1>No delay between pages</option> 
   <option value="$THROTTLE_DELAY_VALUES[1]" $ThrottleOpt2>0.2 sec</option>
   <option value="$THROTTLE_DELAY_VALUES[2]" $ThrottleOpt3>0.5 sec</option>
   <option value="$THROTTLE_DELAY_VALUES[3]" $ThrottleOpt4>1 sec</option>
   <option value="$THROTTLE_DELAY_VALUES[4]" $ThrottleOpt5>5 secs</option>
   <option value="$THROTTLE_DELAY_VALUES[5]" $ThrottleOpt6>15 secs</option>
   </select>
	</fieldset>
	
	<fieldset>
	<legend>Proxy settings</legend>	
	<input type="checkbox" name="config_useproxy" value="1" $useProxyStr />Use proxy server: 	<input type="text" name="config_proxyserver"  value="$UConfig->ProxyServer">
	</fieldset>
	
	<input type="button" class="help_button"  value="Help"/ onclick="OpenHelp('config_spiderptions')">
	</fieldset>
	
	</div>

CONFIG_SPIDEROPTIONS;


//Search page 
$AppearanceOpt1 = "";
$AppearanceOpt2 = "";
$AppearanceOpt3 = "";

$DateSortOpt1 = "";
$DateSortOpt2 = "";
$DateSortOpt3 = "";

$DateRangeOpt1 = "";
$DateRangeOpt2 = "";
$DateRangeOpt3 = "";
		
if($UConfig->FormFormat == "0")
	$AppearanceOpt1 = "selected=\"selected\"";
else if($UConfig->FormFormat == "1")
	$AppearanceOpt2 = "selected=\"selected\"";
else if($UConfig->FormFormat == "2")
	$AppearanceOpt3 = "selected=\"selected\"";

if($UConfig->DefaultSort == "0")
	$DateSortOpt1 = "selected=\"selected\"";
else if($UConfig->DefaultSort == "1")
	$DateSortOpt2 = "selected=\"selected\"";
else if($UConfig->DefaultSort == "2")
	$DateSortOpt3 = "selected=\"selected\"";
	
if($UConfig->DateRangeFormat == "0")
	$DateRangeOpt1 = "selected=\"selected\"";
else if($UConfig->DateRangeFormat == "1")
	$DateRangeOpt2 = "selected=\"selected\"";
	
$curWindowStr = "";	
$newWindowStr = "";
$frameStr = "";
$frameWindow = "";
$dateRangeStr = "";

if(strlen($UConfig->LinkTarget) == 0)
		$curWindowStr =  $RADIOBUTTON_CHECKEDSTR ;
else if($UConfig->LinkTarget == "_blank")
		$newWindowStr = $RADIOBUTTON_CHECKEDSTR;
else
{
	$frameStr = $RADIOBUTTON_CHECKEDSTR;
	$frameWindow = $UConfig->LinkTarget;
}


if($UConfig->DateRangeSearch == true)
	$dateRangeStr  =  $RADIOBUTTON_CHECKEDSTR;


$zoomInfoStr = "";
$timingStr = "";
$matchAllStr = "";
$exactPhraseStr = "";
$useDateStr = "";
$spellingStr = "";
$domainDiversityStr  = "";

if($UConfig->Timing == true)
	$timingStr  = $RADIOBUTTON_CHECKEDSTR;
if($UConfig->ZoomInfo == true)
	$zoomInfoStr  = $RADIOBUTTON_CHECKEDSTR;
if($UConfig->DefaultToAnd == true)
	$matchAllStr  = $RADIOBUTTON_CHECKEDSTR;	
if($UConfig->AllowExactPhrase == true)
	$exactPhraseStr  = $RADIOBUTTON_CHECKEDSTR;	
if($UConfig->UseDateTime == true)
	$useDateStr  = $RADIOBUTTON_CHECKEDSTR;	
if($UConfig->Spelling == true)
	$spellingStr  = $RADIOBUTTON_CHECKEDSTR;	
if($UConfig->UseDomainDiversity == true)
	$domainDiversityStr  = $RADIOBUTTON_CHECKEDSTR;
	
	

echo<<<CONFIG_SEARCHPAGE
<div id="config_searchpage" style="width: 800px; display: none;">
<fieldset>
<legend><img src="./images/search_24.png" class="sectionImage" alt="Search Page">Search Page</legend>	
<a id="#config_searchpage"></a>
	<fieldset>
	<legend>Search Form Appearance</legend>	
	 <select name="config_searchformappearance">
    <option value="0" $AppearanceOpt1>Do not generate</option>
    <option value="1"	$AppearanceOpt2>Basic</option>
    <option value="2"	$AppearanceOpt3>Advanced</option>
   </select>
	</fieldset>
	<fieldset>
	<legend>Results linking</legend>	
<table>
<tr><td>Clicking on a result will open in:
	<tr><td><input type="radio" name="config_resultslinking" $curWindowStr value="current"  />Current window</td></tr>
		<tr><td><input type="radio" name="config_resultslinking" $newWindowStr value="new"  />New window</td></tr>
		<tr><td><input type="radio" name="config_resultslinking" $frameStr value="custom"  >Frame or window: <input type="text" size="15" name="config_resultsname" value="$frameWindow"> </td></tr>
	</table>
	</fieldset>
	<fieldset>
	<legend>Miscellaneous</legend>	
	<input type="checkbox" name="config_defaultmatchall" value="1" $matchAllStr >Default to "match all search words"<br>
	<input type="checkbox" name="config_showsearchtime" value="1" $timingStr >Show time taken to perform search<br>
	<input type="checkbox" name="config_showzoominfo" value="1" $zoomInfoStr >Show Zoom info line<br>
	</fieldset>
	<fieldset>
	<legend>Exact phrase (req. content indexing)</legend>	
	<input type="checkbox" name="config_allowexactphrase" value="1" $exactPhraseStr >Allow exact phrase searching when words are enclosed in double quotes<br>
	</fieldset>
	<fieldset>
	<legend>Search improvement options</legend>	
	<input type="checkbox" name="config_sortbydate" value="1" $useDateStr >Provide opion to "Sort results by date" (Default sort: 	 
	<select name="config_sortdatedef">
    <option value="0" $DateSortOpt1>Relevancy</option>
    <option value="1"	$DateSortOpt2>Sort by date (newest first)</option>
    <option value="2"	$DateSortOpt3>Sort by date (oldest first)</option>
   </select>)<br>
   	<input type="checkbox" name="config_daterangesearch" value="1" $dateRangeStr >Enable Date Range searching (Date format: 
		<select name="config_daterangeformat">
    <option value="0" $DateRangeOpt1>DD/MM/YYYY (English)</option>
    <option value="1"	$DateRangeOpt2>MM/DD/YYYY (American)</option>
   </select>
	)<br>
	<input type="checkbox" name="config_domaindiversity" value="1" $domainDiversityStr >Ensure domain name diversity in first 3 results<br>
	</fieldset>
	<fieldset>
	<legend>Spelling suggestions</legend>	
		<input type="checkbox" name="config_spellingsuggestion" value="1" $spellingStr >Provide spelling suggestions when less than <input type="number" size=5 name="config_spellingresults" value="$UConfig->SpellingWhenLessThan" onkeypress="return onlyNumbers(event)" onblur="CheckisNumGreaterThan0(this, this.value, 'Spelling suggestion threshold', 5)" > results found for a search term<br>
	</fieldset>
	</fieldset>
	<input type="button" class="help_button"  value="Help"/ onclick="OpenHelp('config_searchpage')">
	</fieldset>
</div>
CONFIG_SEARCHPAGE;

//Results layout
$ResultNumberStr = "";
$ResultTitleStr = "";
$ResultMetaDescStr = "";
$ResultContextStr = "";
$ResultTermsStr = "";
$ResultScoreStr = "";
$ResultDateStr = "";
$ResultURLStr = "";
$ResultFilesizeStr = "";
$HighLightingStr = "";
$GotoHighlightStr = "";
$UseZoomImageStr = "";

if($UConfig->ResultNumber == true)
	$ResultNumberStr  = $RADIOBUTTON_CHECKEDSTR;
if($UConfig->ResultTitle == true)
	$ResultTitleStr  = $RADIOBUTTON_CHECKEDSTR;
if($UConfig->ResultMetaDesc == true)
	$ResultMetaDescStr  = $RADIOBUTTON_CHECKEDSTR;	
if($UConfig->ResultContext == true)
	$ResultContextStr  = $RADIOBUTTON_CHECKEDSTR;	
if($UConfig->ResultTerms == true)
	$ResultTermsStr  = $RADIOBUTTON_CHECKEDSTR;	
if($UConfig->ResultScore == true)
	$ResultScoreStr  = $RADIOBUTTON_CHECKEDSTR;	
if($UConfig->ResultDate == true)
	$ResultDateStr  = $RADIOBUTTON_CHECKEDSTR;	
if($UConfig->ResultURL == true)
	$ResultURLStr  = $RADIOBUTTON_CHECKEDSTR;	
if($UConfig->ResultFilesize == true)
	$ResultFilesizeStr  = $RADIOBUTTON_CHECKEDSTR;	
if($UConfig->Highlighting == true)
	$HighLightingStr  = $RADIOBUTTON_CHECKEDSTR;	
if($UConfig->GotoHighlight == true)
	$GotoHighlightStr  = $RADIOBUTTON_CHECKEDSTR;	
if($UConfig->UseZoomImage == true)
	$UseZoomImageStr  = $RADIOBUTTON_CHECKEDSTR;	


echo<<<CONFIG_RESULTSLAYOUT
<div id="results_layout" style="width: 800px; display: none;">
<fieldset>
<legend><img src="./images/results_24.png" class="sectionImage" alt="Results Layout">Results Layout</legend>	
<a id="#results_layout"></a>
	<fieldset>
	<legend>Search results layout</legend>	
<table>
<tr><td colspan=2>Select what appears for each result. </td></tr>
		<tr><td><input type="checkbox" name="config_res_number" value="1" $ResultNumberStr onclick="javascript:GeneratePreview()" />Result Number</td>
		<td><input type="checkbox" name="config_res_terms" value="1" $ResultTermsStr onclick="javascript:GeneratePreview()" />Terms matched</td></tr>
		<tr><td><input type="checkbox" name="config_res_title" value="1" $ResultTitleStr onclick="javascript:GeneratePreview()" />Title of page</td>
		<td><input type="checkbox" name="config_res_score" value="1" $ResultScoreStr onclick="javascript:GeneratePreview()" />Score</td></tr>
		<tr><td><input type="checkbox" name="config_res_metadesciption" value="1" $ResultMetaDescStr onclick="javascript:GeneratePreview()" />Meta description</td>
		<td><input type="checkbox" name="config_res_date" value="1" $ResultDateStr onclick="javascript:GeneratePreview()" />Date</td></tr>
		<tr><td><input type="checkbox" name="config_res_image" value="1" $UseZoomImageStr onclick="javascript:GeneratePreview()" />Image</td>
		<td><input type="checkbox" name="config_res_filesize" value="1" $ResultFilesizeStr onclick="javascript:GeneratePreview()" />File size</td></tr>
		<tr><td><input type="checkbox" name="config_res_context" value="1" $ResultContextStr  onclick="javascript:GeneratePreview()" />Context description</td>
		<td><input type="checkbox" name="config_res_url" value="1" $ResultURLStr onclick="javascript:GeneratePreview()" />URL</td></tr>
		<tr><td colspan=2>Context size: <input type="text" name="config_res_contextsize"  value="$UConfig->ContextSize" onkeypress="return onlyNumbers(event)" onblur="CheckisNumGreaterThan0(this, this.value, 'context size', 30)" /></td></tr>
	</table>
	</fieldset>
	<fieldset>
	<legend>Highlighting Options</legend>	
	<input type="checkbox" name="config_highlight_matched" value="1" $HighLightingStr >Words matching in search results<br>
	<input type="checkbox" name="config_highlight_jumpto" value="1" $GotoHighlightStr >Jump to match and highlight within document<br>
	</fieldset>
	<!--
	<fieldset>
	<legend>Fonts and colours</legend>	
	<input type="button" name="config_modifytemplate" value="Modify Template" onclick="EditTemplate()" disabled="disabled">
	</fieldset>
	-->
	<fieldset>
	<legend>Preview (with default style)</legend>	
	<div id="preview" style="font-family:Times; font-size: 14px;">
	</div>
	</fieldset>
	<input type="button" class="help_button"  value="Help"/ onclick="OpenHelp('results_layout')">
	</fieldset>
</div>
CONFIG_RESULTSLAYOUT;

//Indexing options
$IndexMetaDescStr = "";
$IndexTitleStr = "";
$IndexContentStr = "";
$IndexKeywordsStr = "";
$IndexFilenameStr = "";
$IndexAuthorStr = "";
$IndexLinkTextStr = "";
$IndexAltTextStr = "";
$IndexDCMetaStr = "";
$IndexParamTagsStr = "";
$IndexURLDomainStr = "";
$IndexURLPathStr = "";

if($UConfig->IndexMetaDesc == true)
	$IndexMetaDescStr  = $RADIOBUTTON_CHECKEDSTR;
if($UConfig->IndexTitle == true)
	$IndexTitleStr  = $RADIOBUTTON_CHECKEDSTR;
if($UConfig->IndexContent == true)
	$IndexContentStr  = $RADIOBUTTON_CHECKEDSTR;	
if($UConfig->IndexKeywords == true)
	$IndexKeywordsStr  = $RADIOBUTTON_CHECKEDSTR;	
if($UConfig->IndexFilename == true)
	$IndexFilenameStr  = $RADIOBUTTON_CHECKEDSTR;	
if($UConfig->IndexAuthor == true)
	$IndexAuthorStr  = $RADIOBUTTON_CHECKEDSTR;	
if($UConfig->IndexLinkText == true)
	$IndexLinkTextStr  = $RADIOBUTTON_CHECKEDSTR;	
if($UConfig->IndexAltText == true)
	$IndexAltTextStr  = $RADIOBUTTON_CHECKEDSTR;	
if($UConfig->IndexDCMeta == true)
	$IndexDCMetaStr  = $RADIOBUTTON_CHECKEDSTR;	
if($UConfig->IndexParamTags == true)
	$IndexParamTagsStr  = $RADIOBUTTON_CHECKEDSTR;	
if($UConfig->IndexURLDomain == true)
	$IndexURLDomainStr  = $RADIOBUTTON_CHECKEDSTR;	
if($UConfig->IndexURLPath == true)
	$IndexURLPathStr  = $RADIOBUTTON_CHECKEDSTR;

//Seems @ no longer used?
$DotsStr = "";
$HyphensStr = "";
$UnderscoreStr = "";
$ApostropheStr = "";
$HashStr = "";
$DollarStr = "";
$CommaStr = "";
$ColonStr = "";
$AmpStr = "";
$SlashesStr = "";
$AtStr = "";

if(strpos ($UConfig->WordJoinChars, ".") !== false)
		$DotsStr = $RADIOBUTTON_CHECKEDSTR;
if(strpos ($UConfig->WordJoinChars, "-") !== false)
		$HyphensStr = $RADIOBUTTON_CHECKEDSTR;
if(strpos ($UConfig->WordJoinChars, "_") !== false)
		$UnderscoreStr = $RADIOBUTTON_CHECKEDSTR;
if(strpos ($UConfig->WordJoinChars, "'") !== false)
		$ApostropheStr = $RADIOBUTTON_CHECKEDSTR;
if(strpos ($UConfig->WordJoinChars, "#") !== false)
		$HashStr = $RADIOBUTTON_CHECKEDSTR;
if(strpos ($UConfig->WordJoinChars, "$") !== false)
		$DollarStr = $RADIOBUTTON_CHECKEDSTR;
if(strpos ($UConfig->WordJoinChars, ",") !== false)				
		$CommaStr = $RADIOBUTTON_CHECKEDSTR;
if(strpos ($UConfig->WordJoinChars, ":") !== false)	
		$ColonStr = $RADIOBUTTON_CHECKEDSTR;
if(strpos ($UConfig->WordJoinChars, "&") !== false)
		$AmpStr = $RADIOBUTTON_CHECKEDSTR;	
if(strpos ($UConfig->WordJoinChars, "\\") !== false || strpos ($UConfig->WordJoinChars, "/") !== false)
		$SlashesStr = $RADIOBUTTON_CHECKEDSTR;
if(strpos ($UConfig->WordJoinChars, "@") !== false)
		$AtStr = $RADIOBUTTON_CHECKEDSTR;

$RewriteStr = "";

if($UConfig->RewriteLinks == true)
		$RewriteStr = $RADIOBUTTON_CHECKEDSTR;

echo<<<CONFIG_INDEXINGOPTIONS
<div id="indexing_options" style="width: 800px; display: none;">
<fieldset>
<legend><img src="./images/index_preferences_24.png" class="sectionImage" alt="Indexing options">Indexing options</legend>	
<a id="#indexing_options"></a>
	<fieldset>
	<legend>What to index</legend>	
<table>
<tr><td>Select which parts of a page to include or exclude from indexing </td></tr>
		<tr><td><input type="checkbox" name="config_index_title" value="1" $IndexTitleStr />Title of page</td></tr>
		<tr><td><input type="checkbox" name="config_index_contexnt" value="1" $IndexContentStr />Page content</td></tr>
		<tr><td><input type="checkbox" name="config_index_metadescription" value="1" $IndexMetaDescStr />Meta description</td></tr>
		<tr><td><input type="checkbox" name="config_index_metakeywords" value="1" $IndexKeywordsStr />Meta keywords</td></tr>
		<tr><td><input type="checkbox" name="config_index_metaauthor" value="1" $IndexAuthorStr />Meta author</td></tr>
		<tr><td><input type="checkbox" name="config_index_filename" value="1" $IndexFilenameStr />Filename</td></tr>
		<tr><td><input type="checkbox" name="config_index_linktext" value="1" $IndexLinkTextStr />Link text</td></tr>
		<tr><td><input type="checkbox" name="config_index_alttext" value="1" $IndexAltTextStr />ALT text for image links</td></tr>
		<tr><td><input type="checkbox" name="config_index_dublincore" value="1" $IndexDCMetaStr />Dublin Core meta data</td></tr>
		<tr><td><input type="checkbox" name="config_index_paramtag" value="1" $IndexParamTagsStr />Param tag values</td></tr>
	</table>
	</fieldset>
	<fieldset>
	<legend>Indexing word rules</legend>	
<table>
<tr><td colspan=2>Allow the following characters to join words (eg. "a.b.c", "abc_def") </td></tr>
		<tr><td><input type="checkbox" name="config_join_dot" value="1" $DotsStr />Dots</td>
				<td><input type="checkbox" name="config_join_underscore" value="1" $UnderscoreStr />Underscore</td></tr>
		<tr><td><input type="checkbox" name="config_join_hyphen" value="1" $HyphensStr />Hyphens</td>
				<td><input type="checkbox" name="config_join_apostrophe" value="1" $ApostropheStr />Apostrophes</td></tr>
		<tr><td><input type="checkbox" name="config_join_hash" value="1" $HashStr />Hash sign</td>
				<td><input type="checkbox" name="config_join_comma" value="1" $CommaStr />Comma</td></tr>
		<tr><td><input type="checkbox" name="config_join_dollar" value="1" $DollarStr />Dollar sign</td>
				<td><input type="checkbox" name="config_join_colon" value="1" $ColonStr />Colon</td></tr>
		<tr><td><input type="checkbox" name="config_join_ampersand" value="1" $AmpStr />Ampersand</td>
				<td><input type="checkbox" name="config_join_slash" value="1" $SlashesStr />Slashes</td></tr>
	</table>
	</fieldset>
	<fieldset>
	<legend>Rewrite links</legend>	
	<input type="checkbox" name="config_rewriteurls" value="1" $RewriteStr onclick="RewriteLinkOptChange()" />Rewrite all URLs as follows:<br>
	Find in URL:  <input type="text" size="15" name="config_rewrite_find" value="$UConfig->RewriteFind">
	Replace with: <input type="text" size="15" name="config_rewrite_replace" value="$UConfig->RewriteWith">
	</fieldset>
	<input type="button" class="help_button"  value="Help"/ onclick="OpenHelp('indexing_options')">
	</fieldset>
</div>
CONFIG_INDEXINGOPTIONS;


//Limits
$OptimiseOpt1 = "";
$OptimiseOpt2 = "";
$OptimiseOpt3 = "";
$OptimiseOpt4 = "";
$OptimiseOpt5 = "";
$OptimiseOpt6 = "";
$OptimiseOpt7 = "";
$OptimiseOpt8 = "";
$OptimiseOpt9 = "";

if($UConfig->OptimizeSetting == "0")
	$OptimiseOpt1 = "selected=\"selected\"";
if($UConfig->OptimizeSetting == "1")
	$OptimiseOpt2 = "selected=\"selected\"";
if($UConfig->OptimizeSetting == "2")
	$OptimiseOpt3 = "selected=\"selected\"";
if($UConfig->OptimizeSetting == "3")
	$OptimiseOpt4 = "selected=\"selected\"";
if($UConfig->OptimizeSetting == "4")
	$OptimiseOpt5 = "selected=\"selected\"";
if($UConfig->OptimizeSetting == "5")
	$OptimiseOpt6 = "selected=\"selected\"";
if($UConfig->OptimizeSetting == "6")
	$OptimiseOpt7 = "selected=\"selected\"";
if($UConfig->OptimizeSetting == "7")
	$OptimiseOpt8 = "selected=\"selected\"";
if($UConfig->OptimizeSetting == "8")
	$OptimiseOpt9 = "selected=\"selected\"";

	

$TruncateURLStr = "";
$TruncateTitleStr = "";
$LimitWordsFileStr = "";
$LimitFilesSPStr = "";
$LimitUniqueStr = "";
$LimitURLsSPStr = "";

$enableUnique="disabled=\"disabled\"";
$enableSP="disabled=\"disabled\"";
$enableWords="disabled=\"disabled\"";
$enableTitle="disabled=\"disabled\"";
$enableURL="disabled=\"disabled\"";
$enableURLsSP ="disabled=\"disabled\"";

if($UConfig->TruncateShowURL == true)
{
	$TruncateURLStr = $RADIOBUTTON_CHECKEDSTR;
	$enableURL = "";
}
if($UConfig->TruncateTitleLen == true)
{
	$TruncateTitleStr = $RADIOBUTTON_CHECKEDSTR;	
	$enableTitle = "";
}
if($UConfig->LimitWordsPerPage == true)
{
	$LimitWordsFileStr = $RADIOBUTTON_CHECKEDSTR;	
	$enableWords = "";
}
if($UConfig->LimitPerStartPt == true)
{
	$LimitFilesSPStr = $RADIOBUTTON_CHECKEDSTR;	
	$enableSP = "";
}
if($UConfig->LimitMaxWords == true)
{
	$LimitUniqueStr = $RADIOBUTTON_CHECKEDSTR;	
	$enableUnique = "";
}

if($UConfig->LimitURLsPerStartPt == true)
{
	$LimitURLsSPStr = $RADIOBUTTON_CHECKEDSTR;	
	$enableURLsSP = "";
}


$MAX_FILE_SIZE = $UConfig->MAX_FILE_SIZE / 1024;


echo<<<CONFIG_LIMITS
<div id="limits" style="width: 800px; display: none;">
<fieldset>
<legend><img src="./images/limits_24.png" class="sectionImage" alt="Limits">Limits</legend>	
<a id="#limits"></a>
	<fieldset>
	<legend>Limits</legend>	
<table>
<tr><td colspan=2>These indexer limits can be changed in the professional edition (see our website for more information)</td></tr>
		<tr><td>Max files to index: </td><td><input type="text" size="15" name="config_limits_maxfiles" value="$UConfig->MAXPAGES" onkeyup="UpdateRAMUse()" onkeypress="return onlyNumbers(event)" onblur="CheckisNumGreaterThan0(this, this.value, 'Max files to index', 100)"></td></tr>
		<tr><td>Max file size indexed: </td><td><input type="text" size="10" name="config_limits_maxsize" value="$MAX_FILE_SIZE" onkeyup="UpdateFileSize();UpdateRAMUse();" onkeypress="return onlyNumbers(event)" onblur="CheckisNumGreaterThan0(this, this.value, 'Max file size indexed', 1048576)"> KB (<span id="file_size_mb">1.00 MB</span>)</td></tr>
		<tr><td>Max description length: </td><td><input type="text" size="10" name="config_limits_maxdescription" value="$UConfig->DESCLENGTH"  onkeyup="UpdateRAMUse()" onkeypress="return onlyNumbers(event)" onblur="CheckisNumGreaterThan0(this, this.value, 'Max description length', 150)">characters</td></tr>
		<tr><td>Max results per query: </td><td><input type="text" size="10" name="config_limits_maxquery" value="$UConfig->MaxResultsPerQuery" onkeypress="return onlyNumbers(event)" onblur="CheckisNumGreaterThan0(this, this.value, 'Max results per query', 1000)"></td></tr>
		<tr><td>Max word length: </td><td><input type="text" size="10" name="config_limits_maxwordlength" value="$UConfig->MAXWORDLENGTH" onkeypress="return onlyNumbers(event)" onblur="CheckisNumGreaterThan0(this, this.value, 'Max word length', 35)">characters</td></tr>
	
		<tr><td><input type="checkbox" name="config_limits_max_unique" value="1" $LimitUniqueStr onclick="ToggleChecboxValue('config_limits_max_unique', 'config_limits_unique');" />Limit max unique words: </td><td><input type="text" size="15" name="config_limits_unique" value="$UConfig->MAXWORDS" onkeyup="UpdateRAMUse()" onkeypress="return onlyNumbers(event)" onblur="CheckisNumGreaterThan0(this, this.value, 'Max unique words', 30000)" $enableUnique ></td></tr>
		<tr><td><input type="checkbox" name="config_limits_files_per_sp" value="1" $LimitFilesSPStr onclick="ToggleChecboxValue('config_limits_files_per_sp', 'config_limits_per_sp_val');"/>Limit files per start point</td><td><input type="text" size="10" name="config_limits_per_sp_val" value="$UConfig->MAXPAGES_PER_STARTPT" onkeypress="return onlyNumbers(event)" onblur="CheckisNumGreaterThan0(this, this.value, 'start point file limit', 100)" $enableSP ></td></tr>
		<tr><td><input type="checkbox" name="config_limits_URLs_per_sp" value="1" $LimitURLsSPStr onclick="ToggleChecboxValue('config_limits_URLs_per_sp', 'config_limits_URLs_per_sp_val');" />Limit URLs visited per start point</td><td><input type="text" size="10" name="config_limits_URLs_per_sp_val" value="$UConfig->MAXURLVISITS_PER_STARTPT" onkeypress="return onlyNumbers(event)" onblur="CheckisNumGreaterThan0(this, this.value, 'URLs per start point', 1000)" $enableURLsSP ></td></tr>
		<tr><td><input type="checkbox" name="config_limits_words_per_file" value="1" $LimitWordsFileStr onclick="ToggleChecboxValue('config_limits_words_per_file', 'config_limits_per_file_val');" />Limit words per file</td><td><input type="text" size="10" name="config_limits_per_file_val" value="$UConfig->MAXWORDS_PER_PAGE" onkeypress="return onlyNumbers(event)" onblur="CheckisNumGreaterThan0(this, this.value, 'words per file limit', 30000)" $enableWords ></td></tr>
		<tr><td><input type="checkbox" name="config_limits_truncate_titles" value="1" $TruncateTitleStr onclick="ToggleChecboxValue('config_limits_truncate_titles', 'config_limits_truncate_titles_val');" />Truncate titles longer than</td><td><input type="text" size="10" name="config_limits_truncate_titles_val" value="$UConfig->MAXTITLELENGTH" onkeypress="return onlyNumbers(event)" onblur="CheckisNumGreaterThan0(this, this.value, 'title length limit', 512)" $enableTitle >characters</td></tr>
		<tr><td><input type="checkbox" name="config_limits_truncate_URLS" value="1" $TruncateURLStr onclick="ToggleChecboxValue('config_limits_truncate_URLS', 'config_limits_truncate_URLS_val');" />Truncate URLs on display to</td><td><input type="text" size="10" name="config_limits_truncate_URLS_val" value="$UConfig->ShowURLLength" onkeypress="return onlyNumbers(event)" onblur="CheckisNumGreaterThan0(this, this.value, 'URL length limit', 2000)" $enableURL >characters</td></tr>
	</table>
	</fieldset>
	<fieldset>
	<legend>Optimization</legend>	
	Optimize search behaviour for large sites.
	<table>
		<tr><td>
	<select name="config_optimization" onclick="UpdateOpt();"  onkeyup="UpdateOpt();" >
   <option value="0" $OptimiseOpt1 />Fastest Search (Least accurate)</option>
	 <option value="1" $OptimiseOpt2 />1</option>
	 <option value="2" $OptimiseOpt3 />2</option>
	 <option value="3" $OptimiseOpt4 />3</option>
	 <option value="4" $OptimiseOpt5 />Default</option>
	 <option value="5" $OptimiseOpt6 />5</option>
	 <option value="6" $OptimiseOpt7 />6</option>
	 <option value="7" $OptimiseOpt8 />7</option>
	 <option value="8" $OptimiseOpt9 />Slowest Search (Most accurate)</option>
   </select></td></tr>
	<tr><td><textarea rows="4" cols="30" readonly name=optimization_settings></textarea></td></tr>
	</table>
	</fieldset>
<div id="optimization_ramest"></div>
<input type="button" class="help_button"  value="Help"/ onclick="OpenHelp('limits')">
</fieldset>
</div>
CONFIG_LIMITS;


//Authentication
$UseHTTPAuthStr = "";
$UseCookieLoginStr = "";
$UseIECookiesStr = "";

if($UConfig->UseCookieLogin == true)
	$UseCookieLoginStr = $RADIOBUTTON_CHECKEDSTR;
if($UConfig->UseCookies == true)
	$UseIECookiesStr = $RADIOBUTTON_CHECKEDSTR;
if($UConfig->UseAuth == true)
	$UseHTTPAuthStr = $RADIOBUTTON_CHECKEDSTR;

echo<<<CONFIG_AUTHENTICATION
<div id="authentication" style="width: 800px; display: none;">
<fieldset>
<legend><img src="./images/authentication_24.png" class="sectionImage" alt="Autentication">Authentication</legend>	
<a id="#authentication"></a>
<fieldset>
	<legend>HTTP authentication</legend>	
	<table>
	<tr><td colspan=2><input type="checkbox" name="config_http_authenticate" value="1" $UseHTTPAuthStr  onclick="EnableHTTPAuth()" />Enable HTTP authentication</td></tr>
	<tr><td colspan=2>Enter authentication details here to spider a site requiring authentication</td></tr>
	<tr><td>Login:</td><td><input type="text" name="config_http_login" value="$UConfig->Login" size="25" /></td></tr>
	<tr><td>Password:</td><td><input type="password" name="config_http_pwd" value="$UConfig->Password" size="25" /></td></tr>
	<tr><td colspan=2>HTTP authentication typically appears as a special login window when accessed from a browser, unlike cookie-based authentication which appears as a web page.</td></tr>
	</table>
</fieldset>
<fieldset>
	<legend>Cookie-based authentication</legend>	
	<table>
	<tr><td colspan=2><input type="checkbox" name="config_cookies_fromie" value="1" $UseIECookiesStr   >Use cookies</td></tr>
	<tr><td colspan=2>This allows the spider to use cookies to login and out of webpages</td></tr>
	<tr><td colspan=2><input type="checkbox" name="config_cookies_autologin" value="1"  $UseCookieLoginStr onclick="EnableCookieAuth()" />Automatic login on following page (URL):</td></tr>
	<tr><td colspan=2><input type="text" name="config_cookies_loginpage"  size="25"  value="$UConfig->CookieLoginURL" /></td></tr>
	<tr><td>Login variable name:</td><td><input type="text" name="config_cookies_loginvariable" size="25" value="$UConfig->CookieLoginName" /></td></tr>
	<tr><td>Your login:</td><td><input type="text" name="config_cookies_loginname"  size="25"  value="$UConfig->CookieLoginValue"/></td></tr>
	<tr><td>Password variable name:</td><td><input type="text" name="config_cookies_passwordvariable"  size="25" value="$UConfig->CookiePasswordName" /></td></tr>
	<tr><td>Your password:</td><td><input type="password" name="config_cookies_password"  size="25" value="$UConfig->CookiePasswordValue" /></td></tr>
	<tr><td colspan=2>Note that automatic login may not work on sites or forums with anti-spider/bots mechanisms</td></tr>
</table>
</fieldset>
<input type="button" class="help_button"  value="Help"/ onclick="OpenHelp('authentication')">
</fieldset>
</div>
CONFIG_AUTHENTICATION;

$UseAutocomplete = "";
$UseStats = "";

if($UConfig->UseAutoComplete  == true)
	$UseAutocomplete = $RADIOBUTTON_CHECKEDSTR;

if($UConfig->UseAutoCompleteInclude == true)
	$UseStats = $RADIOBUTTON_CHECKEDSTR;

$Checkbox_AutocompleteUsePageTitle = "";
$Checkbox_AutocompleteUseMetaDesc = "";
if ($UConfig->AutoCompleteUsePageTitle == 1)
	$Checkbox_AutocompleteUsePageTitle = $RADIOBUTTON_CHECKEDSTR;
if ($UConfig->AutoCompleteUseMetaDesc == 1)
	$Checkbox_AutocompleteUseMetaDesc = $RADIOBUTTON_CHECKEDSTR;

echo<<<CONFIG_AUTOCOMPLETE
<div id="autocomplete" style="width: 800px; display: none;">
<fieldset>
<legend><img src="./images/keyboard_key_24.png" class="sectionImage" alt="Autocomplete">Autocomplete</legend>	
	<input type="checkbox" name="config_autocomplete_use" value="1" $UseAutocomplete >Enable Autocomplete<br>
	Autocomplete will suggest the following queries when a user starts typing in the search box.<br>
	<br>
	Extract words from the following:<br>
	<input type="checkbox" name="config_autocomplete_usepagetitle" value="1" $Checkbox_AutocompleteUsePageTitle >Page title<br>
	<input type="checkbox" name="config_autocomplete_usemetadesc" value="1" $Checkbox_AutocompleteUseMetaDesc >Meta keywords, description, etc.<br>	
		<br>Enter additional custom autocomplete suggestions here:<br>
		<textarea style="min-width:250px" rows="20" cols="20"  name="config_autocomplete_list">
CONFIG_AUTOCOMPLETE;

		foreach ($UConfig->AutoCompleteRules as $nextRule)
		{
				 echo "$nextRule\n";
		} 
		
echo<<<CONFIG_AUTOCOMPLETE
	</textarea>
	<br><input type="checkbox" name="config_autocomplete_usetop" value="1"/ $UseStats >Include top
	<input type="text" size="5" name="config_autocomplete_topnum" value="$UConfig->AutoCompleteIncludeTopNum" onkeypress="return onlyNumbers(event)" onblur="CheckisNumGreaterThan0(this, this.value, 'Most popular search items', 500)">
	most popular search terms from Statistics Log file at:<br>
	<input type="text" size="25" name="config_autocomplete_url" value="$UConfig->AutoCompleteIncludeURL"><br>
	The above can be a local downloaded copy on your hard disk or an online http:// URL.
	<br>
<input type="button" class="help_button"  value="Help"/ onclick="OpenHelp('autocomplete')">
</fieldset>
</div>
CONFIG_AUTOCOMPLETE;

/*
<!-- removed FTP section
echo<<<CONFIG_FTP
<div id="ftp" style="width: 800px; display: none;">
<fieldset>
<legend><img src="./images/ftp_24.png" class="sectionImage" alt="FTP">FTP</legend>	
<a id="#ftp"></a>
	<fieldset>
	<legend>Connect to...</legend>	
<table>
<tr><td>FTP Server:</td><td>Port:</td></tr>
<tr><td><input type="text" name="config_ftp_server" value="1" size="25" /></td><td><input type="number" name="config_ftp_port" /></td></tr>
</table>
</fieldset>
	<fieldset>
	<legend>Login</legend>	
<table>
<tr><td colspan=2>Specify login to connect to ftp server</td></tr>
<tr><td>Username:</td><td><input type="text" name="config_ftp_username" size="25" /></td></tr>
<tr><td>Password:</td><td><input type="text" name="config_ftp_pwd" size="25" /></td></tr>
</table>
</fieldset>
	<fieldset>
	<legend>Folder or path on server (eg. "public_html/search")</legend>	
<table>
<tr><td colspan=2>Upload files to the following folder on the server</td></tr>
<tr><td>Remote path:</td><td><input type="text" name="config_ftp_remotepath" size="25" /></td></tr>
</table>
</fieldset>
	<fieldset>
	<legend>FTP Options</legend>	
	<table>
		<tr><td><input type="checkbox" name="config_ftp_autoupload" value="1"  />Automatically upload files at end of indexing</td></tr>
		<tr><td><input type="checkbox" name="config_ftp_nosearchtemplate" value="1"  />Do not upload search template (requires existing file on server)</td></tr>
		<tr><td><input type="checkbox" name="config_ftp_uploadtmp" value="1"  />Upload with .tmp filenames and rename when completed</td></tr>
		<tr><td><input type="checkbox" name="config_ftp_setexecute" value="1"  />Set execute files permisions after uploading (required for CGI)</td></tr>
</table>
</fieldset>
</fieldset>
</div>
CONFIG_FTP;
*/	

//Langauges section
$MapAccentsStr = "";
$MapAccentCharsStr = "";
$MapLigaturesStr = "";
$MapUmlautsStr = "";
$MapAccDiarapsStr = "";
$UseUTF8Str = "";
$UseOtherEncStr = "";
$StripDiacriticsStr = "";
$SubStringSearchStr = "";
$DisableToLowerStr = "";
$UseStemmingStr = "";
$LatinLigStr = "";

if($UConfig->MapAccents == true)
	$MapAccentsStr = $RADIOBUTTON_CHECKEDSTR;
if($UConfig->MapAccentChars == true)
	$MapAccentCharsStr = $RADIOBUTTON_CHECKEDSTR;
if($UConfig->MapLigatureChars == true)
	$MapLigaturesStr = $RADIOBUTTON_CHECKEDSTR;
if($UConfig->MapUmlautChars == true)
	$MapUmlautsStr = $RADIOBUTTON_CHECKEDSTR;
if($UConfig->MapAccentsToDigraphs == true)
	$MapAccDiarapsStr = $RADIOBUTTON_CHECKEDSTR;
if($UConfig->StripDiacritics == true)
	$StripDiacriticsStr = $RADIOBUTTON_CHECKEDSTR;
if($UConfig->SearchAsSubstring == true)
	$SubStringSearchStr = $RADIOBUTTON_CHECKEDSTR;
if($UConfig->DisableToLower == true)
	$DisableToLowerStr = $RADIOBUTTON_CHECKEDSTR;
if($UConfig->UseStemming == true)
	$UseStemmingStr = $RADIOBUTTON_CHECKEDSTR;
	
if($UConfig->MapLatinLigatureChars == true)
	$LatinLigStr = $RADIOBUTTON_CHECKEDSTR; 
	
if($UConfig->UseUTF8 == true)
	$UseUTF8Str = $RADIOBUTTON_CHECKEDSTR;
else
	$UseOtherEncStr = $RADIOBUTTON_CHECKEDSTR;
		
echo<<<CONFIG_LANGUAGES
<div id="languages" style="width: 800px; display: none;">
<fieldset>
<legend><img src="./images/languages_24.png" class="sectionImage" alt="Languages">Languages</legend>	
<a id="#languages"></a>
	<fieldset>
	<legend>Encoding and character sets</legend>	
<table>
<tr><td>Select the appropriate charset for your website. Make sure it matches the meta charset in your pages.</td></tr>	
<tr><td><input type="radio" name="config_languages_encoding" value="1" $UseUTF8Str />Use Unicode (UTF-8 encoding)</td></tr>
<tr><td><input type="radio" name="config_languages_encoding" value="0" $UseOtherEncStr />Specify other encoding</td></tr>
<tr><td><select name="config_languages_encoding_picker">
CONFIG_LANGUAGES;

foreach($CodePageName  as $codepage => $name)
		{
			$CodepageIDStr = $CodePageString[$codepage];
			if($UConfig->Codepage == $codepage)
				echo  "<option value=\"$codepage\" selected=\"selected\"> $CodepageIDStr ($name) </option>";	
			else
				echo  "<option value=\"$codepage\" > $CodepageIDStr ($name) </option>";	
		}

echo<<<CONFIG_LANGUAGES
</select>
</td></tr>
</table>
</fieldset>
	<fieldset>
	<legend>Search page layout</legend>	
	Select the language file for the text that appears on the search page.<br>You can also customize the text file and create your own
	<select name="config_languages_zlang">
CONFIG_LANGUAGES;
	
	foreach($ZlangFiles as $zlang)
		{
			if($UConfig->LanguageFile == $zlang)
				echo  "<option value=\"$zlang\" selected=\"selected\">$zlang</option>";	
			else
				echo  "<option value=\"$zlang\">$zlang</option>";	
		}

	
echo<<<CONFIG_LANGUAGES
</select>
</fieldset>
	<fieldset>
	<legend>International searching options</legend>	
<table>
<tr><td colspan=4><input type="checkbox" name="config_language_acdc" value="1" $MapAccentsStr onclick="ACDCOptChange();" />Enable accent/diacritic insensitivity for:</td></tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="checkbox" name="config_language_accents" value="1" $MapAccentCharsStr />Accents</td>
			<td><input type="checkbox" name="config_language_umlauts" value="1" $MapUmlautsStr />Umlauts</td>
			<td><input type="checkbox" name="config_language_ligatures" value="1" $MapLigaturesStr />Ligatures</td>
			</tr>
		<tr><td colspan=4><input type="checkbox" name="config_language_digraphs" value="1" $MapAccDiarapsStr />Use digraphs for umlauts ("&ouml;" = "oe" etc)</td></tr>
			<tr><td colspan=4><input type="checkbox" name="config_language_latinlig" value="1" $LatinLigStr />Support latin ligatures</td></tr> 
		<tr><td colspan=4><input type="checkbox" name="config_language_singlecase" value="1" $DisableToLowerStr />Support single-case languages (eg asian languages)</td></tr>
		<tr><td colspan=4><input type="checkbox" name="config_language_substringall" value="1" $SubStringSearchStr />Substring match for all searches</td></tr>
		<tr><td colspan=4><input type="checkbox" name="config_language_striparabic" value="1" $StripDiacriticsStr />Strip Arabic diacritic marks from words</td></tr>
</table>
</fieldset>
	<fieldset>
	<legend>Stemming</legend>	
	Stemming allows search results to match words which are similar or derived from your search word. For example, the word
	"fish" will match "fishes", "fishing", "fished" etc.<br>
		<input type="checkbox" name="config_language_stemming" value="1" $UseStemmingStr onclick="StemmingOptChange();" />Enable stemming for:
		<select name="config_languages_stem_lang">
CONFIG_LANGUAGES;

$i = 0;
foreach($STEMMER_LANGUAGES as $StemLang)
		{
			if($UConfig->StemmingLanguageIndex  == $i)
				echo  "<option value=\"$i\" selected=\"selected\"> $StemLang </option>";		
			else
				echo  "<option value=\"$i\" > $StemLang </option>";		
			$i++;
		}
		
echo<<<CONFIG_LANGUAGES
	</select>
 (English only for PHP/ASP)
</fieldset>
<input type="button" class="help_button"  value="Help"/ onclick="OpenHelp('languages')">
</fieldset>
</div>
CONFIG_LANGUAGES;

//Weightings section
$DisableToLowerStr = "";
$UseStemmingStr = "";

//Doing it this way saves a lot of code though it looks a bit unweildly
$TitleWeightOptStrs = array(0 => "", 1 => "", 2 => "", 3 => "", 4 => "", 5 => "", 6 => "", 7 => "", 8 => "", 9 => "", 10 => "");
$DescWeightOptStrs =  array(0 => "", 1 => "", 2 => "", 3 => "", 4 => "", 5 => "", 6 => "", 7 => "", 8 => "", 9 => "", 10 => "");
$KeywordWeightOptStrs =  array(0 => "", 1 => "", 2 => "", 3 => "", 4 => "", 5 => "", 6 => "", 7 => "", 8 => "", 9 => "", 10 => "");
$FilenameWeightOptStrs =  array(0 => "", 1 => "", 2 => "", 3 => "", 4 => "", 5 => "", 6 => "", 7 => "", 8 => "", 9 => "", 10 => "");
$HeadingWeightOptStrs =  array(0 => "", 1 => "", 2 => "", 3 => "", 4 => "", 5 => "", 6 => "", 7 => "", 8 => "", 9 => "", 10 => "");
$LinkWeightOptStrs =  array(0 => "", 1 => "", 2 => "", 3 => "", 4 => "", 5 => "", 6 => "", 7 => "", 8 => "", 9 => "", 10 => "");
$BodyWeightOptStrs =  array(0 => "", 1 => "", 2 => "", 3 => "", 4 => "", 5 => "", 6 => "", 7 => "", 8 => "", 9 => "", 10 => "");
$ProximityOpts =  array(0 => "", 1 => "", 2 => "", 3 => "");
$DensityOpts =  array(0 => "", 1 => "", 2 => "", 3 => "");
$ShortURLOpts =  array(0 => "", 1 => "", 2 => "", 3 => "");

$TitleWeightOptStrs[$UConfig->WeightTitle + $WORDWEIGHT_NORMAL] = "selected=\"selected\"";
$DescWeightOptStrs[$UConfig->WeightDesc + $WORDWEIGHT_NORMAL] = "selected=\"selected\"";
$KeywordWeightOptStrs[$UConfig->WeightKeywords + $WORDWEIGHT_NORMAL] = "selected=\"selected\"";
$FilenameWeightOptStrs[$UConfig->WeightFilename + $WORDWEIGHT_NORMAL] = "selected=\"selected\"";
$HeadingWeightOptStrs[$UConfig->WeightHeadings + $WORDWEIGHT_NORMAL] = "selected=\"selected\"";
$LinkWeightOptStrs[$UConfig->WeightLinktext + $WORDWEIGHT_NORMAL] = "selected=\"selected\"";
$BodyWeightOptStrs[$UConfig->WeightContent + $WORDWEIGHT_NORMAL] = "selected=\"selected\"";
$ProximityOpts[$UConfig->WeightProximity] = "selected=\"selected\"";
$DensityOpts[$UConfig->WeightDensity] = "selected=\"selected\"";
$ShortURLOpts[$UConfig->WeightShortURLs] = "selected=\"selected\"";


echo<<<CONFIG_WEIGHTINGS
<div id="weightings" style="width: 800px; display: none;">
<fieldset>
<legend><img src="./images/weightings_24.png" class="sectionImage" alt="Weightings">Weightings</legend>	
<a id="#weightings"></a>
	<fieldset>
	<legend>Word weighting</legend>	
<table>
<tr><td colspan=2>Adjust the weighting of words indexed depending on where they were found.</td></tr>	
<tr><td>Page title</td><td>
<select name="config_weighting_title">
	<option value="0" $TitleWeightOptStrs[0]>-5 Deboost</option>
	<option value="1" $TitleWeightOptStrs[1]>-4 Deboost</option>
	<option value="2" $TitleWeightOptStrs[2]>-3 Deboost</option>
	<option value="3" $TitleWeightOptStrs[3]>-2 Deboost</option>
	<option value="4" $TitleWeightOptStrs[4]>-1 Deboost</option>
	<option value="5" $TitleWeightOptStrs[5]>Normal</option>
	<option value="6" $TitleWeightOptStrs[6]>+1 Boost</option>
	<option value="7" $TitleWeightOptStrs[7]>+2 Boost</option>
	<option value="8" $TitleWeightOptStrs[8]>+3 Boost</option>
	<option value="9" $TitleWeightOptStrs[9]>+4 Boost</option>
	<option value="10" $TitleWeightOptStrs[10]>+5 Boost</option>
</select></td></tr>
<tr><td>Description</td><td>
<select name="config_weighting_description">
	<option value="0" $DescWeightOptStrs[0]>-5 Deboost</option>
	<option value="1" $DescWeightOptStrs[1]>-4 Deboost</option>
	<option value="2" $DescWeightOptStrs[2]>-3 Deboost</option>
	<option value="3" $DescWeightOptStrs[3]>-2 Deboost</option>
	<option value="4" $DescWeightOptStrs[4]>-1 Deboost</option>
	<option value="5" $DescWeightOptStrs[5]>Normal</option>
	<option value="6" $DescWeightOptStrs[6]>+1 Boost</option>
	<option value="7" $DescWeightOptStrs[7]>+2 Boost</option>
	<option value="8" $DescWeightOptStrs[8]>+3 Boost</option>
	<option value="9" $DescWeightOptStrs[9]>+4 Boost</option>
	<option value="10" $DescWeightOptStrs[10]>+5 Boost</option>	
</select></td></tr>
<tr><td>Heading</td><td>
<select name="config_weighting_heading">
	<option value="0" $HeadingWeightOptStrs[0]>-5 Deboost</option>
	<option value="1" $HeadingWeightOptStrs[1]>-4 Deboost</option>
	<option value="2" $HeadingWeightOptStrs[2]>-3 Deboost</option>
	<option value="3" $HeadingWeightOptStrs[3]>-2 Deboost</option>
	<option value="4" $HeadingWeightOptStrs[4]>-1 Deboost</option>
	<option value="5" $HeadingWeightOptStrs[5]>Normal</option>
	<option value="6" $HeadingWeightOptStrs[6]>+1 Boost</option>
	<option value="7" $HeadingWeightOptStrs[7]>+2 Boost</option>
	<option value="8" $HeadingWeightOptStrs[8]>+3 Boost</option>
	<option value="9" $HeadingWeightOptStrs[9]>+4 Boost</option>
	<option value="10" $HeadingWeightOptStrs[10]>+5 Boost</option>	
</select></td></tr>
<tr><td>Filename</td><td>
<select name="config_weighting_filename">
	<option value="0" $FilenameWeightOptStrs[0]>-5 Deboost</option>
	<option value="1" $FilenameWeightOptStrs[1]>-4 Deboost</option>
	<option value="2" $FilenameWeightOptStrs[2]>-3 Deboost</option>
	<option value="3" $FilenameWeightOptStrs[3]>-2 Deboost</option>
	<option value="4" $FilenameWeightOptStrs[4]>-1 Deboost</option>
	<option value="5" $FilenameWeightOptStrs[5]>Normal</option>
	<option value="6" $FilenameWeightOptStrs[6]>+1 Boost</option>
	<option value="7" $FilenameWeightOptStrs[7]>+2 Boost</option>
	<option value="8" $FilenameWeightOptStrs[8]>+3 Boost</option>
	<option value="9" $FilenameWeightOptStrs[9]>+4 Boost</option>
	<option value="10" $FilenameWeightOptStrs[10]>+5 Boost</option>	
</select></td></tr>
<tr><td>Keywords</td><td>
<select name="config_weighting_keywords">
	<option value="0" $KeywordWeightOptStrs[0]>-5 Deboost</option>
	<option value="1" $KeywordWeightOptStrs[1]>-4 Deboost</option>
	<option value="2" $KeywordWeightOptStrs[2]>-3 Deboost</option>
	<option value="3" $KeywordWeightOptStrs[3]>-2 Deboost</option>
	<option value="4" $KeywordWeightOptStrs[4]>-1 Deboost</option>
	<option value="5" $KeywordWeightOptStrs[5]>Normal</option>
	<option value="6" $KeywordWeightOptStrs[6]>+1 Boost</option>
	<option value="7" $KeywordWeightOptStrs[7]>+2 Boost</option>
	<option value="8" $KeywordWeightOptStrs[8]>+3 Boost</option>
	<option value="9" $KeywordWeightOptStrs[9]>+4 Boost</option>
	<option value="10" $KeywordWeightOptStrs[10]>+5 Boost</option>	
</select></td></tr>
<tr><td>Link/ALT text</td><td>
<select name="config_weighting_linkalt">
	<option value="0" $LinkWeightOptStrs[0]>-5 Deboost</option>
	<option value="1" $LinkWeightOptStrs[1]>-4 Deboost</option>
	<option value="2" $LinkWeightOptStrs[2]>-3 Deboost</option>
	<option value="3" $LinkWeightOptStrs[3]>-2 Deboost</option>
	<option value="4" $LinkWeightOptStrs[4]>-1 Deboost</option>
	<option value="5" $LinkWeightOptStrs[5]>Normal</option>
	<option value="6" $LinkWeightOptStrs[6]>+1 Boost</option>
	<option value="7" $LinkWeightOptStrs[7]>+2 Boost</option>
	<option value="8" $LinkWeightOptStrs[8]>+3 Boost</option>
	<option value="9" $LinkWeightOptStrs[9]>+4 Boost</option>
	<option value="10" $LinkWeightOptStrs[10]>+5 Boost</option>		
</select></td></tr>
<tr><td>Body context</td><td>
<select name="config_weighting_body">
	<option value="0" $BodyWeightOptStrs[0]>-5 Deboost</option>
	<option value="1" $BodyWeightOptStrs[1]>-4 Deboost</option>
	<option value="2" $BodyWeightOptStrs[2]>-3 Deboost</option>
	<option value="3" $BodyWeightOptStrs[3]>-2 Deboost</option>
	<option value="4" $BodyWeightOptStrs[4]>-1 Deboost</option>
	<option value="5" $BodyWeightOptStrs[5]>Normal</option>
	<option value="6" $BodyWeightOptStrs[6]>+1 Boost</option>
	<option value="7" $BodyWeightOptStrs[7]>+2 Boost</option>
	<option value="8" $BodyWeightOptStrs[8]>+3 Boost</option>
	<option value="9" $BodyWeightOptStrs[9]>+4 Boost</option>
	<option value="10" $BodyWeightOptStrs[10]>+5 Boost</option>	
</select></td></tr>
</table>
</fieldset>
	<fieldset>
	<legend>Word position</legend>	
<table>
<tr><td >You can give preference to words that appear towards the top of the page. Also, in multi-word searches, preference will be given to words that are closer together on the same page</td></tr>	
<tr><td>
<select name="config_weighting_wordpos">
	<option value="0" $ProximityOpts[0]>No adjustment</option>
	<option value="1" $ProximityOpts[1]>Standard adjustment</option>
	<option value="2" $ProximityOpts[2]>Strong adjustment</option>
</select></td></tr>
</table>
</fieldset>
	<fieldset>
	<legend>Content density</legend>	
<table>
<tr><td>You can give preference to small and medium sized documents over larger documents here.</td></tr>	
<tr><td>
<select name="config_weighting_density">
	<option value="0" $DensityOpts[0]>No adjustment</option>
	<option value="1" $DensityOpts[1]>Standard adjustment</option>
	<option value="2" $DensityOpts[2]>Strong adjustment</option>
</select></td></tr>
</table>
</fieldset>
	<fieldset>
	<legend>URL length</legend>	
<table>
<tr><td>Give preference to short URLs over longer URLs.</td></tr>	
<tr><td>
<select name="config_weighting_urllength">
	<option value="0" $ShortURLOpts[0]>No adjustment</option>
	<option value="1" $ShortURLOpts[1]>Standard adjustment</option>
	<option value="2" $ShortURLOpts[2]>Strong adjustment</option>
</select></td></tr>
</table>
</fieldset>
<input type="button" class="help_button"  value="Help"/ onclick="OpenHelp('weightings')">
</fieldset>
</div>
CONFIG_WEIGHTINGS;


//Filtering section
$EnableFilteringStr = "";
if($UConfig->UseContentFilter == true)
	$EnableFilteringStr = $RADIOBUTTON_CHECKEDSTR;
	
echo<<<CONFIG_FILTERING
<div id="filtering" style="width: 800px; display: none;">
<fieldset>
<legend><img src="./images/filtering_24.png" class="sectionImage" alt="Filtering">Filtering</legend>	
<a id="#filtering"></a>
	<fieldset>
	<legend>Content filtering</legend>	
<table>
<tr><td colspan=2><input type="checkbox" name="config_filtering_enable" value="1" $EnableFilteringStr onclick="ContentOptChange();" />Enable content filtering</td></tr>	
<tr><td>Specify filtering rules to include or exclude pages based on content.<br><br>
Keywords beginning with a "+" imply that a page must contain this word for it to be indexed.<br><br>
Keywords beginning with a "-" imply that a page containing this word will not be indexed.<br><br>
The order of the rules in this list determines their precedence.
</td><td><textarea style="min-width:250px" cols=20 rows=10 name="filtering_rules" >
CONFIG_FILTERING;
		foreach ($UConfig->ContentFilterRules as $filterName)
		{
			 echo "$filterName\n";
		}
		
echo<<<CONFIG_FILTERING
</textarea></td></tr>
</table>
</fieldset>
<input type="button" class="help_button"  value="Help"/ onclick="OpenHelp('filtering')">
</fieldset>
</div>
CONFIG_FILTERING;

//Categories section
$UseCatsStr = "";
$UseDefCatsStr = "";
$MultiCatSearchStr = "";
$CatBreakDownStr = "";

if($UConfig->UseCats == true)
	$UseCatsStr = $RADIOBUTTON_CHECKEDSTR;
if($UConfig->UseDefCatName == true)
	$UseDefCatsStr = $RADIOBUTTON_CHECKEDSTR;
if($UConfig->SearchMultiCats == true)
	$MultiCatSearchStr = $RADIOBUTTON_CHECKEDSTR;
if($UConfig->DisplayCatSummary == true)
	$CatBreakDownStr = $RADIOBUTTON_CHECKEDSTR;	

echo<<<CONFIG_CATEGORIES
<div id="categories" style="width: 800px; display: none;">
<fieldset>
<legend><img src="./images/categories_24.png" class="sectionImage" alt="Categories">Categories</legend>	
<a id="#categories"></a>
<input type="checkbox" name="config_categories_enable" value="1" $UseCatsStr onclick="CategoryOptChange();" />Enable categories
	<fieldset>
	<legend>Categories</legend>	
<table>
<tr><td>Specify categories here that search engine users will be able to filter their results by.</td></tr>	
<tr><td colspan=5 ><select style="min-width:250px" multiple size=10  name="category_rules[]" onchange="CategoryChange()">
CONFIG_CATEGORIES;
		foreach ($UConfig->cats_list as $nextCat)
		{				
			$catStr = $nextCat->name . " | " . $nextCat->pattern. " | " . $nextCat->description;
			if($nextCat->IsExclusive > 0)
				$catStr .= " | Exclusive";
			 	
			echo "<option VALUE=\"$catStr\">$catStr</option>\n";
		}
echo<<<CONFIG_CATEGORIES
</select></td></tr>
<tr><td><input type="checkbox" name="config_categories_catchfiles" value="1" $UseDefCatsStr  />Catch files not belonging to a category in: <input type="text" name="config_categories_catchcategory" value="$UConfig->DefCatName"  size="15"/></td></tr>
<tr><td><input type="checkbox" name="config_categories_mutil" value="1" $MultiCatSearchStr />Allow searching in multiple categories</td></tr>
<tr><td><input type="checkbox" name="config_categories_breakdown" value="1" $CatBreakDownStr  />Show category breakdown in search results ("Refine your search by...")</td></tr>
<tr><td> <input type="button" name="config_categories_remove" value="Remove" onClick="RemoveCats(false)" />  <input type="button" name="config_categories_removeall" value="Remove All" onClick="RemoveCats(true)"/>  </td></tr>
</table>
</fieldset>
<div id="addeditcat">
		<fieldset>
		<legend>Add/Edit Category</legend>	
		<table>
		<tr><td>Category name: </td><td><input type="text" size="15" name="config_cat_name"></td></tr>
		<tr><td>Match pattern: </td><td><input type="text" size="15"name="config_cat_match"></td></tr>
		<tr><td>&nbsp;</td><td>Wildcard characters "*" and "?" supported.</td></tr>
		<tr><td>&nbsp;</td><td>Specify multiple patterns by separating them with semi-colons (eg: ".pdf;.doc;.xls")</td></tr>
		<tr><td>Description: </td><td><input type="text" size="15"name="config_cat_desc"></td></tr>
		<tr><td>&nbsp;</td><td><input type="checkbox" name="config_cat_exclusive" value="1" />Files belonging to this category can not belong to any other category.</td></tr>
		</table>
		<input type="button" value="Add" name="config_categories_add" onclick="AddEditCategory(false)" />
		<input type="button" name="config_categories_edit" value="Update"onclick="AddEditCategory(true)" />
		</fieldset>
	</div>
	<input type="button" class="help_button"  value="Help"/ onclick="OpenHelp('categories')">
	</fieldset>
</div>
CONFIG_CATEGORIES;

//Sitemaps sections
$UseXMLSitemapStr = "";
$UseTXTSitemapStr = "";
$MultiCatSearchStr = "";
$UsePageboostStr = "";
$UploadSitemapStr = "";
$OnlyBaseURLsStr = "";
$AllURLsStr = "";

if($UConfig->SitemapXML == true)
	$UseXMLSitemapStr = $RADIOBUTTON_CHECKEDSTR;
if($UConfig->SitemapTXT == true)
	$UseTXTSitemapStr = $RADIOBUTTON_CHECKEDSTR;
if($UConfig->SearchMultiCats == true)
	$MultiCatSearchStr = $RADIOBUTTON_CHECKEDSTR;
if($UConfig->DisplayCatSummary == true)
	$CatBreakDownStr = $RADIOBUTTON_CHECKEDSTR;	
if($UConfig->SitemapUsePageBoost == true)
	$UsePageboostStr = $RADIOBUTTON_CHECKEDSTR;	
if($UConfig->SitemapUpload == true)
	$UploadSitemapStr = $RADIOBUTTON_CHECKEDSTR;	

if($UConfig->SitemapUseBaseURL == true)
	$OnlyBaseURLsStr = $RADIOBUTTON_CHECKEDSTR;
else
	$AllURLsStr = $RADIOBUTTON_CHECKEDSTR;
	
	
echo<<<CONFIG_SITEMAPS
<div id="sitemaps" style="width: 800px; display: none;">
<fieldset>
<legend><img src="./images/sitemap_24.png" class="sectionImage" alt="Sitemaps">Sitemaps</legend>	
<a id="#sitemaps"></a>
<p>Sitemaps can be generated for your website while Zoom is indexing. This can be used to help external search engines to index and track changes on your site more thoroughly.
	<fieldset>
	<legend>Sitemap formats</legend>	
<table>
<tr><td><input type="checkbox" name="config_sitemap_txt" value="1"  $UseTXTSitemapStr onclick="SitemapOptChange();" />Text file URL list (Yahoo&trade; Sitemaps compatible)</td></tr>
<tr><td>File will be saved as "urllist.txt" in output directory.</td></tr>
<tr><td><input type="checkbox" name="config_sitemap_xml" value="1" $UseXMLSitemapStr  onclick="SitemapOptChange();" />XML sitemap format (Google&trade; Sitemaps compatible)</td></tr>
<tr><td>&nbsp;&nbsp;&nbsp;Files will be saved as "sitemap.xml" and "sitemap_index.xml" in output directory. Click Help for information.</td></tr>
<tr><td>&nbsp;&nbsp;&nbsp;Sitemap Base URL: <input type="text" name="config_sitemap_baseurl" value="$UConfig->SitemapBaseURL"  size="15"/></td></tr>
<tr><td>&nbsp;&nbsp;&nbsp;<input type="radio" name="config_sitemap_include" value="1" $OnlyBaseURLsStr />Include only URLs within the Sitemap Base URL</td></tr>
<tr><td>&nbsp;&nbsp;&nbsp;<input type="radio" name="config_sitemap_include" value="0" $AllURLsStr />Include all indexed URLs</td></tr>
</table>
</fieldset>
<fieldset>
<legend>Sitemap options</legend>	
<input type="checkbox" name="config_sitemap_pageboost" value="1" $UsePageboostStr />Use ZOOMPAGEBOOST and weighting values for Priority field (XML only)<br>
<input type="checkbox" name="config_sitemap_upload" value="1" $UploadSitemapStr />Upload sitemap files to web server (see FTP tab)<br>
&nbsp;&nbsp;&nbsp;Folder or path on server: <input type="text" name="config_sitemap_uploadpath" value="$UConfig->SitemapUploadPath"  size="15"/>
</fieldset>
<input type="button" class="help_button"  value="Help"/ onclick="OpenHelp('sitemaps')">
</fieldset>
</div>
CONFIG_SITEMAPS;

//Synonyms section
echo<<<CONFIG_SYNONYMS
<div id="synonyms" style="width: 800px; display: none;">
<fieldset>
<legend><img src="./images/synonyms_24.png" class="sectionImage" alt="Synonyms">Synonyms</legend>	
<a id="#synonyms"></a>
	<p>You can specify synonyms, variations of words, common mis-spellings here, and allow them to map to an equivalent word in the index.<br>
	For example: the word "question" could be made equivalent to: "inquiry,enquiry,query,questions"<br>
<table>
<tr><td colspan=6>
<select style="min-width:450px" multiple size=10 name="synonym_rules[]" onchange="SynonymChange()">
CONFIG_SYNONYMS;
	foreach ($UConfig->syn_list as $nextSyn)
	{
		$synStr = $nextSyn->word . " | " . $nextSyn->synonyms;
		 echo "<option VALUE=\"$synStr\">$synStr</option>\n";
	}
echo<<<CONFIG_SYNONYMS
</select></td></tr>
<tr><td> </td><td> <input type="button" name="config_synonyms_remove" value="Remove" onclick="RemoveSynonyms(false);"/> </td><td>  <input type="button" name="config_synonyms_removeall" value="Remove All" onclick="RemoveSynonyms(true);"/> </td><td>  <input type="button" name="config_synonyms_import" value="Import" disabled="disabled" /> </td><td> <input type="button" name="config_synonyms_export" value="Export" disabled="disabled" /></td></tr>
</table>
<div id="addeditsyn">
		<fieldset>
		<legend>Add/Edit Synonym</legend>	
		<table>
		<tr><td>Word: </td><td><input type="text" size="5" name="config_syn_word"></td></tr>
		<tr><td>Synonyms: </td><td><input type="text" size="25"name="config_syn_synonyms"></td></tr>
		<tr><td>&nbsp;</td><td>Specify multiple synonyms by separating them with commas (eg. "mouse,mice,rat")</td></tr>
		</table>

		<input type="button" value="Add" name="config_synonyms_add" onclick="AddEditSynonym(false)" />
		<input type="button" name="config_synonyms_edit" value="Update"onclick="AddEditSynonym(true)" />
		</fieldset>
	</div>
<input type="button" class="help_button"  value="Help"/ onclick="OpenHelp('synonyms')">
</fieldset>
</div>
CONFIG_SYNONYMS;

//Recommended links section
echo<<<CONFIG_RECOMMENDED
<div id="recommended" style="width: 800px; display: none;">
<a id="#recommended"></a>
<fieldset>
<legend><img src="./images/recommended_24.png" class="sectionImage" alt="Recommended">Recommended</legend>	
	<fieldset>
	<legend>Recommended links list</legend>	
	<p>Here you can specify links to be associated with certain keywords. This will allow these links to be recommended above the normal search results.<br>
<table>
<tr><td colspan=6>
<select style="min-width:250px" multiple size=10 name="recommended_rules[]" onchange="RecChange()">
CONFIG_RECOMMENDED;
	foreach ($UConfig->RecommendedList as $nextRecc)
	{
		$recStr = $nextRecc->word . " | " . $nextRecc->URL . " | " . $nextRecc->title . " | " . $nextRecc->desc . " | " . $nextRecc->imgURL;
		 echo "<option value=\"$recStr\">$recStr</option>\n";

	}
	
echo<<<CONFIG_RECOMMENDED
</select></td></tr>
<tr><td colspan=6 >Show up to <input type="text" name="config_recommended_max_links" value="$UConfig->RecommendedMax" size="25" onkeypress="return onlyNumbers(event)" /> recommended links per search query.</td></tr>

<tr>
<td><input type="button" name="config_recommended_remove" value="Remove" onclick="RemoveRecommended(false);" /></td>
<td><input type="button" name="config_recommended_removeall" value="Remove All" onclick="RemoveRecommended(true);" /> </td>
<td><input type="button" name="config_recommended_import" value="Import" disabled="disabled" /></td>
<td><input type="button" name="config_recommended_export" value="Export" disabled="disabled" /></td>
</tr>
</table>
<div id="addeditrec">
		<fieldset>
		<legend>Add/Edit Recomended Link</legend>	
		<table>
		<tr><td>Keyword or phrase:</td><td><input type="text" size="5" name="config_rec_keyword"></td></tr>
		<tr><td>URL:</td><td><input type="text" size="25"name="config_rec_url"></td></tr>
		<tr><td>Page title:</td><td><input type="text" size="25"name="config_rec_title"></td></tr>
		<tr><td>Page description<br>(optional):</td><td><textarea rows="3" rows="25" name="config_rec_desc"></textarea></td></tr>
		<tr><td>Image URL<br>(only used if enabled):</td><td><input type="text" size="25"name="config_rec_img"></td></tr>
		</table>
		<input type="button" value="Add" name="config_recommended_add" onclick="AddEditRecLink(false)" />
		<input type="button" name="config_recommended_edit" value="Update"onclick="AddEditRecLink(true)" />
		</fieldset>
	</div>
</fieldset>
<input type="button" class="help_button"  value="Help"/ onclick="OpenHelp('recommended')">
</fieldset>
</div>
CONFIG_RECOMMENDED;

//meta fields
echo<<<CONFIG_CUSTOMMETA
<div id="custommeta" style="width: 800px; display: none;">
<fieldset>
<legend><img src="./images/metafields_24.png" class="sectionImage" alt="Custom Meta Fields">Custom Meta Fields</legend>	
<a id="#custommeta"></a>
	<fieldset>
	<legend>Custom meta search fields</legend>	
	<p>You can add arbitrary meta fields by which your pages can be indexed and searched by. Zoom will look for these meta fields on your web pages, and index them as specified below.<br>
<table>
<tr><td><select style="min-width:250px" multiple size=10 name="meta_rules[]"  onchange="MetaChange()">
CONFIG_CUSTOMMETA;

	foreach ($UConfig->metafield_list as $nextMeta)
	{
		
		$metaStr = $nextMeta->name . " | " . $METAFIELD_STRINGS[$nextMeta->type] . " | " . $nextMeta->showname  . " | " . $nextMeta->formname  . " | " . $METAFIELD_SEARCH_STRINGS[$nextMeta->method];
		
		if($nextMeta->type == $METAFIELD_TYPE_DROPDOWN)
			{
				$DDValueStr = "";
				foreach ($nextMeta->DropdownValues as $nextDDValue)
				if(strlen($nextDDValue) > 0)
				{
					$DDValueStr .= $nextDDValue . ", ";
				}
									
				rtrim($DDValueStr, ",");
				$metaStr .=" | " . $DDValueStr;
			}
		
		 echo "<option VALUE=\"$metaStr\">$metaStr</option>";
	}
	
echo<<<CONFIG_CUSTOMMETA
</select></td></tr>
<tr>	
	<td><input type="button" name="config_meta_remove" value="Remove" onclick="RemoveMeta(false)" /> 
	<input type="button" name="config_meta_removeall" value="Remove All" onclick="RemoveMeta(true)" /> </td>
</tr>
</table>
<div id="addeditmeta">
		<fieldset>
		<legend>Add/Edit Custom Meta Field</legend>	
		<table>
		<tr><td>Meta name:</td><td><input type="text" size="10" name="config_meta_name"></td></tr>
		<tr><td>Type:</td>
			<td><select name="config_meta_type" onchange="CheckIfDDVEnabled()">
					<option value="0"> $METAFIELD_STRINGS[0] </option>
					<option value="1"> $METAFIELD_STRINGS[1] </option>
					<option value="2"> $METAFIELD_STRINGS[2] </option>
					<option value="3"> $METAFIELD_STRINGS[3] </option>
					<option value="4"> $METAFIELD_STRINGS[4] </option>
			</select></td></tr>
		<tr><td>Dropdown values:</td><td><textarea rows="3" rows="25" name="config_meta_ddvalues"></textarea></td></tr>
		<tr><td>Show in search results as:</td><td><input type="text" size="10"name="config_meta_show"></td></tr>
		<tr><td>Search criteria name:</td><td><input type="text" size="10" name="config_meta_criterianame"></td></tr>
		<tr><td>Search criteria method:</td><td><select name="config_meta_criteriamethod">
			<option value="0"> $METAFIELD_SEARCH_STRINGS[0] </option>
					<option value="1"> $METAFIELD_SEARCH_STRINGS[1] </option>
					<option value="2"> $METAFIELD_SEARCH_STRINGS[2] </option>
					<option value="3"> $METAFIELD_SEARCH_STRINGS[3] </option>
					<option value="4"> $METAFIELD_SEARCH_STRINGS[4] </option>
					<option value="5"> $METAFIELD_SEARCH_STRINGS[5] </option>
			</select>
		</td></tr>
		</table>
		<input type="button" value="Add" name="config_meta_add" onclick="AddEditMeta(false)" />
		<input type="button" name="config_meta_edit" value="Update"onclick="AddEditMeta(true)" />
		</fieldset>
	</div>
</fieldset>
<input type="button" class="help_button"  value="Help"/ onclick="OpenHelp('custommeta')">
</fieldset>
</div>
CONFIG_CUSTOMMETA;

//Index log 
$LogModeBasicStr = "";
$LogModeAdvancedStr = "";
$LogHTMLWarningsStr = "";
$LogDebugModeStr = "";
$LogAppendDateStr = "";
$LogWriteToFileStr  = "";

if($UConfig->LogMode == 0)
	$LogModeBasicStr = "selected=\"selected\"";
else
	$LogModeAdvancedStr = "selected=\"selected\"";

if($UConfig->LogHTMLErrors == true)
	$LogHTMLWarningsStr = $RADIOBUTTON_CHECKEDSTR; 
if($UConfig->LogDebugMode == true)
	$LogDebugModeStr = $RADIOBUTTON_CHECKEDSTR; 
if($UConfig->LogAppendDatetime == true)
	$LogAppendDateStr = $RADIOBUTTON_CHECKEDSTR; 
if($UConfig->LogWriteToFile == true)
	$LogWriteToFileStr = $RADIOBUTTON_CHECKEDSTR; 
	
echo<<<CONFIG_INDEXLOG
<div id="indexlog" style="width: 800px; display: none;">
<fieldset>
<legend><img src="./images/log_24.png" class="sectionImage" alt="Log">Index Log</legend>	
<a id="#indexlog"></a>
	<fieldset>
	<legend>Log Modes</legend>	
	<p>This controls the detail level of the log and what is recorded and made available on the "Log" tab.<br>
	<select name="config_indexlog_detail">
	<option value="0" $LogModeBasicStr>Basic</option>
	<option value="1" $LogModeAdvancedStr>Detailed</option>
	</select><br>
	<input type="checkbox" name="config_indexlog_warnings" value="1" $LogHTMLWarningsStr />Log HTML warnings
</fieldset>
	<fieldset>
	<legend>Log to file</legend>	
	<input type="checkbox" name="config_indexlog_save" value="1" $LogWriteToFileStr onclick="IndexLogOptChange();" />Save index log to file: <input type="text" name="config_indexlog_path" value="$UConfig->LogSaveToFilename"  size="15"/><br>
	<input type="checkbox" name="config_indexlog_appenddate" value="1" $LogAppendDateStr />Append date and time to log filename<br>
	<input type="checkbox" name="config_indexlog_debugmode" value="1" $LogDebugModeStr />Debug mode
	</fieldset>
	<input type="button" class="help_button"  value="Help"/ onclick="OpenHelp('indexlog')">
	</fieldset>
</div>
CONFIG_INDEXLOG;

//Advanced section
$LogSearchesStr = "";
$ShowWizardStr = "";
$BeepOnEndStr = "";
$UseSourcePathStr = "";
$UseXMLStr = "";
$NoCharsetStr = "";
$XMLHighLightStr = "";
		
if($UConfig->Logging == true)
	$LogSearchesStr = $RADIOBUTTON_CHECKEDSTR; 
if($UConfig->WizardUploadReqd == true)
	$ShowWizardStr = $RADIOBUTTON_CHECKEDSTR; 
if(	$UConfig->BeepOnFinish == true)
	$BeepOnEndStr = $RADIOBUTTON_CHECKEDSTR; 
if(	$UConfig->UseSrcPaths == true)
	$UseSourcePathStr = $RADIOBUTTON_CHECKEDSTR; 
if(	$UConfig->UseXML == true)
	$UseXMLStr = $RADIOBUTTON_CHECKEDSTR; 

if($UConfig->NoCharset == true)
	$NoCharsetStr = $RADIOBUTTON_CHECKEDSTR;
	
if($UConfig->XMLHighlight == true)
	$XMLHighLightStr = $RADIOBUTTON_CHECKEDSTR;



echo<<<CONFIG_ADVANCED
<div id="advanced" style="width: 800px; display: none;">
<fieldset>
<legend><img src="./images/advanced_24.png" class="sectionImage" alt="Advanced">Advanced</legend>	
<a id="#advanced"></a>
	<fieldset>
	<legend>General Settings</legend>	
<input type="checkbox" name="config_advanced_wizard" value="1" $ShowWizardStr  /> Do not show wizard on startup
<input type="checkbox" name="config_advanced_beep" value="1" $BeepOnEndStr /> Beep at the end of indexing
</fieldset>
	<fieldset>
	<legend>Search Logging (Not available for JavaScript) </legend>	
	<input type="checkbox" name="config_advanced_logsearches" value="1" $LogSearchesStr onclick="AdvancedOptChange();" />Log the searches made on your site<br>
	You can use the log file created here with the statistics report tool.<br>
	Log filename <input type="text" name="config_advanced_logpath" value="$UConfig->LogFileName"  size="15"/><br>
	Note: Requires server-side write permissions on the log file specified above.
		</fieldset>
	<fieldset>
	<legend>Embedding Script </legend>	
	If you are going to embed Zoom in another server-side script, you should specify the script filename here.<br>
	Link back URL: <input type="text" name="config_advanced_linkback" value="$UConfig->LinkBackURL"  size="15"/>
	</fieldset>
	<fieldset>
	<legend>Custom Script Source Path </legend>	
	<input type="checkbox" name="config_advanced_specifyscript" value="1" $UseSourcePathStr onclick="AdvancedOptChange();" />Specify my own path for the script source code<br>
	Search script path: <input type="text" name="config_advanced_scriptpath" value="$UConfig->SourceScriptPath"  size="15"/>
	</fieldset>
	<fieldset>
	<legend>XML/RSS (CGI only) </legend>	
		<input type="checkbox" name="config_advanced_usexml" value="1" $UseXMLStr onclick="AdvancedOptChange();" />Use XML/RSS output
		<fieldset>
		<legend>Channel information (Optional)</legend>	
		You can specify the following information about your XML search page / RSS channel.
		<table>	
			<tr><td style="text-align:right">Title</td><td><input type="text" name="config_advanced_ci_title" value="$UConfig->XMLTitle"  size="15"/></td></tr>
			<tr><td style="text-align:right">Description</td><td><input type="text" name="config_advanced_ci_desc" value="$UConfig->XMLDescription"  size="15"/></td></tr>
			<tr><td style="text-align:right">URL</td><td><input type="text" name="config_advanced_ci_url" value="$UConfig->XMLLink"  size="15"/></td></tr>
			<tr><td style="text-align:right">XSLT URL</td><td><input type="text" name="config_advanced_ci_xlst" value="$UConfig->XMLStyleSheetURL"  size="15"/></td></tr>
		</table>
		</fieldset>
		<fieldset>
		<legend>OpenSearch</legend>	
		Description file URL <input type="text" name="config_advanced_os_descURL" value="$UConfig->XMLOpenSearchDescURL"  size="15"/>
		</fieldset>
		<fieldset>
		<legend>Options</legend>	
		<input type="checkbox" name="config_advanced_hl_xml" value="1" $XMLHighLightStr />Highlight words in XML results (context only)
		</fieldset>
	</fieldset>
	<fieldset>
	<legend>Spider User-Agent (Enterprise Edition Only) </legend>	
	 User-Agent text used to identify the web spider: <input type="text" name="config_advanced_useragent" value="$UConfig->UserAgentStr"  size="15"/>
	</fieldset>
	<fieldset>
	<legend>Miscellaneous</legend>	
	<input type="checkbox" name="config_advanced_disablechar" value="1" $NoCharsetStr />Disable charset enforcing on search script
	</fieldset>
	<input type="button" class="help_button"  value="Help"/ onclick="OpenHelp('advanced')">
	</fieldset>
</div>
CONFIG_ADVANCED;

//New section for filetype/plugin options that were previously in the "Scan options" -> file extension -> configure dialog
//Split into a new sections for easy udpating and processing (as these are options are based on file type while the thumbnail/image options are based on file extension) 
echo<<<CONFIG_FILETYPES
<div id="filetypes" style="width: 800px; display: none;">
<fieldset>
	<legend>Filetype Options</legend>	
		<select style="min-width:250px" size=10 name="config_filetypes[]" onchange="UpdateFiletypeCfg(false);" >
CONFIG_FILETYPES;

		foreach ($FILETYPE_STRING as $nextType)
		{			
			echo "<option VALUE=\"$nextType\">$nextType</option>";
		}
		
		echo "</select>";
			
		//Hidden values for filetype/plugin options
		foreach($UConfig->PluginConfig as $key => $value)
		{
			echo "<input type=\"hidden\" name=\"$key\" value=\"$value\">\n";
		}
		
		echo "<input type=\"hidden\" name=\"binary_use_desc\" value=\"$UConfig->BinaryUseDescFiles\">\n";
		echo "<input type=\"hidden\" name=\"binary_extract_strings\" value=\"$UConfig->BinaryExtractStrings\">\n";
			
echo<<<CONFIG_FILETYPES
<div id="filetype_configure"></div>
<input type="button" value="Return to Scan options" onclick="javascript:hideshow(document.getElementById('config_scanoptions'), true);"><br>
<input type="button" class="help_button"  value="Help"/ onclick="OpenHelp('filetypes')">
</fieldset>
</div>
CONFIG_FILETYPES;

?>
<input type="submit" value="Save Changes" name="submit" >

<?php
echo "<input type=\"button\" value=\"Discard Changes\" name=\"discard\" onclick=\"window.location='ZoomIndexer.php?config=$CurrentConfigPath'\" >";
?>
</form>
</body>
</html>