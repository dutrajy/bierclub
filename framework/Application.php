<?php

namespace Commercial\Framework;

use ReflectionClass;
use Commercial\Framework\Core\Inspector;
use Commercial\Framework\Lang\Strings;

class Application
{
    protected $directory;

    public function getClasses()
    {
        $iterator = new \RecursiveDirectoryIterator($this->directory);
        $iterator = new \RecursiveIteratorIterator($iterator);
        $iterator = new \RegexIterator($iterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);

        foreach ($iterator as $class) {
            $class[0] = Strings::removePrefix($class[0], $this->directory);
            $class[0] = str_replace("/", "\\", $class[0]);
            $class[0] = Strings::removeSuffix($class[0], ".php");
            $classes[] = "\\Commercial\\Application\\" . $class[0];
        }

        return $classes ?? [];
    }

    public function setDirectory($path)
    {
        $this->directory = $path;
    }
}
