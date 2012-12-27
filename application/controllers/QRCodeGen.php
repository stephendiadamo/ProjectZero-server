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
	// ADD NEW USER
	
	public function addUser(){		
		$this->load->model("users");		
		$this->users->addNewUser($_GET["user"] , $_GET["account_type_id"]);
	}	
	
	// GET DRUGS BY USER_ID
	public function getDrugsByUser(){	
		$this->load->model("users");
		$results = $this->users->getDrugs($_GET["user_id"]);		
		echo json_encode($results->result_array());
	}
	
	public function addNewPrescription(){
	
		$user_id = $_GET["user_id"];
		$doctor_id = $_GET["doctor_id"];
		$drug = $_GET["drug"];
		$qrcode = $this->rawGenerate($doctor_id . ";" . $user_id . ";" . $drug);
		
		$this->load->model("users");
		$this->users->addNewDrug($user_id, $doctor_id, $drug, $qrcode);	
	}	 
}
?>