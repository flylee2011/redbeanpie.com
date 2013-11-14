<?php
/**
 * @fileoverview api接口
 * @authors yifei (flylee.bjfu@gmail.com)
 * @date    2013-11-13 23:32:08
 */

if( !defined('IN') ) die('bad request');
include_once( AROOT . 'controller'.DS.'app.class.php' );

class apiController extends appController {

	function __construct() {
		parent :: __construct();
	}

	/**
	* 用户预订注册
	*
	* @param string name
	* @param string email
	* @param string password
	* @return user array
	*/
	public function user_presign() {
		$params = array();
		$params['username'] = v('username');
		$params['nickname'] = v('nickname');
		$params['email'] = v('email');
		$params['com_email_suffix'] = v('com_email_suffix');
		$params['com_email_prefix'] = v('com_email_prefix');
		$params['com_email_id'] = intval(v('com_email_id'));


		$obj = array();
		$obj[ 'code' ] = '0';
		$obj[ 'msg' ] = 'success';
		$obj[ 'data' ] = '';

		// echo json_encode(array('dataList'=>$obj));
		echo json_encode($obj);
	}

}

?>