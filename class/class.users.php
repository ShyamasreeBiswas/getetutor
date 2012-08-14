<?php
require_once(PATH_TO_CLASS."class.database.php");
class users {
	var $id, 
		$name,
		$username,
		$password,
		$email,
		$type, 
		$is_active,
		$department_id,
		$db_add_date,
		$db_conn = NULL,
		$sql = '';
	
	function __construct() 
	{
		$database = new database();
	   	$this->table_name='user_master';
	}
	
	function showData($init_id) 
	{
		if ($init_id >0) {
			$sql = " SELECT * 
					 FROM	".$this->table_name."
					 WHERE	id = '".$init_id."'";
			
			$result = mysql_query($sql);		
			if ($result) {
				if (mysql_num_rows($result) > 0) {
					$data 					= mysql_fetch_assoc($result);
					$this->id 				= $data['id'];
					$this->name				= $data['name'];
					$this->username			= $data['username'];
					$this->password 		= $data['password'];
					$this->email 			= $data['email'];
					$this->is_active 		= $data['is_active'];
					$this->department_id 	= $data['department_id'];
				} else {
					$this->id 				= '';
					$this->name				= '';
					$this->username			= '';
					$this->password 		= '';
					$this->email			= '';
					$this->is_active 		= '';
					$this->department_id 	= '';
					
				}
				mysql_free_result($result);
			}
			$this->closeClientConn();
		} else {
					$this->id 				= '';
					$this->name				= '';
					$this->username			= '';
					$this->password 		= '';
					$this->email			= '';
					$this->is_active 		= '';
					$this->department_id 	= '';		
				}
	}
	
	function save($user_id)
	{
		$this->user_id=$user_id;
		
		if($this->type=='T'){
			$this->type='SUB';
		}else {
			$this->type='SUBS';
		}
		if ($this->user_id>0) {
		
			$str = "	name  			= '" . $this->name . "',
						username  		= '" . $this->username . "',
						email  			= '" . $this->email	. "',
						type			= '" . $this->type	. "',						
						department_id	= '" . $this->department_id . "'
					";
			if($this->password!="")
			{
				$str	.= ",password		= '" . md5($this->password ). "'";
			}
		
			if(database::updateQuery($this->table_name,$str,'id',$this->user_id) ) {
				$this->admin_msg = "Update Successful";
				return true;
			} else {
				$this->admin_msg = "Update Failed";
				return false;
			}
			$this->closeClientConn();
		} 
		else
		{
				$str = "	name 			= '" . $this->name  		. "',
							username 		= '" . $this->username  		. "',
							password		= '" . md5($this->password) 		. "',
							email  			= '" . $this->email	. "', 
							type			= '" . $this->type	. "',
							department_id 	= '" . $this->department_id 	. "',
							db_add_date  	= NOW()
						";
							
				if(database::insertQuery($this->table_name,$str)) {
					$this->admin_msg = "Insert Successful";
					return true;
				} else {
					$this->admin_msg = "Insert Failed";
					return false;
				}
			 
		}
	}
  
	function deleteData($user_id) 
	{
		if(database::deleteQuery($this->table_name,'id',$user_id)) {
			$this->admin_msg = "Delete Successful";
			return true;
 	    } else {
			$this->admin_msg = "Delete Failed";
		  	return false;
		}
	}
   
	function search($sql)
    {
    	$this->sql=$sql;
	 	return database::runQuery($this->sql);
	}	
	//**delete user from database
	function removeUser() {
		
	}
	
	function activeDeactive($user_id)
   	{
		$this->user_id = $user_id; 
	 	if(database::statusChange('is_active',$this->table_name,'id',$this->user_id)) {
			$this->admin_msg = "Status has been changed successfully";	
	 	} else {
		 	$this->errors="Status has been changed not successfully";
		}
    }
	
    function isEmailExist() 
	{
		$sql 	=  " 	SELECT 	email 
						FROM 	".$this->table_name." 
						WHERE 	email='" . $this->email ."'
				   ";
        $results = mysql_query($sql);
        return ((mysql_num_rows($results)>0) ? false : true);
    }
	
	//**check if username already exist
    function isUserNameExist() 
	{
		$sql 	 = " SELECT username 
					 FROM 	".$this->table_name." 
					 WHERE  username='" . $this->username ."'
				   ";
        $results = mysql_query($sql);
        return ((mysql_num_rows($results)>0) ? false : true);
    }
	
	function getName($user_id)
	{
		$sql 	 = " SELECT name 
					 FROM 	".$this->table_name." 
					 WHERE  id='" . $user_id ."'
				   ";
      $search_array = $this->search($sql);
	  return  ucfirst($search_array[0]['name']);

	}
	
	function getEmail($user_id)
	{
		$sql 	 = " SELECT email 
					 FROM 	".$this->table_name." 
					 WHERE  id='" . $user_id ."'
				   ";
      $search_array = $this->search($sql);
	  return  ucfirst($search_array[0]['email']);

	}
	
	function userListOption($user_id) 
	{
		$sqlquery=" SELECT * FROM ".$this->table_name." WHERE id <> 1";
		$result=self::search($sqlquery);
		$i=0;
		
		$option_others = "<select name = 'user_list' id = 'user_list'>";
		$option_others .= "<option value = ''>---Select User---</option>";  
		while($result[$i]!=NULL)
		{
			if($user_id==$result[$i]['id']) 
			{
				$option_others .=	"<option value=".$result[$i]['id']." selected = 'selected'>".stripslashes($result[$i]['name'])."</option>";
			}
			else 
			{
				$option_others .=	"<option value=".$result[$i]['id'].">".stripslashes($result[$i]['name'])."</option>";
			}
			

	 		$i++;
	 	}
		$option_others .= "</select>";
	 	echo $option_others;
   	}
	
		function openAdminConn()
	{
		$this->db_conn = openAdminConn($this->db_conn);
	}
	
	function closeClientConn()
	{
		closeConn($this->db_conn);
		$this->db_conn = NULL;
		
	}
}
?>