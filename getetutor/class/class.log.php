<?php
require_once(PATH_TO_CLASS."class.database.php");
class log_details {
	var $id, 
		$user_id,
		$event,
		$ip_address,
		$db_add_date,
		$db_conn = NULL,
		$sql = '';
	
	function __construct() 
	{
		$database = new database();
	   	$this->table_name='log_details';
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
					$this->id 			= $data['id'];
					$this->area_code	= $data['area_code'];
					$this->state_code 	= $data['state_code'];
				} else {
					$this->id 			= '';
					$this->area_code	= '';
					$this->state_code 	= '';
				}
				mysql_free_result($result);
			}
			$this->closeClientConn();
		} else {
					$this->id 			= '';
					$this->area_code	= '';
					$this->state_code 	= '';
				}
	}
	
	function save($id)
	{
		$this->id=$id;
		if ($this->id>0) 
		{
			$str = "	user_id  	= '" . $this->user_id  . "',
						event  		= '" . $this->event  . "',
						ip_address  = '" . $this->ip_address  . "',
						db_add_date = NOW()
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
			$str = "	user_id  	= '" . $this->user_id  . "',
						event  		= '" . $this->event  . "',
						ip_address  = '" . $this->ip_address  . "',
						db_add_date = NOW()
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
 
