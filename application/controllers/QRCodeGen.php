<?php 
include "application/libraries/phpqrcode/qrlib.php";

class QRCodeGen extends CI_Controller {
	public function generate(){		
		QRcode::png($_GET["code"]);
	}
	
	// DATABASE STUFF: 	
	// ADD NEW USER
	
	public function addUser(){		
		$this->load->model("users");		
		$this->users->addNewUser($_GET["user"] , $_GET["account_type_id"]);
	}	
	// GET DRUGS BY USER_ID
}
?>