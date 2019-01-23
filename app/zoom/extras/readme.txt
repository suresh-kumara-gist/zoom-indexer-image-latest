================================================================================
ZOOM SEARCH ENGINE
Extras and other optional features

Copyright (c) Wrensoft 2012 (http://www.wrensoft.com/)
================================================================================

This "extras" folder contains optional scripts that can be used with the
Zoom Search Engine package. They provide additional functionality that 
could not be implemented within the main scripts.

--------------------------------------------------------------------------------
CONTENTS
--------------------------------------------------------------------------------
1. Jump to highlighting script (highlight.js)
2. Server-side Statistics PHP script (report.php)
3. Date picker popup selector (zoom_datepicker.js)
4. Date picker default CSS file (zoom_datepicker.css)
5. Autocomplete script (zoom_autocomplete.js)
6. Autocomplete default CSS file (zoom_autocomplete.css)


--------------------------------------------------------------------------------
Jump to highlighting script (highlight.js)
--------------------------------------------------------------------------------
This script allows you to have search results which, when you click on the link, 
will open the document, and scroll to the matched word found. It will also 
highlight all instances of the words that were found on that document. 

You can find information on how to use this script here:
http://www.wrensoft.com/zoom/support/highlighting.html

Also see chapter 7.9 in the Users Guide:
http://www.wrensoft.com/zoom/usersguide.html


--------------------------------------------------------------------------------
Server-side Statistics PHP script (report.php)
--------------------------------------------------------------------------------
If you have PHP support on your web server, you can use the provided script 
to provide online reporting for your search log. This allows you to provide 
live, up-to-date statistics on your website without needing to download the 
log file manually and generate/upload your report.

More information on how to use this can be found in the Help file or under 
chapter 7.10 of the Users Guide:
http://www.wrensoft.com/zoom/usersguide.html


--------------------------------------------------------------------------------
Date picker popup selector (zoom_datepicker.js)
--------------------------------------------------------------------------------
This is a Javascript that can be used by Zoom to provide a date selector popup
window in your search form. This is necessary if you have enabled date range
searching (to find pages within a certain date range).

Simply enable the option in the Indexer ("Configure"->"Search page"->"Enable
Date Range searching") and re-index. Then upload "zoom_datepicker.js" to the same 
folder that you have uploaded your search files to so that it is in the same 
folder as your ZDAT files. That should be all you need to do.

If you are unable to host the "zoom_datepicker.js" files in the same folder
as the search files (for example, if you are using CGI and your "cgi-bin" folder
does not allow ".js" files to be hosted), then you will need to specify an
alternative path for the date picker script ("Configure"->"Advanced"->"Alternate
DatePicker path"). You will need to re-index for this change to take effect. Then,
you will need to upload the "zoom_datepicker.js" file to the folder specified
by your custom path.


--------------------------------------------------------------------------------
Date picker default CSS file (zoom_datepicker.css)
--------------------------------------------------------------------------------
This is a default CSS file for the date picker popup selector described above. 
You can simply link to this CSS file where you want to use the date picker
control (e.g. search_template.html or search.html) with a tag such as this:

<link rel="stylesheet" href="zoom_datepicker.css" type="text/css">

Alternatively, you can simply copy the contents of this file and paste it
into your own CSS file or the <style>...</style> section of your page.

Of course, you can modify the CSS here to customize the appearance of the
date picker window.


--------------------------------------------------------------------------------
Autocomplete script (zoom_autocomplete.js)
--------------------------------------------------------------------------------
This is a Javascript that can be used by Zoom to provide autocomplete for your
search box so that suggestions are provided to the end user as they type.

You will need to enable the option in the Indexer ("Configure"->"Autocomplete"->
"Enable auto complete"). You should also specify a list of words here that you
want to provide as autocomplete suggestions. If you have enabled search
statistics logging, you can also include the most popular search queries made
on your website by pointing it at the Statistics Log file.

When you save these settings and re-index, you will find a copy of this file
(along with zoom_autocomplete.css) will be made in your output folder and
they will be available for uploading with FTP.


--------------------------------------------------------------------------------
Autocomplete default CSS file (zoom_autocomplete.css)
--------------------------------------------------------------------------------
This is a default CSS file for the autocomplete feature. You can modify this
as needed.


--------------------------------------------------------------------------------
TROUBLESHOOTING
--------------------------------------------------------------------------------
The abovementioned support pages should address most issues and we recommend you
read them thoroughly first for all known issues and questions.

There is also a general FAQ and Support section on our website here:
http://www.wrensoft.com/zoom/support/index.html

Wrensoft Web Developments
zoom@wrensoft.com
http://www.wrensoft.com/