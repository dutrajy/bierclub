<?php

namespace Commercial\Application\Sales;

use Commercial\Application\Products\Product;

class Item extends \Commercial\Framework\Database\Record
{
    /** @Property */
    protected $quantity;

    /** @Property */
    protected $saleId;

    protected $sale;

    /** @Property */
    protected $productId;

    protected $product;

    public function getSale()
    {
        if (!$this->sale) {
            $this->sale = Sale::findOne(["id" => $this->saleId]);
        }

        return $this->sale;
    }

    public function setSale($sale)
    {
        $this->saleId = $sale->getId();
        $this->sale = $sale;

        return $this;
    }

    public function getProduct()
    {
        if (!$this->product) {
            $this->product = Product::findOne(["id" => $this->getProductId()]);
        }

        return $this->product;
    }

    public function setProduct($product)
    {
        $this->productId = $product->getId();
        $this->product = $product;
    }

    public static function getTable()
    {
        return "sale_items";
    }
}
