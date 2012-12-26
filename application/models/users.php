<?php 

class Users extends CI_Model {

	function __construct(){
		parent::__construct();
	}
	
	function addNewUser(){			
		$name = $_POST["name"];
		$account_type_id = $_POST["account_type_id"];		
		$query_string = "INSERT INTO users ('id', 'name', 'account_type_id') VALUES (NULL," . $name . "," . $account_type_id . ")";		
		$query = $this->db->query($query_string);	
	}
	
	
	// TODO: WRITE THIS
	function getDrugs($user_id){
	}

}

?>