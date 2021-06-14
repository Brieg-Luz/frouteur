<?php
namespace technique\routeur;

use routeur\routerException\routeurException;

class route {
    public string $name;
    public string $method;
    public string $path;
    public $function;
    public array $matches;

    public function __construct(string $name, string $path, string $method, callable $function)
    {
        $this->name = $name;
        $this->method = $method;
        $this->path = trim($path, '/');
        $this->function = $function;
    }

    public function verifier(string $url) {
        $url = trim($url, '/');
        if (($_SERVER['REQUEST_METHOD'] == "POST" && preg_match("#(^POST$){1}|(^POST\|GET$){1}|(^GET\|POST$){1}#", $this->method)) || ($_SERVER['REQUEST_METHOD'] == "GET" && preg_match("#(^GET$){1}|(^POST\|GET$){1}|(^GET\|POST$){1}#", $this->method))) {
            $path = preg_replace("#:([\w]+)#", '([^/]+)', $this->path);
            $regexPath = "#^$path$#i";
            if (!preg_match($regexPath, $url, $matches)) {
                return false;
            } else {
                array_shift($matches);
                $this->matches = $matches;
                return true;
            }
            return true;
        } else {
            echo "off";
            return false;
        }
    }

    public function execute() {
        return call_user_func_array($this->function, $this->matches);
    }
}