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
		$params = array();
		$params['uid'] = intval(v('uid'));
		
		if($params['uid'] > 0) {

			$resjson = send_request('get_profile_info_by_uid', $params);
			$res = json_decode( $resjson , 1 );

			$data['user_info'] = $res['data']['user_info'];
			$data['album_info'] = $res['data']['album_info'];
			$data['title'] = '个人中心';
			
			render($data, 'web', 'default');
		}
		

	}
	
}