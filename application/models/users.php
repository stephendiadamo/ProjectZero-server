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
			
	function addNewPatient($first_name, $last_name, $account_type_id, $password, $ohip){
		$data = array(
			'account_type_id'=>$account_type_id,
			'password'=>$password,
			'first_name'=>$first_name,
			'last_name'=>$last_name,
			'OHIP'=>$ohip
		);	
		return $this->db->insert('users', $data);  
	}
	
	function addNewUser($first_name, $last_name, $account_type_id, $password){
		$data = array(
			'account_type_id'=>$account_type_id,
			'password'=>$password,
			'first_name'=>$first_name,
			'last_name'=>$last_name,
			'OHIP'=>"NA"
		);	
		return $this->db->insert('users', $data);  
	}
	
	function getPrescData($user_id){	
		$query_string = "SELECT * 
						 FROM prescriptions
						 WHERE user_id = " . $user_id;
		return $this->db->query($query_string);		
	}
	
	function getQRCode($user_id, $drug){
		$query_string = "SELECT qrcode
				         FROM prescriptions
				         WHERE user_id = " . $user_id .
				         " AND drug_name = '" . $drug . "'";
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
	
	function scanPresc($user_id, $drug){
		$decrease_refills = "UPDATE prescriptions
						 	 SET refills = refills - 1
						 	 WHERE user_id = " . $user_id .
						 	 " AND drug_name = '" . $drug . "'";
		
		$times_filled = "UPDATE prescriptions
						 	 SET times_filled = times_filled + 1
						 	 WHERE user_id = " . $user_id .
						 	 " AND drug_name = '" . $drug . "'";
		
		$this->db->query($decrease_refills);
		$this->db->query($times_filled);	
	}
		
	function removeUser($user_id){
		$this->db->delete('users', array('id' => $user_id));
	}	
	
	function login($user, $password){
		$query_string = "SELECT account_type_id 
						 FROM users
						 WHERE name = '" . $user .
						 "' AND password = '" . $password . "'"; 		
		return $this->db->query($query_string);
	}

	function editPrescDrug($user_id, $doctor_id, $drug, $new_drug){
		$query_string = "UPDATE prescriptions 
						 SET drug = '" . $new_drug . "'" .
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


	// TODO: Implement this
	function fixQRCode($user_id, $doctor_id, $drug){
		
		$query_string = "SELET * FROM prescriptions" .
						" WHERE user_id = " . $user_id . 
						" AND drug_name = '" . $drug . "'" .
						" AND doctor_id = " . $doctor_id;
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

			$qrcode = $user_id . ";" . $doctor_id . ";" . $drug . ";" . $note . ";" . $date . ";" . $refills . ";" . $times_filled;

			$query_string = "UPDATE prescriptions 
						 SET qrcode = " . $qrcode .
						 " WHERE user_id = " . $user_id . 
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
						 SET first_name = '" . $last_name .
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
}

?>