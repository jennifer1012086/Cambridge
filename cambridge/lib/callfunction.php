<?php
function getMain() {
	include_once('../classes/db.php');
	$DB = new db();
	$sql = 'SELECT * FROM Category WHERE parent = 0';
	$result = $DB::get_record($sql);
	return $result;
}
function getSub($main) {
	include_once('../classes/db.php');
	$DB = new db();
	$sql = "SELECT * FROM Category WHERE parent = $main";
	$result = $DB::get_record($sql);
	return $result;
}
function getItem($sub) {
	include_once('../classes/db.php');
	$DB = new db();
	$sql = "SELECT DISTINCT o.item FROM Object o, objectAndCat oc WHERE o.oid = oc.oid AND oc.catid = $sub";
	$result = $DB::get_record($sql);
	return $result;
}
function isAccRept($account) {
	include_once('../classes/db.php');
	$DB = new db();
	$sql = "SELECT * FROM Userinfo WHERE acnt = '$account'";
	$result = $DB::count_record($sql);
	if ($result == 1) {
		return true;
	} else {
		return false;
	}
}
function getSemester() {
	include_once('../classes/db.php');
	$DB = new db();
	$sql = "SELECT name FROM Semester WHERE sid = (SELECT MAX(sid) FROM Semester)";
	$result = $DB::get_record($sql);
	return $result;
}
function getSemesterID() {
	include_once('../classes/db.php');
	$DB = new db();
	$sql = "SELECT sid FROM Semester WHERE sid = (SELECT MAX(sid) FROM Semester)";
	$result = $DB::get_record($sql);
	return $result[0]['sid'];
}
function getLimit($oid, $uid) {
	include_once('../classes/db.php');
	$DB = new db();
	$sql = "SELECT num FROM SLimit WHERE oid = $oid AND uid = $uid";
	$result = $DB::count_record($sql);
	if ($result == 1) {
		$result = $DB::get_record($sql);
		$result = $result[0]['num'];
	} else if($result == 0) {
		$sql = "SELECT num FROM OLimit WHERE oid = $oid";
		$result = $DB::get_record($sql);
		$result = $result[0]['num'];
	}
	return $result;
}
function getUserApply($oid, $uid) {
	include_once('../classes/db.php');
	$DB = new db();
	$action = 1;	
	$review = -1;	
	$semester = getSemesterID();
	$sql = "SELECT SUM(num) FROM inAndOut WHERE sid = $semester AND aid = $action AND review != $review AND uid = $uid AND oid = $oid";
	$result = $DB::get_record($sql);
	return $result[0]['SUM(num)'];
}
function returnInventory($ioid) {
	include_once('../classes/db.php');
	$DB = new db();
	$sql = "SELECT oid, num FROM inAndOut WHERE ioid = $ioid";
	$result = $DB::get_record($sql);
	$id = $result[0]['oid'];
	$num = (float)$result[0]['num'];
	$sql = "UPDATE Inventory SET num = (num+$num) WHERE oid = $id";
    $none = $DB::update($sql);
}
function ajaxPass($ioid) {
	include_once('../classes/db.php');
	$DB = new db();
	$review = 1;		
	$sql = "UPDATE inAndOut SET review = $review WHERE ioid = $ioid";
	$none = $DB::update($sql);
}
function ajaxCancel($ioid) {
	include_once('../classes/db.php');
	$DB = new db();
	$review = -1;		
	$sql = "UPDATE inAndOut SET review = $review WHERE ioid = $ioid";
	$none = $DB::update($sql);
	returnInventory($ioid);
}
function ajaxGet($ioid) {
	include_once('../classes/db.php');
	$DB = new db();
	$get = 1;			
	$sql = "UPDATE inAndOut SET get = $get WHERE ioid = $ioid";
	$none = $DB::update($sql);
}
function ajaxReturn($ioid) {
	include_once('../classes/db.php');
	$DB = new db();
	$ret = 1;			
	$sql = "UPDATE inAndOut SET ret = $ret WHERE ioid = $ioid";
	$none = $DB::update($sql);
	returnInventory($ioid);
}
function ajaxGetSub($main) {
	$r = getSub($main);
	echo json_encode($r);
}
function ajaxGetItem($sub) {
	include_once('../classes/db.php');
	$DB = new db();
	$sql = "SELECT o.oid, o.item, o.size, o.color FROM Object o, objectAndCat oc WHERE o.oid = oc.oid AND oc.catid = $sub";
	$result = $DB::get_record($sql);
	echo json_encode($result);
}
function ajaxGetItemInfo($oid) {
	include_once('../classes/db.php');
	$DB = new db();
	$sql = "SELECT o.com, o.size, o.color, o.unit, i.num remain, ol.num olimit FROM Object o, objectAndCat oc, OLimit ol, Inventory i WHERE o.oid = oc.oid AND o.oid = ol.oid AND o.oid = i.oid AND oc.oid = $oid";
	$result = $DB::get_record($sql);
	echo json_encode($result);
}
function ajaxGetIventory($oid) {
	include_once('../classes/db.php');
	$DB = new db();
	$sql = "SELECT num FROM Inventory WHERE oid = $oid";
	$result = $DB::get_record($sql);
	echo json_encode($result);
}
function ajaxGetAccLimit($oid, $uid) {
	$alimit = 0;
	$limit = (int)getLimit($oid, $uid);
	$applied = (float)getUserApply($oid, $uid);
	if(!is_null($applied)) {
		$alimit = $limit-$applied;
	} else {
		$alimit = $limit;
	}
	echo (float)$alimit;
}
function ajaxGetItemLimit($sub, $uid) {
	include_once('../classes/db.php');
	$DB = new db();
	$sql = "SELECT o.oid, o.item, o.size, o.color, ol.num FROM Object o, objectAndCat oc, OLimit ol WHERE o.oid = oc.oid AND o.oid = ol.oid AND oc.catid = $sub";
	$result = $DB::get_record($sql);
	$sql = "SELECT sl.oid, sl.num FROM Object o, objectAndCat oc, SLimit sl WHERE o.oid = oc.oid AND o.oid = sl.oid AND oc.catid = $sub AND sl.uid = $uid";
	$result2 = $DB::get_record($sql);
	for ($i=0; $i < count($result); $i++) {
        $oid = $result[$i]['oid'];
        foreach ($result2 as $r2) {
            if (isset($r2['oid']) && $r2['oid'] == $oid)
                $result[$i]['num'] = $r2['num'];
        }
    }
	echo json_encode($result);
}
function ajaxGetApplyItemByTeacher($uid) {
	$apply = 1;       
    $review = 1;      
    $sem = (int)getSemesterID();
	include_once('../classes/db.php');
	$DB = new db();
	$sql = "SELECT o.oid, c1.name parent, c2.name name, o.item, SUM(io.num) total FROM Object o, objectAndCat oc, Category c1, Category c2, inAndOut io WHERE c1.catid = c2.parent AND o.oid = io.oid AND c2.catid = oc.catid AND o.oid = oc.oid AND io.aid = $apply AND io.review = $review AND io.uid = $uid AND io.sid = $sem GROUP BY io.oid";
	$result = $DB::count_record($sql);
	$r = array();
	if($result > 0) {
		$result2 = $DB::get_record($sql);
		foreach ($result2 as $r2) {
			$limit = getLimit($r2['oid'], $uid);
			$r[] = $r2+array('limit'=>$limit);
		}
	}
	echo json_encode($r);
}
function ajaxGetApplyItemByClass($cid) {
	$apply = 1;        
    $review = 1;       
    $sem = (int)getSemesterID();
	include_once('../classes/db.php');
	$DB = new db();
	$sql = "SELECT c1.name parent, c2.name name, o.item, SUM(io.num) total FROM Object o, objectAndCat oc, Category c1, Category c2, inAndOut io, userAndClass uc WHERE c1.catid = c2.parent AND o.oid = io.oid AND c2.catid = oc.catid AND o.oid = oc.oid AND io.aid = $apply AND io.review = $review AND io.uid = uc.uid AND uc.cid = $cid AND io.sid = $sem GROUP BY io.oid";
	$result = $DB::count_record($sql);
	$result2 = '';
	if($result > 0) {
		$result2 = $DB::get_record($sql);
	}
	echo json_encode($result2);
}
function ajaxGetApplyItemByActivity($eid) {
	$apply = 1;         
    $review = 1;       
    $sem = (int)getSemesterID();
	include_once('../classes/db.php');
	$DB = new db();
	$sql = "SELECT c1.name parent, c2.name name, o.item, SUM(io.num) total FROM Object o, objectAndCat oc, Category c1, Category c2, inAndOut io WHERE c1.catid = c2.parent AND o.oid = io.oid AND c2.catid = oc.catid AND o.oid = oc.oid AND io.aid = $apply AND io.review = $review AND io.eid = $eid AND io.sid = $sem GROUP BY io.oid";
	$result = $DB::count_record($sql);
	$result2 = '';
	if($result > 0) {
		$result2 = $DB::get_record($sql);
	}
	echo json_encode($result2);
}
function ajaxModifySLimit($info, $quantity) {
	include_once('../classes/db.php');
	$DB = new db();
	$sql = "SELECT * FROM SLimit WHERE oid = ".$info[0]." AND uid = ".$info[1];
	$result = $DB::count_record($sql);
	if ($result == 1) {
		$sql = "UPDATE SLimit SET num = $quantity WHERE oid = ".$info[0]." AND uid = ".$info[1];
		$none = $DB::update($sql);
	} else if($result == 0) {
		$sql = "INSERT INTO SLimit(uid,oid,num) VALUES (".$info[1].",".$info[0].",$quantity)";
		$none = $DB::insert($sql);
	}
}
function ajaxChangeLang($none) {
	session_start();
	if ($_SESSION['lang'] == 'zh-tw') {
		$_SESSION['lang'] = 'en';
	} else {
		$_SESSION['lang'] = 'zh-tw';
	}
	echo $_SESSION['lang'];
}
?>