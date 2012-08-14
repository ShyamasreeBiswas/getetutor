<?php
require_once(PATH_TO_CLASS."class.database.php");
class schedules {
	var $id,
		$sdate, 
		$day,
		$stime,
		$maxstrength,
		$user_id,
		$status,		
		$db_conn = NULL,
		$sql = '';
	
	function __construct() 
	{
		$database = new database();
	   	$this->table_name='schedules';
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
					$this->sdate 			= $data['sdate'];
					$this->day				= $data['day'];
					$this->stime			= $data['stime'];
					$this->maxstrength		= $data['maxstrength'];
					$this->user_id			= $data['user_id'];
					$this->status			= $data['status'];
					
				} else {
					$this->id 				= '';
					$this->sdate 			= '';
					$this->day				= '';
					$this->stime			= '';
					$this->maxstrength		= '';
					$this->user_id			= '';
					$this->status			= '';				
				}
				mysql_free_result($result);
			}
			$this->closeClientConn();
		} else {
					$this->id 				= '';
					$this->sdate 			= '';
					$this->day				= '';
					$this->stime			= '';
					$this->maxstrength		= '';
					$this->user_id			= '';
					$this->status			= '';		
				}
	}
	
	function save($id)
	{
		$this->id=$id;
		if ($this->id>0) {
		
			$str = "	sdate  				= '" . $this->sdate . "',
						day  				= '" . $this->day . "',
						stime  				= '" . $this->stime . "',
						maxstrength  		= '" . $this->maxstrength . "',
						user_id				= '" . $this->user_id . "'
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
				$str = "	sdate  				= '" . $this->sdate . "',
							day  				= '" . $this->day . "',
							stime  				= '" . $this->stime . "',
							maxstrength  		= '" . $this->maxstrength . "',
							user_id				= '" . $this->user_id . "'
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
 
