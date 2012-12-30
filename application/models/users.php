<?php 

class Users extends CI_Model {

	function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	function fetchUsers(){
		return $this->db->get("users");	
	}
	
	function addNewUser($name, $account_type_id, $password){
		$data = array(
			'name'=>$name,
			'account_type_id'=>$account_type_id,
			'password'=>$password
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
	
	function addNewDrug($user_id, $doctor_id, $drug, $qrcode, $note, $date){
		$data = array(
			'drug_name'=>$drug,
			'user_id'=>$user_id,
			'doctor_id'=>$doctor_id,
			'qrcode'=>$qrcode,
			'note'=>$note,
			'date'=>$date
		);	
		return $this->db->insert('prescriptions', $data);
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