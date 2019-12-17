<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once "application/libraries/SQNile.php";
class User extends CI_Controller {

	public function request_csrf(){
		session_start();
		$this->load->library("SQNile");
		$_SESSION["token"] = $this->security->get_csrf_hash();
		$_SESSION["session_id"] = session_id();
		echo json_encode($_SESSION);
	}
	public function test(){
	}
	public function login(){
		try {
			session_id($this->input->get("session_id"));
			session_start();
			$this->load->library("nile");
			$email = $this->input->get("email");
			$password = $this->input->get("password");
			$token = $this->input->get("session_id");
			if ($token === $_SESSION['token']) {
				$email = $this->nile->trim_input($email);
				$database = new SQNile();
				$user = $database->fetch("SELECT * from users WHERE email = ?", [$email]);
				if (!is_empty($user))
					if (password_verify($password, $user["password"])) {
						unset($user["password"]);
						$_SESSION = array_merge($_SESSION,$user);
						echo '{"auth":true}';
					}
					else
						echo '{"auth":false,"error":"Password and email didn\'t match."}';
				else
					echo '{"auth":false,"error":"Looks like you are new here! Signup now."}';
			}else
				echo '{"auth":false,"error":"Oops! Our system has detected some unusual activity."}';
		}catch (PDOException $e){
			echo '{"auth":false,"error":"Unknown server error."}';
		}catch (Exception $e) {
			echo '{"auth":false,"error":"Unknown server error."}';
		}
	}

	public function signup(){
		try{
			session_id($this->input->get("session_id"));
			session_start();
			$this->load->library("nile");
			$database = new SQNile();
			$email = $this->input->get("email");
			$token = $this->input->get("token");
			$username = $this->input->get("username");
			$password = $this->input->get("password");
			if ($token === $_SESSION['token']) {
				$email = $this->nile->trim_input($email);
				$username = $this->nile->trim_input($username);
				$password = password_hash($password, PASSWORD_DEFAULT);
				if ($database->is_unique($email,"users","email")
					&& $database->is_unique($username,"users","username")) {
					$database->query("INSERT INTO users (password,email,username) VALUES (?,?,?)"
						, [$password, $email, $username]);
					echo '{"auth":true}';
				}else
					echo '{"auth":false,"error_code":1}';
			}else
				echo '{"auth":false,"error_code":1}';
		}catch(PDOException $e){
			echo json_encode('{"status":false}');
		}

	}
}
