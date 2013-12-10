<?php 
	
	function forward( $url )
	{
		header( "Location: " . $url );
	}

	function jsforword( $url )
	{
		return '<script>location="' . $url . '"</script>';
	}

	// 后台管理员是否登录
	function is_admin_login()
	{
		// return true;
		return $_SESSION['admin_level'] == 1;
	}
?>