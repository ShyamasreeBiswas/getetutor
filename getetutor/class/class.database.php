<?php
require_once(INCLUDES_PATH."getetutor_init.php");
class database {
	var $TableName, $IDName, $ID, $sql, $db_conn;

	function  __construct()
    {
		$db_conn = NULL;
		$db_conn = openAdminConn($db_conn);				
    }
	
	public function runQuery($sql)  
    {
		$rsinfo 	= mysql_query($sql) or die(mysql_error()."Error in run");
		$totalrows	= 0;
		$trecord 	= mysql_num_rows($rsinfo);
		if($trecord != 0 ) {
			while ($row = mysql_fetch_array($rsinfo)) {
				$result[$totalrows]=$row;
				$totalrows++;
			}
			mysql_free_result($rsinfo);
			$this->rows=$trecord;
			return $result;
		} else {
			return false;
		}
	}
	function selectQuery($TableName,$IDName,$ID)
    {
		$sql 	= "	SELECT 	* 
					FROM 	".$TableName." 
					WHERE 	".$IDName."='".$ID."'
			   	  ";
		$exe	= mysql_query($sql) or die(mysql_error()."Error in select");
		return mysql_fetch_array($exe);
    }
    
	function executeQuery($sql)
    {
		$check=mysql_query($sql) or die(mysql_error()."Error in execute");
		if($check) {
			return true;
		} else {
			return false;
		}
   } 
   
   function updateQuery($TableName,$Str,$IDName,$ID) 
   {
		$sql	= "	UPDATE 	".$TableName." 
					SET 	".$Str." 
					WHERE 	".$IDName."='".$ID."'
				  "; 
		$check	= mysql_query($sql) or die(mysql_error()."Error in update");
		if(mysql_affected_rows()===1) {
			return true;
		} else {
			return false;
		}
    } 
	
	function insertQuery($TableName,$Str)
	{
		$sql	= "	INSERT INTO ".$TableName." SET ".$Str;
		$check	= mysql_query($sql) or die(mysql_error()."Error in insert");
		if(mysql_affected_rows()===1) {
			$this->last_insert_id=mysql_insert_id();
			return true;
		} else {
			return false;
		}
	}
	
	function deleteQuery($TableName,$IDName,$ID)
	{
		$sql	= " DELETE FROM ".$TableName." WHERE ".$IDName."='". $ID."'";
		mysql_query($sql) or die(mysql_error()." Error in delete.");
		if(mysql_affected_rows()>0) {
		   return true;
		} else {
		   return false;
		}
	}

	function statusChange($FieldNames,$TableName,$IDName,$ID)
	{
		$sql	= " UPDATE 	".$TableName." 
					SET 	".$FieldNames." = if(".$FieldNames." = 'N','Y','N') 
					WHERE 	".$IDName."='". $ID."'";
		mysql_query($sql) or die(mysql_error()." Error in status changed.");
		if(mysql_affected_rows()>0) {
		   return true;
		} else {
		   return false;
		}
	}

	function getValue($FieldNames,$TableName,$IDName,$ID)
	{
		$sql	= "	SELECT 	".$FieldNames." 
					FROM 	".$TableName." 
					WHERE 	".$IDName."='".$ID."'
				  ";
		$exe	= mysql_query($sql) or die(mysql_error()."Error in get value");
		$rs 	= mysql_fetch_array($exe);
		if(mysql_num_rows($exe) > 0) {
			return $rs[0];
		} else {
			return "";
		}
		mysql_free_result($exe);
	}
	
	function getLastInsertId()
	{
		return $this->last_insert_id;
	}
	
	function getNumRows() {
        return $this->rows;
    } 
	
	private function logError($args,$exception) {
		$this->error 	= $exception->getMessage();
		$filename 		= "error.txt";
		if (!$handle = fopen($filename, 'a+')) {
			echo "Cannot open file ($filename)";
			exit;
		}
		fwrite($handle,date("l dS of F Y h:i:s A"));
		fwrite($handle,"\n");
		if (is_array($args)) foreach ($args as $arguments) fwrite($handle,"argument: $arguments\n");
		fwrite($handle,"error: ".$exception->getMessage()."\n");
		fclose($handle);
	}

	function Close()
    {
        mysql_close();
    }
	
	function  __destruct()
	{
	}

} 

class DB extends database {
	var $table, $fields = array();

	function DB($table)
	{
		parent::database();
		$this->table = $table;
	}
	
	function getfields()
	{
	    $sql = " SHOW COLUMNS FROM ".$this->table;
		return parent::runQuery($sql);
	}
	
	function setfields($columns)
	{
		foreach($columns as $value)
		$this->fields[] = $value;
	}
	
	function insert($env)
	{
		$temp = array();
		foreach($this->fields as $field)
		$temp[] = "'".addslashes($env[$field])."'";
		$sql 	= " INSERT INTO ".$this->table." (".join(",",$this->fields).") VALUES (".join(",",$temp).");";
		return parent::insertQuery($sql);
	} 
	
	function insert3($env)
	{
		$temp = array();
		foreach($this->fields as $field) {
			if(is_array($env[$field])) {
				$string = implode(',',$env[$field]);
				$temp[] = "'$string'";
			} else {
				$temp[] = "'".($env[$field])."'";
			}
		}
		$sql = " INSERT INTO ".$this->table." (".join(",",$this->fields).")values (".join(",",$temp).");";
		return parent::insertQuery($sql);
	} 
	
	function insert2($value=array())
	{
		$temp = array();
		foreach($value as $val) {
			$temp[] = "'".addslashes($val)."'";
		}
		$sql = " INSERT INTO ".$this->table." (".join(",",$this->fields).") VALUES (".join(",",$temp).");";
		return parent::insertQuery($sql);
	}
   
	function update( $id, $data, $unique_field )
	{
		$temp = array();
		foreach( $this->fields as $field ) {
		if($data[$field]) {
			$temp[] = $field."='".addslashes($data[$field])."'";
		}
	}
	$sql = "update ".$this->table." set ".join(",",$temp)."    where $unique_field='$id'";
	return parent::updateQuery($sql);
}	
		
function update1( $id, $data, $unique_field ) {
	$temp = array();
	foreach( $this->fields as $field ) {
		if($data[$field]) {
			if(is_array($data[$field])) {
				$string = implode(',',$data[$field]);
				$temp[] = $field."='".addslashes($string)."'";
			} else {
				$temp[] = $field."='".addslashes($data[$field])."'";
			}
		}
	}
	$sql = " UPDATE ".$this->table." 
			 SET 	".join(",",$temp)."    
			 WHERE 	$unique_field='$id'
		   ";
	return parent::updateQuery($sql);
}
	
function getList()
{
	$sql = " SELECT * FROM ".$this->table;
	return parent::runQuery($sql);
}
}
?>