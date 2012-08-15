<?php 
$link = mysql_connect("localhost", "root", "");
mysql_select_db("getetutor", $link);

$q=$_GET["q"];


	$sql_email = "select * from user_master WHERE `email`='".$q."'";
	$row_email = mysql_query($sql_email);
	
	$count_email = mysql_num_rows($row_email);
	
	if($count_email > 0) {
		
		$res_email = mysql_fetch_array($row_email);
		$to=$q; 
	
	$fromEmail="admin@gmail.com";
	$subject="Password Recovery";
	
	/////////////////////////////// pwd generator /////////////////////////////////////
	
		function random_generator($digits){
			srand ((double) microtime() * 10000000);
			//Array of alphabets
			$input = array ("A", "B", "C", "D", "E","F","G","H","I","J","K","L","M","N","O","P","Q",
			"R","S","T","U","V","W","X","Y","Z");
			
			$random_generator="";// Initialize the string to store random numbers
			for($i=1;$i<$digits+1;$i++){ // Loop the number of times of required digits
			
			if(rand(1,2) == 1){// to decide the digit should be numeric or alphabet
			// Add one random alphabet
			$rand_index = array_rand($input);
			$random_generator .=$input[$rand_index]; // One char is added
			
			}else{
			
			// Add one numeric digit between 1 and 10
			$random_generator .=rand(1,10); // one number is added
			} // end of if else
			
			} // end of for loop
			
			return $random_generator;
		} // end of function
		
		
		$password = random_generator(8); //die;
		/////////////////////////////// pwd generator /////////////////////////////////////
			
		$body="<table width=\"100%\" border=\"0\">
				<tr><td>&nbsp;</td></tr>					
			   <tr>
				 <td>Your Password is given below. Please go to the link below for login.</td>
			   </tr>
			   <tr><td>&nbsp;</td></tr>
			  <tr>
				<td><a href=\"index.php\">localhost/getetutor</a></td>
			  </tr>
				<tr>
					<td>Your Login details given below</td>
				</tr>
				  <tr>
					<td>Username : ".$res_email['username']."</td>
				  </tr>
				  <tr>
					<td>Password : ".$password."</td>
				</tr>
			</table>";
		//print $text; die;
		
		
		$sql_pwd_up = "update user_master SET `password` = '".md5($password)."' WHERE `email` =".$q."";
		mysql_query($sql_pwd_up);
		
		$headers  		= "MIME-Version: 1.0\r\n";
		$headers 		.= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers 		.= "From: Admin <".$fromEmail.">\r\n";
		
		mail($to, $subject, $body, $headers);
		echo "<font color=\"#FF0000\">Please check your email for password.</font>";
		
	}else {
		echo "<font color=\"#FF0000\">Invalid Email address.</font>";
	}
?>