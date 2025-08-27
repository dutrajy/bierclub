<?php

namespace Commercial\Application\Products;

/** @Controller("/api") */
class Api extends \Commercial\Application\Common\Controller
{
    /** @Get("/products") */
    public function list()
    {
        return Product::findAll(["active" => 1]);
    }

    /** @Get("/categories") */
    public function listCategories()
    {
        return Category::findAll();
    }
}
