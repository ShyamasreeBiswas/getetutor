<?php
require_once(PATH_TO_CLASS."class.database.php");
class menu {
	var $menu_id,
		$menu_name, 
		$parent_id,
		$page_name,
		$display_order,
		$is_active,
		$sub_admin,
		$db_conn = NULL,
		$sql = '';
	
	function __construct() 
	{
		$database = new database();
	   	$this->table_name='menu';
	}
	
	function showData($init_id) 
	{
		if ($init_id >0) {
			$sql = " SELECT * 
					 FROM	".$this->table_name."
					 WHERE	menu_id = '".$init_id."'"; 
			
			$result = mysql_query($sql);		
			if ($result) {
				if (mysql_num_rows($result) > 0) {
					$data 						= mysql_fetch_assoc($result);
					$this->menu_id 				= $data['menu_id'];
					$this->menu_name 			= $data['menu_name'];
					$this->parent_id			= $data['parent_id'];
					$this->page_name			= $data['page_name'];
					$this->display_order		= $data['display_order'];
					$this->is_active			= $data['is_active'];
					$this->sub_admin			= $data['sub_admin'];
					
				} else {
					$this->menu_id 				= '';
					$this->menu_name 			= '';
					$this->parent_id			= '';
					$this->page_name			= '';
					$this->display_order		= '';
					$this->is_active			= '';
					$this->sub_admin			= '';					
				}
				mysql_free_result($result);
			}
			$this->closeClientConn();
		} else {
					$this->menu_id 				= '';
					$this->menu_name 			= '';
					$this->parent_id			= '';
					$this->page_name			= '';
					$this->display_order		= '';
					$this->is_active			= '';
					$this->sub_admin			= '';					
				}
	}
	
	
	function menuListOption($menu_value) 
	{
		$sqlquery=" select * from ".$this->table_name;
		$result=self::search($sqlquery);
		$i=0;
		
		while($result[$i]!=NULL)
		{
			if($menu_value==$result[$i]['menu_id']) 
			{
				$option_others .=	"<option value=".$result[$i]['menu_id']." selected = 'selected'>".stripslashes($result[$i]['menu_name'])."</option>";
			}
			else 
			{
				$option_others .=	"<option value=".$result[$i]['menu_id'].">".stripslashes($result[$i]['menu_name'])."</option>";
			}
			

	 		$i++;
	 	}
		$option_others .= "</select>";
	 	echo $option_others;
   	}
	
	
	function menuParentListOption($parent_value) 
	{
		//echo $parent_value; die;
		$sqlquery=" SELECT * FROM ".$this->table_name." WHERE parent_id = 0";
		$result=self::search($sqlquery);
		$i=0;
		
		$option_others = "<select name = 'parent' menu_id = 'menu'>";
	
		while($result[$i]!=NULL)
		{
			if($parent_value==$result[$i]['menu_id']) 
			{
				$option_others .=	"<option value=".$result[$i]['menu_id']." selected = 'selected'>".stripslashes($result[$i]['menu_name'])."</option>";
			}
			else 
			{
				$option_others .=	"<option value=".$result[$i]['menu_id'].">".stripslashes($result[$i]['menu_name'])."</option>";
			}
			

	 		$i++;
	 	}
		$option_others .= "</select>";
	 	echo $option_others;
   	}
	
	function getMenuName($menu_id)
	{
		$query = "SELECT menu_name FROM menu WHERE menu_id = '".$menu_id."'";
		$result=$this->search($query);
		return $result;
	}
	
	function getParentMenuName($parent_id)
	{
		$sql = "SELECT menu_name FROM menu WHERE menu_id = '".$parent_id."'";
		$search_array = $this->search($sql);
	  	return  ucfirst($search_array[0]['menu_name']);
		
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
 
