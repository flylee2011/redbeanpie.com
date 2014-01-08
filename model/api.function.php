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

// 添加相册信息
function add_album_info($img_url, $cate_id, $user_id, $description = '')
{
	$img_url = s($img_url);
	$cate_id = intval($cate_id);
	$user_id = intval($user_id);
	$description = s($description);

	$sql = "INSERT INTO `rbp_album_info` ( `img_url` , `cate_id` , `user_id`, `description` ) VALUES ( '" . s($img_url) . "' , '" . intval( $cate_id ) . "', '" . intval( $user_id ) . "', '" . s($description) . "' )";
	
	run_sql($sql);
	if(db_errno() != 0) {
		return false;
	}
	$lid = last_id();

	return $lid;
}

// 更新用户头像
function update_user_avatar($uid, $avatar_url)
{
	$uid = intval($uid);
	$avatar_url = s($avatar_url);

	$sql = "UPDATE `rbp_userinfo` SET `avatar_url`='" . $avatar_url . "' WHERE `id`=" . $uid;

	run_sql($sql);
	if(db_errno() != 0) {
		return false;
	}
	return true;
}


// 根据用户uid，获取用户所有信息
function get_userinfo_by_uid($uid)
{
	$ret = false;

	$uid = intval($uid);
	$sql = "SELECT * FROM `rbp_userinfo` WHERE `id`=" . $uid;

	if($data = get_line($sql)) {
		$ret = $data;
	}
	return $ret;
}

// 根据用户uid，获取相册信息
function get_albuminfo_by_uid($uid)
{
	$ret = false;

	$uid = intval($uid);
	$sql = "SELECT `id`, `img_url`, `cate_id`, `user_id`, `description` FROM `rbp_album_info` WHERE `user_id`=" . $uid . " AND `cate_id`!=2";

	if($data = get_data($sql)) {
		$ret = $data;
	}
	return $ret;
}

// 根据用户uid，获取个人中心需要的信息，用户信息+相册信息
function get_profile_info_by_uid($uid)
{
	$ret = false;

	$uid = intval($uid);
	$sql_userinfo = "SELECT * FROM `rbp_userinfo` WHERE `id`=" . $uid;
	$sql_albuminfo = "SELECT `id`, `img_url`, `cate_id`, `user_id`, `description` FROM `rbp_album_info` WHERE `user_id`=" . $uid;

	$data = array();

	if($data['user_info'] = get_data($sql_userinfo) and $data['album_info'] = get_data($sql_albuminfo)) {
		$ret = $data;
	}

	return $ret;
}

// 根据用户uid，更新用户基本信息(sideinfo)
function update_user_sideinfo_by_uid($params, $uid)
{
	$uid = intval($uid);
	
	$dsql = array();
	$dsql[] = "`nickname`='" . s( $params['nickname'] ) . "'";
	$dsql[] = "`relationship`='" . s( $params['relationship'] ) . "'";
	$dsql[] = "`gender`='" . s( $params['gender'] ) . "'";
	$dsql[] = "`birthday`='" . s( $params['birthday'] ) . "'";
	$dsql[] = "`height`='" . s( $params['height'] ) . "'";
	$dsql[] = "`weight`='" . s( $params['weight'] ) . "'";
	$dsql[] = "`province_id`='" . s( $params['province'] ) . "'";
	$dsql[] = "`city_id`='" . s( $params['city'] ) . "'";
	$dsql[] = "`company_visible`='" . s( $params['company_visible'] ) . "'";
	$dsql[] = "`job`='" . s( $params['job'] ) . "'";
	$dsql[] = "`income`='" . s( $params['income'] ) . "'";
	$dsql[] = "`university`='" . s( $params['university'] ) . "'";
	$dsql[] = "`education`='" . s( $params['education'] ) . "'";
	$dsql[] = "`weibo_link`='" . s( $params['weibo_link'] ) . "'";

	$setstring = join( ' , ' , $dsql );

	$sql = "UPDATE `rbp_userinfo` SET ". $setstring ." WHERE `id`=" . $uid;
	
	run_sql($sql);
	if(db_errno() != 0) {
		return false;
	}
	return true;
}

// 根据用户uid，更新用户关于我信息
function update_user_aboutme_by_uid($params, $uid)
{
	$uid = intval($uid);

	switch ($params['data_field']) {
		case 'essay1':
			$sql = "UPDATE `rbp_userinfo` SET `essay1`='". $params['content'] ."' WHERE `id`=". $uid;
			break;
		case 'essay2':
			$sql = "UPDATE `rbp_userinfo` SET `essay2`='". $params['content'] ."' WHERE `id`=". $uid;
			break;
		case 'essay3':
			$sql = "UPDATE `rbp_userinfo` SET `essay3`='". $params['content'] ."' WHERE `id`=". $uid;
			break;
		case 'essay4':
			# code...
			break;
		default:
			return false;
			break;
	}
	
	run_sql($sql);
	if(db_errno() != 0) {
		return false;
	}
	return true;
}

?>

