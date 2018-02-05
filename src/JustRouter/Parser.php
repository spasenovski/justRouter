<?php

namespace JustRouter;

/**
 * Takes an instance of Routing\Route object
 * Extracts the variables from placeholders
 */
class Parser
{
	/**
	 * Parse the current url into segments
	 *
	 * @param string
	 *
	 * @return array
	 */
	public function parsePath($path)
	{
		if(!is_string($path))
		{
			throw new \Exception('Path must be string');
		}

		return self::getSegments($path);
	}

	public static function isSegmentStatic($segment)
	{
		if(strpos($segment, '{') === false)
		{
			return true;
		}

		return false;
	}

	public static function isRegex($segment)
	{
		if(strpos($segment, ':') !== false && substr_count($segment, ':') == 1 )
		{
			return true;
		}

		return false;
	}

	public static function parseVarRegex($segment)
	{
		$parts    = explode(':', $segment);
		$variable = $parts[0];

    	if (isset($parts[1])) {
        	$parts = explode('}', $parts[1]);
        	return [
        		'regex' => $parts[0],
        		'variable' => ltrim($variable, '{	')];
    	}

    	return 0;
	}

	public static function segmentsMatch($first, $second)
	{
		if(strcmp($first, $second) === 0) {
			return true;
		}
		return false;
	}

	private static function getSegments($string)
	{
		if(strcmp($string, '/') == 0)
		{
			return [
				'segments' => [''],
				'segcount' => 1];
		}

		if($string[0] == '/')
		{
			$string = ltrim($string, '/');
		}

		$segments = explode('/', $string);
		$segcount = count($segments);

		return [
			'segments' => $segments,
			'segcount' => $segcount];
	}
}