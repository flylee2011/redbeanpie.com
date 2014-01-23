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
	function message_in()
	{
		$data['title'] = '收到的信息';
		render($data, 'web', 'default');
	}
	// 信息-发出的
	function message_send()
	{
		$data['title'] = '发出的信息';
		render($data, 'web', 'default');
	}
	// 信息-单条详情
	function msg_detail()
	{
		$data['title'] = '单条信息';
		render($data, 'web', 'default');
	}

	// 约会-发出的
	function dating_send()
	{
		$data['title'] = '约会';
		render($data, 'web', 'default');
	}
	// 约会-收到的
	function dating_in()
	{
		$data['title'] = '约会';
		render($data, 'web', 'default');
	}

	// 收藏-派对
	function save_party()
	{
		$data['title'] = '收藏';
		render($data, 'web', 'default');
	}
	// 收藏-我收藏的会员
	function save_people()
	{
		$data['title'] = '收藏';
		render($data, 'web', 'default');
	}
	// 收藏-收藏我的会员
	function saved_people()
	{
		$data['title'] = '收藏';
		render($data, 'web', 'default');
	}

	
}