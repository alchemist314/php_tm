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

if (PHP_TM_SECURITY) {

    // view.php
    $_REQUEST['frm_category'] = fSanitize($_REQUEST['frm_category'], 400);
    $_REQUEST['frm_delete_value_id'] = fSanitize($_REQUEST['frm_delete_value_id'], 1000);
    $_REQUEST['frm_update_icon_id'] = fSanitize($_REQUEST['frm_update_icon_id'], 1000);

    // category.php
    $_REQUEST['frm_textarea']=fSanitize($_REQUEST['frm_textarea'],2000);
    $_REQUEST['frm_icon_color']=fSanitize($_REQUEST['frm_icon_color'],10, 'color');
    $_REQUEST['frm_icon_type']=fSanitize($_REQUEST['frm_icon_type'], 2);
    $_REQUEST['frm_cat_id_to_update']=fSanitize($_REQUEST['frm_cat_id_to_update'],1000);
    $_REQUEST['frm_category_name']=fSanitize($_REQUEST['frm_category_name'], 1000);
    $_REQUEST['frm_category_color']=fSanitize($_REQUEST['frm_category_color'], 1000);
}