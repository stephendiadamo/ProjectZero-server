<?php 
include "application/libraries/phpqrcode/qrlib.php";

class QRCodeGen extends CI_Controller {

	public function generate(){		
		QRcode::png($_GET["code"], false, 'L', 4, 2);
	}
	
	public function rawGenerate($code){		
		$temp = QRcode::text($code, false, 'L', 4, 2);
		return json_encode($temp);
	}
		
	// DATABASE STUFF: 		
	public function fetchUsers(){
		$this->load->model("users");
		$data = $this->users->fetchUsers();
		if ($data->result_array() != null){
			echo json_encode($data->result_array());
		}
	}
	
	// Add a new user	
	public function addUser(){		
		$this->load->model("users");
		if (isset($_GET["user"]) && isset($_GET["account_type_id"]) && isset($_GET["password"])){
			try{	
				$this->users->addNewUser($_GET["user"], $_GET["account_type_id"], $_GET["password"]);
				echo "SUCCESS";
			} catch (Exception $e) {
				echo "FAIL";
			}
		} else {
			echo "FAIL";
		}
	}
	
	// Get info by user_id
	public function getUser(){	
		$this->load->model("users");
		if (isset($_GET["user_id"])){
			$results = $this->users->getPrescData($_GET["user_id"]);
			if ($results->result_array() != null){		
				echo json_encode($results->result_array());
			}
		} else {
			echo "FAIL";
		}
	}
	
	public function retrieveQRCode(){
		$this->load->model("users");
		if (isset($_GET["user_id"]) && isset($_GET["drug"])){
			$results = $this->users->getQRCode($_GET["user_id"], $_GET["drug"]);
			if ($results->result_array() != null){		
				echo json_encode($results->result_array());
			}
		} else {
			echo "FAIL";
		}
	}
	
	public function addPresc(){
		if (isset($_GET["user_id"]) && isset($_GET["doctor_id"]) && isset($_GET["drug"])){
			$user_id = $_GET["user_id"];
			$doctor_id = $_GET["doctor_id"];
			$drug = $_GET["drug"];
			$note = "";
			if (isset($_GET["note"])){
				$note = $_GET["note"];
			}
			$qrcode = $this->rawGenerate($doctor_id . ";" . $user_id . ";" . $drug . ";" . $note . ";" . date("d.m.Y"));
			$this->load->model("users");
			try{
				$this->users->addNewDrug($user_id, $doctor_id, $drug, $qrcode, $note, date("Ymd"));				
				echo "SUCCESS";
			} catch (Exception $e){
				echo "FAIL";
			}				
		} else {
			echo "FAIL";
		}
	}
	
	public function removeUser(){
		if (isset($_GET["user_id"])){
			$this->load->model("users");
			$this->users->removeUser($_GET["user_id"]);
			echo "SUCCESS";
		} else {
			echo "FAIL";
		}
	}
	
	public function login(){
		if (isset($_GET["user"]) && isset($_GET["password"])){
			$this->load->model("users");
			$res = $this->users->login($_GET["user"], $_GET["password"]);
			if ($res->result_array() != null){
				echo json_encode($res->result_array());
			} else {
				echo "FAIL";
			}
		} else {
			echo "FAIL";
		} 
	}
}
?>