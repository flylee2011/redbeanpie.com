<?php
if( !defined('IN') ) die('bad request');
include_once( CROOT . 'controller' . DS . 'core.class.php' );

class adminController extends coreController
{
	function __construct()
	{
		// 载入默认的
		parent::__construct();
	}

	function index()
	{
		
	}
	
	
}


?>