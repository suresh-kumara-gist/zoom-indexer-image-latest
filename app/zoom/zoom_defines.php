<?php

$ZOOM_HEADING_STRING = "Zoom Search Engine Indexer";
$ZOOM_VERSION_STRING = "Version 7.0 (Core engine missing or not executable)";

if( !function_exists('ftok') )
{
    function ftok($filename = "", $proj = "")
    {
        if( empty($filename) || !file_exists($filename) )
        {
            return -1;
        }
        else
        {
            $filename = $filename . (string) $proj;
            for($key = array(); sizeof($key) < strlen($filename); $key[] = ord(substr($filename, sizeof($key), 1)));
            return dechex(array_sum($key));
        }
    }
}
  
 
//$EngineExePath = "/var/www/zoom/ZoomEngine";
$EngineExeDir = dirname(__FILE__);
$EngineExePath = $EngineExeDir."/ZoomEngine";
//Chec if we need to switch to using the 64bit build of Zoom (ZoomEngine64)
if(php_uname("m") == "x86_64")
 $EngineExePath .="64";

$FREE_EDITION				= 0;
$STANDARD_EDITION		= 1;
$PRO_EDITION				= 2;
$ENTERPRISE_EDITION	= 3;

$FREE_EDITION_MAXPAGES			= 50;
$STANDARD_EDITION_MAXPAGES	= 100;
$PRO_EDITION_MAXPAGES				= 50000;
$MAX_THEORETICAL_BASEWORDS	= 16777216;	
$MAX_THEORETICAL_PAGES			= 2147483647;

$EngineLogDir = $EngineExeDir . "/.ZoomLogBuffer";

$ProcessID = 1234;
$MappedStatusKey = ftok($EngineExePath, chr($ProcessID));
$MappedLogBufferKey = ftok($EngineLogDir, chr($ProcessID));


$URLLENGTH = 2083;
$MAXDESCLEN = 2000;
$MODE_OFFLINE = 0;
$MODE_SPIDER = 1;
$MAX_FNAME = 256;
$MAX_EXT =  256;
$TITLELEN = 512;	
$MAX_PATH = 260;

// Output index formats
$OUTPUT_PHP = 0;
$OUTPUT_ASP = 1;
$OUTPUT_JSFILE = 2;
$OUTPUT_CGI = 3;
$OUTPUT_ASPNET = 4;

// Output OS
$OS_WINDOWS = 0;
$OS_LINUX = 1;
$OS_BSD = 2;
$OS_FLYINGANT = 3;


//Status values
$INDEXER_STATUS_IDLE 						= 0;
$INDEXER_STATUS_INDEXING				= 1;
$INDEXER_STATUS_PARSING					= 2;
$INDEXER_STATUS_PLUGIN					= 3;
$INDEXER_STATUS_OUTPUT					= 4;
$INDEXER_STATUS_NA							= 5;
$INDEXER_STATUS_CLEANUP					= 6;
$INDEXER_STATUS_FINISHING				= 7;
$INDEXER_STATUS_LOADING					= 8;
$INDEXER_STATUS_FLUSHING				= 9;
$INDEXER_STATUS_CHECKING				= 10;
$INDEXER_STATUS_MERGING					= 11;
$INDEXER_STATUS_READINGDISK			= 12;
$INDEXER_STATUS_PARTIALFINISHED			= 13;
$INDEXER_STATUS_FINISHED				= 14;
$INDEXER_STATUS_OUTOFMEMORY			= 15;
$INDEXER_STATUS_PAUSED					= 16;
$INDEXER_STATUS_WAITINGTOQUIT		= 17;
$INDEXER_STATUS_LOADINGPAGEDATA 	=	18;
$INDEXER_STATUS_PAGEDATALOADED		= 19;
$INDEXER_STATUS_LOADERROR		 		= 20;
$INDEXER_STATUS_WAITINGTORESTART	= 21;

$INDEXER_STATUS_STRING = array(
							"Waiting", 
							"Scanning", 
							"Spidering", 
							"Processing", 
							"Writing index",
							"N/A",
							"Cleanup",
							"Finishing...",
							"Loading",
							"Flushing",
							"Checking",
							"Merging",
							"Reading disk",
							"Partial index complete (stopped by user)",
							"Indexing completed",
							"Out of memory",
							"Paused",
							"Waiting to quit",
							"Loading page data",
							"Page data loaded",
							"Load error",
							"Waiting for restart");
							
									


$HTTPSESSION_STATUS_IDLE = 0;
$HTTPSESSION_STATUS_RECEIVING = 1;
$HTTPSESSION_STATUS_DOWNLOADING	= 2;
$HTTPSESSION_STATUS_REQUESTING = 3;
$HTTPSESSION_STATUS_ABORTING = 4;
$HTTPSESSION_STATUS_CANCELLING = 5;
$HTTPSESSION_STATUS_PAUSED = 6;
$HTTPSESSION_STATUS_RESUMING = 7;
$HTTPSESSION_STATUS_CONNECTING = 8;
$HTTPSESSION_STATUS_CONNECTED = 9;
$HTTPSESSION_STATUS_OPENING	= 10;
$HTTPSESSION_STATUS_CLOSING	= 11;
$HTTPSESSION_STATUS_OPENREQUEST = 12;
$HTTPSESSION_STATUS_QUERYINFO = 13;
$HTTPSESSION_STATUS_REDIRECTING = 14;
$HTTPSESSION_STATUS_OFFLINEINDEX = 15;
$HTTPSESSION_STATUS_INDEXING = 16;
$HTTPSESSION_STATUS_NA = 17;	// keep this one last

$HTTPSESSION_STATUS_STRING = array("Idle", "Receiving", "Downloading", "Requesting", "Aborting", "Cancelling",
				"Paused", "Resuming", "Connecting", "Opening", "Closing", "Open request", "Querying info",
				"Redirecting", "Offline index", "N/A");



// Commands
$__CUSTOM_BUILD_ZOOM_CMD_INITIAL	= 0;
$__CUSTOM_BUILD_ZOOM_CMD_PAUSE		=	1;
$__CUSTOM_BUILD_ZOOM_CMD_RESUME		= 2;
$__CUSTOM_BUILD_ZOOM_CMD_STOP			=	3;
$__CUSTOM_BUILD_ZOOM_CMD_QUIT			=	4;
$__CUSTOM_BUILD_ZOOM_CMD_START		=	5;
$__CUSTOM_BUILD_ZOOM_CMD_UPDATE		=	6;
$__CUSTOM_BUILD_ZOOM_CMD_QUITREQUEST	= 7;


//From plugins.h
$FILETYPE_STRING = array(
	"HTML text",
	"Plain text",
	"Unknown text", 			
	"Binary (Filename only)",
	"Word document",
	"Acrobat document",
	"Powerpoint presentation",
	"Excel spreadsheet",
	"WordPerfect document",
	"Shockwave Flash",
	"Rich Text Format",
	"DjVu document",
	"Image file",
	"MP3 audio",
	"DWF/CAD file",
	"Office 2007 file",	
	"BitTorrent file",	
	"MHT web archive",
	"ZIP archive",
	"Video/media file",
	"Outlook e-mail archive",
	"Mbox e-mail archive",
	"Outlook Express archive",
	"Mozilla Mail Summary File",
	"OpenDocument Text",
	"tar or tar.gz archive",
	"gzip archive",
	"MSG e-mail archive"
);

$AUTHXORKEY = "83135019";	// Password XOR key for config saving

$MAXEXTENSIONS =100;
$MAXSKIPPAGES = 1000;
$MAXSKIPPAGELEN = 1000;
$MAXSKIPWORDS	 = 400;
$METAFIELD_MONEY_COUNT = 4;
$MAXAUTOCOMPLETE = 5000;
$AUTOCOMPLETE_LEN	= 50;
$MAXFILTERRULES	= 400;

$METAFIELD_TYPE_NUMERIC  = 0;
$METAFIELD_TYPE_TEXT		 = 1;
$METAFIELD_TYPE_DROPDOWN = 2;
$METAFIELD_TYPE_MULTI		 = 3;
$METAFIELD_TYPE_MONEY	 	 = 4;
$METAFIELD_TYPE_COUNT 	 = 5;
$METAFIELD_DROPDOWN_MAX  = 254;

$METAFIELD_STRINGS = array(
$METAFIELD_TYPE_NUMERIC => "Numeric", 
$METAFIELD_TYPE_TEXT => "Text",
$METAFIELD_TYPE_DROPDOWN => "Dropdown text",
$METAFIELD_TYPE_MULTI	 => "Multi-select",
$METAFIELD_TYPE_MONEY	 => "Money");


$METAFIELD_SEARCH_PART  = 0;
$METAFIELD_SEARCH_GTE	= 1;
$METAFIELD_SEARCH_GT 	= 2;
$METAFIELD_SEARCH_LTE	= 3;
$METAFIELD_SEARCH_LT	= 4;
$METAFIELD_SEARCH_EXACT = 5;

$METAFIELD_SEARCH_STRINGS = array(
$METAFIELD_SEARCH_PART => "Partial (substring) text match", 
$METAFIELD_SEARCH_GTE => "Greater than or equal to",
$METAFIELD_SEARCH_GT => "Greater than",
$METAFIELD_SEARCH_LTE	 => "Less than or equal to",
$METAFIELD_SEARCH_LT	 => "Less than",
$METAFIELD_SEARCH_EXACT	 => "Exact match");


	
$URLTYPE_INDEX_AND_FOLLOW			= 0;
$URLTYPE_INDEX_ONLY						= 1;
$URLTYPE_FOLLOW_ONLY					= 2;
$URLTYPE_INDEX_AND_FOLLOW_ALL	= 3;
$URLTYPE_OFFLINE_FILE					= 4;
$URLTYPE_FOLLOW_ALL						= 5;	
$URLTYPE_JAVASCRIPTLINK				= 6;	
$URLTYPE_MAXCOUNT							= 7;	

$STARTPOINT_SPIDER_STRINGS = array(
$URLTYPE_INDEX_AND_FOLLOW => "Index page and follow internal* links (Default)", 
$URLTYPE_INDEX_AND_FOLLOW_ALL => "Index page and follow internal* and external links", 
$URLTYPE_INDEX_ONLY => "Index single page only",
$URLTYPE_FOLLOW_ONLY => "Follow links only",
$URLTYPE_FOLLOW_ALL	 => "Follow all links on this page only");

	
$FREE_EDITION_MAXPAGES		= 50;
$FREE_EDITION_MAXWORDS		= 15000;
$FREE_EDITION_MAXFILESIZE	= 1048576;
$FREE_EDITION_DESCLENGH 	= 150;
$FREE_EDITION_MAXQUERY 		= 1000; 
$FREE_EDITION_WORDLEN 		= 35; 


$NUM_OPTIMIZE_SETTINGS= 9; 
$OPTIMIZE_MAXMATCHES = array(50, 100, 500, 1000, 5000, 10000, 50000, 100000, 200000 );
$OPTIMIZE_CONTEXTSEEKS = array( 100, 200, 300, 500, 1000, 5000, 10000, 20000, 30000 );
$OPTIMIZE_MAXSEARCHTIME = array( 5, 10, 20, 30, 60, 120, 180, 240, 300 );

$WORDWEIGHT_NORMAL = 5;

$NUM_THROTTLE_SETTINGS = 6;
$THROTTLE_DELAY_VALUES = array(0, 200, 500, 1000, 5000, 15000 );

$MAX_DOWNLOAD_THREADS = 10;
$CAT_NAME_LEN = 50;
$CAT_PATTERN_LEN = $URLLENGTH;
$CAT_DESC_LEN	= 100;
$WORDLENGTH	= 35;
$MAXWORDLENGTH_LIMIT =	100;
$URL_ELEM = 41;
$HASHHISTORY_OVERALLOCATE = 1.45;
$HASHHISTORY_TYPE_SIZE = 8;
$DICT_ELEM_SIZE = 24;
$DICT_ELEM_HEADER_SIZE = $DICT_ELEM_SIZE - 2 ;
$DICTIONARY_AVG_WORDLENGTH = 7;
$VARIANT_ELEM_SIZE = 11;
$VARIANT_ELEM_HEADER_SIZE = $VARIANT_ELEM_SIZE - 2;
$PAGES_ARRAY_SIZE =	100000;
$WORDMAP_ROW_SIZE = 9;
$WORDMAP_HEADER_SIZE = $WORDMAP_ROW_SIZE - 1;
$LOGLINELENGTH = 300;
$LOG_NUM_LINES = 5001;
$ROBOTS_TXT_MAX_FILE_SIZE = 10485760;
$FLUSH_PER_PAGELIMIT = 100000;
$EST_AVG_WORDMAP_BYTES_PER_PAGE = 3100;
$EST_AVG_BIG_WORDMAP_BYTES_PER_PAGE = 900;
$EST_AVG_BIG_WORDMAP_BYTES_PER_PAGE_AFTERFLUSH = 80;
$MAX_ACCENT_CHAR_LENGTH = 4;	
$NUM_TOTAL_ACCENT_CHARS = 105;

$MAX_SYNONYM_LENGTH = 100;
$REC_KEYWORD_LEN = 500;
$REC_DESC_LEN = 300;

$MAX_CONFIG_LINELEN = ($URLLENGTH*2)+200;

$PLUGIN_PASSWORD_LEN = 40;

$STEMMER_LANGUAGES = array(
  "danish", 
  "dutch", 
  "english", 
  "finnish", 
  "french", 
  "german", 
  "hungarian", 
  "italian", 
  "norwegian", 
  "porter", 
  "portuguese", 
  "romanian", 
  "russian", 
  "spanish", 
  "swedish", 
  "turkish", 
);

$HTTP_MAX_USERAGENT_LEN = 200;

/* Update this if command or lock flag location changes in MappedStatus struct in ZoomIndexer */
$LOCKFLAG_OFFSET = 0;
$COMMAND_OFFSET = 16;

/* Log message types */
$MSG_INDEXED			= 0;
$MSG_SKIPPED			= 1;
$MSG_INIT					= 2;
$MSG_FILEIO				= 3;
$MSG_DOWNLOAD			= 4;
$MSG_UPLOAD				= 5;
$MSG_PLUGIN				= 6;
$MSG_INFO					= 7;
$MSG_ERROR				= 8;
$MSG_WARNING			= 9;
$MSG_STARTSTOP		= 10;
$MSG_QUEUE				= 11;
$MSG_SUMMARY			= 12;
$MSG_DEBUG				= 13;
$MSG_THREAD				= 14;
$MSG_FILTERED			= 15;
$MSG_BROKENLINKS 	= 16;
$MSG_TYPECOUNT		= 17;


?>
