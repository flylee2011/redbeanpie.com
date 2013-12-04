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

	// 登录
	function login()
	{
		
	}
	// 登录页面
	function guest()
	{
		$data['title'] = 'RedBeanPie管理后台';
		render($data, 'admin', 'signin');
	}

	// 首页
	function index()
	{
		$data['title'] = 'RedBeanPie管理后台';
		render($data, 'admin', 'index');
	}
	
	// 用户管理，预注册
	function preuser() {
		$data['title'] = 'RedBeanPie管理后台';
		render($data, 'admin', 'index');
	}
	// 用户管理，注册用户
	function userinfo() {
		$data['title'] = 'RedBeanPie管理后台';
		render($data, 'admin', 'index');
	}
	// 公司管理，公司信息
	function cominfo() {
		$data['title'] = 'RedBeanPie管理后台';
		render($data, 'admin', 'index');
	}
	// 公司管理，行业信息
	function industryinfo() {
		$data['title'] = 'RedBeanPie管理后台';
		render($data, 'admin', 'index');
	}
	
	
}


?>