<?php 

class Users extends CI_Model {

	function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	function addNewUser($name, $account_type_id){
		$data = array(
			'name'=>$name,
			'account_type_id'=>$account_type_id
		);	
		return $this->db->insert('users', $data);  
	}
	
	// TODO: WRITE THIS
	function getDrugs($user_id){
	}

}

?>