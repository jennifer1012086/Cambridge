<?php
include('../lib/callfunction.php');
if(isset($_POST['action']) && !empty($_POST['action']) && isset($_POST['value']) && !empty($_POST['value'])) {
    $action = $_POST['action'];
    $value = $_POST['value'];
    switch($action) {
        case 'ajaxGetSub':
        	return ajaxGetSub($value);
        case 'ajaxGetItem':
        	return ajaxGetItem($value);
        case 'ajaxGetItemInfo':
        	return ajaxGetItemInfo($value);
        case 'ajaxGetIventory':
            return ajaxGetIventory($value);
        case 'ajaxPass':
            return ajaxPass($value);
        case 'ajaxCancel':
            return ajaxCancel($value);
        case 'ajaxGet':
            return ajaxGet($value);
        case 'ajaxReturn':
            return ajaxReturn($value);
        case 'ajaxChangeLang':
            return ajaxChangeLang($value);
        case 'ajaxGetApplyItemByTeacher':
            return ajaxGetApplyItemByTeacher($value);
        case 'ajaxGetApplyItemByClass':
            return ajaxGetApplyItemByClass($value);
        case 'ajaxGetApplyItemByActivity':
            return ajaxGetApplyItemByActivity($value);
    }
}

?>