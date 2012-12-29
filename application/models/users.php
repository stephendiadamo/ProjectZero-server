<?php 

class Users extends CI_Model {

	function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	function fetchUsers(){
		return $this->db->get("users");	
	}
	
	function addNewUser($name, $account_type_id){
		$data = array(
			'name'=>$name,
			'account_type_id'=>$account_type_id
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
	
	function addNewDrug($user_id, $doctor_id, $drug, $qrcode){
		$data = array(
			'drug_name'=>$drug,
			'user_id'=>$user_id,
			'doctor_id'=>$doctor_id,
			'qrcode'=>$qrcode
		);	
		return $this->db->insert('prescriptions', $data);
	}
	
	function removeUser($user_id){
		$this->db->delete('users', array('id' => $user_id));
	}	
}

?>