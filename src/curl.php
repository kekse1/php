<?php

/*
 * Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
 * https://kekse.biz/ https://github.com/kekse1/php/
 * v0.1.2
 *
 * Example for HTTP requests in PHP via the cURL Library.
 *
 */

//
namespace kekse;

//
const DEFAULT_TIMEOUT = 16;
const DEFAULT_FAMILY = 0;	// (ipv)[0,4,6];

//
if(!extension_loaded('curl'))
{
	die('No cURL module loaded/installed!');
}

function renderHeaders($_headers)
{
	if(array_is_list($_headers))
	{
		return $_headers;
	}
	
	$result = [];
	
	if(array_is_list($_headers))
	{
		$count = count($_headers);
		
		for($i = 0, $j = 0; $i < $count; ++$i)
		{
			if(is_string($_headers[$i]) && $_headers[$i] !== '')
			{
				$result[$j++] = $_headers[$i];
			}
		}
	}
	else foreach($_headers as $key => $value)
	{
		if(is_string($value))
		{
			$result[] = trim($key) . ': ' . trim($value);
		}
	}

	return $result;
}

function parseHeaders($_headers)
{
	if(!array_is_list($_headers))
	{
		return $_headers;
	}
	
	$count = count($_headers);
	$result = []; $item;

	for($i = 0; $i < $count; ++$i)
	{
		if(!is_string($_headers[$i]) || $_headers[$i] === '')
		{
			continue;
		}
		
		$item = explode(':', $_headers[$i], 2);
		
		if(count($item) < 2)
		{
			continue;
		}

		$item[0] = trim($item[0]);
		$item[1] = trim($item[1]);

		$result[$item[0]] = $item[1];
	}
	
	return $result;
}

function extractFromHeaders($_headers, $_subject)
{
	$_headers = parseHeaders($_headers);
	$_subject = strtolower($_subject);
	
	foreach($_headers as $key => $value)
	{
		if(strtolower($key) === $_subject)
		{
			return $value;
		}
	}
	
	return null;
}

function httpRequest($_url, $_method = 'GET', $_headers = null, $_data = null, $_timeout = DEFAULT_TIMEOUT, $_family = DEFAULT_FAMILY)
{
	if(!is_string($_method))
	{
		die('Invalid URL');
	}
	
	$_method = strtoupper($_method);
	$curl = curl_init();

	curl_setopt($curl, CURLOPT_URL, $_url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $_method);
	
	if($_method === 'POST')
	{
		if(is_array($_data))
		{
			$_data = json_encode($_data);
		}
		else if(!is_string($_data))
		{
			die('Invalid $_data argument (neither Array nor String)');
		}
		
		curl_setopt($curl, CURLOPT_POSTFIELDS, $_data);
	}
	
	if(is_array($_headers))
	{
		$_headers = renderHeaders($_headers);
		
		curl_setopt($curl, CURLOPT_HTTPHEADER, $_headers);

		$userAgent = extractFromHeaders($_headers, 'user-agent');
		
		if($userAgent !== null)
		{
			curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
		}
	}
	
	if(is_int($_family)) switch($_family)
	{
		case 4:
			curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
			break;
		case 6:
			curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V6);
			break;
	}
	
	if(is_int($_timeout) && $_timeout >= 0)
		curl_setopt($curl, CURLOPT_TIMEOUT, $_timeout);
	//curl_setopt($curl, CURLOPT_HEADER, true);
	
	//
	$response = curl_exec($curl);
	$error = curl_error($curl);
	
	curl_close($curl);
	
	//
	if($error)
	{
		die('Error in cURL request: ' . $error);
	}
	else if(curl_errno($curl))
	{
		die('Error in cURL request: (' . curl_errno($curl) . ') ' . curl_error($curl));
	}
	
	return $response;
}

//
