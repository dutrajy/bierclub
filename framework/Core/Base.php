<?php

namespace Commercial\Framework\Core;

use ReflectionProperty;
use Commercial\Framework\Lang\Strings;

class Base implements \JsonSerializable
{
    public function __construct($data = null)
    {
        $this->fromAssociativeArray($data);
    }

    public function fromAssociativeArray($data)
    {
        if (\is_array($data) || \is_object($data)) {
            foreach ($data as $key => $value) {
                $key = Strings::camel($key);

                $reflector = new ReflectionProperty(static::class, $key);
                $comment = $reflector->getDocComment();
                [$annotations, $values] = Inspector::parse($comment);

                if (in_array("Write", $annotations) || in_array("Property", $annotations)) {
                    $this->{"set" . \ucfirst($key)}($value);
                } else {
                    $this->$key = $value;
                }
            }
        }
    }

    public function __set($name, $value)
    {
        $name = \ucfirst($name);
        $method = "set{$name}";
        $this->$method($value);
    }

    public function __get($name)
    {
        $name = \ucfirst($name);
        $method = "get{$name}";
        return $this->$method();
    }

    public function __call($name, $arguments)
    {
        if (strpos($name, "set") === 0) {
            $property = lcfirst(substr($name, 3));

            $reflector = new ReflectionProperty(static::class, $property);
            $comment = $reflector->getDocComment();
            [$annotations, $values] = Inspector::parse($comment);

            if (in_array("Property", $annotations) || in_array("Write", $annotations)) {
                $this->$property = $arguments[0];
                return $this;
            }
        } elseif (strpos($name, "get") === 0) {
            $property = lcfirst(substr($name, 3));

            $reflector = new ReflectionProperty(static::class, $property);
            $comment = $reflector->getDocComment();
            [$annotations, $values] = Inspector::parse($comment);

            if (in_array("Property", $annotations) || in_array("Read", $annotations)) {
                return $this->$property;
            }
        }
    }

    public function getWriteableData()
    {
        $properties = get_object_vars($this);

        foreach ($properties as $key => $value) {

            $reflector = new ReflectionProperty(static::class, $key);
            $comment = $reflector->getDocComment();
            [$annotations, $values] = Inspector::parse($comment);

            if (in_array("Write", $annotations) || in_array("Property", $annotations)) {
                if ($value || empty($value)) {
                    $associativeArray[Strings::snake($key)] = $value;
                }
            }
        }

        return $associativeArray ?? [];
    }

    public function getReadableData()
    {
        $properties = get_object_vars($this);

        foreach ($properties as $key => $value) {

            $reflector = new ReflectionProperty(static::class, $key);
            $comment = $reflector->getDocComment();
            [$annotations, $values] = Inspector::parse($comment);

            if (in_array("Read", $annotations) || in_array("Property", $annotations)) {
                if ($value || empty($value)) {
                    $associativeArray[Strings::snake($key)] = $value;
                }
            }
        }

        return $associativeArray ?? [];
    }

    public function jsonSerialize()
    {
        return $this->getReadableData();
    }
}
