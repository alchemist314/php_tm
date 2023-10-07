Simply application to help you organize many of tabs in your browser

1. Check you have needed php modules with file install/check_modules.php
2. Export bookmarks as html file from you browser
3. Edit config.php to set up directories and other variables
4. Execute install/sqlite_create.php to create database
5. Use install/bookmarks_parse.php utility to parse your bookmarks.html (or you can add tabs manually)
6. Make sure you have access to write to database from browser (selinux, chmod, e.t.c.)

Features:
    - Load tabs from html (browser bookmarks)
    - Export tabs to html (browser bookmarks)
    - Auto add icon and title from url
    - Set tab icon from hex color or png pictures
    - Move tabs between categories