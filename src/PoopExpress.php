<?php

namespace Exan\PoopExpress;

class PoopExpress
{
    private bool $routeMatch = false;

    private array $parts = [];

    public function __construct(
        private readonly string $method,
        string $uri,
    ) {
        $this->parts = explode('/', $uri);
        array_shift($this->parts);
    }

    public function attempt(string $method, array $uri, callable $handler): bool
    {
        if (count($this->parts) !== count($uri)) {
            return false;
        }

        foreach ($uri as $key => $part) {
            if ($part === '*') {
                $wildcards[] = $this->parts[$key];
                continue;
            }

            if ($part !== $this->parts[$key]) {
                return false;
            }
        }

        if ($method !== $this->method) {
            $this->routeMatch = true;
            return false;
        }

        $handler(...($wildcards ?? []));
        return true;
    }

    public function group(array $uri): bool
    {
        foreach ($uri as $key => $part) {
            if ($part === '*') {
                continue;
            }

            if ($part !== $this->parts[$key]) {
                return false;
            }
        }

        return true;
    }

    public function default()
    {
        $code = $this->routeMatch ? 405 : 404;

        http_response_code($code);

        echo $code;
    }
}
