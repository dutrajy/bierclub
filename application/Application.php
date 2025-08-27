<?php

namespace Commercial\Application;

use Commercial\Framework\Http\Request;
use Commercial\Framework\Web\Router;
use ReflectionClass;
use Commercial\Framework\Core\Inspector;

class Application extends \Commercial\Framework\Application
{
    public function process(Request $request)
    {
        $router = new Router();

        foreach ($this->getClasses() as $class) {
            $reflector = new ReflectionClass($class);
            $comment = $reflector->getDocComment();
            [$annotations, $values] = Inspector::parse($comment);

            if (in_array("Controller", $annotations)) {
                $router->addController($class, $values[0]);
            }
        }

        return $router->dispatch($request);
    }
}
