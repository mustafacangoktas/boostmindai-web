<?php
class Router
{
    private array $routes = [];

    /**
     * Add a GET route to the router.
     *
     * @param string $pattern The route pattern, which can include parameters.
     * @param callable $callback The callback function to handle the request.
     */
    public function get(string $pattern, callable $callback): void
    {
        $this->addRoute('GET', $pattern, $callback);
    }

    /**
     * Add a POST route to the router.
     *
     * @param string $pattern The route pattern, which can include parameters.
     * @param callable $callback The callback function to handle the request.
     */
    public function post(string $pattern, callable $callback): void
    {
        $this->addRoute('POST', $pattern, $callback);
    }

    /**
     * Add a PUT route to the router.
     *
     * @param string $pattern The route pattern, which can include parameters.
     * @param callable $callback The callback function to handle the request.
     */
    public function put(string $pattern, callable $callback): void
    {
        $this->addRoute('PUT', $pattern, $callback);
    }

    /**
     * Add a DELETE route to the router.
     *
     * @param string $pattern The route pattern, which can include parameters.
     * @param callable $callback The callback function to handle the request.
     */
    public function delete(string $pattern, callable $callback): void
    {
        $this->addRoute('DELETE', $pattern, $callback);
    }

    /**
     * Add a route to the router.
     *
     * @param string $method The HTTP method (GET, POST, etc.).
     * @param string $pattern The route pattern, which can include parameters.
     * @param callable $callback The callback function to handle the request.
     */
    private function addRoute(string $method, string $pattern, callable $callback): void
    {
        $this->routes[$method][] = ['pattern' => $pattern, 'callback' => $callback];
    }

    /**
     * Dispatch the request to the appropriate route based on the HTTP method and URI.
     *
     * @param string $method The HTTP method (GET, POST, etc.).
     * @param string $uri The request URI.
     */
    public function dispatch(string $method, string $uri): void
    {
        $supported = getSupportedLanguages();
        $preferred = getPreferredLanguage();

        $parts = explode('/', trim($uri, '/'));
        $urlLang = (!empty($parts[0]) && in_array($parts[0], $supported)) ? $parts[0] : 'en';

        // Handle both language-prefixed and non-prefixed URLs
        $cleanUri = removeLanguagePrefixFromUri($uri);

        if ($preferred !== $urlLang && (!str_starts_with($uri, '/api'))) {
            $redirectUri = $preferred === 'en' ? $cleanUri : '/' . $preferred . $cleanUri;
            header('Location: ' . $redirectUri, true, 302);
            exit;
        }

        // First try exact match with the URI as is
        if ($this->tryDispatchRoute($method, $uri)) {
            return;
        }

        // Then try with the language prefix removed
        if ($uri !== $cleanUri && $this->tryDispatchRoute($method, $cleanUri)) {
            return;
        }

        // If no routes match, show 404
        http_response_code(404);
        require __DIR__ . '/../pages/404.php';
    }

    /**
     * Try to dispatch the route based on the method and URI.
     *
     * @param string $method The HTTP method (GET, POST, etc.).
     * @param string $uri The request URI.
     * @return bool True if a route was matched and dispatched, false otherwise.
     */
    private function tryDispatchRoute(string $method, string $uri): bool
    {
        foreach ($this->routes[$method] ?? [] as $route) {
            // Support {param:regex} as well as {param}
            $pattern = preg_replace_callback(
                '#\{([a-zA-Z0-9_]+)(?::([^}]+))?\}#',
                function ($matches) {
                    $name = $matches[1];
                    $regex = $matches[2] ?? '[^/]+';
                    return "(?P<$name>$regex)";
                },
                $route['pattern']
            );
            $pattern = "#^" . $pattern . "$#";
            if (preg_match($pattern, $uri, $params)) {
                call_user_func_array($route['callback'], array_filter($params, 'is_string', ARRAY_FILTER_USE_KEY));
                return true;
            }
        }
        return false;
    }
}
