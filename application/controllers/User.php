<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once "application/libraries/SQNile.php";
require_once "application/models/Mail.php";
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
			$token = $this->input->get("token");
			if ($token == $_SESSION['token']) {
				$email = $this->nile->trim_input($email);
				$database = new SQNile();
				$user = $database->fetch("SELECT * from users WHERE email = ?", [$email]);
				if (!is_empty($user))
					if (password_verify($password, $user["password"])) {
						unset($user["password"]);
						$this->load->library('encryption');
						$token =  bin2hex($this->encryption->create_key(256));
						$database->query("UPDATE users set token = ? where email = ?",[$token,$email]);
						echo '{"auth":true,"token":'.$token.'}';
					}
					else
						echo '{"auth":false,"error":"Password and email didn\'t match."}';
				else
					echo '{"auth":false,"error":"Looks like you are new here!\n Signup now."}';
			}else
				echo '{"auth":false,"error":"Oops! Our system has detected \nsome unusual activity."}';
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
				if ($database->is_unique($email,"users","email") && $database->is_unique($username,"users","username")) {
					$this->load->library('encryption');
					$token = bin2hex($this->encryption->create_key(256));
					$database->query("INSERT INTO users (password,email,username,token) VALUES (?,?,?,?)"
						, [$password, $email, $username,$token]);
					echo '{"auth":true,"token":'.$token.'}';
					/*$this->load->library("encryption");
					try{
						$real_otp = Mail::sendOTP($email);
					}catch(Exception $ex){
						
					}
					$array = ["real_otp" => $real_otp,
								"session_id" => session_id()];
					$hash = $this->encryption->encrypt(
						json_encode($array)
					);

					/*"status":true,
						"real_otp":'.Mail::sendOTP().',
						*/
				}else
					echo '{"auth":false,"error":"Looks like you already have a account! \nLogin now."}';
			}else
				echo '{"auth":false,"error":"Token error please restart app"}';
		}catch(PDOException $e){
			echo '{"auth":false,"error":"Unexpected error occurred."}';
		}
	}

	public function verify_email(){
		$database = new SQNile();
		$email = $this->input->get("email");
		$otp = $this->input->get("otp");

		$access_token = $this->nile->create_access_token();
		try {
			$database->query("INSERT INTO users (access_token,email) VALUES (?,?)", [$email, $access_token]);
			echo '{"auth":true,"access_token":'.$access_token.'}';
		} catch (Exception $e) {
			echo '{"auth":false,"error":"Unknown error occurred."}';
		}

	}

	public function check_token(){
		try{
			$token = $this->input->get("token");
			$database = new SQNile();
			$this->load->library("nile");
			$token_dbs = $database->fetch("SELECT token from users WHERE token = ?", [$token]);		
			if (is_empty($token_dbs))
				echo '{"auth":false}';	
			else
				echo '{"auth":true,"token":'.$token.'}'; 
		}catch (Exception $e) {
				echo '{"auth":false}';
		}	
	}
}
