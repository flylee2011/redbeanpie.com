<?php
if( !defined('IN') ) die('bad request');
include_once( CROOT . 'controller' . DS . 'core.class.php' );

class appController extends coreController
{
	function __construct()
	{
		// 载入默认的
		parent::__construct();

		if( g('c') != 'api' )
		{
			// set session time
			session_set_cookie_params( c('session_time') );
			@session_start();
		}
	}

	// login check or something

	function check_login()
	{
		if(!is_login()) {
			return forward( '?c=guest' );
		}else {
			// 没有激活邮箱
			if(!is_email_active()) {
				return forward('?c=guest&a=signup_checkemail');
			}
		}
	}
	
	
}


?>