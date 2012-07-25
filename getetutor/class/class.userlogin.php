<?php
require_once(PATH_TO_CLASS."class.database.php");
class userlogin {
	var $id, $username, $password, $new_pwd, $type, $is_active, $can_upload, $can_download, $db_add_date;
	var $errors='';

	function userlogin()
	{
	 	$database = new database();
		$this->table_name='user_master';
	}
	
	function loginUser($username, $password)
	{
		$this->username = $username;
		$this->password= md5($password);
		$this->lastLoginTime =  date ("Y-m-d H:m:s");
			
		if($this->validateUser())
		{
			Session::set_var('loggedin','true');
			Session::set_var('password', $this->password);
			Session::set_var('username', $this->username);
			Session::set_var('id', $this->id);
			Session::set_var('type', $this->type);
			Session::set_var('can_upload', $this->can_upload);
			Session::set_var('can_download', $this->can_download);
			Session::set_var('ip_address', $_SERVER['REMOTE_ADDR']);
			return true;
		} 
		else 
		{
			Session::set_var('loggedin','false');
			Session::set_var('admin_status', 'failed log in attempt');
			return false;
		}
	}
		
	function logOutUser()
	{
		 
		//$file_path = "D:/xampp/htdocs/getetutor/admin/"; 
		
		/*if($_SESSION['id']>0)
		{
			$sql = "SELECT file_name from download_files WHERE user_id = ".$_SESSION['id'];
			$rsinfo 	= mysql_query($sql);
			$totalrows	= 0;
			$trecord 	= mysql_num_rows($rsinfo);
			if($trecord != 0 ) 
			{
				while ($row = mysql_fetch_assoc($rsinfo)) 
				{
					$result[$totalrows]=$row;
					$file_name = $result[$totalrows]['file_name'];
					
					////////////////// delete file from server /////////////
						$path = $file_path."download_files/".$file_name; 
						if(file_exists($path))
						{
							unlink($path);
							mysql_query("DELETE FROM download_files WHERE file_name = '".$file_name."'");
						}
					////////////////// delete file from server /////////////
					
					$totalrows++;
				}
				mysql_free_result($rsinfo);
			}
			Session::destroy();
		}*/
		
		Session::destroy();
	}
	
	function validateUser()
	{
		$validationQry = "	SELECT 	* 
							FROM 	".$this->table_name." 
							WHERE 	username = '$this->username'
							AND		is_active = 'Y'
						 ";
		if($validationResults = mysql_query($validationQry)) 
		{
			$validationData = mysql_fetch_assoc($validationResults);
			if($this->password==  md5($validationData['password'])) 
			{
				$this->admin_status = "success";
				$this->username = $validationData['username'];
				$this->id = $validationData['id'];
				$this->type = $validationData['type'];
				$this->can_upload = $validationData['can_upload'];
				$this->can_download = $validationData['can_download'];
				return true;
			}
			else
			{
				$this->admin_status = "password";
				return false;
			}
			closeConn($db_conn);
		} 
		else 
		{
			$this->admin_status = "email :" . mysql_error();
			return false;
		}
	}
		
	function isExistsPassword()
	{
		$sql = "	SELECT 	* 
					FROM 	".$this->table_name." 
					WHERE 	password='" . $this->password. "' 
					AND 	id ='".$_SESSION["id"]. "'
				";
		if(!($results = mysql_query($sql))) {
			$this->errors .= mysql_error() . "<br /><br />";
			return true;
		}
			return ((mysql_num_rows($results)) ? true : false);
			closeConn($db_conn);
		}
		
		function userChangePassword() {
			if ($this->isExistsPassword()) {
				$sql = "	UPDATE 	".$this->table_name." 
							SET 	password='$this->new_pwd' 
							WHERE 	id ='".$_SESSION["id"]. "' 
						";
				return mysql_query(sprintf($sql, $this->new_pwd, $_SESSION["id"]));
			}
			$this->errors .= mysql_error() . "<br /><br />";
			closeConn($db_conn);
		}
		
 }
?>
