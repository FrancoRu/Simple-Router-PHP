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
    public function endpoint(string $method, string $path, callable ...$callback): void
    {
        if (!in_array($method, $this->methods, true)) $this->methodNotFound();

        $this->mapper[$method][] = [
            'path' => $path,
            'callback' => $callback
        ];
    }

    /**
     * Starts the router and executes the appropriate callback for the current request.
     * If the route or method is not found, it will return a customized 404.
     */
    public function start(): void
    {
        $path = URL;

        $method = $_SERVER['REQUEST_METHOD'];
        if (!in_array($method, $this->methods, true)) $this->methodNotFound();


        foreach ($this->mapper[$method] as $element) {
            if (strcmp($element['path'], $path) === 0) {
                foreach ($element['callback'] as $call) {
                    $call();
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
        echo '<h1>Pagina no encontrada revisa tu url</h1>';
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
