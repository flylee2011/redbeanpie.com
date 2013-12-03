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
	// 首页
	function index()
	{
		$data['title'] = 'RedBeanPie管理后台';
		render($data, 'admin', 'index');
	}
	
	// 用户管理，预注册
	function preuser() {
		render($data, 'admin', 'index');
	}
	// 公司管理，公司信息
	function cominfo() {
		render($data, 'admin', 'index');
	}
	// 公司管理，行业信息
	function industryinfo() {
		render($data, 'admin', 'index');
	}
	
	
}


?>