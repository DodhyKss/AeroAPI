<?php

namespace Core;

class Route
{
    /**
     * @param string $path
     * @param callable|array|string $callback
     * @return RouteAction
     */
    public static function get(string $path, $callback)
    {
        return Application::$app->router->get($path, $callback);
    }

    /**
     * @param string $path
     * @param callable|array|string $callback
     * @return RouteAction
     */
    public static function post(string $path, $callback)
    {
        return Application::$app->router->post($path, $callback);
    }

    /**
     * @param string $path
     * @param callable|array|string $callback
     * @return RouteAction
     */
    public static function put(string $path, $callback)
    {
        return Application::$app->router->put($path, $callback);
    }

    /**
     * @param string $path
     * @param callable|array|string $callback
     * @return RouteAction
     */
    public static function delete(string $path, $callback)
    {
        return Application::$app->router->delete($path, $callback);
    }

    /**
     * @param array $attributes
     * @param \Closure $callback
     */
    public static function group(array $attributes, \Closure $callback)
    {
        Application::$app->router->group($attributes, $callback);
    }
}
