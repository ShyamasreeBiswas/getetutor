<?php
require_once(PATH_TO_CLASS."class.database.php");
class file_manager {
	var $id, 
		$file_name,
		$password,
		$available_days,
		$file_create_date,
		$file_unlink_date,
		$db_conn = NULL,
		$sql = '';
	
	function __construct() 
	{
		$database = new database();
	   	$this->table_name='file_manager';
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
					$data 				= mysql_fetch_assoc($result);
					$this->id 			= $data['id'];
					$this->name			= $data['name'];
					$this->password 	= $data['password'];
					$this->is_active 	= $data['is_active'];
					$this->can_upload 	= $data['can_upload'];
					$this->can_download = $data['can_download'];
				} else {
					$this->id 			= '';
					$this->name			= '';
					$this->password 	= '';
					$this->is_active 	= '';
					$this->can_upload 	= '';
					$this->can_download = '';
				}
				mysql_free_result($result);
			}
			$this->closeClientConn();
		} else {
					$this->id 			= '';
					$this->name			= '';
					$this->password 	= '';
					$this->is_active 	= '';
					$this->can_upload 	= '';
					$this->can_download = '';		
				}
	}

	
	function save($id)
	{
		$this->id=$id;
		if ($this->id>0) 
		{
			$str = "	file_name  		= '" . $this->file_name  . "',
						password		= '" . $this->password . "',
						available_days	= '" . $this->available_days . "',
						file_create_date= '" . $this->file_create_date . "',
						file_unlink_date= '" . $this->file_unlink_date . "'
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
			$str = "	file_name  		= '" . $this->file_name  . "',
						password		= '" . $this->password . "',
						available_days	= '" . $this->available_days . "',
						file_create_date= '" . $this->file_create_date . "',
						file_unlink_date= '" . $this->file_unlink_date . "'
					";
							
				if(database::insertQuery($this->table_name,$str)) {
					$last_id = database::getLastInsertId();
					$this->admin_msg = "Insert Successful";
					return $last_id;
				} else {
					$this->admin_msg = "Insert Failed";
					return 0;
				}
			 
		}
	}
	
	
	function search($sql)
    {
    	$this->sql=$sql;
	 	return database::runQuery($this->sql);
	}	

	function deleteFile($id)
	{
		$sql = "DELETE FROM ".$this->table_name." WHERE id = ".$id;
		database::executeQuery($sql);
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
 
