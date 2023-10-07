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

$aF = file(PHP_TM_PATH_TO_IMPORT_BOOKMARK_FILE);
$sLineCount = 0;
foreach ($aF as $sFullStr) {
    if (preg_match("/<A HREF=/", $sFullStr)) {

        $sFullStr = trim(preg_replace('/\t+/', '', $sFullStr));
        $aExplodeString = explode("<A HREF=", $sFullStr);
        $aExplodeSpace = explode(" ", $aExplodeString[1]);

        $aExplodeIconTMP = explode(" ICON=", $sFullStr);
        $aExplodeIconNext = explode(" ", $aExplodeIconTMP[1]);
        $aExplodeIcon = explode("\"", $aExplodeIconNext[0]);

        $aExplodeIconURL_TMP = explode(" ICON_URI=", $sFullStr);
        $aExplodeIconNextURL = explode(" ", $aExplodeIconURL_TMP[1]);

        // Get icon URL
        $sIconURL = str_replace("\"", "", $aExplodeIconNextURL[0]);

        // Get icon
        $sIcon = str_replace("\"", "", $aExplodeIcon[1]);

        // Get URL
        $sURL = str_replace("\"", "", $aExplodeSpace[0]);
        $sStr = $sURL;

        // Explode domain name
        $aEXP_Domain = explode("://", $sURL);
        $aEXP_Domain_Next = explode("/", $aEXP_Domain[1]);
        $sDomainName = $aEXP_Domain_Next[0];

        // Title
        preg_match('/<A [^>]*>(.+)<\/A>/', $sFullStr, $aMatches);
        $sTitle = $aMatches[1];

        $aURL_Separator = explode("/", $sStr);

        $aHTML['icon'][$sLineCount] = $sIcon;
        $aHTML['icon_url'][$sLineCount] = $sIconURL;
        $aHTML['title'][$sLineCount] = $sTitle;

        $sLastStrValue = trim($aURL_Separator[array_key_last($aURL_Separator)]);
        $aAll_URLs[$sLineCount] = $sStr;
        $sLineCount++;
    }
}

array_unique($aHTML);
$aUniqHTML = array_unique($aAll_URLs);
foreach ($aUniqHTML as $sID_URL => $sStr_URL) {
    try {
        $sQuery = "INSERT INTO " . PHP_TM_PDO_TABLENAME_DATA .
                "(
                 tab_parse_id,
                 tab_parent_id,
                 tab_url,
                 tab_title,
                 tab_icon,
		 tab_icon_url
                 ) 
                VALUES 
                (" . $sID_URL . ",
                 0,
                 '" . str_replace(["\r", "\n"], "", $sStr_URL) . "',
                 '" . $aHTML['title'][$sID_URL] . "',
                 '" . $aHTML['icon'][$sID_URL] . "',
                 '" . $aHTML['icon_url'][$sID_URL] . "'
                 )";

        //print $sQuery."\n";
        $oResults = $oPDO->query($sQuery);
    } catch (PDOException $e) {
        fErrorHandler(["filename" => __FILE__, "line" => __LINE__, "error" => $e->getMessage()]);
    }
}
?>
