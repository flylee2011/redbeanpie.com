<?php

/**
 * 数据model，主要是数据库操作，由api controller调用
 *
 * @return 数据集或false
 */

define( 'USER_INFO' , "`id` ,  `id` as  `uid` , `username` , `password` , `email`, `com_email_prefix`, `com_email_suffix`, `com_email_id`, `invitation_code`, `nickname` , `gender` , `email_active` , `info_complete`, `level` " );

// 获取用户信息
function get_full_info_by_email_password( $email , $password )
{
	$sql = "SELECT ". USER_INFO ." FROM `rbp_userinfo` WHERE `email` = '" . s( $email ) . "' LIMIT 1";
	if(!$line = get_line( $sql )) return false;

	$ret = false;
	
	$passwordv1 = md5( $password  );

	if( $passwordv1 == $line['password'] ) {
		$ret = $line;
	}
	
	return $ret;
}

// 获取所有公司邮箱信息
function get_full_cominfo()
{
	$sql = "SELECT * FROM `rbp_comemail`";
	$ret = false;

	if($data = get_data($sql)) {
		$ret = $data;
	}

	return $ret;
}

// 激活邮箱
function update_email_status($email, $type)
{
	$ret = false;
	// 公司邮箱激活
	if($type === 1) {
		$email_arr = explode('@', $email);
		$sql = "UPDATE `rbp_userinfo` SET `email_active`=1 WHERE `com_email_prefix`='" . $email_arr[0] . "' AND `com_email_suffix`='" . $email_arr[1] . "'";
	}
	// 个人邮箱激活
	if($type === 2) {
		$sql = "UPDATE `rbp_userinfo` SET `email_active`=1 WHERE `email`='" . $email . "'";
	}

	run_sql($sql);

	if(db_errno() === 0) {
		$ret = true;
	}
	return $ret;
}

?>
