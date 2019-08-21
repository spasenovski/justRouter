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
     * @var JustRouter\Parser
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
     * @param RouteCollection $collection
     * @param Parser $parser
     */
    public function __construct(RouteCollection $collection, Parser $parser)
    {
        $this->routes = $collection;
        $this->parser = $parser;
    }

    /**
     * Matches the path string to one of the requests
     *
     * @param string $path The URI
     *
     * @throws \Exception
     *
     * @return array
     */
    public function matchRequest(string $path)
    {
        $routes = $this->routes->allRoutes();
        $parsedPath = $this->parser->parsePath($path);
        $pathSegments = substr_count($path, '/');

        if (!isset($routes[$pathSegments])) {
            return [self::NO_MATCH, [], '', ''];
        }

        $routes = $routes[$pathSegments];

        foreach ($routes as $key => $route) {
            $parsedRoute = $this->parser->parsePath($route->getPath());
            $segments = $parsedRoute['segments'];

            $isMatched = $this->matchSegments($segments, $parsedPath);

            if ($isMatched['isMatched']) {
                return [self::MATCH, $isMatched['variables'], $route->getController(), $route->getPath()];
            }
        }

        return [self::NO_MATCH, [], $route->getController(), $route->getPath()];
    }

    /**
     * Matches the path string to one of the requests
     *
     * @param array $segments The route segments.
     * @param array $parsedPath The parsed URI.
     *
     * @throws RouteException
     *
     * @return array
     */
    protected function matchSegments(array $segments, array $parsedPath)
    {
        $variablesInRoute = [];
        $isMatched = 1;

        foreach ($segments as $key => $segment) {
            if (Parser::isSegmentStatic($segment)) {
                if (!Parser::segmentsMatch($segment, $parsedPath['segments'][$key])) {
                    return ['isMatched' => 0, 'variables' => ''];
                }
            } else {
                $variable = $segment;

                if (is_numeric($variable)) {
                    throw new RouteException('Variable ' . $variable . ' cannot be a number');
                }

                if (Parser::isRegex($variable)) {
                    $parsedReg = Parser::parseVarRegex($variable);
                    $varRegex = '#' . $parsedReg['regex'] . '#';
                    $variable = $parsedReg['variable'];

                    $match = preg_match($varRegex, $parsedPath['segments'][$key]);
                    $isMatched = (!$match) ? 0 : $isMatched;
                }

                $variable = str_replace(['{', '}'], '', $variable);
                $variablesInRoute[$variable] = $parsedPath['segments'][$key];
            }
        }

        if ($isMatched) {
            return ['isMatched' => 1, 'variables' => $variablesInRoute];
        }

        return ['isMatched' => 0];
    }

}