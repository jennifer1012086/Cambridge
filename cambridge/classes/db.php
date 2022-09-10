<?php
class db{
	function connect_db() {
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		$connect = mysqli_connect("localhost","root","", "se-project");
		if (mysqli_connect_error()) {
                print("Connect failed: ".mysqli_connect_error());
                exit();
        } else {
                mysqli_query($connect, "SET NAMES UTF8");
        }
		return $connect;
	}
	function get_record($sql) {
		try {
			$mysqli = self::connect_db();
			$result = $mysqli->query($sql);
			if($result && ($result->num_rows > 0)){
			    $result = $result->fetch_all(MYSQLI_ASSOC);
			}
			self::close_db($mysqli);
			return $result;
		} catch (Exception $e) {
		    echo 'Caught exception: '.$e->getMessage()."\n";
		    exit();
		}
	}
	function count_record($sql) {
		try {
			$mysqli = self::connect_db();
			$result = $mysqli->query($sql);
			if($result){
			    $result = $result->num_rows;
			}
			self::close_db($mysqli);
			return $result;
		} catch (Exception $e) {
		    echo 'Caught exception: '.$e->getMessage()."\n";
		    exit();
		}
	}
	function insert($sql) {
		try {
			$mysqli = self::connect_db();
			$mysqli->query($sql);
			$id = $mysqli->insert_id;
			self::close_db($mysqli);
			return $id;
		} catch (Exception $e) {
		    echo 'Caught exception: '.$e->getMessage()."\n";
		    exit();
		}
	}
	function update($sql) {
		try {
			$mysqli = self::connect_db();
			$r = $mysqli->query($sql);
			self::close_db($mysqli);
			return $r;
		} catch (Exception $e) {
		    echo 'Caught exception: '.$e->getMessage()."\n";
		    exit();
		}
	}
	function close_db($connect) {
		mysqli_close($connect);
	}
}
?>