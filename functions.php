<?php

/*
MIT License

Copyright (c) 2023-2024 Golovanov Grigoriy
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


/** Example: 
  * fSanitize($vString, 10) - cut string length 10, disallow all
  * fSanitize($vString, 20, "email")  - cut 20, allow  email only
  * fSanitize($vString, 10, array("mysql", "email")) - cut 10, allow email only. add slashes \' (for mysql query)
  * fSanitize($vString, 10, ".")  - cut 10, allow dot only
  * fSanitize($vString, 10, array(".", ","))  - cut 10, allow ".", ","
  * fSanitize($vString, 10, "all") - cut 10, allow all
  * fSanitize($vString, 10, array("all", "mysql")) - cut 10, allow all. add slashes \' (for password fieled)
  * fSanitize($vString, 10, "mysql") - cut 10, add slashes \' (for mysql query)
**/
  
/**
   * Cut and clean the string
   *
   * @param string $vString- строка, integer $vLength - допустимая длина строки
   *
   * @return string обработанная строка
   */

function fSanitize ($vString, $vLength="", $aParam="") {

	$vSQLFlag = false;
	$aExc = array();
        $aHTML = array("&", ";");
	$aEmail = array("@", ".", "-", "_");
	$aColor = array("#");
        $aDisallow = array("<", ">", "?", "%", ";", "+", "-", "=", "(",
            	    ")", "*", "&", "#", "@", "`", "\"", "'", "|",
            	    ",", ".", "{", "}", "/", "^", "\\", "_", ":",
            	    "[", "]", "!", "$", "~");

	$vString = trim($vString);
	$vString = strip_tags($vString);
	(int)$vLength > 0 ? $vString = substr($vString, 0, $vLength) : "";
	if (is_array($aParam))  {
	    if (in_array("email", $aParam)) {
    		$aExc = $aEmail;
		$aParam = array_diff($aParam, array("email"));
	    }
	    if (in_array("all", $aParam)) {
		$aDisallow = array();
		$aParam = array_diff($aParam, array("all"));
	    }
	    if (in_array("html", $aParam)) {
    		$aExc = array_merge($aExc, $aHTML);
	        $vString = htmlentities($vString);
		$aParam = array_diff($aParam, array("html"));
	    }
	    if (in_array("mysql", $aParam)) {
		$vSQLFlag = true;
		$aParam = array_diff($aParam, array("mysql"));
	    }
	    if (in_array("color", $aParam)) {
		$vColor = true;
		$aParam = array_diff($aParam, array("color"));
	    }

	} else {
	    if (strlen($aParam)>0) {
		$aParam=="all" ? $aDisallow = array() : "";
		$aParam=="email" ? $aExc = $aEmail : "";
		$aParam=="html" ? $aExc = $aHTML : "";
		$aParam=="color" ? $aExc = $aColor : "";
		$aParam=="mysql" ? $vSQLFlag = true : "";
	    }
	    $aParam = array($aParam);
	}
	$aExc = array_merge($aExc, $aParam);
	$aDisallow = array_diff($aDisallow, $aExc);
	foreach($aDisallow as $vVal) {
    	    $vString = str_replace($vVal, "", $vString);
	}
	if ($vSQLFlag) {
    	    $vString = mysql_real_escape_string($vString);
	}
	return $vString;
    }

    /**
     * Get favicon source
     *
     * @param string $aURL
     * 
     * @return string Base64 PNG source
     * 
     */

    function fGetIconSource($aURL) {

	// Explode full domain name
	$aEXP_Domain=explode("://", $aURL['url']);
	$aEXP_Domain_Next=explode("/", $aEXP_Domain[1]);

	//Check icon URL
	if (!preg_match("/^http/", $aURL['icon_url'])) {
	    // Restore full icon URL
	    $aURL['icon_url']=$aEXP_Domain[0]."://".$aEXP_Domain_Next[0].$aURL['icon_url'];
	}

	$aContext=[
	    "ssl"=>[
    		"verify_peer"=>false,
    		"verify_peer_name"=>false,
	    ],
	];

	$sGetIcon=file_get_contents($aURL['icon_url'], false, stream_context_create($aContext));

        $aEXP=explode(".", $sURL);
        $sImageExtension=$aEXP[(count($aEXP)-1)];

	// Convert SVG to PNG
        if (preg_match("/svg/", $sImageExtension)) {
	    if (extension_loaded('imagick')) {
		$oIM = new Imagick();
		$oIM->readImageBlob($sGetIcon);
		$oIM->setImageFormat("png24");
		$sGetIcon=$oIM->__toString();
		$oIM->clear();
		$oIM->destroy();
	    }
	}
	
	$sResult=base64_encode($sGetIcon);
	$sIconSource="data:image/png;base64,".$sResult;

	return $sIconSource;
    }

/**
 * Get title and favicon url
 * 
 * @param string $sUrl
 * @return string
 */
    function fParseHTML($sUrl) {

	$aResult['url']=$sUrl;
	$oHTML = new DOMDocument();
	$oHTML->strictErrorChecking = FALSE;

	if ($oHTML->loadHTML(file_get_contents($sUrl))) {
	    $oList=$oHTML->getElementsByTagName("title");
    	    $aResult['title'] = str_replace(["\r", "\n"], "", $oList->item(0)->nodeValue);
	    $oLink=$oHTML->getElementsByTagName("head")->item(0)->getElementsByTagName("link");

	    foreach($oLink as $oElements) {
    		if ($oElements->hasAttribute("rel") && $oElements->hasAttribute("href")) {
        	    if (preg_match("/icon/", $oElements->getAttribute("rel"))) {
			$aResult['icon_url']=$oElements->getAttribute("href");
        	    }
    		}
	    }
   	}

	return $aResult;
    }
    
    /**
     * Error handler
     *
     * @param array of strings
     * 
     */
    function fErrorHandler($aParam) {
        if (PHP_SAPI==='cli') {
            print "filename: " . $aParam['filename'] . ", line: " . $aParam['line'] . ", SQLite Error: \e[91m" . $aParam['error'] . "\e[39m \n";
        } else {
            print "<div style='padding:10px;background-color:#ffe8b7;'><b>filename:</b> " . $aParam['filename'] . "<br><b>line:</b> " . $aParam['line'] . "<br><b>SQLite Error: </b><font color=\"red\">" . $aParam['error'] . "</font></div><br>";
        }
    }

    /**
     * Message handler
     *
     * @param array of strings
     * 
     */
    function fMessageHandler($aParam) {
        if (PHP_SAPI==='cli') {
            print $aParam['message'] . "\e[92m" . $aParam['additional_message']."\e[39m \n";
        } else {
            print "<div style='padding:10px;background-color:#ffe8b7;'>" . $aParam['message'] . "<font color=\"green\">" . $aParam['additional_message'] . "</font></div><br>";        
        }
    }
    
    /**
     * Load categories from description table
     * called from tabs.php
     * 
     * @global object $oPDO - SQLite object
     * @return string array
     */

function fLoadCategories() {

    global $oPDO;

	($_REQUEST['frm_sort_type']==false) ? $sSortAction="cat_sort DESC" : $sSortAction="cat_desc";	

        $sQuery="
        SELECT *
        FROM
             ".PHP_TM_PDO_TABLENAME_DESC." 
        ORDER BY 
    	    ".$sSortAction;

	//print $sQuery;

	try {
    	    $oResults = $oPDO->query($sQuery);
	    foreach ($oResults->fetchAll() as $aRow) {
		strlen($aRow['cat_sort'])<1 ? $aRow['cat_sort']=$aRow['cat_id'] : "";
		$aResult['cat_id'][$aRow['id']]=$aRow['cat_id'];
		$aResult['cat_desc'][$aRow['id']]=$aRow['cat_desc'];
		$aResult['cat_icon'][$aRow['id']]=$aRow['cat_icon'];
		$aResult['cat_color'][$aRow['id']]=$aRow['cat_color'];
		$aResult['cat_sort'][$aRow['id']]=$aRow['cat_sort'];
		$aResult['cat_type'][$aRow['id']]=$aRow['cat_type'];
	    }
        } catch(PDOException $e) {
	    fErrorHandler(["filename"=>__FILE__, "line"=>__LINE__, "error"=>$e->getMessage()]);
        }

    return $aResult; 
}


/**
 * Load urls and titles from data table
 * called from index.php
 * 
 * @global object $oPDO - SQLite object
 * @global string $sExportString - prepare string for exports bookmarks
 * @global string $sHexColor - icon color
 * @param integer $sTabParentID - category id
 */
function fLoadTabs($sTabParentID) {

	global $oPDO,
	       $sExportString,
	       $sHexColor;
	
        
        
    $aExtensionsArray =[
      'pdf','txt', 'htm',
      'html', 'js','php',
      'java', 'py', 'asp',
      'shtml', 'aspx', 'cpp',
      'doc', 'xls', 'xlsx',
      'docx', 'odf', 'ods',
      'odt', 'xml',
      'avi', 'mp3', 'mp4',
      'mpeg4', 'wav', 'mpeg',
      'ico', 'jpg', 'jpeg',
      'bmp', 'gif', 'tiff',
      'png', 
      'zip', 'tar', 'tgz',
      'gz', 'rar', '7z',
      'exe', 'bat', 'msi',
      'iso', 'img', 'vmdk'
    ];
    
        
        $sQuery="
        SELECT *
        FROM
            ".PHP_TM_PDO_TABLENAME_DATA." 
        WHERE 
    	    tab_parent_id='".$sTabParentID."'";

	//print $sQuery;
	try {
    	    $oResults = $oPDO->query($sQuery);

	    foreach ($oResults->fetchAll() as $aRow) {
		if ($aRow['tab_parse_id']!==$sTabParentID) {

		    // Explode domain name
		    $aEXP_Domain=explode("://", $aRow['tab_url']);
		    $aEXP_Domain_Next=explode("/", $aEXP_Domain[1]);
                    
                    // Get file extension
                    $aSplitUrl=explode(".", $aEXP_Domain[1]);
                    $sLastElement=$aSplitUrl[count($aSplitUrl)-1];
                    if (in_array($sLastElement,$aExtensionsArray)) {
                        $sExtension="(".$sLastElement.")";
                    } else {
                        $sExtension="";                    
                    }
                    
		    strlen($aRow['tab_icon'])>10 ? $aDomainName[$aEXP_Domain_Next[0]]=$aRow['tab_icon'] : $aDomainName[$aEXP_Domain_Next[0]]='';
        	    $sExportString .="<DT><A HREF=\"".$aRow['tab_url']."\" ADD_DATE=\"".time()."\" LAST_MODIFIED=\"".time()."\" ICON_URI=\"".$aRow['tab_icon_url']."\" ICON=\"".$aRow['tab_icon']."\">".$aRow['tab_title']."</A>\n";
		    
		    ?>
		<table cellpadding="1" cellspacing="1" border="0" style="border-style: solid; border-left: <?=$sHexColor?> 2px solid;">
		    <tr>
			<td style="padding-left: 5px;">
			    <input type="checkbox" name="frm_check_<?=$aRow['id']?>">
			</td>
			<td style="padding-left:5px;padding-right:5px;" align="center" valign="center">
				<a href="javascript:void(0)" onclick="if (confirm('Удалить <?=substr($aRow['tab_title'],0,150)?>?')) { document.getElementById('frm_delete_value_id').value='<?=$aRow['id']?>'; document.getElementById('frm_delete').submit();}"><img src="img/close.png"></a>
			</td>
			<td style="padding-left:3px;">
				<a href="javascript:void(0)" onclick="if (confirm('Обновить иконку <?=substr($aRow['tab_title'],0,150)?>?')) { document.getElementById('frm_update_icon_id').value='<?=$aRow['id']?>'; document.getElementById('frm_update_icon_domain').value='<?=$aRow['tab_url']?>';  document.getElementById('frm_icon_update').submit();}"><img src="img/refresh_ico.png" width=45 height=22></a>
			</td>
                        <td style="padding-left:3px;">
                            <a href="javascript:void(0)" onclick="document.getElementById('frm_add_tab_action_<?= $sTabParentID ?>').value = 'edit';
                                                        document.getElementById('frm_add_tab_title_<?= $sTabParentID ?>').value = '<?= $aRow['tab_title'] ?>';
                                                        document.getElementById('frm_add_tab_url_<?= $sTabParentID ?>').value = '<?= $aRow['tab_url'] ?>';
                                                        document.getElementById('frm_add_tab_edit_item_id_<?= $sTabParentID ?>').value = '<?= $aRow['id'] ?>';
                                                        document.getElementById('frm_add_tab_submit_<?=$sTabParentID?>').value = 'Update';
                                                        location.href = '#edit_tab_<?= $sTabParentID ?>'"><img src="img/edit.png" width=45 height=22></a>
                        </td>
			<td style="padding-left:10px; white-space: nowarp;">
			    <img src="<?=$aDomainName[$aEXP_Domain_Next[0]]?>" width="24" height="24"><?=$sID_URL?>
			</td>
			<td style="padding-left:5px;">
			     <a href="<?=$aRow['tab_url']?>" target='blank'><?=substr($aRow['tab_title'],0,PHP_TM_STRING_LENGTH)?></a> <?=$sExtension?>
			</td>
		    </tr>
		</table>
		    <?php
		}
	    }
        } catch(PDOException $e) {
	    fErrorHandler(["filename"=>__FILE__, "line"=>__LINE__, "error"=>$e->getMessage()]);
        }
    }
