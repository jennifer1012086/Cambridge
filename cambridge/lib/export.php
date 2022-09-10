<?php
try {
	session_start();
	include_once('../classes/user.php');
    $user = new user();
	if(!$user::is_admin()) {
        header('location: ../page/');
    }
	$lang = $_SESSION['lang'];
	require '../component/multilang.php';
	include('./callfunction.php');
	$sem = (int)getSemesterID();
	include_once('../classes/db.php');
	$DB = new db();
	$apply = 1;
	$review = 1;
	$purchase = 3;
	$arr = array();
	$arr[] = array($pos[$lang], $mc[$lang], $sc[$lang], $iname[$lang], $size[$lang], $color[$lang], $unit[$lang], $newp[$lang]);
	$filepath = "export".date('Y-m-d').".csv";
	$sql = "SELECT o.oid, p.name location, c1.name main, c2.name sub, o.item, o.size, o.color, o.unit FROM Place p, objectAndPlace op,  Category c1, Category c2, Object o, objectAndCat oc WHERE c2.catid = oc.catid AND o.oid = oc.oid AND c1.catid = c2.parent AND op.oid = o.oid AND op.pid = p.pid";
	$result = $DB::get_record($sql);
	foreach ($result as $r) {
		$m = simplexml_load_string($r['main']);
		$s = simplexml_load_string($r['sub']);
		$i = simplexml_load_string($r['item']);
		$c = '';
		if ($r['color']) {
			$c = simplexml_load_string($r['color']);
			$c = $c->attributes()->{$lang};
		}
		$arr[$r['oid']] = array($r['location'], $m->attributes()->{$lang}, $s->attributes()->{$lang}, $i->attributes()->{$lang}, $r['size'], $c, $r['unit']);
	}
	$sql = "SELECT oid, SUM(num) FROM inAndOut WHERE aid = $purchase AND sid = $sem GROUP BY oid";
	$result2 = $DB::get_record($sql);
	$arr2 = array();
	foreach ($result2 as $r2) {
		$arr2[$r2['oid']] = $r2['SUM(num)'];
	}
	for ($i=1; $i < count($arr); $i++) {
		$p = 0;
		if(isset($arr2[$i])) {
			$p = (int)$arr2[$i];
		}
		array_push($arr[$i], $p);
	}
	$tmp1 = $arr;
	$sql = "SELECT * FROM Activity";
	$result = $DB::get_record($sql);
	unset($arr2);
	foreach ($result as $r) {
		$e = simplexml_load_string($r['name']);
		$arr2[$r['eid']] = $e->attributes()->{$lang};
	}
	for ($i=1; $i <= count($arr2); $i++) {
		if(isset($arr2[$i])) {
			array_push($tmp1[0], $arr2[$i][0]);
		}
	}
	$sql = "SELECT oid, eid, num FROM inAndOut WHERE aid = $apply AND review = $review AND sid = $sem";
	$result2 = $DB::get_record($sql);
	unset($arr);
	foreach ($result2 as $r2) {
		if (!isset($arr[$r2['oid']])) {
			$arr[$r2['oid']] = array($r2['eid']=>$r2['num']);
		} else if (isset($arr[$r2['oid']][$r2['eid']])) {
			$tmp = (int)$arr[$r2['oid']][$r2['eid']];
			$arr[$r2['oid']][$r2['eid']] = (string)($tmp+(int)$r2['num']);
		} else {
			$arr[$r2['oid']] = $arr[$r2['oid']]+array($r2['eid']=>$r2['num']);
		}
	}
	for ($i=1; $i < count($tmp1); $i++) {
		for($j=1; $j <= count($arr2); $j++) {
			$tmp = '';
			if(isset($arr[$i][$j])) {
				$tmp = $arr[$i][$j];
			}
			array_push($tmp1[$i], $tmp);
		}
	}
	array_push($tmp1[0], $con[$lang]);
	$sql = "SELECT oid, SUM(num) FROM inAndOut WHERE aid = $apply AND review = $review AND sid = $sem GROUP BY oid";
	unset($arr2);
	$result = $DB::get_record($sql);
	$arr2 = array();
	foreach ($result as $r) {
		$arr2[$r['oid']] = $r['SUM(num)'];
	}
	for ($i=1; $i < count($tmp1); $i++) {
		$a = 0;
		if(isset($arr2[$i])) {
			$a = (int)$arr2[$i];
		}
		array_push($tmp1[$i], $a);
	}
	array_push($tmp1[0], $re[$lang]);
	$sql = "SELECT oid, num FROM Inventory";
	unset($arr);
	$result2 = $DB::get_record($sql);
	$arr = array();
	foreach ($result2 as $r2) {
		$arr[$r2['oid']] = $r2['num'];
	}
	for ($i=1; $i < count($tmp1); $i++) {
		$in = 0;
		if(isset($arr[$i])) {
			$in = (int)$arr[$i];
		}
		array_push($tmp1[$i], $in);
	}
	@$fp = fopen($filepath, 'w');
	foreach ($tmp1 as $fields) {
		fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
		fputcsv($fp, $fields);
	}
	fclose($fp);
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Component: must-revalidate, post-check=0, pre-check=0");
	header("Content-type: text/csv");
	header("Content-Length: ".filesize($filepath));
	header("Content-Disposition: attachment; filename=$filepath");
	header("Content-Transfer-Encoding: binary");
	flush(); 
	ob_clean();
	readfile($filepath);
	unlink($filepath);
	exit();
} catch (Exception $e) {
        echo 'Caught exception: '.$e->getMessage()."\n";
}
?>