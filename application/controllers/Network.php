<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Network extends CI_Controller{
	function ping(){
		echo '{"ping:true"}';
	}
}
