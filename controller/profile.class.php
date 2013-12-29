<?php
if( !defined('IN') ) die('bad request');
include_once( AROOT . 'controller'.DS.'app.class.php' );

// 个人中心
class profileController extends appController
{
	function __construct()
	{
		parent::__construct();
	}
	
	// 个人中心首页
	function index()
	{
		$data['title'] = '';
		render($data, 'web', 'default');
	}
	
}