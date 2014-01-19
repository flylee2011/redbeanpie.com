<?php
if( !defined('IN') ) die('bad request');
include_once( AROOT . 'controller'.DS.'app.class.php' );

// 登录后主页
class dashboardController extends appController
{
	function __construct()
	{
		parent::__construct();
		$this->check_login();
	}
	
	// 登录首页
	function index()
	{
		// session_set_cookie_params( c('session_time') );
		// @session_start();
		// echo $_SESSION['nickname'];
		
		$data['title'] = '首页';
		render($data, 'web', 'default');
	}
	
}
	