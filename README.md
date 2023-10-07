<b>PHP_TM (php tab management)</b> - simply application to help you organize many of tabs from your browser
<br><br><b>for example:</b>
<br><br><img src="https://raw.githubusercontent.com/alchemist314/images/main/php_tm/tabs.png">
<br><br><b>create a new tab:</b>
<br><br><img src="https://raw.githubusercontent.com/alchemist314/images/main/php_tm/tab_create.png">
<br><b>edit a tab:</b>
<br><br><img src="https://raw.githubusercontent.com/alchemist314/images/main/php_tm/tab_edit.png">
<br><br>
<b>Install:</b>
<br><br>
1. Check you have needed php modules with file install/check_modules.php
2. Export bookmarks as html file from you browser
3. Edit config.php to set up directories and other variables
4. Execute install/sqlite_create.php to create database
5. Use install/bookmarks_parse.php utility to parse your bookmarks.html (or you can add tabs manually)
6. Make sure you have access to write to database from browser (selinux, chmod, e.t.c.)
<br><br>
<b>Features:</b>
<br>- Load/export tabs from html file (like Firefox browser bookmarks)
<br>- Set tab icon from hex color or png pictures and sort them:
<br><br><img src="https://raw.githubusercontent.com/alchemist314/images/main/php_tm/tab_sort.png">
<br><br>- Move tabs between categories:
<br><br><img src="https://raw.githubusercontent.com/alchemist314/images/main/php_tm/tabs_move.png">
<br><br>- Auto add icon and title from url
<br><br><img src="https://raw.githubusercontent.com/alchemist314/images/main/php_tm/tab_new.png">
<br><br>
The way to save all your tabs from browser to bookmarks (Firefox browser, for example):
<br><br>&nbsp;&nbsp;1. First of all you need to install Firefox extension like "save my tabs":
<br>&nbsp;&nbsp;https://addons.mozilla.org/en-US/firefox/addon/save-all-my-tabs/
<br>&nbsp;&nbsp;2. Save all your tabs to booomarks with extension above
<br>&nbsp;&nbsp;3. Export tabs from Firefox (press Ctrl+Shif+O to open bookmarks manager dialog)
<br>&nbsp;&nbsp;4. Now you can parse html with php_tm:
<br>&nbsp;&nbsp;&nbsp;&nbsp;- edit config.php (you should set path to html file you exported from Firefox and other needed variables)
<br>&nbsp;&nbsp;&nbsp;&nbsp;- execute bookmarks_parse.php
