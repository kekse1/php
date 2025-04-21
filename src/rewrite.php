<?php

//
// Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
// https://kekse.biz/ https://github.com/kekse1/*******/
// v0.4.0
//
//mit erklaerung: ich wollte das selbst sauber loesen,
//ohne integrierte php-funktionalitaet. grund: meine
//website und anderes interpretieren den query-string
//selbst.. da brauch ich 'ne "saubere" loesung quasi..
//bspw. koennen bei mir auch verteilt mehrere '?' auf-
//tauchen.. und manche params duerfen nicht mit '=' enden,
//und sowas halt. ^_^
//

//
function removeEnding($_haystack, $_needle)
{
	if(str_ends_with($_haystack, $_needle))
	{
		return substr($_haystack, 0, strlen($_haystack) - strlen($_needle));
	}
	
	return $_haystack;
}

function removeStarting($_haystack, $_needle)
{
	if(str_starts_with($_haystack, $_needle))
	{
		return substr($_haystack, strlen($_needle));
	}
	
	return $_haystack;
}

//
function cleanQuery($_string)
{
	while(str_contains($_string, '??'))
	{
		$_string = str_replace('??', '?', $_string);
	}

	while(str_contains($_string, '&&'))
	{
		$_string = str_replace('&&', '&', $_string);
	}

	$_string = ltrim($_string, '&?');
	$_string = rtrim($_string, '&?');

	return $_string;
}

function removeParam($_string, $_param)
{
	$_param = ltrim($_param, '?&');
	$_string = '&' . ltrim($_string, '?');

	do
	{
		$start = strpos($_string, '&' . $_param . '=');

		if($start === false)
		{
			$start = strpos($_string, '&' . $_param . '&');

			if($start === false)
			{
				break;
			}
		}

		$stop1 = strpos($_string, '&', $start + 1);
		$stop2 = strpos($_string, '?', $start + 1);

		if($stop1 === false && $stop2 === false)
		{
			$_string = substr($_string, 0, $start);
			break;
		}
		else if($stop1 === false)
		{
			$stop = $stop2;
		}
		else if($stop2 === false)
		{
			$stop = $stop1;
		}
		else if($stop1 < $stop2)
		{
			$stop = $stop1;
		}
		else
		{
			$stop = $stop2;
		}
		
		$_string = substr($_string, 0, $start) . substr($_string, $stop);
	}
	while(true);

	return cleanQuery(removeEnding($_string, '&' . $_param));
}

//
function removeClicks($_query = null, $_status = false)
{
	if(!is_string($_query))
	{
		$_query = $_SERVER['QUERY_STRING'];
	}
	
	$result = cleanQuery($_query);
	$original = $result;

	$result = removeParam($result, 'fbclid');
	$result = removeParam($result, 'gclid');

	if(!$_status)
	{
		return $result;
	}

	return [ $result, ($original !== $result) ];
}

function removeSubDomains($_host = null, $_count = 2, $_status = false)
{
	if(!is_string($_host))
	{
		$_host = $_SERVER['HTTP_HOST'];
	}
	
	$original = $_host;
	$_host = explode('.', $_host);
	$result = [];
	
	for($i = count($_host) - 1; $i >= 0; --$i)
	{
		if(strlen($_host[$i]) > 0)
		{
			array_unshift($result, $_host[$i]);

			if(count($result) >= $_count)
			{
				break;
			}
		}
	}
	
	$result = implode('.', $result);

	if(strlen($result) === 0)
	{
		return null;
	}
	
	if(!$_status)
	{
		return $result;
	}
	
	return [ $result, $result !== $original ];
}

function isIP($_hostname)
{
	if(str_contains($_hostname, ':'))
	{
		if($_hostname[0] === '[' && $_hostname[strlen($_hostname) - 1] === ']')
		{
			return true;
		}
	}

	$_hostname = explode('.', $_hostname);
	$len = count($_hostname);

	for($i = 0; $i < $len; ++$i)
	{
		if(!is_numeric($_hostname[$i]))
		{
			return false;
		}
	}

	return true;
}

//
function rewrite($_code = 307)
{
	//
	if(!is_int($_code))
	{
		$_code = 307;
	}

	//
	$HTTPS = true;
	$HOST = strtolower($_SERVER['HTTP_HOST']);

	if($HOST === 'localhost')
	{
		$HTTPS = false;
	}
	else if(isIP($HOST))
	{
		$HTTPS = false;
	}

	$REWRITE = false;

	$ORIGINAL_QUERY = $_SERVER['QUERY_STRING'];
	$ORIGINAL_HOST = $_SERVER['HTTP_HOST'];

	$HOST = removeSubDomains($ORIGINAL_HOST, 2, false);
	$QUERY = removeClicks($ORIGINAL_QUERY, false);

	if($HTTPS && $_SERVER['HTTPS'] !== 'on')
	{
		$REWRITE = true;
	}
	else if($ORIGINAL_HOST !== $HOST)
	{
		$REWRITE = true;
	}
	else if($ORIGINAL_QUERY !== $QUERY)
	{
		$REWRITE = true;
	}

	if($REWRITE)
	{
		$PATH = str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
		$LOCATION = 'http' . ($HTTPS ? 's' : '') . '://' . $HOST . $PATH . '?' . $QUERY;
		header('Location: ' . $LOCATION, true, $_code);
		exit;
	}
}

?>
