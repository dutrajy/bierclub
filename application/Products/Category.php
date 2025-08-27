<?php

namespace Commercial\Application\Products;

class Category extends \Commercial\Application\Common\Base
{
    /** @Property */
    protected $name;
    
    /** @Property */
    protected $description;
    
    protected $products = [];

    public function getProducts()
    {
        if (empty($this->products)) {
            $this->products = Product::findAllByCategoryId($this->getId());
        }

        return $this->products;
    }

    public function addProduct(Product $product)
    {
        $product->setCategoryId($this->getId());
    }

    public static function getTable()
    {
        return "categories";
    }
}