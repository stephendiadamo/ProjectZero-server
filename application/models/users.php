<?php 
class Users extends CI_Model {

	function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	function fetchUsers(){		
		$query = "SELECT * FROM users WHERE account_type_id = 2";
		return $this->db->query($query);	
	}
	
	function fetchDoctors(){
		$query = "SELECT * FROM users WHERE account_type_id = 1";
		return $this->db->query($query);	
	}
	
	function fetchPharmacists(){
		$query = "SELECT * FROM users WHERE account_type_id = 3";
		return $this->db->query($query);	
	}
			
	function addNewUser($first_name, $last_name, $account_type_id, $password, $ohip){
		$data = array(
			'account_type_id'=>$account_type_id,
			'password'=>$password,
			'first_name'=>$first_name,
			'last_name'=>$last_name,
			'OHIP'=>$ohip
		);	

		$query = "SELECT * FROM users WHERE OHIP = '" . $ohip . "'";
		$retData = $this->db->query($query);
		if ($retData->result_array() == null){
			try{
				return $this->db->insert('users', $data);
			} catch (Exception $e){
				return "FAIL";
			}
		}
	}	
	
	function getPrescData($user_id){	
		$query_string = "SELECT * 
						 FROM prescriptions
						 WHERE user_id = " . $user_id;
		return $this->db->query($query_string);		
	}

	function getSinglePrescData($user_id, $drug){	
		$query_string = "SELECT *
				         FROM prescriptions
				         WHERE user_id = " . $user_id .
				         " AND drug_name = '" . $drug . "'";
		return $this->db->query($query_string);  
	}
	
	function getQRCode($user_id, $drug){
		$query_string = "SELECT qrcode
				         FROM prescriptions
				         WHERE user_id = " . $user_id .
				         " AND drug_name = '" . $drug . "'";
		return $this->db->query($query_string);  
	}

	function getPrescById($id){
		$query_string = "SELECT *
				         FROM prescriptions
				         WHERE id = " . $id;

		return $this->db->query($query_string);  
	}
	
	function addNewDrug($user_id, $doctor_id, $drug, $qrcode, $note, $date, $refills){
		$data = array(
			'drug_name'=>$drug,
			'user_id'=>$user_id,
			'doctor_id'=>$doctor_id,
			'qrcode'=>$qrcode,
			'note'=>$note,
			'date'=>$date,
			'refills'=>$refills,
			'times_filled'=>0
		);	
		return $this->db->insert('prescriptions', $data);
	}

	function scanPresc($presc_id){
		$decrease_refills = "UPDATE prescriptions
						 	 SET refills = refills - 1
						 	 WHERE id = " . $presc_id;
						 	 		
		$times_filled = "UPDATE prescriptions
						 	 SET times_filled = times_filled + 1
						 	 WHERE id = " . $presc_id;
		
		$this->db->query($decrease_refills);
		$this->db->query($times_filled);	
	}
		
	function removeUser($user_id){
		$this->db->delete('users', array('id' => $user_id));
	}	
	
	function login($ohip, $password){
		$query_string = "SELECT *
						 FROM users
						 WHERE ohip = '" . $ohip .
						 "' AND password = '" . $password . "'"; 		
		return $this->db->query($query_string);
	}

	function editPrescDrug($user_id, $doctor_id, $drug, $new_drug){
		$query_string = "UPDATE prescriptions 
						 SET drug_name = '" . $new_drug . "'" .
						 " WHERE user_id = " . $user_id . 
						 " AND drug_name = '" . $drug . "'" .
						 " AND doctor_id = " . $doctor_id;
		return $this->db->query($query_string);
	}

	function editPrescNote($user_id, $doctor_id, $drug, $note){
		$query_string = "UPDATE prescriptions 
						 SET note = '" . $note . "'" .
						 " WHERE user_id = " . $user_id . 
						 " AND drug_name = '" . $drug . "'" .
						 " AND doctor_id = " . $doctor_id;
		return $this->db->query($query_string);
	}

	function editPrescRefills($user_id, $doctor_id, $drug, $refills){
		$query_string = "UPDATE prescriptions 
						 SET refills = " . $refills .
						 " WHERE user_id = " . $user_id . 
						 " AND drug_name = '" . $drug . "'" .
						 " AND doctor_id = " . $doctor_id;
		return $this->db->query($query_string);
	}

	function fixQRCode($user_id, $drug){
		
		$query_string = "SELECT * FROM prescriptions" .
						" WHERE user_id = " . $user_id . 
						" AND drug_name = '" . $drug . "'";						
		$data = $this->db->query($query_string);
		
		if ($data->result_array() != null){
			$data = $data->result_array();
			$user_id = $data[0]["user_id"];
			$doctor_id = $data[0]["doctor_id"];
			$drug = $data[0]["drug_name"];
			$note = $data[0]["note"];
			$date = $data[0]["date"];
			$refills = $data[0]["refills"];
			$times_filled = $data[0]["times_filled"];

			$qrcode =  array("user_id" => $user_id,
							 "doctor_id" => $doctor_id,
							 "drug" => $drug,
							 "note" => $note,
							 "date" => $date,
							 "refills" => $refills,
							 "times_filled" => $times_filled
						);
			$qrcode = json_encode($qrcode);

			$query_string = "UPDATE prescriptions 
						 SET qrcode = '" . $qrcode .
						 "' WHERE user_id = " . $user_id . 
						 " AND drug_name = '" . $drug . "'" .
						 " AND doctor_id = " . $doctor_id;			
			return $this->db->query($query_string);
		}
	}

	function editUserFirstName($user_id, $first_name){
		$query_string = "UPDATE users 
						 SET first_name = '" . $first_name .
						 "' WHERE id = " . $user_id;
		return $this->db->query($query_string);	
	}

	function editUserLastName($user_id, $last_name){
		$query_string = "UPDATE users 
						 SET last_name = '" . $last_name .
						 "' WHERE id = " . $user_id;
		return $this->db->query($query_string);	
	}

	function editUserPassword($user_id, $password){
		$query_string = "UPDATE users 
						 SET password = '" . $password .
						 "' WHERE id = " . $user_id;
		return $this->db->query($query_string);	
	}

	function editUserOHIP($user_id, $ohip){
		$query_string = "UPDATE users 
						 SET OHIP = '" . $ohip .
						 "' WHERE id = " . $user_id;
		return $this->db->query($query_string);	
	}

	function getUserIDFromName($first_name, $last_name){
		$query_string = "SELECT * 
						 FROM users 
						 WHERE first_name = '" . $first_name . "'
						 AND last_name = '" . $last_name . "'";
		return $this->db->query($query_string);
	}

	function getUserIDFromOHIP($ohip){
		$query_string = "SELECT * 
						 FROM users 
						 WHERE ohip = '" . $ohip . "'";						 
		return $this->db->query($query_string);
	}

}

?>