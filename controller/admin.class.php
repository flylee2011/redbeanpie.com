<?php
if( !defined('IN') ) die('bad request');
include_once( CROOT . 'controller' . DS . 'core.class.php' );

class adminController extends coreController
{
	function __construct()
	{
		// 载入默认的
		parent::__construct();
		$this->check_login();
	}

	function check_login()
	{
		session_set_cookie_params( c('session_time') );
		@session_start();
		if(g('a') != 'guest' && !is_admin_login()) {
			return forward('?c=admin&a=guest');
		}
	}

	// 登录
	function login()
	{
		$email = z( t( v( 'email' ) ) );
		$password = md5(z( t( v( 'password' ) ) ));

		$sql = "SELECT * FROM `rbp_admin` WHERE `password` = '". s($password) ."' AND `username` = '". s($email) ."'";
		$line = get_line($sql);

		if(!$line) {
			// return ajax_echo( _('用户名密码错误') );
			die('用户名密码错误');
		}else {
			session_set_cookie_params( c('session_time') );

			@session_start();
            $_SESSION[ 'admin_uid' ] = $line[ 'id' ];
            $_SESSION[ 'admin_nickname' ] = $line[ 'nickname' ];
            $_SESSION[ 'admin_levelname' ] = $line[ 'levelname' ];
            $_SESSION[ 'admin_level' ] = $line[ 'level' ];

			forward('?c=admin&a=index');
		}
	}

	// 登出
	function logout()
	{
		unset( $_SESSION['admin_level'] );
		return forward('?c=admin&a=guest');
	}

	// 登录页面，默认入口
	function guest()
	{
		if(is_admin_login()) {
			return forward('?c=admin&a=index');
		}
		$data['title'] = 'RedBeanPie管理后台';
		render($data, 'admin', 'signin');
	}

	// 首页
	function index()
	{
		$data['title'] = 'RedBeanPie管理后台';
		render($data, 'admin', 'index');
	}
	
	// 用户管理，预注册
	function preuser() {
		
		$sql = 'SELECT `id`, `email`, `nickname`, `com_email_prefix`, `com_email_suffix`, `com_name`, `create_time` FROM `rbp_user` WHERE `password` = ""';
		$data['title'] = 'RedBeanPie管理后台';
		$data['userinfo_arr'] = get_data($sql);

		render($data, 'admin', 'index');
	}
	// 用户管理，注册用户
	function userinfo() {
		$data['title'] = 'RedBeanPie管理后台';
		render($data, 'admin', 'index');
	}

	// 公司管理，公司信息
	function cominfo() {
		$sql_com = 'SELECT `id`, `email_suffix`, `company_name`, `industry_id`, `is_active` FROM `rbp_company`';
		
		$data['cominfo_arr'] = get_data($sql_com);
		$data['title'] = 'RedBeanPie管理后台';
		render($data, 'admin', 'index');
	}
	// 公司管理，添加公司信息页面
	function add_cominfo() {
		$sql = 'SELECT `id`, `industry_name` FROM `rbp_industry`';

		$data['industryinfo_arr'] = get_data($sql);
		$data['title'] = 'RedBeanPie管理后台';
		render($data, 'admin', 'index');
	}
	// 公司管理，添加公司信息接口
	function add_cominfo_api() {
		$params['company_name'] = t(v('comname'));
		$params['com_email_suffix'] = t(v('com_email_suffix'));
		$params['industry_id'] = intval(v('industryid'));

		$dsql = array();
		$dsql[] = "'" . s( $params['company_name'] ) . "'";
		$dsql[] = "'" . s( $params['com_email_suffix'] ) . "'";
		$dsql[] = "'" . s( $params['industry_id'] ) . "'";

		$sql = "INSERT INTO `rbp_company`(`company_name`, `email_suffix`, `industry_id`) VALUES(" . join( ' , ' , $dsql ) . ")";
		
		$resobj = array();
		$resobj['info'] = '添加成功';
		$resobj['status'] = 'y';

		run_sql($sql);

		if(db_errno() != 0) {
			$resobj['info'] = db_errno();
			$resobj['status'] = 'n';
		}
		echo json_encode($resobj);
	}
	// 公司管理，编辑公司信息页面
	function edit_cominfo() {
		$comid = intval(v('com_id'));
		$industryid = intval(v('industry_id'));

		$sql_com = 'SELECT `id`, `email_suffix`, `company_name`, `industry_id` FROM `rbp_company` WHERE `id`=' . $comid;
		$sql_industry = 'SELECT `id`, `industry_name` FROM `rbp_industry`';
		
		$cominfo = get_line($sql_com);
		$industryinfo = get_data($sql_industry);

		$data['title'] = 'RedBeanPie管理后台';
		$data['cominfo'] = $cominfo;
		$data['industryinfo_arr'] = $industryinfo;
		render($data, 'admin', 'index');
	}
	// 公司管理，编辑公司信息接口
	function edit_cominfo_api() {
		$params['com_email_suffix'] = t(v('com_email_suffix'));
		$params['comname'] = t(v('comname'));
		$params['comid'] = intval(v('comid'));
		$params['industryid'] = intval(v('industryid'));

		$sql = 'UPDATE `rbp_company` SET `email_suffix`="'. $params['com_email_suffix'] .'", `company_name`="'. $params['comname'] .'", `industry_id`=' . $params['industryid'] . ' WHERE `id`=' . $params['comid'];
		
		$resobj = array();
		$resobj['info'] = '修改成功';
		$resobj['status'] = 'y';

		run_sql($sql);

		if(db_errno() != 0) {
			$resobj['info'] = db_errno();
			$resobj['status'] = 'n';
		}
		echo json_encode($resobj);
	}
	// 公司管理，更新公司信息是否开放接口
	function update_comactive_api() {
		$com_id = intval(v('com_id'));
		$is_active = intval(v('is_active'));
		$active = 0;

		if($is_active != 1) {
			$active = 1;
		}

		$sql = 'UPDATE `rbp_company` SET `is_active`='. $active .' WHERE `id`=' . $com_id;

		$resobj = array();
		$resobj['info'] = '修改成功';
		$resobj['status'] = 'y';

		run_sql($sql);

		if(db_errno() != 0) {
			$resobj['info'] = db_errno();
			$resobj['status'] = 'n';
		}
		echo json_encode($resobj);
	}
	
	// 公司管理，行业信息
	function industryinfo() {
		$sql = 'SELECT `id`, `industry_name` FROM `rbp_industry`';

		$data['industryinfo_arr'] = get_data($sql);
		$data['title'] = 'RedBeanPie管理后台';
		render($data, 'admin', 'index');
	}
	// 公司管理，添加行业信息页面
	function add_industryinfo() {
		$data['title'] = 'RedBeanPie管理后台';
		render($data, 'admin', 'index');
	}
	// 公司管理，行业信息检查
	function check_industryinfo() {	
		$params['industry_name'] = t(v('param'));

		$sql = "SELECT COUNT(*) FROM `rbp_industry` WHERE `industry_name` = '". $params['industry_name'] ."'";

		$resobj = array();
		$resobj['info'] = '验证成功';
		$resobj['status'] = 'y';

		if(get_var($sql) >= 1) {
			$resobj['info'] = '该行业已添加';
			$resobj['status'] = 'n';
		}

		echo json_encode($resobj);

	}
	// 公司管理，添加行业信息接口
	function add_industryinfo_api() {
		$params['industry_name'] = t(v('industryname'));

		$sql = "INSERT INTO `rbp_industry`(`industry_name`) VALUES ('". $params['industry_name'] ."')";

		$resobj = array();
		$resobj['info'] = '添加成功';
		$resobj['status'] = 'y';

		run_sql($sql);

		if(db_errno() != 0) {
			$resobj['info'] = db_errno();
			$resobj['status'] = 'n';
		}
		echo json_encode($resobj);

	}
	// 公司管理，编辑行业信息页面
	function edit_industryinfo() {
		$id = intval(v('id'));
		$sql = 'SELECT `industry_name` FROM `rbp_industry` WHERE `id` = ' . $id;
		$industryName = get_var($sql);

		$data['title'] = 'RedBeanPie管理后台';
		$data['industry_name'] = $industryName;
		$data['id'] = $id;
		render($data, 'admin', 'index');
	}
	// 公司管理，编辑行业信息接口
	function edit_industryinfo_api() {
		$params['industry_name'] = t(v('industryname'));
		$params['industry_id'] = intval(v('industryid'));

		$sql = "UPDATE `rbp_industry` SET `industry_name`='". $params['industry_name'] ."' WHERE `id`=". $params['industry_id'];

		$resobj = array();
		$resobj['info'] = '修改成功';
		$resobj['status'] = 'y';

		run_sql($sql);

		if(db_errno() != 0) {
			$resobj['info'] = db_errno();
			$resobj['status'] = 'n';
		}
		echo json_encode($resobj);

	}

	// 邀请码管理
	function codeinfo() {
		$sql = 'SELECT `id`, `code`, `is_active`, `create_time` FROM `rbp_codeinfo`';

		$data['title'] = 'RedBeanPie管理后台';
		$data['codeinfo_arr'] = get_data($sql);
		render($data, 'admin', 'index');
	}
	// 邀请码生成接口
	function add_codeinfo_api() {
		$count = intval(v('codecount'));
		$rescount = $count;
		$chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";

		$resobj = array();
		$resobj['info'] = '成功添加' . $count . '个邀请码';
		$resobj['status'] = 'y';

		for($i=0;$i<$count;$i++) {
			$code = "";
			for($j=0;$j<10;$j++) {
				$code .= $chars[mt_rand(0, strlen($chars)-1)];
			}
			$sql = 'INSERT INTO `rbp_codeinfo`(`code`) VALUES ("'. $code .'")';

			run_sql($sql);

			if(db_errno() != 0) {
				$rescount = $rescount - 1;
				$resobj['info'] = '成功添加' . $rescount . '个邀请码';
				$resobj['status'] = 'y';
				continue;
			}

		}	
		echo json_encode($resobj);
	}
	
	
}


?>