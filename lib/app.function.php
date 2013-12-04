<?php 
	
	function is_admin_login()
	{
		return $_SESSION['level'] == 9;
	}
?>