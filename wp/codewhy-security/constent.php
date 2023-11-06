<?php





$http = (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS']=='on') ? 'https://' : 'http://';

if(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) and $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
{
	$http = 'https://';
	$_SERVER['HTTPS'] = 'on';
}


$home =   $http . $_SERVER['HTTP_HOST']; #获取当前访问的域名
$siteurl = $http  . $_SERVER['HTTP_HOST'];


define('DISABLE_WP_CRON', true);

define('WP_HOME', $home);
define('WP_SITEURL', $siteurl);


