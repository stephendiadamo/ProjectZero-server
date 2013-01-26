<html lang="en">

<head>
	<title>Register User</title>
	<?php 
		$this->load->helper('url');
		$this->load->helper('form');
	?>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.js"></script>
	<script>
	var jq;	
	// Something is conflicting with jquery 
	jq=jQuery.noConflict();		
	// Jquery loadup
	jq (function() {
		jq('#reg').submit(function() {
			var formdata = jq('#reg').serialize();
			var sub_url = "<?php echo base_url() . "index.php/QRCodeGen/addUser/?";?>" + formdata;
			jq.ajax({
				url: sub_url,
				success: function(data){
					alert("Success!");
				},
				error: function(xhr, ajaxOptions, thrownError){	
					alert("Error: " + xhr.responseText);
				}
			});
			return false;
		});
	});
		
	</script>
	<style type="text/css">
	
		body{
			padding: 100px;
			width: 1000px;
			margin: 0 auto;	
		}
		#content{
			width: 70%;
			margin: 0 auto;	
		}
		form{
			width: 50%;
			margin: 0 auto;	
		}
		
		h1{
			text-align: center;
		}
		
		input, select{
			padding: 5px;
			width: 150px;
		}
		#sub{
			width: 120px;
		
		}
		td{
		 	width: 170px;
		}	
	</style>
</head>

<body>
	<div id="content">
	<h1>Register User</h1>

	<form id="reg">
		<table>
		<tr>
			<td>First name: </td> 
			<td><input required type="text" name="first_name"/></td>
		</tr>
		<tr>
			<td>Last name: </td>
			<td><input required type="text" name="last_name"/></td>
		</tr> 
		<tr> 
			<td>Account type: </td>
			<td>
			<select name="account_type_id">
  				<option value="1">Doctor</option>
  				<option value="2">Patient</option>
  				<option value="3">Pharmacist</option>
			</select> </td>
		</tr>
		<tr><td> Password: </td><td><input required type="password" name="password"/></td></tr>
		<tr><td> Repeat password: </td><td><input required type="password" name="rep_password"/></td></tr>
		<tr><td>OHIP ID: </td><td><input required type="text" name="ohip"/></td></tr>
		<tr><td>Birthday: </td><td><input required type="text" name="birthday"/></td></tr>
		<tr><td><input id="sub" type="submit" value="Register User"/></td></tr>
		</table>	
	</form>	
	</div>
</body>

</html>