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

	function importDataFromCSV()
	{
		$file_path  = $this->file_path;
		$file_name  = $this->file_name;
		$user_id	= $_SESSION['id'];
		
		$sql_last_id = "SELECT lead_id FROM lead_details ORDER BY lead_id DESC LIMIT 0,1";
		$last_id_array	  = $this->search($sql_last_id);
		$last_id	= $last_id_array[0]['lead_id'];
		if($last_id=="") { $last_id = 0; } else { $last_id = $last_id; }
				
		$sql = "LOAD DATA LOCAL INFILE '".$file_path."' INTO TABLE lead_details FIELDS TERMINATED BY ','  ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES  (first_name, 
				middle_name, 
				last_name, 
				street_address1, 
				street_address2, 
				city, 
				state, 
				zip, 
				county, 
				phone)";
		
		if(mysql_query($sql))
		{
			$latest_id = database::getLastInsertId();
			
			$no_of_affected_rows = mysql_affected_rows();
			
			////////////////////////////// Set State ///////////////////////////////////////
			$sql_update_state= "UPDATE lead_details SET state = '' WHERE lead_id > ".$last_id;
			mysql_query($sql_update_state);
			
			////////////////////////////// Set Area Code ///////////////////////////////////
			$sql_update_area= "UPDATE lead_details SET area_code = SUBSTR(`phone`,1,3) WHERE lead_id > ".$last_id;
			mysql_query($sql_update_area);
			
			////////////////////////////// Set Lead Pack Name and User Id ///////////////////////////////////
			$sql_update_pack = "UPDATE lead_details SET lead_pack_name = '".$file_name."', uploaded_by = '".$user_id."' WHERE lead_id > ".$last_id;
			mysql_query($sql_update_pack);
			
			/////////////////////////////// Update State with State-Area Code Master //////////////////////////
			$rows = mysql_query("SELECT area_code, state_code FROM area_code_master");
			while ($row = mysql_fetch_assoc($rows)) 
			{
				$area_code 	= $row['area_code'];
				$state_code = $row['state_code'];
				
				mysql_query("UPDATE lead_details SET state = '".$state_code."' WHERE area_code = ".$area_code." WHERE lead_id > ".$last_id);
			}
			
			return $no_of_affected_rows;
			
			
		}
		else
		{
			return false;
		}
	}
	
	function importDataFromFtpFile()
	{
		$ftp_file_path	= $this->ftp_file_path."/ftp_uploaded_file/";
		$file_name		= $this->file_name;
		
		$this->file_path = $ftp_file_path.$file_name;
		
		$no_of_affected_rows = $this->importDataFromCSV();
		if($no_of_affected_rows>0)
		{
			///// move the file from one folder to another
			
			$old_file_path = $ftp_file_path.$file_name;
			$new_file_path = $this->ftp_file_path.$file_name;

			if(rename($old_file_path, $new_file_path))
			{
				return $no_of_affected_rows;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;		
		}
		
	}
	
	function getGroupByAreaCode($file_name)
	{
		$sql = "SELECT area_code, count(area_code) AS cnt_area FROM lead_details WHERE lead_pack_name = '".$file_name."' GROUP BY area_code";
		$result = $this->search($sql);
		return $result;
	}
	
	function getCountByAreaCode($area_code)
	{
		$sql = "SELECT count(area_code) AS cnt_area FROM lead_details WHERE area_code = ".$area_code;
		$result = $this->search($sql);
		return $result[0]['cnt_area'];
	}
	
	function updateByAreaCodeState($area_code, $state_code)
	{
		$query	= "UPDATE ".$this->table_name." SET state = '".$state_code."' WHERE area_code = ".$area_code;
		mysql_query($query);
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
 
