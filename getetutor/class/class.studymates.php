<?php
require_once(PATH_TO_CLASS."class.database.php");
class studymates {
	var $id,
		$mate_name,
		$mate_type,
		$mate_size,		 
		$department_id,
		$course_id,
		$uploaded_by,
		$is_active,		
		$db_conn = NULL,
		$sql = '';
	
	function __construct() 
	{
		$database = new database();
	   	$this->table_name='study_materials';
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
					$data 						= mysql_fetch_assoc($result);
					$this->id 					= $data['id'];
					$this->mate_name 			= $data['mate_name'];
					$this->mate_type			= $data['mate_type'];
					$this->mate_size			= $data['mate_size'];
					$this->department_id		= $data['department_id'];
					$this->course_id			= $data['course_id'];
					$this->uploaded_by			= $data['uploaded_by'];
					$this->is_active			= $data['is_active'];
					
				} else {
					$this->id 					= '';
					$this->mate_name 			= '';
					$this->mate_type			= '';
					$this->mate_size			= '';
					$this->department_id		= '';
					$this->course_id			= '';
					$this->uploaded_by			= '';
					$this->is_active			= '';					
				}
				mysql_free_result($result);
			}
			$this->closeClientConn();
		} else {
					$this->id 					= '';
					$this->mate_name 			= '';
					$this->mate_type			= '';
					$this->mate_size			= '';
					$this->department_id		= '';
					$this->course_id			= '';
					$this->uploaded_by			= '';
					$this->is_active			= '';			
				}
	}
	
	function save($id)
	{
		$this->id=$id;
		if ($this->id>0) {
		
			$str = "	mate_name  			= '" . $this->mate_name . "',
						mate_type  			= '" . $this->mate_type . "',
						mate_size  			= '" . $this->mate_size . "',
						department_id  		= '" . $this->department_id . "',
						course_id  			= '" . $this->course_id . "',
						uploaded_by  		= '" . $this->uploaded_by . "'
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
				$str = "	mate_name  			= '" . $this->mate_name . "',
							mate_type  			= '" . $this->mate_type . "',
							mate_size  			= '" . $this->mate_size . "',
							department_id  		= '" . $this->department_id . "',
							course_id  			= '" . $this->course_id . "',
							uploaded_by  		= '" . $this->uploaded_by . "'
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
		if(database::deleteQuery($this->table_name,'id',$id)) {
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
	 	if(database::statusChange('is_active',$this->table_name,'id',$this->id)) {
			$this->admin_msg = "Status has been changed successfully";	
	 	} else {
		 	$this->errors="Status has been changed not successfully";
		}
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
 
