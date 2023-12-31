<?php

require __DIR__ . "/constent.php";


require runController();

function runController()
{
    $uri    = detect_uri();

    if($uri == '/')
    {
        $uri = "wp-login.php";
    }

    if($uri == 'wp-admin')
    {
        $uri = 'wp-admin/index.php';
    }

	if(stripos($uri, 'wp-json') === 0)
	{
		$uri = "index.php";
	}


    $files  = require __DIR__ . "/wp-admin-files.php";

    if(! in_array($uri, $files))
    {
        throw new ErrorException( $uri . " not allow access");
    }


    chdir( dirname(__DIR__) . '/' . dirname($uri));

    return basename($uri);
}




function detect_uri()
{
	if ( ! isset($_SERVER['REQUEST_URI']) OR ! isset($_SERVER['SCRIPT_NAME']))
	{
		return '';
	}

	$uri = $_SERVER['REQUEST_URI'];
	if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0)
	{
		$uri = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
	}
	elseif (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0)
	{
		$uri = substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
	}

	// This section ensures that even on servers that require the URI to be in the query string (Nginx) a correct
	// URI is found, and also fixes the QUERY_STRING server var and $_GET array.
	if (strncmp($uri, '?/', 2) === 0)
	{
		$uri = substr($uri, 2);
	}
	$parts = preg_split('#\?#i', $uri, 2);
	$uri = $parts[0];
	if (isset($parts[1]))
	{
		$_SERVER['QUERY_STRING'] = $parts[1];
		parse_str($_SERVER['QUERY_STRING'], $_GET);
	}
	else
	{
		$_SERVER['QUERY_STRING'] = '';
		$_GET = array();
	}

	if ($uri == '/' || empty($uri))
	{
		return '/';
	}

	$uri = parse_url($uri, PHP_URL_PATH);

	// Do some final cleaning of the URI and return it
	return str_replace(array('//', '../'), '/', trim($uri, '/'));
}
