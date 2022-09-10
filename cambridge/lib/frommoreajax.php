<?php
include('../lib/callfunction.php');
if(isset($_POST['action']) && !empty($_POST['action']) && isset($_POST['value1']) && !empty($_POST['value1']) && isset($_POST['value2']) && !empty($_POST['value2'])) {
    $action = $_POST['action'];
    $value1 = $_POST['value1'];
    $value2 = $_POST['value2'];
    switch($action) {
        case 'ajaxGetAccLimit':
        	return ajaxGetAccLimit($value1, $value2);
        case 'ajaxGetItemLimit':
        	return ajaxGetItemLimit($value1, $value2);
        case 'ajaxModifySLimit':
        	return ajaxModifySLimit($value1, $value2);
    }
}

?>