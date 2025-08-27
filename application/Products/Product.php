<?php

namespace Commercial\Application\Products;

use Commercial\Application\Common\Base;
use Commercial\Framework\Lang\Strings;

class Product extends Base
{
    /** @Property */
    protected $categoryId;
    
    /** @Property */
    protected $ean;

    /** @Property */
    protected $name;

    /** @Property */
    protected $image;

    /** @Property */
    protected $price;

    /** @Property */
    protected $description;

    /** @Property */
    protected $active;


    public static function sanitize(array $data) : array
    {
        $sanitized['ean'] = Strings::clean($data['ean']);

        $sanitized['name'] = \filter_var(
            $data['name'],
            FILTER_SANITIZE_STRING
        );

        $sanitized['price'] = str_replace(".", "", $data['price']);
        $sanitized['price'] = str_replace(",", ".", $sanitized['price']);
        $sanitized['price'] = floatval($sanitized['price']);

        $sanitized['description'] = \filter_var(
            $data['description'],
            FILTER_SANITIZE_STRING
        );

        return $sanitized;
    }
}
