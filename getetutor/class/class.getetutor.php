<?php
require_once(PATH_TO_CLASS."class.database.php");
class leads {
	var $lead_id, 
		$lead_pack_name,
		$first_name,
		$middle_name, 
		$last_name,
		$street_address1,
		$street_address2,
		$state,
		$city,
		$county,
		$zip,
		$area_code,
		$phone,
		$uploaded_by,
		$db_add_date,
		$file_path,
		$file_name,
		$ftp_file_path,
		$db_conn = NULL,
		$sql = '';
	
	function __construct() 
	{
		$database = new database();
	   	$this->table_name='lead_details';
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
 
