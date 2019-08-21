<?php

namespace JustRouter;

/**
 * A single route
 */
class Route
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var array
     */
    private $httpMethods = array();

    /**
     * @var controller
     */
    private $controller;

    /**
     * Constructor
     *
     * @param array
     * @param string
     * @param string
     */
    public function __construct(array $httpMethods, $path, $controller)
    {
        $this->path = $path;
        $this->httpMethods = $httpMethods;
        $this->controller = $controller;
    }

    /**
     * transforms it into an array
     *
     * @return array
     */
    public function toArray()
    {
        return array('path' => $this->path,
            'methods' => $this->httpMethods,
            'controller' => $this->controller
        );
    }

    /**
     * get the path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * get the http methods allowed
     *
     * @return array
     */
    public function getHttpMethods()
    {
        return $this->httpMethods;
    }

    public function getController()
    {
        return $this->controller;
    }
}