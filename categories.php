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
        <title>Category</title>
        <link href="css/styles.css" rel="stylesheet" type="text/css">
        <link href="vendor/color-picker/color-picker.min.css" rel="stylesheet">
    </head>
    <body>
    <script src="js/actions.js"></script>


        <?php
        include "config.php";
        include "functions.php";
        include "db.php";
        include "header.php";

        $aColors = [
            "#5f6d5d",
            "#008f00",
            "#16ff00",
            "#ceec6a",
            "#ff9900",
            "#fdd173",
            "#e9ff00",
            "#d2d200",
            "#fbf6bf",
            "#bb8000",
            "#9d795f",
            "#bfafa4",
            "#e5fbe9",
            "#4fc6cd",
            "#29a9ac",
            "#0700ff",
            "#00f8ff",
            "#00129f",
            "#ff00e9",
            "#ff0000",
            "#9f0000",
            "#f2bbbb",
            "#b175b5",
            "#9c02a0",
            "#b175b5",
            "#d3ccff",
            "#fcd8ff"
        ];

        if ($_REQUEST['frm_action'] == "category_delete") {

            $sQuery = "
        DELETE FROM 
    	    " . PHP_TM_PDO_TABLENAME_DESC . " 
        WHERE 
    	    cat_id = " . $_REQUEST['frm_cat_id_to_update'];

            //print $sQuery;

            try {
                $oResults = $oPDO->query($sQuery);
                fMessageHandler(["message"=>"Delete category: " . $_REQUEST['frm_textarea'], "additional_message"=>" complete!"]);
            } catch (PDOException $e) {
                fErrorHandler(["filename" => __FILE__, "line" => __LINE__, "error" => $e->getMessage()]);
            }

            $sQuery = "
        DELETE FROM 
    	    " . PHP_TM_PDO_TABLENAME_DATA . " 
        WHERE 
    	    tab_parent_id = " . $_REQUEST['frm_cat_id_to_update'];

            //print $sQuery;

            try {
                $oResults = $oPDO->query($sQuery);
                fMessageHandler(["message"=>"Delete items: ", "additional_message"=>" complete!"]);
            } catch (PDOException $e) {
                fErrorHandler(["filename" => __FILE__, "line" => __LINE__, "error" => $e->getMessage()]);
            }
        }

        if ($_REQUEST['frm_action'] == "category_update") {

            ($_REQUEST['frm_icon_type'] == 1) ? $sFrmIcon = $_REQUEST['frm_file_select'] : $sFrmIcon = $_REQUEST['frm_icon'];

            $sQuery = "
        UPDATE 
    	    " . PHP_TM_PDO_TABLENAME_DESC . " 
        SET 
    	    cat_desc='" . $_REQUEST['frm_textarea'] . "',
    	    cat_icon='" . $sFrmIcon . "',
    	    cat_color='" . $_REQUEST['frm_icon_color'] . "',
    	    cat_type='" . $_REQUEST['frm_icon_type'] . "'
        WHERE cat_id = " . $_REQUEST['frm_cat_id_to_update'];

            //print $sQuery;

            try {
                $oResults = $oPDO->query($sQuery);
                fMessageHandler(["message"=>"Update category: " . $_REQUEST['frm_textarea'], "additional_message"=>" complete!"]);
            } catch (PDOException $e) {
                fErrorHandler(["filename" => __FILE__, "line" => __LINE__, "error" => $e->getMessage()]);
            }
        }

        if ($_REQUEST['frm_action'] == "create_category") {

            $sQuery = "
        SELECT 
    	    MAX(cat_id) as max_cat_id
        FROM
            " . PHP_TM_PDO_TABLENAME_DESC;

            //print $sQuery;
            try {
                $oResults = $oPDO->query($sQuery);
                foreach ($oResults->fetchAll() as $aRow) {
                    $sNewCategoryID = ($aRow['max_cat_id'] + 1);
                }
            } catch (PDOException $e) {
                fErrorHandler(["filename" => __FILE__, "line" => __LINE__, "error" => $e->getMessage()]);
            }

            $sQuery = "
        INSERT INTO 
    	" . PHP_TM_PDO_TABLENAME_DESC . " 
    	(cat_id,
    	 cat_desc,
    	 cat_icon,
    	 cat_color,
    	 cat_sort) 
        VALUES (
        " . $sNewCategoryID . ",
        '" . $_REQUEST['frm_category_name'] . "',
        '" . $_REQUEST['frm_icon'] . "',
        '" . $_REQUEST['frm_category_color'] . "',
        " . $sNewCategoryID . ")";

            //print $sQuery;
            try {
                $oResults = $oPDO->query($sQuery);
                fMessageHandler(["message"=>"Create category: " . $_REQUEST['frm_category_name'], "additional_message"=>" complete!"]);
            } catch (PDOException $e) {
                fErrorHandler(["filename" => __FILE__, "line" => __LINE__, "error" => $e->getMessage()]);
            }
        }

        $sQuery = "
        SELECT *
        FROM
             " . PHP_TM_PDO_TABLENAME_DESC . " 
	ORDER BY 
	    cat_desc";

        //print $sQuery;
        try {
            $oResults = $oPDO->query($sQuery);
            foreach ($oResults->fetchAll() as $aRow) {
                $aCatID[$aRow['id']] = $aRow['cat_id'];
                $aCatDesc[$aRow['id']] = $aRow['cat_desc'];
            }
        } catch (PDOException $e) {
            fErrorHandler(["filename" => __FILE__, "line" => __LINE__, "error" => $e->getMessage()]);
        }

        if (count($aCatID) > 0) {
            ?>
            <a href="categories.php?action=create">Create new category</a>
            <form method="post" action="categories.php" id="frm_main_category" name="frm_main_category">
                <br>Select a category:&nbsp;
                <select name="frm_category" id="frm_category" onchange="fCategorySelect(this.value);">
                    <option value="-1" selected>---</option>
                    <?php
                    foreach ($aCatDesc as $sID => $sCatDesc) {
                        if ($_REQUEST['frm_category'] == $aCatID[$sID]) {
                            print "<option value=" . $aCatID[$sID] . " selected>" . substr($sCatDesc, 0, 200) . "</option>";
                        } else {
                            print "<option value=" . $aCatID[$sID] . ">" . substr($sCatDesc, 0, 200) . "</option>";
                        }
                    }
                    ?>

                </select>

                <input type="hidden" name="frm_action_select" id="frm_action_select" value="category_edit">
    <?php
}
?>
        </form>
<?php
if ($_REQUEST['action'] == "create") {
    ?>

            <div id="new_category">
                <br>
                <form method="post" action="categories.php" style="padding:5px;" name="frm_create_category">
                    <table style="padding:5px;color:#000;background-color:#fff;" id="frm_create_category">
                        <tr>
                            <td>
                                Category name:
                            </td>
                            <td>
                                <input type="text" name="frm_category_name" value="">
                            </td>
                        <tr>
                            <td>
                                Category color:
                            </td>
                            <td>
                                <input type="text" id="frm_category_color" name="frm_category_color" value="">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input type="submit" value="Create">
                                <input type="button" value="Cancel" onclick="document.getElementById('new_category').style.display = 'none'">
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="frm_action" value="create_category">
                </form>
                select color:
                <table>
    <?php
    $sCount = 0;
    foreach ($aColors as $sHexColor) {
        $sCount == 0 ? print "<tr>" : "";
        print "<td onclick=\"document.getElementById('frm_category_color').value='" . $sHexColor . "';document.getElementById('frm_create_category').style.backgroundColor='" . $sHexColor . "'\"  style='width:24px;height:24px;background-color:" . $sHexColor . "';>&nbsp;&nbsp;</td>\n";
        if ($sCount > 0) {
            (($sCount % 10) == 0) ? print "</tr><tr>" : "";
        }
        $sCount++;
    }
    ?>
                    </tr>
                </table>
            </div>

    <?php
}

if ($_REQUEST['frm_action_select'] == "category_edit") {

    $aFRM_Radio_checked[1] = 0;
    $aFRM_Radio_checked[2] = 0;

    $sQuery = "
        SELECT *
        FROM
            " . PHP_TM_PDO_TABLENAME_DESC . " 
        WHERE 
    	    cat_id=" . $_REQUEST['frm_category'];

    //print $sQuery;
    try {
        $oResults = $oPDO->query($sQuery);
        foreach ($oResults->fetchAll() as $aRow) {
            $aCategory['description'] = $aRow['cat_desc'];
            $aCategory['cat_icon'] = $aRow['cat_icon'];
            $aCategory['cat_color'] = $aRow['cat_color'];
            $aCategory['cat_type'] = $aRow['cat_type'];
            $aRow['cat_type'] == 1 ? $aFRM_Radio_checked[1] = "checked" : $aFRM_Radio_checked[2] = "checked";
        }
    } catch (PDOException $e) {
        fErrorHandler(["filename" => __FILE__, "line" => __LINE__, "error" => $e->getMessage()]);
    }
    ?>
            <form method="post" action="categories.php" id="frm_category_edit" name="frm_category_edit">
                <br>
                <input type="radio" name="frm_icon_type" value="1" <?= $aFRM_Radio_checked[1] ?>>Icon URL (type icon URL or select from existing <input type="file" name="frm_file_select" id="frm_file_select" onchange="document.getElementById('frm_icon').disabled = true">):<br><input type="text" name="frm_icon" id="frm_icon" value="<?= $aCategory['cat_icon'] ?>" size="80%">
                <div>
                    <div style="float:left;width:160px;">
                        <br><input type="radio" name="frm_icon_type" value="2" <?= $aFRM_Radio_checked[2] ?>>Icon Color:
                        <div style="padding:5px;background-color:<?= $aCategory['cat_color'] ?>;" id="frm_icon_bgcolor">
                            <input type="text" name="frm_icon_color" id="frm_icon_color" value="<?= $aCategory['cat_color'] ?>" size="10%">
                        </div>
                    </div>
                    <div style="float:left;">
                        <table>
    <?php
    // Show color palette
    $sCount = 0;
    foreach ($aColors as $sHexColor) {
        $sCount == 0 ? print "<tr>" : "";
        print "<td onclick=\"document.getElementById('frm_icon_color').value='" . $sHexColor . "';document.getElementById('frm_icon_bgcolor').style.backgroundColor='" . $sHexColor . "'\"  style='width:24px;height:24px;background-color:" . $sHexColor . "';>&nbsp;&nbsp;</td>\n";
        if ($sCount > 0) {
            (($sCount % 10) == 0) ? print "</tr><tr>" : "";
        }
        $sCount++;
    }
    ?>
                            </tr>
                        </table>
                    </div>
                </div>
                <textarea name="frm_textarea" id="frm_textarea" rows="10" cols="100"><?= $aCategory['description'] ?></textarea>
                <input type="hidden" name="frm_action" value="" id="frm_action">
                <input type="hidden" name="frm_cat_id_to_update" value="<?= $_REQUEST['frm_category'] ?>">
                <br><br>
                <input type="button" value="Delete" onclick="if (confirm('Удалить <?= substr($aCategory['description'], 0, 150) ?> ?')) {
                    document.getElementById('frm_action').value = 'category_delete'; document.getElementById('frm_category_edit').submit();}">
                <input type="button" value="Update" onclick="document.getElementById('frm_action').value = 'category_update';
                document.getElementById('frm_category_edit').submit();">
                <input type="button" value="Cancel" onclick="document.getElementById('frm_category_edit').style.display = 'none';
                document.getElementById('frm_category').value = -1;">
            </form>
                <?php
            }
            ?>

        <script src="vendor/color-picker/color-picker.min.js"></script>
        <script>
            var oPicker;
            var sFirstLoad = false;
            if (document.getElementById('frm_icon_bgcolor')) {
                oPicker = new CP(document.getElementById('frm_icon_color'));
                document.getElementById('frm_icon_bgcolor').style.backgroundColor = "#fff";
            }
            if (document.getElementById('frm_category_color')) {
                sFirstLoad = true;
                oPicker = new CP(document.getElementById('frm_category_color'));
            }
            oPicker.on('change', function (r, g, b) {
                this.source.value = this.color(r, g, b);
                if (document.getElementById('frm_icon_bgcolor')) {
                    document.getElementById('frm_icon_color').value = this.source.value;
                    document.getElementById('frm_icon_bgcolor').style.backgroundColor = this.source.value;
                }
                if (document.getElementById('frm_category_color')) {
                    document.getElementById('frm_category_color').value = this.source.value;
                    document.getElementById('frm_create_category').style.backgroundColor = this.source.value;
                    if (sFirstLoad == true) {
                        document.getElementById('frm_create_category').style.backgroundColor = "#ffffff";
                        sFirstLoad = false;
                    }
                }
            });

        </script>


    </body>
</html>


