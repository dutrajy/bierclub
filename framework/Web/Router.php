<?php

namespace Commercial\Framework\Web;

use ReflectionClass;
use Commercial\Framework\Core\Inspector;

// Based on https://github.com/nikic/FastRoute
class Router
{
    const MIN_GROUPS = 5;

    protected $routes = [];
    protected $uris = [];

    public function addController($controller, $prefix = "") {
        $reflector = new ReflectionClass($controller);

        foreach ($reflector->getMethods() as $action) {
            $comment = $action->getDocComment();
            [$annotations, $values] = Inspector::parse($comment);

            $methods = ["GET", "HEAD", "POST", "PUT", "DELETE", "CONNECT", "OPTIONS", "TRACE"];

            foreach ($annotations as $index => $annotation) {
                if (in_array(strtoupper($annotation), $methods)) {
                    $this->addRoute(
                        strtoupper($annotation),
                        $prefix . $values[$index],
                        $controller . "::" . $action->getName()
                    );
                } elseif ($annotation === "Any") {
                    $this->addRoute(
                        $methods,
                        $prefix . $values[$index],
                        $controller . "::" . $action->getName()
                    );
                }
            }
        }
    }

    public function addRoute($methods, $uri, $handler)
    {
        $methods = \is_string($methods) ? [$methods] : $methods;

        $uri = \preg_replace("~\{[[:alpha:]][[:alnum:]]*\}~xu", "([^/]+)", $uri, -1, $count);

        $this->routes[] = [
            "methods" => $methods,
            "uri" => $uri,
            "variables" => $count,
            "handler" => $handler,
        ];

        $this->uris[] = $uri . str_repeat("()", static::MIN_GROUPS + (count($this->uris) - $count));
    }

    public function getRegex()
    {
        return "~^(?|" . implode("|", $this->uris) . ")$~ux";
    }

    public function dispatch($request)
    {
        $method = $request->getMethod();
        $uri = $request->getUri();

        if (!preg_match($this->getRegex(), $uri, $matches)) {
            return "404 Not Found";
        }


        $route = $this->routes[count($matches) - (static::MIN_GROUPS + 1)];
        $variables = array_slice($matches, 1, $route["variables"]);

        if (in_array($method, $route["methods"])) {
            if (is_string($route["handler"])) {
                [$controller, $action] = explode("::", $route["handler"]);
                return (new $controller)->$action($request, ...$variables);
            } else {
                return $route["handler"]($request, ...$variables);
            }
        }

        return "Not Dispatched!";
    }
}
