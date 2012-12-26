<?php 
include "application/libraries/phpqrcode/qrlib.php";

class QRCodeGen extends CI_Controller {
	public function generate(){		
		QRcode::png($_GET["code"]);
	}
	
	
	// DATABASE STUFF: 
	
	// ADD NEW USER
	// GET DRUGS BY USER_ID
	
	
}
?>