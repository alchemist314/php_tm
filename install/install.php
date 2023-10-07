<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Install PHP_TM</title>
    </head>
    <body>
        <h3>Install</h3>
        <ol>
            <li><a href="check_modules.php" target="blank">Check</a> you have needed php modules with file install/check_modules.php
            <li>Export bookmarks as html file from you browser
            <li>Edit config.php to set up directories and other variables
            <li><a href="create_database.php" target="blank">Execute</a> install/create_database.php to create database
            <li>Use install/bookmarks_parse.php utility to parse your bookmarks.html (or you can add tabs manually)
	    <li>Make sure you have access to write to database from browser (selinux, chmod, e.t.c.)
        </ol>
    </body>
</html>
