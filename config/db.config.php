<?php

if( defined('SAE_APPNAME') )
{
	$GLOBALS['config']['db']['db_host'] = SAE_MYSQL_HOST_M;
	$GLOBALS['config']['db']['db_host_read'] = SAE_MYSQL_HOST_S;
	
	$GLOBALS['config']['db']['db_port'] = SAE_MYSQL_PORT;
	
	$GLOBALS['config']['db']['db_user'] = SAE_MYSQL_USER;
	$GLOBALS['config']['db']['db_password'] = SAE_MYSQL_PASS;
	$GLOBALS['config']['db']['db_name'] = SAE_MYSQL_DB;
	
}
else
{
	$GLOBALS['config']['db']['db_host'] = '115.28.178.4';
	$GLOBALS['config']['db']['db_port'] = 3306;
	$GLOBALS['config']['db']['db_user'] = 'redbeanpie';
	$GLOBALS['config']['db']['db_password'] = 'redbeanpie2013';
	$GLOBALS['config']['db']['db_name'] = 'redbeanpie';

}
