<?php
if( !defined('IN') ) die('bad request');
include_once( AROOT . 'controller'.DS.'app.class.php' );

class guestController extends appController
{
	function __construct()
	{
		parent::__construct();
	}
	
	// 未登录首页
	function index()
	{
		$data['title'] = '首页';
		render($data, 'web', 'default');
	}

	// 登录页
	function signin()
	{
		$data['title'] = '首页';
		render($data, 'web', 'default');
	}
	
	// 注册
	function signup()
	{
		$data['title'] = '首页';
		render($data, 'web', 'default');
	}
	
}
	