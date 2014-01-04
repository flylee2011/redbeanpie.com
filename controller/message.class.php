<?php
if( !defined('IN') ) die('bad request');
include_once( AROOT . 'controller'.DS.'app.class.php' );

// 消息页
class messageController extends appController
{
	function __construct()
	{
		parent::__construct();
	}
	
	// 信息-收到的
	function index()
	{
		$data['title'] = '收到的信息';
		render($data, 'web', 'default');
	}
	// 信息-发出的
	function index_send()
	{
		$data['title'] = '发出的信息';
		render($data, 'web', 'default');
	}
	// 信息-单条详情
	function msg_detail()
	{

	}

	// 约会-发出的
	function dating_send()
	{

	}
	// 约会-收到的
	function dating_in()
	{

	}

	// 收藏-派对
	function save_party()
	{

	}
	// 收藏-我收藏的会员
	function save_people()
	{

	}
	// 收藏-收藏我的会员
	function saved_people()
	{

	}

	
}