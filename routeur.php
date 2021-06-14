<?php
use technique\routeur\route;
use technique\routeur\routerException;

namespace technique\routeur;

class routeur
{
    public $routes = [];
    private $url;

    /**
     * __construct
     *
     * @param  mixed $url correspond à l'url avec laquelle on a appelée la page
     * @return void
     */

    public function __construct(string $url)
    {
        $url = preg_replace('#([?\#]{1}[\w=&]+)#', '', $url);
        $this->url = $url;
    }

    /**
     * listen
     *
     * @param  mixed $path the path of the route
     * @param  mixed $method the accepted method of the request, it can be "POST", "GET" or "POST|GET" (and vice-versa) 
     * @param  mixed $function the function called if the road match
     * @return void
     */

    public function chemin(string $name, string $method, string $path, callable $function)
    {
        if (preg_match("#(^POST$){1}|(^GET$){1}|(^POST\|GET$){1}|(^GET\|POST$){1}$#", $method)) {
            $route = new route($name, $path, $method, $function);
            $this->routes[] = $route;
        }
    }

    public function engager() {
        foreach ($this->routes as $route) {
            if ($route->verifier($this->url)) {
                $this->page = $route->name;
                $route->execute();
                return true;
            }
        }
        return false;
    }

    public function hypertexte($name) {
        foreach ($this->routes as $route) {
            if ($route->name == $name) {
                return '/' . $route->path;
            }
        }
    }
}
