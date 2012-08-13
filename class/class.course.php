<?php
require_once(PATH_TO_CLASS."class.database.php");
class course {
	var $id,
		$course_name, 
		$department_id,
		$tutor_name,
		$is_active,		
		$db_conn = NULL,
		$sql = '';
	
	function __construct() 
	{
		$database = new database();
	   	$this->table_name='course';
	}
	
	function showData($init_id) 
	{
		
		if ($init_id >0) {
			$sql = " SELECT * 
					 FROM	".$this->table_name."
					 WHERE	course_id = '".$init_id."'";
			
			$result = mysql_query($sql);		
			if ($result) {
				if (mysql_num_rows($result) > 0) {
					$data 					= mysql_fetch_assoc($result);
					$this->id 				= $data['course_id'];
					$this->course_name 		= $data['course_name'];
					$this->department_id	= $data['department_id'];
					$this->tutor_name		= $data['tutor_name'];
					$this->is_active		= $data['is_active'];
					
				} else {
					$this->id 				= '';
					$this->course_name 		= '';
					$this->department_id	= '';
					$this->tutor_name		= '';
					$this->is_active		= '';					
				}
				mysql_free_result($result);
			}
			$this->closeClientConn();
		} else {
					$this->id 				= '';
					$this->course_name 		= '';
					$this->department_id	= '';
					$this->tutor_name		= '';
					$this->is_active		= '';				
				}
	}
	
	function save($id)
	{
		$this->id=$id;
		if ($this->id>0) {
		
			$str = "	course_name  			= '" . $this->course_name . "',
						department_id  			= '" . $this->department_id . "',
						tutor_name  			= '" . $this->tutor_name . "'
					";
		
			if(database::updateQuery($this->table_name,$str,'id',$this->id) ) {
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
				$str = "	course_name  			= '" . $this->course_name . "',
							department_id  			= '" . $this->department_id . "',
							tutor_name  			= '" . $this->tutor_name . "'
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
  
	function deleteData($id) 
	{
		if(database::deleteQuery($this->table_name,'course_id',$id)) {
			$this->admin_msg = "Delete Successful";
			return true;
 	    } else {
			$this->admin_msg = "Delete Failed";
		  	return false;
		}
	}
   	
		
	function activeDeactive($id)
   	{
		$this->id = $id; 
	 	if(database::statusChange('is_active',$this->table_name,'course_id',$this->id)) {
			$this->admin_msg = "Status has been changed successfully";	
	 	} else {
		 	$this->errors="Status has been changed not successfully";
		}
    }
	
	
		
	function getCourseName($course_id)
	{
		$query = "SELECT course_name FROM course WHERE course_id = '".$course_id."'";
		$search_array=$this->search($query);
		return  ucfirst($search_array[0]['course_name']);
	}
	
	
	function search($sql)
    {
    	$this->sql=$sql;
	 	return database::runQuery($this->sql);
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
 
