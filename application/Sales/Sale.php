<?php

namespace Commercial\Application\Sales;

use Commercial\Application\Products\Product;
use Commercial\Application\Common\Creatable;

class Sale extends \Commercial\Framework\Database\Record
{
    /** @Property */
    protected $observations;

    /** @Property */
    protected $total = 0;

    protected $items = [];

    use Creatable;

    public function addProduct(Product $product, int $quantity)
    {
        $item = new Item();
        $item->setProduct($product);
        $item->setQuantity($quantity);

        $this->items[] = $item;

        $this->total += $product->getPrice() * $quantity;
    }

    public function getItems()
    {
        if (empty($this->items)) {
            $this->items = Item::findAll(["sale_id" => $this->getId()]);
        }

        return $this->items;
    }

    public function insert()
    {
        parent::insert();
        foreach ($this->items as $item) {
            $item->setSale($this);
            $item->insert();
        }
    }
}
