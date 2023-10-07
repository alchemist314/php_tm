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

include "../config.php";
include "../db.php";
include "../functions.php";

file_put_contents(PHP_TM_SQLITE_PATH, "");
chmod(PHP_TM_SQLITE_PATH, 0766);

$vQuery = "
/* DATA TABLE */

CREATE TABLE IF NOT EXISTS `" . PHP_TM_PDO_TABLENAME_DATA . "` (
`id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
`tab_parse_id` INTEGER NOT NULL,
`tab_parent_id` INTEGER NOT NULL,
`tab_url` TEXT DEFAULT '',
`tab_title` TEXT DEFAULT '',
`tab_icon` TEXT DEFAULT '',
`tab_icon_url` TEXT DEFAULT ''
);
";

try {
    $oResult = $oPDO->query($vQuery);
    fMessageHandler(["message" => "Create data table: ", "additional_message" => "success!"]);
} catch (PDOException $e) {
    fErrorHandler(["filename" => __FILE__, "line" => __LINE__, "error" => $e->getMessage()]);
}

$vQuery = "

/* DESCRIPTION TABLE */

CREATE TABLE IF NOT EXISTS `" . PHP_TM_PDO_TABLENAME_DESC . "` (
`id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
`cat_id` INTEGER NOT NULL,
`cat_desc` TEXT DEFAULT '',
`cat_icon` TEXT DEFAULT '',
`cat_color` TEXT DEFAULT '',
`cat_sort` INTEGER NULL,
`cat_type` INTEGER NULL
);
";

//print $vQuery;

try {
    $oResult = $oPDO->query($vQuery);
    fMessageHandler(["message" => "Create description table: ", "additional_message" => "success!"]);
} catch (PDOException $e) {
    fErrorHandler(["filename" => __FILE__, "line" => __LINE__, "error" => $e->getMessage()]);
}
?>