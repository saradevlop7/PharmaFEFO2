<?php
class Router {
    private array $routes = [];

    public function get(string $path, array $handler): void {
        $this->routes[] = ['GET', $path, $handler];
    }
    public function post(string $path, array $handler): void {
        $this->routes[] = ['POST', $path, $handler];
    }
    public function patch(string $path, array $handler): void {
        $this->routes[] = ['PATCH', $path, $handler];
    }

    public function dispatch(string $method, string $uri): void {
        $uri = strtok($uri, '?'); // strip query string
        foreach ($this->routes as [$rMethod, $rPath, $handler]) {
            $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $rPath);
            if ($method === $rMethod && preg_match("#^{$pattern}$#", $uri, $matches)) {
                array_shift($matches);
                [$class, $action] = $handler;
                require_once dirname(__DIR__) . "/Controller/{$class}.php";
                $className  = basename(str_replace('\\', '/', $class));
                $controller = new $className();
                $controller->$action(...$matches);
                return;
            }
        }
        http_response_code(404);
        echo json_encode(['error' => '404 Not Found']);
    }

    public static function isApiRoute(string $uri): bool {
        return str_starts_with($uri, '/api/');
    }
}
