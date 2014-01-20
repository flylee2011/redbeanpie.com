<?php
if( !defined('IN') ) die('bad request');
include_once( AROOT . 'controller'.DS.'app.class.php' );

// 约会页
class datingController extends appController
{
	function __construct()
	{
		parent::__construct();
	}
	
	// 约会列表
	function index()
	{
		$data['title'] = '约会';
		render($data, 'web', 'default');
	}

	// 约会详情
	function detail()
	{
		$data['title'] = '约会';
		render($data, 'web', 'default');
	}
	// 发起约会
	function launch()
	{
		$data['title'] = '发起约会';
		render($data, 'web', 'default');
	}
	
}