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
		$data['title'] = '首页';
		render($data, 'web', 'default');
	}
	
}
	