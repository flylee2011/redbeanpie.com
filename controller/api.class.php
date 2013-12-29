<?php
/**
 * @fileoverview api接口
 * @authors yifei (flylee.bjfu@gmail.com)
 * @date    2013-11-13 23:32:08
 */

if( !defined('IN') ) die('bad request');
include_once( AROOT . 'controller'.DS.'app.class.php' );

define( 'LR_API_TOKEN_ERROR' , 10001 );
define( 'LR_API_USER_ERROR' , 10002 );
define( 'LR_API_DB_ERROR' , 10004 );
define( 'LR_API_NOT_IMPLEMENT_YET' , 10005 );
define( 'LR_API_ARGS_ERROR' , 10006 );
define( 'LR_API_DB_EMPTY_RESULT' , 10007 );
define( 'LR_API_USER_CLOSED' , 10008 );
define( 'LR_API_FORBIDDEN' , 10009 );
define( 'LR_API_UPGRADE_ERROR' , 10010 );
define( 'LR_API_UPGRADE_ABORT' , 10011 );

// 操作成功
define('API_SUCCESS_CODE', 'S00001');
// 数据库操作失败
define('API_DB_ERROR_CODE' , 'E00001');
// 用户名或密码错误
define('API_TOKEN_ERROR_CODE' , 'E10001');

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
		$params['gender'] = intval(v('gender'));
		$params['email'] = v('email');
		$params['com_email_suffix'] = v('com_email_suffix');
		$params['com_email_prefix'] = v('com_email_prefix');
		$params['com_email_id'] = intval(v('com_email_id'));
		$params['com_name'] = v('com_name');

		$dsql = array();
		
		$dsql[] = "'" . s( $params['username'] ) . "'";
		$dsql[] = "'" . s( $params['nickname'] ) . "'";
		$dsql[] = "'" . s( $params['gender'] ) . "'";
		$dsql[] = "'" . s( $params['email'] ) . "'";
		$dsql[] = "'" . s( $params['com_email_suffix'] ) . "'";
		$dsql[] = "'" . s( $params['com_email_prefix'] ) . "'";
		$dsql[] = "'" . s( $params['com_email_id'] ) . "'";
		$dsql[] = "'" . s( $params['com_name'] ) . "'";

		$sql = "INSERT INTO `rbp_userinfo` ( `username` , `nickname` , `gender`, `email` , `com_email_suffix` , `com_email_prefix`, `com_email_id`, `com_name` ) VALUES ( " . join( ' , ' , $dsql ) . " )";

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

		}else if($params['name'] === 'default-comemail') {
			
			$sql = "SELECT COUNT(*) FROM `rbp_userinfo` WHERE `com_email_suffix` = '" . $params['com_email_suffix'] . "' AND `com_email_prefix` = '" . $params['param'] . "'";

		}else if($params['name'] === 'other-comemail') {
			
			$email_split = explode('@', $params['param']);
			$sql = "SELECT COUNT(*) FROM `rbp_userinfo` WHERE `com_email_suffix` = '" . $email_split[1] . "' AND `com_email_prefix` = '" . $email_split[0] . "'";

		}

		if(get_var($sql) >= 1) {
			$resobj['info'] = '该邮箱已预定';
			$resobj['status'] = 'n';
		}

		echo json_encode($resobj);
	}

	public function get_cominfo() {
		$params = array();
		$params['comid'] = intval(v('comid'));

		$sql = 'SELECT `email_suffix` FROM `rbp_comemail` WHERE `id` = "'. $params['comid'] .'"';

		$resobj = array();
		$resobj['code'] = '0';
		$resobj['msg'] = 'success';
		$resobj['data'] = '';

		$resobj['data'] = get_line($sql);

		echo json_encode($resobj);
	}

	/**
     * 登录，通过email和密码获取token
     *
     * @param string email
     * @param string password
     * @return token array
     */
    public function get_user_token()
    {
        $email = z( t( v( 'email' ) ) );
        $password = z( t( v( 'password' ) ) );
        
		if( $user = get_full_info_by_email_password( $email , $password ) ) {
			session_set_cookie_params( c('session_time') );
			@session_start();
            $token = session_id();
            $_SESSION[ 'token' ] = $token;
            $_SESSION[ 'uid' ] = $user[ 'id' ];
            $_SESSION[ 'username' ] = $user['username'];
            $_SESSION[ 'email' ] = $user[ 'email' ];
            $_SESSION[ 'nickname' ] = $user[ 'nickname' ];
            $_SESSION[ 'email_active' ] = intval($user[ 'email_active' ]);
            $_SESSION[ 'info_complete' ] = intval($user[ 'info_complete' ]);
			$_SESSION[ 'level' ] = intval($user['level']);
			if($user['invitation_code']) {
				$_SESSION['user_type'] = 2;
			}else {
				$_SESSION['user_type'] = 1;
			}
			
			return self::send_result( $_SESSION );
        }else {
            return self::send_error( API_TOKEN_ERROR_CODE , '用户名或密码错误' );
        }
    }

    /**
     * 用户注册-公司邮箱
     *
     * @param string email
     * @param string password
     * @return user array
     */
    public function add_user_staff()
    {
    	$dsql = array();
		
		$dsql[] = "'" . s( v( 'email' ) ) . "'";
		$dsql[] = "'" . s( v( 'email' ) ) . "'";
		$dsql[] = "'" . s( md5( v( 'password' ) ) ) . "'";
		$dsql[] = "'" . s( v( 'gender' ) ) . "'";
        $dsql[] = "'" . s( v( 'com_email_prefix' ) ) . "'";
        $dsql[] = "'" . s( v( 'com_email_suffix' ) ) . "'";
        $dsql[] = "'" . s( v( 'company_name' ) ) . "'";
        $dsql[] = "'" . s( v( 'company_id' ) ) . "'";
        $dsql[] = "'" . s( v( 'company_visible' ) ) . "'";
        // $dsql[] = "'" . s( date( "Y-m-d H:i:s" ) ) . "'";
        
		$sql = "INSERT INTO `rbp_userinfo` ( `username` , `email` , `password` , `gender`, `com_email_prefix`, `com_email_suffix`, `com_name`, `com_email_id`, `company_visible` ) VALUES ( " . join( ' , ' , $dsql ) . " )";
		
        run_sql( $sql );
        
        if( db_errno() != 0 )
        {
            return self::send_error( API_DB_ERROR_CODE, '数据库错误' );
        }

        $data = array('');
		session_set_cookie_params( c('session_time') );
		@session_start();
		$com_email = v('com_email_prefix') . '@' . v('com_email_suffix');
		$_SESSION['com_email'] = $com_email;
		$_SESSION['email'] = v('email');
		$_SESSION['user_type'] = 1;

		$encode_email = base64_encode($com_email);
		$to_email = $com_email;
		$mail_subject = 'RedBeanPie激活邮箱';
		$link = c('site_domain') . '/?c=guest&a=signup_activeemail&type=1&email=' . $encode_email;
		$body = '<p>hi, 红豆派欢迎您，请点击以下链接激活您的账号~</p>'
				. '<p><a style="color:#f00;font-size:20px;" target="_blank" href="'. $link .'">验证链接</a></p>'
				. '<p>如果您无法点击验证链接，请复制链接到浏览器中，谢谢~ '. $link .'</p>';
		$from_email = c('sendmail_email');
		$from_email_name = '红豆派(redbeanpie.com)';
		$from_email_pwd = c('sendmail_pwd');
		$smtp_host = c('sendmail_host');

		phpmailer_send_mail($to_email, $mail_subject, $body, $from_email, $from_email_name, $smtp_host, 25, $from_email, $from_email_pwd);

        return self::send_result( $data );
    }

	/**
	 * 用户注册-邀请码
	 *
	 * @param string email
	 * @param string password
	 * @return user array
	 */
	public function add_user_code()
    {
		$dsql = array();

		$dsql[] = "'" . s( v( 'email' ) ) . "'";
		$dsql[] = "'" . s( v( 'email' ) ) . "'";
		$dsql[] = "'" . s( md5( v( 'password' ) ) ) . "'";
		$dsql[] = "'" . s( v( 'gender' ) ) . "'";
		$dsql[] = "'" . s( v( 'code' ) ) . "'";

		$sql = "INSERT INTO `rbp_userinfo` ( `username` , `email` , `password` , `gender`, `invitation_code` ) VALUES ( " . join( ' , ' , $dsql ) . " )";

		run_sql( $sql );

		if( db_errno() != 0 )
		{
		    return self::send_error( API_DB_ERROR_CODE, '数据库错误' );
		}else {
			$sql_code = "UPDATE `rbp_codeinfo` SET `is_active`=0 WHERE `code`='" . v('code') ."'";
			run_sql($sql_code);

			$data = array('');
			session_set_cookie_params( c('session_time') );
			@session_start();
			$_SESSION['email'] = v('email');
			$_SESSION['user_type'] = 2;

			$encode_email = base64_encode(v('email'));
			$to_email = v('email');
			$mail_subject = '红豆派用户邮箱验证';
			$link = c('site_domain') . '/?c=guest&a=signup_activeemail&type=2&email=' . $encode_email;
			$body = '<p>hi, 红豆派欢迎您，请点击以下链接激活您的账号~</p>'
					. '<p><a style="color:#f00;font-size:20px;" target="_blank" href="'. $link .'">验证链接</a></p>'
					. '<p>如果您无法点击验证链接，请复制链接到浏览器中，谢谢~ '. $link .'</p>';
			$from_email = c('sendmail_email');
			$from_email_name = '红豆派(redbeanpie.com)';
			$from_email_pwd = c('sendmail_pwd');
			$smtp_host = c('sendmail_host');

			phpmailer_send_mail($to_email, $mail_subject, $body, $from_email, $from_email_name, $smtp_host, 25, $from_email, $from_email_pwd);

			return self::send_result( $data );
		}

    }


	/**
	 * 获取全部公司信息
	 *
	 * @return cominfo array
	 */
	public function get_full_cominfo()
	{
		$cominfo_arr = get_full_cominfo();
		return self::send_result($cominfo_arr);
	}

	/**
	 * 激活邮箱
	 *
	 * @return status
	 */
	public function update_email_status()
	{
		$email = t(v('email'));
		$type = intval(v('type'));

		if(update_email_status($email, $type)) {
			$data['status'] = 'success';
			return self::send_result($data);
		}else {
			return self::send_error(API_DB_ERROR_CODE, '数据库操作失败');
		}
	}

	public function upload_file()
	{		
		$post_input = 'php://input';
		$save_path = dirname( __FILE__ );
		$postdata = file_get_contents( $post_input );

		if ( isset( $postdata ) && strlen( $postdata ) > 0 ) {
			$filename = AROOT . 'upload/' . uniqid() . '.jpg';
			$handle = fopen( $filename, 'w+' );
			fwrite( $handle, $postdata );
			fclose( $handle );
			if ( is_file( $filename ) ) {
				echo 'Image data save successed,file:' . $filename;
				exit ();
			}else {
				// die ( 'Image upload error!' );
				echo 'Image upload error!';
			}
		}else {
			// die ( 'Image data not detected!' );
			echo 'Image data not detected!';
		}
	}

	/**
	 * Note:for multipart/form-data upload
	 * 这个是标准表单上传PHP文件
	 * Please be amended accordingly based on the actual situation
	 */
	public function upload_file_form()
	{

		if (!$_FILES['Filedata']) {
			die ( 'Image data not detected!' );
		}
		if ($_FILES['Filedata']['error'] > 0) {
			switch ($_FILES ['Filedata'] ['error']) {
				case 1 :
					$error_log = 'The file is bigger than this PHP installation allows';
					break;
				case 2 :
					$error_log = 'The file is bigger than this form allows';
					break;
				case 3 :
					$error_log = 'Only part of the file was uploaded';
					break;
				case 4 :
					$error_log = 'No file was uploaded';
					break;
				default :
					break;
			}
			die ( 'upload error:' . $error_log );
		} else {
			$img_data = $_FILES['Filedata']['tmp_name'];
			$size = getimagesize($img_data);
			$file_type = $size['mime'];
			if (!in_array($file_type, array('image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/gif'))) {
				$error_log = 'only allow jpg,png,gif';
				die ( 'upload error:' . $error_log );
			}
			switch($file_type) {
				case 'image/jpg' :
				case 'image/jpeg' :
				case 'image/pjpeg' :
					$extension = 'jpg';
					break;
				case 'image/png' :
					$extension = 'png';
					break;
				case 'image/gif' :
					$extension = 'gif';
					break;
			}	
		}
		if (!is_file($img_data)) {
			die ( 'Image upload error!' );
		}
		//图片保存路径,默认保存在该代码所在目录(可根据实际需求修改保存路径)
		$save_path = dirname( __FILE__ );
		$uinqid = uniqid();
		$filename = $save_path . '/' . $uinqid . '.' . $extension;
		$result = move_uploaded_file( $img_data, $filename );
		if ( ! $result || ! is_file( $filename ) ) {
			die ( 'Image upload error!' );
		}
		echo 'Image data save successed,file:' . $filename;
		exit ();
	}


	/**
	 * 返回成功信息（json）
	 */
	public static function send_result( $data )
	{   
		$obj = array();
		$obj[ 'code' ] = '0';
		$obj[ 'msg' ] = 'success';
		$obj[ 'data' ] = $data;

		return json_encode( $obj );
	}

	/**
	 * 返回错误信息（json）
	 */
	public static function send_error( $code , $msg )
	{
		$obj = array();
		$obj[ 'code' ] = $code;
		$obj[ 'msg' ] = $msg;

		return json_encode( $obj );
	}

}

?>