<?php
class Session {
	function Session()
	{
		session_start();
	}

    function set_var($varname,$varvalue)
    {
	    if(!isset($varname) || !isset($varvalue) ) {
        } else {
			$_SESSION[$varname] = $varvalue;
		}
	 }
	 
	 function get_var( $varname )
	 {
	 	if(!isset($varname)) {
        } else {
			return $_SESSION[$varname];
		}
	 }
	   
	// Get a current session string (EXAMPLE: PHPSESSID=b188e8c9c45b347cdded2)
	// Syntax: $sid = $session->get_sid_string();
	// echo "$sid" to grab the string id.  By default it uses PHPSESSID= variable
	function get_sid_string()
	{
		return session_name() . "=" . session_id();
	}
		 
	// Get current session ID (EXAMPLE: whatever=b188e8c9c45b347cdded2)
	// Syntax: $sid = $session->get_sid();
	function get_sid()
	{
		return session_id();
	}
	     
	// Unset a current session variable
	// Syntax: $session->var_unset("variable name");
	function var_unset($varname)
	{
		if(!isset($varname)) {
        } else {
			unset ($_SESSION[$varname]);
		}
	}
		 
	// This will delete your current session and delete every session variable created
	// Syntax: $session->destroy();
	function destroy()
	{
		Session::ses_unset();
	    session_destroy();
	}
	     
	// Display all variables in a current session
	// Syntax: $session->show();
	function show()
	{
		$completeSession = $_SESSION;
		foreach($completeSession as $k=>$v) {
			echo "$k => $v \n<br />";
		}
	}
		 
	// Unset every variable in the current session 
	// Syntax: $session->ses_unset();
	function ses_unset() {   
		if(isset($_SESSION)) {
			$a = $_SESSION;
			while(list($key,) = each($a)) {
				Session::var_unset($key);
			}   
		}
	 }
 }
?>
