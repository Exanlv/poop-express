<?php

class PoopExpress
{
    private bool $routeMatch = false;

    public function __construct(
        private readonly string $method,
        private readonly string $uri
    ) {
    }

    public function attempt(string $method, string $uri, callable $handler): bool
    {
        $routeMatch = preg_match($uri, $this->uri, $matches);
        if ($routeMatch) {
            if ($method === $this->method) {
                array_shift($matches);

                $handler(...$matches);
                return true;
            }

            $this->routeMatch = true;
        }

        return false;
    }

    public function group(string $uri): bool
    {
        return preg_match($uri, $this->uri);
    }

    public function default()
    {
        $code = $this->routeMatch ? 405 : 404;

        http_response_code($code);

        echo $code;
    }
}
