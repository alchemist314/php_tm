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
include "../functions.php";
include "../db.php";
include "../header.php";

$sQuery = "
        SELECT *
        FROM
            " . PHP_TM_PDO_TABLENAME_DATA . " 
        ORDER BY 
    	    length(tab_icon)";

//print $sQuery;

try {
    $oResults = $oPDO->query($sQuery);
    foreach ($oResults->fetchAll() as $aRow) {
        $aRab['id'][$aRow['id']] = $aRow['id'];

        // Explode domain name
        $aEXP_Domain = explode("://", $aRow['tab_url']);
        $aEXP_Domain_Next = explode("/", $aEXP_Domain[1]);

        if (strlen($aRow['tab_icon']) > 10) {
            if (exif_imagetype($aRow['tab_icon'])) {
                $aTab['icon'][$aRow['id']] = $aRow['tab_icon'];
                $aTab['domain_icon_not_empty_by_domain'][$aEXP_Domain_Next[0]] = $aRow['tab_icon'];
                $aTab['id_by_domain'][$aEXP_Domain_Next[0]] = $aRow['id'];
            } else {
                $aTab['icon'][$aRow['id']] = '';
            }
        }

        $aTab['url'][$aRow['id']] = $aRow['tab_url'];

        if (strlen($aEXP_Domain_Next[0]) > 0) {
            $aTab['domain'][$aRow['id']] = $aEXP_Domain_Next[0];
        }

        $aTab['domain_by_id'][$aRow['id']] = $aEXP_Domain_Next[0];
    }
} catch (PDOException $e) {
    fErrorHandler(["filename" => __FILE__, "line" => __LINE__, "error" => $e->getMessage()]);
}

foreach ($aTab['domain'] as $sKey => $sVal) {
    // Icon not found for this ID
    if (strlen($aTab['icon'][$sKey]) < 10) {
        // Domain has an icon
        if ($aTab['id_by_domain'][$sVal] > 0) {

            // Adding icon from whole array of domains
            $sQuery = "
    			UPDATE 
    			    " . PHP_TM_PDO_TABLENAME_DATA . " 
    			SET 
    			    tab_icon='" . $aTab['icon'][$aTab['id_by_domain'][$sVal]] . "'
    			WHERE 
    			    id =" . $sKey;

            /*
              print "<br>Empty ID: ".$sKey.
              " Domain name: ".$aTab['domain'][$sKey].
              " Not empty ID: ".$aTab['id_by_domain'][$sVal].
              " Domain name: ".$aTab['domain'][$aTab['id_by_domain'][$sVal]].
              "\n";
             */
            //   print $sQuery;

            try {
                $oResults = $oPDO->query($sQuery);
            } catch (PDOException $e) {
                fErrorHandler(["filename" => __FILE__, "line" => __LINE__, "error" => $e->getMessage()]);
            }
        }
    }
}

fMessageHandler(["message" => "Fix icons: ", "additional_message" => "complete!"]);
?>