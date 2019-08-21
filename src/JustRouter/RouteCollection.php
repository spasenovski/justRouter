<?php

namespace JustRouter;

/**
 * A collection of routes
 */
class RouteCollection
{
    /**
     * @var array
     */
    public $routes = array();

    /**
     * Adds a route.
     *
     * @param string $name  The route name
     * @param Route  $route A Route instance
     */
    public function addRoute(array $httpMethods, $path, $controller, $name = '')
    {
        $numberSegments                  = substr_count($path, '/');
    	$this->routes[$numberSegments][] = new Route($httpMethods, $path, $controller, $name);
    }

    /**
     * Adds a GET route.
     *
     * @param string $name  The route name
     * @param Route  $route A Route instance
     */
    public function get($path, $controller, $name = '')
    {
        $this->addRoute(['GET'], $path, $controller, $name);
    }

    /**
     * Adds a POST route.
     *
     * @param string $name  The route name
     * @param Route  $route A Route instance
     */
    public function post($path, $controller, $name = '')
    {
        $this->addRoute(['POST'], $path, $controller, $name);
    }

    /**
     * Adds a PUT route.
     *
     * @param string $name  The route name
     * @param Route  $route A Route instance
     */
    public function put($path, $controller, $name = '')
    {
        $this->addRoute(['PUT'], $path, $controller, $name);
    }

    /**
     * Adds a Patch route.
     *
     * @param string $name  The route name
     * @param Route  $route A Route instance
     */
    public function patch($path, $controller, $name = '')
    {
        $this->addRoute(['PATCH'], $path, $controller, $name);
    }

    /**
     * Adds a DELETE route.
     *
     * @param string $name  The route name
     * @param Route  $route A Route instance
     */
    public function delete($path, $controller, $name = '')
    {
        $this->addRoute(['DELETE'], $path, $controller, $name);
    }

    /**
     * Gets a route by name.
     *
     * @param string $name The route name
     *
     * @return Route|null A Route instance or null when not found
     */
    public function getRoute($name)
    {
        return isset($this->routes[$name]) ? $this->routes[$name]['route'] : null;
    }

    /**
     * Removes a route or an array of routes by name from the collection.
     *
     * @param string|array $name The route name or an array of route names
     */
    public function removeRoute($name)
    {
        foreach ((array) $name as $n) {
            unset($this->routes[$n]);
        }
    }

    /**
     * Returns all routes in this collection.
     *
     * @return Route[] An array of routes
     */
    public function allRoutes()
    {
        return $this->routes;
    }

    /**
     * Gets the number of Routes in this collection.
     *
     * @return int The number of routes
     */
    public function countRoutes()
    {
        return count($this->routes);
    }
}