<?php
if( !defined('IN') ) die('bad request');
include_once( AROOT . 'controller'.DS.'app.class.php' );

// 未登录，登录，注册
class guestController extends appController
{
	function __construct()
	{
		parent::__construct();
	}
	
	// 未登录首页
	function index()
	{
		if( is_login() ) return forward( '?c=dashboard' );

		$data['title'] = '红豆派';
		render($data, 'web', 'default');
	}

	// 登录页
	function signin()
	{
		$data['title'] = '登录';
		render($data, 'web', 'default');
	}
	
	// 注册页
	function signup()
	{
		$resjson = send_request('api', 'get_full_cominfo', null);
		$cominfo_data = json_decode( $resjson , 1 );

		$data['title'] = '用户注册';
		$data['cominfo_arr'] = $cominfo_data['data'];

		render($data, 'web', 'default');
	}

	// 注册完成，验证邮箱页
	function signup_checkemail()
	{
		if(is_email_active()) {
			forward('?c=dashboard');
		}else {
			$data['title'] = '验证邮箱';
			render($data, 'web', 'default');
		}
		
	}

	// 注册完成，激活邮箱成功页
	function signup_complete()
	{
		$data['title'] = '邮箱激活成功';
		render($data, 'web', 'default');
	}

	/**********************************************************************/

	
	// 激活邮箱
	function signup_activeemail()
	{
		$params = array();
		$params['email'] = base64_decode(t(v('email')));
		$params['type'] = intval(v('type'));

		if($resjson = send_request('api', 'update_email_status', $params)) {
			$data = json_decode( $resjson , 1 );
			if($data['code'] === '0') {
				forward('?c=guest&a=signup_complete');
			}else {
				echo send_json_res($data['code'], $data['msg'], null);
			}
		}
	}
	
	// 登录
	function login()
	{
		if($user = login( v('email') , v('password') )) {
			foreach( $user as $key => $value ) {
				$_SESSION[$key] = $value;
			}

			echo send_json_res('0', '登录成功', $user);
		}elseif($user === null) {
			echo send_json_res('1', '网络错误', null);
		}else {
			echo send_json_res('2', '用户名或密码错误', null);
		}
	}

	// 登出
	function logout()
	{
		foreach( $_SESSION as $key=>$value )
		{
			unset( $_SESSION[$key] );
		}
		
		forward('?c=guest');
	}

	// 注册-公司邮箱
	function signup_staff()
	{
		$email = t(v('email'));
		$password = t(v('password'));
		$gender = intval(v('gender'));
		$com_email_prefix = t(v('com_email_prefix'));
		$com_email_suffix = t(v('com_email_suffix'));
		$company_name = t(v('company_name'));
		$company_id = intval(v('company_select'));
		$company_visible = intval(v('company_visible'));

		$params = array();
		$params['email'] = $email;
		$params['password'] = $password;
		$params['gender'] = $gender;
		$params['com_email_prefix'] = $com_email_prefix;
		$params['com_email_suffix'] = $com_email_suffix;
		$params['company_name'] = $company_name;
		$params['company_id'] = $company_id;
		$params['company_visible'] = $company_visible;

		if($content = send_request('api', 'add_user_staff' ,  $params )) {
			$data = json_decode( $content , 1 );
			if( ($data['code'] == 0) && is_array( $data['data'] ) ) {
				echo send_json_res('0', '注册成功', $data['data']);
			}else {
				echo send_json_res($data['code'], $data['msg'], null);
			}
		}else {
			echo send_json_res('1', '网络错误', null);
		}
	}

	// 注册-邀请码
	function signup_code()
	{
		$params = array();
		$params['email'] = t(v('email'));
		$params['password'] = t(v('password'));
		$params['gender'] = intval(v('gender'));
		$params['code'] = t(v('code'));

		if($content = send_request( 'api', 'add_user_code' ,  $params )) {
			$data = json_decode( $content , 1 );
			if( ($data['code'] == 0) && is_array( $data['data'] ) ) {
				echo send_json_res('0', '注册成功', $data['data']);
			}else {
				echo send_json_res($data['code'], $data['msg'], null);
			}
		}else {
			echo send_json_res('1', '网络错误', null);
		}
	}

	// 发送邮件测试
	function sendmail()
	{
        phpmailer_send_mail('yifei2@staff.sina.com.cn', '红豆派用户邮箱验证', '<p>hi, 红豆派欢迎您，请点击以下链接激活您的账号~</p><p><a style="color:#f00;font-size:20px;" target="_blank" href="http://redbeanpie.com">验证链接</a></p><p>如果您无法点击验证链接，请复制链接到浏览器中，谢谢~ http://redbeanpie.com</p>', 'yifei.li@redbeanpie.com', '红豆派(redbeanpie.com)', 'smtp.exmail.qq.com', 25, 'yifei.li@redbeanpie.com', 'jinbaoer2011');

	}
	
}