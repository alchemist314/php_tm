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
        <title>Sort</title>
        <link href="css/styles.css" rel="stylesheet" type="text/css">
    </head>
    <script src="js/action.js"></script>
    <body>

        <?php
        session_start();

        include "config.php";
        include "functions.php";
        include "db.php";
        include "header.php";

        if (isset($_REQUEST['frm_move_action'])) {
            $_SESSION['frm_move_action'] = $_REQUEST['frm_move_action'];
        } else {
            $_REQUEST['frm_move_action'] = $_SESSION['frm_move_action'];
        }
        if (isset($_REQUEST['frm_sort_type'])) {
            $_SESSION['frm_sort_type'] = $_REQUEST['frm_sort_type'];
        } else {
            $_REQUEST['frm_sort_type'] = $_SESSION['frm_sort_type'];
        }

        switch ($_REQUEST['frm_move_action']) {
            case 0:
                $sSelectedFlag[0] = "selected";
                // Set icon type=color
                $sQuery = "
	    UPDATE
		" . PHP_TM_PDO_TABLENAME_DESC . " 
	    SET 
		cat_type=2";
                break;
            case 1:
                $sSelectedFlag[1] = "selected";
                // Set icon type=color
                $sQuery = "
	    UPDATE
		" . PHP_TM_PDO_TABLENAME_DESC . " 
	    SET 
		cat_type=2";
                break;
            case 2:
                $sSelectedFlag[2] = "selected";
                // Set icon type=iconfile
                $sQuery = "
	    UPDATE
		" . PHP_TM_PDO_TABLENAME_DESC . " 
	    SET 
		cat_type=1";
                break;
        }

        try {
            $oResults = $oPDO->query($sQuery);
        } catch (PDOException $e) {
            fErrorHandler(["filename" => __FILE__, "line" => __LINE__, "error" => $e->getMessage()]);
        }
        
        // Load categories from description table
        $aCategories = fLoadCategories();

        if ($_REQUEST['frm_action']) {


            if ($_REQUEST['frm_action'] == "up") {

                switch ($_REQUEST['frm_move_action']) {
                    case 0:
                        $sSQL_Str = "cat_color='" . $aCategories['cat_color'][$_REQUEST['frm_up']] . "', cat_type=2";
                        break;
                    case 1:
                        $sSQL_Str = "cat_sort='" . $aCategories['cat_sort'][$_REQUEST['frm_up']] . "'";
                        break;
                    case 2:
                        $sSQL_Str = "cat_icon='" . $aCategories['cat_icon'][$_REQUEST['frm_up']] . "', cat_type=1";
                        break;
                }

                $sQuery = "
        UPDATE
    	     " . PHP_TM_PDO_TABLENAME_DESC . " 
    	SET 
	     " . $sSQL_Str . "
    	WHERE 
    	    id=" . $_REQUEST['frm_current'];

                try {
                    $oResults = $oPDO->query($sQuery);
                } catch (PDOException $e) {
                    fErrorHandler(["filename" => __FILE__, "line" => __LINE__, "error" => $e->getMessage()]);
                }

                switch ($_REQUEST['frm_move_action']) {
                    case 0:
                        $sSQL_Str = "cat_color='" . $aCategories['cat_color'][$_REQUEST['frm_current']] . "', cat_type=2";
                        break;
                    case 1:
                        $sSQL_Str = "cat_sort='" . $aCategories['cat_sort'][$_REQUEST['frm_current']] . "'";
                        break;
                    case 2:
                        $sSQL_Str = "cat_icon='" . $aCategories['cat_icon'][$_REQUEST['frm_current']] . "', cat_type=1";
                        break;
                }

                $sQuery = "
        UPDATE
    	     " . PHP_TM_PDO_TABLENAME_DESC . " 
    	SET 
	     " . $sSQL_Str . "
    	WHERE 
    	    id=" . $_REQUEST['frm_up'];

                try {
                    $oResults = $oPDO->query($sQuery);
                } catch (PDOException $e) {
                    fErrorHandler(["filename" => __FILE__, "line" => __LINE__, "error" => $e->getMessage()]);
                }
            }

            if ($_REQUEST['frm_action'] == "down") {

                switch ($_REQUEST['frm_move_action']) {
                    case 0:
                        $sSQL_Str = "cat_color='" . $aCategories['cat_color'][$_REQUEST['frm_down']] . "', cat_type=2";
                        break;
                    case 1:
                        $sSQL_Str = "cat_sort='" . $aCategories['cat_sort'][$_REQUEST['frm_down']] . "'";
                        break;
                    case 2:
                        $sSQL_Str = "cat_icon='" . $aCategories['cat_icon'][$_REQUEST['frm_down']] . "', cat_type=1";
                        break;
                }

                $sQuery = "
        UPDATE
    	     " . PHP_TM_PDO_TABLENAME_DESC . " 
    	SET 
	     " . $sSQL_Str . "
    	WHERE 
    	    id=" . $_REQUEST['frm_current'];

                try {
                    $oResults = $oPDO->query($sQuery);
                } catch (PDOException $e) {
                    fErrorHandler(["filename" => __FILE__, "line" => __LINE__, "error" => $e->getMessage()]);
                }

                switch ($_REQUEST['frm_move_action']) {
                    case 0:
                        $sSQL_Str = "cat_color='" . $aCategories['cat_color'][$_REQUEST['frm_current']] . "', cat_type=2";
                        break;
                    case 1:
                        $sSQL_Str = "cat_sort='" . $aCategories['cat_sort'][$_REQUEST['frm_current']] . "'";
                        break;
                    case 2:
                        $sSQL_Str = "cat_icon='" . $aCategories['cat_icon'][$_REQUEST['frm_current']] . "', cat_type=1";
                        break;
                }

                $sQuery = "
        UPDATE
    	     " . PHP_TM_PDO_TABLENAME_DESC . " 
    	SET 
	     " . $sSQL_Str . "
    	WHERE 
    	    id=" . $_REQUEST['frm_down'];

                try {
                    $oResults = $oPDO->query($sQuery);
                } catch (PDOException $e) {
                    fErrorHandler(["filename" => __FILE__, "line" => __LINE__, "error" => $e->getMessage()]);
                }
            }
        }
        ?>

        <form action="tabs.php" method="post" name="frm_main" id="frm_main">
            <input type="hidden" name="frm_action" id="frm_action" value="">
            <input type="hidden" name="frm_up" id="frm_up" value="">
            <input type="hidden" name="frm_down" id="frm_down" value="">
            <input type="hidden" name="frm_current" id="frm_current" value="">
            <input type="hidden" name="frm_desc_action" id="frm_desc_action" value="<?= $_REQUEST['frm_desc_action'] ?>">
            <input type="hidden" name="frm_move_action" id="frm_move_action" value="<?= $_REQUEST['frm_move_action'] ?>">
        </form>

        <table>
            <tr>
                <td>
                    <form action="tabs.php" method="post" name="frm_main_action" id="frm_main_action">
                        <select name="frm_move_action" id="frm_move_action" onchange="document.getElementById('frm_main_action').submit()">
                            <option value="0" <?= $sSelectedFlag[0] ?>>Move colors</option>
                            <option value="1"<?= $sSelectedFlag[1] ?>>Move categories</option>
                            <option value="2" <?= $sSelectedFlag[2] ?>>Move icons</option>
                        </select>
                    </form>
                </td>
                <td>
                    <form action="tabs.php" method="post" name="frm_sort_action" id="frm_sort_action">
                        Sort:
                        <select name="frm_sort_type" id="frm_sort_type" onchange="document.getElementById('frm_sort_action').submit()">
<?php
$_REQUEST['frm_sort_type'] ? $sSortFlag[1] = "selected" : $sSortFlag[0] = "selected";
?>
                            <option value="0" <?= $sSortFlag[0] ?>>by user preferences</option>
                            <option value="1" <?= $sSortFlag[1] ?>>auto by name</option>
                        </select>
                    </form>
                </td>
            </tr>
        </table>
<?php
$aCategories = fLoadCategories();
?>
        <script>
<?php
$sCount = 0;
foreach ($aCategories['cat_sort'] as $sID => $sNULL) {
    // Make an JS array
    print "aCat[" . $sCount . "]=" . $sID . ";\n";
    $sCount++;
}
?>
        </script>
        <table>
<?php
$sCount = 0;
foreach ($aCategories['cat_sort'] as $sID => $sNULL) {
    $sCatID = $aCategories['cat_id'][$sID];
    $_REQUEST['frm_desc_action'] == "category" ? $sActionDesc = "category" : $sActionDesc = "icon";

    $sEmptyIconFlag = false;
    if ($aCategories['cat_type'][$sID] == 1) {
        !strlen($aCategories['cat_icon'][$sID]) > 0 ? $sEmptyIconFlag = true : "";
        $sShowIcon = "<img src=\"" . PHP_TM_HTTP_ROOT . "/tmp/icon_cache/" . $aCategories['cat_icon'][$sID] . "\" width=\"24\" height=\"24\">";
    } else {
        $sShowIcon = "<div style=\"background-color:" . $aCategories['cat_color'][$sID] . ";width:24px;height:24px;\"></div>";
    }
    // Detect color in icon URL
    if (preg_match("/^#(?:[0-9a-fA-F]{3}){1,2}$/", $aCategories['cat_icon'][$sID])) {
        $sShowIcon = "<div style=\"background-color:" . $aCategories['cat_color'][$sID] . ";width:24px;height:24px;\"></div>";
    }
    // Detect empty icon
    if ($sEmptyIconFlag) {
        $sShowIcon = "<div style=\"background-color:" . $aCategories['cat_color'][$sID] . ";width:24px;height:24px;\"></div>";
    }
    ?>
                <tr>
                    <td>
                        <a href="javascript:void(0)" onclick="fMoveTab('<?= $sActionDesc ?>', 'up', <?= $sCount ?>)" style="text-decoration:none;">&uarr;</q>
                    </td>
                    <td>
                        <a href="javascript:void(0)" onclick="fMoveTab('<?= $sActionDesc ?>', 'down', <?= $sCount ?>)" style="text-decoration:none;">&darr;</a>
                    </td>
                    <td>
                <?= $sShowIcon ?>
                    </td>
                    <td>
                        &nbsp;&nbsp;
                    </td>
                    <td>
    <?= $sHrefStr ?>
    <?= $aCategories['cat_desc'][$sID] ?>
                        </a>
                        </div>
    <?php
    $sCount++;
}
?>
                    </body>
                    </html>
