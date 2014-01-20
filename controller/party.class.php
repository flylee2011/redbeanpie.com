<?php
if( !defined('IN') ) die('bad request');
include_once( AROOT . 'controller'.DS.'app.class.php' );

// 派对页
class partyController extends appController
{
	function __construct()
	{
		parent::__construct();
	}
	
	// 派对列表
	function index()
	{
		$data['title'] = '派对';
		render($data, 'web', 'default');
	}

	// 派对详情
	function detail()
	{
		$data['title'] = '派对';
		render($data, 'web', 'default');
	}

	// 发起派对
	function launch()
	{
		$data['title'] = '发起派对';
		render($data, 'web', 'default');
	}
	
}