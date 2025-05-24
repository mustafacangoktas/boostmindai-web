<?php

class Router
{
    private array $routes = [];

    public function get($pattern, $callback): void
    {
        $this->addRoute('GET', $pattern, $callback);
    }

    public function post($pattern, $callback): void
    {
        $this->addRoute('POST', $pattern, $callback);
    }

    public function put($pattern, $callback): void
    {
        $this->addRoute('PUT', $pattern, $callback);
    }

    public function delete($pattern, $callback): void
    {
        $this->addRoute('DELETE', $pattern, $callback);
    }

    private function addRoute($method, $pattern, $callback): void
    {
        $this->routes[$method][] = ['pattern' => $pattern, 'callback' => $callback];
    }

    public function dispatch($method, $uri): void
    {
        $supported = getSupportedLanguages();
        $preferred = getPreferredLanguage();

        $parts = explode('/', trim($uri, '/'));
        $urlLang = (!empty($parts[0]) && in_array($parts[0], $supported)) ? $parts[0] : 'en';

        // Handle both language-prefixed and non-prefixed URLs
        $cleanUri = removeLanguagePrefixFromUri($uri);

        if ($preferred !== $urlLang) {
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

    private function tryDispatchRoute($method, $uri): bool
    {
        foreach ($this->routes[$method] ?? [] as $route) {
            $pattern = "#^" . preg_replace('#\{([a-zA-Z0-9_]+)\}#', '(?P<\1>[^/]+)', $route['pattern']) . "$#";
            if (preg_match($pattern, $uri, $params)) {
                call_user_func_array($route['callback'], array_filter($params, 'is_string', ARRAY_FILTER_USE_KEY));
                return true;
            }
        }
        return false;
    }
}
