<?php 
class Users extends CI_Model {

	function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	function fetchUsers(){
		return $this->db->get("users");	
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
}

?>