<?php
function is_empty($var){
	return empty($var);
}
require_once "application/libraries/SQNile.php";
class Nile{

	function trim_input($data){
		$data = urlencode($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	 static function create_access_token(){
		$CI = & get_instance();
		$CI->load->library("encryption");
		$database = new SQNile();
		$access_token = null;
		do{
			$access_token = $CI->encryption->encrypt();
		}while(!$database->is_unique($access_token,
			"access_token","access_token"));
		return $access_token;
	}
}
