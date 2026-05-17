<?php

namespace Core;

class Router
{
    protected array $routes = [];
    public Request $request;
    public Response $response;
    protected array $groupStack = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @param string $path
     * @param callable|array|string $callback
     * @return RouteAction
     */
    public function get(string $path, $callback)
    {
        $path = $this->applyGroupPrefix($path);
        $this->routes['get'][$path] = [
            'callback' => $callback,
            'middlewares' => $this->getGroupMiddlewares()
        ];
        return new RouteAction($this, 'get', $path);
    }

    /**
     * @param string $path
     * @param callable|array|string $callback
     * @return RouteAction
     */
    public function post(string $path, $callback)
    {
        $path = $this->applyGroupPrefix($path);
        $this->routes['post'][$path] = [
            'callback' => $callback,
            'middlewares' => $this->getGroupMiddlewares()
        ];
        return new RouteAction($this, 'post', $path);
    }

    /**
     * @param string $path
     * @param callable|array|string $callback
     * @return RouteAction
     */
    public function put(string $path, $callback)
    {
        $path = $this->applyGroupPrefix($path);
        $this->routes['put'][$path] = [
            'callback' => $callback,
            'middlewares' => $this->getGroupMiddlewares()
        ];
        return new RouteAction($this, 'put', $path);
    }

    /**
     * @param string $path
     * @param callable|array|string $callback
     * @return RouteAction
     */
    public function delete(string $path, $callback)
    {
        $path = $this->applyGroupPrefix($path);
        $this->routes['delete'][$path] = [
            'callback' => $callback,
            'middlewares' => $this->getGroupMiddlewares()
        ];
        return new RouteAction($this, 'delete', $path);
    }

    /**
     * @param array $attributes
     * @param \Closure $callback
     */
    public function group(array $attributes, \Closure $callback)
    {
        $this->groupStack[] = $attributes;
        $callback();
        array_pop($this->groupStack);
    }

    protected function applyGroupPrefix(string $path): string
    {
        $prefix = '';
        foreach ($this->groupStack as $group) {
            if (isset($group['prefix'])) {
                $prefix .= '/' . trim($group['prefix'], '/');
            }
        }
        
        if ($prefix) {
            $path = '/' . trim($prefix, '/') . '/' . trim($path, '/');
            $path = preg_replace('/\/+/', '/', $path);
        }
        
        return $path;
    }

    protected function getGroupMiddlewares(): array
    {
        $middlewares = [];
        foreach ($this->groupStack as $group) {
            if (isset($group['middleware'])) {
                $groupMiddleware = $group['middleware'];
                if (is_array($groupMiddleware)) {
                    $middlewares = array_merge($middlewares, $groupMiddleware);
                } else {
                    $middlewares[] = $groupMiddleware;
                }
            }
        }
        return $middlewares;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $route = $this->routes[$method][$path] ?? false;

        if ($route === false) {
            abort(404, '404 Halaman Tidak Ditemukan');
        }

        $callback = $route['callback'];

        // Eksekusi middleware pada level route
        foreach ($route['middlewares'] as $middlewareClass) {
            $middleware = new $middlewareClass();
            $middleware->execute();
        }

        if (is_string($callback)) {
            return $this->renderView($callback);
        }

        if (is_array($callback)) {
            /** @var \Core\Controller $controller */
            $controller = Application::$app->make($callback[0]);
            Application::$app->controller = $controller;
            $controller->action = $callback[1];
            $callback[0] = $controller;

            // Dukungan opsional jika middleware masih didefinisikan di controller
            if (method_exists($controller, 'getMiddlewares')) {
                foreach ($controller->getMiddlewares() as $middleware) {
                    if (empty($middleware->actions) || in_array($controller->action, $middleware->actions)) {
                        $middleware->execute();
                    }
                }
            }
        }

        return call_user_func($callback, $this->request, $this->response);
    }

    /**
     * @param string $method
     * @param string $path
     * @param string|array $middleware
     */
    public function addMiddleware(string $method, string $path, $middleware)
    {
        if (is_array($middleware)) {
            $this->routes[$method][$path]['middlewares'] = array_merge($this->routes[$method][$path]['middlewares'], $middleware);
        } else {
            $this->routes[$method][$path]['middlewares'][] = $middleware;
        }
    }

    public function renderView($view, $params = [])
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        $viewFile = Application::$ROOT_DIR . "/app/Views/$view.php";
        if (file_exists($viewFile)) {
            include_once $viewFile;
        } else {
            return "View $view not found!";
        }
        return ob_get_clean();
    }
}

class RouteAction
{
    private Router $router;
    private string $method;
    private string $path;

    public function __construct(Router $router, string $method, string $path)
    {
        $this->router = $router;
        $this->method = $method;
        $this->path = $path;
    }

    /**
     * @param string|array $middleware
     * @return $this
     */
    public function middleware($middleware)
    {
        $this->router->addMiddleware($this->method, $this->path, $middleware);
        return $this;
    }
}
