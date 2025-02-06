<?php

namespace Exan\PoopExpress;

class PoopExpress
{
    private array $parts = [];

    public function __construct(
        private readonly string $method,
        string $uri,
    ) {
        $this->parts = explode('/', substr($uri, 1));
    }

    public function attempt(array $uri, array $handlers): bool
    {
        foreach ($uri as $key => $part) {
            if ($part === '*') {
                $wildcards[] = $this->parts[$key];
                continue;
            }

            if ($part !== $this->parts[$key]) {
                return false;
            }
        }

        ($handlers[$this->method] ?? function () {
            http_response_code(405);
            echo '405';
        })(...($wildcards ?? []));

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
}
