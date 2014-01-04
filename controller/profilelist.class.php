<?php
if( !defined('IN') ) die('bad request');
include_once( AROOT . 'controller'.DS.'app.class.php' );

// 会员列表页
class profileListController extends appController
{
	function __construct()
	{
		parent::__construct();
	}
	
	// 会员列表首页
	function index()
	{
		$data['title'] = '浏览会员';
		render($data, 'web', 'default');
	}
	
}