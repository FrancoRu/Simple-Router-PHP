<?php

namespace App;

/**
 * Class Router
 * 
 * This class implements the functions necessary for routing.
 * 
 * @package App
 */
class Router
{
    /**
     * @var array $methods Supported HTTP methods.
     */
    private array $methods;

    /**
     * @var array $mapper Map of routes to callback functions.
     */
    private array $mapper;

    /**
     * @var bool $isGrouping Defines when routes should be grouped.
     */
    private bool $isGrouping;

    /**
     * @var array $options An array of options for the group, including:
     *   - "path": The common prefix path for all routes within this group. Defaults to an empty string.
     *   - "middleware": An array of middleware functions to be applied to all routes within this group. Defaults to an empty array.
     */
    private array $options;

    /**
     * Constructor initializes the Router class.
     */
    public function __construct()
    {
        $this->methods = ['GET', 'PUT', 'POST', 'DELETE'];

        foreach ($this->methods as $element) {
            $this->mapper[$element] = [];
        }
    }

    /**
     * Defines an endpoint for a given HTTP method and path.
     *
     * @param string $method The HTTP method.
     * @param string $path The path to match.
     * @param callable $callback The callback function to execute when the route is matched.
     * @return void Always, but when no errors are found, if an error is encountered, print a message via console.log.
     */
    private function endpoint(string $method, string $path, callable ...$callback): void
    {
        if (!in_array($method, $this->methods, true))
            $this->methodNotFound();

        if ($this->isGrouping) {
            $callback = array_merge($this->options["middleware"], $callback);
            $path = $this->concatenatePaths($this->options["middleware"], $path);
        }

        $this->mapper[$method][] = [
            'path' => $path,
            'callback' => $callback
        ];
    }

    /**
     * Concatenates multiple paths into a single path.
     *
     * @param string ...$paths The paths to concatenate.
     * @return string The concatenated path.
     */
    private function concatenatePaths(string ...$paths)
    {
        $pathSegments = array_merge(
            ...array_map(function ($path) {
                return explode("/", $path);
            }, $paths)
        );
        $sanitizedPathSegments = array_filter($pathSegments, function ($segment) {
            return $segment;
        });

        $fullPath = "/" . implode("/", $sanitizedPathSegments);

        return $fullPath;
    }

    /**
     * Defines a group of routes with common prefix path or middlewares.
     *
     * @param array $options An array of options for the group, including:
     *   - "path": The common prefix path for all routes within this group. Defaults to an empty string.
     *   - "middleware": An array of middleware functions to be applied to all routes within this group. Defaults to an empty array.
     * @param callable $callback A callback function where routes specific to this group are defined.
     * @return void
     */
    public function group(array $options, callable $callback): void
    {
        if (!isset($options["path"]))
            $options["path"] = "";

        if (!isset($options["middleware"]))
            $options["middleware"] = [];

        $this->options = $options;

        $this->isGrouping = true;
        $callback($this);
        $this->isGrouping = false;
    }

    /**
     * Define a route for GET requests.
     *
     * @param string $path The URL path for the GET route.
     * @param callable ...$callback One or more callback functions to execute when the GET route is matched.
     * @return void
     */
    public function get(string $path, callable ...$callback): void
    {
        $this->endpoint('GET', $path, ...$callback);
    }

    /**
     * Define a route for POST requests.
     *
     * @param string $path The URL path for the POST route.
     * @param callable ...$callback One or more callback functions to execute when the POST route is matched.
     * @return void
     */
    public function post(string $path, callable ...$callback): void
    {
        $this->endpoint('POST', $path, ...$callback);
    }

    /**
     * Define a route for PUT requests.
     *
     * @param string $path The URL path for the PUT route.
     * @param callable ...$callback One or more callback functions to execute when the PUT route is matched.
     * @return void
     */
    public function put(string $path, callable ...$callback): void
    {
        $this->endpoint('PUT', $path, ...$callback);
    }

    /**
     * Define a route for DELETE requests.
     *
     * @param string $path The URL path for the DELETE route.
     * @param callable ...$callback One or more callback functions to execute when the DELETE route is matched.
     * @return void
     */
    public function delete(string $path, callable ...$callback): void
    {
        $this->endpoint('DELETE', $path, ...$callback);
    }

    /**
     * Starts the router and executes the appropriate callback for the current request.
     * If the route or method is not found, it will return a customized 404.
     */
    public function start(): void
    {
        $path = URL;

        $method = $_SERVER['REQUEST_METHOD'];
        if (!in_array($method, $this->methods, true))
            $this->methodNotFound();


        foreach ($this->mapper[$method] as $element) {
            if (strcmp($element['path'], $path) === 0) {
                foreach ($element['callback'] as $call) {
                    if (is_callable($call)) {
                        call_user_func($call);
                    }
                }
                exit;
            }
        }

        $this->badrequest();
    }

    /**
     * Handles a bad request (404).
     */
    private function badrequest(): void
    {
        echo '<h1>Page not found</h1>';
    }

    /*
     *  Print to the console.
     */
    private function printConsole(string $message): void
    {
        echo '<script>console.log(' . $message . ')</script>';
        exit;
    }

    /*
     *  Print to the console when an unsupported method is encountered.
     */
    private function methodNotFound()
    {
        $message = "method not supported, only the following methods are accepted: " . implode(", ", $this->methods);
        $this->printConsole($message);
    }
}
