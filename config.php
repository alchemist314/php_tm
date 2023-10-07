<?php

/*
MIT License

Copyright (c) 2023 Golovanov Grigoriy
Contact e-mail: magentrum@gmail.com


Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

*/

ini_set("display_errors", 0);
//ini_set("display_errors", 1);
//ini_set("error_reporting", E_ALL & ~E_NOTICE & ~E_DEPRECATED);
ini_set("error_reporting", E_WARNING);


// Path to SQLITE Database
define('PHP_TM_SQLITE_PATH', '/var/www/html/git/tabs/db/tab_manager.db'); 

// Path to scripts folder
define('PHP_TM_HTTP_ROOT', 'https://192.168.1.3/git/tabs'); 
// Path to export bookmarks file
define('PHP_TM_PATH_TO_EXPORT_BOOKMARK_FILE', '/var/www/html/git/tabs/tmp/BOOKMARKS.html');
// Path to import bookmarks file
define('PHP_TM_PATH_TO_IMPORT_BOOKMARK_FILE', '/var/www/html/git/tabs/tmp/BOOKMARKS.html');
// Path to icons folder
define('PHP_TM_PATH_TO_ICON_CACHE_FOLDER', '/var/www/html/git/tabs/tmp/icons_cache');
// Tab length for view
define('PHP_TM_STRING_LENGTH', 100);

// Security (sanitize variables)
define('PHP_TM_SECURITY', false);

// Database name
define('PHP_TM_PDO_DBNAME', 'tm'); 

// Table name
define('PHP_TM_PDO_TABLENAME_DATA', 'tab_data'); 
define('PHP_TM_PDO_TABLENAME_DESC', 'tab_desc'); 


?>