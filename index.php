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

?>

<html>
<head>
    <title>Tabs</title>
    <link href="css/styles.css" rel="stylesheet" type="text/css">
</head>
<body>

<?php

session_start();

include "config.php";
include "db.php";
include "functions.php";
include "header.php";


if (isset($_REQUEST['frm_sort'])) {
    $_SESSION['frm_sort']=$_REQUEST['frm_sort'];
} else {
    $_REQUEST['frm_sort']=$_SESSION['frm_sort'];
}

	$_REQUEST['frm_sort']>1 ? $sSortAction="cat_sort DESC" : $sSortAction="cat_desc";

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
		$aCatID[$aRow['id']]=$aRow['cat_id'];
		$aCatDesc[$aRow['id']]=$aRow['cat_desc'];
		$aCatIcon[$aRow['id']]=$aRow['cat_icon'];
		$aCatColor[$aRow['id']]=$aRow['cat_color'];
		$aCatType[$aRow['id']]=$aRow['cat_type'];
	    }
        } catch(PDOException $e) {
	    fErrorHandler(["filename"=>__FILE__, "line"=>__LINE__, "error"=>$e->getMessage()]);
        }

    $sCheckBoxAddTabAutoTitle=false;
    foreach($aCatID as $sID) {
	if ($_REQUEST["frm_add_tab_submit_$sID"]=="Add" || $_REQUEST["frm_add_tab_submit_$sID"]=="Update") {
	    if ($_REQUEST["frm_add_tab_auto_title_$sID"]=="on") {
		$sCheckBoxAddTabAutoTitle=true;
	    }
	    $aAddTab['title_manual']=$_REQUEST["frm_add_tab_title_$sID"];
	    $aAddTab['cat_id']=$sID;
	    $aAddTab['action']=$_REQUEST["frm_add_tab_action_$sID"];
	    $aAddTab['tab_id']=$_REQUEST["frm_add_tab_edit_item_id_$sID"];
	    $aAddTab['url']=$_REQUEST["frm_add_tab_url_$sID"];
	}
    }

if ($_REQUEST['frm_category_action']) {
    foreach($_REQUEST as $sName=>$sVal){
        $aEXP_frm_check=explode("_", $sName);
            if (preg_match("/frm_check/", $sName)==true) {
                $aAllRequestID[]=$aEXP_frm_check[2];
                if (preg_match("/on/", $sVal)==true) {
                    $aChecked[]=$aEXP_frm_check[2];
		    isset($sIDList)? $sIDList.=",".$aEXP_frm_check[2] : $sIDList.=$aEXP_frm_check[2];
		}
	    }
    }

    // Move selected tabs to another category
    if ($_REQUEST['frm_action']=="move") {
        $sQuery="
        UPDATE 
    	    ".PHP_TM_PDO_TABLENAME_DATA." 
        SET 
    	    tab_parent_id=".$_REQUEST['frm_category']."
        WHERE 
    	    id IN (".$sIDList.")";
        $sMessage="Move";
    }
    
    // Delete selected tabs
    if ($_REQUEST['frm_action']=="delete") {
        $sQuery="
        DELETE FROM 
    	    ".PHP_TM_PDO_TABLENAME_DATA."
        WHERE 
    	    id IN (".$sIDList.")";
        $sMessage="Delete";
    }
    
	//print $sQuery;
	try {
    	    $oResults = $oPDO->query($sQuery);
            strlen($sMessage) >0 ? fMessageHandler(["message"=>$sMessage." tabs: ", "additional_message"=>" complete!"]) : "";
        } catch(PDOException $e) {
	    fErrorHandler(["filename"=>__FILE__, "line"=>__LINE__, "error"=>$e->getMessage()]);
        }
}

// Delete one selected tab
if ($_REQUEST['frm_action']=="delete_one") {
        $sQuery="
        DELETE FROM 
    	    ".PHP_TM_PDO_TABLENAME_DATA." 
        WHERE 
    	    id=".$_REQUEST['frm_delete_value_id'];

	//print $sQuery;
	try {
    	    $oResults = $oPDO->query($sQuery);
            fMessageHandler(["message"=>"Deleted tab: ", "additional_message"=>" complete!"]);
        } catch(PDOException $e) {
	    fErrorHandler(["filename"=>__FILE__, "line"=>__LINE__, "error"=>$e->getMessage()]);
        }
}


// Update selected icon
if ($_REQUEST['frm_action']=="update_icon") {

    $aResult = fParseHTML($_REQUEST['frm_update_icon_domain']);
    $sIconSource = fGetIconSource($aResult);

        $sQuery="
        UPDATE 
    	    ".PHP_TM_PDO_TABLENAME_DATA." 
    	SET 
    	    tab_icon='".$sIconSource."'
        WHERE 
    	    id=".$_REQUEST['frm_update_icon_id'];

	//print $sQuery;
	try {
    	    $oResults = $oPDO->query($sQuery);
            fMessageHandler(["message"=>"Icon update: ", "additional_message"=>" complete!"]);
        } catch(PDOException $e) {
	    fErrorHandler(["filename"=>__FILE__, "line"=>__LINE__, "error"=>$e->getMessage()]);
        }
}

// Update existing tab
if (($aAddTab['action']=="edit") && (strlen($aAddTab['url'])>0)) {
    $sQuery="
    UPDATE 
	".PHP_TM_PDO_TABLENAME_DATA." 
    SET 
	tab_title='".$aAddTab['title_manual']."', 
	tab_url='".$aAddTab['url']."'
    WHERE id = ".$aAddTab['tab_id'];

    //print $sQuery;
    try {
	$oResults = $oPDO->query($sQuery);
        fMessageHandler(["message"=>"Tab update: ", "additional_message"=>" complete!"]);
    } catch(PDOException $e) {
	fErrorHandler(["filename"=>__FILE__, "line"=>__LINE__, "error"=>$e->getMessage()]);
    }

}

// Add new tab
if (($aAddTab['action']=="add_tab") && (strlen($aAddTab['url'])>0)) {

    if ($sCheckBoxAddTabAutoTitle==true) {
	$aResult = fParseHTML($aAddTab['url']);
	$sIconSource = fGetIconSource($aResult);
	$sAddTabTitle=$aResult['title'];
    } else {
	$sAddTabTitle=$aAddTab['title_manual'];
    }

        $sQuery="
        INSERT INTO 
    	    ".PHP_TM_PDO_TABLENAME_DATA." (
    		tab_url,
    		tab_title,
    		tab_icon_url,
    		tab_icon,
    		tab_parent_id,
    		tab_parse_id
    	    )
	VALUES 
		('".$aAddTab['url']."',
		'".$sAddTabTitle."',
		'".$aResult['icon_url']."',
		'".$sIconSource."',
		".$aAddTab['cat_id'].",
		-1
	)";

	//print $sQuery;
	try {
    	    $oResults = $oPDO->query($sQuery);
            fMessageHandler(["message"=>"Add new tab: " . $sAddTabTitle, "additional_message"=>" complete!"]);
        } catch(PDOException $e) {
	    fErrorHandler(["filename"=>__FILE__, "line"=>__LINE__, "error"=>$e->getMessage()]);
        }
}

?>

<script>

    function fPreparedHTML() {
	aHTML[0]='selected&nbsp;';
	aHTML[1]='to:&nbsp;<select name="frm_category">'
	<?php
	foreach ($aCatDesc as $sID => $sCatDesc) {
	?>
	aHTML[1]+='<option value=<?=$aCatID[$sID]?>><?=str_replace(["\r", "\n"], "", $sCatDesc)?></option>';
	<?php
	}
	?>
	aHTML[1]+='</select>';
	aHTML[2]='<input type="hidden" name="frm_action" id="frm_action" value="">';
	aHTML[3]='&nbsp;<input type="button" value="OK" onclick="fSubmitMainForm();">';
	aHTML[4]='<select name="frm_sort" onchange="document.getElementById(\'frm_main_form\').submit();">';
	aHTML[4]+='<option value="0">---</option>';
	aHTML[4]+='<option value="1">auto by name</option>';
	aHTML[4]+='<option value="2">user preferences</option>';
	aHTML[4]+='</select>';
    }

</script>
    <form method="post" action="index.php" name="frm_main_form" id="frm_main_form">
    <table>
	<tr>
	    <td>
		<select name="frm_category_action" id="frm_category_action" onchange="fChangeFrmSelect(this.value);">
		    <option value="0">---</option>
		    <option value="1">Move</option>
		    <option value="2">Delete</option>
		    <option value="3">Export</option>
		    <option value="4">Sort</option>
		</select>
	    <td>
	    <td id="frm_td_select">
	    </td>
	</tr>
    </table>
    <table cellpadding="1" cellspacing="1" border="0" style="width:100%">
    <?php
	$sExportString = "
		<!DOCTYPE NETSCAPE-Bookmark-file-1>
		    <META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=UTF-8\">
		    <TITLE>Bookmarks</TITLE>
		    <H1>Bookmarks menu</H1>
			<DL><p>
			<DL><p>
    			<DT><H3 ADD_DATE=\"".time()."\" LAST_MODIFIED=\"".time()."\">bookmarks</H3>
    			<DL><p>
		";

	foreach ($aCatID as $sID => $sCatID) {
	    $sHexColor=$aCatColor[$sID];
	?>

	<tr>
	    <td valign="top" width="10">
		<?php
    		    $sEmptyIconFlag=false;
                
		    if ($aCatType[$sID]==1) {
			!strlen($aCatIcon[$sID]) >0 ?  $sEmptyIconFlag=true : "";
			$sShowIcon="<img src=\"".PHP_TM_HTTP_ROOT."/tmp/icon_cache/".$aCatIcon[$sID]."\" width=\"24\" height=\"24\">";
		    } else {
			$sShowIcon="<div style=\"background-color:".$aCatColor[$sID].";width:24px;height:24px;\"></div>";
		    }
		    // Detect color in icon URL
		    if (preg_match("/^#(?:[0-9a-fA-F]{3,6})$/", $aCatIcon[$sID])) {
			$sShowIcon="<div style=\"background-color:".$aCatColor[$sID].";width:24px;height:24px;\"></div>";
		    }
		    
            	    // Detect empty icon
        	    if ($sEmptyIconFlag) {
			$sShowIcon="<div style=\"background-color:".$aCatColor[$sID].";width:24px;height:24px;\"></div>";
        	    }

		?>
		<a href="javascript:void(0)" onclick="fCounter(<?=$sCatID?>);"><?=$sShowIcon?></a>
	    </td>
	    <td valign="top" width="1">
		&nbsp;&nbsp;
	    </td>
	    <td>
		<a href="javascript:void(0)" onclick="fCounter(<?=$sCatID?>);">
		<?=$aCatDesc[$sID]?>
		</a>
		<div id="z_<?=$sCatID?>" style="display:none; background-color: #f8f8f8; padding:5px; width:90%;">
	<?php
            // Load urls and titles by category id
	    fLoadTabs($sCatID);
	?>
		<table cellpadding="0" cellspacing="0" border="0">
		    <tr>
			<td colspan="3">

	    <a name="edit_tab_<?=$sCatID?>">
	    <form method="post" action="index.php" name="frm_add_tab_<?=$sCatID?>" id="frm_add_tab_<?=$sCatID?>">
		<br>
		<table border="0">
		    <tr>
			<td>Tab Title:</td><td><input type="text" name="frm_add_tab_title_<?=$sCatID?>" id="frm_add_tab_title_<?=$sCatID?>" value="" size="50"></td>
		    </tr>
		    <tr>
			<td>Tab URL:</td><td><input type="text" name="frm_add_tab_url_<?=$sCatID?>" id="frm_add_tab_url_<?=$sCatID?>" value="" size="50"></td>
		    </tr>
		    <tr>
			<td colspan="2">Add title and favicon automatically:<input type="checkbox" name="frm_add_tab_auto_title_<?=$sCatID?>" id="frm_add_tab_auto_title_<?=$sCatID?>">
			    <input type="hidden" name="frm_add_tab_action_<?=$sCatID?>" id="frm_add_tab_action_<?=$sCatID?>" value="add_tab">
			    <input type="hidden" name="frm_add_tab_edit_item_id_<?=$sCatID?>" id="frm_add_tab_edit_item_id_<?=$sCatID?>" value="">
			    <input type="submit" name="frm_add_tab_submit_<?=$sCatID?>" id="frm_add_tab_submit_<?=$sCatID?>" value="Add">
			    <input type="button" name="frm_add_tab_cancel_<?=$sCatID?>" onclick="document.getElementById('frm_add_tab_action_<?=$sCatID?>').value = 'add_tab';
			    									 document.getElementById('frm_add_tab_submit_<?=$sCatID?>').value = 'Add';
			    									 document.getElementById('frm_add_tab_edit_item_id_<?=$sCatID?>').value = '';
			    									 document.getElementById('frm_add_tab_title_<?=$sCatID?>').value = '';
			    									 document.getElementById('frm_add_tab_url_<?=$sCatID?>').value = '';" value="Cancel">
			</td>
		    </tr>
		</table>
	    </form>
			</td>
		    </tr>
		</table>
	</div>
	<?php
	}

	// Export tabs to a file
	if ($_REQUEST['frm_action']=="export") {
	    $sExportString .=" 	  </DL><p>
				</DL><p>
			     </DL>";

	    file_put_contents(PHP_TM_PATH_TO_EXPORT_BOOKMARK_FILE, $sExportString);
            fMessageHandler(["message"=>"Bookmarks export: ", "additional_message"=>" complete!"]);
        }
    ?>
	    </td>
	</tr>
    </table>
    </form>

    <form method="post" action="index.php" id="frm_delete" name="frm_delete">
	<input type="hidden" name="frm_delete_value_id" id="frm_delete_value_id" value="">
	<input type="hidden" name="frm_action" value="delete_one">
    </form>

    <form method="post" action="index.php" name="frm_icon_update" id="frm_icon_update">
	<input type="hidden" name="frm_update_icon_id" id="frm_update_icon_id" value="">
	<input type="hidden" name="frm_update_icon_domain" id="frm_update_icon_domain" value="">
	<input type="hidden" name="frm_action" value="update_icon">
    </form>

<script src="js/actions.js"></script>

</body>
</html>
    