<?php
require_once(PATH_TO_CLASS."class.database.php");
class ip_country {
	var $range_from, 
		$range_to,
		$country_short_code,
		$country_long_code,
		$country_name,
		$db_conn = NULL,
		$sql = '';
	
	function __construct() 
	{
		$database = new database();
	   	$this->table_name='ip_country';
	}
	
	function getCountryName($ip_address)
	{
		$sql_ip 	= "SELECT INET_ATON('".$ip_address."') AS range_value";
		$result_ip 	= $this->search($sql_ip);
		
		$range_value= $result_ip[0]['range_value'];
		
		$sql 	= "SELECT country_short_code, country_long_code, country_name  FROM ip_country WHERE range_from <=".$range_value." AND range_to >=".$range_value;
		$result = $this->search($sql);
		if(!empty($result))
		{
			return $result[0]['country_name'];
		}
		else
		{
			return "Country not found";
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
 
