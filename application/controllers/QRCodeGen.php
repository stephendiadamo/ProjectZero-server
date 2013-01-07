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
	
	public function fetchDoctors(){
		$this->load->model("users");
		$data = $this->users->fetchDoctors();
		if ($data->result_array() != null){
			echo json_encode($data->result_array());
		}
	}
	
	public function fetchPharmacists(){
		$this->load->model("users");
		$data = $this->users->fetchPharmacists();
		if ($data->result_array() != null){
			echo json_encode($data->result_array());
		}
	}
		
	// Add a new user	
	public function addUser(){		
		$this->load->model("users");
		if (isset($_GET["first_name"]) && isset($_GET["last_name"]) && isset($_GET["account_type_id"]) && isset($_GET["password"]) && isset($_GET["ohip"])){
			try{	
				$retData = $this->users->addNewUser($_GET["first_name"], $_GET["last_name"], $_GET["account_type_id"], $_GET["password"], $_GET["ohip"]);				
				if ($retData){
					echo "SUCCESS";
				} else {
					echo "FAIL";
				}				
			} catch (Exception $e) {
				echo "FAIL: DATABASE ERROR";
			}
		} else {
			echo "FAIL: NOT ALL FIELDS FILLED";
		}
	}
	
	// Get info by user_id
	public function getUser(){			
		if (isset($_GET["user_id"])){
			$this->load->model("users");
			$results = $this->users->getPrescData($_GET["user_id"]);
			if ($results->result_array() != null){						
				echo json_encode($results->result_array());
			} else {
				echo "No data";
			}
		} else {
			echo "FAIL: parameter not set";
		}
	}

	public function getValidPrescriptions(){
		if (isset($_GET["user_id"])){
			$this->load->model("users");
			$results = $this->users->getValidPrescData($_GET["user_id"]);
			if ($results->result_array() != null){		
				echo json_encode($results->result_array());
			} else {
				echo "No data";
			}
		} else {
			echo "FAIL: parameter not set";
		}

	}
	
	public function retrieveQRCode(){
		$this->load->model("users");
		if (isset($_GET["presc_id"])){
			$results = $this->users->getSinglePrescDataById($_GET["presc_id"]);
			if ($results->result_array() != null){		
				$data = $results->result_array();								
				$presc_id = $data[0]["presc_id"];
				QRcode::png($presc_id, false, "L", 4, 2);								
			} else {
				echo "No such prescription";
			}
		} else {
			echo "FAIL";
		}
	}
	
	public function decreasePresc(){
		if (isset($_GET["presc_id"])){			
			$this->load->model("users");
			$presc_res = $this->users->getPrescById($_GET["presc_id"]);
			if ($presc_res->result_array() != null){
				$data = $presc_res->result_array();				
				$uid = $data[0]["user_id"];
				$drug = $data[0]["drug_name"];

				$this->users->descreasePresc($_GET["presc_id"]);
				$this->users->fixQRCode($uid, $drug);					

				$res = $this->users->getSinglePrescDataById($_GET["presc_id"]);

				if ($res->result_array() != null){
					$temp = $res->result_array();
					echo "[".$temp[0]["qrcode"]."]";
				} else {
					echo "No such Prescription";
				}
			} else {
				echo "No such prescription";
			}
		}
		else {
			echo "FAIL: prescription id is required";
		}

	}

	public function scanCode(){		
		if (isset($_GET["presc_id"])){			
			$this->load->model("users");
			$presc_res = $this->users->getPrescById($_GET["presc_id"]);
			if ($presc_res->result_array() != null){
				$data = $presc_res->result_array();				
				$uid = $data[0]["user_id"];
				$drug = $data[0]["drug_name"];				
				$res = $this->users->getSinglePrescDataById($_GET["presc_id"]);
				if ($res->result_array() != null){
					$temp = $res->result_array();
					echo "[".$temp[0]["qrcode"]."]";
				} else {
					echo "No such Prescription";
				}
			} else {
				echo "No such prescription";
			}
		}
		else {
			echo "FAIL: prescription id is required";
		}
	}

	public function editPresc(){
				
		$this->load->model("users");
		if (isset($_GET["user_id"]) && isset($_GET["doctor_id"]) && isset($_GET["drug"])){
			$uid = $_GET["user_id"];
			$did = $_GET["doctor_id"];
			$drug = $_GET["drug"];

			if (isset($_GET["note"])){
				$this->users->editPrescNote($uid, $did, $drug, $_GET["note"]);
			}

			if (isset($_GET["refills"])){
				$this->users->editPrescRefills($uid, $did, $drug, $_GET["refills"]);	
			}

			if (isset($_GET["new_drug"])){
				$this->users->editPrescDrug($uid, $did, $drug, $_GET["new_drug"]);
				$drug = $_GET["new_drug"];
			}

			$this->users->fixQRCode($uid, $drug);	
			$results = $this->users->getPrescData($uid);
			if ($results->result_array() != null){		
				echo json_encode($results->result_array());
			}
		
		} else {
			echo "FAIL: REQUIRED INFO NOT SET";
		}
	}
	

	public function editUser(){
		$this->load->model("users");

		if (isset($_GET["user_id"])){
			$uid = $_GET["user_id"];			
			if (isset($_GET["first_name"])){
				$this->users->editUserFirstName($uid, $_GET["first_name"]);
			}

			if (isset($_GET["last_name"])){
				$this->users->editUserLastName($uid, $_GET["last_name"]);
			}

			if (isset($_GET["password"])){
				$this->users->editUserPassword($uid, $_GET["password"]);
			}

			if (isset($_GET["ohip"])){
				$this->users->editUserOHIP($uid, $_GET["ohip"]);
			}
			$this->fetchUsers();
		}
	}

	public function addPresc(){
		if (isset($_GET["user_id"]) && isset($_GET["doctor_id"]) && isset($_GET["drug"])){
			$user_id = $_GET["user_id"];
			$doctor_id = $_GET["doctor_id"];
			$drug = $_GET["drug"];
			$note = "none";
			if (isset($_GET["note"])){
				$note = $_GET["note"];
			}
			$refills = 0;
			if (isset($_GET["refills"])){
				$refills = $_GET["refills"];
			}					

			$date = date("d.m.Y");
			$qrcode =  array("user_id" => $user_id,
							 "doctor_id" => $doctor_id,
							 "drug" => $drug,
							 "note" => $note,
							 "date" => $date,
							 "refills" => $refills,
							 "times_filled" => "0"
						);

			
			$this->load->model("users");
			
			try{
				$qr_json = json_encode($qrcode);	
				$this->users->addNewDrug($user_id, $doctor_id, $drug, $qr_json, $note, date("Ymd"), $refills);								
				$last_inserted = strval(mysql_insert_id());				
				QRcode::png($last_inserted, false, "L", 4, 2);
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
		if (isset($_GET["ohip"]) && isset($_GET["password"])){
			$this->load->model("users");
			$res = $this->users->login($_GET["ohip"], $_GET["password"]);
			if ($res->result_array() != null){
				echo json_encode($res->result_array());
			} else {
				echo "FAIL";
			}
		} else {
			echo "FAIL";
		} 
	}

	public function getUserFromName(){
		if (isset($_GET["first_name"]) && isset($_GET["last_name"])){
			$this->load->model("users");
			$res = $this->users->getUserIDFromName($_GET["first_name"], $_GET["last_name"]);
			if ($res->result_array() != null){
				echo json_encode($res->result_array());
			} else {
				echo "no such user";
			}
		} else {
			echo "FAIL: FIRST AND LAST NAME NOT ENTERED";
		}
	}

	public function getUserFromOHIP(){
		if (isset($_GET["ohip"])){
			$this->load->model("users");
			$res = $this->users->getUserIDFromOHIP($_GET["ohip"]);
			if ($res->result_array() != null){
				echo json_encode($res->result_array());
			} else {
				echo "no such user";
			}
		} else {
			echo "FAIL: OHIP NOT ENTERED";
		}
	}
}
?>