<?php 
include "application/libraries/phpqrcode/qrlib.php";

class QRCodeGen extends CI_Controller {
	public function generate($code){		
		QRcode::png($code);
	}
}
?>