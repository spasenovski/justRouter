<?php

namespace JustRouter;

class Matcher
{
	/**
		* @var RouteCollection
	*/
	private $routes;

	/**
		* @var string
	*/
	private $path;

	/**
	   * @var JustRoute\Parser
	*/
	private $parser;

	/**
	  * @var bool
	*/
	const MATCH = 1;

	/**
	  * @var bool
	*/
	const NO_MATCH = 0;

	/**
		* Constructor.
		*
		* @param RouteCollection $routes  					A RouteCollection instance
		* @param JustRoute\Parser          $parser
     */
	public function __construct(RouteCollection $collection, Parser $parser)
	{
		$this->routes = $collection;
		$this->parser = $parser;
	}

	/**
		* Matches the path string to one of the requests
		*
		* @param $path                     $string
		*
		* @returns array
     */
	public function matchRequest($path)
	{
		$return 	  = array();
		$routes 	  = $this->routes->allRoutes();
		$parsePath 	  = $this->parser->parsePath($path);
		$isMatched 	  = 1;
		$pathSegments = substr_count($path, '/');

		if(!isset($routes[$pathSegments]))
		{
			return [self::NO_MATCH, [], '', ''];
		}

		$routes = $routes[$pathSegments];

		foreach($routes as $key => $route) {
				$parsedRoute 	  = $this->parser->parsePath($route->getPath());
				$count       	  = $parsedRoute['segcount'];
				$segments    	  = $parsedRoute['segments'];
				$isMatched   	  = 1;
				$variablesInRoute = [];

				for($i = 0; $i < $count; $i++)
				{
					if(Parser::isSegmentStatic($segments[$i]))
					{
						if(!Parser::segmentsMatch($segments[$i], $parsePath['segments'][$i]))
						{
							$isMatched = 0;
						}
					}
					else
					{
						$variable = $segments[$i];

						if(is_numeric($variable))
						{
							throw new RouteException('Variable ' . $variable . ' cannot be a number');
						}

						if(Parser::isRegex($variable))
						{
							$parsedReg = Parser::parseVarRegex($variable);
							$varRegex  = '#' . $parsedReg['regex'] . '#';
							$variable  = $parsedReg['variable'];

							$match 	   = preg_match($varRegex, $parsePath['segments'][$i]);
							$isMatched = (!$match) ? 0 : $isMatched;
						}

						$variable 					 = str_replace(['{', '}'], '', $variable);
						$variablesInRoute[$variable] = $parsePath['segments'][$i];
					}
				}

				if($isMatched)
				{
					return [self::MATCH, $variablesInRoute, $route->getController(), $route->getPath()];
				}
		}

		return [self::NO_MATCH, [], $route->getController(), $route->getPath()];
	}

}