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
		if (isset($_GET["user"]) && isset($_GET["account_type_id"])){
			$this->users->addNewUser($_GET["user"], $_GET["account_type_id"]);
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
		}
	}
	
	public function retrieveQRCode(){
		$this->load->model("users");
		if (isset($_GET["user_id"]) && isset($_GET["drug"])){
			$results = $this->users->getQRCode($_GET["user_id"], $_GET["drug"]);
			if ($results->result_array() != null){		
				echo json_encode($results->result_array());
			}
		}
	}
	
	public function addPresc(){
		if (isset($_GET["user_id"]) && isset($_GET["doctor_id"]) && isset($_GET["drug"])){
			$user_id = $_GET["user_id"];
			$doctor_id = $_GET["doctor_id"];
			$drug = $_GET["drug"];
			$qrcode = $this->rawGenerate($doctor_id . ";" . $user_id . ";" . $drug);
			$this->load->model("users");
			$this->users->addNewDrug($user_id, $doctor_id, $drug, $qrcode);	
		}
	}
	
	public function removeUser(){
		if (isset($_GET["user_id"])){
			$this->load->model("users");
			$this->users->removeUser($_GET["user_id"]);
		}
	}	
}
?>