<?php

namespace Tahir\Cookies;

class Cookie
{
    private $app;
    private $config = [

        'name' => '__tahir_cookie__',
        'expires' => 3600,
        'path' => '/',
        'domain' => 'localhost',
        'secure' => false,
        'httponly' => true

    ];


    public function __construct($app)
    {
        $this->app = $app;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function getExpiration(): int
    {
        return (isset($this->getConfig()['expires']) ? filter_var($this->getConfig()['expires'], FILTER_VALIDATE_INT) : 0);
    }

    public function getPath(): string
    {
        return (isset($this->getConfig()['path']) ? filter_var($this->getConfig()['path'], FILTER_SANITIZE_STRING) : '/');
    }

    public function getDomain(): string
    {
        return ($this->getConfig()['domain'] ?? $_SERVER['SERVER_NAME']);
    }

    public function isSecure(): bool
    {
        return ($this->getConfig()['secure'] ?? isset($_SERVER['HTTPS']));
    }

    public function isHttpOnly(): bool
    {
        return ($this->getConfig()['httpOnly'] ?? true);
    }

    public function getCookieName(): string
    {
        return ($this->getConfig()['name'] ?? '');
    }




    public function has($key)
    {
        return isset($_COOKIE[$key]);
    }

    public function set($key,$value)
    {
        setcookie($key, $value, $this->getExpiration(),
        $this->getPath(), $this->getDomain(),
        $this->isSecure(), $this->isHttpOnly());
    }

    public function get($key)
    {
        if ($this->has($key))
        {
            return $_COOKIE[$key];
        }
        return false;
    }

    public function delete($key)
    {
        if ($this->has())
        {
            setcookie($key, '', (time() - 3600), $this->getPath(), $this->getDomain(), $this->isSecure(), $this->isHttpOnly());
        }
    }

    public function invalidate()
    {
        foreach ($_COOKIE as $name => $value)
        {
            $this->delete($name);
        }
    }
}