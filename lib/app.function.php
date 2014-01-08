<?php 
	
	function forward( $url )
	{
		header( "Location: " . $url );
	}

	function jsforword( $url )
	{
		return '<script>location="' . $url . '"</script>';
	}

	// 后台管理员是否登录
	function is_admin_login()
	{
		// return true;
		return $_SESSION['admin_level'] == 1;
	}

	// 用户是否登录
	function is_login()
	{
		return $_SESSION['level'] > 0;
	}

	// 用户邮箱是否激活
	function is_email_active()
	{
		return $_SESSION['email_active'] === 1;
	}

	// 用户信息是否完善
	function is_info_complete()
	{
		return $_SESSION['info_complete'] === 1;
	}

	// 是否是登录用户查看自己的个人主页
	function is_login_profile()
	{
		return intval(v('uid')) === intval($_SESSION['uid']);
	}

	// 通用接口向前端返回json数据
	function send_json_res($code, $msg, $data)
	{
		$obj = array();
		$obj[ 'code' ] = $code;
		$obj[ 'msg' ] = $msg;
		$obj[ 'data' ] = $data;

		return json_encode( $obj );
	}

	// 请求api controller的方法
	function send_request( $action , $param , $token = null )
	{
		require_once( AROOT . 'controller' . DS . 'api.class.php' );
		require_once( AROOT . 'model' . DS . 'api.function.php' );
		$GLOBALS['API_EMBED_MODE'] = 1;

		// local request
		$bake_request = $_REQUEST;
		$_REQUEST['c'] = 'api';
		// $GLOBALS['a'] = $_REQUEST['a'] = $action;
		if( $token !== null )
			$_REQUEST['token'] = $token;

		if( (is_array( $param )) && (count($param) > 0) )
			foreach( $param as $key => $value )
			{
				$_REQUEST[$key] =  $value ;
			}


		$api = new apiController();
		// magic call
		if( method_exists($api, $action) || has_hook('API_'.$action) ) {
			$content = $api->$action();
			$_REQUEST = $bake_request;
			// $GLOBALS['a'] = $_REQUEST['a'];

			return $content;
			//if($data = json_decode( $content , 1 ))
			//return json_encode($data['data']);
		}else {
			return 'API_'.$action . ' NOT EXISTS';
		}

		return null;
	}

	function phpmailer_send_mail(  $to , $subject , $body , $from , $from_name,  $host , $port , $user , $password )
	{
	    if( !isset( $GLOBALS['LP_MAILER'] ) )
	    {
	        include_once( AROOT . 'lib' . DS . 'class.phpmailer.php' );
	        $GLOBALS['LP_MAILER'] = new PHPMailer();
	    }

	    $mail = $GLOBALS['LP_MAILER'];
	    $mail->CharSet = 'UTF-8';
	    $mail->Encoding = 'base64';
	    // $mail->SMTPDebug = 1;
	    $mail->IsSMTP(); 
	    $mail->Host = $host;
	    $mail->SMTPAuth = true;
	    $mail->Port = $port;
	    $mail->Username = $user;
	    $mail->Password = $password;
	    $mail->From = $from;
	    $mail->FromName = $from_name;
	    $mail->Subject = $subject ;
	    $mail->WordWrap = 50;
	    $mail->MsgHTML($body);
	    $mail->AddAddress( $to );

	    if(!$mail->Send())
	    {
	        $GLOBALS['MAILER_ERROR'] = $mail->ErrorInfo;
	        // echo $mail->ErrorInfo;
	        return false;
	    }
	    else
	    {
	        $mail->ClearAddresses();
	        // echo 'success';
	        return true;
	    }
	}

	// 创建支持多级目录
	function  mkpath( $mkpath , $mode =0777){
		$path_arr = explode ( '/' , $mkpath );

		foreach($path_arr as $value) {
			if (!empty( $value )) {
				if ( empty  ( $path )) {
					$path = $value;
				}else {
					$path .= '/' . $value ;
				}
				is_dir ( $path )  or   mkdir ( $path , $mode );
			}
		}

		if (is_dir( $mkpath )) {
			return true;
		}
		return false;
	}

	// 计算年龄
	function cal_age($birth)
	{
		// $birth='0000-00-00';
		if($birth ===  '0000-00-00') {
			return '';
		}
		list($by,$bm,$bd) = explode('-',$birth);
		$cm = date('n');
		$cd = date('j');
		$age = date('Y') - $by -1;
		if ($cm>$bm || $cm==$bm && $cd>$bd) $age++;
		return $age;
	}

	/***************************************************************/
	// 登录
	function login( $email , $password )
	{
		$params = array();
		$params['email'] = $email;
		$params['password'] = $password;

		if($content = send_request( 'get_user_token' ,  $params )) {
			$data = json_decode( $content , 1 );
			if( ($data['code'] == 0) && is_array( $data['data'] ) ) {
				return $data['data'];
			}else {
				return false;
			}
		}else {
			return null;
		}
	}



?>