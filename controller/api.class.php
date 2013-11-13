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
		
	}

}

?>