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
	* @param string nickname
	* @param string email
	* @param string com_email_suffix
	* @param string com_email_prefix
	* @param string com_email_id
	* @return resobj json
	*/
	public function user_presign() {
		$params = array();
		$params['username'] = v('email');
		$params['nickname'] = v('nickname');
		$params['email'] = v('email');
		$params['com_email_suffix'] = v('com_email_suffix');
		$params['com_email_prefix'] = v('com_email_prefix');
		$params['com_email_id'] = intval(v('com_email_id'));

		$dsql = array();
		
		$dsql[] = "'" . s( $params['username'] ) . "'";
		$dsql[] = "'" . s( $params['nickname'] ) . "'";
		$dsql[] = "'" . s( $params['email'] ) . "'";
		$dsql[] = "'" . s( $params['com_email_suffix'] ) . "'";
		$dsql[] = "'" . s( $params['com_email_prefix'] ) . "'";
		$dsql[] = "'" . s( $params['com_email_id'] ) . "'";

		$sql = "INSERT INTO `rbp_userinfo` ( `username` , `nickname` , `email` , `com_email_suffix` , `com_email_prefix`, `com_email_id` ) VALUES ( " . join( ' , ' , $dsql ) . " )";

		run_sql($sql);
		
		$resobj = array();
		$resobj['code'] = '0';
		$resobj['msg'] = 'success';
		$resobj['data'] = '';

		if(db_errno() != 0) {
			$resobj['code'] = db_errno();
			$resobj['msg'] = 'failure';
			echo json_encode($resobj);
		}else {
			echo json_encode($resobj);
		}
		// echo json_encode(array('dataList'=>$resobj));
	}

	public function check_presign() {
		$params = array();
		$params['name'] = v('name');
		$params['param'] = v('param');
		$params['com_email_suffix'] = v('com_email_suffix');

		$resobj = array();
		$resobj['info'] = '验证通过';
		$resobj['status'] = 'y';

		
		if($params['name'] === 'useremail') {
			$sql = "SELECT COUNT(*) FROM `rbp_userinfo` WHERE `email` = '" . $params['param'] . "'";
			if(get_var($sql) >= 1) {
				$resobj['info'] = '该邮箱已预定';
				$resobj['status'] = 'n';
			}
		}else if($params['name'] === 'comemail') {
			$sql = "SELECT COUNT(*) FROM `rbp_userinfo` WHERE `com_email_suffix` = '" . $params['com_email_suffix'] . "' AND `com_email_prefix` = '" . $params['param'] . "'";
			if(get_var($sql) >= 1) {
				$resobj['info'] = '该邮箱已预定';
				$resobj['status'] = 'n';
			}
		}

		echo json_encode($resobj);
	}

}

?>