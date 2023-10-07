/*
 
 MIT License
 Copyright (c) 2023 Golovanov Grigoriy
 Contact e-mail: magentrum@gmail.com
 
 */


var aCounter = [];
var aHTML = [];

var aCat = [];
var sUp = 0;
var sDown = 0;
var sUpL = 0;
var sDownL = 0;


function fChangeFrmSelect(val) {
    fPreparedHTML();
    switch (val) {
        // ---
        case "0":
            document.getElementById('frm_td_select').innerHTML = "";
            break;
            // Move
        case "1":
            document.getElementById('frm_td_select').innerHTML = (aHTML[0] + aHTML[1] + aHTML[2] + aHTML[3]);
            break;
            // Delete
        case "2":
            document.getElementById('frm_td_select').innerHTML = (aHTML[0] + aHTML[2] + aHTML[3]);
            break;
            // Export
        case "3":
            document.getElementById('frm_td_select').innerHTML = (aHTML[2] + aHTML[3]);
            break;
            // Sort
        case "4":
            document.getElementById('frm_td_select').innerHTML = (aHTML[2] + aHTML[4]);
            break;

    }
}

function fSubmitMainForm() {
    switch (document.getElementById('frm_category_action').value) {
        // ---
        case "0":
            alert('Please select any action!');
            break;
            // Move
        case "1":
            document.getElementById('frm_action').value = 'move';
            document.getElementById('frm_main_form').submit();
            break;
            // Delete
        case "2":
            if (confirm('You want to delete selected items?')) {
                document.getElementById('frm_action').value = 'delete';
                document.getElementById('frm_main_form').submit();
            }
            break;
            // Export
        case "3":
            document.getElementById('frm_action').value = 'export';
            document.getElementById('frm_main_form').submit();
            break;
            // Sort
        case "4":
            document.getElementById('frm_action').value = 'sort';
            document.getElementById('frm_main_form').submit();
            break;

    }
}

function fMoveTab(sDesc, sAction, sID) {
    var sAllowFlag = true;
    for (l = 0; l < aCat.length; l++) {
        if (l == parseInt(sID)) {
            if (sAction == "up") {
                if (l > 0) {
                    sUp = aCat[l - 1];
                    sUpL = l;
                } else {
                    sAllowFlag = false;
                }
            }
            if (sAction == "down") {
                if (l < (aCat.length - 1)) {
                    sDown = aCat[l + 1];
                    sDownL = l;
                } else {
                    sAllowFlag = false;
                }
            }
        }
    }
    document.getElementById('frm_desc_action').value = sDesc;
    document.getElementById('frm_action').value = sAction;
    document.getElementById('frm_up').value = sUp;
    document.getElementById('frm_down').value = sDown;
    document.getElementById('frm_current').value = aCat[sID];
    if (sAllowFlag == true) {
        document.getElementById('frm_main').submit();
    }
}

function fCounter(val) {

    if ((aCounter[val] > 0) || (aCounter[val] === undefined)) {
        aCounter[val] = 0;
        document.getElementById('z_' + val).style.display = 'block';
    } else {
        aCounter[val] = 1;
        document.getElementById('z_' + val).style.display = 'none';
    }
}

function fCategorySelect(val) {
    if (document.getElementById('frm_textarea')) {
        document.getElementById('frm_textarea').value = '';
    }
    document.getElementById('frm_action_select').value = 'category_edit';
    if (val >= 0) {
        document.getElementById('frm_main_category').submit();
    } else {
        if (document.getElementById('frm_category_edit')) {
            document.getElementById('frm_category_edit').style.display = 'none';
        }
    }
}
