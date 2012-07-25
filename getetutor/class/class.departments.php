<?php
require_once(PATH_TO_CLASS."class.database.php");
class departments {
	var $id,
		$code, 
		$name,
		$status,		
		$db_conn = NULL,
		$sql = '';
	
	function __construct() 
	{
		$database = new database();
	   	$this->table_name='departments';
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
					$this->code 		= $data['code'];
					$this->name			= $data['name'];
					$this->status		= $data['status'];
					
				} else {
					$this->id 			= '';
					$this->code 		= '';
					$this->name			= '';
					$this->status		= '';					
				}
				mysql_free_result($result);
			}
			$this->closeClientConn();
		} else {
					$this->id 			= '';
					$this->code 		= '';
					$this->name			= '';
					$this->status		= '';					
				}
	}
	
	function save($id)
	{
		$this->id=$id;
		if ($this->id>0) {
		
			$str = "	code  			= '" . $this->code . "',
						name  			= '" . $this->name . "'
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
				$str = "	code 			= '" . $this->code . "',
							name 			= '" . $this->name . "'
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
	 	if(database::statusChange('status',$this->table_name,'id',$this->id)) {
			$this->admin_msg = "Status has been changed successfully";	
	 	} else {
		 	$this->errors="Status has been changed not successfully";
		}
    }
	
	
	function departmentListOption($dept_value) 
	{
		$sqlquery=" select * from ".$this->table_name." where status='Y'";
		$result=self::search($sqlquery);
		$i=0;
		
		$option_others = "<select name = 'department_id' id = 'department_id'>";
		while($result[$i]!=NULL)
		{
			if($dep_value==$result[$i]['id']) 
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
	
	function getDepartmentName($department_code)
	{
		$query = "SELECT name FROM departments WHERE code = '".$department_code."'";
		$result=$this->search($query);
		return $result;
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
 
