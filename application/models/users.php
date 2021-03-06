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
			
	function addNewUser($first_name, $last_name, $account_type_id, $password, $ohip, $birthday, $description){
		$data = array(
			'account_type_id'=>$account_type_id,
			'password'=>$password,
			'first_name'=>$first_name,
			'last_name'=>$last_name,
			'OHIP'=>$ohip,
			'birthday'=>$birthday,
			'description'=>$description	
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

	function getValidPrescData($user_id){
		$query_string = "SELECT * 
						 FROM prescriptions
						 WHERE user_id = " . $user_id . 
						 " AND refills > 0";
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

	function getPrescById($presc_id){
		$query_string = "SELECT *
				         FROM prescriptions
				         WHERE presc_id = " . $presc_id;

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
			'times_filled'=>0,
			'isValid'=>"yes"
		);	
		return $this->db->insert('prescriptions', $data);
	}

	function descreasePresc($presc_id){
		$decrease_refills = "UPDATE prescriptions
						 	 SET refills = refills - 1
						 	 WHERE presc_id = " . $presc_id .
						 	 " AND refills > 0";
						 	 		
		$times_filled = "UPDATE prescriptions
						 	 SET times_filled = times_filled + 1
						 	 WHERE presc_id = " . $presc_id . 
						 	 " AND refills > 0";
		
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
			$isValid = $data[0]["isValid"];


			$qrcode =  array("user_id" => $user_id,
							 "doctor_id" => $doctor_id,
							 "drug" => $drug,
							 "note" => $note,
							 "date" => $date,
							 "refills" => $refills,
							 "times_filled" => $times_filled,
							 "isValid"=>$isValid
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

	function getUserById($id){
			$query_string = "SELECT * 
						 FROM users 
						 WHERE id = " . $id;
		return $this->db->query($query_string);
	}

	function removePresc($presc_id){
		return $this->db->delete('prescriptions', array('presc_id' => $presc_id));
	}

	function setPrescInvalid($presc_id){
		$query_string = "UPDATE prescriptions 
						 SET isValid = 'no' 
						 WHERE presc_id = " . $presc_id;
		return $this->db->query($query_string);
	}

	function getValidPrescDataByValid($user_id){
		$query_string = "SELECT * 
						 FROM prescriptions
						 WHERE user_id = " . $user_id . 
						 " AND isValid = 'yes'";
		return $this->db->query($query_string);
	}

	function getValidPrescDataByInvalid($user_id){
		$query_string = "SELECT * 
						 FROM prescriptions
						 WHERE user_id = " . $user_id . 
						 " AND isValid = 'no'";
		return $this->db->query($query_string);
	}

	function fixInvalid($presc_id){
		$query_string = "SELECT refills
						 FROM prescriptions 
						 WHERE presc_id = " . $presc_id;
		$result = $this->db->query($query_string);
		if ($result->result_array() != null){		
				$data = $result->result_array();								
				$num_refills = $data[0]["refills"];
				if ($num_refills <= 0) {
					 $this->setPrescInvalid($presc_id);
				}
		}
		return $result;
	}

	function addUserDescription($user_id, $descr){
		$query_string = "UPDATE users 
						 SET description = '" . $descr . "'" .
						 " WHERE id = " . $user_id;
		return $this->db->query($query_string);
	}
	
	function getPatientsOfDoctor($doctor_id){
		$query_string = "SELECT * 
						 FROM users join (select user_id from users join prescriptions where doctor_id = " . $doctor_id . 
						 " group by user_id) as a where users.id = a.user_id";
		return $this->db->query($query_string);
	}

}

?>