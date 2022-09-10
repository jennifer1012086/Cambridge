<?php 
class user {
	function is_login() {
		if(!isset($_SESSION['valid_user']) || $_SESSION['session'] != session_id()) {
		    header('location: ../lib/logout.php');
		}
	}
	function is_admin() {
		if(isset($_SESSION['role']) && $_SESSION['role'] == 4) {
			return true;
		} else {
			return false;
		}
	}
	function get_userinfo() {
		$info = array();
		$info[0] = $_SESSION['valid_user'];
		$info[1] = $_SESSION['username'];
		$info[2] = $_SESSION['role'];

		return $info;
	}
}