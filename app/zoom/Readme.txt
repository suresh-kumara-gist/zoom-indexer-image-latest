System Requirements
--------------------

128 MB of memory (more memory is required to index larger sites)
At least 30 megabytes of available disk space
Web server with PHP(5 or higher) installed
PHP needs to support shared memory (compiled with  --enable-shmop)
glibc 2.12 or higher reccomended
libcurl 7.19 or higher recommended


Installation
-------------
This guide was based on a standalone installation of Ubuntu 12.04.

Prerequisites: Apache, PHP5 and the Apache PHP5 module must be installed and working correctly.

 - Un-tar the zoom tar file into this folder (tar -xvf  zoom_linux.tar), copy the "zoom" folder into the Apache DocumentRoot/htdocs folder, the usual default is /var/www.
 - There is a shell script in the zoom folder, install.sh, that can be run to set all the required permissions, this is not a very secure method as it will set permissions so all users can access the files. If you know the user that your webserver runs as you can either alter the script or follow the recommended steps below.
    1. Check that permissions are set so the web server can acess all the php files
   		 	chmod a+r *
		2. Check that the images folder has read and execute permissions set and that the contents of this folder have read permissions
				chmod -R a+r images
				chmod a+rx images
		3. Check that the ZoomEngine executable permissions are set
				chmod a+rx ZoomEngine
		4. Check that  default.zcfg, userinfo.dat, .ZoomLogBuffer, .ZoomPageDataBuffer and .key.tmp files have read/write permissions
				chmod a+rw default.zcfg
				chmod a+rw userinfo.dat
				chmod a+rw .ZoomLogBuffer
				chmod a+rw .ZoomPageDataBuffer
				chmod a+rw .key.tmp
		5. Check that the "plugins" folder and the executables it contains can also be read and executed
				chmod -R a+rx plugins
		6. Check that the "help" folder be read
				chmod -R a+r help
		7. Create a "temp" directory in the "zoom" directory and make sure it can be accessed and has read/write permissions
				mkdir temp
				chmod a+rwx temp
	  8. Check that the ZoomEngine executable can successfully run on this system by using the "ldd" command and that there are no missing libraries. If ldd reports "not a dynamic executable" you may be trying to execute on a 64bit system with no 32bit libraries sinstalled (Zoom Linux is currently 32bit only). Missing libraries will be reported as "not found".
				ldd ZoomEngine
 - Plugin Installation, some plugins are not packaged with Zoom and need to be installed before using if required.
	 If you want to index WordPerfect files (wpd2txt) you will need to install "libwpd-tools" using your Linux distributions package manager (eg "apt-get install libwpd-tools").
 - Open a web browser and point to http://localhost/zoom/ZoomIndexer.php and you should see the initial zoom screen displayed.

Use
------
To create or configure an existing Zoom config file enter a filename (it should end in .zcfg) into the "Zoom config file:" text field.
Clicking the "Configure" button will open a new page that will allow you to edit the configuration values, similar to the Windows Zoom application.
After making ant changes you will need to click the "Save changes" button, the ZoomIndexer page will then be displayed again.
Click start to begin indexing.

Plugin Installation
--------------------
Some plugins are not packaged with Zoom and need to be installed before using;

WordPerfect files (wpd2txt) - Need to install libwpd-tools (as named on ubuntu, use command "sudo apt-get install libwpd-tools")

Possible Issues
---------------

Missing libraries: On some Linux distributions you may need to install missing libraries such as libcurl. You can check all the required libraries
are available by running the ldd command on the ZoomEngine executable.

SELinux alerts: On some distributions that make use of SELinux (Security Enhanced Linux) you may get warning alerts displayed when the Zoom PHP files try
to launch the ZoomEngine executable and ZoomEngine will be blocked from starting. You will need to disable this functionality in order for Zoom to work.
There is some information here,
http://docs.fedoraproject.org/en-US/Fedora/13/html/Managing_Confined_Services/sect-Managing_Confined_Services-The_Apache_HTTP_Server-Booleans.html
http://beginlinux.com/server_training/web-server/976-apache-and-selinux,
that may be useful. We have yet to confirm the solution but enabling the "httpd_execmem" option may allow zoom to execute.

So far we have been unsucessful in our attempts to get Zoom working with SELinux enabled, the major issue appears to be even after enabling the settings below the shared memory access
required by PHP is blocked by SELinux.

setsebool -P httpd_enable_cgi 1
setsebool -P http_builtin_scripting 1

chcon -v --type=httpd_unconfined_script_exec_t /var/www/zoom/ZoomEngine64
chcon -v --type=httpd_unconfined_script_exec_t /var/www/zoom/get_zoom_status.php
chcon -v --type=httpd_unconfined_script_exec_t /var/www/zoom/get_zoom_log.php
chcon -v --type=httpd_unconfined_script_exec_t /var/www/zoom/send_zoom_command.php




Updates
---------------


Version 7.1 build 1002 (22 May 2017)

 - From 3/May/2017 forward, Zoom For Linux is only available under a Server License which is designed
   to run on web servers and allows you to host the indexer on multiple machines within one organisation.
 - Single user desktop licenses for Zoom for Linux has been discontinued due to the complexity of the
   support environment. However, all existing single user licenses of Zoom For Linux has been
   automatically upgraded to a Server License.
 - Fixed bug with PHP script spelling suggestion based on stemmed words.
 - Added support for UTF-8 punctuation entities, e.g. &mdash; and &ndash;
 - Added handling for failing to connect to proxy server.
 - Fixed context description spacing for stand-alone punctuation characters (e.g. Bob & Pete).
   Requires re-indexing.
 - Fixed CGI crash bug when wildcard matching with word where the last character is UTF-8 encoded.
 - Fixed bug with MP3 meta information and MP3 technical data options.
 - Added option to index meta tags outside of <head>...</head> tags.
 - Fixed CGI handling of wildcard search queries and UTF-8 characters.
 - Added configurable Indexing Options for HTML5 tags <header>, <footer>, <nav>, <article>, and <section>.
   Default to not index (but follow links from) header, footer, and nav tags.
 - Fixed bug with ASP search script when searching for words containing '+' character when '+' is enabled
   for word joining.
 - Fixed HTML validation issue for 'zoom_match' label with PHP and ASP scripts.
 - Fixed bug with decoding HTML hexadecimal entities (e.g. a)

V7.1 build 1001, 22 Nov 2016
 - Improved spelling suggestion implementation for PHP, CGI, ASP.NET output
 - Many bug fixes. See http://www.wrensoft.com/zoom/whatsnew.html

V7.1 build 1000, 17 Feb 2016
 - Increased unique words capacity
 - Please see web page for more details at http://www.wrensoft.com/zoom/whatsnew.html

V7.0 build 1008, 6 August 2015
 - Added Autocomplete indexing features (page title, meta keywords and description)
 - Changed PHP GUI to report the version and build number from core engine executable
 - Fixed bug with image meta indexing
 - Fixed bugs with log and status not updating in Chrome

V7.0 build 1007, 22 April 2015
 - Fixed issue with license key recognition being dropped intermittently
 - Fixed bug with autocomplete file creation failing during parsing of searchwords.log file

V7.0 build 1006, 26 March 2015
 - Fixed many critical Zoom core engine bugs
 - Restored autocomplete feature which was unavailable in Linux
 - Updated PHP and ASP scripts

V7.0 build 1005, 11 Febraury 2015
 - Configuration
    The "Start options" will now be displayed by default when opening config.php
    When saving a config file if config.php fails to redirect to ZoomIndexer.php a hyperlink to the page will now be displayed
    If the config.php is reloaded or accessed directly instead of using a default config name a warning and a link to ZoomIndexer.php will now be displayed
 - Configuration (Limits)
		Added extra optimsation levels for consistency
		Added "Limit URLs visited per start point" setting
		Improved the warning messages for licencing and hardware limitations
		Limits in the free edition are no longer editable for consistency
 - Changed build environment so more Linux system are now supported, see revised system requirements
 - Increased the speed of processing the log snapshot messages so it should be more responsive during large indexes
 - Fixed some caching issues that were preventing the indexing statistics and log display from updating properly when using Internet Explorer
 - Fixed a bug where unicode comments in MP3 files were not being indexed correctly
 - Fixed a possible crash during the indexing of MP3 files
 - Fixed a bug in the advanced start point options where the weighting value was not being saved correctly when an item was first created

V7.0 build 1004, 18 September 2014
 - Configuration
	Added Autocomplete options
 - Configuration (Spider options)
   	Added proxy server settings
 - Configuration (Limits)
	Fixed a JavaScript error that was causing the estimated ram use to not be calculated and displayed on page load
 - Updated the pdftotext, officexplugin, swf2html and odt2txt plugins for 64bit
 - Updated ZoomEngine64 to call the new 64bit plugins
 - Fixed a warning message that could erroneously appear about a missing XLS plugin

V7.0 build 1003, 18 July 2014
 - There are now 32bit and 64bit ZoomEngine executables included in the install package. The PHP interface should automatically
   choose which one to use based on machine architecture.
 - Linux CGI search has been updated to include a 64bit version
 - Configuration (Languages);
 	Added "Support latin ligatures" option
- Configuration (Limits);
	Added "Max word length" option
 - Moved the doctotext, xlstotext and ppttotext plugins into the internal Zoom engine so these external plugins no longer need to be used
 - Console mode command line flags added (-c and -autorun) so zoom can be run as a scheduled cron job
 - Updated the help file with how to schedule Zoom to be automatically run
 - Added a warning message if attempting to use the Linux version of Zoom on a non-Linux system

V7.0 build 1002, 6 June 2014
 - Registration page should now report an error if Zoom doesn't respond as expected

V7.0 build 1001, 4 June 2014
 - Registration user name and zoom edition is now displayed in the header of the main and config pages
 - Log snapshot view background colour is now the full length of the line to match full log view
 - Added a separate error message when clicking the start button and Zoom fails to launch due to missing system libraries
 - Made some changes to installer script to check for required libraries
 - Configuration (start options);
    Added "ASP.NET" platform option
 - Configuration (search page);
    "Limit files for this start point" will now only accept numbers
    Added domain name diversity option
    Added default sort results option to Sort results by date
    Added date range sorting options
 - Configuration (limits);
    Added max results per query option
    Changed max unique words to be an optional limit
 - Configuration (Advanced start point options);
    Added default start point from start options page as first entry in list, can now change start point options for this entry
    Now will switch between spider mode and offline mode dynamically (not only when loading config)
 - Fixed a missing configuration value (MAXRESULTSPERQUERY) that was causing an error message when searching using CGI. Affected configuration files will need to be opened and resaved.
 - Help file updates

V7.0 build 1000, 24 March 2014
 - Initial release
