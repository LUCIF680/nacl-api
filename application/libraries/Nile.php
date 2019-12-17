<?php
function is_empty($var){
	return empty($var);
}

class Nile{

	function trim_input($data){
		$data = urlencode($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
}
