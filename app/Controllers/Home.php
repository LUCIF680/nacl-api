<?php 
namespace App\Controllers;
use MainModel;
use Strings;

class Home extends BaseController implements Strings{
	public function __construct(){

		
	}
	public function index(){
		return view('welcome_message');
	}
	public function ping(){
		try {
			$header = $this->request->getHeader('Token')->getValue();
			MainModel::matchToken($header);
		} catch (\Exception $e) {
			$this->response->setStatusCode(500);
		}

	}
	public function newRelease(){
		try {
			$header = $this->request->getHeader('Token');
			MainModel::matchToken($header->getValue());
		} catch (\Exception $e) {
			$this->response->setStatusCode(500);
		}
		$db = \Config\Database::connect();
		$rows = $db->query('SELECT games.dev_id,games.price,games.title,developer.comp_name,games.thumbnail FROM games 
		LEFT JOIN developer ON developer.dev_id = games.dev_id ORDER BY id DESC LIMIT 9 ');
		$rows = $rows->getResult();
		echo json_encode($rows);
	}
	public function images($data){
		$data = json_decode($data);
	}
	public function apk($data){
		echo 1;
	}
}
